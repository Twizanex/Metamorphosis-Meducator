<?php
header("Content-Type: application/rss+xml; charset=UTF-8");


    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
 
 
 

$rssfeed = '<?xml version="1.0" encoding="ISO-8859-1"?>';
$rssfeed .= '<rss version="2.0">';
$rssfeed .= '<channel>';
$rssfeed .= '<title>MetaMorphosis Entities Feed</title>';
$rssfeed .= '<link>http://metamorphosis.med.duth.gr</link>';
$rssfeed .= '<description>This is an example RSS feed</description>';
$rssfeed .= '<language>en-us</language>';
$rssfeed .= '<copyright>Copyright (C) 2010 DUTH MediaLab</copyright>';

$items=get_entities($type = "user",$subtype="",$owner_guid=0,$order_by="",$limit =1000,$offset = 0,$count = false,$site_guid=0,$container_guid = null,$timelower=0,$timeupper=0);


foreach ( $items as $item )
{
        $rssfeed .= '<item>';
        $rssfeed .= '<title>' . $item->name . '</title>';
		$rssfeed .= '<guid isPermaLink="false">' .$item->guid  . '</guid>';
		if ($item->issimpleuser!="yes")
        $rssfeed .= '<description>' .$item->meducator7 .'</description>'; 
		if ($item->issimpleuser!="yes")

		$rssfeed .= '<technical>'.$item->meducator8.'</technical>';
				if ($item->issimpleuser!="yes")

		$rssfeed .= '<keywords>' .$item->meducator4.'</keywords>';
				if ($item->issimpleuser!="yes")

		$rssfeed .= '<ResourceLanguage>' .$item->meducator5.'</ResourceLanguage>';
				if ($item->issimpleuser!="yes")

		$rssfeed .= '<MetadataLanguage>' .$item->meducator24.'</MetadataLanguage>';
		if ($item->issimpleuser!="yes")
	    $rssfeed .= '<category>' .  'RESOURCE' .'</category>';
		else
		$rssfeed .= '<category>' .  'USER' .'</category>'; 
		$rssfeed .= '<foaf_profile>' . $item->getURL() ."?view=foaf".'</foaf_profile>';
		if ($item->issimpleuser!="yes")
        $rssfeed .= '<link>' ."http://metamorphosis.med.duth.gr/mod/content_item/create_xml.php?id=$item->guid".'</link>';
		else
		        $rssfeed .= '<link>' . $item->getURL() . '</link>';

		$date=$item->getTimeCreated();
        $rssfeed .= '<pubDate>' . date("D, d M Y H:i:s O",$date) . '</pubDate>';
        $rssfeed .= '</item>';
}

    $rssfeed .= '</channel>';
    $rssfeed .= '</rss>';

    echo $rssfeed;


?>