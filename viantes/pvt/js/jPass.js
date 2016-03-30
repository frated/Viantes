jQuery(document).ready(function() {
	var myInput = document.getElementById('pwd2');
	myInput.onpaste = function(e) {
		e.preventDefault();
	}
});