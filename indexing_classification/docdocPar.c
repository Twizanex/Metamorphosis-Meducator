#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <mpi.h>

#define DEBUG 1
/*
It converts the doc-term matrices into doc-doc matrices, by using the script docdoc.php
Usage: compile it by using mpicc and then launch by
	mpiexec -n N ./docdocPar C I
N is the number of processes you want to process the doc-term matrices
C is 1 if you want to consider contexts in the conversion
I is 1 if you want to do a complete new indexing (slow but more accurate), 0 if you want to use the results of  the last done indexing (faster but sometimes not very accurate)
*/

int  main(int argc, char *argv[]) {
	int i, err;
	char command[30];
	int numNodi, rank; 
	int enable = atoi (argv [1]); 
	MPI_Status Stat; 
	MPI_Init(&argc, &argv); 
	MPI_Comm_size(MPI_COMM_WORLD, &numNodi);
	if (DEBUG == 1) {
		printf ("Numero di nodi = %d \n", numNodi);
		printf ("enable = %d\n", enable);
		fflush (stdout); 
	}
	MPI_Comm_rank(MPI_COMM_WORLD, &rank);
	if (rank == 0) {
		//We use now docdoc.php, but you can also use the HipHop generated executable
		sprintf(command,"php -f docdoc.php %d %s %s %s",(rank+1),numNodi,argv[1],argv[2]);
		printf("Executing %s...\n\n", command); fflush (stdout); 
		system(command);
	}
	else {
		sprintf(command,"php -f docdoc.php %d %s %s %s",(rank+1),numNodi,argv[1],argv[2]);
		printf("Executing %s...\n\n", command);
		system(command);
	}
	MPI_Finalize();
}