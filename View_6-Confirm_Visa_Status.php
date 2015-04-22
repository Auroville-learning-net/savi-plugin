<?php
class Confirm_Visa_Status extends Default_Profile  {

    public $custom_type = 'view_6';       
    public function __construct() {
        // Create a new Post Type
        add_action( 'init', array($this, 'create_post_type' ));
        // Call the Units_Metabox when showing the post
        add_action( 'add_meta_boxes', array($this, 'savi_con_visa_sta_init_metabox' ));  
        // Call the Save Post when av_unit is being saved
        add_action( 'add_meta_boxes', array($this, 'savi_con_visa_sta_action_metabox' )); 
        add_action( 'wp_ajax_nopriv_savi_con_visa_sta_ajaxSubmission', array($this,'savi_con_visa_sta_ajaxSubmission') );  
   	    add_action( 'wp_ajax_savi_con_visa_sta_ajaxSubmission', array($this,'savi_con_visa_sta_ajaxSubmission') );            
        add_filter('redirect_post_location', array($this, 'savi_con_visa_sta_redirect'), '99'); 
        add_action('admin_notices', array($this, 'savi_con_visa_sta_saved_notice')); 
        
        //drive from Default_Profile class 
        add_action( 'init', array($this, 'generate_unserialize' ));
        //drive from Default_Profile class
        add_action('add_meta_boxes',  array($this,'savi_add_default_profile_meta_box'),10,2); 
       
       // Call the Save Post when av_unit is being saved
        add_action( 'save_post', array($this, 'savi_con_visa_sta_save_data' ));
    }
    // Create a new Post Type 
    public function create_post_type() {
        register_post_type( $this->custom_type,
            array(
                'labels' => array(
                    'name' => __( 'Confirm Visa' ),
                    'singular_name' => __( 'Confirm Visa' )
                ),
                'supports' => array( 'title'),
				'menu_icon' => 'dashicons-editor-help',
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => $this->custom_type),
                 'exclude_from_search' => false,
                 'publicly_queryable'=>false
            )
        );
        
    }
    public function savi_con_visa_sta_show_metabox($post) {

        $post_id = $_GET['post'];//$wp_query->post->ID;
        $postmetaArray = get_post_meta($post->ID);        
        $current_user_id=get_current_user_id();
        $post_user_id = $postmetaArray['user_id'][0];
		  $post_id = $_GET['post'];//$wp_query->post->ID;
        $name = get_the_title($post_id);
        $volunteer_opportunityID = get_post_meta( $post_id, 'volunteer_opportunity', true );
		  $volunteer_opportunityName = get_the_title( $volunteer_opportunityID);
		  $unitID = (int)get_post_meta($volunteer_opportunityID,'av_unit',true);
		  $projectID = (int)get_post_meta($volunteer_opportunityID,'projectname',true);
		  $volunteer_UnitName = get_the_title( $unitID);
		  $volunteer_ProjectName = ($projectID >0)?get_the_title( $projectID):"General";
         echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='name'><b>Name :</b></label>&nbsp;&nbsp;".ucfirst($name)."\n";;
            echo " </div>";
         echo "</div>";
         echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='name'><b>Project Name :</b></label>&nbsp;&nbsp;".$volunteer_ProjectName."\n";;
            echo " </div>";
         echo "</div>";
         echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='name'><b>Unit Name :</b></label>&nbsp;&nbsp;".$volunteer_UnitName."\n";;
            echo " </div>";
         echo "</div>";
         echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='name'><b>Opportunity Name :</b></label>&nbsp;&nbsp;".$volunteer_opportunityName."\n";;
            echo " </div>";
         echo "</div>";     
        if($current_user_id!=$post_user_id) {
?>
       <script type="text/javascript" >
      
       jQuery("#title").attr("readonly",true);
       </script>
<?php      
       }  
   }
   public function savi_con_visa_sta_init_metabox() {
       add_meta_box( 'volunteer_opportunity_details', "Volunteer Opportunity Details"
                   , array($this,'savi_con_visa_sta_show_metabox'), $this->custom_type, 'normal', 'low');
     // remove_meta_box( 'submitdiv', $this->custom_type, 'side' );              
   }   
   public function savi_con_visa_sta_action_metabox() {
       add_meta_box( 'volunteer_opportunity_action', "Volunteer Opportunity Action"
                   , array($this,'savi_con_visa_sta_showaction_metabox'), $this->custom_type, 'normal', 'low'); 
   }  
    // Saving the meta data when saving the post
   public function savi_con_visa_sta_showaction_metabox($post) {
    
     wp_nonce_field( plugin_basename( __FILE__ ), $this->nonce );
     $post_id  = $_REQUEST['post'];
     $confirm_receipt_sealed_envelop = get_post_meta($post_id,'confirm_receipt_sealed_envelop',true);
     if(empty($confirm_receipt_sealed_envelop)):
       $disable_confirm_visa="disabled='disabled'";
       $disable_confirm_visa_particulars="disabled='disabled'";
       $input_sealed_envelop_confirmed = "style='display:block'";
       $span_sealed_envelop_confirmed = "style='display:none'";
     else:
       $disable_confirm_visa="";    
       $disable_confirm_visa_particulars=""; 
       $input_sealed_envelop_confirmed = "style='display:none'";
       $span_sealed_envelop_confirmed = "style='display:block'";
     endif;
     
  		echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<input type='button' id='button_confirm_receipt_sealed_envelop' value='Confirm Receipt of Sealed Envelop'
                 onclick=\"ajaxConfirmVisaSubmission('".$post_id."','confirm_receipt_sealed_envelop');\" 
                  class='button' id='submit' name='submit' $input_sealed_envelop_confirmed>\n";
                 echo "<div id='confirm_receipt_sealed_envelop' class='spinner'></div>";
                 echo "<span id='span_sealed_envelop_confirmed' $span_sealed_envelop_confirmed >
                  Receipt of Sealed Envelop Confirmed</span>"; 
            echo " </div>";
  		echo "</div><br>";
     echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
					$html = '<input id="visa_particulars" type="file" name="visa_particulars" value="" size="25"'.$disable_confirm_visa_particulars.'/>';
		
					$html .= '<p class="description">';
					if( '' == get_post_meta( $post->ID, 'visa_particulars', true ) ) {
						$html .= __( 'You have no file attached to this post.', 'visa' );
					} else {
						$html .= get_post_meta( $post->ID, 'visa_particulars', true );
					} // end if
					$html .= '</p><!-- /.description -->';
		      echo $html; 		
				echo " </div>";
		  	echo "</div><br>";
         	echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<input type='submit' id='button_confirm_visa' value='Confirm Visa'
                 $disable_confirm_visa class='button' name='save-close' >\n";
                 echo "<input type='hidden' name='saveclose_referer' value='$_SERVER[HTTP_REFERER]'>"; 
            echo " </div>";
  		echo "</div><br>"; 
		
  		?>

        <script>
        jQuery('form').attr('enctype', 'multipart/form-data');
        function showConfirmVisaProcessing(action){
	        jQuery('#'+action).show();
		  }
	     function hideConfirmVisaProcessing(action){
	        jQuery('#'+action).hide();
		  }  
  		  function ajaxConfirmVisaSubmission(id,action){
        		//alert (action);
        		jQuery('#button_'+action).attr('disabled',true);
        		showConfirmVisaProcessing(action);
  		  		jQuery.ajax({
             
   	   	  url: "<?php echo get_bloginfo('url')?>/wp-admin/admin-ajax.php",
      	  	 data: {
            		 
            		'post_id' : id,
            		'action' : 'savi_con_visa_sta_ajaxSubmission',
            		'ajax_visa_action' :action
        	  	},
        		success: function(respon) {
            jQuery('#button_'+action).attr('disabled',false);        		
        		hideConfirmVisaProcessing(action);	
        		 
            if(respon != 0) {
            
              switch (respon) {
                    case ('confirm_receipt_sealed_envelop'):
               		   alert('Receipt of Sealed Envelop Confirmed');
               		   jQuery('#button_confirm_receipt_sealed_envelop').hide();
               		   jQuery('#span_sealed_envelop_confirmed').show();
               		   jQuery('#button_confirm_visa').attr('disabled',false);
               		   jQuery('#visa_particulars').attr('disabled',false);
               		   
                        break;
                    case ('already_receipt_sealed_enveloped_confirmed'):
               		   alert('Already Receipt Sealed Enveloped Confirmed');
               		   jQuery('#button_confirm_receipt_sealed_envelop').hide();
               		   jQuery('#span_sealed_envelop_confirmed').show();
               		   jQuery('#button_confirm_visa').attr('disabled',false);
               		   jQuery('#visa_particulars').attr('disabled',false);
               		   
                        break;    
                    default:
                        break;
                }
            }
            else {
            //    jQuery("#"+id).val('');
              //  jQuery("#"+id).removeAttr('readonly');
            }
        	 },
        	 error: function(respon, ajaxOptions, thrownError) {
        		alert(respon.status);
        		alert(thrownError);
        		alert(ajaxOptions);
        	}
        });
		}
 
        </script>
     <?php      
    }   
	 function savi_con_visa_sta_ajaxSubmission() {
   
	   $post_id = $_REQUEST['post_id'];
	   $action = $_REQUEST['ajax_visa_action'];
      	
      	if($action =='confirm_receipt_sealed_envelop') :
      	     if(empty($confirm_receipt_sealed_envelop)):
				     $blogtime = current_time( 'mysql' ); 
				     update_post_meta($post_id,'confirm_receipt_sealed_envelop',$blogtime); 
				     $oppID = get_post_meta($post_id,'volunteer_opportunity',true);
				     $user_id= get_post_meta($post_id,'user_id',true);
					 $user_info = get_userdata( $user_id );
					 $clientEmail = $user_info->user_email;
					 $site_url = get_bloginfo('wpurl');
					 $option_name = 'Instruction_for_Auroville_VISA_Application';
					 $templatePage = (int) get_option($option_name);
					 $printTemplate = get_post($templatePage);
					 $content = $printTemplate->post_content;
					 $savi_shortcodes = array();
					 foreach($GLOBALS['shortcode_tags'] as $keys => $values){
						if( substr( $keys, 0, 4 ) === "SAVI" ) $savi_shortcodes[] = $keys;
					 }
					$default_atts = 'profile_id="'.$post_id.'" user_id="'.$user_id.'" user_pwd="'.$random_password.'" need_visa=="'.$requires_visa.'" post_type=="'.$post_type.'" opportunity_id="'.$oppID.'"';
					foreach($savi_shortcodes as $saviCodes){
						if (has_shortcode($content, $saviCodes)) 
							$content = str_replace('['.$saviCodes,'['.$saviCodes.' '.$default_atts,$content);
					}
					 $printContent = apply_filters('the_content',$content);
					 add_filter( 'wp_mail_content_type', create_function('', 'return "text/html";') );
					 $blog_title = get_bloginfo('name');
					 $option_name = 'Instruction_for_Auroville_VISA_Application';
					 $templatePage = (int) get_option($option_name);
					 $TemplateTitle = get_the_title($templatePage);
					 $subject = $blog_title." - ".$TemplateTitle;
					 
					 $mail_headers[]='From: Auroville Learning Network <'. get_option( 'admin_email' ).'>' . "\r\n";
					//Check if this is a test site
					$test_mentor_email = get_option("test_mentor_email");
					if($test_mentor_email!="") wp_mail($test_mentor_email, $subject, $printContent, $mail_headers);
					else wp_mail($clientEmail, $subject, $printContent,$mail_headers);
					
				    echo $action;
			  else:
			     echo "already_receipt_sealed_enveloped_confirmed";
			  endif;	  
      			exit;			
			endif;  		

		}
	  public static function savi_con_visa_sta_redirect($location) {
      
      global $post ;
       if( $post->post_type != "view_6" ) return $location;
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
      	// If the user uploaded an image, let's upload it to the server
			if( !empty($_FILES['visa_particulars']) && ($_FILES['visa_particulars']['error'] ) == 0 ) {
			    
				// Upload the goal image to the uploads directory, resize the image, then upload the resized version
				$goal_image_file = wp_upload_bits( $_FILES['visa_particulars']['name'], null, file_get_contents( $_FILES['visa_particulars']['tmp_name'] ) );

				// Set post meta about this image. Need the comment ID and need the path.
				if( false == $goal_image_file['error'] ) {
				
					// Since we've already added the key for this, we'll just update it with the file.
					update_post_meta( $_POST['post_ID'], 'visa_particulars', $goal_image_file['url'] );
		            wp_update_post(array('ID' => $_POST['post_ID'], 'post_type' => 'view_7'));
		            update_post_meta($post_id,'view_7_created',current_time( 'mysql' ));
				} // end if/else

			} // end if
	
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
    public static function savi_con_visa_sta_saved_notice() {
    	global $post ;
    	 if( $post->post_type != "view_6" ) return ;
		if (isset($_GET['lbsmessage'])) {
			?>
			<div class="updated">
				<p>Confirmed Visa</p>
			</div>
			<?php
		}
	}
    // Saving the meta data when saving the post
   public function savi_con_visa_sta_save_data( $post_id ) {
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
            $saved_savi_views_admin_details_admin_notes = get_post_meta($post->ID, 'savi_views_admin-details_admin-notes', true);
       		$admin_note_content = sanitize_text_field($_POST['savi_views_admin_details_admin_notes']);
 				$admin_note_author = get_current_user_id();
 				$admin_note_time = date('Y-m-d H:i:s');
       		if(trim($admin_note_content)!=""){
               $savi_views_admin_details_admin_notes = array (  "admin_note_author" => $admin_note_author,
               									"admin_note_time" =>$admin_note_time,
               									"admin_note_content" =>$admin_note_content);
              $saved_savi_views_admin_details_admin_notes[]  = $savi_views_admin_details_admin_notes;
                update_post_meta( $post->ID, 'savi_views_admin-details_admin-notes', $saved_savi_views_admin_details_admin_notes);
            }
       
    }  
 	 
 }
?>
