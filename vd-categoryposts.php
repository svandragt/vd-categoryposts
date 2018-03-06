<?php

/*
Plugin Name: Category Posts
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: Sander van Dragt
Author URI: https://vandragt.com
License: GPL3
*/

function vd_categoryposts( $single_category = false ) {

	// get the last 50 posts
	$posts = get_posts( array(
		'posts_per_page' => 50,
	) );

	// sort first 3 posts per category
	$category_posts = array();
	foreach ( $posts as $post ) {
		var_dump( $post->post_title );
		$post_categories = get_the_category( $post->ID );
		foreach ( $post_categories as $post_category ) {
			// skip multiple categories if disabled
			if ( $post_categories[0] !== $post_category && $single_category ) {
				continue;
			}
			if ( ! isset( $category_posts[ $post_category->name ] ) ) {
				$category_posts[ $post_category->name ] = array();
			}
			if ( count( $category_posts[ $post_category->name ] ) < 3 ) {
				$category_posts[ $post_category->name ][] = $post;
			}
		}
	}

	return $category_posts;
}

function vd_categoryposts_loop() {
	$category_posts = vd_categoryposts( true );

	foreach ( $category_posts as $category => $posts ):
		$category_id   = get_cat_ID( $category );
		$category_link = get_category_link( $category_id );
		printf( '<article class="format-list"><h2 class="category"><a href="%s">%s</a></h2>', $category_link, $category );
		foreach ( $posts as $post ):
			setup_postdata( $post );
			the_title( $post );
		endforeach;
		print( '</article>' );
		wp_reset_postdata();
	endforeach;
}

function vd_categoryposts_shortcode( $atts ) {
	vd_categoryposts_loop();

	return "vd_categoryposts_shortcode";
}

add_shortcode( 'categoryposts', 'vd_categoryposts_shortcode' );