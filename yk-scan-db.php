<?php

/**
 * Plugin Name: Scan data base by post type
 * Plugin URI: https://yupiketing.com/
 * Description: Search a string in all the post types.
 * Version: 1.0
 * Author: Yupiketing
 * Author URI: https://yupiketing.com/
 * Requires at least: 5.0
 * Tested up to: 5.5.0
 *
 * Text Domain: yk-scan-db
 * Domain Path: /lang/
 */

	defined( 'ABSPATH' );

	function yk_scan_db_function( $search ) {
		$result = '<ul>';
		$args = array(
			'post_type' 			=> get_post_types(),
			'posts_per_page' 	=> -1,
			's' 							=> $search,
		);
		$data = new WP_Query( $args );
		while ( $data->have_posts() ) {
			$data->the_post();
			$post_id = get_the_ID();
			$post_url = get_the_permalink( $post_id );
			$post_title = get_the_title( $post_id );
			$post_type = get_post_type( $post_id );
			$result .= '<li>'.$post_type.' - <a href="'.$post_url.'" target="_blank">'.$post_url.'</a> - <a href="'.site_url().'/wp-admin/post.php?post='.$post_id.'&action=edit" target="_blank">'.__('Edit', 'yk-scan-db').'</a></li>';
		}
		$result .= '<ul>';
		if ( $data->found_posts == 0 ){
			$result = __( "Not found", "yk-scan-db" );
		}
		return $result;
	}

	function yk_scan_db_page(){
		$post_types = get_post_types();
		$string = ( isset( $_POST["string"] ) ) ? $_POST["string"] : '';
		$action = ( isset( $_POST["action"] ) ) ? $_POST["action"] : '';
		echo '<div class="wrap">
			<h1>'.__( "Scan post types", "yk-scan-db" ).'</h1>
			<p>
			<form method="post" action="">
				<input type="hidden" name="action" value="scan">
				<input type="text" name="string" value="'.$string.'" placeholder="'.__('String', 'yk-scan-db').'" required>
				<input type="submit" class="button button-primary" value="'.__('Search', 'yk-scan-db').'">
			</form>
			</p>';
			if ( $action == 'scan'){
				if ( $string ){
					echo yk_scan_db_function( $_POST["string"] );
				}
			}
		echo '</div>';
	}

	function yk_scan_db_menu() {
		add_submenu_page(	'tools.php',
										__( "Scan post types", "yk-scan-db" ),
										__( "Scan post types", "yk-scan-db" ),
										'manage_options',
										'scan-db',
										'yk_scan_db_page',
										10 );
	}
	add_action( 'admin_menu', 'yk_scan_db_menu', 10 );

?>