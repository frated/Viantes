<?php
/*
*------------CONFIGURAZIONE in php.ini
Nome 					Default 	Descrizione
file_uploads 			"1" 		Indica se abilitare o meno gli upload di file. Vedere anche i parametri upload_max_filesize, upload_tmp_dir e post_max_size. 
upload_tmp_dir 			NULL 		Directory temporanea utilizzata per il transito dei file durante l'upload. 
									Deve avere i permessi di scrittura per gli utenti utilizzati dal PHP per girare. 
									Se non indicata il PHP utilizzerà il default di sistema.
									Se la directory specificata non ha i permessi di scrittura, PHP ripiega sulla directory temporanea di sistema. 
									Se open_basedir è attivo, la directory temporanea di sistema deve avere i diritti di scrittura, per permettere un upload.
max_input_nesting_level 64 			Imposta la profondità massima di nidificazione delle variabili di input (es. $_GET, $_POST..) 	
max_input_vars 			1000 		Quante variabili di input possono essere accettate (il limite è applicato alle variabili superglobali $_GET, $_POST e $_COOKIE 
									separatamente). L'uso di questa direttiva mitiga la possibilità di attacchi di tipo denial of service che usano collisioni hash. 
									Se ci sono più variabili di input di quanto specificato da questa direttiva, viene rilasciato un E_WARNING, e le ulteriori 
									variabili di input vengono troncate dalla richiesta. 
upload_max_filesize 	"2M" 		La dimensione massima di un file inviato. Quando un integer è usato, il valore è misurato in byte. 
									Si può anche usare una notazione abbreviata: K/k Kilobytes, M/m  Megabytes and G/g Gigabytes (available since PHP 5.1.0)
max_file_uploads 		20 			Il numero massimo di file che si possono caricare in upload simultaneamente. 
									A partire da PHP 5.3.4, i campi upload lasciati vuoti durante l'invio non sono presi in conto da questo limite. 

*------------ARRAY SUPERGLOBALE $_FILES
$_FILES['userfile']['name']		    Il nome originale del file sulla macchina dell'utente.
	
$_FILES['userfile']['type']    		Il mime-type del file, se il browser fornisce questa informazione. 
									Un esempio potrebbe essere "image/gif". Questo mime type comunque non è controllato sul lato PHP e quindi non ci si deve fidare di questo valore.
	
$_FILES['userfile']['size']    		La dimensione, in bytes, del file caricato.
	
$_FILES['userfile']['tmp_name']		Il nome del file temporaneo in cui il file caricato è salvato sul server.

$_FILES['userfile']['error']    	Il codice di errore associato all'upload di questo file. Questo elemento è stato aggiunto nella versione 4.2.0 di PHP.
*/
?>