//Separatore di lista
var listDelimiter='#@#'
//Separatore di elemento
var elementDelimiter = '@#@';


/* ##########################################################
   ########################## OVERLAY ####################### */

/* Mostra la pagina overlay-login-signin.html e setta i campi nascosti */
function showOvrlyLgSg(){
	var srcPage = $('#ovrly-initial-src-page').val();
	var destPageLg = $('#ovrly-initial-login-dst-page').val();
	var destPageSg = $('#ovrly-initial-sign-dst-page').val();
	
	$('html,body').scrollTop(0);
	$('#overlay-login-signin').show();
	
	$('#overlay-srcPage-lg').val(srcPage);
	$('#overlay-srcPage-sg').val(srcPage);
	$('#overlay-srcPage-rc').val(srcPage);
	
	$('#overlay-destPage-lg').val(destPageLg);
	$('#overlay-destPage-sg').val(destPageSg);
	$('#overlay-destPage-rc').val(destPageSg);
	
	showOverlayIfIE('#eaeaea');
}

/* Mostra la pagina overlay-login-signin.html con il login visibile e la registrazione nascosta */
function showOvrlyLgSgMod1(){
	showOvrlyLgSg();
	$('#overlay-lg-sg-first').show();
	$('#overlay-lg-sg-second').hide();
	$('#overlay-lg-sg-third').hide();
}

/* Nasconde l'overlay del login */
function hideOvrlyLgSg(){
	$('#overlay-login-signin').hide();
	hideOverlayIfIE();
	window.location.href = $('#overlay-srcPage-lg').val();
}

/* Mostra l'overlay per il caricamento di un'immagine un video o un doc */
function showOverlayForLoad(text, submitId) {
	$('#overlay-loadImg-simple-msg').text(text); 
	$('#overlay-loadImg').show();  
	$('#'+submitId+'').click();
	showOverlayIfIE('#fff');
}

/* Mostra l'overlay dell'invio dei messaggi */
function showMsgOverlay(urlBack) {
	showMsgOverlayNoUrl();
	$('#overlay-msg-url-back').val(urlBack);
}
/* Mostra l'overlay dell'invio dei messaggi 
 * l'url back e' impostata dal chiamante*/
function showMsgOverlayNoUrl() {
	$('#overlay-msg-new').show(); 
	showOverlayIfIE('#eaeaea');
}

/* Mostra l'overlay se il browser e' IE!!!*/
function showOverlayIfIE(bgColor){
	//default (potrebbe non arrivarmi in tal caso)
	if (bgColor == null) { bgColor = '#eaeaea';}
	
	if ( $.browser.msie && isIE7OrIE8() ) {
		$('#main-banner-fade-id').hide();
		$('#main-div').hide();
		$('#footer-div').hide();
		$('html').css('background-color', bgColor);
	}
}

/* Nasconde l'overlay se il browser e' IE7/8 !!!*/
function hideOverlayIfIE(){
	if ( $.browser.msie && isIE7OrIE8() ) {
		$('#main-banner-fade-id').show();
		$('#main-div').show();
		$('#footer-div').show();
		$('html').css('background-color', '#bcbcbc');//Attenzione: questo e' quello del body
	}
}
/* Torna true se la versione di ie e' un 7.x o una 8.x*/
function isIE7OrIE8(){
	var version = $.browser.version;
	var idx7 = version.indexOf("7."); //e' = a 0 se la versione e' un 7.x
	var idx8 = version.indexOf("8."); //e' = a 0 se la versione e' un 8.x
	return (idx7 == 0 || idx8 == 0 );
}


/* ###############################################################
   ########################## ASYNC FILEDS ####################### */

/**
 * Mostra il fumetto descrittivo per i campi che lo prevedono
 */
function showCartoon(fieldName){
	//mostro il fumetto descrittivo 
	$('#'+fieldName+'FrcCart').css('display', 'inline');
	$('#'+fieldName+'FrcCart').css('zoom', '1'); 
	$('#'+fieldName+'Cartoon').css('display', 'inline'); 
	$('#'+fieldName+'Cartoon').css('zoom', '1');
}

/**
 * Nasconde il fumetto descrittivo per i campi che lo prevedono
 */
function hideCartoon(fieldName){
	//nascondo il fumetto descrittivo 
	$('#'+fieldName+'FrcCart').css('display', 'none');
	$('#'+fieldName+'Cartoon').css('display', 'none'); 
}

/**
 * Invoca una chiamata GET asincrona verso una "destUrl" avente come id del campo
 * da dove leggre il dato "fieldName". Da usare per gli input text. La chiamata
 * e' invocata solo se la lunghezza della stringa digitata non e' inferiore "minLength".
 */
function doFieldAsyncGetMinLen(destUrl, fieldName, minLength){
	if ($('#'+fieldName+'').val().length == 0) {
		$('#'+fieldName+'FldLD').hide(); //nascondo l'immagine di load
		$('#'+fieldName+'FldOK').hide(); //nascondo l'immagine di load
		return;
	}
	
	$('#'+fieldName+'Blank').hide();
	$('#'+fieldName+'FldOK').hide();
	$('#'+fieldName+'FldLD').show();
	
	if ($('#'+fieldName+'').val().length < minLength ) {
		//alert('return');
		return;
	}
	
	doFieldAsyncGet(destUrl, fieldName);
}

/**
 * Esegue l'autocompletamento del campo fieldName verso l'url destUrl 
 * se il numero di caratteri e' maggiore di minLength
 */
function doAutocomplete(destUrl, fieldName, minLength){
	if ($('#'+fieldName+'').val().length == 0) {
		$('#'+fieldName+'FldLD').hide(); //nascondo l'immagine di load
		return;
	}

	$('#'+fieldName+'FldOK').hide();
	$('#'+fieldName+'Blank').hide();
	$('#'+fieldName+'FldLD').show();
	
	if ($('#'+fieldName+'').val().length < minLength ) {
		//alert('return');
		return;
	}
	
	doFieldAsyncGet(destUrl, fieldName);
}

/**
 * Invoca una chiamata GET asincrona verso una "destUrl" avente come id del campo
 * da dove leggre il dato "fieldName". Da usare per gli input text. 
 * LAvora SOLO per campi con lunghezza > 0
 */
function doFieldAsyncGet(destUrl, fieldName) {
	if ($('#'+fieldName+'').val().length == 0) {
		
		$('#'+fieldName+'').removeClass("errorInput");
		//nascondo DIV errore
		$('#'+fieldName+'DIV').hide();
		return;
	}
	doGet(destUrl, fieldName);
}

/**
 * Invoca una chiamata GET asincrona verso una "destUrl" avente come id del campo
 * da dove leggre il dato "fieldName".
 */
function doGet(destUrl, fieldName){
	//poiche le new line vengono automaticamente eliminate dalla GET, per garantire che la lunghezza di una stringa sia uguale 
	//sia quando e' inviata in GET sia quando e' inviata in POST (altrimenti la POST risulta piu' lunga), devo fare cosi'
	var valNoNewLine = $('#'+fieldName+'').val().replace(/(?:\r|\n)/g, '§');
	//valNoNewLine.replace(/(?:\r)/g, '§');
	//valNoNewLine.replace(/(?:\n)/g, '§');
	
	dataString = fieldName + "=" + valNoNewLine;
	$.ajax({
		type: "GET",
		url: destUrl,
		data: dataString,
		dataType: "html",
		success: function(response){
			//alert(response);
			var arr = response.split('=');
			if (arr.length == 1) {
				//$('#'+fieldName+'').css('border', '1px solid #FFAA00');
				$('#'+fieldName+'').removeClass("errorInput");
				
				//nascondo DIV errore
				//$('#'+fieldName+'DIV').hide();
				$('#'+fieldName+'DIV').find("p").text('');
				$('#'+fieldName+'ErrMsgHidden').val('');
				
				//mostro le immagini corrette
				$('#'+fieldName+'FldOK').show();
				$('#'+fieldName+'FldLD').hide();
				
				return;
			}
			$('#'+fieldName+'').addClass("errorInput");
			
			var msg = (decodeURIComponent(arr[1]).replace(/\+/g, " ")); //sostituisce tutte le occ di + in " "
			$('#'+fieldName+'DIV').find("p").html(msg);
			$('#'+fieldName+'DIV').show();
			
			$('#'+fieldName+'FldOK').hide();
			$('#'+fieldName+'FldLD').hide();
		},
		error: function(){
			//no action
		}
	});
}


/* ###############################################################
   ####################### BUILD MEDIA TAG ####################### */

/**
 * Ottiene un tag immagine a partire da una chiamata get asincrona
 */
function doImgTagAsyncGet(destUrl, id, revTp){
	dataString = "id=" + id + "&revTp=" + revTp;
	$.ajax({
		type: "GET",
		url: destUrl,
		data: dataString,
		/* contentType: "blob",*/
		contentType: "html",
		success: function(response){
			/* ########## GESTIONE ALTERNATIVA CON BLOB 
			$('#realArtImg_'+id).attr('src', "data:image/jpeg;base64," + response);
			######## FINE GESTIONE ALTERNATIVA CON BLOB  */
			var itemArray = response.split('@#@');
			var srcImgAttribute = itemArray[0];
			var comment = itemArray[1];
			
			$('#loadRevImg_'+id).hide();
			$('#realArtImg_'+id).attr('src', srcImgAttribute);
			$('#realArtImg_'+id).show();
			
			$('#commentImg_'+id).val(comment);
		},
		error: function(){
			//no action
		}
	});
}

/**
 * Ottiene un tag video a partire da una chiamata get asincrona
 */
function doMovTagAsyncGet(destUrl, id, revTp){
	dataString = "id=" + id + "&revTp=" + revTp;
	$.ajax({
		type: "GET",
		url: destUrl,
		data: dataString,
		/* contentType: "blob",*/
		contentType: "html",
		success: function(response){
			var itemArray = response.split('@#@');
			var srcFileName = itemArray[0];
			var comment = itemArray[1];
			var xDim = itemArray[2];
			var yDim = itemArray[3];
			var height = Math.round( 400 / xDim * yDim );
			
			$('#loadRevImg_'+id).hide();
			$('#spanMov'+id).after( 
				"<object height=\"" + height + "\" width=\"400\" >" + 
					"<param name=\"movie\" value=\"" + srcFileName+ "\">" +
					"<param name=\"play\" value=\"false\" />" +
					"<param name=\"wmode\" value=\"transparent\" />" +
					"<embed id=\"crtReviewMov" + id + "\" src=\"" + srcFileName+ "\" "  +
						   "type=\"application/x-shockwave-flash\" volume=\"100\" " +
						   "wmode=\"transparent\" allowscriptaccess=\"always\" allowfullscreen=\"true\" play=\"false\" " +
						   "height=\"" + height + "\" width=\"400\">" +
					"</embed>" + 
				"</object>" +
				"<input type=\"hidden\" id=\"videoStartStopForIE_"+id+"\" value=\"false\">");
			$('#commentMov_'+id).val(comment);
			$('#playMov'+id).css('margin-top', ((height)/2)+'px' );
		},
		error: function(){
			//no action
		}
	});
}

/**
 * Setta i campi opportuni nel tag contenitore dei documenti 
 */
function doDocTagAsyncGet(destUrl, id, revTp){
	dataString = "id=" + id + "&revTp=" + revTp;
	$.ajax({
		type: "GET",
		url: destUrl,
		data: dataString,
		contentType: "html",
		success: function(response){
			var itemArray = response.split('@#@');
			var fullFileName = itemArray[0];
			var shortFileName = itemArray[1];
			var comment = itemArray[2];
			
			$('#loadRevImg_'+id).hide(); 
			$('#docAnchor_'+id).attr('href', fullFileName);
			$('#docAnchor_'+id).append(shortFileName);
			$('#docAnchor_'+id).show();

			$('#docIco_'+id).show();
			
			$('#commentDoc_'+id).val(comment);
		},
		error: function(){
			//no action
		}
	});
}


/* ###############################################################
   ###################### BUILD INDEX PAGE ####################### */

/**
 * Costruisce "n" reviewItemBox a partire da una chiamata asincrona
 */
function doReviewItemBox(numOfBox, mode, txt1, txt2, loggeUsrId, isMob){
	var reviewId = ( mode == "PUSH" ) ? $("input[id=\"topReviewId\"]:hidden").val() : $("input[id=\"bottomReviewId\"]:hidden").val();
	var cityRevId = ( mode == "PUSH" ) ? $("input[id=\"topCityReviewId\"]:hidden").val() : $("input[id=\"bottomCityReviewId\"]:hidden").val();
	var countryRevId = ( mode == "PUSH" ) ? $("input[id=\"topCountryReviewId\"]:hidden").val() : $("input[id=\"bottomCountryReviewId\"]:hidden").val();

	$.ajax({
		type: "POST",
		url: "/viantes/pvt/pages/review/show/showRevListAsy.php",
		data: {reviewId: reviewId, cityRevId : cityRevId, countryRevId : countryRevId, numOfBox: numOfBox, mode: mode},
		dataType: "html",
		success: function(response){
			//alert(response);
			if (response == '') return;
			
			var primoReviewId     = true;
			var primoCityRevId    = true;
			var primoCountryRevId = true;
			
			var arr = response.split(listDelimiter);
			for (var i = 0; i < arr.length; i++) {
				var item = arr[i];
				var itemArray = item.split(elementDelimiter);
				var revId = itemArray[0]; //review id
				var usrId = itemArray[1]; //user id
				var siteName = itemArray[2]; //siteName
				var dt_In = itemArray[3]; //data inserimento
				var descr = itemArray[4]; //descrizione
				descr = descr.replace(/<br>/g, ' ');
				descr = descr.replace(/<br \/>/g, ' ');
				descr = descr.substring(0, 75)
				var revCV = itemArray[5]; //review cover 
				var usrNm = itemArray[6]; //user name
				var usrCv = itemArray[7]; //user profile cover
				var reviewCoverWidt = itemArray[8]; //ratio img widt (supposed height=128)
				var reviewType = itemArray[9];
				var star = itemArray[10]; star = parseInt(star) > 999 ? "999+" : star;
				var see = itemArray[11];  see  = parseInt(see)  > 999 ? "999+" : see;
				var post = itemArray[12]; post = parseInt(post) > 999 ? "999+" : post;
				//TODO string buffer 
				var revItemBoxDiv = "<div class=\"reviewItemBoxContainer\">" + 
										"<div class=\"reviewItemBoxTop\">" + 
											"<a href=\"viantes/pub/pages/profile/" + (loggeUsrId == usrId ? "myProfile.php" : "showProfile.php?usrId=" +usrId) + " \">" +
												"<img src=\"" + usrCv + "\" width=\"36\" height=\"36\" class=\"reviewItemsUsrCover\" >" +
											"</a>" +
											"<p><b>" + 
												"<a href=\"viantes/pub/pages/profile/" + (loggeUsrId == usrId ? "myProfile.php" : "showProfile.php?usrId=" +usrId) + " \"> " + 
													usrNm + 
												"</a></b>&nbsp;&nbsp;" + 
												txt1 + " <b>" + siteName + "</b>" +
											"</p>" +
										"</div>" +
										"<div class=\"reviewItemBoxCenter\">" + 
											"<img src=\"" + revCV + "\" width=\"" + reviewCoverWidt + "\" height=\"128\"" + (isMob ? " style=\"display:block; margin-bottom: 6px;\" " : "" ) + ">" + 
											"<p>" + 
												descr +
												"<br><br>" + 
												"<a href=\"viantes/pub/pages/review/showReview.php?revId=" +revId+ "&reviewType=" + reviewType + " \">" 
													+ txt2 +
												"</a>" +
												"<br><br>" + 
											"</p>" +
											"<div class=\"reviewItemBoxCenterButtons\">" +
												"<a href=\"viantes/pub/pages/review/showReview.php?revId=" +revId+ "&reviewType=" + reviewType + " \">" +
													"<img src=\"/viantes/pvt/img/review/star_666.png\" />"+
													"<b><p class=\"reviewItemBoxStarP\"> " + star + "</p></b>" +
													"<img src=\"/viantes/pvt/img/review/see_666.png\" style=\"margin-left: 14px\" />"+
													"<b><p class=\"reviewItemBoxStarP\"> " + see + "</p></b>" +
													"<img src=\"/viantes/pvt/img/review/post_666.png\" style=\"margin-left: 14px; width: 18px;\" />"+
													"<b><p class=\"reviewItemBoxStarP\" style=\"margin-left:-10px\"> " + post + "</p></b>" +
												"</a>" +
											"</div>" +
										"</div>" +
									"</div>";
				
				//Se sono in modalita' PUSH
				if ( mode == 'PUSH' ) {
					//Non e' la prima volta che faccio una push => Push
					if ( $("input[id=\"exec\"]:hidden").val() != 0 ) {	

						$('#reviewItemBoxId').prepend(revItemBoxDiv);

						//Aggiorno il campo nascosto (TOP) con il primo della lista -- caso review
						if ( reviewType == 1 && primoReviewId )	{
							$("input[id=\"topReviewId\"]:hidden").val(revId);
							primoReviewId = false;
						}
						
						//Aggiorno il campo nascosto (TOP) con il primo della lista -- caso city review
						if ( reviewType == 2 && primoCityRevId )	{
							$("input[id=\"topCityReviewId\"]:hidden").val(revId);
							primoCityRevId = false;
						}
						
						//Aggiorno il campo nascosto (TOP) con il primo della lista -- caso country review
						if ( reviewType == 3 && primoCountryRevId )	{
							$("input[id=\"topCountryReviewId\"]:hidden").val(revId);
							primoCountryRevId = false;
						}
					} 
					else {
					//Viceversa e' la primissima volta => faccio una append pur essendo 
					//in modalita' PUSH. Inizializzo entrambe le variabili TOP e BOTTOM
						$('#reviewItemBoxId').append(revItemBoxDiv);

						//Aggiorno il campo nascosto (TOP) con il primo della lista -- caso review
						if ( reviewType == 1 && primoReviewId )	{
							$("input[id=\"topReviewId\"]:hidden").val(revId);
							primoReviewId = false;
						}
						
						//Aggiorno il campo nascosto (TOP) con il primo della lista -- caso city review
						if ( reviewType == 2 && primoCityRevId )	{
							$("input[id=\"topCityReviewId\"]:hidden").val(revId);
							primoCityRevId = false;
						}
						
						//Aggiorno il campo nascosto (TOP) con il primo della lista -- caso country review
						if ( reviewType == 3 && primoCountryRevId )	{
							$("input[id=\"topCountryReviewId\"]:hidden").val(revId);
							primoCountryRevId = false;
						}
						
						//Aggiorno il campo nascosto (BOTTOM) con l'ultimo della lista 
						//(setto sempre quello corrente alla fine avro' l'ultimo)
						//if ( i == arr.length - 1 ) $("input[id=\"bottomReviewId\"]:hidden").val(revId);
						if ( reviewType == 1 ) $("input[id=\"bottomReviewId\"]:hidden").val(revId);
						
						if ( reviewType == 2 ) $("input[id=\"bottomCityReviewId\"]:hidden").val(revId);
						
						if ( reviewType == 3 ) $("input[id=\"bottomCountryReviewId\"]:hidden").val(revId);
					}
				} else {
					//Altrimenti (modalita' APPEND)
					$('#reviewItemBoxId').append(revItemBoxDiv);

					//Aggiorno il campo nascosto (BOTTOM) con l'ultimo della lista
					//(setto sempre quello corrente alla fine avro' l'ultimo)
					//if ( i == arr.length - 1 ) $("input[id=\"bottomReviewId\"]:hidden").val(revId);
					if ( reviewType == 1 ) $("input[id=\"bottomReviewId\"]:hidden").val(revId);
					
					if ( reviewType == 2 ) $("input[id=\"bottomCityReviewId\"]:hidden").val(revId);
					
					if ( reviewType == 3 ) $("input[id=\"bottomCountryReviewId\"]:hidden").val(revId);
				}
			}//for
			$("input[id=\"exec\"]:hidden").val('1');
		},
		error: function(){
			//no action
		}
	});
}


/* ############################################################################
   ############## MANAGE SESSION AN RELOAD REVIEW FIELDS  ##################### */

/**
 * Cancella un elemento specifico della CommonReviewBean.
 */
function delFromSess(name, type, index){
	$.ajax({
		type: "POST",
		url: "/viantes/pvt/pages/review/common/delFromSession.php",
		data: {name: name, type: type, index: index},
		dataType: "html",
		success: function(response){
			//alert(response);
			reloadReviewPage('');
		},
		error: function(){
			//no action
		}
	});
}

/**
 * Legge i parametri attuali, fa la encode di alcuni di essi 
 * (quelli che non sono gia' encodati) e ricarica la pagina delle recensioni.
 */
function reloadReviewPage(anchor) {
	var arr = window.location.href.split('?');
	var tabActive = "?tabactive=" + $('#tabactive').val();
	
	//Fix problem - in some case the last char is # and not work
	var urlBaseLastChar = arr[0].substring(arr[0].length -1, arr[0].length);
	if (urlBaseLastChar == '#') {
		arr[0] = arr[0].substring(0, arr[0].length -1);
	}
	var url =  arr[0] + tabActive + "&catRev=" + $('#catRev').val() + "&country=" + $('#country').val() + "&vote=" + $('#vote').val()
					+ "&locality=" + encodeURIComponent($('#locality').val()) 
					+ "&city="     + encodeURIComponent($('#city').val())
					+ "&site="     + encodeURIComponent($('#site').val()) 
					+ "&descr="    + encodeURIComponent($('#descr').val())
					+ "&arrive="   + encodeURIComponent($('#arrive').val()) 
					+ "&warn="     + encodeURIComponent($('#warn').val())
					+ "&whEat="    + encodeURIComponent($('#whEat').val()) 
					+ "&cook="     + encodeURIComponent($('#cook').val()) 
					+ "&whStay="   + encodeURIComponent($('#whStay').val()) 
					+ "&myth="     + encodeURIComponent($('#myth').val())
					+ ( $('#catRevDIV').find('p').text() != '' ? "&catRevErrMsg=" + $('#catRevDIV').find('p').text() : '')
					+ ( $('#cityDIV').find('p').text() != '' ? "&cityErrMsg=" + $('#cityDIV').find('p').text() : '')
					+ ( $('#countryDIV').find('p').text() != '' ? "&countryErrMsg=" + $('#countryDIV').find('p').text() : '')
					+ ( $('#localityDIV').find('p').text() != '' ? "&localityErrMsg=" + $('#localityDIV').find('p').text() : '')
					+ ( $('#siteDIV').find('p').text() != '' ? "&siteErrMsg=" + $('#siteDIV').find('p').text() : '')
					+ ( $('#descrDIV').find('p').text() != '' ? "&descrErrMsg=" + $('#descrDIV').find('p').text() : '')
					+ ( $('#arriveDIV').find('p').text() != '' ? "&arriveErrMsg=" + $('#arriveDIV').find('p').text() : '')
					+ ( $('#warnDIV').find('p').text() != '' ? "&warnErrMsg=" + $('#warnDIV').find('p').text() : '')
					+ ( $('#cookDIV').find('p').text() != '' ? "&cookErrMsg=" + $('#cookDIV').find('p').text() : '')
					+ ( $('#whStayDIV').find('p').text() != '' ? "&whStayErrMsg=" + $('#whStayDIV').find('p').text() : '')
					+ ( $('#whEatDIV').find('p').text() != '' ? "&whEatErrMsg=" + $('#whEatDIV').find('p').text() : '')
					+ ( $('#mythDIV').find('p').text() != '' ? "&mythErrMsg=" + $('#mythDIV').find('p').text() : '');
	
	if ( anchor != '') url += '#' + anchor;	

	window.location.href = url;
	//window.location.reload();
}

		
/**
 * Avvia/Ferma l'esecuzione di un video. Modifica l'immagina da visualizare 
 * e cambia i testi dei tooltip da mostrare
 */
function mngMov(id){
	if ( $('#crtReviewMov' + id).attr('play') == 'false' && $('#videoStartStopForIE_' +id).val() == 'false' ) {
		//setto lo stato di play a true"
		$('#crtReviewMov' + id).attr('play', 'true');
		$('#videoStartStopForIE_'+id).val('true');

		//mostra pause e nascondo play
		$('#playMov' + id).attr("src", "/viantes/pvt/img/review/pause_32.png");

		document.getElementById('crtReviewMov' + id).Play();
		return false;
	}
	$('#crtReviewMov' + id).attr('play', 'false');
	$('#videoStartStopForIE_'+id).val('false');

	document.getElementById('crtReviewMov' + id).StopPlay();
	$('#playMov' + id).attr("src", "/viantes/pvt/img/review/play_32.png");
	return false;
}
/**
 * Avvia/Ferma l'esecuzione di un video. Modifica l'immagina da visualizare 
 * Specifico per il Viewer
 */
function mngMov4Viewer(){
	if ( $('#currentMov').attr('play') == 'false' && $('#videoStartStopForIE').val() == 'false' ) {
		
		//setto lo stato di play a true"
		$('#currentMov').attr('play', 'true');
		$('#videoStartStopForIE').val('true');
		
		//mostra pause e nascondo play
		$('#viewerPlayId').attr("src", "/viantes/pvt/img/review/pause_32.png"); //ie

		document.getElementById('currentMov').Play();
		return false;
	}
	
	//setto lo stato di play a false"
	$('#currentMov').attr('play', 'false');
	$('#videoStartStopForIE').val('false');

	//mostra play e nascondo pause
	$('#viewerPlayId').attr("src", "/viantes/pvt/img/review/play_32.png"); //ie
	document.getElementById('currentMov').StopPlay();
	return false;
}

/**
 * Imposta tutti i campi valorizzati dall'utente piu' gli eventuali 
 * messaggi di errore in modo da riaversi nella pagina dopo l'upload 
 * o la cancellazione di un file
 */
function setFieldsAndErrMsgReviewPage(tab){
	$('#catRevHidden_' + tab).val($('#catRev').val()); 
	$('#cityHidden_' + tab).val($('#city').val());
	$('#countryHidden_' + tab).val($('#country').val()); 
	$('#localityHidden_' + tab).val($('#locality').val()); 
	$('#siteHidden_' + tab).val($('#site').val());
	$('#descrHidden_' + tab).val($('#descr').val()); 
	$('#arriveHidden_' + tab).val($('#arrive').val());
	$('#warnHidden_' + tab).val($('#warn').val());
	$('#whEatHidden_' + tab).val($('#whEat').val());
	$('#cookHidden_' + tab).val($('#cook').val());
	$('#whStayHidden_' + tab).val($('#whStay').val());
	$('#mythHidden_' + tab).val($('#myth').val());
	$('#voteHidden_' + tab).val($('#vote').val());
	
	$('#cityErrMsgHidden_' + tab).val($('#cityDIV').find('p').text());
	$('#catRevErrMsgHidden_' + tab).val($('#catRevDIV').find('p').text());
	$('#countryErrMsgHidden_' + tab).val($('#countryDIV').find('p').text());
	$('#localityErrMsgHidden_' + tab).val($('#localityDIV').find('p').text());
	$('#siteErrMsgHidden_' + tab).val($('#siteDIV').find('p').text());
	$('#descrErrMsgHidden_' + tab).val($('#descrDIV').find('p').text()); 
	$('#arriveErrMsgHidden_' + tab).val($('#arriveDIV').find('p').text());
	$('#warnErrMsgHidden_' + tab).val($('#warnDIV').find('p').text());
	$('#whEatErrMsgHidden_' + tab).val($('#whEatDIV').find('p').text());
	$('#cookErrMsgHidden_' + tab).val($('#cookDIV').find('p').text());
	$('#whStayErrMsgHidden_' + tab).val($('#whStayDIV').find('p').text());
	$('#mythErrMsgHidden_' + tab).val($('#mythDIV').find('p').text());
}


/**
 * Azzera la text area se il suo valore e' uguale a initVal
 * Metodo da chiamare nell'onfocus della textArea
 */
function resetTellUsTextArea(id, initVal) {
	if ($('#comment' + id).val() == initVal)  {
		$('#comment' + id).val(''); 
		$('#comment' + id).css('color', '#454545');
	}
}

/**
 * Ripristina la text area, se il suo valore e' vuoto, con il valore initVal.
 * Se valorizzata crea un campo nascosto
 * Metodo da chiamare nell'onBlur della textArea
 */
function restoreTellUsTextArea(name, id, initVal) {
	//invio il testo inserito (anche se vuoto)
	val = $('#comment' + id).val();
	$.ajax({
		type: "GET", 
		url: "/viantes/pvt/pages/review/create/saveCommentAsy.php",
		data: {name: name, id: id, val: val},
		contentType: "html",
		success: function(response){
			//alert(response);
		}
	});
	
	if ($('#comment' + id).val() == '' )  {
		$('#comment' + id).val(initVal); 
		$('#comment' + id).css('color', '#aaaaaa');
	}
}


/* ########################################################
   ###################### TAB EVENT ####################### */

/**
 * Gestisce i link dei tab nascondendo e visualizzando i tab corretti
 */
jQuery(document).ready(function() {
	jQuery('.tabs .tab-links a').on('click', function(e)  {
		var currentAttrValue = jQuery(this).attr('href');
		// Show/Hide Tabs
		//OPZ 1 
		jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
		// OPZ 2 jQuery('.tabs ' + currentAttrValue).slideDown(400).siblings().slideUp(400);
		// OPZ 3 jQuery('.tabs ' + currentAttrValue).siblings().slideUp(400);
		//jQuery('.tabs ' + currentAttrValue).delay(400).slideDown(400);
		
		// Change/remove current tab to active
		jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
		
		e.preventDefault();
	});
});	

/* disable right-clicking in PROD *
$(function () { $(this).bind("contextmenu", function (e) { e.preventDefault(); }); });*/


/* #######################################################
   ###################### VOTE ########################### */

/* Mostra il voto per un solo pallino *
function showVote1() {
	$('#0_1').hide(); $('#1_1').show(); $('#0_2').show(); $('#1_2').hide(); $('#0_3').show(); 
	$('#1_3').hide(); $('#0_4').show(); $('#1_4').hide(); $('#0_5').show(); $('#1_5').hide();
}*/
/* Setta il voto per un solo pallino */
function setVote1() {
	$('#0_1').hide(); $('#1_1').show(); $('#0_2').show(); $('#1_2').hide(); $('#0_3').show(); 
	$('#1_3').hide(); $('#0_4').show(); $('#1_4').hide(); $('#0_5').show(); $('#1_5').hide();
	$('#vote').val('1');
}

/* Mostra il voto per due pallini *
function showVote2() {
	$('#0_1').hide(); $('#1_1').show(); $('#0_2').hide(); $('#1_2').show(); $('#0_3').show(); 
	$('#1_3').hide(); $('#0_4').show(); $('#1_4').hide(); $('#0_5').show(); $('#1_5').hide();
}
/* Setta il voto per due pallini */
function setVote2() {
	$('#0_1').hide(); $('#1_1').show(); $('#0_2').hide(); $('#1_2').show(); $('#0_3').show(); 
	$('#1_3').hide(); $('#0_4').show(); $('#1_4').hide(); $('#0_5').show(); $('#1_5').hide();
	$('#vote').val('2');
}

/* Mostra il voto per tre pallini *
function showVote3() {
	$('#0_1').hide(); $('#1_1').show(); $('#0_2').hide(); $('#1_2').show(); $('#0_3').hide(); 
	$('#1_3').show(); $('#0_4').show(); $('#1_4').hide(); $('#0_5').show(); $('#1_5').hide();
}
/* Setta il voto per tre pallini */
function setVote3() {
	$('#0_1').hide(); $('#1_1').show(); $('#0_2').hide(); $('#1_2').show(); $('#0_3').hide(); 
	$('#1_3').show(); $('#0_4').show(); $('#1_4').hide(); $('#0_5').show(); $('#1_5').hide();
	$('#vote').val('3');
}

/* Mostra il voto per quattro pallini *
function showVote4() {
	$('#0_1').hide(); $('#1_1').show(); $('#0_2').hide(); $('#1_2').show(); $('#0_3').hide(); 
	$('#1_3').show(); $('#0_4').hide(); $('#1_4').show(); $('#0_5').show(); $('#1_5').hide();
}
/* Setta il voto per quattro pallini */
function setVote4() {
	$('#0_1').hide(); $('#1_1').show(); $('#0_2').hide(); $('#1_2').show(); $('#0_3').hide(); 
	$('#1_3').show(); $('#0_4').hide(); $('#1_4').show(); $('#0_5').show(); $('#1_5').hide();
	$('#vote').val('4');
}

/* Mostra il voto per cinque pallini *
function showVote5() {
	$('#0_1').hide(); $('#1_1').show(); $('#0_2').hide(); $('#1_2').show(); $('#0_3').hide(); 
	$('#1_3').show(); $('#0_4').hide(); $('#1_4').show(); $('#0_5').hide(); $('#1_5').show();
}
/* Setta il voto per cinque pallini */
function setVote5() {
	$('#0_1').hide(); $('#1_1').show(); $('#0_2').hide(); $('#1_2').show(); $('#0_3').hide(); 
	$('#1_3').show(); $('#0_4').hide(); $('#1_4').show(); $('#0_5').hide(); $('#1_5').show();
	$('#vote').val('5');
}

/*
function showCurrent(){
	if ($('#vote').val() == 1) showVote1();
	if ($('#vote').val() == 2) showVote2();
	if ($('#vote').val() == 3) showVote3();
	if ($('#vote').val() == 4) showVote4();
	if ($('#vote').val() == 5) showVote5();
}*/


/* #######################################################
   #################### STAR & SEE ####################### */

/**
 * Segnala (o annulla) una recensione come 'star' o come 'see'
 * @mode  = se do => segnala se undo => annulla segnalazione
 * @revId = id della recensione
 * @revTp = tipo di recensione
 * @star  = 1 per star, 2 per see
 */
function doStar(mode, revId, revTp, star){
	dataString = "mode="+mode+"&reviewId="+revId+"&revType="+revTp+"&star="+star;
	$.ajax({
		type: "GET",
		data: dataString,
		url : "/viantes/pvt/pages/review/common/setStarAsy.php",
		dataType: "html",
		success: function(response){
			//alert(response);
		},
		error: function(){
			//no action
		}
	});
}
/**
 * Salva un post 
 * @revId = id della recensione
 * @revTp = tipo di recensione
 */
function doPost(revId, revTp, justPost){
	//leggo il contenuto del post e se nullo o troppo lungo lo script termina
	var post = $('#postTxtArea').val();
	if (post == null || post == '') return;
	if (post.length > 140) return;

	//se e' il primo post incremento e cambio colore
	if (!justPost)	{
		cangePostVal('+1'); 
		$('#userPostYes').attr("src", "/viantes/pvt/img/review/post.png");
	}
	
	$('#postDivBox').hide();
	dataString = "reviewId="+revId+"&revType="+revTp+"&post="+post;
	$.ajax({
		type: "GET",
		data: dataString,
		url : "/viantes/pvt/pages/review/common/setPostAsy.php",
		dataType: "html",
		success: function(response){
			//alert(response);
		},
		error: function(){
			//no action
		}
	});
}
/* Aggiorna, a seguito di un click sulla 'star', il contatore delle 'star' */
function cangeStarVal(addVal){
	var val = $('#starDivId').find("p").text();
	
	if (val == '999+') return;
	
	if (val == '999') sum = "999+";
	else  			  sum = parseInt(val) + parseInt(addVal);
		
	$('#starDivId').find("p").text(sum);
}
/* Aggiorna, a seguito di un click sul 'see', il contatore dei 'see' */
function cangeSeeVal(addVal){
	var val = $('#seeDivId').find("p").text();
	
	if (val == '999+') return;
	
	if (val == '999') sum = "999+";
	else  			  sum = parseInt(val) + parseInt(addVal);
	
	$('#seeDivId').find("p").text(sum);
}
/* Aggiorna, a seguito di un click sul 'see', il contatore dei 'see' */
function cangePostVal(addVal){
	var val = $('#postDivId').find("p").text();
	
	if (val == '999+') return;
	
	if (val == '999') sum = "999+";
	else  			  sum = parseInt(val) + parseInt(addVal);
	
	$('#postDivId').find("p").text(sum);
}

/**
 * Renderizza la popup che mostra tutti gli str/see/post
 */
function renderSSP(revId, revTp){
	dataString = "revId=" + revId + "&revTp=" + revTp;
	$.ajax({
		type: "GET",
		url: "/viantes/pvt/pages/review/common/overlayStarPostListAsy.php",
		data: dataString,
		contentType: "json",
		success: function(resp){
			
			//cancello il vecchio contenuto
			$('#sspContentDiv').remove();
			
			//count number of element
			var cnt = 0;
			
			//div esterno
			var content = "<div id=\"sspContentDiv\" class=\"sspContentDiv\">";
			
			$.map(resp, function (item) {
				
				var link = "/viantes/pub/pages/profile/showProfile.php?usrId=" + item.usrId;
				
				if (item.star == 1) {
					content = content +
					"<div class=\"sspMainDiv\">"+
						"<div class=\"sspRowCol1 dspl-inln-blk\">"+
							"<a class=\"dspl-inln-blk\" href=\"" + link + "\">" +
								"<img class=\"msgCoverBodyCell\" width=\"25\" height=\"25\" src=\"" + item.usrCover + "\" />" + item.author +
							"</a>"+
						"</div>"+
						"&nbsp; ha cliccato su <img src=\"/viantes/pvt/img/review/star.png\" width=\"16\"/>"+
					"</div>";
				}
				if (item.see == 1) {
					content = content +
					"<div class=\"sspMainDiv\">"+
						"<div class=\"sspRowCol1 dspl-inln-blk\">"+
							"<a class=\"dspl-inln-blk\" href=\"" + link + "\">" +
								"<img class=\"msgCoverBodyCell\" width=\"25\" height=\"25\" src=\"" + item.usrCover + "\" />" + item.author +
							"</a>"+
						"</div>"+
						"&nbsp; ha cliccato su <img src=\"/viantes/pvt/img/review/see.png\" width=\"16\"/>"+
					"</div>";
				}
				if (item.post != '') {
					content = content +
					"<div class=\"sspMainDiv\">"+
						"<div class=\"sspRowCol1 dspl-inln-blk\">"+
							"<a class=\"dspl-inln-blk\" href=\"" + link + "\">" +
								"<img class=\"msgCoverBodyCell\" width=\"25\" height=\"25\" src=\"" + item.usrCover + "\" />" + item.author +
							"</a>"+
						"</div>" +
						"&nbsp; ha commentato:" +
						"<div class=\"sspRowCol2\">" +
							"" + item.post +
						"</div>" +
					"</div>";
				}
				
				//Potrei avere elementi see = 0 o dei post = ''
				if (item.star == 1 || item.see == 1 || item.post != '')
					cnt++;
			});
			
			//chiudo il div esterno
			content = content + "</div>";
			
			if (cnt > 0) {
				//inserisco dopo il tag sspTopDiv
				$('#sspTopDiv').after(content);
			} else {
				//mostro il emssaggio di lista vuota
				$('#sspEmptyContentDiv').show();
			}			
		
			//mostro
			$('#overlaySSPList').show();
		},
		error: function(){
			//no action
		}
	});
}
/* ###########################################################
   #################### COMMON GENERIC ####################### */

 /* Ritorna true se nella barra degli indirizzi e' presente la stringa 
  * /m/viantes => quasi certamente 99.99% e' un mobile */
 function isMobile(){
	var arr = window.location.href.split('?');
	return arr[0].indexOf('/m/viantes') > -1;
}
/* Verifica la selezione degli interest, prepara i parametri 
 * ed una chiamata asincrono verso saveInterest.php */
function callSaveInterestAsy(name) {
	$('#zeroInterestErr').hide(); $('#maxInterestErr').hide();
	var i = 0;
	var interestList = '';
	$('input[type=checkbox]').each(function () {
		if (this.checked) {
			i++;
			var id = this.value;
			var src = $('#interestPath' + id).attr('src');
			var txt = $('#interestSiteName' + id).text();
			interestList += (i == 1 ? '': listDelimiter) + id + elementDelimiter + src + elementDelimiter + txt;
		}
	});
	
	if ( i == 0 ){
		$('#zeroInterestErr').show();
		return;
	}

	if ( i > 50 ){
		$('#maxInterestErr').show();
		return;
	}
	
	$('#overlayAddInterestTop').hide();
	$('#overlayAddInterestLoading').show();
	$.ajax({ 
		type: 'POST', url: '/viantes/pvt/pages/review/create/saveInterest.php',
		data: {name: name, interestList: interestList},
		dataType: 'html',
		success: function(response){
			//letme test
			//alert(response); 
			$('#overlayAddInterestLoading').hide();
			$('#overlayAddInterest').hide();
			$('#overlayAddInterestTop').show();
			
			//reloadReviewPage('createRevSection2');
			reloadReviewPage('');
		}
	});
}

/* Mostra una finestra di conferma se e' stato selezionato 
 * almeno un messaggio da cancellare */
function confirmDelMsg(tabNum) {
	var objId = 'input[name=delMsgTab'+ tabNum + ']';
	var find = false;
	$(objId).each(function () {
		if (this.checked) {
			find = true;
			//break; can not be used :(
		}
	});
	if (find){
		// a seconda che sia mobile o no faccio cose diverse
		if (isMobile()) {
			showDelMsg();
		}
		else{
			$('#overlay-del-msg').show();
			$('#menu-status').val(4); 
		}
		return;
	}else{
		// a seconda che sia mobile o no faccio cose diverse
		if (isMobile()) {
			showDelNoItemMsg();
		}
		else {
			$('#overlay-del-msg-no-sel').show();
		}
		return;
	}
}

/** Aggiorna il numero di messaggi da leggere dell'utente loggato */
function reloadUsrMsg(){
	$.ajax({
		type: "GET", url: "/viantes/pvt/pages/msg/reloadUsrMsgAsy.php", dataType: "html",
		success: function(response){
			//alert(response);
		},
		error: function(){
			//no action
		}
	});;
}
/** Refresh captcha image (code) and reset input value */
function refreshCaptcha(reqType){
	$('#captcha_img').attr('src', '/viantes/pvt/pages/captcha/captcha.php?reqType=' + reqType + '&' + Math.random());
	$('#captcha_code').val(''); 
	return false;
}


/* #####################################################
   #################### MESSAGGI ####################### */
   
/* Cancella o ripristina (se sono nel tab4) i messaggi selezionati del tab corrente */
function delOrRestoreMsg() {

	//nascondo la finestra di conferma cancellazione
	if (isMobile()) {
		$('#mob-lod-img').show();
		$('#mob-del-msg').hide();
	}
	//nascondo l'overlay di conferma cancellazione
	else{
		$('#overlay-loadImg').show();
		$('#overlay-del-msg').hide();
	}

	var tabactive = $('#tabactive').val();
	var isRrestore = $('#isRrestore').val(); 
	var mode = ( tabactive == '4' && isRrestore ==  '1') ? '5' : tabactive;
	
	//Ciclo sull'oggetto check box e prendo solo euqlli checked
	var objId = 'input[name=delMsgTab'+ tabactive + ']';
	var msgList = '';
	$(objId).each(function () {
		if (this.checked) {
			var id = this.value;
			msgList +=  ( msgList == '' ) ? id : ';' + id;
		}
	});
	
	//se non trovo nulla cerco un eventuale campo hidden (vuol dire che vengo dalla pagina showMsg.php)
	if (msgList == '')	msgList = $('#delMsgShoMsg').val();
	
	$.ajax({
		type: "GET",
		url: "/viantes/pvt/pages/msg/delOrRestoreMsg.php",
		data: "msgList=" + msgList + "&mode=" + mode,
		dataType: "html",
		success: function(response){
			if (isMobile()) {				
				window.location.href = window.location.origin + "/m/viantes/pub/pages/profile/message.php?tabactive=" + tabactive;
				$('#mob-lod-img').hide();
			}	
			else{				
				window.location.href = window.location.origin + "/viantes/pub/pages/profile/message.php?tabactive=" + tabactive;
				$('#overlay-loadImg').hide();
			}
		},
		error: function(){
			//no action
		}
	});
}
/* Chiude l'overlay del messaggio */
function closeOverlayMsg () {
	$('#overlay-msg-new').hide();
	
	//divido l'url in 2 parti
	var arr = window.location.href.split('?');
	
	var queryStr = "";
	
	//cerco nella parte di destra (se esiste, arr.length > 1 ) i parametri che mi interessano 
	if ( arr.length > 1) {
		var paramArray = arr[1].split('&');	
		for (var i = 0; i < paramArray.length; i++) {
			//il parametro usrId=xxx devo rimetterlo nell'url
			if (paramArray[i].indexOf('usrId=') != -1)
				queryStr += "&" + paramArray[i];
			//il parametro msgId=xxx devo rimetterlo nell'url
			if (paramArray[i].indexOf('msgId=') != -1)
				queryStr += "&" + paramArray[i];
		}
	}
	
	//il tab active puo' non esserci (e' presente solo nei messaggi)
	if ($('#tabactive').length) {
		var url  = arr[0] + "?tabactive=" + $('#tabactive').val() + queryStr;
	} else {
		var url  = arr[0] + "?1=1" + queryStr;
	}
	
	//Ricarico la pagina 
	url = url.replace("#", "");
	window.location.href = url;
}