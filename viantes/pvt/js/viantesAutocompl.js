/**
 * Autocompletamento che carica le nazioni in base alla lingua
 * url: "/viantes/pvt/pages/lang/getJesonCountry.php",
 */	
$(function() {
	
    /** 
	 * Autocomplete per la ricerca delle recensioni nell'header
	 */
	if ( $('#autoComplHeaderSearchRev').length != 0 ) { // nel viewer non esiste questo tagId 
		$('#autoComplHeaderSearchRev').autocomplete({
			source: function (request, response) {
				dataString = "k=" + request.term + "&langCode=";
				$.ajax({
					type: "GET",
					url: "/viantes/pvt/pages/review/search/searchRev4AutocomplAsy.php",
					data: dataString,
					contentType: "json",
					success: function(resp){
						response($.map(resp, function (item) {
							return { label: item.type, value: item.name.split(elementDelimiter)[0]};
						}));
					}
				});
			},
			select:function (event, ui) {
				event.preventDefault();
				var arr = ui.item.value.split(elementDelimiter);
				$('#kwrds').val(arr[0]);
				$('#type').val(ui.item.value);
				$('#autoComplHeaderSearchRev').val(arr[0]);
			}
		})
		.data("ui-autocomplete")._renderItem = function (ul, item) {
			var arr = item.value.split(elementDelimiter);

			//formatto il risultato dell'autocomplete 
			var myTag = '<div class="autocompHeaderSearchItem1" style="background-image: url(\'/viantes/pvt/img/common/autocomplIco' + item.label + '.png\');"></div>' +
						'<div class="autocompHeaderSearchItem2">' +
							'<div style="left: 44px; top: 8px; color: #fa0; font-weight: bold;">' + arr[0] + '</div>'+
							'<div style="left: 44px; top: 23px; color: #555; font-size:11px;">' + arr[1] + '</div>' +
						'</div>';
			
			return $("<li></li>").data("ui-autocomplete", item).append(myTag).appendTo(ul);
		 };
	 } // if 
	 
	 
    /** 
	 * Autocomplete per la ricerca dei destinatari di un messaggio 
	 */
	 if ( $('#autoComplMsgTo').length != 0 ) { // se esiste il tag con id="autoComplMsgTo" 
		 $('#autoComplMsgTo').autocomplete({
			source: function (request, response) {
				dataString = "usrName=" + request.term;
				$.ajax({
					type: "GET",
					url: "/viantes/pvt/pages/auth/searchUsrDest4Autocompl.php",
					data: dataString,
					contentType: "json",
					success: function(resp){
						response($.map(resp, function (item) {
							return { label: item.cover, value: item.name};
						}));
					}
				});
			},
			select:function (event, ui) {
				event.preventDefault();
				$('#autoComplMsgTo').val(ui.item.value);
			}
		})
		.data("ui-autocomplete")._renderItem = function (ul, item) {
			console.log(item);
			var arr = item.label.split(elementDelimiter);

			//formatto il risultato dell'autocomplete 
			var myTag = '<img style="position: absolute; border-radius: 12px; " src="' + item.label + '" height="25" width="25">' +
						'<div class="autocompMsgToItem1">' + item.value + '</div>';
			
			return $("<li></li>").data("ui-autocomplete", item).append(myTag).appendTo(ul);
		 };
	 } // if 
	 
});
