<?php
class FRS_Custom_Bulk_Action {
		
		public function __construct() {
			
			//if(is_admin()) {
				// admin actions/filters
				add_action('admin_footer-edit.php', array(&$this, 'custom_bulk_admin_footer'));
				add_action('load-edit.php',         array(&$this, 'custom_bulk_action'));
				add_action('admin_notices',         array(&$this, 'custom_bulk_admin_notices'));
				add_action('restrict_manage_posts', array(&$this,'savi_manage_country'));
				add_action('restrict_manage_posts', array(&$this,'savi_views_by_filters'));
				add_action( 'admin_action_inline-approval', array(&$this,'savi_admin_inline_approval') );
            // Add the filter for searching selected country 
				add_filter( 'parse_query', array(&$this,'savi_admin_posts_filter'));
				add_action('admin_menu', array(&$this,'hd_add_box'));
				add_action('admin_head',array(&$this,'hd_add_buttons'));
				add_filter('bulk_actions-edit-view_5', array(&$this,'savi_views_CBA_remove') );
				add_filter('bulk_actions-edit-view_6', array(&$this,'savi_views_CBA_remove') );
				add_filter('bulk_actions-edit-view_7', array(&$this,'savi_views_CBA_remove') );   
				
            add_action('admin_menu', array(&$this,'savi_remove_menus_for_editor'));  
            
        // }
		}
		public function savi_admin_inline_approval() {
			// Handle request then generate response using echo or leaving PHP and using HTML
			global $typenow,$wpdb;
			$post_type = $typenow;
			$wp_list_table = _get_list_table('WP_Posts_List_Table');  
			$action ="approval";
			$sendback = remove_query_arg( array($action, 'untrashed', 'deleted', 'ids')
										 , wp_get_referer() );
			if ( ! $sendback )
				$sendback = admin_url( "edit.php?post_type=$post_type" );
				
			$pagenum = $wp_list_table->get_pagenum();
			$sendback = add_query_arg( 'paged', $pagenum, $sendback );
			if($post_type =="view_1"): 
				$this->savi_view_1_update($_REQUEST['post']);
			endif;
			$sendback = add_query_arg( array('inline-approval' => 1,'ids' => $_REQUEST['post'] ), $sendback );
			$sendback = remove_query_arg( array('action', 'action2', 'tags_input', 'post_author'
									, 'comment_status', 'ping_status', '_status'
									,  'post', 'bulk_edit', 'post_view'), $sendback );	
			wp_redirect($sendback);
			die;
		}
		public function hd_add_box() {

			global $submenu;
			unset($submenu['edit.php?post_type=view_0'][10]);
			unset($submenu['edit.php?post_type=view_1'][10]);
			unset($submenu['edit.php?post_type=view_2'][10]);
			unset($submenu['edit.php?post_type=view_3'][10]);
			unset($submenu['edit.php?post_type=view_4'][10]); 
			unset($submenu['edit.php?post_type=view_5'][10]); 
			unset($submenu['edit.php?post_type=view_6'][10]); 
			unset($submenu['edit.php?post_type=view_7'][10]); 
			unset($submenu['edit.php?post_type=view_8'][10]);  
		}	
		function hd_add_buttons() {
			global $pagenow;
			$custom_post_type = array('view_0','view_1','view_2','view_3','view_4','view_5','view_6','view_7','view_8');
			if($pagenow == 'edit.php' && in_array($_GET['post_type'],$custom_post_type )) {
				 echo '<style type="text/css">.add-new-h2{ display:none; }</style>';
			}  
		}
	  function custom_bulk_admin_footer() {
			global $post_type,$current_user;
                        $user_role = $current_user->roles[0];
                        if(in_array($user_role,array('administrator'))){
                          $is_access_allowed ='yes';
                        }else{
                          $is_access_allowed ='no';  
                        }

			$custom_post_type = array('view_0','view_1','view_2','view_3','view_4','view_5','view_6','view_7','view_8');
         switch($post_type) {
               case 'view_0': 
                    $newaction ='confirmRegistration';         
                    $text ="Confirm Registration";
                    break;
               case 'view_1':
                    $newaction ='Approval';         
                    $text ="Approve the User"; 
                    break;
               case 'view_2':
                    $newaction ='SendEmailToVolunteer';         
                    $text ="Send Email to Volunteer"; 
                    break;
               case 'view_3':
                    $newaction ='RevertStatus';         
                    $text ="Revert the Status"; 
                    break;
               case 'view_4':
                   $newaction ='ResetVolunteer';         
                   $text ="Reset Volunteer"; 
                   break;            
            }
            	
            ?>
            <script type="text/javascript">
                        var view_type ="<?php echo $post_type ?>";
                       
						jQuery(document).ready(function() {
						<?php if(in_array($post_type,$custom_post_type ) && $_REQUEST['post_status']!="trash") :?>
							jQuery('<option>').val('<?php echo $newaction ?>').text('<?php echo $text ?>')
							                                             .appendTo("select[name='action']");
							jQuery('<option>').val('<?php echo $newaction ?>').text('<?php echo $text ?>')
							                                             .appendTo("select[name='action2']");
			                              <?php if($is_access_allowed =='yes'):?>                
							jQuery('<option>').val('delete').text('Delete Permanently')
							                                             .appendTo("select[name='action']");
							jQuery('<option>').val('delete').text('Delete Permanently')
									  .appendTo("select[name='action2']");   
                                                        jQuery("select[name='m'] option[value=0]").text("Application date");  
                                                        
                                                     <?php endif;?>
						<?php endif;?>						                                    
                           jQuery(".row-title").removeAttr("href")
                                         .css({"cursor":"default","color":"#555555","font-weight":"normal"});
                           if(view_type == "view_0" || view_type == "view_1"
                             || view_type == "view_2" || view_type == "view_3" || view_type == "view_4" ){
                                jQuery("option[value='edit']").remove();	
                           }     					
                           if(view_type == "view_0"|| view_type == "view_1" || view_type == "view_2" || view_type == "view_3"
                                                                            || view_type == "view_4")
                                jQuery("option[value='trash']").remove();						 
						
                        });
					</script>
          
       <?php
     }
	  function custom_bulk_action() {
			global $typenow,$wpdb;
			$post_type = $typenow;
			$custom_post_type = array('view_0','view_1','view_2','view_3','view_4','view_5','view_6','view_7','view_8');
			if(in_array($post_type,$custom_post_type ) && $_REQUEST['post_status']!="trash") {
					$wp_list_table = _get_list_table('WP_Posts_List_Table');  
					// depending on your resource type this could be WP_Users_List_Table, WP_Comments_List_Table, etc
		         
		           $action = $wp_list_table->current_action();
	
					$allowed_actions = array("confirmRegistration","Approval","SendEmailToVolunteer","RevertStatus","ResetVolunteer");
					if(!in_array($action, $allowed_actions)) return;
					// security check
				
					check_admin_referer('bulk-posts');

					// make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'

					if(isset($_REQUEST['post'])) {
							$post_ids = array_map('intval', $_REQUEST['post']);
					}
			   	if(empty($post_ids)) return;
					// this is based on wp-admin/edit.php
					
				
					$sendback = remove_query_arg( array($action, 'untrashed', 'deleted', 'ids')
					                             , wp_get_referer() );
				   if ( ! $sendback )
							$sendback = admin_url( "edit.php?post_type=$post_type" );
					$pagenum = $wp_list_table->get_pagenum();
					$sendback = add_query_arg( 'paged', $pagenum, $sendback );
					switch($action) {
						case 'confirmRegistration':
                  case 'Approval':
                  case 'SendEmailToVolunteer':
                  case 'RevertStatus':
                  case 'ResetVolunteer': 						 
							$count = 0;
							foreach( $post_ids as $post_id ) {
							   if($post_type =="view_0"): 	
						   			$this->savi_view_0_update($post_id);	   
                        endif;						   	
						   	if($post_type =="view_1"): 
						     	  	   $this->savi_view_1_update($post_id);
                        endif; 						      
						      if($post_type =="view_2") :
						     	   $this->savi_view_2_update($post_id);
                        endif;
                        if($post_type =="view_3") :
                         $this->savi_view_3_update($post_id);
						     	endif;
						     	if($post_type =="view_4") :
                         $this->savi_view_4_update($post_id);
						     	endif;
                        $count++;                                                     
                     }
                     $sendback = add_query_arg( array($action => $count,'ids' => join(',', $post_ids) ), $sendback );	                  
 						   break;
 						   default:return;
              }
                      
			
      	$sendback = remove_query_arg( array('action', 'action2', 'tags_input', 'post_author'
				                            , 'comment_status', 'ping_status', '_status'
				                            ,  'post', 'bulk_edit', 'post_view'), $sendback );
			wp_redirect($sendback);
			exit();
		 }	
	  }
	  function custom_bulk_admin_notices() {
	  		global $post_type, $pagenow;
	
         $custom_post_type = array('view_0','view_1','view_2','view_3','view_4');
         
         if(isset($_REQUEST['confirmRegistration'])) {
             $newaction ='confirmRegistration';         
             $content ="Confirmed Registration";
         }
         if(isset($_REQUEST['Approval']) ) {
             $newaction ='Approval';         
             $content ="Approved";
             
         }
         if(isset($_REQUEST['SendEmailToVolunteer'])) {
             $newaction ='SendEmailToVolunteer';         
             $content ="Mail Send to the Volunteers";
         }
         if(isset($_REQUEST['RevertStatus'])) {
             $newaction ='RevertStatus';         
             $content ="Revert the voluteers view_3 to view_2";
         }
         if(isset($_REQUEST['ResetVolunteer'])) {
             $newaction ='ResetVolunteer';         
             $content ="Reset the Volunteers to active status";
         } 
         if($pagenow == 'edit.php' && in_array($post_type,$custom_post_type ) 
			                          && $_REQUEST['post_status']!="trash" 
			                          && isset($_REQUEST[$newaction]) 
			                          && (int) $_REQUEST[$newaction]) {
					$message = sprintf( _n( 'Post '.$content, '%s posts '.$content
				                  , $_REQUEST[$newaction] )
				                   , number_format_i18n( $_REQUEST[$newaction] ) );
					echo "<div class=\"updated\"><p>{$message}</p></div>";
			}
			 if(isset($_REQUEST['inline-approval'])) :
			    $newaction ='inline-approval';         
                $content ="Approved";
				$message = sprintf( _n( 'Post '.$content, '%s posts '.$content
				                  , $_REQUEST[$newaction] )
				                   , number_format_i18n( $_REQUEST[$newaction]) );
					echo "<div class=\"updated\"><p>{$message}</p></div>";
			endif;		
		}
		function create_custom_user($postID) {
			global $wpdb;
			$clientEmail= get_post_meta($postID, 'savi_views_contact-details_email', true);
   		
			$clientName = get_the_title($postID) ; 
			if (email_exists($clientEmail) == false ) :
        			/*if ($clientName ) {
        			    if(username_exists( $clientName )){
                                        $clientName = $clientName.$postID;
                                    }     
                                }else{
                                    $fname = get_post_meta($postID, 'savi_views_contact-details_first-name', true);
                                    $lname = get_post_meta($postID, 'savi_views_contact-details_last-name', true);  
                                    $clientName =  $fname." ".$lname.$postID;
                                }*/
                                $parts=explode('@',$clientEmail);
                	  	$pos_terminate = strpos($parts[1],".");
                	  	$current_increment_id = $wpdb->get_var("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".DB_NAME."' AND TABLE_NAME = 'wp_users'" );
                		//$clientName = $parts[0].substr($parts[1],0,$pos_terminate);
                		
                		$clientName = "savi".$current_increment_id;
          		$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
	       		$user_id = wp_create_user( $clientName, $random_password, $clientEmail );
	       		$fname = get_post_meta($postID, 'savi_views_contact-details_first-name', true);
                $lname = get_post_meta($postID, 'savi_views_contact-details_last-name', true);  
                $Name =  $fname." ".$lname;
	       		wp_update_user( array( 'ID' => $user_id, 'user_nicename' => $clientName ,"display_name" => $Name) );
	       		$wp_user_object = new WP_User($user_id);
          		$wp_user_object->set_role('subscriber'); 
          		add_user_meta( $user_id, 'status', 'V1'); 
          		add_user_meta( $user_id, 'profile_post_id', $postID); 
				add_user_meta( $user_id, 'savi_role', 'volunteers');          	
          		$htmlmessage = $this->saviGetTemplate($postID,$user_id,$random_password,'','view_0');
          		
          		$site_url = get_bloginfo('wpurl');
          		add_filter( 'wp_mail_content_type', array($this,'set_html_content_type') );
          		$blog_title = get_bloginfo('name');
          		$option_name = 'Inital_Enquiry';
				$templatePage = (int) get_option($option_name);
				$TemplateTitle = get_the_title($templatePage);
          		$subject = $blog_title." - ".$TemplateTitle;
				$mail_headers[]='From: Auroville Learning Network <'. get_option( 'admin_email' ).'>' . "\r\n";
				//Check if this is a test site
				$test_mentor_email = get_option("test_mentor_email");
				if($test_mentor_email!="") wp_mail($test_mentor_email, $subject, $htmlmessage,$mail_headers);
				else wp_mail($clientEmail, $subject, $htmlmessage,$mail_headers);
          	
          		return array(
		                	'user_id'	    => $user_id,
			                'passwordNew'	=> $random_password );
                       
        endif;          	
      }
    
      function set_html_content_type() {

			return 'text/html';
      }
   
    function saviGetTemplate($postID ='',$user_id ='',$random_password ='',$oppID ='',$post_type ='', $requires_visa=false) {
 	     global $wpdb;
    	 
    	 /*---------- new code for shortcodes----------*/
    	  switch($post_type){
			 
			case 'view_0':
			   $option_name = 'Inital_Enquiry';
			   break;
			case 'view_1':
			   $option_name = 'Profile_Reviews';
			   break;   
			case 'view_2':
			   $option_name = 'Opportunity_Selection';
			   break;
		   case 'view_2_profile_offer':
			   $option_name = 'Volunteer_Profile_Offer';
			   break;	      
			   
			case 'view_3':
			   $option_name = 'Opportunity_Confirmation';
			   break;   
			case 'view_6':
			   $option_name = 'Pre_Visa';
			   break; 
			case 'view_6_instruction_for_auroville_visa_application':
			   $option_name = 'Instruction_for_Auroville_VISA_Application';
			   break;      
			case 'view_7':
			   $option_name = 'Induction_Instructions';
			   break;   
			 
		 }
    	 $templatePage = (int) get_option($option_name);
    	 $printTemplate = get_post($templatePage);
    	 $content = $printTemplate->post_content;
    	 $savi_shortcodes = array();
		 foreach($GLOBALS['shortcode_tags'] as $keys => $values){
			if( substr( $keys, 0, 4 ) === "SAVI" ) $savi_shortcodes[] = $keys;
		 }
	 	$default_atts = 'profile_id="'.$postID.'" user_id="'.$user_id.'" user_pwd="'.$random_password.'" need_visa=="'.$requires_visa.'" post_type=="'.$post_type.'" opportunity_id="'.$oppID.'"';
		foreach($savi_shortcodes as $saviCodes){
			if (has_shortcode($content, $saviCodes)) 
				$content = str_replace('['.$saviCodes,'['.$saviCodes.' '.$default_atts,$content);
		}
		$printContent = apply_filters('the_content',$content);
        return $printContent;
    	 
      
    }
      function savi_new_user_notification($user_id,$password) {
   
      	$site_url = get_bloginfo('wpurl');
      	$user_info = get_userdata( $user_id );
      	$to = $user_info->user_email;
      	$subject = "New User Created: ".$site_url."";
      	$message = "Hello " .$user_info->display_name .
                   "\nWe have Created New user name and password.
                    !\n\nThank you for visiting\n ".$site_url."\n\nUser Name : $user_info->display_name.password:$password w\n\n";
      	$mail_headers[]='From: Auroville Learning Network <'. get_option( 'admin_email' ).'>' . "\r\n";
		 //Check if this is a test site
		$test_mentor_email = get_option("test_mentor_email");
		if($test_mentor_email!="") wp_mail($test_mentor_email, $subject, $message, $mail_headers);
		else wp_mail($to, $subject, $message, $mail_headers);
   
   	}
   	function savi_email_to_volunteer($user_id,$interest_shown) {
   
      	$site_url = get_bloginfo('wpurl');
      	$user_info = get_userdata( $user_id );
      	$to = $user_info->user_email;
      	$subject = "Opportunities selected depending your skills: ".$site_url."";
      	$message = "Hello " .$user_info->display_name .
                   "\nWe have selected opportunities depending upon your skills please select best of three opportuniies. 
                    !\n\nThank you for visiting\n ".$site_url."\n\nThe Opportunites are as below\n\n".$interest_shown;
      	$mail_headers[]='From: Auroville Learning Network <'. get_option( 'admin_email' ).'>' . "\r\n";
		//Check if this is a test site
		$test_mentor_email = get_option("test_mentor_email");
		if($test_mentor_email!="") wp_mail($test_mentor_email, $subject, $message,$mail_headers);
		else wp_mail($to, $subject, $message,$mail_headers);
   
   	}
   	function savi_approval_email($user_id) {
      	$site_url = get_bloginfo('wpurl');
		$user_info = get_userdata( $user_id );
		$to = $user_info->user_email;
		$subject = "Profile Approved: ".$site_url."";
		$message = "Hello " .$user_info->display_name .
			   "\nYour profile has been Approved,So you can start accessing the opportunities page
				!\n\nThank you for visiting\n ".$site_url."";
		$mail_headers[]='From: Auroville Learning Network <'. get_option( 'admin_email' ).'>' . "\r\n";
		  //Check if this is a test site
		$test_mentor_email = get_option("test_mentor_email");
		if($test_mentor_email!="") wp_mail($test_mentor_email, $subject, $message,$mail_headers);
		else wp_mail($to, $subject, $message, $mail_headers);
     }
     function savi_manage_country() {
     		$custom_post_type = array('view_0','view_1','view_3','view_4','view_5','view_6','view_7','view_8');
	     	if (isset($_GET['post_type']) && post_type_exists($_GET['post_type']) 
	     		&& in_array(strtolower($_GET['post_type']),$custom_post_type)) {
	    			$this->savi_dropdown_custom_field(array(
                                                          'show_option_all'	=> 'Show all Country',
                                                          'show_option_none'	=> false,
		                                          'name'		=> 'country',
                                                          'selected'		=> !empty($_GET['country']) ? $_GET['country'] : "",
                                                          'include_selected'	=> false,
         					           'custom_filed' => 'savi_views_contact-details_nationality', 
         												'class' =>'',
	    												));
	    	}
   	} 
  function savi_views_by_filters() {
    global $typenow;
    global $wp_query;
    $custom_post_type = array('view_1','view_2','view_3','view_4','view_5','view_6','view_7','view_8');
	     	if (isset($_GET['post_type']) && post_type_exists($_GET['post_type']) 
	     		&& in_array(strtolower($_GET['post_type']),$custom_post_type)) {
        $taxonomy = 'savi_opp_cat_work_area';
        $work_area_taxonomy = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' =>  __("Show All {$work_area_taxonomy->label}"),
            'taxonomy'        =>  $taxonomy,
            'name'            =>  'work_area_filter',
            'orderby'         =>  'name',
            'selected'        =>  $_REQUEST['work_area_filter'],
            'hierarchical'    =>  true,
            'depth'           =>  3,
            'show_count'      =>  false, // Show # listings in parens
            'hide_empty'      =>  true, // Don't show businesses w/o listings
            'parent'          =>   '0'
         ));
    }
    if (isset($_GET['post_type']) && post_type_exists($_GET['post_type']) 
	     		&& ($_GET['post_type'] == 'view_2') ) {
	     		    
    ?>
    
              <select class="postform" id="duration_filter" name="duration_filter">
            	<option value="0" <?php if( $_REQUEST['duration_filter'] == "0" ): ?> selected='selected' <?php  endif;  ?>  class="level-0"> Show All Time of Stay</option>
            	<option value="2-4" <?php if( $_REQUEST['duration_filter'] == "2-4" ): ?> selected='selected' <?php  endif;  ?> class="level-0">2-4 months</option>
            	<option value="3-5" <?php if( $_REQUEST['duration_filter'] == "3-5" ): ?> selected='selected' <?php  endif;  ?> class="level-0">3-5 months</option>
            	<option value="4-6" <?php if( $_REQUEST['duration_filter'] == "4-6" ): ?> selected='selected' <?php  endif;  ?> class="level-0">4-6 months</option>
            	<option value="6+" <?php if( $_REQUEST['duration_filter'] == "6+" ): ?> selected='selected' <?php  endif;  ?> class="level-0">  >6 months </option>
              </select>
    
    <?php
         
         	
	}     		    
}
  function savi_dropdown_custom_field( $args = '' ) {

      	global $wpdb,$post_type;
      	$name = 'ADMIN_FILTER_FIELD_VALUE';
	   	$id = "ADMIN_FILTER_FIELD_VALUE";
      	$class = $args['class'];
      	$show_option_all = $args['show_option_all'];
      	$custom_filed  =  $args['custom_filed'];  
      	$sql = 'SELECT	DISTINCT meta_value 
				FROM	wp_postmeta wpm,
						wp_posts wp
				WHERE 	meta_key="'.$custom_filed.'"
				AND 	wp.ID = wpm.post_id
				AND		wp.post_type ="'.$post_type.'"
				ORDER BY 1';
      	$fields = $wpdb->get_results($sql, ARRAY_A);
      	$output = '';
      	if ( !empty($fields) || count($fields) > 1 ) {
	 			$output = "<select name='{$name}'{$id} class='$class'>\n";
         	if ( $show_option_all )
					$output .= "\t<option value=''>$show_option_all</option>\n";
      		$found_selected = false;
				foreach ( (array) $fields as $field ) {
					$field_value = $field['meta_value'];
					$field_value_id = $field['meta_value'];
					if(is_numeric($field_value)):
						 $country_sql="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 738 AND item_id=$field_value";
						 $country_results = $wpdb->get_results($country_sql,ARRAY_A);
						 $field_value = $country_results[0]['meta_value'];		
					endif;
					$selected = isset($_REQUEST['ADMIN_FILTER_FIELD_VALUE'])?$_REQUEST['ADMIN_FILTER_FIELD_VALUE']:"";
					$_selected = selected( $field_value, $selected, false );
					if ( $_selected )
						$found_selected = true;
						$output .= "\t<option value='$field_value_id' $_selected>" . $field_value . "</option>\n";
		     }
        	  $output .= "</select><input type='hidden' name='ADMIN_FILTER_FIELD_NAME' value='".$custom_filed."'/>";
        	  echo $output;
        }
      }
  		function savi_admin_posts_filter( $query ) {
  			global $pagenow,$wpdb;$qv = &$query->query_vars;
     		        if ( is_admin() && $pagenow=='edit.php' && isset($_GET['ADMIN_FILTER_FIELD_NAME']) &&
     	                                            $_GET['ADMIN_FILTER_FIELD_NAME'] != '') {
      			          $query->query_vars['meta_key'] = $_GET['ADMIN_FILTER_FIELD_NAME'];
      			          if (isset($_GET['ADMIN_FILTER_FIELD_VALUE']) && $_GET['ADMIN_FILTER_FIELD_VALUE'] != '')
        					$query->query_vars['meta_value'] = $_GET['ADMIN_FILTER_FIELD_VALUE'];
          	       }
          	       if ( is_admin() && $pagenow=='edit.php' &&
          	          isset($_REQUEST['work_area_filter']) && $_REQUEST['work_area_filter']!=0 ) {
						    $parent_workarea = $_REQUEST['work_area_filter'];
							$results=$wpdb->get_results("SELECT term_id from wp_term_taxonomy 
							where parent = $parent_workarea");
							$childIndex = 0;
							foreach ($results as $result)
							{
								$childTerm = $result->term_id;
								if ($childIndex > 0) 
									$search_children = $search_children.' or ';
							   
								$search_children = $search_children.' meta_value like'. '"%' .$childTerm.'%"';
								$childIndex++;
							}
							$post_ids = $wpdb->get_results("SELECT post_id from wp_frm_items where id in 
												(select item_id from wp_frm_item_metas where $search_children)" );
					        $new_postIDs = array();
					        $i = 0;
							foreach($post_ids as $postID) {
								
								$new_postIDs[$i++] = $postID->post_id;
								
						    }					 
                             
                                     $qv['post__in'] = $new_postIDs;
                                    
                  }
                  if( is_admin() && $pagenow=='edit.php' && isset( $_REQUEST['duration_filter'] ) && $_REQUEST['duration_filter'] !=0) :
                      
                      $duration_filter = $_REQUEST['duration_filter'] ;
                      switch($duration_filter){
                          
                          case '2-4': // 2-4 months
                          
                              $meta_value = array(2,3,4);
                              $meta_compare = 'IN';
                              
                          break; 
                          case '3-5': //3-5 months
                              $meta_value = array(3,4,5);
                              $meta_compare = 'IN';
                              
                          break; 
                          case '4-6': //4-6 months
                              $meta_value = array(4,5,6);
                              $meta_compare = 'IN';
                              
                          break;
                          case '6+': // Greather than 6 months
                              $meta_value = 6;
                              $meta_compare = '>';
                              
                          break; 
                          
                      }      
                     
                      $query->query_vars['meta_key'] = 'savi_views_stay-details_duration';
                      $query->query_vars['meta_value'] = $meta_value;
                      $query->query_vars['meta_compare'] = $meta_compare;
                      $query->query_vars['meta_type'] = NUMERIC;
                      
                      
                  endif;  
                   //$qv['post__in'] = array(2853);
  		}
		function savi_view_0_update($post_id) {
			global $wpdb;
  			$args= $this->create_custom_user($post_id);	
  			$userid = $args['user_id'];
  			
  			if(!empty($userid) && $userid > 0) {
      			$wpdb->query("update wp_posts set post_type='view_1', 
                           	             post_author= '$userid', post_status='publish'  where ID = $post_id");
            	           update_post_meta($post_id,'user_id',$userid);
            	           update_post_meta($post_id,'plain_password',$args['passwordNew']);
            	           update_post_meta($post_id,'view_1_created',current_time( 'mysql' ));
            	        
			   // We are updating the table wp_frm_items 
			   // user_id = $userid --> To assign the user to the form entry. The form uses this to show the profile information
			   // post_id = 0 --> This will ensure that the draft can be saved 
			   // is_draft = 1 --> This will ensure that the draft can be saved
			   // form_id = 19 --> This will move the form from view_0 to view_1
            	           $wpdb->query("UPDATE wp_frm_items set user_id = $userid, post_id = 0, is_draft = 1, form_id = 19 where post_id = $post_id");
			   $resultset = $wpdb->get_row("SELECT id FROM wp_frm_items WHERE user_id = $userid");
			   
			   // This is used to disable the editing / approval of the profiles that are not yet submitted
			   update_post_meta($post_id, 'profile_incomplete', 'yes');
			   
			   if ($resultset != null) {
				// The check is redundant. But if the resultset is null somehow, then you can assume that you are in deep shit
				$item_id = $resultset->id;
				
				// Transfer the information from the post meta into the item metas table so that the save as draft works correctly
				// Vrata had suggested another method of duplicating the form data. But considering that both touch the nose round the admin_head, the alternate method is just going around the head in a clockwise manner. 
				$wpdb->query("INSERT INTO wp_frm_item_metas (meta_value, field_id, item_id) 
						(SELECT DISTINCT meta_value, wp_frm_fields.id, $item_id
						    FROM wp_frm_fields, wp_postmeta
						    WHERE field_options LIKE CONCAT(  '%', meta_key,  '%' ) 
						    AND post_id = $post_id
						    AND form_id = 19)");
            	           }
           	           
      	      }
      	         
			/*else { 
		      	    $wpdb->query("update wp_posts set post_type='view_1',post_status='publish' where ID =".$post_id);
      	                }   */
  		}
  	   function savi_view_1_update($post_id) {
  	      global $wpdb; 
			/*$wpdb->query("update wp_posts set post_type='view_2',post_author='1'
                                                                             where ID =".$post_id);*/
                          $wpdb->query("update wp_posts set post_type='view_2'
                                                                             where ID =".$post_id);
                          update_post_meta($post_id,'view_2_created',current_time( 'mysql' ));                                                   
         $user_id= get_post_meta($post_id,'user_id',true);
         $user_info = get_userdata( $user_id );
      	 $clientEmail = $user_info->user_email;
         update_user_meta( $user_id,'status', 'V2', 'V1' );
         $site_url = get_bloginfo('wpurl');
         $htmlmessage = $this->saviGetTemplate($post_id,$user_id,'','','view_1');
         add_filter( 'wp_mail_content_type', array($this,'set_html_content_type') );
         $blog_title = get_bloginfo('name');
		 $option_name = 'Profile_Reviews';
		 $templatePage = (int) get_option($option_name);
		 $TemplateTitle = get_the_title($templatePage);
		 $subject = $blog_title." - ".$TemplateTitle;
		 $mail_headers[]='From: Auroville Learning Network <'. get_option( 'admin_email' ).'>' . "\r\n";
		 //Check if this is a test site
		$test_mentor_email = get_option("test_mentor_email");
		if($test_mentor_email!="") wp_mail($test_mentor_email, $subject, $htmlmessage,$mail_headers); //send all messages to this mail
		else wp_mail($clientEmail, $subject, $htmlmessage,$mail_headers);

  	   }
  
      function savi_view_2_update($post_id) {
         global $wpdb;
			$expressOpportunitiesMeta = get_post_meta( $post_id, 'express_opportunities', false );
			$allexpressOpportunities = $expressOpportunitiesMeta[0];
			$expressOpportunitiesValue = "";
			$user_id= get_post_meta($post_id,'user_id',true);
            if (sizeof($allexpressOpportunities) > 0 && is_array($allexpressOpportunities)) {
            	foreach($allexpressOpportunities as $key=>$expressOpportunity) {
               	  $expressOpportunitiesID = $expressOpportunity['express_opportunity'];
                  $expressOpportunitiesValue.= get_the_title($expressOpportunitiesID)."\n";
                  
                   /* ======================================================
                    when volunteer profile is moved from view_2 to view_3, 
                     Mail sent to opportunity contact person
                  ========================================================*/  
                  
					 $user_info = get_userdata( $user_id );
					 $clientEmail = $user_info->user_email;
					 $site_url = get_bloginfo('wpurl');
					 $mentorEmail = get_post_meta($expressOpportunitiesID,'contactEmail',true);
					
					 $htmlmessage = $this->saviGetTemplate($post_id,$user_id,'',$expressOpportunitiesID,'view_2_profile_offer');
					 add_filter( 'wp_mail_content_type', array($this,'set_html_content_type') );
					 $blog_title = get_bloginfo('name');
					 $option_name = 'Volunteer_Profile_Offer';
					 $templatePage = (int) get_option($option_name);
					 $TemplateTitle = get_the_title($templatePage);
					 $subject = $blog_title." - ".$TemplateTitle;
					 $mail_headers[]='From: Auroville Learning Network <'. get_option( 'admin_email' ).'>' . "\r\n";
					 //Check if this is a test site
					 $test_mentor_email = get_option("test_mentor_email");
					 if($test_mentor_email!=""){
						wp_mail($test_mentor_email, $subject, $htmlmessage,$mail_headers);
					  }else{
						wp_mail($mentorEmail, $subject, $htmlmessage,$mail_headers); 
						
					 }
                  /* ======================================================
                    when volunteer profile is moved from view_2 to view_3, 
                    for each opportunities in expressed_opportunity, store 
                    volunteer user ID in opportunity meta-field
                    array expressed_volunteer.
                  ========================================================*/  
                  
					$expressVolunteerMeta = get_post_meta($expressOpportunitiesID,'expressed_volunteer',true);
					$allexpressVolunteer = array();
					$newexpressVolunteers = array();
					$allexpressVolunteer = $expressVolunteerMeta;
					$arraySize = sizeof($allexpressVolunteer);   
					$isVolunteerUserID = false; 
					$volunteer_user_id  = $user_id;
                   if ($arraySize > 0 && is_array($allexpressVolunteer)) {
		               /* ======================================================
		           			checks if express volunteer having volunteer user ids
		           			 add the selected opportunity end of the array 
		           	  ========================================================*/ 		
            			$express_volunteerID = $volunteer_user_id;
            		   	foreach($allexpressVolunteer as $searchExpressVolunteer){
							if($searchExpressVolunteer == $express_volunteerID){
							  	$isVolunteerUserID = true;
							}
						}
						if(!$isVolunteerUserID){
							$allexpressVolunteer[] = $express_volunteerID;
					    }	
         			}
      	        else {
                    /* ======================================================
                      else express volunteer having no volunteer user ids add 
                      the selected opportunity begining of the array 
                    ========================================================*/ 	 
      	      		    $express_volunteerID = $volunteer_user_id;
					//	$allexpressVolunteerInfo = array (  "express_volunteerID" => $express_volunteerID, );
               		    $allexpressVolunteer[0] = $express_volunteerID;
				      	
      			}
         			$newexpressVolunteers = $allexpressVolunteer;     
         			update_post_meta($expressOpportunitiesID,'expressed_volunteer',$newexpressVolunteers);     
         		}
               $wpdb->query("update wp_posts set post_type='view_3' where ID =".$post_id);
               update_post_meta($post_id,'view_3_created',current_time( 'mysql' ));
               
			  /* ========================================================
				  update ordered_opportunity and ordered_opportunity_date
				  post meta filed to the user profile
				========================================================*/ 	 
               update_post_meta($post_id,'ordered_opportunity','');
               update_post_meta($post_id,'ordered_opportunity_date','');
               
               update_post_meta($post_id,'reminder_flag','0');
              /* ======================================================
                     Send the email for selecting top 3 in order of
                     preference using the form link
                 ========================================================*/ 	 
               
                 $user_info = get_userdata( $user_id );
				 $clientEmail = $user_info->user_email;
				 $site_url = get_bloginfo('wpurl');
				 $htmlmessage = $this->saviGetTemplate($post_id,$user_id,'','','view_2');
				 add_filter( 'wp_mail_content_type', array($this,'set_html_content_type') );
				 $blog_title = get_bloginfo('name');
				 $option_name = 'Opportunity_Selection';
				 $templatePage = (int) get_option($option_name);
				 $TemplateTitle = get_the_title($templatePage);
				 $subject = $blog_title." - ".$TemplateTitle;
				 $mail_headers[]='From: Auroville Learning Network <'. get_option( 'admin_email' ).'>' . "\r\n";
				 //Check if this is a test site
				$test_mentor_email = get_option("test_mentor_email");
				if($test_mentor_email!="") wp_mail($test_mentor_email, $subject, $htmlmessage,$mail_headers);
				else wp_mail($clientEmail, $subject, $htmlmessage,$mail_headers);
        }      
      }
  
      function savi_view_3_update($post_id) {
            global $wpdb;
            
             /* ======================================================
               remove the $profile_user_id in the ordered_new_volunteer
               because revert the status view_3 to view_2 
             ========================================================*/ 	 
            $expressOpportunitiesIDs = array();
            $ordered_new_volunteerMeta = array();
            $removed_ordered_new_volunteerIDs = array();
            $expressedVolunteerMeta = array();
            $removed_expressedVolunteerIDs = array();
            $profile_user_id =  get_post_meta($post_id,'user_id',true);
            $expressOpportunitiesMeta = get_post_meta( $post_id, 'express_opportunities', false );
            $allexpressOpportunities = $expressOpportunitiesMeta[0];
            if (sizeof($allexpressOpportunities) > 0 && is_array($allexpressOpportunities)) {
            	foreach($allexpressOpportunities as $key=>$expressOpportunity) {
               	  $expressOpportunitiesIDs[] = $expressOpportunity['express_opportunity'];
                 }
            } 
            foreach($expressOpportunitiesIDs as $expressOpportunitiesID){
				$ordered_new_volunteerMeta = get_post_meta( $expressOpportunitiesID, 'ordered_new_volunteer',true);
        		 foreach($ordered_new_volunteerMeta as $vol_user_id){
					if($vol_user_id != $profile_user_id ):
						$removed_ordered_new_volunteerIDs[] = $vol_user_id;				
					endif; 
				 }
                 update_post_meta($expressOpportunitiesID,'ordered_new_volunteer',$removed_ordered_new_volunteerIDs);
                 $expressedVolunteerMeta = get_post_meta( $expressOpportunitiesID, 'expressed_volunteer', true );
                  foreach($expressedVolunteerMeta as $expressedVolunteer_user_id){
					if($expressedVolunteer_user_id != $profile_user_id ):
						$removed_expressedVolunteerIDs[] = $expressedVolunteer_user_id;				
					endif; 
				 } 
				 update_post_meta($expressOpportunitiesID,'expressed_volunteer',$removed_expressedVolunteerIDs);   
				unset($removed_ordered_new_volunteerIDs);
				unset($removed_expressedVolunteerIDs);
	        }   
	              
            $wpdb->query("update wp_posts set post_type='view_2' where ID =".$post_id);
			$wpdb->query("delete from wp_postmeta where post_id =".$post_id." and meta_key='express_opportunities'");
			$wpdb->query("delete from wp_postmeta where post_id =".$post_id." and meta_key='ordered_opportunity'");
			$wpdb->query("delete from wp_postmeta where post_id =".$post_id." and meta_key='ordered_opportunity_date'");
			$wpdb->query("delete from wp_postmeta where post_id =".$post_id." and meta_key='reminder_flag'");	
			
				
		}
	   function savi_view_4_update($post_id) {
	   	    global $wpdb;
	   	    
             /* ======================================================
               remove the $profile_user_id in the ordered_new_volunteer
               because revert the volunteer (Dormant) view_4 to view_1 
             ========================================================*/
	   	    $expressOpportunitiesIDs = array();
            $ordered_new_volunteerMeta = array();
            $removed_ordered_new_volunteerIDs = array();
            $expressedVolunteerMeta = array();
            $removed_expressedVolunteerIDs = array();
            $profile_user_id =  get_post_meta($post_id,'user_id',true);
            $expressOpportunitiesMeta = get_post_meta( $post_id, 'express_opportunities', false );
            $allexpressOpportunities = $expressOpportunitiesMeta[0];
            if (sizeof($allexpressOpportunities) > 0 && is_array($allexpressOpportunities)) {
            	foreach($allexpressOpportunities as $key=>$expressOpportunity) {
               	  $expressOpportunitiesIDs[] = $expressOpportunity['express_opportunity'];
                 }
            } 
            foreach($expressOpportunitiesIDs as $expressOpportunitiesID){
				$ordered_new_volunteerMeta = get_post_meta( $expressOpportunitiesID, 'ordered_new_volunteer',true);
        		 foreach($ordered_new_volunteerMeta as $vol_user_id){
					if($vol_user_id != $profile_user_id ):
						$removed_ordered_new_volunteerIDs[] = $vol_user_id;				
					endif; 
				 }
                 update_post_meta($expressOpportunitiesID,'ordered_new_volunteer',$removed_ordered_new_volunteerIDs);
                 $expressedVolunteerMeta = get_post_meta( $expressOpportunitiesID, 'expressed_volunteer', true );
                  foreach($expressedVolunteerMeta as $expressedVolunteer_user_id){
					if($expressedVolunteer_user_id != $profile_user_id ):
						$removed_expressedVolunteerIDs[] = $expressedVolunteer_user_id;				
					endif; 
				 } 
				 update_post_meta($expressOpportunitiesID,'expressed_volunteer',$removed_expressedVolunteerIDs);  
				 unset($removed_ordered_new_volunteerIDs);
				 unset($removed_expressedVolunteerIDs); 
	        }   
	        
			$wpdb->query("update wp_posts set post_type='view_1' where ID =".$post_id);
			$wpdb->query("delete from wp_postmeta where post_id =".$post_id." and meta_key='express_opportunities'");
			$wpdb->query("delete from wp_postmeta where post_id =".$post_id." and meta_key='ordered_opportunity'");
			$wpdb->query("delete from wp_postmeta where post_id =".$post_id." and meta_key='ordered_opportunity_date'");
			$wpdb->query("delete from wp_postmeta where post_id =".$post_id." and meta_key='reminder_flag'");	
						
		} 
		
		function savi_views_CBA_remove( $actions ){
        unset( $actions[ 'edit' ] );
        unset( $actions[ 'trash' ] );
        return $actions;
      }   
      function savi_remove_menus_for_editor()
      {
      	
    		global $menu;
         
    		global $current_user;
    		$user_role = $current_user->roles[0];
         if(in_array($user_role,array('editor'))){
         	$is_access_allowed ='yes';
          }else{
            $is_access_allowed ='no';  
          }
			if($is_access_allowed == "yes" )
    		{
        		$restricted = array(__('Pages'),
                            __('Media'),
                            __('Links'),
                            __('Comments'),
                            __('Appearance'),
                            __('Plugins'),
                            __('Users'),
                            __('Tools'),
                            __('Settings'),
                            __('Posts'),
                            __('Profile'),
                            __('Programs'),
                            __('Forms'),


        						);
        		end ($menu);
        	
        		while (prev($menu)){
            		$value = explode(' ',$menu[key($menu)][0]);
            		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
            	}
        	
        		remove_menu_page( 'edit.php?post_type=project' );
            
    	}
  	}         	      	 	 
}
?>
