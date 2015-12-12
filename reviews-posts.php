<?php 
/************************************
*
Create Custom Post type(FB Reviews)
*
*************************************/
require_once 'src/config.php';
require_once 'src/facebook.php';
class reviewsType {
					/************************************
					Registering Custom Post Type (FB Reviews)
					*************************************/
	
	public static function registerReviewsPostType() {
	
					/************************************
					Labels for Custom Post Type(FB Reviews)
					*************************************/

					$labels = array(
					'name'                => _x( 'FB Reviews', 'Post Type General Name', 'Delikatessboxen' ),
					'singular_name'       => _x( 'FB Review', 'Post Type Singular Name', 'Delikatessboxen' ),
					'menu_name'           => __( 'FB Reviews', 'Delikatessboxen' ),
					'parent_item_colon'   => __( 'Parent FB Review', 'Delikatessboxen' ),
					'all_items'           => __( 'All FB Reviews', 'Delikatessboxen' ),
					'view_item'           => __( 'View FB Review', 'Delikatessboxen' ),
					'add_new_item'        => __( 'Add New FB Review', 'Delikatessboxen' ),
					'add_new'             => __( 'Add New', 'Delikatessboxen' ),
					'edit_item'           => __( 'Edit FB Review', 'Delikatessboxen' ),
					'update_item'         => __( 'Update FB Review', 'Delikatessboxen' ),
					'search_items'        => __( 'Search FB Reviews', 'Delikatessboxen' ),
					'not_found'           => __( 'Not Found', 'Delikatessboxen' ),
					'not_found_in_trash'  => __( 'Not found in Trash', 'Delikatessboxen' ),
					);
					
					/************************************
					Other supports for Custom Post Type(FB Reviews)
					*************************************/

					$args = array(
					'label'               => __( 'fb_reviews', 'Delikatessboxen' ),
					'description'         => __( 'Facebook Reviews For Our Services', 'Delikatessboxen' ),
					'labels'              => $labels,
					'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
					'public'              => true,
					'show_ui'             => true,
					'show_in_menu'        => true,
					'show_in_nav_menus'   => true,
					'show_in_admin_bar'   => true,
					'menu_position'       => 6,
					'can_export'          => true,
					'has_archive'         => true,
					'exclude_from_search' => false,
					'publicly_queryable'  => true,
					'capability_type'     => 'page',
					);

					/************************************
					Register Custom Post Type(FB Reviews)
					*************************************/

					register_post_type( 'fb_reviews', $args );
					
	}
/*	public static function facebookReviewsFrontend($atts) {
				//extract( shortcode_atts( array( 'limit' => 5 ), $atts ) );
				$access_token = get_option('page_access_token');
				// Create our Application instance (replace this with your appId and secret).
				$facebook = new Facebook();
				$reviews_details = $facebook->api('/362622557223811/ratings', 'GET', array('access_token' => $access_token ));
				$loop_count = count($reviews_details[data]);
				foreach($reviews_details[data] as $key => $reviews_detail){
						$user_id = $reviews_detail[reviewer][id];
						$user_name = $reviews_detail[reviewer][name];
						$user_rating = $reviews_detail[rating];
						$user_review_text = $reviews_detail[review_text];
						echo '<pre>';
						print_r($reviews_detail);
						echo '<pre>';
					}  
				}
*/	
	}
				add_action( 'init', array( 'reviewsType','registerReviewsPostType' ) );
				//add_shortcode( 'facebook-reviews', array( 'reviewsType','facebookReviewsFrontend' ) );
?>