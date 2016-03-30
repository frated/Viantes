$(document).on('click', function(evt) {

	//ottengo il margine sinistro dell'elemento header-user-text-id
	var offsetUserTxt = $('#header-user-text-id').offset();
	if( !(typeof offsetUserTxt === 'undefined') && !(typeof offsetUserTxt === 'null') ) {
		var marginLeft = offsetUserTxt.left;
		//lo assegno alla freccia in su
		$('#freccia-menu-user-id').css('margin-left', marginLeft + 'px');
		
		//lo assegno anche al box del menu (scalato di 20px)
		$('#userMenuId').css('margin-left', (marginLeft-20) + 'px');
	}
	//ottengo il margine sinistro dell'elemento header-review-text-id
	var offsetReviewTxt = $('#header-review-text-id').offset();
	if( !(typeof offsetReviewTxt === 'undefined') && !(typeof offsetReviewTxt === 'null') ) {
		var marginLeft = offsetReviewTxt.left;
		//lo assegno alla freccia in su
		$('#freccia-menu-review-id').css('margin-left', marginLeft + 'px');
		
		//lo assegno anche al box del menu (scalato di 20px)
		$('#reviewMenuId').css('margin-left', (marginLeft-20) + 'px');
	}

    if ( $('#ONOFFSTATUS').attr('value') == 'ON' ){
		$('#ONOFFSTATUS').attr('value', 'OFF');
		return;
	}
	
	userMenu(0);
	reviewMenu(0);
});

function userMenuOnOff(){
	if ( $('#userMenuId').is(':visible') ) {
		userMenu(0);
		return; 
	}
	reviewMenu(0);
	userMenu(1);
	$('#ONOFFSTATUS').attr('value', 'ON');	
}

function reviewMenuOnOff(){
	if ( $('#reviewMenuId').is(':visible') ) {
		reviewMenu(0);
		return; 
	}
	userMenu(0);
	reviewMenu(1);	
	$('#ONOFFSTATUS').attr('value', 'ON');
}

function userMenu(on){
	if (on == 1){
		$('#userMenuId').show(); 
		$('#freccia-menu-user-id').show(); 
	}
	else{
		$('#userMenuId').hide(); 
		$('#freccia-menu-user-id').hide(); 
	}
}
function reviewMenu(on){
	if (on == 1){
		$('#reviewMenuId').show(); 
		$('#freccia-menu-review-id').show(); 
	}
	else{
		$('#reviewMenuId').hide(); 
		$('#freccia-menu-review-id').hide(); 
	}
}

function selectElement(elem){
	$('#'+elem).css('font-weight','bold');
}
function unselectElement(elem){
	$('#'+elem).css('font-weight','normal');
}
