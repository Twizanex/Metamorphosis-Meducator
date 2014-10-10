<?php

	/**
         * @package Elgg
         * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
         * @author Roger Curry, Grid Research Centre [curry@cpsc.ucalgary.ca]
         * @author Tingxi Tan, Grid Research Centre [txtan@cpsc.ucalgary.ca]
         * @link http://grc.ucalgary.ca/
         */

		$info = "<em><b><a href=\"{$vars['entity']->getURL()}\">{$vars['entity']->title}</a></b></em>";
		$authors = $vars['entity']->authors;
		$authors = explode(',',$authors);
		if (!empty($authors)) {
			for($index= 0; $index < count($authors) - 1; $index++) {
				$cauthor = $authors[$index];
				if(!ctype_digit($cauthor)) echo "$cauthor, ";
				else{
					$user = get_entity((int)$cauthor);
					echo '<a href="' . $CONFIG->wwwroot . 'pg/publications/' . $user->username . '">' . $user->name . '</a>, ';
						
				}
			}
			$cauthor = $authors[$index];
			if(!ctype_digit($cauthor)) echo "$cauthor";
			else{
				$user = get_entity((int)$cauthor);
				echo '<a href="' . $CONFIG->wwwroot . 'pg/publications/' . $user->username . '">' . $user->name . '</a>';
			}
			
		}
		
		echo ". $info.";
		
		if (!empty($vars['entity']->source)) {
			echo " " . $vars['entity']->source;
		}
		if (!empty($vars['entity']->volume))
			echo ' ' . $vars['entity']->volume;
		if (!empty($vars['entity']->issue))
			echo '('.$vars['entity']->issue . ')';
		if (!empty($vars['entity']->pages))
			echo ':' . $vars['entity']->pages;
		if (!empty($vars['entity']->year)) {
                        echo ', ' . $vars['entity']->year;
                }
		echo ".";

?>
