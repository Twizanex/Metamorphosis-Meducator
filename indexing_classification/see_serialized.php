<?php
require_once 'classes.php';
require_once 'config.php';
$lr = unserialize(file_get_contents($IOdir. "changes"));
print_r($lr);
?>