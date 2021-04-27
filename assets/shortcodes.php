<?php defined( 'ABSPATH' ) or die( 'nope' );

function FIG_table_shortcode( $atts, $content = null ) {
	
	$a = shortcode_atts( array(
		'columns'	=>	'',
	), $atts );

	$fig_admin = new FIGAdmin();
	
	ob_start(); ?>
	
	<div class="fig-table-content-container" id="fig-table-autoloader" data-ids="<?php echo $a['columns']; ?>" data-context="shortcode">
		
		<p class="loading"><?php _e( 'Data is loading . . .', 'tm-fancy-info-getter' ); ?></p>
		
	</div>
	
	<?php $output_string = ob_get_contents();
	
	ob_end_clean();
	
	return $output_string;
	
}
add_shortcode( 'fancy_info', 'FIG_table_shortcode' );