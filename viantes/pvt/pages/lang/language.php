<?php

class Language {
	
    private $langCode;

    public function __construct($userLanguage){
        $this->lanCode = $userLanguage;
    }
	
    public function getLanguageArray(){
		
        switch($this->lanCode){
            case "en": 
                return $this->doEn();
                break;
				
            case "it":
                return $this->doIt();
                break;
				
            default:
                return $this->doEn();
                break;
        }
    }
	
	/*
	------------------
	Language: Italian
	------------------
	*/
	private function doIt() {
		$lang = array();
		
		// WELCOME
		$lang['WELCOME_PAGE_TITLE'] = 'Viantes: le recensioni dei tuoi viaggi';
		$lang['WELCOME_HAS_PUB'] = 'ha pubblicato la recensione:';
		$lang['WELCOME_KEEP_READING'] = '...continua a leggere';
		
		// MY_PROFILE
		$lang['MYPROFILE_PAGE_TITLE'] = 'Il mio Profilo';
		$lang['MYPROFILE_MY_INFO'] = 'I miei dati';
		$lang['MYPROFILE_MY_INFO_MODIFY'] = 'Modifica';
		$lang['MYPROFILE_MY_INFO_CANCEL'] = 'Cancella';
		$lang['MYPROFILE_MY_INFO_SAVE'] = 'Salva';
		$lang['MYPROFILE_MY_DEN_USR'] = 'Blocca Utente';
		$lang['MYPROFILE_MY_ALLOW_USR'] = 'Sblocca Utente';
		$lang['MYPROFILE_MY_SEND_MSG'] = 'Invia Messaggio';
		$lang['MYPROFILE_FIRST_NAME'] = 'Nome:';
		$lang['MYPROFILE_LAST_NAME'] = 'Cognome:';
		$lang['MYPROFILE_EMAIL'] = 'Email:';
		$lang['MYPROFILE_MOBILE_NUM'] = 'Numero di cellulare';
		$lang['MYPROFILE_GENDER'] = 'Genere';
		$lang['MYPROFILE_GENDER_0'] = 'Seleziona...';
		$lang['MYPROFILE_GENDER_1'] = 'Maschio';
		$lang['MYPROFILE_GENDER_2'] = 'Femmina';
		$lang['MYPROFILE_BIRTH_DATE'] = 'Data di nascita:';
		$lang['MYPROFILE_CITY'] = 'Citt&agrave; :';
		$lang['MYPROFILE_POSTAL_CODE'] = 'Codice Postale:';
		$lang['MYPROFILE_COUNTRY'] = 'Nazione:';
		$lang['MY_PROFILE_FIRST_NAME_LENGTH_ERR'] = "Il campo nome pu&ograve avere al massimo 60 caratteri";
		$lang['MY_PROFILE_FIRST_NAME_PATTERN_ERR'] = 'Il campo nome contiene caratteri non permessi';
		$lang['MY_PROFILE_LAST_NAME_LENGTH_ERR'] = "Il campo cognome pu&ograve avere al massimo 60 caratteri";
		$lang['MY_PROFILE_LAST_NAME_PATTERN_ERR'] = "Il campo cognome contiene caratteri non permessi";
		$lang['MY_PROFILE_MOB_NUM_LENGTH_ERR'] = "Il campo numero di cellulare deve avere almeno 7 caratteri e non pu&ograve di 15";
		$lang['MY_PROFILE_MOB_NUM_PATTERN_ERR'] = "Il campo numero di cellulare contiene caratteri non permessi";
		$lang['MY_PROFILE_DATE_OF_BIRTH_ERR'] = "Il campo data di nascita ha un formato errato";
		$lang['MY_PROFILE_CITY_LENGTH_ERR'] = "Il campo citt&agrave; deve avere almeno 3 caratteri e non pu&ograve di 60";
		$lang['MY_PROFILE_CITY_PATTERN_ERR'] = "Il campo citta' contiene caratteri non permessi";
		$lang['MY_PROFILE_POSTCODE_LEN_ERR'] = 'Il campo codice postale deve avere almeno 3 caratteri e non pu&ograve di 10';
		$lang['MY_PROFILE_COUNTRY_LENGTH_ERR'] = "Il campo nazione deve avere almeno 3 caratteri e non pu&ograve di 60";
		$lang['MY_PROFILE_COUNTRY_PATTERN_ERR'] = "Il campo nazione contiene caratteri non permessi";
		$lang['MYPROFILE_MY_REVIEW'] = 'Le mie recensioni';
		
		// SHOW_PROFILE
		$lang['SHOWPROFILE_PAGE_TITLE'] = 'Profile Utente';
		$lang['SHOWPROFILE_MY_INFO'] = 'Informazioni Utente';
		$lang['SHOWPROFILE_USER_REVIEW'] = 'Recensioni Utente';
		
		// SETTING
		$lang['SETTING_PAGE_TITLE'] = 'Impostazioni';
		$lang['SETTING_PAGE_H3'] = 'Impostazioni';
		$lang['SETTING_PAGE_DISCL'] = 'Cambia le tue impostazioni';
		$lang['SETTING_ACCOUNT'] = 'Account';
		$lang['SETTING_ACCOUNT_NAME'] = 'Nome utente:';
		$lang['SETTING_ACCOUNT_EMAIL'] = 'Email:';
		$lang['SETTING_ACCOUNT_LANG'] = 'Lingua:';
		$lang['SETTING_PASSWORD'] = 'Password';
		$lang['SETTING_PASSWORD_CHANGE'] = 'Password:';
		$lang['SETTING_PASSWORD_NEW'] = 'Nuova Password:';
		$lang['SETTING_PASSWORD_REPEAT'] = 'Repeti Nuova Password:';
		$lang['SETTING_PWD_ERROR'] = 'Passoword errata';
		$lang['SETTING_PWD_REPET_ERROR'] = 'Le passoword non coincidono';
		$lang['SETTING_PWD_MISSING'] = 'Campo password mancante';
		$lang['SETTING_PRIVACY'] = 'Privacy';
		$lang['SETTING_PROFILE_TYPE'] = 'Tipo di Profilo';
		$lang['SETTING_PROFILE_TYPE_0'] = 'Pubblico'; 
		$lang['SETTING_PROFILE_TYPE_1'] = 'Privato';
		
		// CREATE REVIEW
		$lang['CREATE_REVIEW_PAGE_TOP_MSG_OK'] = 'Recensione creata con successo';
		$lang['CREATE_REVIEW_PAGE_TITLE'] = 'Recensisci un sito';
		$lang['CREATE_REVIEW_PAGE_H3'] = 'Recensisci un sito';
		$lang['CREATE_REVIEW_PAGE_DISCL'] = 'Recensisci un sito in pochi secondi: compila i campi di seguito, allega foto, video o altri file utili, e la tua recensione sar&agrave; subito visibile!';
		$lang['CREATE_REVIEW_TAB_REV_TITLE'] = 'Recensione';
		$lang['CREATE_REVIEW_TITLE_ESSENTIAL'] = 'Informazioni Indispensabili';
		$lang['CREATE_REVIEW_TAB_PIC_TITLE'] = 'Foto';
		$lang['CREATE_REVIEW_TAB_VID_TITLE'] = 'Video';
		$lang['CREATE_REVIEW_TAB_DOC_TITLE'] = 'Documenti';
		$lang['CREATE_REVIEW_FIELD_CATEG'] = 'Scegli una categoria';
		$lang['CREATE_REVIEW_FIELD_COUNTRY'] = 'Nazione';
		$lang['CREATE_REVIEW_FIELD_LOCALITY'] = 'Luogo';
		$lang['CREATE_REVIEW_FIELD_SITE_NAME'] = 'Nome del Sito';
		$lang['CREATE_REVIEW_FIELD_DSCR'] = 'Scrivi la tua recensione';
		$lang['CREATE_REVIEW_FIELD_ARRIVE'] = 'Come Arrivare';
		$lang['CREATE_REVIEW_TITLE_OTHER'] = 'Ulteriori Informazioni';
		$lang['CREATE_REVIEW_FIELD_WARN'] = 'Avvertenze';
		$lang['CREATE_REVIEW_FIELD_MYTH'] = 'Miti da sfatare';
		$lang['CREATE_REVIEW_FIELD_WHEAT'] = 'Dove Mangiare';
		$lang['CREATE_REVIEW_FIELD_COOK'] = 'Cucina';
		$lang['CREATE_REVIEW_FIELD_WHSTAY'] = 'Dove Alloggiare';
		$lang['CREATE_REVIEW_FIELD_VOTE'] = 'Valutazione';
		$lang['CREATE_REVIEW_FIELD_COVER'] = 'Immagine di copertina';
		$lang['CREATE_REVIEW_EMPTY_CATREV_ERR'] = 'Selezionare una categoria valida';
		$lang['CREATE_REVIEW_LOCALITY_EMPTY_ERR'] = 'Localit&agrave; mancante';
		$lang['CREATE_REVIEW_LCOALITY_LENGTH_ERR'] = 'Il campo localit&agrave; deve contenere almeno 3 caratteri e non più di 80';
		$lang['CREATE_REVIEW_SITE_EMPTY_ERR'] = 'Nome del sito mancante';
		$lang['CREATE_REVIEW_EMPTY_DS_ERR'] = 'Descrizione mancante';
		$lang['CREATE_REVIEW_SITE_LENGTH_ERR'] = 'Il nome del sito deve contenere almeno 3 caratteri e non più di 40';
		$lang['CREATE_REVIEW_SITE_EXISTS_ERR'] = 'Il nome del sito che hai scelto &egrave;  gi&agrave;  in uso. Scegliere un altro nome del sito e riprovare';
		$lang['CREATE_REVIEW_DS_LENGTH_ERR'] = 'La descrizione deve contenere almeno 50 caratteri e non più di 2000';
		$lang['CREATE_REVIEW_ARRIVE_LENGTH_MIN_ERR'] = 'Una buona recensione su come arrivare dovrebbe contenere almeno 25 caratteri';
		$lang['CREATE_REVIEW_ARRIVE_LENGTH_MAX_ERR'] = 'Inserire al massimo 500 caratteri';
		$lang['CREATE_REVIEW_WARN_LENGTH_MIN_ERR'] = 'Un buon avvertimento dovrebbe contenere almeno 10 caratteri';
		$lang['CREATE_REVIEW_WARN_LENGTH_MAX_ERR'] = 'Inserire al massimo 100 caratteri';
		$lang['CREATE_REVIEW_WTEAT_LENGTH_MIN_ERR'] = 'Una buona recensione su dove mangiare dovrebbe contenere almeno 25 caratteri';
		$lang['CREATE_REVIEW_WTEAT_LENGTH_MAX_ERR'] = 'Inserire al massimo 500 caratteri';
		$lang['CREATE_REVIEW_COOK_LENGTH_MIN_ERR'] = 'Una buona recensione sulla cucina del posto dovrebbe contenere almeno 25 caratteri';
		$lang['CREATE_REVIEW_COOK_LENGTH_MAX_ERR'] = 'Inserire al massimo 500 caratteri';
		$lang['CREATE_REVIEW_WHSTAY_LENGTH_MIN_ERR'] = 'Una buona recensione su dove alloggiare dovrebbe contenere almeno 25 caratteri';
		$lang['CREATE_REVIEW_WHSTAY_LENGTH_MAX_ERR'] = 'Inserire al massimo 500 caratteri';
		$lang['CREATE_REVIEW_MYTH_LENGTH_MIN_ERR'] = 'Per sfatare un mito inserisci almeno 10 caratteri';
		$lang['CREATE_REVIEW_MYTH_LENGTH_MAX_ERR'] = 'Inserire al massimo 100 caratteri';
		$lang['CREATE_REVIEW_COVER_ERR'] = 'Caricare un\'immagine di copertina';
		$lang['CREATE_REVIEW_SUBMIT_VAL'] = 'Crea Recensione';
		$lang['CREATE_REVIEW_CHANGE_VAL'] = 'Cambia qualcosa';
		$lang['CREATE_REVIEW_COMPLETE_VAL'] = 'Fine';
		$lang['CREATE_REVIEW_NO_IMG_TXT1'] = 'Non hai caricato ancora nessuna foto';
		$lang['CREATE_REVIEW_NO_IMG_TXT2'] = 'Clicca sul pulsante per caricare subito la tua prima foto';
		$lang['CREATE_REVIEW_IMG_TXT1'] = 'Foto Allegate';
		$lang['CREATE_REVIEW_IMG_TXT2'] = 'Clicca sul pulsante per aggiungere nuove foto';
		$lang['CREATE_REVIEW_IMG_BT_TITLE'] = 'aggiungi una foto';
		$lang['CREATE_REVIEW_IMG_WAIT_LOAD'] = 'Attendere prego';
		$lang['CREATE_REVIEW_NO_MOV_TXT1'] = 'Non hai caricato ancora nessun video';
		$lang['CREATE_REVIEW_NO_MOV_TXT2'] = 'Clicca sul pulsante per caricare il tuo primo video';
		$lang['CREATE_REVIEW_MOV_TXT1'] = 'Video Allegati';
		$lang['CREATE_REVIEW_MOV_TXT2'] = 'Clicca sul pulsante per aggiungere altri video';
		$lang['CREATE_REVIEW_MOV_WAIT_LOAD'] = 'Attendere prego, il caricamento potrebbe richiedere alcuni minuti!';
		$lang['CREATE_REVIEW_NO_DOC_TXT1'] = 'Non hai caricato ancora nessun documento';
		$lang['CREATE_REVIEW_NO_DOC_TXT2'] = 'Clicca sul pulsante per caricare il tuo primo documento';
		$lang['CREATE_REVIEW_DOC_TXT1'] = 'Documenti Alleagti';
		$lang['CREATE_REVIEW_DOC_TXT2'] = 'Clicca sul pulsante per aggiungere altri documenti';
		$lang['CREATE_REVIEW_DOC_BT_TITLE'] = 'aggiungi un documento';
		$lang['CREATE_REVIEW_IMG_TELL_US'] = 'raccontaci questa foto...';
		$lang['CREATE_REVIEW_MOV_TELL_US'] = 'raccontaci questo video...';
		$lang['CREATE_REVIEW_DOC_TELL_US'] = 'raccontaci questo documento...';
		$lang['CREATE_REVIEW_CARTOON_CATEGORY'] = 'Seleziona la categora alla quale appartiene il sito';
		$lang['CREATE_REVIEW_CARTOON_SITE'] = 'Scrivi il nome del sito da recensire. Ad esempio "San Giovanni in Laterano", "Monte Fuji", "Castello di Neuschwanstein", "Piazza Navora" o ancora "Manhattan"';
		$lang['CREATE_REVIEW_CARTOON_LOCALITY'] = 'Scrivi l\'indirizzo del sito. Ad esempio "Piazza San Giovanni in Laterano, 4 Roma Italia". Per i siti più famosi può essere sufficiente anche solo il nome della città o della nazione. As esempio, se stai recensendo il Monte Fuji, puoi scrivere nel campo località "Giappone", per la Basilica di San Giovanni in Laterano scrivi semplicemente "Roma"';
		$lang['CREATE_REVIEW_CARTOON_REVIEW'] = 'Scrivi un\'approfondita e dettagliata recensione del sito';
		$lang['CREATE_REVIEW_CARTOON_ARRIVE'] = 'Scrivi come raggiungere il sito; se il sito si trova in una grande citt&agrave; descrivi quali mezzi di trasporto urbani (metropolitana, autobus) portano al sito. Se il sito si trova lontano dai grandi centri indica i mezzi extraurbani partendo dalla principale città pi&ugrave; vicina al sito. Se preferisci, descrivi anche come raggiungere la citt&agrave; dove si trova il sito o la citt&agrave; ad esso pi&ugrave; vicina';
		$lang['CREATE_REVIEW_CARTOON_WARN'] = 'Se hai un consiglio importante allora inserisci qu&igrave il tuo suggerimento';
		$lang['CREATE_REVIEW_CARTOON_EAT'] = 'Consiglia, se conosci, uno o pi&ugrave; locali dove poter mangiare. Sarebbe opportuno indicare la tipologia di locale (pizzeria, trattoria, cucina tipica...), i piatti tipici e la fascia di prezzo (economico, normale, caro...)';
		$lang['CREATE_REVIEW_CARTOON_COOK'] = 'Descrivi la cucina tipica del posto indicando magari i piatti tipici che conosci';
		$lang['CREATE_REVIEW_CARTOON_STAY'] = 'Consiglia, se conosci, uno o pi&ugrave; posti dove alloggiare. Sarebbe opportuno indicare la tipologia di alloggio (b&b, bungalow, guest house, bungalow, hotel 3 stelle...) e la fascia di prezzo (economico, normale, caro...)';
		$lang['CREATE_REVIEW_CARTOON_MYTH'] = 'Se vuoi sfatare qualche mito allora inserisci un tuo commento. N.B. Inserisci un commento solo se hai avuto un\'esperienza significativa tale da poter sfatare dei luoighi comuni noti che ritieni non veri';
		$lang['CREATE_REVIEW_CARTOON_VOTE'] = 'Dai un voto da 1 a 5 per questo sito';
		$lang['CREATE_REVIEW_ADDRS_NOT_VALID'] = 'Sito o indirizzo non validi. Verifica il nome del sito e la localit&agrave; e riprova';
		
		// CREATE REVIEW STEP2 
		$lang['CREATE_REVIEW_S2_PAGE_TITLE'] = 'Verifica i dati';
		$lang['CREATE_REVIEW_S2_PAGE_H3'] = 'Verifica i dati';
		$lang['CREATE_REVIEW_S2_PAGE_DISCL'] = 'Verifica i dati e se tutto OK clicca sul pulsante Fine!';
		$lang['CREATE_REVIEW_S2_REVIEW'] = 'La tua recensione';
		$lang['CREATE_REVIEW_S2_FIELD_CATEG'] = 'Categoria';
		
		// CREATE CITY REVIEW
		$lang['CREATE_CITY_REV_PAGE_TOP_MSG_ERROR'] = 'Attenzione: per poter recensire una citt&agrave &egrave; necessario recensire almeno un sito di interesse di questa citt&agrave. Per proseguire, dal men&ugrave; \'Recensioni\' clicca su \'Recensisci un sito\'.';
		$lang['CREATE_CITY_REV_PAGE_TOP_MSG_OK'] = 'Recensione creata con successo';
		$lang['CREATE_CITY_REV_PAGE_TITLE'] = 'Recensisci una citt&agrave;';
		$lang['CREATE_CITY_REV_PAGE_H3'] = 'Recensisci una citt&agrave;';
		$lang['CREATE_CITY_REV_PAGE_DISCL'] = 'Recensisci una citt&agrave; in pochi secondi: compila i campi di seguito, aggiungi almeno un sito da visitare, allega foto, video o altri file utili, e la tua recensione sar&agrave; subito visibile!';
		$lang['CREATE_CITY_REV_TAB_REV_TITLE'] = 'Recensione';
		$lang['CREATE_CITY_REV_TITLE_ESSENTIAL'] = 'Informazioni Indispensabili';
		$lang['CREATE_CITY_REV_TAB_PIC_TITLE'] = 'Foto';
		$lang['CREATE_CITY_REV_TAB_VID_TITLE'] = 'Video';
		$lang['CREATE_CITY_REV_TAB_DOC_TITLE'] = 'Documenti';
		$lang['CREATE_CITY_REV_FIELD_CITY_NAME'] = 'Nome della Citt&agrave;';
		$lang['CREATE_CITY_REV_FIELD_COUNTRY'] = 'Nazione';
		$lang['CREATE_CITY_REV_FIELD_DSCR'] = 'Scrivi la tua recensione su questa citt&agrave;';
		$lang['CREATE_CITY_REV_FIELD_COVER'] = 'Immagine di copertina';
		$lang['CREATE_CITY_REV_FIELD_HOW_TO_ARR'] = 'Come raggiungere il centro citt&agrave;';
		$lang['CREATE_CITY_WHAT_TO_SEE'] = 'Cosa Vedere';
		$lang['CREATE_CITY_WHAT_TO_SEE_ADD'] = 'Aggiungi un sito da visitare';
		$lang['CREATE_CITY_REV_TITLE_OTHER'] = 'Ulteriori Informazioni';
		$lang['CREATE_CITY_REV_FIELD_WARN'] = 'Avvertenze';
		$lang['CREATE_CITY_REV_FIELD_MYTH'] = 'Miti da sfatare';
		$lang['CREATE_CITY_REV_FIELD_WHEAT'] = 'Dove Mangiare';
		$lang['CREATE_CITY_REV_FIELD_VOTE'] = 'Valutazione';
		$lang['CREATE_CITY_REV_SUBMIT_VAL'] = 'Crea Recensione';
		$lang['CREATE_CITY_REV_CHANGE_VAL'] = 'Cambia qualcosa';
		$lang['CREATE_CITY_REV_COMPLETE_VAL'] = 'Fine';
		$lang['CREATE_CITY_REV_CITY_EMPTY_ERR'] = 'Campo citt&agrave; mancante';
		$lang['CREATE_CITY_REV_CITY_LENGTH_ERR'] = 'Il campo citt&agrave; deve contenere almeno 3 caratteri e non più di 60';
		$lang['CREATE_CITY_REV_COUNTRY_EMPTY_ERR'] = 'Campo Nazione mancante';
		$lang['CREATE_CITY_REV_COUNTRY_ERR'] = 'Campo Nazione errato';
		$lang['CREATE_CITY_REV_EMPTY_DS_ERR'] = 'Recensione mancante';
		$lang['CREATE_CITY_REV_DS_LENGTH_ERR'] = 'La recensione deve contenere almeno 50 caratteri e non più di 2000';
		$lang['CREATE_CITY_REV_COVER_ERR'] = 'Caricare un\'immagine di copertina';
		$lang['CREATE_CITY_REV_EMPTY_ARRIVE_ERR'] = 'Descrizione mancante';
		$lang['CREATE_CITY_REV_ARRIVE_LENGTH_ERR']=  'La descrizione su come raggiungere la citt&agrave; deve contenere almeno 50 caratteri e non più di 1000';
		$lang['CREATE_CITY_REV_WARN_LENGTH_MIN_ERR'] = 'Un buon avvertimento dovrebbe contenere almeno 10 caratteri';
		$lang['CREATE_CITY_REV_WARN_LENGTH_MAX_ERR'] = 'Inserire al massimo 100 caratteri';
		$lang['CREATE_CITY_REV_WTEAT_LENGTH_MIN_ERR'] = 'Una buona recensione su dove mangiare dovrebbe contenere almeno 25 caratteri';
		$lang['CREATE_CITY_REV_WTEAT_LENGTH_MAX_ERR'] = 'Inserire al massimo 500 caratteri';
		$lang['CREATE_CITY_COOK_LENGTH_MIN_ERR'] = 'Una buona recensione sulla cucina del posto dovrebbe contenere almeno 25 caratteri';
		$lang['CREATE_CITY_COOK_LENGTH_MAX_ERR'] = 'Inserire al massimo 500 caratteri';
		$lang['CREATE_CITY_WHSTAY_LENGTH_MIN_ERR'] = 'Una buona recensione su dove alloggiare dovrebbe contenere almeno 25 caratteri';
		$lang['CREATE_CITY_WHSTAY_LENGTH_MAX_ERR'] = 'Inserire al massimo 500 caratteri';
		$lang['CREATE_CITY_REV_MYTH_LENGTH_MIN_ERR'] = 'Per sfatare un mito inserisci almeno 10 caratteri';
		$lang['CREATE_CITY_REV_MYTH_LENGTH_MAX_ERR'] = 'Inserire al massimo 100 caratteri';
		$lang['CREATE_CITY_REV_INTER_EMPTY_ERR'] = 'Inserisci almeno un sito da visitare';
		$lang['CREATE_CITY_CARTOON_CITY'] = 'Scrivi il nome della citt&agrave; da recensire. Se il nome &egrave; ambiguo, aggiungi anche la nazione. Ad esempio "Parigi Texas Stati Uniti" oppure "Los Angeles Cile"';
		$lang['CREATE_CITY_CARTOON_COUNTRY'] = 'Scrivi il nome della nazione dove si trova la citt&agrave;';
		$lang['CREATE_CITY_CARTOON_REVIEW'] = 'Scrivi un\'approfondita e dettagliata recensione della citt&agrave;';
		$lang['CREATE_CITY_CARTOON_ARRIVE'] = 'Scrivi come raggiungere il centro della citt&agrave;; da almeno uno delle tre principali infrastrutture di trasporto: l\'aeroporto, la ferrovia, il porto. Se &egrave; necessario usare altri mezzi descrivi accuratamente tutte le informazioni utili per chi legge. Se conosci pi&ugrave; modi per raggiungere il centro descrivi entrambi indicando quellì pi&ugrave; veloci, quelli pi&ugrave; economici, e quelli pi&ugrave; confortevoli';
		$lang['CREATE_CITY_CARTOON_INTEREST'] = 'Aggiungi le recensioni dei siti di questa citt&agrave; che consigli da visitare';
		$lang['CREATE_CITY_CARTOON_WARN'] = 'Se hai un consiglio importante allora inserisci qu&igrave il tuo suggerimento';
		$lang['CREATE_CITY_CARTOON_EAT'] = 'Consiglia, se conosci, uno o pi&ugrave; locali dove poter mangiare. Sarebbe opportuno indicare la tipologia di locale (pizzeria, trattoria, cucina tipica...), i piatti tipici e la fascia di prezzo (economico, normale, caro...)';
		$lang['CREATE_CITY_CARTOON_MYTH'] = 'Se vuoi sfatare qualche mito allora inserisci un tuo commento. N.B. Inserisci un commento solo se hai avuto un\'esperienza significativa tale da poter sfatare dei luoighi comuni noti che ritieni non veri';
		$lang['CREATE_CITY_CARTOON_VOTE'] = 'Dai un voto da 1 a 5 per questa citt&agrave;';
		$lang['CREATE_CITY_ADDRS_NOT_FOUND'] = 'Citt&agrave; non trovata';
		
		// CREATE CITY INTEREST
		$lang['CREATE_CITY_INTEREST_TITLE']='Seleziona ed aggiungi i luoghi di interesse';
		$lang['CREATE_CITY_INTEREST_ADD_BUT']='Aggiugi selezionati';
		$lang['CREATE_CITY_INTEREST_CANC_BUT']='Chiudi';
		$lang['CREATE_CITY_INTEREST_ZERO_SELECT_ERROR']='Scegliere almeno un luogo da visitare';
		$lang['CREATE_CITY_INTEREST_MAX_SELECT_ERROR']='Scegliere non pi&ugrave; di 50 luoghi da visitare';
		$lang['CREATE_CITY_INTEREST_SELECT']='Seleziona';
		$lang['CREATE_CITY_INTEREST_COVER']='Copertina';
		$lang['CREATE_CITY_INTEREST_NAME']='Nome del sito';
		$lang['CREATE_CITY_INTEREST_NO_SITE'] = 'Attenzione!!! nessun sito recensito';
		$lang['CREATE_CITY_INTEREST_TO_CONTINUE'] = 'Per proseguire recensisci almeno un sito di interesse da visitare in questa citt&agrave;';
		
		// CREATE CITY STEP2 
		$lang['CREATE_CITY_REV_S2_PAGE_TITLE'] = 'Verifica i dati';
		$lang['CREATE_CITY_REV_S2_PAGE_H3'] = 'Verifica i dati';
		$lang['CREATE_CITY_REV_S2_PAGE_DISCL'] = 'Verifica i dati e se tutto OK clicca sul pulsante Fine!';
		$lang['CREATE_CITY_REV_S2_REVIEW'] = 'La tua recensione';
		
		// CREATE COUNTRY REVIEW
		$lang['CREATE_COUNTRY_REV_PAGE_TOP_MSG_ERROR'] = 'Attenzione: per poter recensire una nazione &egrave; necessario recensire almeno una citt&agrave di questa nazione. Per proseguire, dal men&ugrave; \'Recensioni\' clicca su \'Recensisci una citt&agrave\'.';
		$lang['CREATE_COUNTRY_REV_PAGE_TOP_MSG_OK'] = 'Recensione creata con successo';
		$lang['CREATE_COUNTRY_REV_PAGE_TITLE'] = 'Recensisci una nazione o una regione';
		$lang['CREATE_COUNTRY_REV_PAGE_H3'] = 'Recensisci una nazione o una regione';
		$lang['CREATE_COUNTRY_REV_PAGE_DISCL'] = 'Recensisci una nazione o una regione in pochi secondi: compila i campi di seguito, aggiungi almeno una citt&grave; da visitare, allega foto, video o altri file utili, e la tua recensione sar&agrave; subito visibile!';
		$lang['CREATE_COUNTRY_REV_TAB_REV_TITLE'] = 'Recensione';
		$lang['CREATE_COUNTRY_REV_TITLE_ESSENTIAL'] = 'Informazioni Indispensabili';
		$lang['CREATE_COUNTRY_REV_TAB_PIC_TITLE'] = 'Foto';
		$lang['CREATE_COUNTRY_REV_TAB_VID_TITLE'] = 'Video';
		$lang['CREATE_COUNTRY_REV_TAB_DOC_TITLE'] = 'Documenti';
		$lang['CREATE_COUNTRY_REV_FIELD_COUNTRY_NAME'] = 'Nome della nazione';
		$lang['CREATE_COUNTRY_REV_FIELD_COUNTRY'] = 'Nazione';
		$lang['CREATE_COUNTRY_REV_FIELD_DSCR'] = 'Scrivi la tua recensione su questa nazione o regione';
		$lang['CREATE_COUNTRY_REV_FIELD_COVER'] = 'Immagine di copertina';
		$lang['CREATE_COUNTRY_REV_FIELD_HOW_TO_ARR'] = 'Come arrivare';
		$lang['CREATE_COUNTRY_WHAT_TO_SEE'] = 'Cosa Vedere';
		$lang['CREATE_COUNTRY_WHAT_TO_SEE_ADD'] = 'Aggiungi le citt&agrave; da visitare da te recensite';
		$lang['CREATE_COUNTRY_REV_TITLE_OTHER'] = 'Ulteriori Informazioni';
		$lang['CREATE_COUNTRY_REV_FIELD_WARN'] = 'Avvertenze';
		$lang['CREATE_COUNTRY_REV_FIELD_MYTH'] = 'Miti da sfatare';
		$lang['CREATE_COUNTRY_REV_FIELD_VOTE'] = 'Valutazione';
		$lang['CREATE_COUNTRY_REV_SUBMIT_VAL'] = 'Crea Recensione';
		$lang['CREATE_COUNTRY_REV_CHANGE_VAL'] = 'Cambia qualcosa';
		$lang['CREATE_COUNTRY_REV_COMPLETE_VAL'] = 'Fine';
		$lang['CREATE_COUNTRY_REV_COUNTRY_EMPTY_ERR'] = 'Campo Nazione mancante';
		$lang['CREATE_COUNTRY_REV_COUNTRY_LENGTH_ERR'] = 'Inserisci al amssimo 50 caratteri';
		$lang['CREATE_COUNTRY_REV_EMPTY_DS_ERR'] = 'Descrizione mancante';
		$lang['CREATE_COUNTRY_REV_DS_LENGTH_ERR'] = 'La descrizione deve contenere almeno 50 caratteri e non più di 2000';
		$lang['CREATE_COUNTRY_REV_COVER_ERR'] = 'Caricare un\'immagine di copertina';
		$lang['CREATE_COUNTRY_REV_EMPTY_ARRIVE_ERR'] = 'Descrizione mancante';
		$lang['CREATE_COUNTRY_REV_ARRIVE_LENGTH_MIN_ERR'] = 'Una buona recensione su come arrivare dovrebbe contenere almeno 25 caratteri';
		$lang['CREATE_COUNTRY_REV_ARRIVE_LENGTH_MAX_ERR'] = 'Inserire al massimo 500 caratteri';
		$lang['CREATE_COUNTRY_REV_WARN_LENGTH_MIN_ERR'] = 'Un buon avvertimento dovrebbe contenere almeno 10 caratteri';
		$lang['CREATE_COUNTRY_REV_WARN_LENGTH_MAX_ERR'] = 'Inserire al massimo 100 caratteri';
		$lang['CREATE_COUNTRY_COOK_LENGTH_MIN_ERR'] = 'Una buona recensione sulla cucina del posto dovrebbe contenere almeno 25 caratteri';
		$lang['CREATE_COUNTRY_COOK_LENGTH_MAX_ERR'] = 'Inserire al massimo 500 caratteri';
		$lang['CREATE_COUNTRY_REV_MYTH_LENGTH_MIN_ERR'] = 'Per sfatare un mito inserisci almeno 10 caratteri';
		$lang['CREATE_COUNTRY_REV_MYTH_LENGTH_MAX_ERR'] = 'Inserire al massimo 100 caratteri';
		$lang['CREATE_COUNTRY_REV_INTER_EMPTY_ERR'] = 'Inserisci almeno una citt&agrave; da visitare';
		$lang['CREATE_COUNTRY_CARTOON_COUNTRY'] = 'Scrivi il nome della nazione o regione da recensire';
		$lang['CREATE_COUNTRY_CARTOON_REVIEW'] = 'Scrivi un\'approfondita e dettagliata recensione della nazione';
		$lang['CREATE_COUNTRY_CARTOON_INTEREST'] = 'Aggiungi le recensioni delle citt&agrave; di questa nazione o regione che consigli da visitare';
		$lang['CREATE_COUNTRY_CARTOON_WARN'] = 'Se hai un consiglio importante allora inserisci qu&igrave il tuo suggerimento';
		$lang['CREATE_COUNTRY_CARTOON_ARRIVE'] = 'Scrivi come raggiungere la nazione, indicando come raggiungere almeno una delle principali citt&agrave. Se conosci pi&ugrave; modi per raggiungere la nazione o la regione descrivi entrambi indicando quellì pi&ugrave; veloci, quelli pi&ugrave; economici, e quelli pi&ugrave; confortevoli';
		$lang['CREATE_COUNTRY_CARTOON_MYTH'] = 'Se vuoi sfatare qualche mito allora inserisci un tuo commento. N.B. Inserisci un commento solo se hai avuto un\'esperienza significativa tale da poter sfatare dei luoighi comuni noti che ritieni non veri';
		$lang['CREATE_COUNTRY_CARTOON_VOTE'] = 'Dai un voto da 1 a 5 per questa nazione o regione';
		$lang['CREATE_COUNTRY_ADDRS_NOT_FOUND'] = 'Nazione o regione non trovata';
		
		// CREATE COUNTRY INTEREST
		$lang['CREATE_COUNTRY_INTEREST_TITLE']='Seleziona ed aggiungi le citt&agrave; di interesse';
		$lang['CREATE_COUNTRY_INTEREST_ADD_BUT']='Aggiugi selezionati';
		$lang['CREATE_COUNTRY_INTEREST_CANC_BUT']='Chiudi';
		$lang['CREATE_COUNTRY_INTEREST_ZERO_SELECT_ERROR']='Scegliere almeno una citt&agrave; da visitare';
		$lang['CREATE_COUNTRY_INTEREST_MAX_SELECT_ERROR']='Scegliere non pi&ugrave; di 50 citt&agrave da visitare';
		$lang['CREATE_COUNTRY_INTEREST_SELECT']='Seleziona';
		$lang['CREATE_COUNTRY_INTEREST_COVER']='Copertina';
		$lang['CREATE_COUNTRY_INTEREST_NAME']='Nome della citt&agrave;';
		$lang['CREATE_COUNTRY_INTEREST_NO_SITE']='Attenzione!!! Nessuna citt&agrave; recensita';
		$lang['CREATE_COUNTRY_INTEREST_TO_CONTINUE'] = 'Per proseguire recensisci almeno una città di questa nazione';
		
		// CREATE COUNTRY STEP2 
		$lang['CREATE_COUNTRY_REV_S2_PAGE_TITLE'] = 'Verifica i dati';
		$lang['CREATE_COUNTRY_REV_S2_PAGE_H3'] = 'Verifica i dati';
		$lang['CREATE_COUNTRY_REV_S2_PAGE_DISCL'] = 'Verifica i dati e se tutto OK clicca sul pulsante Fine!';
		$lang['CREATE_COUNTRY_REV_S2_REVIEW'] = 'La tua recensione';
		
		// SEARCH REVIEW
		$lang['SEARCH_REVIEW_PAGE_TOP_MSG'] = 'Ricerca Avanzata';
		$lang['SEARCH_REVIEW_SEARCH_BUTTON'] = 'cerca';
		$lang['SEARCH_REVIEW_SEARCH_SITE_NAME'] = 'Inserisci le parole chiave da ricercare';
		$lang['SEARCH_REVIEW_SEARCH_TYPE_1'] = 'Recensioni di siti';
		$lang['SEARCH_REVIEW_SEARCH_TYPE_2'] = 'Recensioni di citt&agrave;';
		$lang['SEARCH_REVIEW_SEARCH_TYPE_3'] = 'Recensioni di nazioni/regioni';
		$lang['SEARCH_REVIEW_ADV_ONLY_IMG'] = 'Solo recensioni con immagini allegate';
		$lang['SEARCH_REVIEW_ADV_ONLY_MOV'] = 'Solo recensioni con video allegati';
		$lang['SEARCH_REVIEW_ADV_ONLY_DOC'] = 'Solo recensioni con documenti allegati';
		$lang['SEARCH_REVIEW_ADV_LANG'] = 'Contenuti in lingua';
		$lang['SEARCH_REVIEW_EMPTY_KWDS_ERROR'] = 'parola chiave mancante';
		$lang['SEARCH_REVIEW_EMPTY_KWDS_LENG_ERROR'] = 'inserire alemno 3 caratteri e non pi&ugrave; di 25';
		$lang['SEARCH_REVIEW_ILLEGAL_KWDS_ERROR'] = 'il carattere % non &egrave; consentito';
		
		// SEARCH REVIEW RESULT
		$lang['SEARCH_REVIEW_RESULT_TITLE'] = 'Risultati della ricerca';
		$lang['SEARCH_REVIEW_RESULT_BACK'] = 'Torna alla ricerca';
		$lang['SEARCH_REVIEW_RESULT_H1'] = 'Risultati della ricerca';
		$lang['SEARCH_REVIEW_RESULT_NO_RESULT'] = 'Nessun risultato per i criteri di ricerca impostati';
		$lang['SEARCH_REVIEW_RESULT_OVER_EVAL'] = 'Valutazione Complessiva';
		$lang['SEARCH_REVIEW_SEE_ALL'] = 'vedi tutti...';
		$lang['SEARCH_REVIEW_RESULT_AUTHOR'] = 'Autore';
		$lang['SEARCH_REVIEW_RESULT_REVIEW'] = 'Recensione:';
		$lang['SEARCH_REVIEW_RESULT_HOW_ARRIVE'] = 'Come arrivare:';
		$lang['SEARCH_REVIEW_RESULT_TO_VISIT'] = 'Cosa visitare:';
		$lang['SEARCH_REVIEW_RESULT_WARN'] = 'Avvertenze:';
		$lang['SEARCH_REVIEW_RESULT_WHERE_TO_EAT'] = 'Dove mangiare:';
		$lang['SEARCH_REVIEW_RESULT_COOKING'] = 'Cucina:';
		$lang['SEARCH_REVIEW_RESULT_WHERE_TO_STAY'] = 'Dove alloggiare:';
		$lang['SEARCH_REVIEW_RESULT_MITH'] = 'Miti da sfatare'; 
		$lang['SEARCH_REVIEW_RESULT_VOTE'] = 'Valutazione:';
		$lang['SEARCH_REVIEW_RESULT_PAGIN_RESULT'] = 'Risultati trovati:';
		$lang['SEARCH_REVIEW_RESULT_PAGIN_BACK'] = '< Indietro';
		$lang['SEARCH_REVIEW_RESULT_PAGIN_NEXT'] = 'Avanti >';
		
		// SEARCH REVIEW MULTI RESULT
		$lang['SEARCH_REVIEW_MULTI_RES_TITLE'] = 'Risultati della ricerca';
		$lang['SEARCH_REVIEW_MULTI_RES_BACK'] = 'Torna alla ricerca';
		$lang['SEARCH_REVIEW_MULTI_RES_H1'] = 'Risultati della ricerca';
		$lang['SEARCH_REVIEW_MULTI_RES_H2'] = 'Abbiamo trovato pi&ugrave; risultati per la tua ricerca. Scegline uno tra l\'elenco...';
		$lang['SEARCH_REVIEW_MULTI_RES_SITE'] = 'Nome del sito';
		$lang['SEARCH_REVIEW_MULTI_RES_LOC'] = 'Localit&agrave';
		$lang['SEARCH_REVIEW_MULTI_RES_COUNTRY'] = 'Nazione';
		$lang['SEARCH_REVIEW_MULTI_RES_CITY'] = 'Citt&agrave';
		
		// MY REVIEW
		$lang['MY_REV_PAGE_TITLE'] = 'Le mie Recensioni';
		$lang['MY_REV_PAGE_H3'] = 'Le mie recensioni';
		$lang['MY_REV_PAGE_DISCL'] = 'In questa pagine puoi trovare la lista delle tue recensioni';
		$lang['MY_REV_SITE_TITLE'] = 'Siti recensiti';
		$lang['MY_REV_NO_REV'] = 'Nessun sito recensito';
		$lang['MY_REV_CITY_TITLE'] = 'Citt&agrave; recensite';
		$lang['MY_REV_NO_CITY_REV'] = 'Nessuna citt&agrave; recensita';
		$lang['MY_REV_COUNTRY_TITLE'] = 'Nazioni recensite';
		$lang['MY_REV_NO_COUNTRY_REV'] = 'Nessuna nazione recensita';
		$lang['MY_REV_CREATE_REV'] = 'Crea una Recensione';
		$lang['MY_REV_DT_INS'] = 'Data di creazione';
		$lang['MY_REV_SITE'] = 'Sito';
		$lang['MY_REV_DESCR'] = 'Recensione';
		
		// SHOW REVIEW
		$lang['SHOW_REVIEW_PAGE_TITLE_NOT_FOUND'] = 'Recensione non trovato';
		$lang['SHOW_REVIEW_PAGE_H3_NOT_FOUND'] = 'Recensione non trovata';
		$lang['SHOW_REVIEW_PAGE_DISCL_NOT_FOUND'] = 'Impossibile trovare la recensione. Si &egrave;  verificato un errore inatteso.<br>La recensione potrebbe essere stata rimossa.<br>Prova a ritornare nelle tue recensioni e clicca nuovamente sulla recensione';
		$lang['SHOW_REVIEW_NO_IMG_MESSAGE'] = 'Non ci sono immagini da visualizzare';
		$lang['SHOW_REVIEW_SHOW_IMG_MESSAGE'] = 'Immagini della recensione';
		$lang['SHOW_REVIEW_LOAD_NEW_IMG'] = 'Carica nuove immagini';
		$lang['SHOW_REVIEW_NO_MOV_MESSAGE'] = 'Non ci sono video da visualizzare';
		$lang['SHOW_REVIEW_SHOW_MOV_MESSAGE'] = 'Video della recensione';
		$lang['SHOW_REVIEW_LOAD_NEW_MOV'] = 'Carica nuovi video';
		$lang['SHOW_REVIEW_NO_DOC_MESSAGE'] = 'Non ci sono documenti da visualizzare';
		$lang['SHOW_REVIEW_SHOW_DOC_MESSAGE'] = 'Documenti della recensione';
		$lang['SHOW_REVIEW_LOAD_NEW_DOC'] = 'Carica nuovi docuemnti';
		$lang['SHOW_REVIEW_IMG_NO_COMMENT'] = 'Nessun commento disponibile per questa immagine';
		$lang['SHOW_REVIEW_MOV_NO_COMMENT'] = 'Nessun commento disponibile per questo video';
		$lang['SHOW_REVIEW_DOC_NO_COMMENT'] = 'Nessun commento disponibile per questo documento';
		$lang['SHOW_REVIEW_LOCALITY'] = 'Localit&agrave;:';
		$lang['SHOW_REVIEW_REVIEW'] = 'Recensione:';
		$lang['SHOW_REVIEW_HOW_ARRIVE'] = 'Come arrivare:';
		$lang['SHOW_REVIEW_TO_VISIT'] = 'Cosa visitare:';
		$lang['SHOW_REVIEW_WARNING'] = 'Avvertenze:';
		$lang['SHOW_REVIEW_WHERE_EAT'] = 'Dove mangiare:';
		$lang['SHOW_REVIEW_COOKING'] = 'Cucina:';
		$lang['SHOW_REVIEW_WHERE_STAY'] = 'Dove alloggiare:';
		$lang['SHOW_REVIEW_MYTH'] = 'Miti da sfatare:';
		$lang['SHOW_REVIEW_VOTE'] = 'Valutazione:';
		
		//STAR SEE ORDER 
		$lang['SSO_POST_COMMENT'] = 'Posta un commento (massimo 140 caratteri)';
		$lang['SSO_POST_SEND'] = 'Posta';
		$lang['SSO_ORDER_BY'] = 'Ordina per:';
		$lang['SSO_ORDER_DATA'] = 'Data Decrescente';
		$lang['SSO_ORDER_DATA_DESC'] = 'Data Crescente';
		$lang['SSO_ORDER_VOTE'] = 'Voto Decrescente';
		$lang['SSO_ORDER_VOTE_DESC'] = 'Voto Crescente';
		$lang['SSO_ORDER_STAR'] = 'Stelle Decrescenti:';
		$lang['SSO_ORDER_STAR_DESC'] = 'Stelle Crescente';
		
		// MESSAGES
		$lang['MESSAGE_TOP_MSG_OK'] = 'Messaggio inviato con successo';
		$lang['MESSAGE_TOP_MSG_DFT_OK'] = 'Messaggio salvato in bozze con successo';
		$lang['MESSAGE_PAGE_TITLE'] = 'Messaggi';
		$lang['MESSAGE_PAGE_H3'] = 'Messaggi';
		$lang['MESSAGE_PAGE_DISCL'] = 'Invia e ricevi messaggi con gli utenti';
		$lang['MESSAGE_NEW'] = 'Nuovo Messaggio';
		$lang['MESSAGE_TAB_SENT'] = 'Inviati';
		$lang['MESSAGE_TAB_IN'] = 'Ricevuti';
		$lang['MESSAGE_TAB_DRAFT'] = 'Bozze';
		$lang['MESSAGE_TAB_TRASH'] = 'Cestino';
		$lang['MESSAGE_SENT_H3'] = 'Messaggi Inviati';
		$lang['MESSAGE_IN_H3'] = 'Messaggi Ricevuti';
		$lang['MESSAGE_DRAFT_H3'] = 'Messaggi in bozza';
		$lang['MESSAGE_TRASH_H3'] = 'Messaggi Cancellati';
		$lang['MESSAGE_NO_SENT_MSG'] = 'Nessun messaggio inviato';
		$lang['MESSAGE_NO_RECEV_MSG'] = 'Nessun messaggio ricevuto';
		$lang['MESSAGE_NO_DRAFT_MSG'] = 'Nessun messaggio in bozza';
		$lang['MESSAGE_NO_TRASH_MSG'] = 'Nessun messaggio nel cestino';
		$lang['MESSAGE_COMMON_FROM'] = 'Da';
		$lang['MESSAGE_COMMON_TO'] = 'A';
		$lang['MESSAGE_COMMON_DT'] = 'Data';
		$lang['MESSAGE_COMMON_SBJ'] = 'Oggetto';
		$lang['MESSAGE_COMMON_MSG'] = 'Mesaggio';
		$lang['MESSAGE_OVERLAY_RECIPIENT'] = 'Destinatario';
		$lang['MESSAGE_BACK_LINK'] = 'Torna ai messaggi';
		$lang['MESSAGE_OVERLAY_SAVE'] = 'Salva Messaggio';
		$lang['MESSAGE_OVERLAY_SEND'] = 'Invia Messaggio';
		$lang['MESSAGE_OVERLAY_DEL'] = 'Cancella';
		$lang['MESSAGE_OVERLAY_RESTORE'] = 'Ripristina';
		$lang['MESSAGE_OVERLAY_REPLY'] = 'Rispondi';
		$lang['OVERLAY_DEL_MSG_NO_SEL_TITLE'] = 'Operazione non valida';
		$lang['OVERLAY_DEL_MSG_NO_SEL'] = 'Nessun messaggio selezionato';
		$lang['MESSAGE_SEND_TO_EMPTY_ERR'] = 'Inserire un destinatario';
		$lang['MESSAGE_SEND_TO_NOT_EXISTS'] = 'Il destinatario non esiste';
		$lang['MESSAGE_SEND_SBJT_EMPTY_ERR'] = 'Inserire un oggetto';
		$lang['MESSAGE_SEND_SBJT_LENGTH_MIN_ERR'] = 'L\'oggetto non pu&ograve; contenere pi&ugrave; di 100 caratteri';
		$lang['MESSAGE_SEND_MESSAGE_EMPTY_ERR'] = 'Inserire un messaggio';
		$lang['MESSAGE_SEND_MESSAGE_LENGTH_MIN_ERR'] = 'Il messaggio non pu&ograve; contenere pi&ugrave; di 2000 caratteri';
		$lang['MESSAGE_SEND_MESSAGE_NO_PERMITTED'] = 'Non hai il permesso di inviare messaggi all\'utente';
		
		// OVERLAY-LOGIN-SIGNIN
		$lang['OVERLAY_LOG_SIGN_LOGIN'] = 'accedi';
		$lang['OVERLAY_LOG_SIGN_PWD_FORGOT'] = 'password dimenticata?';
		$lang['OVERLAY_LOG_SIGN_SINGUP'] = 'registrati';
		$lang['OVERLAY_LOG_SIGN_EMAIL'] = 'Email';
		$lang['OVERLAY_LOG_SIGN_PSWD'] = 'Password';
		$lang['OVERLAY_LOG_SIGN_NAME'] = 'Nome';
		$lang['OVERLAY_LOG_SIGN_SIGNIN'] = 'Registrati';
		$lang['OVERLAY_LOG_SIGN_RECOVER'] = 'Recupera';
		$lang['OVERLAY_LOG_SIGN_SEND_MAIL_OK'] = 'Operazione effettuata con successo. Controlla la tua mail e rispondi al link che ti abbiamo inviato';
		$lang['OVERLAY_LOG_SIGN_EMPTY_EMAIL_ERR'] = 'Campo email mancante';
		$lang['OVERLAY_LOG_SIGN_EMPTY_PWD_ERR'] = 'Campo password mancante';
		$lang['OVERLAY_LOG_SIGN_EMPTY_NCK_ERR'] = 'Campo nome mancante';
		$lang['OVERLAY_LOG_SIGN_EMAIL_OR_PWD_INC_ERR'] = 'Email o password non corretti';
		$lang['OVERLAY_LOG_SIGN_EMAIL_EXISTS_ERR'] = "Email gi&agrave;  presente, scegliere un'altra email e riprovare";
		$lang['OVERLAY_LOG_SIGN_NAME_EXISTS_ERR'] = "Nome gi&agrave;  presente, scegliere un altro nome e riprovare";
		$lang['OVERLAY_LOG_SIGN_PWD_LENGTH_ERR'] = 'La password deve contenere almeno 6 caratteri e non più di 20';
		$lang['OVERLAY_LOG_SIGN_NAME_LENGTH_ERR'] = 'Il nome deve contenere almeno 4 caratteri e non più di 20';
		$lang['OVERLAY_LOG_SIGN_CONF_TERMS'] = 'Conferma di aver letto le';
		$lang['OVERLAY_LOG_SIGN_LINK_TERMS'] = 'condizioni';
		$lang['OVERLAY_LOG_SIGN_TERMS_ERR'] = 'Conferma le condizioni del servizio';
		$lang['OVERLAY_LOG_SIGN_REMEMBER'] = 'Ricordami';
		$lang['OVERLAY_LOG_SIGN_RECOVER_OK'] = 'Ti abbiamo inviato una email; verifica di aver ricevuto il messaggio e clicca sul link che ti abbiamo inviato per completare l\'operazione';
		$lang['OVERLAY_LOG_SIGN_INSERT_CAPTCHA'] = 'Inserisci il codice di sicurezza';
		$lang['OVERLAY_LOG_SIGN_REFRESH_CAPTCHA'] = 'ricarica ';
		$lang['OVERLAY_LOG_SIGN_CPTCH_CD_ERROR'] = 'Codice di sicurezza errato';
		
		// OVERLAY-DEL-ITEM
		$lang['OVERLAY_DEL_ITM_TITLE'] = 'Conferma cancellazione';
		$lang['OVERLAY_DEL_ITM_QUESTION'] = 'Sei sicuro di voler cancellare il file selezionato?';
		$lang['OVERLAY_DEL_ITM_ANSW_YES'] = 'Si, continua';
		$lang['OVERLAY_DEL_ITM_ANSW_CANC'] = 'Annulla';
		
		// OVERLAY-DEL-MSG
		$lang['OVERLAY_DEL_MSG_TITLE'] = 'Conferma operazione';
		$lang['OVERLAY_DEL_MSG_QUESTION'] = 'Sei sicuro di voler cancellare i messaggi selezionati?';
		$lang['OVERLAY_RESTORE_MSG_QUESTION'] = 'Sei sicuro di voler ripristinare i messaggi selezionati?';
		$lang['OVERLAY_DEL_MSG_ANSW_YES'] = 'Sì, continua';
		$lang['OVERLAY_DEL_MSG_ANSW_CANC'] = 'Annulla';

		// OVERLAY-DEN-USR
		$lang['OVERLAY_DEN_USR_TITLE'] = 'Conferma operazione';
		$lang['OVERLAY_DEN_USR_QUESTION'] = 'Sei sicuro di voler bloccare questo utente?';
		$lang['OVERLAY_DEN_USR_QUESTION2'] = 'Sei sicuro di voler sbloccare questo utente?';
		$lang['OVERLAY_DEN_USR_ANSW_YES'] = 'Sì, continua';
		$lang['OVERLAY_DEN_USR_ANSW_CANC'] = 'Annulla';
		
		// SIGNIN_OK_PAGE
		$lang['CONF_SIGN_TITLE'] = 'Registrazione OK';
		$lang['CONF_SIGN_H3'] = 'Conferma registrazione';
		$lang['CONF_SIGN_H3_OK'] = 'Registrazione confermata';
		$lang['CONF_SIGN_PAGE_KO'] = 'Qualcosa &egrave; andato storto, la pagina non &egrave; attualmente disponibile!';
		$lang['CONF_SIGN_PAGE_OK'] = 'Operazione completata con successo!';
		$lang['CONF_SIGN_PAGE_OK_NOW'] = 'Ora puoi iniziare!!! Per recensire un sito clicca';
		$lang['CONF_SIGN_PAGE_OK_NOW_HOME'] = 'Per tornare alla pagina iniziale';
		$lang['CONF_SIGN_PAGE_OK_HR'] = 'qui';
		$lang['CONF_SIGN_FORM_TITLE'] = 'Ci siamo, devi solo confermare la password e sarai pronto per iniziare!!!';
		$lang['CONF_SIGN_PAGE_NON_AVLB_TIT'] = "Possibili cause";
		$lang['CONF_SIGN_PAGE_NON_AVLB_CAUSE_1'] = 'La pagina &egrave; scaduta perch&eacute; &egrave; passato troppo tempo da quando hai effettuato la registrazione. Ripeti la procedura di registrazione.';
		$lang['CONF_SIGN_PAGE_NON_AVLB_CAUSE_2'] = 'Il link non &egrave; più disponibile. L\'utente &egrave; gi&agrave; stato confermato in precedenza';
		$lang['CONF_SIGN_PAGE_NON_AVLB_CAUSE_3'] = 'Hai copiato male il link. Riguarda la mail e riprova';
		$lang['CONF_SIGN_PWD'] = 'Conferma password';
		$lang['CONF_SIGN_DONE'] = 'Fatto!';
		$lang['CONF_SIGN_EMPTY_PWD_ERR'] = 'Campo password mancante';
		$lang['CONF_SIGN_PWD_ERR'] = 'Password non corretta';
		
		// RECOVER_PWD
		$lang['RECOVER_PWD_TITLE'] = 'Recupero Password';
		$lang['RECOVER_PWD_H3'] = 'Recupero Password';
		$lang['RECOVER_PWD_H3_OK'] = 'Stai per completare la procedura di recupero password';
		$lang['RECOVER_PWD_PAGE_KO'] = 'Qualcosa &egrave; andato storto, la pagina non &egrave; attualmente disponibile!';
		$lang['RECOVER_PWD_FORM_TITLE'] = 'Inserisci e conferma la nuova password';
		$lang['RECOVER_PWD_SUBMIT_COMPLETE'] = 'Completa';
		$lang['RECOVER_PWD_PAGE_NON_AVLB_TIT'] = 'Possibili cause';
		$lang['RECOVER_PWD_PAGE_NON_AVLB_CAUSE_1'] = 'La pagina &egrave; scaduta perch&eacute; &egrave; passato troppo tempo da quando hai richiesto il recupero della password. Ripeti la procedura se necessario.';
		$lang['RECOVER_PWD_PAGE_NON_AVLB_CAUSE_2'] = 'Il link non &egrave; più disponibile. La procedura di recupero password &egrave; gi&agrave; stata effettuata';
		$lang['RECOVER_PWD_PAGE_NON_AVLB_CAUSE_3'] = 'Hai copiato male il link. Riguarda la mail e riprova';
		$lang['RECOVER_PWD_PWD'] = 'password';
		$lang['RECOVER_PWD_CNFRM_PWD'] = 'conferma password';
		$lang['RECOVER_PWD_EMPTY_PWD_ERR'] = 'Campo password mancante';
		$lang['SETTING_PWD_REPET_ERROR'] = 'Le passoword non coincidono';
		$lang['RECOVER_PWD_MAX_REQUEST_ERROR'] = 'Hai superato il numero massimo di richieste. Devi aspettare 24 ore prima di poter effettuare una nuova richiesta.';
		$lang['RECOVER_PWD_EMAIL_NOT_EX'] = 'L\'email non esiste';
		
		// MENU
		$lang['COMM_MENU_HOME'] = 'Home';
		$lang['COMM_MENU_REVIEW'] = 'Recensioni';
		$lang['COMM_MENU_PROFILE'] = 'Profilo';
		$lang['COMM_MENU_MYPROFILE'] = 'Il mio Profilo';
		$lang['COMM_MENU_CREATE_REVIEW'] = 'Recensisci un sito';
		$lang['COMM_MENU_ADV_SEARCH'] = 'Ricerca avanzata';
		$lang['COMM_MENU_MY_REVIEW'] = 'Le mie Recensioni';
		$lang['COMM_MENU_CITY_REVIEW'] = 'Recensisci una citt&agrave;';
		$lang['COMM_MENU_COUNTRY_REVIEW'] = 'Recensisci una nazione';
		$lang['COMM_MENU_SETTINGS'] = 'Impostazioni';
		$lang['COMM_MENU_MESSAGES'] = 'Messaggi';
		$lang['COMM_MENU_ENTER'] = 'Entra';
		$lang['COMM_MENU_EXIT'] = 'Esci';
		$lang['COMM_SEARCH_MSG'] = 'Cerca';

		// FOOTER
		$lang['COMM_FOOTER_TXT'] = '&copy; 2016 Viantes';
		$lang['COMM_FOOTER_WHO'] = 'Chi siamo';
		$lang['COMM_FOOTER_MISISON'] = 'Mission';
		$lang['COMM_FOOTER_CONTACTS'] = 'Contatti';
		$lang['COMM_FOOTER_COOKIE_INFO'] = 'Informativa sui Cookie';
		$lang['COMM_FOOTER_TERMS'] = 'Termini e condizioni';

		// CONTACT
		$lang['CONTACT_TITLE'] = 'Contatti';
		$lang['CONTACT_DISCL'] = 'Utilizza questo form per inviarci un tuo commento';
		$lang['CONTACT_NAME'] = 'Nome';
		$lang['CONTACT_MAIL'] = 'Email';
		$lang['CONTACT_COMMENT'] = 'Commento';
		$lang['CONTACT_SECURE'] = 'Inserisci il codice di sicurezza';
		$lang['CONTACT_SEND'] = 'Invia';
		$lang['CONTACT_MSG_SEND_OK'] = 'Commento inviato con successo';
		$lang['CONTACT_EMPTY_NAME_ERR'] = 'Nome mancante';
		$lang['CONTACT_NAME_LENGTH_ERR'] = 'Il campo nome deve contenere almeno 3 caratteri e non più di 60';
		$lang['CONTACT_EMPTY_EMAIL_ERR'] = 'Email mancante';
		$lang['CONTACT_EMPTY_DS_ERR'] = 'Commento mancante';
		$lang['CONTACT_DS_LENGTH_ERR'] = 'Il campo commento deve contenere almeno 10 caratteri e non più di 500';

		// GENERIC
		$lang['GEN_IS_NOT_EMAIL_REG'] = 'Il formato dell\'email non &egrave;  corretto';
		$lang['GEN_IS_NOT_PWD_REG'] = 'La password deve contenere almeno una lettera maiuscola, una minuscola ed un numero';
		$lang['GEN_IS_NOT_NAME_REG'] = 'Il nome deve contenere solo lettere e numeri';
		$lang['GEN_REQUEST_OK'] = 'Operazine effettuata con successo';
		$lang['GEN_REQUEST_KO'] = 'Errore generico nella richiesta. Riprovare in seguito';
		$lang['GEN_CANCEL'] = 'Elimina';
		$lang['GEN_EXPAND'] = 'Ingrandisci';
		$lang['GEN_PLAY'] = 'Play';
		$lang['GEN_PAUSE'] = 'Pausa';
		$lang['GEN_CLOSE'] = 'Chiudi';
		$lang['GEN_GALLERY'] = 'Galleria';
		$lang['GEN_NO_RESULT'] = 'la lista &egrave; vuota';

		//FILE UPLOAD
		$lang['UPLOAD_IMG_ERR_TYPE']='Il file che stai caricando non &egrave;  un\'immagine! E\' possibile caricare solo file di tipo immagine';
		$lang['UPLOAD_MOV_ERR_TYPE']='Il file che stai caricando non &egrave;  un video! E\' possibile caricare solo file di tipo video';
		$lang['UPLOAD_MOV_ERR_FORMAT']='Il file che stai caricando potrebbe essere corrotto o il formato del video &egrave;  sconosciuto';
		$lang['UPLOAD_DOC_ERR_TYPE']='Il file che stai caricando non &egrave;  un pdf! E\' possibile caricare solo file di tipo pdf';
		$lang['UPLOAD_ERR_NAME_TOO_LONG']='Il nome del file &egrave;  troppo lungo (max 60 caratteri).';
		$lang['UPLOAD_ERR_INI_SIZE']='Il file che stai caricando supera la dimensione masisma consentita! Riprova con un file più piccolo di 100 M byte';
		$lang['UPLOAD_ERR_PARTIAL']='Il file &egrave;  scato caricato solo parzialmente. Rirpovare a caricare nuovamente il file.';
		$lang['UPLOAD_ERR_NO_FILE'] = 'Il file non &egrave;  stato caricato! Riprovare nuovamente';
		$lang['UPLOAD_ERR_NO_TMP_DIR'] = 'Si &egrave; verificato un errore duranto l\'aquisizione del file! Riprovare nuovamente';
		$lang['UPLOAD_ERR_CANT_WRITE'] = 'Impossibile acquisire il file! Riprovare nuovamente';
		$lang['UPLOAD_ERR_EXTENSION'] = 'L\'acquisizione file &egrave;  stata bloccata! Riprovare nuovamente, se il problema permane scegliere un altro file';
		$lang['UPLOAD_ERR_PART_UPLOADED'] = 'Il file &egrave;  stato caricato solo in parte! Riprovare nuovamente, se il problema permane scegliere un altro file';
		$lang['UPLOAD_ERR_IMG_TOO_SMALL'] = "L'immagine caricata ha dimensioni troppo ridotte. Caricare un'immagine pi&ugrave; grande";
		$lang['UPLOAD_ERR_IMG_TOO_BIG'] = "L'immagine caricata ha dimensioni troppo grandi. Caricare un'immagine pi&ugrave; piccola";
		$lang['UPLOAD_ERR_ONLY_JPEG'] = "E' possibile caricare come immagine del profilo solo file di tipo JPEG";
		$lang['UPLOAD_ERR_IMG_MAX_NUM_EXCEEDED'] = 'Superato il numero massimo di foto';
		$lang['UPLOAD_ERR_MOV_MAX_NUM_EXCEEDED'] = 'Superato il numero massimo di video';
		$lang['UPLOAD_ERR_DOC_MAX_NUM_EXCEEDED'] = 'Superato il numero massimo di documenti';
		
		//MAIL 
		$lang['SEND_MAIL_RECOVER_PWD_SBJ'] = 'Recupero password';
		$lang['SEND_MAIL_RECOVER_PWD_TITLE'] = 'Recupero password'; 
		$lang['SEND_MAIL_RECOVER_PWD_P1'] = 'Abbiamo preso in carico la tua richiesta di recupero password: per completare la procedura clicca sul link seguente: ';
		$lang['SEND_MAIL_RECOVER_PWD_P2'] = '<b>Attenzione</b> se non hai fatto alcuna richiesta ignora questa mail e non cliccare sul link di sopra. Ricordati che il link vale per 48 ore, poi sar&agrave; invalidato.';
		$lang['SEND_MAIL_SIGNIN_SBJ'] = 'Benvenuto ';
		$lang['SEND_MAIL_SIGNIN_PWD_TITLE1'] = 'Ciao ';
		$lang['SEND_MAIL_SIGNIN_PWD_TITLE2'] = ',<br>la registrazione &egrave; avvenuta con successo!!!';
		$lang['SEND_MAIL_SIGNIN_PWD_P1'] = 'Per completare la registrazione clicca sul seguente link: ';
		$lang['SEND_MAIL_SIGNIN_PWD_P2'] = 'Se hai problemi copia ed incolla il seguente link sul browser: ';
		
		//ERROR PAGE
		$lang['ERROR_TITLE'] = 'Errore';
		$lang['ERROR_H1'] = 'Errore';
		$lang['ERROR_REASON_UNEXPECTED'] = 'Si &egrave; verificato un errore imprevisto';
		$lang['ERROR_BACK_TO_HOME'] = 'Torna alla <a href="https://www.viantes.com/">home</a>';
		$lang['ERROR_REASON_SESSION_EXPIRED'] = 'Sessione scaduta';
		$lang['ERROR_CLOSE'] = 'Chiudi';
		
		//TERMS
		$lang['TERMS_TITLE'] = 'Termini';
		$lang['TERMS_H1'] = 'Termini e Condizioni';
		$lang['TERMS_DISCL'] = 'Termini e condizioni di utilizzo del servizio';
		
		//INO_COOKIE				
		$lang['INO_COOKIE_MSG'] = "Questo sito utilizza i cookie e altre tecnologie simili per garantirti una migliore esperienza durante la navigazione sul nostro sito web. Per maggiori informazioni su cosa sono e come funzionano clicca <a target=\"_blank\" href=\"/viantes/pub/pages/infoCookie.php\">qui</a>. Chiudendo la questa finestra informativa o continuando la navigazione acconsenti all'uso dei cookie e di altre tecnologie simili.";

		//LANGUAGE
		$lang['it'] = 'Italiano';
		$lang['en'] = 'Inglese';
		
		return $lang;
	}
	
	
	/*
	------------------
	Language: English
	------------------
	*/
	private function doEn() {
		$lang = array();
		
		// WELCOME
		$lang['WELCOME_PAGE_TITLE'] = 'Viantes: reviews of your travel';
		$lang['WELCOME_HAS_PUB'] = 'has published the Review:';
		$lang['WELCOME_KEEP_READING'] = '...keep reading';
		
		// MY_PROFILE
		$lang['MYPROFILE_PAGE_TITLE'] = 'My Profile';
		$lang['MYPROFILE_MY_INFO'] = 'My information';
		$lang['MYPROFILE_MY_INFO_MODIFY'] = 'Mofify';
		$lang['MYPROFILE_MY_INFO_CANCEL'] = 'Cancel';
		$lang['MYPROFILE_MY_INFO_SAVE'] = 'Save';
		$lang['MYPROFILE_MY_DEN_USR'] = 'Block User';
		$lang['MYPROFILE_MY_ALLOW_USR'] = 'Unlocks User';
		$lang['MYPROFILE_MY_SEND_MSG'] = 'Send Message';
		$lang['MYPROFILE_FIRST_NAME'] = 'First Name:';
		$lang['MYPROFILE_LAST_NAME'] = 'Last Name:';
		$lang['MYPROFILE_EMAIL'] = 'Email:';
		$lang['MYPROFILE_MOBILE_NUM'] = 'Mobile Number';
		$lang['MYPROFILE_GENDER'] = 'Gender';
		$lang['MYPROFILE_GENDER_0'] = 'Select...';
		$lang['MYPROFILE_GENDER_1'] = 'Male';
		$lang['MYPROFILE_GENDER_2'] = 'Female';
		$lang['MYPROFILE_BIRTH_DATE'] = 'Birth date:';
		$lang['MYPROFILE_CITY'] = 'City:';
		$lang['MYPROFILE_POSTAL_CODE'] = 'Postal Code:';
		$lang['MYPROFILE_COUNTRY'] = 'Conutry:';
		$lang['MY_PROFILE_FIRST_NAME_LENGTH_ERR'] = 'First Name can not be more than 60 characters';
		$lang['MY_PROFILE_FIRST_NAME_PATTERN_ERR'] = 'First Name contains not allowed characters';
		$lang['MY_PROFILE_LAST_NAME_LENGTH_ERR'] = 'Last Name can not be more than 60 characters';
		$lang['MY_PROFILE_LAST_NAME_PATTERN_ERR'] = 'Last Name contains not allowed characters';
		$lang['MY_PROFILE_MOB_NUM_LENGTH_ERR'] = 'Mobile Number  must be at least 7 and no more than 15';
		$lang['MY_PROFILE_MOB_NUM_PATTERN_ERR'] = 'Mobile Number contains not allowed characters';
		$lang['MY_PROFILE_DATE_OF_BIRTH_ERR'] = "Birth date has a wrong format";
		$lang['MY_PROFILE_CITY_LENGTH_ERR'] = 'City  must be at least 3 characters and no more than 60';
		$lang['MY_PROFILE_CITY_PATTERN_ERR'] = 'City contains not allowed characters';
		$lang['MY_PROFILE_POSTCODE_LEN_ERR'] = 'Postcode must be at least 3 characters and no more than 10';
		$lang['MY_PROFILE_COUNTRY_LENGTH_ERR'] = 'Country must be at least 3 characters and no more than 60';
		$lang['MY_PROFILE_COUNTRY_PATTERN_ERR'] = 'Country contains not allowed characters';
		$lang['MYPROFILE_MY_REVIEW'] = 'My Review';
		
		// SHOW_PROFILE
		$lang['SHOWPROFILE_PAGE_TITLE'] = 'User Profile';
		$lang['SHOWPROFILE_MY_INFO'] = 'User Information';
		$lang['SHOWPROFILE_USER_REVIEW'] = 'User Reviews';
		
		// SETTING
		$lang['SETTING_PAGE_TITLE'] = 'Settings';
		$lang['SETTING_PAGE_H3'] = 'Settings';
		$lang['SETTING_PAGE_DISCL'] = 'Change your current setting';
		$lang['SETTING_ACCOUNT'] = 'Account';
		$lang['SETTING_ACCOUNT_NAME'] = 'User Name:';
		$lang['SETTING_ACCOUNT_EMAIL'] = 'Email:';
		$lang['SETTING_ACCOUNT_LANG'] = 'Language:';
		$lang['SETTING_PASSWORD'] = 'Password';
		$lang['SETTING_PASSWORD_CHANGE'] = 'Password:';
		$lang['SETTING_PASSWORD_NEW'] = 'New Password:';
		$lang['SETTING_PASSWORD_REPEAT'] = 'Repeat Password:';
		$lang['SETTING_PWD_ERROR'] = 'Passoword wrong';
		$lang['SETTING_PWD_REPET_ERROR'] = 'Passwords do not match';
		$lang['SETTING_PWD_MISSING'] = 'Password field missing';
		$lang['SETTING_PRIVACY'] = 'Privacy';
		$lang['SETTING_PROFILE_TYPE'] = 'Profile Type';
		$lang['SETTING_PROFILE_TYPE_0'] = 'Public'; 
		$lang['SETTING_PROFILE_TYPE_1'] = 'Private';
		
		// CREATE REVIEW
		$lang['CREATE_REVIEW_PAGE_TOP_MSG_OK'] = 'The review is successfully created';
		$lang['CREATE_REVIEW_PAGE_TITLE'] = 'Review a site';
		$lang['CREATE_REVIEW_PAGE_H3'] = 'Review a site';
		$lang['CREATE_REVIEW_PAGE_DISCL'] = 'Review a site in a few seconds: compile the follow fields, attach photos, videos or other useful files, and your review will be visible immediately!';
		$lang['CREATE_REVIEW_TAB_REV_TITLE'] = 'Review';
		$lang['CREATE_REVIEW_TITLE_ESSENTIAL'] = 'Essential information';
		$lang['CREATE_REVIEW_TAB_PIC_TITLE'] = 'Pictures';
		$lang['CREATE_REVIEW_TAB_VID_TITLE'] = 'Video';
		$lang['CREATE_REVIEW_TAB_DOC_TITLE'] = 'Documents';
		$lang['CREATE_REVIEW_FIELD_CATEG'] = 'Select a category';
		$lang['CREATE_REVIEW_FIELD_COUNTRY'] = 'Country';
		$lang['CREATE_REVIEW_FIELD_LOCALITY'] = 'Locality';
		$lang['CREATE_REVIEW_FIELD_SITE_NAME'] = 'Site Name';
		$lang['CREATE_REVIEW_FIELD_DSCR'] = 'Write your Review';
		$lang['CREATE_REVIEW_FIELD_ARRIVE'] = 'How to Arrive';
		$lang['CREATE_REVIEW_TITLE_OTHER'] = 'More Information';
		$lang['CREATE_REVIEW_FIELD_WARN'] = 'Warnings';
		$lang['CREATE_REVIEW_FIELD_MYTH'] = 'Debunking Myths';
		$lang['CREATE_REVIEW_FIELD_WHEAT'] = 'Where to eat';
		$lang['CREATE_REVIEW_FIELD_COOK'] = 'Cooking';
		$lang['CREATE_REVIEW_FIELD_WHSTAY'] = 'Where to stay';
		$lang['CREATE_REVIEW_FIELD_VOTE'] = 'Judgement';
		$lang['CREATE_REVIEW_FIELD_COVER'] = 'Cover Picture';
		$lang['CREATE_REVIEW_EMPTY_CATREV_ERR'] = 'Select a valid category';
		$lang['CREATE_REVIEW_LOCALITY_EMPTY_ERR'] = 'Locality missing';
		$lang['CREATE_REVIEW_LCOALITY_LENGTH_ERR'] = 'Locality name must contains at least 3 characters and not more than 80';
		$lang['CREATE_REVIEW_SITE_EMPTY_ERR'] = 'Site name missing';
		$lang['CREATE_REVIEW_EMPTY_DS_ERR'] = 'Description missing';
		$lang['CREATE_REVIEW_SITE_LENGTH_ERR'] = 'Site name must contains at least 3 characters and not more than 40';
		$lang['CREATE_REVIEW_SITE_EXISTS_ERR'] = 'Site name you have chosen is already in use. Choose another site name and try again';
		$lang['CREATE_REVIEW_DS_LENGTH_ERR'] = 'Description must be at least 50 characters and no more than 2000';
		$lang['CREATE_REVIEW_ARRIVE_LENGTH_MIN_ERR'] = 'A good review about how to arrive should contains at least 25 characters';
		$lang['CREATE_REVIEW_ARRIVE_LENGTH_MAX_ERR'] = 'Enter no more than 500 characters';
		$lang['CREATE_REVIEW_WARN_LENGTH_MIN_ERR'] = 'A good warning should contains at least 10 characters';
		$lang['CREATE_REVIEW_WARN_LENGTH_MAX_ERR'] = 'Enter no more than 100 characters';
		$lang['CREATE_REVIEW_WTEAT_LENGTH_MIN_ERR'] = 'A good review about how to eat should contains at least 25 characters';
		$lang['CREATE_REVIEW_WTEAT_LENGTH_MAX_ERR'] = 'Enter no more than 500 characters';
		$lang['CREATE_REVIEW_COOK_LENGTH_MIN_ERR'] = 'A good review about cooking should contains at least 25 characters';
		$lang['CREATE_REVIEW_COOK_LENGTH_MAX_ERR'] = 'Enter no more than 500 characters';
		$lang['CREATE_REVIEW_WHSTAY_LENGTH_MIN_ERR'] = 'A good review about how to stay should contains at least 25 characters';
		$lang['CREATE_REVIEW_WHSTAY_LENGTH_MAX_ERR'] = 'Enter no more than 500 characters';
		$lang['CREATE_REVIEW_MYTH_LENGTH_MIN_ERR'] = 'If you want debunk a myths enter at least 10 characters';
		$lang['CREATE_REVIEW_MYTH_LENGTH_MAX_ERR'] = 'Enter no more than 100 characters';
		$lang['CREATE_REVIEW_COVER_ERR'] = 'Upload a cover picture';
		$lang['CREATE_REVIEW_COMPLETE_VAL'] = 'Complete';
		$lang['CREATE_REVIEW_SUBMIT_VAL'] = 'Create Review';
		$lang['CREATE_REVIEW_CHANGE_VAL'] = 'Something changes';
		$lang['CREATE_REVIEW_COMPLETE_VAL'] = 'Finish';
		$lang['CREATE_REVIEW_NO_IMG_TXT1'] = 'No pictures uploaded yet';
		$lang['CREATE_REVIEW_NO_IMG_TXT2'] = 'Click the button to add your first picture';
		$lang['CREATE_REVIEW_IMG_TXT1'] = 'Attached Pictures';
		$lang['CREATE_REVIEW_IMG_TXT2'] = 'Click the button to add new pictures';
		$lang['CREATE_REVIEW_IMG_BT_TITLE'] = 'add pictires now';
		$lang['CREATE_REVIEW_IMG_WAIT_LOAD'] = 'Wait please';
		$lang['CREATE_REVIEW_NO_MOV_TXT1'] = 'No video uploaded yet';
		$lang['CREATE_REVIEW_NO_MOV_TXT2'] = 'Click the button to add your first video';
		$lang['CREATE_REVIEW_MOV_TXT1'] = 'Attached Video';
		$lang['CREATE_REVIEW_MOV_TXT2'] = 'Click the button to add new pictures';
		$lang['CREATE_REVIEW_MOV_WAIT_LOAD'] = 'Please wait, upload may take a few minutes!';
		$lang['CREATE_REVIEW_NO_DOC_TXT1'] = 'No documents uploaded yet';
		$lang['CREATE_REVIEW_NO_DOC_TXT2'] = 'Click the button to add your first document';
		$lang['CREATE_REVIEW_DOC_TXT1'] = 'Attached Documents';
		$lang['CREATE_REVIEW_DOC_TXT2'] = 'Click the button to add new documents';
		$lang['CREATE_REVIEW_DOC_BT_TITLE'] = 'add document now';
		$lang['CREATE_REVIEW_IMG_TELL_US'] = 'tell us about this photo...';
		$lang['CREATE_REVIEW_MOV_TELL_US'] = 'tell us about this video...';
		$lang['CREATE_REVIEW_DOC_TELL_US'] = 'tell us about this document...';
		$lang['CREATE_REVIEW_CARTOON_CATEGORY'] = 'Select site membership categoty';
		$lang['CREATE_REVIEW_CARTOON_SITE'] = 'Write the name of the site to review. Such as " St. John Lateran " , " Mount Fuji " , " Neuschwanstein Castle " , " Piazza Navora " or " Manhattan "';
		$lang['CREATE_REVIEW_CARTOON_LOCALITY'] = 'Enter the site address . For example " St. John Lateran\'s Square 4, Rome Italy " . For the most famous sites can be sufficient even the name of the city or the nation . As example, if you\'re reviewing the Mount Fuji, write " Japan " in this field, for St. John Lateran\'s Basilica write simply " Rome " .';
		$lang['CREATE_REVIEW_CARTOON_REVIEW'] = 'Write a depth and detailed site review';
		$lang['CREATE_REVIEW_CARTOON_ARRIVE'] = 'Write how to arrive to the site; if the site is located in a big city describe which urban transport ( metro , buses ) take for leading to the site. If the site is located far from the major centers, indicates the suburban transport starting from the nearest major city. If you prefer, describe how to arrive to the city where the site is located, or how to arrive to the nearest major city also';
		$lang['CREATE_REVIEW_CARTOON_WARN'] = 'If you have an advice then write your suggestion here';
		$lang['CREATE_REVIEW_CARTOON_EAT'] = 'Recommends , if you know one or more places to eat. It would be appropriate to indicate the type of place (pizzeria, tavern, traditional cuisine...), the dishes and price range (economic, average or expensive...)';
		$lang['CREATE_REVIEW_CARTOON_COOK'] = 'Describe the typical local cuisine indicating maybe the dishes you know';
		$lang['CREATE_REVIEW_CARTOON_STAY'] = 'Recommends , if you know one or more places to stay. It would be appropriate to indicate the type of place ((b&b, bungalow, guest house, bungalow, hotel ...)) and price range (economic, average or expensive...)';
		$lang['CREATE_REVIEW_CARTOON_MYTH'] = 'If you want debunk a myth wite your comment here. Note Well: Write your comment only if you have significant experience such as to dispel clichés';
		$lang['CREATE_REVIEW_CARTOON_VOTE'] = 'Assign a vote from one to five to this site';
		$lang['CREATE_REVIEW_ADDRS_NOT_VALID'] = 'Site or address invalid . Please check site name and locality and try again';
		
		// CREATE REVIEW STEP2 
		$lang['CREATE_REVIEW_S2_PAGE_TITLE'] = 'Check data';
		$lang['CREATE_REVIEW_S2_PAGE_H3'] = 'Check data';
		$lang['CREATE_REVIEW_S2_PAGE_DISCL'] = 'Check data and if everything OK click the Finish button !';
		$lang['CREATE_REVIEW_S2_REVIEW'] = 'Your review';
		$lang['CREATE_REVIEW_S2_FIELD_CATEG'] = 'Category';
		
		// CREATE CITY REVIEW
		$lang['CREATE_CITY_REV_PAGE_TOP_MSG_ERROR'] = 'Attention: to review a city is necessary to review at least one site of interest of this city. To continue, from \'Review\' menu click \'Review a site\' .';
		$lang['CREATE_CITY_REV_PAGE_TOP_MSG_OK'] = 'The review is successfully created';
		$lang['CREATE_CITY_REV_PAGE_TITLE'] = 'Review a city';
		$lang['CREATE_CITY_REV_PAGE_H3'] = 'Review a city';
		$lang['CREATE_CITY_REV_PAGE_DISCL'] = 'Review a city in a few seconds: compile the follow fields, add at least one site to visit, attach photos, videos or other useful files, and your review will be visible immediately!';
		$lang['CREATE_CITY_REV_TAB_REV_TITLE'] = 'Review';
		$lang['CREATE_CITY_REV_TITLE_ESSENTIAL'] = 'Essential information';
		$lang['CREATE_CITY_REV_TAB_PIC_TITLE'] = 'Pictures';
		$lang['CREATE_CITY_REV_TAB_VID_TITLE'] = 'Video';
		$lang['CREATE_CITY_REV_TAB_DOC_TITLE'] = 'Documents';
		$lang['CREATE_CITY_REV_FIELD_CITY_NAME'] = 'City name';
		$lang['CREATE_CITY_REV_FIELD_COUNTRY'] = 'Country';
		$lang['CREATE_CITY_REV_FIELD_DSCR'] = 'Write your review about this city';
		$lang['CREATE_CITY_REV_FIELD_COVER'] = 'Cover Picture';
		$lang['CREATE_CITY_REV_FIELD_HOW_TO_ARR'] = 'How to reach the city center';
		$lang['CREATE_CITY_WHAT_TO_SEE'] = 'What to see';
		$lang['CREATE_CITY_WHAT_TO_SEE_ADD'] = 'Add sites to visit';
		$lang['CREATE_CITY_REV_TITLE_OTHER'] = 'More Information';
		$lang['CREATE_CITY_REV_FIELD_WARN'] = 'Warnings';
		$lang['CREATE_CITY_REV_FIELD_MYTH'] = 'Debunking Myths';
		$lang['CREATE_CITY_REV_FIELD_WHEAT'] = 'Where to eat';
		$lang['CREATE_CITY_REV_FIELD_VOTE'] = 'Judgement';
		$lang['CREATE_CITY_REV_SUBMIT_VAL'] = 'Create Review';
		$lang['CREATE_CITY_REV_CHANGE_VAL'] = 'Something changes';
		$lang['CREATE_CITY_REV_COMPLETE_VAL'] = 'Finish';
		$lang['CREATE_CITY_REV_CITY_EMPTY_ERR'] = 'City missing';
		$lang['CREATE_CITY_REV_CITY_LENGTH_ERR'] = 'City name must contains at least 3 characters and not more than 60';
		$lang['CREATE_CITY_REV_COUNTRY_EMPTY_ERR'] = 'Country missing';
		$lang['CREATE_CITY_REV_COUNTRY_ERR'] = 'Campo Nazione errato';
		$lang['CREATE_CITY_REV_EMPTY_DS_ERR'] = 'Review missing';
		$lang['CREATE_CITY_REV_DS_LENGTH_ERR'] = 'Review must contains at least 50 characters and not more than 2000';
		$lang['CREATE_CITY_REV_COVER_ERR'] = 'Upload a cover picture';
		$lang['CREATE_CITY_REV_EMPTY_ARRIVE_ERR'] = 'Description missing';
		$lang['CREATE_CITY_REV_ARRIVE_LENGTH_ERR']=  'A good description about how to reach the city must contains at least 50 characters and not more than 1000';
		$lang['CREATE_CITY_REV_WARN_LENGTH_MIN_ERR'] = 'A good warning should contains at least 10 characters';
		$lang['CREATE_CITY_REV_WARN_LENGTH_MAX_ERR'] = 'Enter no more than 100 characters';
		$lang['CREATE_CITY_REV_WTEAT_LENGTH_MIN_ERR'] = 'A good review about how to eat should contains at least 25 characters';
		$lang['CREATE_CITY_REV_WTEAT_LENGTH_MAX_ERR'] = 'Enter no more than 500 characters';
		$lang['CREATE_CITY_COOK_LENGTH_MIN_ERR'] = 'A good review about cooking should contains at least 25 characters';
		$lang['CREATE_CITY_COOK_LENGTH_MAX_ERR'] = 'Enter no more than 500 characters';
		$lang['CREATE_CITY_WHSTAY_LENGTH_MIN_ERR'] = 'A good review about how to stay should contains at least 25 characters';
		$lang['CREATE_CITY_WHSTAY_LENGTH_MAX_ERR'] = 'Enter no more than 500 characters';
		$lang['CREATE_CITY_REV_MYTH_LENGTH_MIN_ERR'] = 'Enter no more than 10 characters';
		$lang['CREATE_CITY_REV_MYTH_LENGTH_MAX_ERR'] = 'Enter no more than 100 characters';
		$lang['CREATE_CITY_REV_INTER_EMPTY_ERR'] = 'Enter at least one site to visit';
		$lang['CREATE_CITY_CARTOON_CITY'] = 'Write the name of the city you want review. If the name is ambiguous , add also the nation . For example, " Paris Texas USA " oppure " Los Angeles Chile "';
		$lang['CREATE_CITY_CARTOON_COUNTRY'] = 'Write the name of the country where is located the city';
		$lang['CREATE_CITY_CARTOON_REVIEW'] = 'Write a depth and detailed city review';
		$lang['CREATE_CITY_CARTOON_ARRIVE'] = 'Write how to get to the center of the city ; by at least one of the three major transport infrastructure : the airport , the railway, the port. If is necessary to use other means describe accurately all relevant information for the reader . If you know more ways to get to the center describe both indicating faster ones , the cheaper ones , and those more comfortable';
		$lang['CREATE_CITY_CARTOON_INTEREST'] = 'Add site reviews of this city that advice to visit';
		$lang['CREATE_CITY_CARTOON_WARN'] = 'If you have an advice then write your suggestion here';
		$lang['CREATE_CITY_CARTOON_EAT'] = 'Recommends , if you know one or more places to eat. It would be appropriate to indicate the type of place (pizzeria, tavern, traditional cuisine...), the dishes and price range (economic, average or expensive...)';
		$lang['CREATE_CITY_CARTOON_MYTH'] = 'If you want debunk a myth wite your comment here. Note Well: Write your comment only if you have significant experience such as to dispel clichés';
		$lang['CREATE_CITY_CARTOON_VOTE'] = 'Assign a vote from one to five to this city';
		$lang['CREATE_CITY_ADDRS_NOT_FOUND'] = 'City not found';
		
		// CREATE CITY INTEREST
		$lang['CREATE_CITY_INTEREST_TITLE']='Select and add site of interest';
		$lang['CREATE_CITY_INTEREST_ADD_BUT']='Add selected';
		$lang['CREATE_CITY_INTEREST_CANC_BUT']='Close';
		$lang['CREATE_CITY_INTEREST_ZERO_SELECT_ERROR']='Select at least one site to visit';
		$lang['CREATE_CITY_INTEREST_MAX_SELECT_ERROR']='Select no more than 50 site to visit';
		$lang['CREATE_CITY_INTEREST_SELECT']='Select';
		$lang['CREATE_CITY_INTEREST_COVER']='Cover';
		$lang['CREATE_CITY_INTEREST_NAME']='Site name';
		$lang['CREATE_CITY_INTEREST_NO_SITE'] = 'Attention!!! No site reviewed';
		$lang['CREATE_CITY_INTEREST_TO_CONTINUE'] = 'To continue review at least one site of interest to visit in this city';
		
		// CREATE CITY STEP2 
		$lang['CREATE_CITY_REV_S2_PAGE_TITLE'] = 'Check data';
		$lang['CREATE_CITY_REV_S2_PAGE_H3'] = 'Check data';
		$lang['CREATE_CITY_REV_S2_PAGE_DISCL'] = 'Check data and if everything OK click the Finish button !';
		$lang['CREATE_CITY_REV_S2_REVIEW'] = 'Your review';
		
		// CREATE COUNTRY REVIEW
		$lang['CREATE_COUNTRY_REV_PAGE_TOP_MSG_ERROR'] = 'Attention: to review a country you must review at least one city of this country. To continue, from \'Review\' menu click \'Review a city\' .';
		$lang['CREATE_COUNTRY_REV_PAGE_TOP_MSG_OK'] = 'The review is successfully created';
		$lang['CREATE_COUNTRY_REV_PAGE_TITLE'] = 'Review a country';
		$lang['CREATE_COUNTRY_REV_PAGE_H3'] = 'Review a country';
		$lang['CREATE_COUNTRY_REV_PAGE_DISCL'] = 'Review a city in a few seconds: compile the follow fields, add at least one city to visit, attach photos, videos or other useful files, and your review will be visible immediately!';
		$lang['CREATE_COUNTRY_REV_TAB_REV_TITLE'] = 'Review';
		$lang['CREATE_COUNTRY_REV_TITLE_ESSENTIAL'] = 'Essential information';
		$lang['CREATE_COUNTRY_REV_TAB_PIC_TITLE'] = 'Pictures';
		$lang['CREATE_COUNTRY_REV_TAB_VID_TITLE'] = 'Video';
		$lang['CREATE_COUNTRY_REV_TAB_DOC_TITLE'] = 'Documents';
		$lang['CREATE_COUNTRY_REV_FIELD_COUNTRY_NAME'] = 'Country name';
		$lang['CREATE_COUNTRY_REV_FIELD_COUNTRY'] = 'Country';
		$lang['CREATE_COUNTRY_REV_FIELD_DSCR'] = 'Write your review about this country';
		$lang['CREATE_COUNTRY_REV_FIELD_COVER'] = 'Cover Picture';
		$lang['CREATE_COUNTRY_REV_FIELD_HOW_TO_ARR'] = 'How to arrive';
		$lang['CREATE_COUNTRY_WHAT_TO_SEE'] = 'What to see';
		$lang['CREATE_COUNTRY_WHAT_TO_SEE_ADD'] = 'Add a city to visit reviewed by you';
		$lang['CREATE_COUNTRY_REV_TITLE_OTHER'] = 'More Information';
		$lang['CREATE_COUNTRY_REV_FIELD_WARN'] = 'Warnings';
		$lang['CREATE_COUNTRY_REV_FIELD_MYTH'] = 'Debunking Myths';
		$lang['CREATE_COUNTRY_REV_FIELD_VOTE'] = 'Judgement';
		$lang['CREATE_COUNTRY_REV_SUBMIT_VAL'] = 'Create Review';
		$lang['CREATE_COUNTRY_REV_CHANGE_VAL'] = 'Something changes';
		$lang['CREATE_COUNTRY_REV_COMPLETE_VAL'] = 'Finish';
		$lang['CREATE_COUNTRY_REV_COUNTRY_EMPTY_ERR'] = 'Country missing';
		$lang['CREATE_COUNTRY_REV_COUNTRY_LENGTH_ERR'] = 'Enter no more than 50 characters';
		$lang['CREATE_COUNTRY_REV_EMPTY_DS_ERR'] = 'Description missing';
		$lang['CREATE_COUNTRY_REV_DS_LENGTH_ERR'] = 'Description must contains at least 50 characters and not more than 2000';
		$lang['CREATE_COUNTRY_REV_COVER_ERR'] = 'Upload a cover picture';
		$lang['CREATE_COUNTRY_REV_EMPTY_ARRIVE_ERR'] = 'Description missing';
		$lang['CREATE_COUNTRY_REV_ARRIVE_LENGTH_MIN_ERR'] = 'A good description about how to arrive must contains at least 25 characters';
		$lang['CREATE_COUNTRY_REV_ARRIVE_LENGTH_MAX_ERR'] = 'Enter no more than 500 characters';
		$lang['CREATE_COUNTRY_REV_WARN_LENGTH_MIN_ERR'] = 'A good warning should contains at least 10 characters';
		$lang['CREATE_COUNTRY_REV_WARN_LENGTH_MAX_ERR'] = 'Enter no more than 100 characters';
		$lang['CREATE_COUNTRY_COOK_LENGTH_MIN_ERR'] = 'A good review about cooking should contains at least 25 characters';
		$lang['CREATE_COUNTRY_COOK_LENGTH_MAX_ERR'] = 'Enter no more than 500 characters';
		$lang['CREATE_COUNTRY_REV_MYTH_LENGTH_MIN_ERR'] = 'Enter no more than 10 characters';
		$lang['CREATE_COUNTRY_REV_MYTH_LENGTH_MAX_ERR'] = 'Enter no more than 100 characters';
		$lang['CREATE_COUNTRY_REV_INTER_EMPTY_ERR'] = 'Enter at least one city to visit';
		$lang['CREATE_COUNTRY_CARTOON_COUNTRY'] = 'Write the name of the country or region you want review';
		$lang['CREATE_COUNTRY_CARTOON_REVIEW'] = 'Write a depth and detailed country review';
		$lang['CREATE_COUNTRY_CARTOON_INTEREST'] = 'Add city reviews of this country or region that advice to visit';
		$lang['CREATE_COUNTRY_CARTOON_WARN'] = 'If you have an advice then write your suggestion here';
		$lang['CREATE_COUNTRY_CARTOON_ARRIVE'] = 'Write how to arrive to the country, indicating how to reach at least one of the major cities. If you know more ways to reach the country or region write both indicating faster ones , the cheaper ones , and those more comfortable';
		$lang['CREATE_COUNTRY_CARTOON_MYTH'] = 'If you want debunk a myth wite your comment here. Note Well: Write your comment only if you have significant experience such as to dispel clichés';
		$lang['CREATE_COUNTRY_CARTOON_VOTE'] = 'Assign a vote from one to five to this country or region';
		$lang['CREATE_COUNTRY_ADDRS_NOT_FOUND'] = 'Country not found';
		
		// CREATE COUNTRY INTEREST
		$lang['CREATE_COUNTRY_INTEREST_TITLE']='Select and add cities of interest';
		$lang['CREATE_COUNTRY_INTEREST_ADD_BUT']='Add selected';
		$lang['CREATE_COUNTRY_INTEREST_CANC_BUT']='Close';
		$lang['CREATE_COUNTRY_INTEREST_ZERO_SELECT_ERROR']='Select at least one city to visit';
		$lang['CREATE_COUNTRY_INTEREST_MAX_SELECT_ERROR']='Select no more than 50 city to visit';
		$lang['CREATE_COUNTRY_INTEREST_SELECT']='Select';
		$lang['CREATE_COUNTRY_INTEREST_COVER']='Cover';
		$lang['CREATE_COUNTRY_INTEREST_NAME']='City name';
		$lang['CREATE_COUNTRY_INTEREST_NO_SITE']='Attention!!! No city reviewed';
		$lang['CREATE_COUNTRY_INTEREST_TO_CONTINUE'] = 'To continue review at least one city of this country';
		
		// CREATE COUNTRY STEP2 
		$lang['CREATE_COUNTRY_REV_S2_PAGE_TITLE'] = 'Check data';
		$lang['CREATE_COUNTRY_REV_S2_PAGE_H3'] = 'Check data';
		$lang['CREATE_COUNTRY_REV_S2_PAGE_DISCL'] = 'Check data and if everything OK click the Finish button !';
		$lang['CREATE_COUNTRY_REV_S2_REVIEW'] = 'Your review';
		
		// SEARCH REVIEW
		$lang['SEARCH_REVIEW_PAGE_TOP_MSG'] = 'Advanced Search';
		$lang['SEARCH_REVIEW_SEARCH_BUTTON'] = 'search';
		$lang['SEARCH_REVIEW_SEARCH_SITE_NAME'] = 'Enter keywords to search';
		$lang['SEARCH_REVIEW_SEARCH_TYPE_1'] = 'Site review';
		$lang['SEARCH_REVIEW_SEARCH_TYPE_2'] = 'City review';
		$lang['SEARCH_REVIEW_SEARCH_TYPE_3'] = 'Country review';
		$lang['SEARCH_REVIEW_ADV_ONLY_IMG'] = 'Only reviews with pictures attached';
		$lang['SEARCH_REVIEW_ADV_ONLY_MOV'] = 'Only reviews with videos attached';
		$lang['SEARCH_REVIEW_ADV_ONLY_DOC'] = 'Only reviews with documents attached';
		$lang['SEARCH_REVIEW_ADV_LANG'] = 'Language content';
		$lang['SEARCH_REVIEW_EMPTY_KWDS_ERROR'] = 'keywords missing';
		$lang['SEARCH_REVIEW_EMPTY_KWDS_LENG_ERROR'] = 'keywords must contains at least 3 characters and not more than 25';
		$lang['SEARCH_REVIEW_ILLEGAL_KWDS_ERROR'] = 'the % character is not allowed';
		
		// SEARCH REVIEW RESULT
		$lang['SEARCH_REVIEW_RESULT_TITLE'] = 'Search results';
		$lang['SEARCH_REVIEW_RESULT_BACK'] = 'Back to search';
		$lang['SEARCH_REVIEW_RESULT_H1'] = 'Search results';
		$lang['SEARCH_REVIEW_RESULT_NO_RESULT'] = 'No results for the setted search criteria';
		$lang['SEARCH_REVIEW_RESULT_OVER_EVAL'] = 'Overall evaluation';
		$lang['SEARCH_REVIEW_SEE_ALL'] = 'see all...';
		$lang['SEARCH_REVIEW_RESULT_AUTHOR'] = 'Author';
		$lang['SEARCH_REVIEW_RESULT_REVIEW'] = 'Review:';
		$lang['SEARCH_REVIEW_RESULT_HOW_ARRIVE'] = 'How to arrive:';
		$lang['SEARCH_REVIEW_RESULT_TO_VISIT'] = 'What to visit:';
		$lang['SEARCH_REVIEW_RESULT_WARN'] = 'Warnings:';
		$lang['SEARCH_REVIEW_RESULT_WHERE_TO_EAT'] = 'Where to eat:';
		$lang['SEARCH_REVIEW_RESULT_COOKING'] = 'Cooking:';
		$lang['SEARCH_REVIEW_RESULT_WHERE_TO_STAY'] = 'Where to stay:';
		$lang['SEARCH_REVIEW_RESULT_MITH'] = 'Debunking Myths:';
		$lang['SEARCH_REVIEW_RESULT_VOTE'] = 'Judgement:';
		$lang['SEARCH_REVIEW_RESULT_PAGIN_RESULT'] = 'Results found :';
		$lang['SEARCH_REVIEW_RESULT_PAGIN_BACK'] = '< Back';
		$lang['SEARCH_REVIEW_RESULT_PAGIN_NEXT'] = 'Next >';
		
		// SEARCH REVIEW MULTI RESULT
		$lang['SEARCH_REVIEW_MULTI_RES_TITLE'] = 'Search results';
		$lang['SEARCH_REVIEW_MULTI_RES_BACK'] = 'Back to search';
		$lang['SEARCH_REVIEW_MULTI_RES_H1'] = 'Search results';
		$lang['SEARCH_REVIEW_MULTI_RES_H2'] = 'More results found. Choose one from the list ...';
		$lang['SEARCH_REVIEW_MULTI_RES_SITE'] = 'Site name';
		$lang['SEARCH_REVIEW_MULTI_RES_LOC'] = 'Locality';
		$lang['SEARCH_REVIEW_MULTI_RES_COUNTRY'] = 'Country';
		$lang['SEARCH_REVIEW_MULTI_RES_CITY'] = 'City';
		
		// MY REVIEW
		$lang['MY_REV_PAGE_TITLE'] = 'My Reviews';
		$lang['MY_REV_PAGE_H3'] = 'My reviews';
		$lang['MY_REV_PAGE_DISCL'] = 'This page contains your reviews list';
		$lang['MY_REV_SITE_TITLE'] = 'Site reviews';
		$lang['MY_REV_NO_REV'] = 'There are no site reviews';
		$lang['MY_REV_CITY_TITLE'] = 'City reviews';
		$lang['MY_REV_NO_CITY_REV'] = 'There are no city reviews';
		$lang['MY_REV_COUNTRY_TITLE'] = 'Country reviews';
		$lang['MY_REV_NO_COUNTRY_REV'] = 'There are no country reviews';
		$lang['MY_REV_NO_REV'] = 'There are no reviews.';
		$lang['MY_REV_CREATE_REV'] = 'Create a Review';
		$lang['MY_REV_DT_INS'] = 'Creation date';
		$lang['MY_REV_SITE'] = 'Site';
		$lang['MY_REV_DESCR'] = 'Review';

		// SHOW REVIEW
		$lang['SHOW_REVIEW_PAGE_TITLE_NOT_FOUND'] = 'Review not found';
		$lang['SHOW_REVIEW_PAGE_H3_NOT_FOUND'] = 'Review not found';
		$lang['SHOW_REVIEW_PAGE_DISCL_NOT_FOUND'] = 'The review can not be find. There was an unexpected error.<br>The review may have been removed.<br>Try to return to your review page and click again on the review';
		$lang['SHOW_REVIEW_NO_IMG_MESSAGE'] = 'There are no images to display';
		$lang['SHOW_REVIEW_SHOW_IMG_MESSAGE'] = 'Review\'s pictures';
		$lang['SHOW_REVIEW_LOAD_NEW_IMG'] = 'Load new pictures';
		$lang['SHOW_REVIEW_NO_MOV_MESSAGE'] = 'There are no video to display';
		$lang['SHOW_REVIEW_SHOW_MOV_MESSAGE'] = 'Review\'s video';
		$lang['SHOW_REVIEW_LOAD_NEW_MOV'] = 'Load new video';
		$lang['SHOW_REVIEW_NO_DOC_MESSAGE'] = 'There are no documents to display';
		$lang['SHOW_REVIEW_SHOW_DOC_MESSAGE'] = 'Review\'s documents';
		$lang['SHOW_REVIEW_LOAD_NEW_DOC'] = 'Load new documents';
		$lang['SHOW_REVIEW_IMG_NO_COMMENT'] = 'No comment available for this picture';
		$lang['SHOW_REVIEW_MOV_NO_COMMENT'] = 'No comment available for this video';
		$lang['SHOW_REVIEW_DOC_NO_COMMENT'] = 'No comment available for this document';
		$lang['SHOW_REVIEW_LOCALITY'] = 'Locality';
		$lang['SHOW_REVIEW_REVIEW'] = 'Review:';
		$lang['SHOW_REVIEW_HOW_ARRIVE'] = 'How to arrive:';
		$lang['SHOW_REVIEW_TO_VISIT'] = 'What to visit:';
		$lang['SHOW_REVIEW_WARNING'] = 'Warnings:';
		$lang['SHOW_REVIEW_WHERE_EAT'] = 'Where to eat:';
		$lang['SHOW_REVIEW_COOKING'] = 'Cooking:';
		$lang['SHOW_REVIEW_WHERE_STAY'] = 'Where to stay:';
		$lang['SHOW_REVIEW_MYTH'] = 'Debunking Myths:';
		$lang['SHOW_REVIEW_VOTE'] = 'Judgement:';
		
		//STAR SEE ORDER 
		$lang['SSO_POST_COMMENT'] = 'Post a comment (max 140 characters)';
		$lang['SSO_POST_SEND'] = 'Post';
		$lang['SSO_ORDER_BY'] = 'Order by:';
		$lang['SSO_ORDER_DATA'] = 'Descending Date';
		$lang['SSO_ORDER_DATA_DESC'] = 'Increasing Date';
		$lang['SSO_ORDER_VOTE'] = 'Descending Rating';
		$lang['SSO_ORDER_VOTE_DESC'] = 'Increasing Rating';
		$lang['SSO_ORDER_STAR'] = 'Descending Stars';
		$lang['SSO_ORDER_STAR_DESC'] = 'Increasing Stars';
		
		// MESSAGES
		$lang['MESSAGE_TOP_MSG_OK'] = 'Message sent successfully';
		$lang['MESSAGE_TOP_MSG_DFT_OK'] = 'Message saved successfully';
		$lang['MESSAGE_PAGE_TITLE'] = 'Messages';
		$lang['MESSAGE_PAGE_H3'] = 'Messages';
		$lang['MESSAGE_PAGE_DISCL'] = 'send and receive messages with users';
		$lang['MESSAGE_NEW'] = 'New Message';
		$lang['MESSAGE_TAB_SENT'] = 'Sent';
		$lang['MESSAGE_TAB_IN'] = 'Received';
		$lang['MESSAGE_TAB_DRAFT'] = 'Draft';
		$lang['MESSAGE_TAB_TRASH'] = 'Trash';
		$lang['MESSAGE_SENT_H3'] = 'Sent Messages';
		$lang['MESSAGE_IN_H3'] = 'Received Messages';
		$lang['MESSAGE_DRAFT_H3'] = 'Draft Messages';
		$lang['MESSAGE_TRASH_H3'] = 'Deleted Messages';
		$lang['MESSAGE_NO_SENT_MSG'] = 'No messages sent';
		$lang['MESSAGE_NO_RECEV_MSG'] = 'No messages received';
		$lang['MESSAGE_NO_DRAFT_MSG'] = 'No messages in draft';
		$lang['MESSAGE_NO_TRASH_MSG'] = 'No messages in trash';
		$lang['MESSAGE_COMMON_FROM'] = 'From';
		$lang['MESSAGE_COMMON_TO'] = 'TO';
		$lang['MESSAGE_COMMON_DT'] = 'Date';
		$lang['MESSAGE_COMMON_SBJ'] = 'Subject';
		$lang['MESSAGE_COMMON_MSG'] = 'Message';
		$lang['MESSAGE_OVERLAY_RECIPIENT'] = 'Recipient';
		$lang['MESSAGE_BACK_LINK'] = 'Back to messages';
		$lang['MESSAGE_OVERLAY_SAVE'] = 'Save Message';
		$lang['MESSAGE_OVERLAY_SEND'] = 'Send Message';
		$lang['MESSAGE_OVERLAY_DEL'] = 'Delete';
		$lang['MESSAGE_OVERLAY_RESTORE'] = 'Resore';
		$lang['MESSAGE_OVERLAY_REPLY'] = 'Reply';
		$lang['OVERLAY_DEL_MSG_NO_SEL_TITLE'] = 'Invalid operation';
		$lang['OVERLAY_DEL_MSG_NO_SEL'] = 'No messages selected';
		$lang['MESSAGE_SEND_TO_EMPTY_ERR'] = 'Enter recipient';
		$lang['MESSAGE_SEND_TO_NOT_EXISTS'] = 'the recipient does not exists';
		$lang['MESSAGE_SEND_SBJT_EMPTY_ERR'] = 'Enter a subject';
		$lang['MESSAGE_SEND_SBJT_LENGTH_MIN_ERR'] = 'Subejct must contains at least 100 characters ';
		$lang['MESSAGE_SEND_MESSAGE_EMPTY_ERR'] = 'Enter a message';
		$lang['MESSAGE_SEND_MESSAGE_LENGTH_MIN_ERR'] = 'Message must contains at least 2000 characters ';
		$lang['MESSAGE_SEND_MESSAGE_NO_PERMITTED'] = 'You are not allowed to send messages to the user';
		
		// OVERLAY-LOGIN-SIGNIN
		$lang['OVERLAY_LOG_SIGN_LOGIN'] = 'login';
		$lang['OVERLAY_LOG_SIGN_PWD_FORGOT'] = 'forgot password?';
		$lang['OVERLAY_LOG_SIGN_SINGUP'] = 'singup';
		$lang['OVERLAY_LOG_SIGN_EMAIL'] = 'Email';
		$lang['OVERLAY_LOG_SIGN_PSWD'] = 'Password';
		$lang['OVERLAY_LOG_SIGN_NAME'] = 'Name';
		$lang['OVERLAY_LOG_SIGN_SIGNIN'] = 'Sign in';
		$lang['OVERLAY_LOG_SIGN_RECOVER'] = 'Recover';
		$lang['OVERLAY_LOG_SIGN_SEND_MAIL_OK'] = 'Operation carried out successfully . Check your email and answer the link we sent you';
		$lang['OVERLAY_LOG_SIGN_EMPTY_EMAIL_ERR'] = 'Email field missing';
		$lang['OVERLAY_LOG_SIGN_EMPTY_PWD_ERR'] = 'Password field missing';
		$lang['OVERLAY_LOG_SIGN_EMPTY_NCK_ERR'] = 'Name field missing';
		$lang['OVERLAY_LOG_SIGN_EMAIL_OR_PWD_INC_ERR'] = 'Email or password incorrect';
		$lang['OVERLAY_LOG_SIGN_EMAIL_EXISTS_ERR'] = 'Email already exists, choose another email and try again';
		$lang['OVERLAY_LOG_SIGN_NAME_EXISTS_ERR'] = 'Name already exists, choose another name and try again';
		$lang['OVERLAY_LOG_SIGN_PWD_LENGTH_ERR'] = 'Password must be at least 6 characters and no more than 20';
		$lang['OVERLAY_LOG_SIGN_NAME_LENGTH_ERR'] = 'Name must be at least 4 characters and no more than 20';
		$lang['OVERLAY_LOG_SIGN_CONF_TERMS'] = 'Confirm you have read the';
		$lang['OVERLAY_LOG_SIGN_LINK_TERMS'] = 'conditions';
		$lang['OVERLAY_LOG_SIGN_TERMS_ERR'] = 'Please confirm terms and conditions';
		$lang['OVERLAY_LOG_SIGN_REMEMBER'] = 'Remember me';
		$lang['OVERLAY_LOG_SIGN_RECOVER_OK'] = 'We sent you an email ; make sure you have received the message and click the link that we have sent to complete the task';
		$lang['OVERLAY_LOG_SIGN_INSERT_CAPTCHA'] = 'Enter Security Code';
		$lang['OVERLAY_LOG_SIGN_REFRESH_CAPTCHA'] = 'refresh';
		$lang['OVERLAY_LOG_SIGN_CPTCH_CD_ERROR'] = 'Wrong Security Code';
		
		// OVERLAY-DEL-ITEM
		$lang['OVERLAY_DEL_ITM_TITLE'] = 'Confirm deletion';
		$lang['OVERLAY_DEL_ITM_QUESTION'] = 'Are you sure you want delete the selected file ?';
		$lang['OVERLAY_DEL_ITM_ANSW_YES'] = 'Yes, go on';
		$lang['OVERLAY_DEL_ITM_ANSW_CANC'] = 'Annul';
		
		// OVERLAY-DEL-MSG
		$lang['OVERLAY_DEL_MSG_TITLE'] = 'Confirm operation';
		$lang['OVERLAY_DEL_MSG_QUESTION'] = 'Are you sure you want delete the selected messages ?';
		$lang['OVERLAY_RESTORE_MSG_QUESTION'] = 'Are you sure you want restore the selected messages ?';
		$lang['OVERLAY_DEL_MSG_ANSW_YES'] = 'Yes, go on';
		$lang['OVERLAY_DEL_MSG_ANSW_CANC'] = 'Annul';

		// OVERLAY-DEN-USR
		$lang['OVERLAY_DEN_USR_TITLE'] = 'Confirm operation';
		$lang['OVERLAY_DEN_USR_QUESTION'] = 'Are you sure you want block this user ?';
		$lang['OVERLAY_DEN_USR_QUESTION2'] = 'Are you sure you want unlock this user ?';
		$lang['OVERLAY_DEN_USR_ANSW_YES'] = 'Yes, go on';
		$lang['OVERLAY_DEN_USR_ANSW_CANC'] = 'Annul';
		
		// SIGNIN_OK_PAGE
		$lang['CONF_SIGN_TITLE'] = 'Sing In OK';
		$lang['CONF_SIGN_H3'] = 'Confirm Sing On';
		$lang['CONF_SIGN_H3_OK'] = 'Sing On confirmed';
		$lang['CONF_SIGN_PAGE_KO'] = 'Something has gone wrong, the page is not currently available!';
		$lang['CONF_SIGN_PAGE_OK'] = 'The operation completed successfully!';
		$lang['CONF_SIGN_PAGE_OK_NOW'] = 'Now you can start !!! To review a site click';
		$lang['CONF_SIGN_PAGE_OK_NOW_HOME'] = 'To return to the home page click';
		$lang['CONF_SIGN_PAGE_OK_HR'] = 'here';
		$lang['CONF_SIGN_FORM_TITLE'] = 'There we are, you just have to confirm the password and you\'re ready to start !!!';
		$lang['CONF_SIGN_PAGE_NON_AVLB_TIT'] = 'Possible causes';
		$lang['CONF_SIGN_PAGE_NON_AVLB_CAUSE_1'] = 'The page has expired because it\'s been too long since you signed on. Repeat the sing in procedure.';
		$lang['CONF_SIGN_PAGE_NON_AVLB_CAUSE_2'] = 'The link is no longer available. The user has already been confirmed previously';
		$lang['CONF_SIGN_PAGE_NON_AVLB_CAUSE_3'] = 'You copied the wrong link. Regards the email and try again';
		$lang['CONF_SIGN_PWD'] = 'Confirm password';
		$lang['CONF_SIGN_DONE'] = 'Done !';
		$lang['CONF_SIGN_EMPTY_PWD_ERR'] = 'Password field missing';
		$lang['CONF_SIGN_PWD_ERR'] = 'Password incorrect';
		
		// RECOVER_PWD
		$lang['RECOVER_PWD_TITLE'] = 'Password Recover';
		$lang['RECOVER_PWD_H3'] = 'Password Recover';
		$lang['RECOVER_PWD_H3_OK'] = 'You are completing password recover procedure';
		$lang['RECOVER_PWD_PAGE_KO'] = 'Something has gone wrong, the page is not currently available!';
		$lang['RECOVER_PWD_FORM_TITLE'] = 'Insert a new password e confirm it';
		$lang['RECOVER_PWD_SUBMIT_COMPLETE'] = 'Complete';
		$lang['RECOVER_PWD_PAGE_NON_AVLB_TIT'] = 'Possible causes';
		$lang['RECOVER_PWD_PAGE_NON_AVLB_CAUSE_1'] = 'The page has expired because it\'s been too long since recover requested. Repeat recover if necessary.';
		$lang['RECOVER_PWD_PAGE_NON_AVLB_CAUSE_2'] = 'The link is no longer available. The recover has already done previously';
		$lang['RECOVER_PWD_PAGE_NON_AVLB_CAUSE_3'] = 'You copied the wrong link. Regards the email and try again';
		$lang['RECOVER_PWD_PWD'] = 'password';
		$lang['RECOVER_PWD_CNFRM_PWD'] = 'confirm password';
		$lang['RECOVER_PWD_EMPTY_PWD_ERR'] = 'Password field missing';
		$lang['SETTING_PWD_REPET_ERROR'] = 'Passwords do not match';
		$lang['RECOVER_PWD_MAX_REQUEST_ERROR'] = 'You have exceeded the maximum number of requests . You must wait 24 hours before making a new request .';
		$lang['RECOVER_PWD_EMAIL_NOT_EX'] = 'Email not exist';
		
		// MENU
		$lang['COMM_MENU_HOME'] = 'Home';
		$lang['COMM_MENU_REVIEW'] = 'Reviews';
		$lang['COMM_MENU_PROFILE'] = 'Profile';
		$lang['COMM_MENU_MYPROFILE'] = 'My Profile';
		$lang['COMM_MENU_CREATE_REVIEW'] = 'Review a site';
		$lang['COMM_MENU_ADV_SEARCH'] = 'Advanced Search';
		$lang['COMM_MENU_MY_REVIEW'] = 'My Reviews';
		$lang['COMM_MENU_CITY_REVIEW'] = 'Review a city';
		$lang['COMM_MENU_COUNTRY_REVIEW'] = 'Review a country';
		$lang['COMM_MENU_SETTINGS'] = 'Settings';
		$lang['COMM_MENU_MESSAGES'] = 'Messaggi';
		$lang['COMM_MENU_ENTER'] = 'Enter';
		$lang['COMM_MENU_EXIT'] = 'Exit';
		$lang['COMM_SEARCH_MSG'] = 'Search';

		// FOOTER
		$lang['COMM_FOOTER_TXT'] = '&copy; 2016 Viantes';
		$lang['COMM_FOOTER_WHO'] = 'About us';
		$lang['COMM_FOOTER_MISISON'] = 'Mission';
		$lang['COMM_FOOTER_CONTACTS'] = 'Contacts';
		$lang['COMM_FOOTER_COOKIE_INFO'] = 'Cookie Policy';
		$lang['COMM_FOOTER_TERMS'] = 'Terms and conditions';

		// CONTACT
		$lang['CONTACT_TITLE'] = 'Contact';
		$lang['CONTACT_DISCL'] = 'Use this form to send us your comments';
		$lang['CONTACT_NAME'] = 'Name';
		$lang['CONTACT_MAIL'] = 'Email';
		$lang['CONTACT_COMMENT'] = 'Comment';
		$lang['CONTACT_SECURE'] = 'Digit security code';
		$lang['CONTACT_SEND'] = 'Send';
		$lang['CONTACT_MSG_SEND_OK'] = 'Comment sent successfully';
		$lang['CONTACT_EMPTY_NAME_ERR'] = 'Name missing';
		$lang['CONTACT_NAME_LENGTH_ERR'] = 'Name must contains at least 3 characters and not more than 60';
		$lang['CONTACT_EMPTY_EMAIL_ERR'] = 'Email missing';
		$lang['CONTACT_EMPTY_DS_ERR'] = 'Comment missing';
		$lang['CONTACT_DS_LENGTH_ERR'] = 'Comment must contains at least 10 characters and not more than 500';

		// GENERIC
		$lang['GEN_IS_NOT_EMAIL_REG'] = 'Email format is incorrect';
		$lang['GEN_IS_NOT_PWD_REG'] = 'Password must contains at least an uppercase letter, a lowercase letter, and a number';
		$lang['GEN_IS_NOT_NAME_REG'] = 'Name must contains only letters and numbers';
		$lang['GEN_REQUEST_OK'] = 'Request performed successfully';
		$lang['GEN_REQUEST_KO'] = 'Generic error during your request. Please try again';
		$lang['GEN_CANCEL'] = 'Delete';
		$lang['GEN_EXPAND'] = 'Expand';
		$lang['GEN_PLAY'] = 'Play';
		$lang['GEN_PAUSE'] = 'Pause';
		$lang['GEN_CLOSE'] = 'Close';
		$lang['GEN_GALLERY'] = 'Gallery';
		$lang['GEN_NO_RESULT'] = 'The list is empty';
		
		//FILE UPLOAD
		$lang['UPLOAD_IMG_ERR_TYPE']='The file you are uploading is not an image! Please load only image file type';
		$lang['UPLOAD_MOV_ERR_TYPE']='The file you are uploading is not a video! Please load only video file type';
		$lang['UPLOAD_MOV_ERR_FORMAT']='The file you are uploading could be corrupted or the video format is unknown';
		$lang['UPLOAD_DOC_ERR_TYPE']='The file you are uploading is not a pdf! Please load only pdf file type';
		$lang['UPLOAD_ERR_NAME_TOO_LONG']='File name is too long (max 60 characters).';
		$lang['UPLOAD_ERR_INI_SIZE']='The uploaded file exceeds the upload max filesize! Please try with a file smaller than 100 M byte';
		$lang['UPLOAD_ERR_PARTIAL']='The uploaded file was only partially uploaded! Please try again';
		$lang['UPLOAD_ERR_NO_FILE'] = 'No file was uploaded! Please try again';
		$lang['UPLOAD_ERR_NO_TMP_DIR'] = 'Error during load file! Please try again';
		$lang['UPLOAD_ERR_CANT_WRITE'] = 'Failed to upload file! Please try again';
		$lang['UPLOAD_ERR_EXTENSION'] = 'File upload is locked! Try again, if the problem still choose another file';
		$lang['UPLOAD_ERR_PART_UPLOADED'] = 'The uploaded file was only partially uploaded! Try again, if the problem still choose another file';
		$lang['UPLOAD_ERR_IMG_TOO_SMALL'] = 'Image too small. Load a larger image please!';
		$lang['UPLOAD_ERR_IMG_TOO_BIG'] = 'Image too big. Load a smaller image please!';
		$lang['UPLOAD_ERR_ONLY_JPEG'] = 'You can load only JPEG picture type for your profile';
		$lang['UPLOAD_ERR_IMG_MAX_NUM_EXCEEDED'] = 'Maximum number of photos exceeded';
		$lang['UPLOAD_ERR_MOV_MAX_NUM_EXCEEDED'] = 'Maximum number of videos exceeded';
		$lang['UPLOAD_ERR_DOC_MAX_NUM_EXCEEDED'] = 'Maximum number of documents exceeded';
		
		//MAIL 
		$lang['SEND_MAIL_RECOVER_PWD_SBJ'] = 'Recovery pasword';
		$lang['SEND_MAIL_RECOVER_PWD_TITLE'] = 'Recovery pasword';
		$lang['SEND_MAIL_RECOVER_PWD_P1'] = 'We are managing your password recovery request : to complete the process click the following link : ';
		$lang['SEND_MAIL_RECOVER_PWD_P2'] = '<b>Warning</b> if you have not made any demands ignore this email and do not click on the above link . Remember that the link is valid for 48 hours , then it will be invalidated';
		$lang['SEND_MAIL_SIGNIN_SBJ'] = 'Welcome ';
		$lang['SEND_MAIL_SIGNIN_PWD_TITLE1'] = 'Hi ';
		$lang['SEND_MAIL_SIGNIN_PWD_TITLE2'] = ' ,<br>Registration was successful !!!'; 
		$lang['SEND_MAIL_SIGNIN_PWD_P1'] = 'To complete your registration please click on the following link : '; 
		$lang['SEND_MAIL_SIGNIN_PWD_P2'] = 'If you have problems copy and paste the following link on the browser : ';
		
		//ERROR PAGE
		$lang['ERROR_TITLE'] = 'Error';
		$lang['ERROR_H1'] = 'Error';
		$lang['ERROR_REASON_UNEXPECTED'] = 'An unexpected error occurred';
		$lang['ERROR_BACK_TO_HOME'] = 'Back to <a href="https://www.viantes.com/">home</a>';
		$lang['ERROR_REASON_SESSION_EXPIRED'] = 'Session expired';
		$lang['ERROR_CLOSE'] = 'Close';
		
		//TERMS
		$lang['TERMS_TITLE'] = 'Terms';
		$lang['TERMS_H1'] = 'Terms and Conditions';
		$lang['TERMS_DISCL'] = 'Terms and conditions of Service';
		
		//INO_COOKIE
		$lang['INO_COOKIE_MSG'] = "This site uses cookies and other similar technologies to guarantee a Better Experience While browsing on our website. For more information about its click <a target=\"_blank\" href=\"/viantes/pub/pages/infoCookie.php\">here</a>. Closing this information window or continuing browsing you consent to the use of cookies and other similar technologies.";
		
		//LANGUAGE
		$lang['it'] = 'Italian';
		$lang['en'] = 'English';
		
		return $lang;
	}
}
?>
