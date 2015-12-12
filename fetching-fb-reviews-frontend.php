<?php
/************************************
*
Used For Frontend Facebook Display
*
*************************************/
 
class frontendFacebookReviews {

	public static function facebookReviewsFrontend($atts) {
	
				extract( shortcode_atts( array( 'limit' => 5 ), $atts ) );
				$args = array('posts_per_page' => -1,'order' => 'DESC','post_type' => 'fb_reviews','post_status' => 'publish');
				$fb_front_posts = get_posts( $args );
				foreach($fb_front_posts as $key => $fb_front_post){ 
				$fetched_users_front = get_post_meta( $fb_front_post->ID, 'fbrvs_user_id', true ); 
				$fetched_users_rating_front = get_post_meta( $fb_front_post->ID, 'fbrvs_user_rating', true );
				?>
                <li>
                  <blockquote>
                  <div class="row">
                    <div class="col-sm-3 text-center">
					  <img class="img-circle" src="https://graph.facebook.com/<?php echo $fetched_users_front; ?>/picture?type=normal&amp;width=100&amp;height=100" style="width: 100px;height:100px;">
                    </div>
					<div class="col-sm-9">
					<div class="rating">
					<?php for($i=0;$i<$fetched_users_rating_front;$i++){ ?><span class="glyphicon glyphicon-star"></span><?php } ?>
					<?php $balance = 5 - $fetched_users_rating_front; for($j=0;$j<$balance;$j++){?> <span class="glyphicon glyphicon-star-empty"></span><?php } ?>
					</div>
					<p><?php echo $fb_front_post->post_content; ?></p>
					<small><a style="color:#777;" href="https://www.facebook.com/<?php echo $fetched_users_front; ?>" target="_blank"><?php echo $fb_front_post->post_title; ?></a></small>
					</div>				  	
                  </div>
                </blockquote>
                </li>
		<?php if($key == $limit-1){	break; } 
 			}  
		}
	}
	
	add_shortcode( 'facebook-reviews', array( 'frontendFacebookReviews','facebookReviewsFrontend' ) );
?>