<?php
/**
 * Plugin Name:    Posts Date Reschedule
 * Plugin URI:     https://skostadinov.eu/posts-date-reschedule/
 * Description:    Basic extension with which you can set bulk, new dates for your posts.
 * Version:        1.0.0
 * Author:         Stoyan Kostadinov
 * Author URI:     http://skostadinov.eu
 * Text Domain:    pdr
 *
 * @package         Posts Date Reschedule
 * @author          Stoyan Kostadinov
 * @copyright       Copyright (c) Stoyan Kostadinov <www.skostadinov.eu>
 */


define( 'PDR_PATH', dirname( __FILE__ ) );
define( 'PDR_URI', plugin_dir_url( __FILE__ ) );

if ( !defined( 'ABSPATH' ) ){
	exit;
}

if( !class_exists( 'PostsDateReschedule' ) ):

class PostsDateReschedule{

	public function __construct(){
		$this->hooks();
	}

	public function hooks(){
		add_action( 'admin_menu', array($this, 'pdr_regsiter_pages') );
		add_action('admin_enqueue_scripts', array($this, 'register_styles_scripts') );

		load_plugin_textdomain( 'pdr', false, PDR_PATH . '/languages' ); 
	}

	public function register_styles_scripts(){
		//Styles
		wp_enqueue_style( 'pdr-styles', PDR_URI . '/assets/styles/main.css', '', '1.0.0', null );

		//Scrpts
		wp_enqueue_script( 'pdr-scripts', PDR_URI . '/assets/scripts/main.js', 'jquery', '1.0.0', false );
	}

	public function pdr_regsiter_pages(){
		add_menu_page( __( "Posts Date Reschedule", 'pdr' ), __( "Posts Date Reschedule", 'pdr' ), 'edit_themes', 'posts-date-reschedule-settings', array( $this, 'pdr_main_settings_menu' ) );
	}

	public function pdr_main_settings_menu(){
		$pdr_messages = array();
		include_once( PDR_PATH . '/inc/settings-page.php');

		if ( isset($_POST['pdr_save']) && !empty($_POST['pdr_save']) ) {
			$posts = $_POST['pdr_posts'];

			if ( isset($posts) ) {
				$pdt_function = intval($_POST['pdt_function']);

				if ( $pdt_function == 1 ) {
					$date = $_POST['pdt_function_date'];
					$time = $_POST['pdt_function_time'];

					if ( isset($date) && isset($time) && $date != null && $time != null ) {
						foreach ($posts as $post) {
							$new_date = $date . ' ' . $time . ':00';
							$post_info = array(
								'ID' => $post,
								'post_date' => $new_date,
							);
						}
					}else{
						$pdr_messages[] = array(
							'type' => 'error',
							'message' => 'Please choose date & time',
						);
					}
				}

				if ( $pdt_function == 2 || $pdt_function == 3 ) {
					if ( isset($_POST['pdt_function_days_plus']) && $_POST['pdt_function_days_plus'] != null || isset($_POST['pdt_function_days_minus']) && $_POST['pdt_function_days_minus'] != null ) {
						foreach ( $posts as  $post ) {
							if ( isset($_POST['pdt_function_days_plus']) && isset($_POST['pdt_function_days_minus'])) {
								$post_timestamp = get_the_date('U', $post);
							
								if ( $pdt_function == 2 ) {
									$new_timestamp = $post_timestamp + (intval($_POST['pdt_function_days_plus'])*(60 * 60 * 24));
								}else{
									$new_timestamp = $post_timestamp - (intval($_POST['pdt_function_days_minus'])*(60 * 60 * 24));
								}

								$new_date = date('Y-m-d H:i:s', $new_timestamp);
								$post_info = array(
									'ID' => $post,
									'post_date' => $new_date,
								);
							}	
						}
					}else{
						$pdr_messages[] = array(
							'type' => 'error',
							'message' => 'Please choose days',
						);
					}
				}

				if ( $pdt_function == 4 ) {
					foreach ( $posts as  $post ) {
						$timestamp = mktime(0, 0, 0, 0, 0, date('Y') );

						$new_timestamp = $timestamp + rand( 0, 60*60*24*365 );
						$new_date = date('Y-m-d H:i:s', $new_timestamp);
						$post_info = array(
							'ID' => $post,
							'post_date' => $new_date,
						);
					}
				}

				if ( wp_update_post($post_info) ) {
					$pdr_messages[] = array(
						'type' => 'success',
						'message' => 'Updated the posts successfully.',
					);
				}

			}else{
				$pdr_messages[] = array(
					'type' => 'error',
					'message' => 'Please choose posts',
				);
			}
		}

		if ( isset($pdr_messages) ) {
			foreach ($pdr_messages as $message) {
				echo '<div class="notice notice-'.$message['type'].'">'.$message['message'].'</div>';
			}
		}
	}
}

endif;

add_action( 'plugins_loaded', function() {
	new PostsDateReschedule();
} );