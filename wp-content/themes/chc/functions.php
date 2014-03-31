<?php

add_filter( 'wp_nav_menu_items', 'wpsites_add_logo_nav_menu', 10, 2 );

function wpsites_add_logo_nav_menu( $menu, stdClass $args ){

    if ( 'primary' != $args->theme_location )
        return $menu;


    $menu = sprintf( '<li id="nav-logo">%s</li>', __( '<a href=" ' . get_site_url() . '"><img src="' .  get_template_directory_uri() . '/images/logos/chc_logo_56w_45h.png" alt="Home" /></a>' ) ) . $menu;

    return $menu;

}

/**
 * Add Custom Header
 */

function theme_chc_custom_header_setup() { 

	add_theme_support( 'custom-header', array( 'default-image' => '%2$s/header-blue.png' ) );

	register_default_headers( array(
	    'greenblue' => array(
	        'url'           => '%2$s/header-blue.png',
	        'thumbnail_url' => '%2$s/header-blue-thumbnail.png',
	        'description'   => _x( 'Blue', 'Blue default header', 'twentythirteen' )
	    ),
	) );

} 
add_action( 'after_setup_theme', 'theme_chc_custom_header_setup' );


/**
 * Import twenty thirteen base styles
 */

function theme_chc_styles() {

	// Load Twenty Thirteen main style
	wp_enqueue_style( 'twentythirteen', get_template_directory_uri() . '/style.css' , array( ), '2013-09-09' );

	// Load Twenty Thirteen RTL style if necessary
	if ( is_rtl() ) {
		wp_enqueue_style( 'twentythirteen-rtl', get_template_directory_uri() . '/rtl.css' , array( 'twentythirteen' ), '2013-09-09' );
	}

	// Loads our main stylesheet
	wp_enqueue_style( 'twentythirteen-style', get_stylesheet_uri(), array( 'twentythirteen' ), '2013-09-09' );

}

add_action( 'wp_enqueue_scripts', 'theme_chc_styles' );

?>
