<?php
    /*
    Plugin Name: innovator-facebook-reviews
    Plugin URI: https://github.com/vinodhip/
    Description: Plugin for displaying facebook-reviews
    Author: VinothKumar Subramani
    Version: 1.0
    Author URI: https://github.com/vinodhip/
    */
?>
<?php 
/************************************
*
Facebook Reviews
*
*************************************/
require_once 'src/config.php';
require_once 'src/facebook.php';
require_once 'reviews-posts.php';
require_once 'fetching-fb-reviews-frontend.php';

class facebookReviews {

			/************************************
			Creating Theme Options For Facebook page setting
			*************************************/

	public static function theme_options_init_fbrvs() {
			register_setting( 'theme_options', 'theme_options');
	} 
	
			/************************************
			Add this options in separate page
			*************************************/
			
	public static function theme_options_add_facebook_page() {
			add_options_page( __( 'Facebook Reviews', 'sampletheme' ), __( 'Facebook Reviews', 'sampletheme' ), 'edit_theme_options', 'facebook_reviews_settings', 'facebookReviews::theme_options_do_facebook_page' );
	} 

			/************************************
			Creating UI for entering facebook details
			*************************************/

	public static function theme_options_do_facebook_page() {
			
		   if ( $_POST['update_themeoptions_fb'] == 'true' ) { self::themeoptions_update_fbrvs(); }
?>	
			<div class="wrap">
				<div id="social_options">
					<h2>Facebook Application Details</h2>
					<p class="notification"></p>
					<form method="post" action="" name="header_form" id="header_form" >
						<input type="hidden" name="update_themeoptions_fb" value="true" />
						<label for="metakeyselect">Facebook App ID</label><br />
						<input type="text" name="fbrvs_app_id" size="40" id="fbrvs_app_id"  value="<?php echo get_option('fbrvs_app_id'); ?>"/><br />
						<label>Facebook App Secret Key</label><br />
						<input type="text" name="fbrvs_secret_key" size="40" id="fbrvs_secret_key"  value="<?php echo get_option('fbrvs_secret_key'); ?>"/><br />
						<label>Facebook Page ID</label><br />
						<input type="text" name="fbrvs_page_id" size="40" id="fbrvs_page_id"  value="<?php echo get_option('fbrvs_page_id'); ?>"/><br />
						<label>Facebook Page Access Token</label><br />
						<input type="text" name="fbrvs_page_access_token" size="40" id="fbrvs_page_access_token"  value="<?php echo get_option('fbrvs_page_access_token'); ?>"/><br />
						
						<input id="btn" class="button button-primary button-large" type="submit" value="Save/Update" name="req_submit" />	
					</form>
				</div>
				<div id="social_options">
					<h2>Sync Facebook Reviews To Our Admin Panel</h2>
					<form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
						<input type="hidden" name="action" value="fetch_fb">
						<input class="button button-primary button-large" type="submit" value="Sync Now">
					</form>
					<p class="notification">Click Here to Fetch the Reviews From Facebook To our FB Reviews Posts</p>
				</div>	
				<?php
				?>
		</div>
		<style type="text/css">
					.wrap p{
					margin:0;
					}
					#social_options {
					width: 50%;
					}
					#social_options label {
					font-size: 15px;
					font-weight: bold;
					padding: 0 0 12px;
					}
					#social_options input {
					margin: 0 0 25px;
					}
					#social_options form {
					margin-left: 25px;
					margin-top:35px;
					}
					.notification {
					color: red;
					font-size: 16px;
					font-weight: bold;
					padding-left: 0%;
					}
						
		</style>
<?php 
		}

			/************************************
			Update the Facebook Details
			*************************************/
		
	 public static function themeoptions_update_fbrvs() {
	  
			update_option( 'fbrvs_app_id', $_POST['fbrvs_app_id'] );
			update_option( 'fbrvs_secret_key', $_POST['fbrvs_secret_key'] );
			update_option( 'fbrvs_page_id', $_POST['fbrvs_page_id'] );
			update_option( 'fbrvs_page_access_token', $_POST['fbrvs_page_access_token'] );
		
		} 
	public static function fetchingFacebookReviewsToAdmin() {
       
				$fbrvs_appid = get_option('fbrvs_app_id');
				$fbrvs_app_secret = get_option('fbrvs_secret_key');
				$fbrvs_pageid = get_option('fbrvs_page_id');
				$access_token = get_option('fbrvs_page_access_token');
				// Create our Application instance (replace this with your appId and secret).
				$facebook = new Facebook(array(
				  'appId'  => $fbrvs_appid,
				  'secret' => $fbrvs_app_secret,
				  'cookie' => true
				));
				$reviews_details = $facebook->api('/'.$fbrvs_pageid.'/ratings', 'GET', array('access_token' => $access_token ));
				$loop_count = count($reviews_details[data]);
				$args = array('posts_per_page' => -1,'order' => 'DESC','post_type' => 'fb_reviews','post_status' => 'publish');
				$fb_posts = get_posts( $args );
				foreach($fb_posts as $fb_post){ $fetched_users[] = get_post_meta( $fb_post->ID, 'fbrvs_user_id', true ); }
				foreach($reviews_details[data] as $key => $reviews_detail){
						$user_id = $reviews_detail[reviewer][id];
						$user_name = $reviews_detail[reviewer][name];
						$user_rating = $reviews_detail[rating];
						$user_review_text = $reviews_detail[review_text];
						$post_date = gmdate('Y-m-d H:i:s', strtotime($reviews_detail[created_time]));
						if(!in_array($user_id, $fetched_users)){
						$new_review = array(
						'post_content'          => $user_review_text,
						'post_title'            => $user_name,
						'post_status'           => 'publish', 
						'post_type'             => 'fb_reviews',
						'post_date'      =>  $post_date,
						);							
						$fbrvs_post_id = wp_insert_post($new_review, true);
						add_post_meta($fbrvs_post_id, 'fbrvs_user_id', $user_id, true);
						add_post_meta($fbrvs_post_id, 'fbrvs_user_rating', $user_rating, true);
					}
				 } //end foreach
        
        //redirect back to plugin page
        wp_redirect(admin_url("options-general.php?page=facebook_reviews_settings"));
        
    	}//end function
		
	 }
		add_action( 'admin_init', array( 'facebookReviews', 'theme_options_init_fbrvs' ) );
		add_action( 'admin_menu', array( 'facebookReviews', 'theme_options_add_facebook_page' ) );
		add_action( 'theme_update_actions', array( 'facebookReviews', 'theme_options_do_facebook_page' ) );
		add_action( 'admin_post_fetch_fb', array( 'facebookReviews', 'fetchingFacebookReviewsToAdmin') );
?>
