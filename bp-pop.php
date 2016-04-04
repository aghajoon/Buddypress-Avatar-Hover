<?php

/*
Plugin Name: Buddypress Avatar Hover
Plugin URI: http://webcaffe.ir
Description: buddypress Avatar Hover add pop box when hovering on member/group avatar  .
Version: 1.0
Author: asghar hatampoor
Author URI: http://webcaffe.ir

*/
if ( !defined( 'ABSPATH' ) ) exit;

define('BP_POP_URL', plugin_dir_url(__FILE__));

function bp_pop_load_textdomain() {
    load_plugin_textdomain('bp-pop', false, dirname(plugin_basename(__FILE__)) . "/languages/");
}
add_action('init', 'bp_pop_load_textdomain');

function load_styles_pop() {
    if(!is_user_logged_in())
        return;   
		
            wp_register_style( 'bp-pop',BP_POP_URL.'css/bp-pop.css', array(),'20150510','all' );
            wp_enqueue_style( 'bp-pop' );
        }
add_action( 'wp_print_styles', 'load_styles_pop' );	

function load_js_pop() {
global $bp;		
  	$user = wp_get_current_user();
    if(!is_user_logged_in())
        return;

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-tooltipster', BP_POP_URL . 'js/jquery.tooltipster.js', $dep = array() );
	wp_enqueue_script( 'bp-pop', BP_POP_URL . 'js/bp-pop.js', $dep = array() );
	wp_localize_script( 'bp-pop', '_member', array( 'id' => $user->ID ) );
        }
add_action( 'wp_enqueue_scripts', 'load_js_pop' );



function bp_pop_member($params = array()){
global $bp;		
    $user_id = $_POST['id']; 
	
	$avatar_options = array ( 'item_id' => $user_id,  'type' => 'full', 'width' => 90, 'height' => 90 );
	$avatar_friend = array ( 'item_id' => $user_id,  'id' => 'non-pop', 'type' => 'full', 'width' => 50, 'height' => 50 );
	
	?> 	
      <div class="cont">
                <div class="g-hover-card"> 	 
				  <div class="g-hover-card-img">
				  
				<?php 
				if ( bp_displayed_user_use_cover_image_header() ) {
					$cover_image = bp_attachments_get_attachment( 'url', array(
		         'item_id'   => $user_id,
	            ) );
					
				}else{
					$cover_image=get_user_meta($user_id, 'bp_cover', true);		
				}
						 
                if(!empty($cover_image)){?>
				<style type="text/css">
                .g-hover-card-img {
                background-image: url("<?php echo $cover_image;?>");
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
                }
                </style>
               
				<?php }else{
				$cover_profile = get_option( 'bp_cover_profile' );
				if(!empty($cover_profile)){?>
				<style type="text/css">
                .g-hover-card-img {
                background-image: url("<?php echo $cover_profile;?>");
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
                }
                </style>
				<?php }else{?>
				<style type="text/css">
                .g-hover-card-img {
                background-image: url("<?php echo BP_POP_URL ; ?>images/default-cover.jpg");
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
                }
                </style>
				<?php }?>
				<?php }?>
				  </div>
                <div class="user-avatar-pop">
              <?php 
			  echo bp_pop_get_online_status($user_id);
			  echo bp_pop_fetch_avatar( $avatar_options ); 
			?> 
			  </div>
			<div class="bottom-pop">
			<ul>
	<?php $friendship_status = BP_Friends_Friendship::check_is_friend( bp_loggedin_user_id(), $user_id );
	if ( $friendship_status == 'is_friend') : ?>     			       
	<li><a id="friend-<?php echo $user_id;?>" rel="add" class="btpop" href="<?php echo wp_nonce_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/remove-friend/' . $user_id . '/', 'friends_remove_friend' );?>" title="<?php _e('Cancel Friendship','bp-pop')?>"><i class="pop-font pop-minus"></i></a></li>
	<?php elseif ( $friendship_status == 'not_friends') : ?>
	<li><a id="friend-<?php echo $user_id;?>" rel="remove" class="btpop" href="<?php echo wp_nonce_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/add-friend/' . $user_id . '/', 'friends_add_friend' );?>" title="<?php _e('Add Friend','bp-pop')?>"><i class="pop-font pop-plus"></i></a></li>
	<?php elseif ( $friendship_status == 'pending') :?>
	<li><a id="friend-<?php echo $user_id;?>" rel="add" class="btpop" href="<?php echo wp_nonce_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/requests/cancel/' . $user_id . '/', 'friends_withdraw_friendship' );?>" title="<?php _e('Cancel Friendship Request','bp-pop')?>"><i class="pop-font pop-minus"></i></a></li>
	<?php elseif ( $friendship_status == 'awaiting_response') :?>
	<?php if ( bp_has_members( 'type=alphabetical&include=' .$user_id ) ) : ?>
	<?php while ( bp_members() ) : bp_the_member(); ?>
	<li><a id="friend-<?php echo $user_id;?>" rel="accept" class="btpop" href="<?php echo bp_friend_accept_request_link();?>" title="<?php _e('Accept', 'buddypress')?>"><i class="pop-font pop-accept"></i></a></li>
	<li><a id="friend-<?php echo $user_id;?>" rel="reject" class="btpop" href="<?php echo bp_friend_reject_request_link();?>" title="<?php _e('Reject', 'buddypress')?>"><i class="pop-font pop-minus"></i></a></li>
	<?php endwhile; ?>
	<?php endif;?>
	<?php endif;?>
    <li><a class="btpop" href="<?php echo wp_nonce_url( bp_loggedin_user_domain() . bp_get_messages_slug() . '/compose/?r=' . bp_core_get_username( $user_id ) );?>" title="<?php _e('Send Messege','bp-pop')?>"><i class="pop-font pop-envelope"></i></a></li>
         </ul>
		 </div>	
                <div class="info-pop">
                   <div class="title-pop">
                      <a href="<?php echo bp_core_get_user_domain( $user_id );?>"><?php echo bp_pop_get_user_displayname( $user_id );?></a>
                   </div>
				</div>   
		        <div class="info-user-pop">
				     <div class="to-mem">
                         <h2><?php echo friends_get_total_friend_count( $user_id );?></h2>
				         <p><?php _e('Total friends','bp-pop')?></p>
				    </div>
				  	
		<div id="friends-container" >
	    <?php if ( bp_has_members( 'user_id='.$user_id.'&type=active&per_page=3&max=3&populate_extras=0' ) ) : ?>
	      <?php while ( bp_members() ) : bp_the_member(); ?>
	        <div class="item-avatar-friend-pop">
	          <a  href="<?php bp_member_permalink() ?>"><?php bp_member_avatar( $avatar_friend) ?></a>
	        </div>
	      <?php endwhile; ?>
	    <?php else:?>
	    <div class="to-gro">  <p><?php _e('No friends!','bp-pop')?></p> </div>
	    <?php endif;?>
	    </div>
		        </div>            
            </div>
	</div>          
<?php wp_die( );
}
add_action ('wp_ajax_bp_pop_member', 'bp_pop_member');
add_action( 'wp_ajax_nopriv_bp_pop_member', 'bp_pop_member' );

function bp_pop_group($group_slug){
global $bp;
    $group_id = $_POST['group_slug'];
	$user = wp_get_current_user();
	$group = groups_get_group( array( 'group_id' => $group_id) );

	$avatar_options = array ( 
		'item_id' => $group_id,
		'object' => 'group',
		'type' => 'full',
		'width' => 90,
		'height' => 90
		); ?>
      <div class="cont">   
                <div class="g-hover-card"> 	 <div class="g-hover-card-img">
				<?php 
					if ( bp_group_use_cover_image_header() )  {
					$cover_src = bp_attachments_get_attachment( 'url', array(
		                'object_dir' => 'groups',
	                	'item_id'    => $group_id,
	                     ) );					
				}else{
					$cover_src=groups_get_groupmeta($group_id, 'bp_cover_group',true);	
				}
					 
                if(!empty($cover_src)){?>
               <style type="text/css">
                .g-hover-card-img {
                background-image: url("<?php echo $cover_src;?>");
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
                }
                </style>
				<?php }else{
				$cover_group = get_option( 'bp_cover_group' );
				if(!empty($cover_group)){?>
				<style type="text/css">
                .g-hover-card-img {
                background-image: url("<?php echo $cover_group;?>");
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
                }
                </style>
				<?php }else{?>
				<style type="text/css">
                .g-hover-card-img {
                background-image: url("<?php echo BP_POP_URL ; ?>images/default-cover.jpg");
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
                }
                </style>
				<?php }?>				
				<?php }?>
				  </div>
                <div class="user-avatar-pop">
              <?php echo bp_pop_fetch_avatar( $avatar_options );?>       
			  </div>
                <div class="info-pop">
                   <div class="title-pop">
                      <a href="<?php echo bp_group_permalink( $group );?>"><?php echo $group->name;?></a>				  
                   </div>	
				</div>   
				   <div class="info-user-pop"> 
				     <div class="to-mem">
                         <h2><?php echo groups_get_total_member_count( $group_id )?></h2>
				         <p><?php _e('Members','bp-pop')?></p>
						 
				    </div>
				  	  
		<div class="to-gro" style="width:140px;"> 
			<div class="bottom-pop">
<?php if ( bp_get_group_join_button( $group ) ):
			 ?>   	       
                     <ul> 		
					 <!--<li><a class="min-avatar" href="<?php echo bp_get_group_creator_permalink( $group );?>"><?php echo bp_group_creator_avatar( $group );?></a></li>-->
								<?php if ( groups_is_user_member( $user->ID, $group_id ) ) : ?>
							<li><a title="Leave Group" class="btpop" href="<?php echo wp_nonce_url( bp_get_group_permalink( $group ) . 'leave-group', 'groups_leave_group' );?>" title="<?php _e('leave group','bp-pop')?>"><i class="pop-font pop-leave"></i></a></li>
							<?php else: ?>
							<?php if ( 'public' == $group->status ) : ?>
							<li><a title="Join Group" class="btpop" href="<?php echo wp_nonce_url( bp_get_group_permalink( $group ) . 'join', 'groups_join_group' );?>" title="<?php _e('Join group','bp-pop')?>"><i class="pop-font pop-plus"></i></a></li>
                            <?php
                            elseif ( 'private' == $group->status ) :?>
							<li><a title="Request Membership" class="btpop" href="<?php echo wp_nonce_url( bp_get_group_permalink( $group ) . 'request-membership', 'groups_request_membership' );?>" title="<?php _e('Request membership','bp-pop')?>"><i class="pop-font pop-Request"></i></a></li>
							<?php endif;
							 endif;
			                if ( 'public' == $group->status ) { ?>
							<li><span class="btpop"><i class="pop-font pop-globe"></i></span></li>
                            <?php
                            } elseif ( 'hidden' == $group->status ) { ?>
							<li><span class="btpop"><i class="pop-font pop-eye"></i></span></li>
                            <?php
	                    	} elseif ( 'private' == $group->status ) { ?>
							<li ><span class="btpop"><i class="pop-font pop-lock"></i></span></li>
                            <?php
		                    }?>						
	                 </ul>	
<?php endif;?>
		 </div>	
		<p><?php echo substr( $group->description, 0, 70 );?>...</p> </div>       
		                </div>    
                       </div>
			          </div>   
<?php
	wp_die( );
}
add_action ('wp_ajax_bp_pop_group', 'bp_pop_group');
add_action( 'wp_ajax_nopriv_bp_pop_group', 'bp_pop_group' );


function bp_pop_fetch_avatar( $args = '' ) {
	    global $bp;
	   return bp_core_fetch_avatar( $args );	
       extract( $args );
	
}

function bp_pop_get_user_displayname( $user_id ) {
	if ( is_plugin_active( 'buddypress/bp-loader.php' ) ) 
		return bp_core_get_user_displayname( $user_id );

	$user_info = get_userdata( $user_id );
	return $user_info->user_nicename;
}
function  bp_pop_is_user_online($user_id, $time=6)
	{
		global $wpdb;
		$sql = $wpdb->prepare( "SELECT u.user_login FROM $wpdb->users u JOIN $wpdb->usermeta um ON um.user_id = u.ID
			WHERE u.ID = %d
			AND um.meta_key = 'last_activity'
			AND DATE_ADD( um.meta_value, INTERVAL %d MINUTE ) >= UTC_TIMESTAMP()", $user_id, $time);
		$user_login = $wpdb->get_var( $sql );
		if(isset($user_login) && $user_login !=""){
		return true;
		}else {
		return false;
		}
	}
function  bp_pop_get_online_status($user_id) {
		$output = '';
		if (bp_pop_is_user_online($user_id)) {
			$output .= '<div class="online-status online-pop"></div>';
		} else {
			$output .= '<div class="online-status"></div>';
		}
		return $output;
	}
		
function bp_pop_id_add( $text, $params ) {
    if ( $params['object'] == 'user') {
        return preg_replace('~<img (.+?) />~i', "<img $1 id='avatar-user'   />", $text );
    }elseif( $params['object'] == 'group') {        
        return preg_replace('~<img (.+?) />~i', "<img $1 id='avatar-group' />", $text );
    }else{
        return $text;
    }
}
add_filter('bp_core_fetch_avatar', 'bp_pop_id_add', 10, 2 );