/**
 * AJAX request for data table.
 */
function figAjaxLoadTableData( reFetchData = 0 ) {

	var xhr = new XMLHttpRequest();
	var ajaxURL = figAjaxUrl.ajax_url;
	var figColIDs = document.getElementById( 'fig-table-autoloader' ).getAttribute( 'data-ids' );
	var figContext = document.getElementById( 'fig-table-autoloader' ).getAttribute( 'data-context' );
	var figParams = 'ids=' + figColIDs + '&context=' + figContext + '&fetch=' + reFetchData;
	
	xhr.open( 'POST', ajaxURL + '?action=fig_process_ajax_request', true );
	
	xhr.onreadystatechange = function() {
		
		if ( xhr.readyState === 4 ) {
			
			document.getElementById( 'fig-table-autoloader' ).innerHTML = xhr.responseText;
			
		}
		
	};
	
	xhr.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded' );
	
	xhr.send( figParams );

}

/**
 * AJAX force refresh API data.
 */
function figAjaxForceReloadData() {

	var xhr = new XMLHttpRequest();
	var ajaxURL = figAjaxUrl.ajax_url;
	var figParams = 'force=true';
	
	xhr.open( 'POST', ajaxURL + '?action=fig_process_miusage_api_check', true );
	
	xhr.onload = function (e) {
		
		if ( xhr.readyState === 4 ) {
			
			document.getElementById( 'fig-next-check-datetime' ).innerHTML = xhr.responseText;
			
		}
		
	}
	
	xhr.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded' );
	
	xhr.send( figParams );

}

window.onload = function() {
	
	if ( document.getElementById( 'fig-table-autoloader' ) ) {
		
		/**
		 * AJAX load the data on page load.
		 */
		figAjaxLoadTableData();
		
	}
	
	if ( document.getElementById( 'fig-refresh-admin-table-data' ) ) {
		
		/**
		 * AJAX refresh the data in admin on button click.
		 */
		document.getElementById( 'fig-refresh-admin-table-data' ).onclick = function() {
			
			document.getElementById( 'fig-table-autoloader' ).innerHTML = '<p class="loading">Data is loading . . .</p>';
			
			figAjaxForceReloadData();
			
			figAjaxLoadTableData( true );
			
		}
		
	}
	
}