#include "Kohonen.h"
#include <stdlib.h>
#include <stdio.h>
#include <unistd.h>
#include <string.h>
#include <math.h>
#include <time.h>

void use_free_neuron(int nr, int ncl, int nf, int * classe, double ** m, double ** w);
void  ordmax(double * v, double * ord, int n);

int  salvaMatrice (char * nomeFile, int nr, int nc, double ** m)  {

		int r, c;

		FILE        *uscita;

		uscita=fopen(nomeFile,"w");

		if(uscita==NULL)

			return (-1);

		for (r = 0; r < nr;r++) 

			for (c = 0; c < nc; c++) 

				fprintf (uscita, "%f\n", m[r][c]);

		if (DEBUG == 1) printf ("MATRICE %s CREATA E SALVATA \n", nomeFile);

		fclose (uscita); 

		return (0); 

}

					

					

	

	// FINE Funzioni per il salvataggio di matrici

    

    

    

int salvaVettore (char * nomeFile, int nr,  int * m)  {

		int r;

		FILE        *uscita;

		uscita=fopen(nomeFile,"w");

		if(uscita==NULL)

			return (-1);

		for (r = 0; r < nr;r++) 

					fprintf (uscita, "%d\n", m[r]);

		if (DEBUG == 1) printf ("Vettore %s CREATO E SALVATO \n", nomeFile);

		fclose (uscita);

		return (0); 

}

// Parametri:
// nc: numero di classe
// numcicli: numero di epoche algoritmo parallelo
// m3: matrice documento - termine (contiene solo i valori non contiene la prima righa con i nomi delle colonne; non contiene i nomi dei documenti (nella prima colonna di ogni riga)): Dimensione: numeroRighePerParte X nfea
// smin: matrice documento - documento (contiene solo i valori): Dimensione numeroRighePerParte X nr. Pu� diventare quadrata se numeroRighePerParte = nr
// w: matrice dei pesi: dimensione nr X nc
// nfea: numero colonne matrice documento - termine
// classe: vettore contenente la classe di appartenenza di ogni documento. Dimensione numeroRighePerParte
// numeroRighePerParte: numero di documenti da classificare. Per eseguire l'algoritmo sequenzialmente porre numeroRighePerParte = nr


void ckC2PerParOtt (double eta, double teta, int recombine_step, int nc, int  numcicli, int nr, double ** m3, double ** smin,  double ** w, int nfea, int * classe, int numeroRighePerParte) {
		double * nw = (double *) malloc (nc * sizeof (double));
		double * nwd = (double *) malloc (nc * sizeof (double));
		double * nws = (double *) malloc (nc * sizeof (double));
		double * ord = (double *) malloc (nc * sizeof (double));
		double * ordz = (double *) malloc (nc * sizeof (double));
		int ciclo, i, j, k, h;
		int jkp = 0; 		// Usato per il recombine steps. Controllare il valore iniziale		
		double kk;
		int imax = nr;int cmax = nc; int ivmax = 1; 
		for (i = 0; i < cmax; i ++) {
        	nwd [i] = 0.0;
        	nws [i] = 0.0;
    	}
    	if (DEBUG == 1) {
    		printf ("DATI DENTRO CKC2PERPAROTT \n");
    		printf ("NUM CICLI = %d \n", numcicli);
    		printf (" NUM RIGHE PER PARTE = %d \n", numeroRighePerParte);
    		printf ("NUMERO DI CLASSI = %d \n", cmax);
    		printf ("Numero colonne = %d \n", imax);
    	}
		//' *****************     INIZIO DELL'ALGORITMO        *******************
		for (ciclo = 1; ciclo <=  numcicli; ciclo++) {           //conta i cicli
			if (DEBUG == 1) printf ("Ciclo in kohonen per versione parallela  = %d \n",  ciclo);
    		for (i = 0; i < numeroRighePerParte; i++) { // scorro gli elementi in esame
			int iz ;
        		for (iz= 0; iz <nc; iz++){             // azzero il vettore ord
            			ord[iz] = 0;
			}
				// ********* CALCOLA IL NEURONE VINCENTE **********
			  	if (ciclo == numcicli)  teta = 0;      //se sono all' ultimo ciclo il teta � zero
    			for (j = 0; j < cmax; j++) {         //scorro le classi
        			nw[j] = 0.0;
        			//Ancora da capire. Prima c'era un if con Text10 controllo inesistente
        			nwd[j] = 0.0;
        			nws [j] = 0.0;
        			if (nwd[j] < 0)  nwd[j] = 0.001;
            		for (k = 0; k < imax; k++) {
            			nwd[j] = nwd[j] + (smin[i] [k] - w[k] [j]) * (smin[i][k] - w[k][j]);
            			nws[j] = nws[j] + smin[i] [k] * smin[i] [k];
		          	}
			        nwd[j] = sqrt (nwd[j]);
        			nws[j] = sqrt (nws[j]);
        			nw[j] = (nws[j] * 1.0) / (nwd[j] * 1.0);
    			}
    			ordmax(nw, ord, cmax);
    			double dwx;
    			if (ciclo != numcicli) {
					//'***** INCREMENTA PESI TRA INPUT I E NEURONE VINCENTE
					for (h = 0; h < cmax; h++) {
    					kk = 0;
					    if (ord[h] <= ivmax) kk = 1; else kk = 1.0 / (1.0 * (ord[h] * ord[h]));
				    	for (k = 0; k < imax; k++) {
        					dwx = smin [i] [k] - w[k] [h];
        					w [k] [h] = w [k] [h] + kk * eta * dwx;
    					}
					}
				}
				int izy;
				for (izy = 0; izy <  cmax; izy ++) 
					if (ord [izy] == 1)  classe[i] = izy;
			} // Fine ciclo che scorre elementi in esame 
			jkp = jkp + 1;
			if (jkp == recombine_step) {
				use_free_neuron (numeroRighePerParte, nc, nfea, classe, m3, w);
				jkp = 0;
			}
		} // Fine ciclo per ciclo da 1 al numero di cicli
		if (DEBUG == 1) printf ("HO FINITO I CICLI DI KOHONEN IN CKC2PERPAROTT\n"); 
		char * nomeFile = "ParteClassi.txt";
		if (DEBUG == 1) printf ("CHIAMO SALVA VETTORE IN CKC2PERPAROTT\n");
		salvaVettore (nomeFile, numeroRighePerParte, classe );
		nomeFile = "./Prova/MatWPar.txt";
		if (DEBUG == 1) printf ("CHIAMO SALVA MATRICe IN CKC2PERPAROTT\n");
		salvaMatrice (nomeFile, nr, nc, w);
		
		free(nw);
		free(nwd);
		free(nws);
		free(ord);
		free(ordz);
}

double   statm (double * X, int n) {
		double mx = 0.0;
		double mxf = 0.0;
		int i;
		double txm;
		for (i = 0; i < n; i++)
    		mx = mx + X[i];
		txm = mx * 1.0 / n;
		return txm; 
}



double  statds(double * X, double xm, int n) {
		double mxf = 0.0;
		int i;
		double txqm;
		for (i = 0; i < n; i++)
    		mxf = mxf + (X[i] - xm) * (X[i] - xm);
		txqm = sqrt (mxf * 1.0 / n);
		return txqm;
}


void use_free_neuron(int nr, int ncl, int nf, int * classe, double ** m, double ** w) {
		// IL centroide e' indicizzato (secondo indice  con il numero di features
		// Se utilizzo la matrice s il centroide e' pure indicizzato con nr
		double ** centroide  = (double **) malloc (ncl * sizeof (double * )); 
		int ff;
		for (ff = 0; ff < ncl; ff++){
			centroide [ff] = (double *) malloc (nf * sizeof (double));
		}
		double **distcentro = (double **) malloc (ncl * sizeof (double * )); 
		int ff2;
		for (ff2 = 0; ff2 < ncl; ff2++){
			distcentro [ff2] = (double *) malloc (nr * sizeof (double));
		}
		
		// Controllare i tipi
		double * vet = (double *) malloc (nr * sizeof (double));
		double * riduzione = (double *) malloc (ncl * sizeof (double));
		double * ncasi = (double *) malloc (ncl  * sizeof (double));
		double  * nfree = (double *) malloc (ncl * sizeof (double));
		double  * nrid = (double *) malloc (ncl * sizeof (double));

		int n = ncl;
		int ncasi_attuali = nr;
		int nfeatures_attuali = nf;
		int nfeatures_iniziali = nf;
		int ncasi_iniziali = nr;
		int nc; 
		int i, j;
		int k; 
		int m1;
		double ridmax;
		// Controllare il tipo
		int nlib, nmax;
		// char *sz;
		int i1;
		double vetm, vetxqm;
		for (j = 0; j < n; j++)
		// Era nr
			for (i = 0; i < nr; i++) {
				//centroide [j] [i] = 0;
				distcentro [j] [i] = 0;
			}
		for (j = 0; j < n; j++) {
			nc = 0;
			for (i = 0; i <  nfeatures_iniziali; i++)
				centroide [j] [i] = 0.0;
			for (i = 0; i <  ncasi_attuali; i++)
    			if (classe [i] == j) {
    				nc = nc + 1;
    				// k = invcc [i];
    				// invcc  � una funzione che restituisce lindice dell'iesimo
    				// elemento selezionato
    				// Ricontrollare
    				k = i; 
    				for (m1 = 0; m1 < nfeatures_iniziali; m1++)
        					centroide[j] [m1] = centroide [j] [m1] + m[k] [m1];
    			}
			ncasi [j] = nc;
			for (m1 = 0; m1 < nfeatures_iniziali; m1 ++)
    			if (nc != 0)
    				centroide [j] [m1] = centroide [j] [m1] / nc;
		}
		do {
			ridmax = 0;
			nlib = 0;
			nmax = 0;
			for (j = 0; j < n; j++) { 	//  Numero di classi
    			// sz = j + " ";
    			nc = 0;
    			for (i = 0; i <  ncasi_attuali; i ++) {
        			distcentro [j] [i] = 0.0;
        			if (classe [i] == j) {
            			nc = nc + 1;
            			// invcc funzione che restituisce l'indice degli elementi presi
            			// k = invcc[i];
            			k = i;
            			for (m1 = 0; m1 < nfeatures_iniziali; m1 ++)
                			distcentro [j] [i] = distcentro [j] [i] + (m[k] [m1] - centroide [j] [m1]) * (m[k] [m1] - centroide [j] [m1]);
            			distcentro [j] [i] = sqrt (distcentro [j] [i]) / nfeatures_attuali;
        			}
    			}
    			// era 0. Ricontrollare
		    	i1 = -1;
    			for (i = 0; i < ncasi_attuali; i ++)
        			if (classe [i] == j) {
            			i1 = i1 + 1;
            			vet [i1] = distcentro [j] [ i];
        			}
		    	// sz = sz + " " + i1 + " ";
    			if (i1 == -1 && nfree [j] == 0) {
        			nlib = j;
        			nfree [j] = 1;
    			}
		    	riduzione [j] = 0;
		    	// Dovrebbe essere -1
    			if (i1 != -1) {
        			vetm = 0;
        			vetxqm = 0;
        			// Dovrebbe essere j1 + 1
        			vetm = statm (vet, i1);
        			vetxqm = statds (vet, vetm,  i1);
        			riduzione [j] = vetm * (1 + vetxqm);
    			}
		    	if (riduzione [j] > ridmax && nrid [j] == 0) {
        			ridmax = riduzione [j];
        			nmax = j;
    			}
			}
			if (nmax != 0)  nrid [nmax] = 1;
			if (nlib != 0 &&  nmax != 0) 
				for (i = 0; i <  nr; i++)
    				w [i] [nlib] = w [i] [nmax];
		} while (nlib != 0 && nmax != 0);		// Controllare questa condizione
		
		
		free(centroide); 
		free(distcentro); 
		free(vet);
		free(riduzione);
		free(ncasi);
		free(nfree);
		free(nrid);
	}
	
	
	
	
void  ordmax(double * v, double * ord, int n) {
	int i; 
	double vmax;
	int k; 
	int j; 
	int pmax = -1;
	for (i = 0; i < n; i++)
		ord[i] = 0;
	k = 1;
	for (i = 0; i < n; i++) {
		vmax = -10000;
		for (j = 0; j < n; j++)
    		if (v[j] > vmax && ord[j] == 0) {
        		pmax = j;
        		vmax = v[j];
    		}
		ord[pmax] = k;
		k = k + 1;
	}
}   


int main(int argc,char** argv){

	int params;
	char *m_dt = NULL, *m_dd = NULL;
	int num_d = 0, num_t = 0; 
	while ((params = getopt(argc, argv, "r:c:t:d:")) != -1){
		switch(params){
			case 'r':
				num_d = atoi(optarg);
				break;
			case 'c': 
				num_t = atoi(optarg);
				break;
			case 't': 
				m_dt = optarg;
				break;
			case 'd': 
				m_dd = optarg;
				break;
			default:
				printf("Usage kohonen -r [num_docs] -c [mun_terms] -t [matrix_doc_terms] -d [matrix_doc_doc]\n");
				return 0;
		}
	}
	if(num_d == 0 || num_t == 0 || m_dt == NULL || m_dd == NULL){
		printf("Usage kohonen -r [num_docs] -c [mun_terms] -t [matrix_doc_terms] -d [matrix_doc_doc]\n");
		return 0;
	}
	int ind = 0, ind_b = 0, ind_num = 0,ind_row = 0;

	double *tmp_num = (double *)malloc(sizeof(double) * num_t);
	double* m3[num_d];
	char *buffer = (char *)malloc(sizeof(char) * 10);
	double max_dt = 0;
	while(m_dt[ind] != 0){
		if(m_dt[ind] != ',' && m_dt[ind] != '-'){				
			*(buffer + ind_b) = *(m_dt + ind);
			ind_b++;
		}
		else if(m_dt[ind] == ','){
			tmp_num[ind_num] = atof(buffer);
			if(max_dt < tmp_num[ind_num])
				max_dt = tmp_num[ind_num];
			buffer = (char *)malloc(sizeof(char) * 10);
			ind_b = 0;
			ind_num++;
		}
		else if(m_dt[ind] == '-'){
			tmp_num[ind_num] = atof(buffer);
			buffer = (char *)malloc(sizeof(char) * 10);
			ind_b = 0;
			ind_num = 0;
			m3[ind_row] = tmp_num;
			tmp_num = (double *)malloc(sizeof(double) * num_t);
			ind_row++;
		}
		ind++;
	}
	free(tmp_num);
	free(buffer);
	ind = 0;
	ind_b = 0;
	ind_num = 0;
	ind_row = 0;
	buffer = (char *)malloc(sizeof(char) * 10);
	tmp_num = (double *)malloc(sizeof(double) * num_d);
	double* smin[num_d];
	double max_dd = 0;
	while(m_dd[ind] != 0){
		if(m_dd[ind] != ',' && m_dd[ind] != '-'){				
			*(buffer + ind_b) = *(m_dd + ind);
			ind_b++;
		}
		else if(m_dd[ind] == ','){
			tmp_num[ind_num] = atof(buffer);
			if(max_dd < tmp_num[ind_num])
				max_dd = tmp_num[ind_num];
			buffer = (char *)malloc(sizeof(char) * 10);
			ind_b = 0;
			ind_num++;
		}
		else if(m_dd[ind] == '-'){
			tmp_num[ind_num] = atof(buffer);
			buffer = (char *)malloc(sizeof(char) * 10);
			ind_b = 0;
			ind_num = 0;
			smin[ind_row] = tmp_num;
			tmp_num = (double *)malloc(sizeof(double) * num_d);
			ind_row++;
		}
		ind++;
	}
	free(tmp_num);
	free(buffer);
	int i,j;
	/*for(i = 0; i < num_d; i++){
		for(j = 0; j < num_t; j++){
			m3[i][j] = m3[i][j]/max_dt; 
		}
	}
	for(i = 0; i < num_d; i++){
		for(j = 0; j < num_d; j++){
			smin[i][j] = smin[i][j]/max_dd;
		}
	}*/

	if (DEBUG == 1){
		printf("--------------Matrice documento termine----------\n");
		for(i = 0; i < num_d; i++){
			for(j = 0; j < num_t; j++){
				printf(" %f ",m3[i][j]);
			}
			printf("\n");
		}
		printf("--------------Matrice documento documento--------\n");
		for(i = 0; i < num_d; i++){
			for(j = 0; j < num_d; j++){
				printf(" %f ",smin[i][j]);
			}
			printf("\n");
		}
	}
	
	int nc = num_d*2;
	int numcicli = 20;

	int nfea = num_t;
	int * c;
	int numeroRighePerParte = num_d;
	double eta = 0.5;
	double teta = 0.2;
	int recombine_step = 12;
	int nr = numeroRighePerParte;
	int classe[numeroRighePerParte];
	
	double* w[nr];
	srand ( time(NULL) );
	double tmp[nc];
	for(i = 0; i < nr; i++){
		for(j = 0; j < nc; j++){
			tmp[j] = rand()/((double)RAND_MAX + 1);
		}
		w[i] = tmp;
	}
	
	ckC2PerParOtt (eta, teta, recombine_step, nc, numcicli, nr, m3, smin,  w, nfea, classe, numeroRighePerParte);
	return 0;
}
