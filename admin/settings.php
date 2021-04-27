<?php defined( 'ABSPATH' ) or die( 'nope' );

class FIGAdmin {
	
	/**
	 * Define the default tabs in the navigation area.
	 */
	public $tabs = array(
		
		'the-info'		=>	'The Info',
		'shortcode'		=>	'Shortcode',
		'dev'			=>	'Developer'
		
	);
	
	/**
	 * Define the sub navigation method.
	 */
	public function adminSubNav() {
		
		$current_tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : null );
		
		$i = 0; ?>
		
		<nav class="fig-admin-navigation">
			
			<ul>
				<?php foreach ( $this->tabs as $key => $tab ) { $i++ ?>
				<li <?php if ( ( $current_tab == $key ) || ( empty( $current_tab ) && $i == 1 ) ) : echo ' class="active"'; endif; ?>>
					<a href="<?php echo get_admin_url( null, '/admin.php?page=fancy-info-settings&tab=' . $key ); ?>"><?php echo __( $tab, 'tm-fancy-info-getter' ); ?></a>
				</li>
				<?php } ?>
			</ul>
			
		</nav>
		
	<?php }
	
	/**
	 * Display the table based upon the specific API data specified.
	 */
	public function displayDataTable( $option, $context = 'admin', $cols = array() ) {
		
		$data = get_option( $option );
		
		if ( !empty( $cols ) ) :
			
			$cols = explode( ',', $cols );
			
		endif; ?>
		
		<h2 class="fig-table-heading section-heading">
			<?php _e( $data->title, 'tm-fancy-info-getter' ); ?>
		</h2>
		
		<table class="wp-list-table widefat fixed striped table-view-list fig-table-content">
			
			<thead>
				
				<tr>
					<?php $i = 0;
					foreach ( $data->data->headers as $header_item ) {
						
						if ( !empty( $cols ) && !in_array( $header_item, $cols ) ) :
							
							continue;
							
						endif; ?>
						
						<th scope="col">
							<?php _e( $header_item, 'tm-fancy-info-getter' ); ?>
						</th>
					<?php $i++; } ?>
				</tr>
				
			</thead>
			
			<tbody id="the-list">
				
				<?php foreach ( $data->data->rows as $row_item ) { ?>
				<tr>
					<?php $i = 0;
					foreach ( $row_item as $cell_item ) {
						
						if ( !empty( $cols ) && !in_array( $data->data->headers[$i], $cols ) ) :
							
							$i++;
							
							continue;
							
						endif; ?>
						
						<td>
						<?php if ( $i == 4 ) {
							
							echo date( 'm/d/Y', $cell_item );
							
						} else { 
							
							_e( $cell_item, 'tm-fancy-info-getter' );
							
						}; ?>
						</td>
					<?php $i++; } ?>
				</tr>
				<?php } ?>
				
			</tbody>
			
			<tfoot>
				
				<tr>
					<?php foreach ( $data->data->headers as $header_item ) {
					if ( !empty( $cols ) && !in_array( $header_item, $cols ) ) :
						
						continue;
						
					endif; ?>
					<th scope="col"><?php _e( $header_item, 'tm-fancy-info-getter' ); ?></th>
					<?php } ?>
				</tr>
				
			</tfoot>
			
		</table>
		
	<?php }
	
}

function fig_setup_admin_pages() {
	
	add_menu_page( __( 'Fancy Info', 'tm-fancy-info-getter' ), __( 'Fancy Info', 'tm-fancy-info-getter' ), 'manage_options', 'fancy-info-settings', 'fig_admin_settings_content', 'data:image/svg+xml;base64,' . base64_encode( '<svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="cloud-download" class="svg-inline--fa fa-cloud-download fa-w-20" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M543.7 200.1C539.7 142.1 491.4 96 432 96c-7.6 0-15.1.8-22.4 2.3C377.7 58.3 328.1 32 272 32c-84.6 0-155.5 59.7-172.3 139.8C39.9 196.1 0 254.4 0 320c0 88.4 71.6 160 160 160h336c79.5 0 144-64.5 144-144 0-61.8-39.2-115.8-96.3-135.9zM496 432H160c-61.9 0-112-50.1-112-112 0-56.4 41.7-103.1 96-110.9V208c0-70.7 57.3-128 128-128 53.5 0 99.3 32.8 118.4 79.4 11.2-9.6 25.7-15.4 41.6-15.4 35.3 0 64 28.7 64 64 0 11.8-3.2 22.9-8.8 32.4 2.9-.3 5.9-.4 8.8-.4 53 0 96 43 96 96s-43 96-96 96zM383.6 255.6c-4.7-4.7-12.4-4.7-17.1.1L312 311.5V172c0-6.6-5.4-12-12-12h-24c-6.6 0-12 5.4-12 12v139.5l-54.5-55.8c-4.7-4.8-12.3-4.8-17.1-.1l-16.9 16.9c-4.7 4.7-4.7 12.3 0 17l104 104c4.7 4.7 12.3 4.7 17 0l104-104c4.7-4.7 4.7-12.3 0-17l-16.9-16.9z"></path></svg>' ) );
	
}
add_action( 'admin_menu', 'fig_setup_admin_pages' );

function fig_admin_settings_content() {
	
	$data = get_option( 'miusage_api' );
	$update_time = get_option( 'miusage_api_check_time' );
	
	$current_tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : null );
	
	$fig_admin = new FIGAdmin(); ?>
	
	<div class="fig-admin-heading">
		
		<img class="admin-logo" src="<?php echo plugins_url( '../', __FILE__ ); ?>/img/logo.svg" alt="Logo" />
		
	</div>
	
	<div class="fig-wrap">
	
		<!-- main content -->
		<div id="post-body-content">
		
			<?php $fig_admin->adminSubNav(); ?>
			
			<div class="fig-admin-content-area">
				
				<?php 
				/**
				 * Primary table to display the info table.
				 */
				if ( $current_tab == 'the-info' || empty( $current_tab ) ) :
					
					if ( !empty( $data ) ) { ?>
						
						<div id="fig-table-autoloader" data-context="admin">
							
							<p class="loading"><?php _e( 'Data is loading . . .', 'tm-fancy-info-getter' ); ?></p>
							
						</div>
						
					<?php } else {
						
						_e( 'Sorry, there’s no data to display at the moment!', 'tm-fancy-info-getter' );
						
					} ?>
					
					<p>
						<button class="button button-primary fig-button" id="fig-refresh-admin-table-data">Refresh Fancy Info</button>
					</p>
					
					<p class="description">
						<?php _e( 'We’ll automatically re-fetch data from the source on', 'tm-fancy-info-getter' ); ?>
						<strong id="fig-next-check-datetime"><?php echo date_i18n( 'l, F j Y \a\t g:ia', $update_time ); ?></strong>.
					</p>
					
				<?php
				/**
				 * Shortcode creator section.
				 */	
				elseif ( $current_tab == 'shortcode' ) : ?>
					
					<h2 class="section-heading">
						<?php _e( 'Shortcode Creator', 'tm-fancy-info-getter' ); ?>
					</h2>
					
					<p class="description">
						<?php _e( 'Ready to add this amazing info to your site content? You’re in the right place! First, pick the info you would like included in the info table and then copy/paste the shortcode to the post/page on which you would like to display the info.', 'tm-fancy-info-getter' ); ?>
					</p>
					
					<div class="fig-setting-row">
						
						<div class="fig-setting-header">
							
							<lable for="">
								<h4><?php _e( 'Columns', 'tm-fancy-info-getter' ); ?></h4>
							</lable>
							
						</div>
						
						<div class="fig-setting-body">
							
							<?php foreach ( $data->data->headers as $header_item ) { ?>
							<p>
								<label for="shortcode-setting-<?php echo $header_item; ?>">
									<input type="checkbox" class="fig-shortcode-col-option" id="shortcode-setting-<?php echo $header_item; ?>" data-id="<?php echo $header_item; ?>" onclick="toggleFigShortcodeCols()" checked="checked" />
									<?php _e( $header_item, 'tm-fancy-info-getter' ); ?>
								</label>
							</p>
							<?php } ?>
							
							<p class="description">
								<?php _e( 'Specify which columns you would like included in this instance of the shortcode.', 'tm-fancy-info-getter' ); ?>
							</p>
							
						</div>
						
					</div>
					
					<div class="fig-setting-row">
						
						<div class="fig-setting-header">
							
							<lable for="fig-shortcode">
								<h4><?php _e( 'Shortcode', 'tm-fancy-info-getter' ); ?></h4>
							</lable>
							
						</div>
						
						<div class="fig-setting-body">
							
							<?php $col_string = implode( ',', $data->data->headers ); ?>
							
							<p>
								<input type="text" id="fig-shortcode" class="widefat" value='[fancy_info columns="<?php echo $col_string; ?>"]' data-cols="<?php echo $col_string; ?>" readonly="readonly" />
							</p>
							
							<p class="description"><?php _e( 'Click the box above to copy the shortcode to your clipboard.', 'tm-fancy-info-getter' ); ?></p>
							
						</div>
						
					</div>
				<?php 
				/**
				 * Developer CLI command.
				 */	
				elseif ( $current_tab == 'dev' ) :?>
				
					<h2 class="section-heading">
						<?php _e( 'WP CLI Command', 'tm-fancy-info-getter' ); ?>
					</h2>
					
					<p class="description">
						<?php _e( 'Use this command if you would like to refresh the fancy info data from its API source:', 'tm-fancy-info-getter' ); ?>
					</p>
					
					<p><br />
						<code>php wp-cli.phar fig-refresh-miusage-api</code>
					</p>
				
				<?php endif; ?>
				
			</div>
			
		</div>
	
	</div>
	
<?php }