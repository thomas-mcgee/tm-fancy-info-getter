/**
 * Enable auto-copy when clicking the shortcode input field.
 */
if ( document.getElementById('fig-shortcode') ) {
	
	document.getElementById('fig-shortcode').onclick = function() {
		
		this.select();
		
		document.execCommand('copy');
		
	}
	
}

function toggleFigShortcodeCols() {
	
	var cols = [];
	
	const selectedCols = document.querySelectorAll( '.fig-shortcode-col-option' );
	
	/**
	 * Loop through checkboxes
	 */
	for ( var i=0; i < selectedCols.length; i++ ) {
		
		/**
		 * If checked, add it to the shortcode.
		 */
		if ( selectedCols[i].checked ) {
			
			var itemID = selectedCols[i].getAttribute( 'data-id' );
			
			cols.push( itemID );
			
		}
	
	}
	
	/**
	 * Rewrite the shortcode and apply it to the input field.
	 */
	document.getElementById( 'fig-shortcode' ).value = '[fancy_info columns="' + cols + '"]';
	
}
