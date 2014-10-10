<html>
<head>
<style>
body {
	text-align: left;
	font-weight: normal;
	vertical-align: top;
	font: 12px Arial, Helvetica, sans-serif;
	background-color: white;
	margin: 10px;
}
table {
	border-collapse: separate;
	border-spacing: 1;
}
caption, th, td {
	text-align: left;
	font-weight: normal;
	vertical-align: top;
	font: 9px Arial, Helvetica, sans-serif;
	background-color: silver;
}

</style>
</head>
<body>
<?php
include_once(dirname(dirname(dirname(__FILE__))) . "/../engine/start.php");
$owner = $_SESSION['user'];

if (is_null($owner->username) || empty($owner->username))  {
	if (!isloggedin())
		forward('pg/dasboard/');
}
	$uploaddir = $CONFIG->dataroot;
	$file = $uploaddir . 'import-' . $owner->guid . '.csv';
	if (count($_FILES)) {
		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $file)) {
			$handle = fopen ($file, "r");
			echo '<form method="POST"><table>';
			$lineNr = 0;
			while (!feof($handle)) {
				$line = fgets($handle);
				$fields = explode(',',$line);
				if (!$lineNr) {
					$fieldNr = 0;
					foreach($fields as $field){
						switch($field){
							case '"Vorname"':
								$imp["vorname"] = $fieldNr;
								break;
							case '"Nachname"':
								$imp["nachname"] = $fieldNr;
								break;
							case '"Mobiltelefon"':
								$imp["mobil"] = $fieldNr;
								break;
							case '"E-Mail-Adresse"':
								$imp["email"] = $fieldNr;
								break;
							continue;
						} // switch
						$fieldNr ++;
					}
				}
				if ($fields[$imp["vorname"]]>'' || $fields[$imp["nachname"]]>'') {
					echo '<tr>';
					echo '<td>' . ($lineNr ? '<input type="checkbox" name="DSNr[]" value="'.$lineNr.'" checked>' : '&nbsp;' ) . '</td><td nowprap>' . utf8_encode(str_replace('"','', $fields[$imp["vorname"]] . ' ' . $fields[$imp["nachname"]] )). '</td><td nowprap>' . str_replace('"','', $fields[$imp["email"]]) . '</td><td nowprap>' . str_replace('"','', $fields[$imp["mobil"]]) . '</td>';
					echo '</tr>';
				}
				$lineNr++;
			}
			fclose ($handle);
			echo '</table><input type="submit" name="Submit" value="Import"></form>';
		}
	} elseif (is_array($_POST["DSNr"])) {
		$handle = fopen ($file, "r");
		$lineNr = 0;
		while (!feof($handle)) {
			$line = fgets($handle);
			$fields = explode(',',$line);
			if (!$lineNr) {
				$fieldNr = 0;
				foreach($fields as $field){
					switch($field){
						case '"Vorname"':
							$imp["vorname"] = $fieldNr;
							break;
						case '"Nachname"':
							$imp["nachname"] = $fieldNr;
							break;
						case '"Mobiltelefon"':
							$imp["mobil"] = $fieldNr;
							break;
						case '"E-Mail-Adresse"':
							$imp["email"] = $fieldNr;
							break;
						continue;
					} // switch
					$fieldNr ++;
				}
			}
			if (in_array($lineNr,$_POST["DSNr"])) {
				$v = new ElggObject();
				$v->subtype = "PrivateContact";
				$v->access_id = 0; // Zugriff nur für den Creator

				$name = str_replace('"','', $fields[$imp["vorname"]] . ' ' . $fields[$imp["nachname"]] );
                $v->user_last_name = $name ? $name : 'NULL';
                $email = str_replace('"','', $fields[$imp["email"]]);
                $v->user_email = $email ? $email : 'NULL';
                $mobil = str_replace('"','', $fields[$imp["mobil"]]);
                $v->mobil = $mobil ? $mobil : 'NULL';
                $v->user_date_add = date("Y-m-d", time());

				$v->save();
				echo utf8_encode(str_replace('"','', $fields[$imp["vorname"]] . ' ' . $fields[$imp["nachname"]] )). ', ' . str_replace('"','', $fields[$imp["email"]]) . ', ' . str_replace('"','', $fields[$imp["mobil"]]) . '<br>';
			}
			$lineNr++;
		}
		fclose ($handle);
		@unlink($file);

	} else {
		echo nl2br('<h2>Import von CSV Daten</h2>
Die k&ouml;nnen hier CSV-Daten importieren. So k&ouml;nnen Sie zum Beispiel Ihre <strong>Kontaktdaten aus dem Outlook</strong> importieren.

<strong>Gehen Sie dazu z.B. im Outlook 2003 wie folgt vor:</strong>

Datei &gt;&gt; Importiern / Exportieren &gt;&gt; Exportieren in eine Datei &gt;&gt; Kommagetrennt Werte (Windows) &gt;&gt; Kontakte &gt;&gt; Exportierte Datei speichern unter: (w&auml;hlen Sie eienen Speicherort, z.B. C:\outlook-kontakte.csv)

Stellen Sie die Aktion fertig und laden Sie die Datei jetzt hier:
<form enctype="multipart/form-data" action="" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
Datei ausw&auml;hlen: <input name="userfile" type="file">
<input type="submit" value="Send File">
</form>

<strong>Hinweise zur Problembehebung bzw. dem Aufbau einer CSV-Datei:</strong> Die erste Zeile des Exportes enh&auml;lt die Feldnamen, dabei m&uuml;ssen folgende Felder definiert sein: <strong>"Vorname" "Nachname" "Mobiltelefon" "E-Mail-Adresse"</strong> - wichtig sind die Anf&uuml;hrungszeichen. Trennen Sie die Felder mit Kommata.
');
	}
?>
</body>
</html>