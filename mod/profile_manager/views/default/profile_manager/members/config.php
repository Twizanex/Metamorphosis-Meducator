<?php
$IOdir = "C:/Progra~2/Apache~1/Apache2.2/htdocs/iodir/";
$IndexingClassificationPath = 'C:/Progra~2/Apache~1/Apache2.2/htdocs/indexing_classification/';
$data_source = 1;
$metadata_fields = "description;title;educationalObjectives;creator";
$uses_fields = "teachingLearningInstructions";
$tags_fields = "subject";
$keywords_limit = -1;
$context_limit = -1;
$width_sliding_window = 2;
$enable_idf = 0;
$enable_synonyms = 0;
$syn_db_host = "localhost:3306";
$syn_db_user = "root";
$syn_db_pass = "";
$syn_db_name = "wordnet30";
$classification_method_metadata = 3;
$classification_method_tags = 3;
$classification_method_uses = 3;
$classification_method_replinks = 3;
$output_file_kohonen = 'ParteClassi.txt';
$YACA_threshold = 0.8;
$minimum_association_threshold = 0.6;
$dd_crosscheck = 1;
?>
