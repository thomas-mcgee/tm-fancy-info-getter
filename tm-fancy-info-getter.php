<?php defined( 'ABSPATH' ) or die( 'nope' );

/**
 * Plugin Name:       Fancy Info Getter
 * Plugin URI:        https://awesomemotive.com/career/developer-applicant-challenge/
 * Description:       The coding challenge for Awesome Motive.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Thomas McGee
 * Author URI:        https://thomasmcgee.tv
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       tm-fancy-info-getter
 * Domain Path:       /languages
 */
 
/**
 * Requires
 */
require_once dirname( __FILE__ ) . '/assets/api.php';
require_once dirname( __FILE__ ) . '/admin/settings.php';
require_once dirname( __FILE__ ) . '/assets/shortcodes.php';
require_once dirname( __FILE__ ) . '/assets/cli.php';

/**
 * Updated string, used for cache-breaking with version updates.
 */
function fig_updated( $dev_mode = false ) {
    
    /**
     * Change $dev_mode to true to prevent caching of resources.
     */
    if ( !$dev_mode ) {
        
        return '00001';
        
    } else {
        
        return time();
        
    }
    
}

/**
 * Enqueue front-end scripts and styles.
 */
function fig_include_enqueues() {
    
    wp_register_script( 'fig-ajax-functions', plugins_url( '/js/ajax-min.js', __FILE__ ), array(), fig_updated(), true );
    
    $wp_vars = array(
        'ajax_url' => admin_url( 'admin-ajax.php' ) ,
    );
    
    wp_localize_script( 'fig-ajax-functions', 'figAjaxUrl', $wp_vars );
    
    wp_enqueue_style( 'fig-styles', plugins_url( '/style.css', __FILE__ ), $deps = array(), fig_updated() );
    wp_enqueue_script( 'fig-ajax-functions' );
    
}
add_action( 'wp_enqueue_scripts', 'fig_include_enqueues' );

/**
 * Enqueue admin scripts and styles.
 */
function fig_include_admin_enqueues() {
    
    wp_register_script( 'fig-ajax-functions', plugins_url( '/js/ajax-min.js', __FILE__ ), array(), fig_updated(), true );
    
    $wp_vars = array(
        'ajax_url' => admin_url( 'admin-ajax.php' ) ,
    );
    
    wp_localize_script( 'fig-ajax-functions', 'figAjaxUrl', $wp_vars );
    
    wp_enqueue_style( 'fig-admin-styles', plugins_url( '/admin/admin-style.css', __FILE__ ), $deps = array(), fig_updated() );
    wp_enqueue_script( 'fig-admin-functions', plugins_url( '/admin/admin-functions-min.js', __FILE__ ), array(), fig_updated(), true );
    wp_enqueue_script( 'fig-ajax-functions' );
    
}
add_action( 'admin_enqueue_scripts', 'fig_include_admin_enqueues' );
