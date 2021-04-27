<?php defined( 'ABSPATH' ) or die( 'nope' );

function fig_refresh_miusage_api() {
	
	$api = new FIGApiFetch();
	
	$api->setUrl( 'https://miusage.com/v1/challenge/1/' );
	
	$api->setOption( 'miusage' );
	
	$success = $api->saveApiData();
	
	if ( $success ) {
		
		WP_CLI::success( 'Updated API data successfully.' );
		
	} else {
		
		WP_CLI::error( 'Unable to retrieve data. Please try again later.' );
		
	}
	
}

if ( defined( 'WP_CLI' ) && WP_CLI ) :
	
	WP_CLI::add_command( 'fig-refresh-miusage-api', 'fig_refresh_miusage_api' );
	
endif;