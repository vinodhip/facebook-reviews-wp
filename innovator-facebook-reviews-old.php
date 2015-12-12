<?php
    /*
    Plugin Name: innovator-facebook-reviews
    Plugin URI: http://www.innovator.se/
    Description: Plugin for displaying facebook-reviews
    Author: Jonas Stensved
    Version: 1.0
    Author URI: http://www.innovator.se/
    */
?>
<?php 
/************************************
*
Facebook Reviews
*
*************************************/
require 'src/config.php';
require 'src/facebook.php';

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
						<label>Facebook App ID</label><br />
						<input type="text" name="app_id" size="40" id="app_id"  value="<?php echo get_option('app_id'); ?>"/><br />
						<label>Facebook App Secret Key</label><br />
						<input type="text" name="secret_key" size="40" id="secret_key"  value="<?php echo get_option('secret_key'); ?>"/><br />
						<label>Facebook Page ID</label><br />
						<input type="text" name="page_id" size="40" id="page_id"  value="<?php echo get_option('page_id'); ?>"/><br />
						<label>Facebook Page Access Token</label><br />
						<input type="text" name="page_access_token" size="40" id="page_access_token"  value="<?php echo get_option('page_access_token'); ?>"/><br />
						
						<input id="btn" type="submit" value="Save/Update" name="req_submit" />	
					</form>
				</div>
		</div>
					<style>
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
					#social_options #btn {
					cursor: pointer;
					width: 100px;
					}
					.notification {
					color: red;
					font-size: 16px;
					font-weight: bold;
					padding-left: 5%;
					}
					#btn{
					background: none repeat scroll 0 0 #2ea2cc;
					border-color: #0074a2;
					box-shadow: 0 1px 0 rgba(120, 200, 230, 0.5) inset, 0 1px 0 rgba(0, 0, 0, 0.15);
					color: #fff;
					text-decoration: none;
					border-radius: 3px;
					border-style: solid;
					border-width: 1px;
					box-sizing: border-box;
					cursor: pointer;
					display: inline-block;
					font-size: 13px;
					height: 28px;
					line-height: 26px;
					margin: 5% 0 0 2%;
					padding: 0 10px 1px;
					text-decoration: none;
					white-space: nowrap;					
					}
					
					</style>	
<?php 
		}

			/************************************
			Update the Facebook Details
			*************************************/
		
	 public static function themeoptions_update_fbrvs() {
	  
			update_option( 'app_id', $_POST['app_id'] );
			update_option( 'secret_key', $_POST['secret_key'] );
			update_option( 'page_id', $_POST['page_id'] );
			update_option( 'page_access_token', $_POST['page_access_token'] );
		
		}
	public static function facebookReviewsFrontend($atts) {
				extract( shortcode_atts( array( 'limit' => 5 ), $atts ) );
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
					?>
                <li>
                  <blockquote>
                  <div class="row">
                    <div class="col-sm-3 text-center">
                      <!--<img class="img-circle" src="http://api.randomuser.me/portraits/med/women/19.jpg" style="width: 100px;height:100px;">-->
					  <img class="img-circle" src="https://graph.facebook.com/<?php echo $user_id; ?>/picture?type=normal" style="width: 100px;height:100px;">
                    </div>
					<div class="col-sm-9">
					<div class="rating">
					<?php for($i=0;$i<$user_rating;$i++){ ?><span class="glyphicon glyphicon-star"></span><?php } ?>
					<?php $balance = 5 - $user_rating; for($j=0;$j<$balance;$j++){?><span class="glyphicon glyphicon-star-empty"><?php } ?>
					</div>
					<p><?php echo $user_review_text; ?></p>
					<small><?php echo $user_name; ?></small>
					</div>				  	
                  </div>
                </blockquote>
                </li>
		<?php if($key == $limit-1){	break; } }  
		}
	}
		add_action( 'admin_init', array( 'facebookReviews', 'theme_options_init_fbrvs' ) );
		add_action( 'admin_menu', array( 'facebookReviews', 'theme_options_add_facebook_page' ) );
		add_action( 'theme_update_actions', array( 'facebookReviews', 'theme_options_do_facebook_page' ) );
		add_shortcode( 'facebook-reviews', array( 'facebookReviews','facebookReviewsFrontend' ) );
?>