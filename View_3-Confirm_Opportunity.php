<?php
class Confirm_Opportunity extends Default_Profile {

    public $custom_type = 'view_3';       
    public function __construct() {
        // Create a new Post Type
         add_action( 'init', array($this, 'create_post_type' ));
        // Call the Units_Metabox when showing the post
        add_action( 'add_meta_boxes', array($this, 'savi_con_opp_init_metabox' ));  
        add_action('post_submitbox_misc_actions', array('Confirm_Opportunity', 'savi_con_opp_add_button')); // add button
		  add_filter('redirect_post_location', array('Confirm_Opportunity', 'savi_con_opp_redirect'), '99'); // change redirect URL
		  add_action('admin_notices', array('Confirm_Opportunity', 'savi_con_opp_saved_notice'));    
        add_action( 'admin_head-post.php', array($this,'savi_con_opp_posttype_admin_css'));   
        // set custom class for display back ground color depending upon the reminder flag       
        add_filter( 'post_class', array($this,'savi_con_opp_filter_post_class') );  
        // add custom css styles sheet for displaying reminder flag background color
         add_action( 'admin_enqueue_scripts', array($this,'savi_con_opp_reminder_flag_css'), 10, 1 ); 
        //drive from Default_Profile class 
        add_action( 'init', array($this, 'generate_unserialize' ));
        //drive from Default_Profile class
        add_action('add_meta_boxes',  array($this,'savi_add_default_profile_meta_box'),10,2);
        // Call the Save Post when av_unit is being saved
        add_action( 'save_post', array($this, 'savi_con_opp_save_data' ));
    }
    // Create a new Post Type 
    public function create_post_type() {
        register_post_type( $this->custom_type,
            array(
                'labels' => array(
                    'name' => __( 'Opportunities Selection' ),
                    'singular_name' => __( 'Opportunity Selection' )
                ),
                'supports' => array( 'title'),
                'public' => true,
				'menu_icon' => 'dashicons-yes',
                'has_archive' => true,
                'rewrite' => array('slug' => $this->custom_type),
                 'exclude_from_search' => false,
                 'publicly_queryable'=>false
            )
        ); 
        
    }
		function savi_con_opp_filter_post_class( $classes ) {
  
       global $post;
       		
		 if( $post->post_type == 'view_3' ){

       	$reminder_flag = get_post_meta(get_the_ID(),'reminder_flag',true);         
    	   switch($reminder_flag) {
				case '1':
        		  $my_post_class = 'flag_1';
        		  break;
     		  case '2':
          		$my_post_class = 'flag_2';
         		 break;   
     		  case '3':
         		 $my_post_class = 'flag_3';
         		 break;
          default:return $classes;		    
       	} 
       }	 
        // Add it to the array of post classes
        $classes[] = $my_post_class;
    
        // Return the array
        return $classes;
    }
     function savi_con_opp_reminder_flag_css(){
		$plugin_dir = plugins_url();
		wp_enqueue_style( 'savi-reminder-flag-css', $plugin_dir . '/savi-plugin/css/reminder-flag.css', array(), 1.0 );
	}
    public function savi_con_opp_show_metabox($post) {
    
        $post_id = $_GET['post'];//$wp_query->post->ID;
        $order_user_id =  get_post_meta($post_id,'user_id',true);  
        $orderOpportunitiesMeta = get_post_meta( $post_id, 'ordered_opportunity', true );  
        $allorderOpportunities = $orderOpportunitiesMeta;
        if (sizeof($allorderOpportunities) > 0 && is_array($allorderOpportunities)) {
        		foreach($allorderOpportunities as $orderOpportunity) {
        	        	$orderOpportunitiesID = $orderOpportunity;
	        		$opportunity_status = get_post_meta( $orderOpportunitiesID, 'opportunity_status', true );
        			$postion_of_profile = "";	  
        		     if( $opportunity_status != "closed"  ) { 
        		     /* check the no of opportunity with placed opportunity 
        		         if no of opportunity equal to placed opporunity then this opportunity not available */ 	
        		          
        		        $ordered_new_volunteer = get_post_meta($orderOpportunitiesID,'ordered_new_volunteer',true);
        		        //echo "<pre>",print_r($ordered_new_volunteer),"</pre>";
        		        if(count($ordered_new_volunteer) > 0 ){
        		       		foreach($ordered_new_volunteer as $key => $vol_user_id){
							
							if($vol_user_id == $order_user_id ):
							 $postion_of_profile = $key+1;
							endif; 
					   }
				}
                        if( $postion_of_profile !="" ) {
							$mentor_pref_position  = $postion_of_profile;
  							$orderOpportunitiesValue = get_the_title($orderOpportunitiesID);
  							$unitID = get_post_meta($orderOpportunitiesID,'av_unit',true);
  							$unitNAME = get_the_title($unitID);
                            $SelectOpportunityHTML.= "<input type='radio' name='placed_opportunity' 
                                                      value='$orderOpportunitiesID'>&nbsp;&nbsp;
                                                       $orderOpportunitiesValue ( $unitNAME pref: $mentor_pref_position )<br>";
						} else {
                             $orderOpportunitiesValue = get_the_title($orderOpportunitiesID); 
                             $SelectOpportunityHTML.= "<label style=\"margin-left:25px;\">&nbsp;&nbsp;$orderOpportunitiesValue</label><br>";   							
  						  }
                        
                     }   
                                              
           }
       } 
       echo $SelectOpportunityHTML; 
       
   }
   public function savi_con_opp_init_metabox() {
       add_meta_box( 'confirm_opportunity', "Opportunities associated profiles"
                   , array($this,'savi_con_opp_show_metabox'), $this->custom_type, 'normal', 'low'); 
   }    
    // Saving the meta data when saving the post
   
    
    public static function savi_con_opp_add_button() {

      global $post;
       
		 if( $post->post_type != 'view_3' ) return $post;
				
		// work out if post is published or not
		$status = get_post_status($_GET['post']);
		// if the post is already published, label the button as "update"
		$button_label = ($status == 'publish' || $status == 'private') ? 'Confirm Opportunity' : 'Publish and Close';

		// TODO: fix duplicated IDs
		?>

		<div id="major-publishing-actions" style="overflow:hidden">
			<div id="publishing-action">
				<input type="hidden" name="saveclose_referer" value="<?php echo $_SERVER['HTTP_REFERER'] ?>">
				<input type="submit" tabindex="5" value="<?php echo $button_label ?>" class="button-primary" id="custom" name="save-close">
			</div>
		</div>

		<?php
	}

	/**
	 * Generates the URL to redirect to
	 * @param $location The redirect location (we're overwriting this)
	 * @return string The new URL to redirect to, which should be the post listing page of the relevant post type
	 */
	public static function savi_con_opp_redirect($location) {
       global $post ;
       $needsVisa=false;
       if( $post->post_type != "view_3" ) return $location;
		if (!isset($_POST['save-close'])) return $location;

		// determine the post status (private if selected, else published)
		//$post_status = ($_POST['post_status'] == 'private') ? 'private' : 'publish';

		// we want to publish new posts
		$post_status = 'publish';

		// if the post was published, allow the status to be changed to something else (eg. draft)
		if ($_POST['original_post_status'] == 'publish' || $_POST['original_post_status'] == 'private') {
			$post_status = $_POST['post_status'];
		}
		// handle private post visibility
		if ($_POST['post_status'] == 'private') {
			$post_status = 'private';
		}
      
		wp_update_post(array('ID' => $_POST['post_ID'], 'post_status' => $post_status));
		
		$placed_opportunityID = $_POST['placed_opportunity'];
		
	 if(isset($placed_opportunityID) && !empty($placed_opportunityID)) {	

			$placed_opportunitycount = (int)get_post_meta( $placed_opportunityID, 'placed_opportunities', true );
			$newcount  =($placed_opportunitycount > 0 )?($placed_opportunitycount+1):1;       
			update_post_meta($placed_opportunityID,'placed_opportunities',$newcount);
			update_post_meta($_POST['post_ID'],'volunteer_opportunity',$placed_opportunityID);
			$is_visa_required = get_post_meta( $_POST['post_ID'], 'savi_views_stay-details_special-visa', true );  
			//$view_type = (strtolower($is_visa_required) == "yes")?"'view_5'":"'view_7'";
			if(strtolower($is_visa_required) == "no"): // if country is india this post meta field is empty
				$view_type = "view_7";
			else:
				$intership = get_post_meta( $_POST['post_ID'], 'savi_views_education-details_intership', true );  
				$time_of_duration = ( int ) get_post_meta( $_POST['post_ID'], 'savi_views_stay-details_duration', true );  
			    if( (strtolower($intership) == "no" && $time_of_duration < 6) ):
			       // checks for tourist VISA for volunteer
					$view_type = "view_7";
			    else:
			        $view_type = "view_5";
			        $needsVisa = true;
			    endif;
			endif; 
			
		wp_update_post(array('ID' => $_POST['post_ID'], 'post_type' => $view_type));
			//update_post_meta($post_id,$view_type.'_created',current_time( 'mysql' ));
			$user_id= get_post_meta($_POST['post_ID'],'user_id',true);
			//Confirm_Opportunity::savi_confirm_opportunity_email($user_id,$placed_opportunityID);
			$mentorEmail = get_post_meta($placed_opportunityID,'contactEmail',true);
			$user_info = get_userdata( $user_id );
			$clientEmail = $user_info->user_email;
			$site_url = get_bloginfo('wpurl');
		    $htmlmessage = FRS_Custom_Bulk_Action::saviGetTemplate($_POST['post_ID'],$user_id,'',$placed_opportunityID,'view_3', $needsVisa);
		     
		    add_filter( 'wp_mail_content_type', array(FRS_Custom_Bulk_Action,'set_html_content_type') );
		     
		  	$blog_title = get_bloginfo('name');
			$option_name = 'Opportunity_Confirmation';
			$templatePage = (int) get_option($option_name);
			
			$TemplateTitle = get_the_title($templatePage);
			
			$subject = $blog_title." - ".$TemplateTitle;
			$test_mentor_email = get_option("test_mentor_email");
			$headers[]='From: Auroville Learning Network <'. get_option( 'admin_email' ).'>' . "\r\n";
			
			if($test_mentor_email!=""){
				$headers[] = 'Cc:volunteer.email@example.com';
				wp_mail($test_mentor_email, $subject, $htmlmessage,$headers);
			  }else{
				$headers[] = 'Cc:'.$clientEmail;
				 wp_mail($mentorEmail, $subject, $htmlmessage,$headers); 
				 
			}
			
     }
   	
		// if we have an HTTP referer saved, and it's a post listing page, redirect back to that (maintains pagination, filters, etc.)
		if (isset($_POST['saveclose_referer']) && strstr($_POST['saveclose_referer'], 'edit.php') !== false) {
			if (strstr($_POST['saveclose_referer'], 'lbsmessage') === false) {
				if (strstr($_POST['saveclose_referer'], '?') === false) {
					return $_POST['saveclose_referer'] . '?lbsmessage=1';
				}
				return $_POST['saveclose_referer'] . '&lbsmessage=1';
			}
			return $_POST['saveclose_referer'];
		}
		// no referer saved, just redirect back to the main post listing page for the post type
		else {
			return get_admin_url() . 'edit.php?lbsmessage=1&post_type=' . $_POST['post_type'];
		}
	}

	/**
	 * Display a notice on the post listing page to inform the user that a post was saved
	 */
	public static function savi_con_opp_saved_notice() {
			global $post ;
    	 if( $post->post_type != "view_3" ) return ;
		if (isset($_GET['lbsmessage'])) {
			?>
			<div class="updated">
				<p>Post saved</p>
			</div>
			<?php
		}
	}
   function savi_con_opp_posttype_admin_css() {
    	global $post_type;
    	 if( $post_type != 'view_3' ) return;
    	$post_types = array( 'view_3');
    	if(in_array($post_type, $post_types))
    	/*	echo '<style type="text/css">
    		  #post-preview,#publishing-action #publish { display: none; } 
    	     </style>';*/
		?>
	   	<script type="text/javascript" >
   			jQuery("#title").attr("readonly",true);
   		</script>
		<?php      
       }  
    function savi_confirm_opportunity_email($user_id,$opprtunity_id) {
         $opportunity_name = get_the_title($opprtunity_id);
      	$site_url = get_bloginfo('wpurl');
		$user_info = get_userdata( $user_id );
		$to = $user_info->user_email;
		$subject = "Confirm Opportunity: ".$site_url."";
		$message = "Hello " .$user_info->display_name .
			   "\nYour have been assigned ".$opportunity_name. " 
				!\n\nThank you for visiting\n ".$site_url."";
		//wp_mail( $to, $subject, $message);
		$mail_headers[]='From: Auroville Learning Network <'. get_option( 'admin_email' ).'>' . "\r\n";
		//Check if this is a test site
		$test_mentor_email = get_option("test_mentor_email");
		if($test_mentor_email!="") wp_mail($test_mentor_email, $subject, $message, $mail_headers);
		else wp_mail($to, $subject, $message, $mail_headers);
       }    
         // Saving the meta data when saving the post
   public function savi_con_opp_save_data( $post_id ) {
      global $post ;
       if( $post->post_type != $this->custom_type ) return $post_id;

        
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
         //   return $post_id;
        
        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) )
                return $post_id;
        
        } else {

            if ( ! current_user_can( 'edit_post', $post_id ) )
                return $post_id;
        }
         
            $savi_views_admin_details_admin_notes = array();
            $saved_savi_views_admin_details_admin_notes = array();
            $saved_savi_views_admin_details_admin_notes = get_post_meta($post->ID, 'savi_views_admin-details_admin-notes', true);
       		$admin_note_content = sanitize_text_field($_POST['savi_views_admin_details_admin_notes']);
 				$admin_note_author = get_current_user_id();
 				$admin_note_time = date('Y-m-d H:i:s');
       		if(trim($admin_note_content)!=""){
               $savi_views_admin_details_admin_notes = array (  "admin_note_author" => $admin_note_author,
               									"admin_note_time" =>$admin_note_time,
               									"admin_note_content" =>$admin_note_content);
              if(is_array($saved_savi_views_admin_details_admin_notes) && sizeof($saved_savi_views_admin_details_admin_notes)>0):  									
             	  $saved_savi_views_admin_details_admin_notes[]  = $savi_views_admin_details_admin_notes;
             	else:
             	  $saved_savi_views_admin_details_admin_notes[0]  = $savi_views_admin_details_admin_notes;
             	endif;  
                update_post_meta( $post->ID, 'savi_views_admin-details_admin-notes', $saved_savi_views_admin_details_admin_notes);
            }
       
    }  
 }
?>
