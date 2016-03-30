/* 
 * Muove la parte di immagine selezionata di uno shift verso destra 
 * sempre che questo non superi il limite superiore destro maxX1
*/
function toRight(shift, maxX1){
	var x1 = parseInt( $("#x1").val() );
	var delta = x1 + shift <= maxX1 ? shift : maxX1 - x1;
	moveOriz(delta);
};
/* 
 * Muove la parte di immagine selezionata di uno shift verso l'alto 
 * sempre che questo non sia inferiore al limite inferiore minY1
*/
function toTop(shift, minY1){
	var y1 = parseInt( $("#y1").val() );
	var delta = y1 - shift >= minY1 ? shift : y1 - minY1;
	//alert("shift " + shift + " minY1 " + minY1 + " y1 " + y1);
	moveVert(-delta);
};
/* 
 * Muove la parte di immagine selezionata di uno shift verso sinistra
 * sempre che questo non sia inferiore al limite sinistro minX1
*/
function toLeft(shift, minX1){
	var x1 = parseInt( $("#x1").val() );
	var delta = x1 - shift >= minX1 ? shift : x1 - minX1;
	moveOriz(-delta);
};
/* 
 * Muove la parte di immagine selezionata di uno shift verso il basso 
 * sempre che questo non superi il limite superiore maxY1
*/
function toDown(shift, maxY1){
	var y1 = parseInt( $("#y1").val() );
	var delta = y1 + shift <= maxY1 ? shift : maxY1 - y1;
	moveVert(delta);
};
/* Performa un generico movimento lungo l'asse Y */
function moveOriz(delta){
	//current value (es 8px, 36px or 756px)
	var _x0 = $("#divToBeMoved").css('left');
	var numCifr = _x0.length;

	//current value (es 8, 36 or 756)
	var x0 = parseInt( _x0.substring(0, numCifr-2) );
	x0+=delta;
	$("#x0").val(x0);
	
	$('#divToBeMoved').css('left', x0 + 'px');
	$('#imgToBeMoved').css('left', '-'+ x0 + 'px');

	var x1 = parseInt( $("#x1").val() );
	x1+=delta;
	$("#x1").val(x1);
};
function moveVert(delta){
	//current value (es 8px, 36px or 756px)
	var _y0 = $("#divToBeMoved").css('top');
	var numCifr = _y0.length;

	//current value (es 8, 36 or 756)
	var y0 = parseInt( _y0.substring(0, numCifr-2) );
	y0 += delta;
	$("#y0").val(y0);
	
	$('#divToBeMoved').css('top', y0 + 'px');
	$('#imgToBeMoved').css('top', '-'+ y0 + 'px');

	var y1 = parseInt( $("#y1").val() );
	y1 += delta;
	$("#y1").val(y1);
};
