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
            
        // }
		}
		
		

function savi_admin_inline_approval() {
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
	   function hd_add_box() {
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
			//	echo $_REQUEST['action'];
				//die;
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
	  	//
         $custom_post_type = array('view_0','view_1','view_2','view_3','view_4');
         if(isset($_REQUEST['confirmRegistration'])) {
             $newaction ='confirmRegistration';         
             $content ="Confirmed Registration";
         }
         if(isset($_REQUEST['Approval']) ) {
             $newaction ='Approval';         
             $content ="Approved";
             //	die($pagenow.$post_type);
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
                	  	$current_increment_id = $wpdb->get_var("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'aurovill_wordpress' AND TABLE_NAME = 'wp_users'" );
                		//$clientName = $parts[0].substr($parts[1],0,$pos_terminate);
                		$clientName = "savi".$current_increment_id;
          		$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
	       		$user_id = wp_create_user( $clientName, $random_password, $clientEmail );
	       		$fname = get_post_meta($postID, 'savi_views_contact-details_first-name', true);
                $lname = get_post_meta($postID, 'savi_views_contact-details_last-name', true);  
                $Name =  $fname." ".$lname;
	       		wp_update_user( array( 'ID' => $user_id, 'user_nicename' => $Name ,"display_name" => $Name) );
	       		$wp_user_object = new WP_User($user_id);
          		$wp_user_object->set_role('subscriber'); 
          		add_user_meta( $user_id, 'status', 'V1'); 
          		add_user_meta( $user_id, 'profile_post_id', $postID); 
				//$wpdb->query("update wp_users set user_login = 'savi$user_id' where ID = ".$user_id);
          		$htmlmessage = $this->saviGetTemplate($postID,$user_id,$random_password);
          		//wp_new_user_notification($user_id, $random_password);
          		$site_url = get_bloginfo('wpurl');
          		add_filter( 'wp_mail_content_type', array($this,'set_html_content_type') );
          		$subject = "New User Created: ".$site_url."";
          		wp_mail($clientEmail, $subject, $htmlmessage);
          		//$headers .= "MIME-Version: 1.0\r\n";
			//$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
          		//mail($clientEmail, $subject, $htmlmessage, $headers);
          		return array(
		                	'user_id'	    => $user_id,
			                'passwordNew'	=> $random_password );
                       
        endif;          	
      }
      function set_html_content_type() {

	return 'text/html';
      }
   /*   function saviGetTemplate($postID) {
 	     global $wpdb;
    	 $fields = array("salutation", "firstname", "lastname", "address", "country", "userid", "password");
    	 $values = array();
    	 $meta = get_post_meta( $postID );
   	  	 $country_id = $meta['savi_views_contact-details_nationality'][0];
    	 $country_sql="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 738 AND item_id=$country_id";
	 	 $country_results = $wpdb->get_results($country_sql,ARRAY_A);
	 	 $country = $country_results[0]['meta_value'];		
   	     $replace_array['salutation'] ="Mr / Mrs";
    	 $replace_array['firstname'] =$meta['savi_views_contact-details_first-name'][0];
    	 $replace_array['lastname'] =$meta['savi_views_contact-details_last-name'][0];
    	 $replace_array['address'] =$meta['savi_views_contact-details_address'][0];
    	 $replace_array['country'] =$country;
    	 $replace_array['userid'] =$meta['user_id'][0];
    	 $replace_array['password'] =$meta['plain_password'][0];
     	 for ($arrIndex = 0; $arrIndex < sizeof($fields); $arrIndex++) {
		 	 $fieldname = $fields[$arrIndex];
		 	 $values[$arrIndex] = $replace_array[$fieldname];
		 	 $fields[$arrIndex] = "[$fieldname]";
    	 }
    	 $templatePage = (int) get_option('Inital_Enquiry');
    	 $printTemplate = get_post($templatePage);
    	 $content = apply_filters('the_content',$printTemplate->post_content);
 	 $printContent = str_replace($fields, $values, $content);  // Replace the fieldnames with the fieldvalues
    	 return $printContent;

      
    }*/
    function saviGetTemplate($postID,$user_id,$random_password) {
 	     global $wpdb;
    	 $fields = array("SAVI_name","SAVI_user_name", "SAVI_user_password");
    	 $values = array();
    	 $meta = get_post_meta( $postID );
    	 
   //	print_r($meta);
     $user_login=$wpdb->get_var("SELECT user_login FROM wp_users WHERE ID =".$user_id);
     $user_display_name=$wpdb->get_var("SELECT display_name FROM wp_users WHERE ID =".$user_id);
	//die;
	     $replace_array['SAVI_name'] =$user_display_name;
    	 $replace_array['SAVI_user_name'] =$user_login;
    	 $replace_array['SAVI_user_password'] =$random_password;
     	 for ($arrIndex = 0; $arrIndex < sizeof($fields); $arrIndex++) {
		 	 $fieldname = $fields[$arrIndex];
		 	 $values[$arrIndex] = $replace_array[$fieldname];
		 	 $fields[$arrIndex] = "[$fieldname]";
    	 }
    	// print_r($values);die;
    	 $templatePage = (int) get_option('Inital_Enquiry');
    	 $printTemplate = get_post($templatePage);
    	 $content = apply_filters('the_content',$printTemplate->post_content);
 	 $printContent = str_replace($fields, $values, $content);  // Replace the fieldnames with the fieldvalues
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
      	wp_mail( $to, $subject, $message);
      	 //mail($to,$subject,$message);
   
   	}
   	function savi_email_to_volunteer($user_id,$interest_shown) {
   
      	$site_url = get_bloginfo('wpurl');
      	$user_info = get_userdata( $user_id );
      	$to = $user_info->user_email;
      	$subject = "Opportunities selected depending your skills: ".$site_url."";
      	$message = "Hello " .$user_info->display_name .
                   "\nWe have selected opportunities depending upon your skills please select best of three opportuniies. 
                    !\n\nThank you for visiting\n ".$site_url."\n\nThe Opportunites are as below\n\n".$interest_shown;
      	wp_mail( $to, $subject, $message);
      	// mail($to,$subject,$message);
   
   	}
   	function savi_approval_email($user_id) {
   
      	$site_url = get_bloginfo('wpurl');
        	$user_info = get_userdata( $user_id );
        	$to = $user_info->user_email;
        	$subject = "Profile Approved: ".$site_url."";
        	$message = "Hello " .$user_info->display_name .
                   "\nYour profile has been Approved,So you can start accessing the opportunities page
                    !\n\nThank you for visiting\n ".$site_url."";
        	wp_mail( $to, $subject, $message);
              // mail($to,$subject,$message);
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
            'name'            =>  'savi_opp_cat_work_area',
            'orderby'         =>  'name',
            'selected'        =>  $wp_query->query['term'],
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

      	global $wpdb;
      	$name = 'ADMIN_FILTER_FIELD_VALUE';
	   	$id = "ADMIN_FILTER_FIELD_VALUE";
      	$class = $args['class'];
      	$show_option_all = $args['show_option_all'];
      	$custom_filed  =  $args['custom_filed'];  
      	$sql = 'SELECT DISTINCT meta_value FROM wp_postmeta where meta_key="'.$custom_filed.'" ORDER BY 1';
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
					$_selected = selected( $field_value, $selected, false );
					if ( $_selected )
						$found_selected = true;
						$output .= "\t<option value='$field_value_id'$_selected>" . $field_value . "</option>\n";
		     }
        	  $output .= "</select><input type='hidden' name='ADMIN_FILTER_FIELD_NAME' value='".$custom_filed."'/>";
        	  echo $output;
        }
      }
  		function savi_admin_posts_filter( $query ) {
  			global $pagenow;$qv = &$query->query_vars;
     		        if ( is_admin() && $pagenow=='edit.php' && isset($_GET['ADMIN_FILTER_FIELD_NAME']) &&
     	                                            $_GET['ADMIN_FILTER_FIELD_NAME'] != '') {
      			          $query->query_vars['meta_key'] = $_GET['ADMIN_FILTER_FIELD_NAME'];
      			          if (isset($_GET['ADMIN_FILTER_FIELD_VALUE']) && $_GET['ADMIN_FILTER_FIELD_VALUE'] != '')
        					$query->query_vars['meta_value'] = $_GET['ADMIN_FILTER_FIELD_VALUE'];
          	       }
          	       if ( is_admin() && $pagenow=='edit.php' && isset($qv['taxonomy']) && $qv['taxonomy']=='savi_opp_cat_work_area' &&
                                       isset($qv['term']) && is_numeric($qv['term']) && isset($_REQUEST['savi_opp_cat_work_area']) && $_REQUEST['savi_opp_cat_work_area']!=0 ) {
                                       $term = get_term_by('id',$qv['term'],'savi_opp_cat_work_area');
                                       $qv['term'] = $term->slug;
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
                   
  		}
		function savi_view_0_update($post_id) {
			global $wpdb;
  			$args= $this->create_custom_user($post_id);	
  			if(!empty($args['user_id']) && $args['user_id'] > 0) {
      			$wpdb->query("update wp_posts set post_type='view_1'
                           	            ,post_author='".$args['user_id']."',post_status='publish'  where ID =".$post_id);
            	           update_post_meta($post_id,'user_id',$args['user_id']);
            	           update_post_meta($post_id,'plain_password',$args['passwordNew']);
            	          $wpdb->query("update wp_frm_items set form_id = 19, user_id =".$args['user_id']." where post_id = ".$post_id);
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
         $user_id= get_post_meta($post_id,'user_id',true);
         update_user_meta( $user_id,'status', 'V2', 'V1' );
         $this->savi_approval_email($user_id);  	   
  	   }
      function savi_view_2_update($post_id) {
         global $wpdb;
			$expressOpportunitiesMeta = get_post_meta( $post_id, 'express_opportunities', false );
			$allexpressOpportunities = $expressOpportunitiesMeta[0];
			$expressOpportunitiesValue = "";
         if (sizeof($allexpressOpportunities) > 0 && is_array($allexpressOpportunities)) {
            	foreach($allexpressOpportunities as $key=>$expressOpportunity) {
               	$expressOpportunitiesID = $expressOpportunity['express_opportunity'];
                  $expressOpportunitiesValue.= get_the_title($expressOpportunitiesID)."\n";
               }
               $wpdb->query("update wp_posts set post_type='view_3' where ID =".$post_id);
               update_post_meta($post_id,'reminder_flag','0');
               $user_id= get_post_meta($post_id,'user_id',true);
               $this->savi_email_to_volunteer($user_id,$expressOpportunitiesValue);	                                    
        }      
      }
      function savi_view_3_update($post_id) {
      	global $wpdb;
			$wpdb->query("update wp_posts set post_type='view_2' where ID =".$post_id);
			$wpdb->query("delete from wp_postmeta where post_id =".$post_id." and meta_key='express_opportunities'");
			$wpdb->query("delete from wp_postmeta where post_id =".$post_id." and meta_key='reminder_flag'");			
		}
	   function savi_view_4_update($post_id) {
	   	global $wpdb;
			$wpdb->query("update wp_posts set post_type='view_1' where ID =".$post_id);
			$wpdb->query("delete from wp_postmeta where post_id =".$post_id." and meta_key='express_opportunities'");			
		} 
		function savi_views_CBA_remove( $actions ){
        unset( $actions[ 'edit' ] );
        unset( $actions[ 'trash' ] );
        return $actions;
      }      	      	 	 
}
?>