<?php

	$polish = array(
	
           'gmap' => 'Mapy Google',
           'gmap:desc' => 'Ten plugin pozwala ci na używanie map Google na twojej stronie.',
           'gmap:nokey' => "You've installed the Google Map plugin but you still need to supply a GMap API key in the <a href='%spg/admin/plugins'>Tool Administration panel</a>.",

           'gmap:location' => 'Wpisz lokację',
           'gmap:id' => 'Wpisz ID elementu mapy (opcjonalne)',
           'gmap:zoom' => 'Wpisz poziom zbliżenia',
           'gmap:notfound' => 'Nie znaleziono adresu \'%s\'',
           
           'gmap:submit' => 'Zapisz',
           'gmap:modify' => 'Enter your Google Maps API Key<br /><small>You can obtain an API Key <a target="_blank" href="http://code.google.com/apis/maps/signup.html">here</a>.</small>',
           'gmap:modify:success' => 'Pomyślnie zapisano nowe ustawienia API Google Maps.',
           
		   'gmap:modify:failed' => 'Nie udało się zapisać ustawień API Google Maps.',
           'gmap:failed:keyrequired' => 'Musisz wpisać klucz API.',
           'gmap:nomap' => 'Wymagany jest argument mapy.',
           'gmap:noopts' => 'Nie podano lokacji.',
           'gmap:noloc' => 'Należy podać adres, lub współrzędne geograficzne.',
           'gmap:failed:geocode' => 'Geocoding failed.',
		   'gmap:last' => 'Ostatnie',
		   
           'gmap:river:created' => "%s dodał(a) plugin map Googla.",
           'gmap:river:updated' => "%s zaktualizował(a) plugin map Googla.",
           'gmap:river:delete' => "%s usunął(a) plugin map Googla.",

           'item:object:' . GMAP_SUBTYPE => 'Mapa Google'
	);
					
	add_translation("pl",$polish);

?>