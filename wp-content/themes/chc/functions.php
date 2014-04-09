<?php


function get_header_img_cap() {
    $header_data = get_theme_mod('header_image_data');
    $header_array = (array) $header_data;
    $attachment_id = $header_array["attachment_id"];
    $attachment = get_post( $attachment_id ); 
    $header_array =  array(
        'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
        'caption' => $attachment->post_excerpt,
        'description' => $attachment->post_content,
        'href' => get_permalink( $attachment->ID ),
        'src' => $attachment->guid,
        'title' => $attachment->post_title
    );
    echo $header_array['caption'];
    
}

add_filter( 'wp_nav_menu_items', 'wpsites_add_logo_nav_menu', 10, 2 );


/*
 *
 * START IMG CAP REGISTER
 *
 */


function mytheme_customize_register( $wp_customize ) {
    //All our sections, settings, and controls will be added here

    $wp_customize->add_setting( 'img_cap_textcolor' , array(
        'default'     => '#000000',
        'transport'   => 'refresh',
    ) );

    $wp_customize->add_section( 'img_cap' , array(
    'title'      => __( 'Image caption', 'chc' ),
    'priority'   => 30,
) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'img_cap_textcolor', array(
	'label'      => __( 'Image Caption Color', 'chc' ),
	'section'    => 'your_section_id',
	'settings'   => 'your_setting_id',
            ) 
        ) 
    );

}

add_action( 'customize_register', 'mytheme_customize_register' );

function mytheme_customize_css()
{
    ?>
         <style type="text/css">
             h3.header-cap { color:#ffffff; text-align: right; font-size: 12px; }
             /*h3.header-cap { color:<?php echo get_theme_mod('img_cap_textcolor'); ?>; }*/
         </style>
    <?php
}
add_action( 'wp_head', 'mytheme_customize_css');


/*
 *
 * END IMG CAP REGISTER 
 *
 */


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
