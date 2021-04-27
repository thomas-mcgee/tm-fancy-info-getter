<?php defined( 'ABSPATH' ) or die( 'nope' );

/**
 * Processing related to any API call.
 */
class FIGApiFetch {
	
	/**
	 * API URL to fetch.
	 */
	public $url;
	
	/**
	 * Name of the option under which to save fetched API data.
	 */
	public $option;
	
	public function setUrl( $new_url ) {
		
		$this->url = $new_url;
		
	}
	
	public function setOption( $new_option ) {
		
		$this->option = $new_option . '_api';
		
	}
	
	/**
	 * Fetch the data from the API URL.
	 */
	public function getData() {
		
		if ( !empty( $this->url ) && !filter_var( $this->url, FILTER_VALIDATE_URL ) === FALSE ) :
			
			$data = wp_remote_get( $this->url );
			
			if ( isset( $data['response']['code'] ) && $data['response']['code'] == 200 ) {
				
				$body = $data['body'];
				
				/**
				 * Return data as an Object.
				 */
				return json_decode( $body );
				
			} else {
				
				/**
				 * Something went wrong.
				 */
				return false;
				
			}
			
		endif; 
		
	}
	
	/**
	 * Save data via update_option in order to limit API requests.
	 */
	public function saveApiData() {
		
		$data = $this->getData();
		
		if ( $data ) :
			
			update_option( $this->option, $data );
			
			update_option( $this->option . '_check_time', strtotime( '+1 hour', current_time( 'timestamp' ) ) );
			
			return true;
			
		else :
			
			return false;
			
		endif;
		
	}
	
	/**
	 * Check to see if it’s time to fetch new data from the API source.
	 */
	public function checkApiUpdate( $force = false ) {
		
		$api_data = get_option( $this->option );
		
		$update_time = get_option( $this->option . '_check_time' );
		
		if ( $force || empty( $api_data ) || empty( $update_time ) || current_time( 'timestamp' ) > $update_time ) :
			
			$this->saveApiData();
			
		endif;
		
	}
	
}

function fig_process_miusage_api_check() {
	
	$force = ( isset( $_POST['force'] ) ? $_POST['force'] : false );
	
	$api = new FIGApiFetch();
	
	$api->setUrl( 'https://miusage.com/v1/challenge/1/' );
	
	$api->setOption( 'miusage' );
	
	$api->checkApiUpdate( $force );
	
	if ( $force ) :
		
		$update_time = get_option( $api->option . '_check_time' );
		
		echo date_i18n( 'l, F j Y \a\t g:ia', $update_time );
		
		wp_die();
		
	endif;
	
}
add_action( 'init', 'fig_process_miusage_api_check' );
add_action( 'admin_init', 'fig_process_miusage_api_check' );
add_action( 'wp_ajax_fig_process_miusage_api_check', 'fig_process_miusage_api_check' );
add_action( 'wp_ajax_nopriv_fig_process_miusage_api_check', 'fig_process_miusage_api_check' );

function fig_process_ajax_request() { 
	
	$ids = ( isset( $_POST['ids'] ) ? $_POST['ids'] : null );
	$context = ( isset( $_POST['context'] ) ? $_POST['context'] : null );
	$fetch = ( isset( $_POST['fetch'] ) ? $_POST['fetch'] : false );
	
	if ( !empty( $ids ) && $context == 'shortcode' ) :
		
		$ids = urldecode( $ids );
		
	else :
		
		$ids = false;
		
	endif;
	
	$fig_admin = new FIGAdmin();
	
	/**
	 * We need to refresh/re-fetch the data from the API source.
	 */
	if ( $fetch ) :
		
		$fig_api_fetch = new FIGApiFetch();
		
		$fig_api_fetch->saveApiData();
		
	endif;
	
	$fig_admin->displayDataTable( 'miusage_api', $context, $ids );
	
	wp_die();
	
}
add_action( 'wp_ajax_fig_process_ajax_request', 'fig_process_ajax_request' );
add_action( 'wp_ajax_nopriv_fig_process_ajax_request', 'fig_process_ajax_request' );