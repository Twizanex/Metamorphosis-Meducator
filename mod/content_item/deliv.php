<?php
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 

 
    // set the title
    $title = "About MetaMorphosis+";
 
    // start building the main column of the page
    $area2 = elgg_view_title($title);
  
    // Add the form to this section
	
	global $CONFIG;
	
	$area2 .= "<div class=\"contentWrapper\">";
	$area2 .="


<div class=Section1>

<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
line-height:normal'><span lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";
mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:Tahoma;
color:#555555'>S. <span class=SpellE>Dietze</span>, <span class=SpellE>H.Q.Yu</span>,
D. Giordano, E. Kaldoudi, N. Dovrolis, and D. Taibi,\"<b>Linked Education:
interlinking educational Resources and the Web of Data</b>\", in the Proceedings
of the 27th ACM Symposium On Applied Computing (SAC-2012), Special Track on
Semantic Web and Applications, Riva del Garda (Trento), Italy, 25-29 March,
2012 <br>
[</span><span lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_2012_SAC_reprint.pdf\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>reprint (0.8MB)</span></b></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#555555'>]-[</span><span
lang=EN-US><a href=\"http://users.marshall.edu/~hanh/SWA2012/\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>link to conference
site</span></b></a></span><span lang=EN-US style='font-size:10.0pt;font-family:
\"Arial\";mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:
Tahoma;color:#555555'>] </span><span lang=EN-US style='font-size:8.5pt;
font-family:\"Tahoma\",\"sans-serif\";mso-fareast-font-family:\"Times New Roman\";
color:#555555'><o:p></o:p></span></p>

<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
line-height:normal'><span lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";
mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:Tahoma;
color:#555555'>E. Kaldoudi, N. Dovrolis, S. <span class=SpellE>Konstantinidis</span>,
P. Bamidis,\"<b>Depicting Educational Content Repurposing Context and
Inheritance</b>\", IEEE Transactions on Information Technology in Biomedicine,
vol. 15(1), pp. 164-170, 2011. <br>
[</span><span lang=EN-US><a
href=\"http://ieeexplore.ieee.org/stamp/stamp.jsp?tp=&amp;arnumber=5648462\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>link to online
publication</span></b></a></span><span lang=EN-US style='font-size:10.0pt;
font-family:\"Arial\";mso-fareast-font-family:\"Times New Roman\";
mso-bidi-font-family:Tahoma;color:#555555'>]-[</span><span lang=EN-US
style='font-size:8.5pt;font-family:\"Tahoma\",\"sans-serif\";mso-fareast-font-family:
\"Times New Roman\";color:#555555'><a
href=\"http://iris.med.duth.gr/Portals/14/pub03_journal_papers/Kaldoudi_2011_TITB_preprint.pdf\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";color:#666666'>preliminary
version of the paper (0.3MB)</span></b></a></span><span lang=EN-US
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#555555'>] </span><span
lang=EN-US style='font-size:8.5pt;font-family:\"Tahoma\",\"sans-serif\";mso-fareast-font-family:
\"Times New Roman\";color:#555555'><o:p></o:p></span></p>

<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
line-height:normal'><span lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";
mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:Tahoma;
color:#555555'>E. Kaldoudi, N. Dovrolis, and S. Dietze,\"<b>Information
Organization on the Internet based on Heterogeneous Social Networks</b>\", ACM
SIGDOC'11: The 29th ACM International Conference on Design of Communication,
Pisa, Italy, October 3-5, 2011 (pages: 107-114) <br>
[</span><span lang=EN-US><a
href=\"http://dl.acm.org/citation.cfm?id=2038496&amp;dl=ACM&amp;coll=DL&amp;CFID=100726617&amp;CFTOKEN=99277537\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>link to online
publication</span></b></a></span><span lang=EN-US style='font-size:10.0pt;
font-family:\"Arial\";mso-fareast-font-family:\"Times New Roman\";
mso-bidi-font-family:Tahoma;color:#555555'>]-[</span><span lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_SIGDOC_2011_reprint.pdf\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>preliminary
version of the paper (0.7MB)</span></b></a></span><span lang=EN-US
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#555555'>]-[</span><span
lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_SIGDOC_2011_presentation.pdf\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>presentation
(1.3MB)</span></b></a></span><span lang=EN-US style='font-size:10.0pt;
font-family:\"Arial\";mso-fareast-font-family:\"Times New Roman\";
mso-bidi-font-family:Tahoma;color:#555555'> ]-[</span><span lang=EN-US><a
href=\"http://www.sigdoc.org/2011/\"><b><span style='font-size:10.0pt;font-family:
\"Arial\";mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:
Tahoma;color:#666666'>link to conference site</span></b></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#555555'>] </span><span
lang=EN-US style='font-size:8.5pt;font-family:\"Tahoma\",\"sans-serif\";mso-fareast-font-family:
\"Times New Roman\";color:#555555'><o:p></o:p></span></p>

<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
line-height:normal'><span lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";
mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:Tahoma;
color:#555555'>N. Dovrolis, T. <span class=SpellE>Stefanut</span>, S. Dietze,
H.Q. Yu, C. Valentine and E. Kaldoudi,\"<b>Semantic Annotation and Linking of
Medical Educational Resources</b>\", 5th European Conference of the
International Federation for Medical and Biological Engineering, Budapest,
Hungary, September 14-18, 2011 (pages: 1400-1403) IFMBE Proceedings, vol. 37,
1400-1403, 2011 <br>
[</span><span lang=EN-US><a
href=\"http://www.springer.com/engineering/biomedical+engineering/book/978-3-642-23507-8\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>link to online
publication</span></b></a></span><span lang=EN-US style='font-size:10.0pt;
font-family:\"Arial\";mso-fareast-font-family:\"Times New Roman\";
mso-bidi-font-family:Tahoma;color:#555555'>]-[</span><span lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_IFMBE_EMBEC_2011_reprint.pdf\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>reprint (0.6MB)</span></b></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#555555'>]-[</span><span
lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_IFMBE_EMBEC_2011_presentation.pdf\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>presentation
(1.7MB)</span></b></a></span><span lang=EN-US style='font-size:10.0pt;
font-family:\"Arial\";mso-fareast-font-family:\"Times New Roman\";
mso-bidi-font-family:Tahoma;color:#555555'>]-[</span><span lang=EN-US><a
href=\"http://www.embec2011.com/?mod=content&amp;cla=content&amp;fun=access&amp;id=84&amp;mid=1&amp;temp=base\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>link to conference
site</span></b></a></span><span lang=EN-US style='font-size:10.0pt;font-family:
\"Arial\";mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:
Tahoma;color:#555555'>] </span><span lang=EN-US style='font-size:8.5pt;
font-family:\"Tahoma\",\"sans-serif\";mso-fareast-font-family:\"Times New Roman\";
color:#555555'><o:p></o:p></span></p>

<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
line-height:normal'><span lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";
mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:Tahoma;
color:#555555'>E. Kaldoudi, N. Dovrolis, D. Giordano, and S. Dietze,\"<b>Educational
Resources as Social Objects in Semantic Social Networks</b>&quot;, Proceedings
of the ESWC2011: 8th Extended Semantic Web Conference - Linked Learning 2011:
1st International Workshop on eLearning Approaches for the Linked Data Age, <span
class=SpellE>Herakleio</span>, May 29-30, 2011 <br>
[</span><span lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_ESWC_2011_social_reprint.pdf\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>reprint (0.5MB)</span></b></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#555555'>]-[</span><span
lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_ESWC_2011_social_presentation.pdf\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>presentation
(1.4MB)</span></b></a></span><span lang=EN-US style='font-size:10.0pt;
font-family:\"Arial\";mso-fareast-font-family:\"Times New Roman\";
mso-bidi-font-family:Tahoma;color:#555555'>]-[</span><span lang=EN-US><a
href=\"http://www.eswc2011.org/content/about-eswc-2011\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>link to conference
site</span></b></a></span><span lang=EN-US style='font-size:10.0pt;font-family:
\"Arial\";mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:
Tahoma;color:#555555'>] </span><span lang=EN-US style='font-size:8.5pt;
font-family:\"Tahoma\",\"sans-serif\";mso-fareast-font-family:\"Times New Roman\";
color:#555555'><o:p></o:p></span></p>

<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
line-height:normal'><span lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";
mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:Tahoma;
color:#555555'>S. Dietze, E. Kaldoudi, N. Dovrolis, H.Q. Yu, and D. <span
class=SpellE>Taibi</span>,\"<span class=SpellE><b>MetaMorphosis</b></span><b>+
- A social network of educational Web resources based on semantic integration
of services and data</b>&quot;, Demo Paper, in the Proceedings of the 10th
International Semantic Web Conference, Bonn, Germany, 23-27 October, 2011<br>
[</span><span lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_ISWC_2011_reprint.pdf\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>reprint (0.2MB)</span></b></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#555555'>]-[</span><span
lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_2011_ISWC_poster.jpg\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>poster (0.9MB)</span></b></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#555555'>]-[</span><span
lang=EN-US><a href=\"http://iswc2011.semanticweb.org/\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>link to conference
site</span></b></a></span><span lang=EN-US style='font-size:10.0pt;font-family:
\"Arial\";mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:
Tahoma;color:#555555'>] </span><span lang=EN-US style='font-size:8.5pt;
font-family:\"Tahoma\",\"sans-serif\";mso-fareast-font-family:\"Times New Roman\";
color:#555555'><o:p></o:p></span></p>

<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
line-height:normal'><span lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";
mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:Tahoma;
color:#555555'>H.Q. Yu, S. Dietze, N. Li, C. <span class=SpellE>Pedrinaci</span>,
D. Taibi, N. Dovrolis, T. <span class=SpellE>Stefanut</span>, E. Kaldoudi and
J. <span class=SpellE>Dominque</span>,\"<b>A Linked Data-driven &amp;
Service-oriented Architecture for Sharing Educational Resources</b>\", In the
Proceedings of the ESWC2011: 8th Extended Semantic Web Conference - Linked
Learning 2011: 1st International Workshop on eLearning Approaches for the
Linked Data Age, <span class=SpellE>Herakleio</span>, May 29-30, 2011<br>
[</span><span lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_ESWC_2011_semantic_reprint.pdf\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>reprint (1MB)</span></b></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#555555'>]-[</span><span
lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_ESWC_2011_semantic_presentation.pdf\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>presentation (1MB)</span></b></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#555555'> ]-[</span><span
lang=EN-US><a href=\"http://www.eswc2011.org/content/about-eswc-2011\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>link to conference
site</span></b></a></span><span lang=EN-US style='font-size:10.0pt;font-family:
\"Arial\";mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:
Tahoma;color:#555555'>] </span><span lang=EN-US style='font-size:8.5pt;
font-family:\"Tahoma\",\"sans-serif\";mso-fareast-font-family:\"Times New Roman\";
color:#555555'><o:p></o:p></span></p>

<p><span lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";
color:#555555'>E. Kaldoudi, N. Dovrolis, S. <span class=SpellE>Konstantinidis</span>,
P. Bamidis,\"<strong><span style='font-family:\"Arial\";mso-bidi-font-family:
Tahoma'>Educational Content Organization and Retrieval via a Social Network</span></strong>\",
presented in the 2nd International Conference on Virtual Patients &amp; <span
class=SpellE>MedBiquitous</span> Annual Conference, London UK, April 26-28,
2010, and published in Bio-Algorithms and Med-Systems, vol. 6(11), p. 71, 2010<br>
[</span><span lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub04_journal_abstracts/Kaldoudi_ICVP_2010_repurposing_reprint.pdf\"><strong><span
style='font-family:\"Tahoma\",\"sans-serif\";color:#666666'>abstract</span></strong></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";color:#555555'>]-[</span><span
lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub04_journal_abstracts/Kaldoudi_ICVP_2010_repurposing_presentation.pdf\"><strong><span
style='font-size:10.0pt;font-family:\"Arial\";mso-bidi-font-family:Tahoma;
color:#666666'>presentation (1.5MB)</span></strong></a></span><span lang=EN-US
style='font-size:10.0pt;font-family:\"Arial\";color:#555555'>]</span><span
lang=EN-US style='color:#555555'><o:p></o:p></span></p>

<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
line-height:normal'><span lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";
mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:Tahoma;
color:#555555'>N. Dovrolis, K. <span class=SpellE>Zigeridou</span>, and E.
Kaldoudi,\"<b>A Semantically Linked Virtual Community for Sharing Educational
Resources in Biological Sciences</b>\", p. 33 in the Proceedings of HSCBB_10:
The 5th Conference of the Hellenic Society for Computational Biology and
Bioinformatics, <span class=SpellE>Alexandroupolis</span>, Greece, 17-19
October 2010<br>
[</span><span lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub06_conf_abstracts/Kaldoudi_HSCBB_2010.pdf\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>abstract</span></b></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#555555'>]-[</span><span
lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub06_conf_abstracts/Kaldoudi_HSCBB_2010_presentation.pdf\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>presentation
(0.7MB)</span></b></a></span><span lang=EN-US style='font-size:10.0pt;
font-family:\"Arial\";mso-fareast-font-family:\"Times New Roman\";
mso-bidi-font-family:Tahoma;color:#555555'>] </span><span lang=EN-US
style='font-size:8.5pt;font-family:\"Tahoma\",\"sans-serif\";mso-fareast-font-family:
\"Times New Roman\";color:#555555'><o:p></o:p></span></p>

<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
line-height:normal'><span lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";
mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:Tahoma;
color:#555555'>E. Kaldoudi, and N. Dovrolis,\"<b>Sharing and Linking Medical
Educational Resources on the Semantic Web - The <span class=SpellE>MetaMorphosis</span>
Network Experience</b>\", 6th International Conference of Aerospace Medicine,
Thessaloniki, Greece, 22-26 September 2010<br>
[</span><span lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub07_conf_announc/Kaldoudi_2010_gasma_presentation.pdf\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>presentation</span></b></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#555555'>]</span><span
lang=EN-US style='font-size:8.5pt;font-family:\"Tahoma\",\"sans-serif\";mso-fareast-font-family:
\"Times New Roman\";color:#555555'><o:p></o:p></span></p>

<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
line-height:normal'><span lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";
mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:Tahoma;
color:#555555'>E. Kaldoudi,\"<b>Content Sharing Functional Requirements: Survey
Results</b>\", 2nd International Workshop on Multi-type Content Repurposing and
Sharing in Medical Education, Plovdiv, Bulgaria, 21 January 2010<br>
[</span><span lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub07_conf_announc/Kaldoudi_Workshop2_2010_Survey.pdf\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>presentation</span></b></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#555555'>] </span><span
lang=EN-US style='font-size:8.5pt;font-family:\"Tahoma\",\"sans-serif\";mso-fareast-font-family:
\"Times New Roman\";color:#555555'><o:p></o:p></span></p>

<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
line-height:normal'><span lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";
mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:Tahoma;
color:#555555'>D. Giordano, E. Kaldoudi,\"<b>Managing Repurposed Content</b>\",
2nd International Workshop on Multi-type Content Repurposing and Sharing in
Medical Education, Plovdiv, Bulgaria, 21 January 2010<br>
[</span><span lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub07_conf_announc/Kaldoudi_Workshop2_2010_Sharing.pdf\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>presentation</span></b></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#555555'>]</span><span
lang=EN-US style='font-size:8.5pt;font-family:\"Tahoma\",\"sans-serif\";mso-fareast-font-family:
\"Times New Roman\";color:#555555'><o:p></o:p></span></p>

<p><span lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";
color:#555555'>E. Kaldoudi, N. Dovrolis, S. <span class=SpellE>Konstantinidis</span>,
P. Bamidis,\"<strong><span style='font-family:\"Arial\";mso-bidi-font-family:
Tahoma'>Social Networking for Learning Object Repurposing in Medical Education</span></strong>\",
The Journal on Information Technology in Healthcare, vol. 7(4), pp. 233-243,
2009.<br>
[</span><span lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub03_journal_papers/Kaldoudi_2009_JITH_Repurposing.pdf\"
target=\"_blank\"><strong><span style='font-size:10.0pt;font-family:\"Arial\";
mso-bidi-font-family:Tahoma;color:#666666'><b>reprint</b></span></strong></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";color:#555555'>]</span><span
lang=EN-US style='color:#555555'><o:p></o:p></span></p>

<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
line-height:normal'><span lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";
mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:Tahoma;
color:#555555'>N. Dovrolis, S. <span class=SpellE>Konstantinidis</span>, P.
Bamidis, E. Kaldoudi,\"<b>Depicting Educational Content Re-purposing Context
and Inheritance</b>\", ITAB2009: 9th International Conference on Information
Technology and Applications in Biomedicine, <span class=SpellE>Larnaca</span>,
Cyprus, November 5-7, 2009 <br>
[</span><span lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_ITAB_2009_repurposing_reprint.pdf\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>reprint (0.5MB)</span></b></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#555555'>]-[</span><span
lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_ITAB_2009_repurposing_presentation.pdf\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>presentation(1MB)</span></b></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#555555'>]-[</span><span
lang=EN-US><a href=\"http://www.cs.ucy.ac.cy/itab2009/\"><b><span
style='font-size:10.0pt;font-family:\"Arial\";mso-fareast-font-family:
\"Times New Roman\";mso-bidi-font-family:Tahoma;color:#666666'>link to conference
site</span></b></a></span><span lang=EN-US style='font-size:10.0pt;font-family:
\"Arial\";mso-fareast-font-family:\"Times New Roman\";mso-bidi-font-family:
Tahoma;color:#555555'>]</span><span lang=EN-US style='font-size:8.5pt;
font-family:\"Tahoma\",\"sans-serif\";mso-fareast-font-family:\"Times New Roman\";
color:#555555'><o:p></o:p></span></p>

<p><span lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";
color:#555555'>E. Kaldoudi, P. Bamidis, C. <span class=SpellE>Pattichis</span>,
\"<strong><span style='font-family:\"Arial\";mso-bidi-font-family:Tahoma'>Multi-Type
Content Repurposing and Sharing in Medical Education</span></strong>\",
Proceedings of INTED2009: International Technology, Education and Development
Conference, pp. 5129-5139, Valencia, March 9-11, 2009<br>
[</span><span lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_INTED_2009_mEducator.pdf\"><strong><span
style='font-size:10.0pt;font-family:\"Arial\";mso-bidi-font-family:Tahoma;
color:#666666'>reprint (1.3MB)</span></strong></a></span><span lang=EN-US
style='font-size:10.0pt;font-family:\"Arial\";color:#555555'>]-[</span><span
lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_INTED_2009_mEducator_presentation.pdf\"><strong><span
style='font-size:10.0pt;font-family:\"Arial\";mso-bidi-font-family:Tahoma;
color:#666666'>presentation (1.1MB)</span></strong></a></span><span lang=EN-US
style='font-size:10.0pt;font-family:\"Arial\";color:#555555'>]-[</span><span
lang=EN-US><a href=\"http://www.iated.org/inted2009/\"><strong><span
style='font-size:10.0pt;font-family:\"Arial\";mso-bidi-font-family:Tahoma;
color:#666666'>link to conference site</span></strong></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";color:#555555'>]</span><span
lang=EN-US style='color:#555555'><o:p></o:p></span></p>

<p><span lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";
color:#555555'>E. Kaldoudi, M. <span class=SpellE>Papaioakeim</span>, P.M. <span
class=SpellE>Bamidis</span>, V. <span class=SpellE>Vargemezis</span>,\"<strong><span
style='font-family:\"Arial\";mso-bidi-font-family:Tahoma'>Towards Expert
Content Sharing in Medical Education</span></strong>\", Proceedings of
INTED2008: International Technology, Education and Development Conference,
Valencia, March 3-5, 2008<br>
[</span><span lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_INTED_2008_ContentSharing.pdf\"><strong><span
style='font-size:10.0pt;font-family:\"Arial\";mso-bidi-font-family:Tahoma;
color:#666666'>reprint 0.1MB)</span></strong></a></span><span lang=EN-US
style='font-size:10.0pt;font-family:\"Arial\";color:#555555'>]-[</span><span
lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_INTED_2008_ContentSharing_presentation.pdf\"><strong><span
style='font-size:10.0pt;font-family:\"Arial\";mso-bidi-font-family:Tahoma;
color:#666666'>presentation (1MB)</span></strong></a></span><span lang=EN-US
style='font-size:10.0pt;font-family:\"Arial\";color:#555555'>]-[</span><span
lang=EN-US><a href=\"http://www.iated.org/inted2008/\"><strong><span
style='font-size:10.0pt;font-family:\"Arial\";mso-bidi-font-family:Tahoma;
color:#666666'>link to conference site</span></strong></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";color:#555555'>]</span><span
lang=EN-US style='color:#555555'><o:p></o:p></span></p>

<p><span lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";
color:#555555'>E. Kaldoudi, M. <span class=SpellE>Papaioakeim</span>, S. <span
class=SpellE>Konstantinidis</span>, P.D. <span class=SpellE>Bamidis</span>,\"<strong><span
style='font-family:\"Arial\";mso-bidi-font-family:Tahoma'>Virtual
Collaborative Academic Education in Medicin</span></strong>e\", ICICTH2007: 5th
International Conference on Information &amp; Communication Technologies in
Health, Samos, July 5-7, 2007<br>
[</span><span lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_ICICHT_2007_CollaborativeLearning.pdf\"><strong><span
style='font-size:10.0pt;font-family:\"Arial\";mso-bidi-font-family:Tahoma;
color:#666666'>reprint (0.1MB)</span></strong></a></span><span lang=EN-US
style='font-size:10.0pt;font-family:\"Arial\";color:#555555'>]-[</span><span
lang=EN-US><a href=\"http://www.ineag.gr/ICICTH/index.php\"><strong><span
style='font-size:10.0pt;font-family:\"Arial\";mso-bidi-font-family:Tahoma;
color:#666666'>link to conference site</span></strong></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";color:#555555'>]</span><span
lang=EN-US style='color:#555555'><o:p></o:p></span></p>

<p><span lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";
color:#555555'>M.<span class=SpellE>Papaioakeim</span>, E. Kaldoudi, V. <span
class=SpellE>Vargemezis</span>, K. <span class=SpellE>Simopoulos</span>,\"<strong><span
style='font-family:\"Arial\";mso-bidi-font-family:Tahoma'>Confronting the
Problem of Ever Expanding Core Knowledge and the Necessity of Handling
Overspecialized Disciplines in Medical Education</span></strong>\", ITAB 2006:
IEEE International Special Topic Conference on Information Technology in
Biomedicine, <span class=SpellE>Ioannina</span>, Greece, October 26-28, 2006<br>
[</span><span lang=EN-US><a
href=\"http://iris.med.duth.gr/Portals/14/pub05_conf_papers/Kaldoudi_ITAB_2006_MedicalEducation.pdf\"><strong><span
style='font-size:10.0pt;font-family:\"Arial\";mso-bidi-font-family:Tahoma;
color:#666666'>reprint (0.1MB)</span></strong></a></span><span lang=EN-US
style='font-size:10.0pt;font-family:\"Arial\";color:#555555'>]-[</span><span
lang=EN-US><a href=\"http://medlab.cs.uoi.gr/itab2006/\"><strong><span
style='font-size:10.0pt;font-family:\"Arial\";mso-bidi-font-family:Tahoma;
color:#666666'>link to conference site</span></strong></a></span><span
lang=EN-US style='font-size:10.0pt;font-family:\"Arial\";color:#555555'>]</span><a
name=\"_GoBack\"></a><span lang=EN-US style='color:#555555'><o:p></o:p></span></p>

</div>





	
";
	$area2 .= "</div>";
    // layout the page
	 $body =elgg_view_layout('one_column', $area2);

 
 	
    // draw the page
    page_draw($title, $body);
	fclose($ourFileHandle);

?>