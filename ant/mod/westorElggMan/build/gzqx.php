<?php
	$enc = array();
	$file = 'script/westorelggman.765e584e0a8a.js';
	$zipFile = $file . '.gz';
	if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && file_exists($zipFile))
	{
	   $enc = explode(',', strtolower(preg_replace("/s+/", "", $_SERVER['HTTP_ACCEPT_ENCODING'])));
	   if (in_array('gzip', $enc))
	   {
	       header("Content-Encoding: gzip");
	       header("Content-Type: application/x-javascript");
	       echo file_get_contents($zipFile);
	       die;
	   }
	}
	echo file_get_contents($file);
	die;
?>