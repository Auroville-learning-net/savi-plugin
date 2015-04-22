<?php
class Visa_Documentation extends Default_Profile {

    public $custom_type = 'view_5';       
    public function __construct() {
        // Create a new Post Type
        add_action( 'init', array($this, 'create_post_type' ));
        // Call the Units_Metabox when showing the post
        add_action( 'add_meta_boxes', array($this, 'savi_visa_doc_init_metabox' ));
        // Call the Units_Metabox when showing the post
        add_action( 'add_meta_boxes', array($this, 'savi_visa_doc_action_metabox' ));  
        // Call the Save Post when av_unit is being saved
        add_action( 'save_post', array($this, 'savi_visa_doc_save_data' ));
        // Call the custom filter, filtering the posts by country        
        add_action( 'admin_enqueue_scripts', array($this,'savi_admin_dual_css_js'), 10, 1 );

        add_action( 'wp_ajax_nopriv_savi_visa_doc_ajaxSubmission', array($this,'savi_visa_doc_ajaxSubmission') );  
   	  add_action( 'wp_ajax_savi_visa_doc_ajaxSubmission', array($this,'savi_visa_doc_ajaxSubmission') );            
        add_filter('redirect_post_location', array($this, 'savi_visa_doc_redirect'), '99'); 
        add_action('admin_notices', array($this, 'savi_visa_doc_saved_notice'));
        
        //drive from Default_Profile class 
        add_action( 'init', array($this, 'generate_unserialize' ));
        //drive from Default_Profile class
        add_action('add_meta_boxes',  array($this,'savi_add_default_profile_meta_box'),10,2); 
    }
    // Create a new Post Type 
    public function create_post_type() {
        register_post_type( $this->custom_type,
            array(
                'labels' => array(
                    'name' => __( 'Pre-Visa' ),
                    'singular_name' => __( 'Pre-Visa' )
                ),
                'supports' => array( ''),
				'menu_icon' => 'dashicons-admin-site',
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => $this->custom_type),
                 'exclude_from_search' => false,
                 'publicly_queryable'=>false
            )
        );
        
    }
    public function savi_visa_doc_showdetails_metabox($post) {

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
    
        
   }
   public function savi_visa_doc_showaction_metabox($post) {
	   
	 
     $post_id  = $_REQUEST['post'];
     $site_url = get_bloginfo('wpurl');
     $generate = get_post_meta($post_id,'generated_entry_letter',true);
     $entry_group_confirmation = get_post_meta($post_id,'entry_group_confirmation',true);
     $sealed_envelop_reference = get_post_meta($post_id,'sealed_envelop_reference',true);
     $pdf_path = $site_url."/entry_letter/entryletter$post_id.pdf";
   
     if(file_exists( realpath('..')."\entry_letter\entryletter$post_id.pdf")):
      
        $disable_entry_group_confirmation="";
       $input_button_generate = "style='display:none'";
       $anchor_generate ="style='display:block'";
     else:
       $disable_entry_group_confirmation="disabled='disabled'";   
       $input_button_generate = "style='display:block'";
       $anchor_generate ="style='display:none'";
     endif;
     if(empty($entry_group_confirmation)):
		 
		 /* if(file_exists( realpath('..')."\entry_letter\entryletter$post_id.pdf")):
			 $disable_sealed_envelop_reference="";   
		  else: */
		     $disable_sealed_envelop_reference="disabled='disabled'";
		 // endif; 
		 $input_button_group_confirmation = "style='display:block'";
         $span_group_confirmation ="style='display:none'";
     else:
       $input_button_group_confirmation = "style='display:none'";
       $span_group_confirmation ="style='display:block'"; 
     endif;
     if(empty($sealed_envelop_reference)):
		  $disable_send_sealed_coverto_volunteer="disabled='disabled'";
		  $input_receipt_of_sealed = "style='display:block'";
		  $span_receipt_of_sealed ="style='display:none'"; 
	 else:
       $input_receipt_of_sealed = "style='display:none'";
       $span_receipt_of_sealed ="style='display:block'"; 
     endif;
   
  		echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
               
                echo "<input type='button' id='button_generate' value='Generate Entry Letter'
                 onclick=\"ajaxSubmission('".$post_id."','generate');\"  class='button' id='submit' name='submit' $input_button_generate>\n";
                 echo "<div id='generate' class='spinner'></div>";
            
                 echo "<a href='".$site_url."/entry_letter/entryletter$post_id.pdf' id='download_enylet' 
                        $anchor_generate >Download Entry Letter</a>";
                       
            echo " </div>";
  		echo "</div><br>";
  
  		echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<input type='button' id='button_entry_group' value='Confirm Sending of Entry letter to Entry Group'
                 $input_button_group_confirmation $disable_entry_group_confirmation onclick=\"ajaxSubmission('".$post_id."','entry_group');\" 
                  class='button' name='submit' >\n";
                echo "<div id='entry_group' class='spinner'></div>"; 
                   echo "<span id='span_group_confirmation' $span_group_confirmation >Sending of Entry letter to Entry Group Confirmed</span>"; 
            echo " </div>";
  		echo "</div><br>";
   	echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<input type='button' value='Confirm Receipt of Sealed Envelop Reference'
                id='button_sealed_envelop_reference' $input_receipt_of_sealed  $disable_sealed_envelop_reference
                  onclick=\"ajaxSubmission('".$post_id."','sealed_envelop_reference');\" class='button'  name='submit'>\n";
                echo "<div id='sealed_envelop_reference' class='spinner'></div>";
                echo "<span id='span_sealed_envelop_reference' $span_receipt_of_sealed >Receipt of Sealed Envelop Reference Confirmed</span>";
            echo " </div>";
  		echo "</div><br>";
  
  		echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo"Registered Mail";
            echo " </div>";
             echo "<div class='rwmb-input'>\n";
              echo "<input type='text' name='registered_mail' id='registered_mail' $disable_send_sealed_coverto_volunteer/>\n";
            echo "</div>";
  		echo "</div>";  
  		echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo"Registered Mail Date";
            echo " </div>";
             echo "<div class='rwmb-input'>\n";
              echo "<input type='text' class='rwmb-date hasDatepicker' name='registered_mail_date' 
               id='registered_mail_date' data-options='{&quot;dateFormat&quot;:&quot;yy-mm-dd&quot;,&quot;showButtonPanel&quot;:true,&quot;appendText&quot;:&quot;(yyyy-mm-dd)&quot;,&quot;changeMonth&quot;:true,&quot;changeYear&quot;:true}'
                $disable_send_sealed_coverto_volunteer/>\n";
            echo "</div>";
  		echo "</div><br>";  
  		echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<input type='submit' value='Confirm Sending Sealed Cover to Volunteer'
                 $disable_send_sealed_coverto_volunteer class='button' id='button_sending_sealed_cover' name='save-close'>\n";
                echo "<input type='hidden' name='saveclose_referer' value='$_SERVER[HTTP_REFERER]'>"; 
            echo " </div>";
  		echo "</div>";
  		$user_id= get_post_meta($post_id,'user_id',true);
             $user_info = get_userdata( $user_id );
             $volunteer_opportunity_id = get_post_meta($post_id,'volunteer_opportunity',true);
             $option_name = 'Pre_Visa_Entry_Letter';
			 $templatePage = (int) get_option($option_name);
			 $printTemplate = get_post($templatePage);
			 $content = $printTemplate->post_content;
			 $savi_shortcodes = array();
			 foreach($GLOBALS['shortcode_tags'] as $keys => $values){
				if( substr( $keys, 0, 4 ) === "SAVI" ) $savi_shortcodes[] = $keys;
			 }
			 $default_atts = 'profile_id="'.$post_id.'" user_id="'.$user_id.'" user_pwd="'.$random_password.'" need_visa=="'.$requires_visa.'" post_type=="'.$post_type.'" opportunity_id="'.$volunteer_opportunity_id.'"';
			 foreach($savi_shortcodes as $saviCodes){
				if (has_shortcode($content, $saviCodes)) 
					$content = str_replace('['.$saviCodes,'['.$saviCodes.' '.$default_atts,$content);
			 }
			 $printContent = apply_filters('the_content',$content);
			 echo $printContent;
  		?>

        <script>
        function showProcessing(action){
	        jQuery('#'+action).show();
		  }
	     function hideProcessing(action){
	        jQuery('#'+action).hide();
		  }  
  		  function ajaxSubmission(id,action){
        		//alert (action);
        		jQuery('#button_'+action).attr('disabled',true);
        		showProcessing(action);
  		  		jQuery.ajax({
             
   	   	  url: "<?php echo get_bloginfo('url')?>/wp-admin/admin-ajax.php",
      	  	 data: {
            		 
            		'post_id' : id,
            		'action' : 'savi_visa_doc_ajaxSubmission',
            		'ajax_action' :action
        	  	},
        		success: function(respon) {
            jQuery('#button_'+action).attr('disabled',false);        		
        		hideProcessing(action);	
        		 
            if(respon != 0) {
              switch (respon) {
                    case 'generate':
                        alert('Entry Letter Generated successfully');
                        jQuery('#button_generate').hide();
                        jQuery('#button_entry_group').attr('disabled',false);
               		    jQuery('#download_enylet').show();
               		    
               		    
                        break;
                    case 'entry_group':
                       alert('Sending of Entry letter to Entry Group Confirmed');
                       jQuery('#button_sealed_envelop_reference').attr('disabled',false);
                       
                       jQuery('#button_entry_group').hide();
               		   jQuery('#span_group_confirmation').show();
               		    
                        break;
                    case ('sealed_envelop_reference'):
               		   alert('Receipt of Sealed Envelop Reference Confirmed');
               		   jQuery('#registered_mail').attr('disabled',false);
               		   jQuery('.rwmb-date').attr('disabled',false);
               		   jQuery('#button_sending_sealed_cover').attr('disabled',false);
               		   
                       jQuery('#button_sealed_envelop_reference').hide();
                       jQuery('#span_sealed_envelop_reference').show();
                       
               		   
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
   public function savi_visa_doc_init_metabox() {
       add_meta_box( 'volunteer_opportunity_details', "Volunteer Opportunity Details"
                   , array($this,'savi_visa_doc_showdetails_metabox'), $this->custom_type, 'normal', 'low');
      //remove_meta_box( 'submitdiv', $this->custom_type, 'side' );              
   }
   public function savi_visa_doc_action_metabox() {
       add_meta_box( 'volunteer_opportunity_action', "Volunteer Opportunity Action"
                   , array($this,'savi_visa_doc_showaction_metabox'), $this->custom_type, 'normal', 'low'); 
   }    
    // Saving the meta data when saving the post
   public function savi_visa_doc_save_data( $post_id ) {
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
    function savi_admin_dual_css_js(){
		$template_dir = get_stylesheet_directory_uri();

		wp_enqueue_script( 'savi-dual-js', $template_dir . '/js/jquery.bootstrap-duallistbox.js',
		                                             array( 'jquery'), SAVI_2014_VERSION, false );
		wp_enqueue_style( 'savi-dual-css', $template_dir . '/css/bootstrap-duallistbox.css', array(), 1.0 );
	}
   function savi_visa_doc_ajaxSubmission() {
   
	   $post_id = $_REQUEST['post_id'];
	   $action = $_REQUEST['ajax_action'];
     
			if($action =='generate') :
			  $blogtime = current_time( 'mysql' ); 
			 update_post_meta($post_id,'generated_entry_letter',$blogtime);
			 require_once(ABSPATH . '/wp-content/plugins/savi-plugin/dompdf/dompdf_config.inc.php');
			 $user_id= get_post_meta($post_id,'user_id',true);
             $user_info = get_userdata( $user_id );
             $volunteer_opportunity_id = get_post_meta($post_id,'volunteer_opportunity',true);
             $option_name = 'Pre_Visa_Entry_Letter';
			 $templatePage = (int) get_option($option_name);
			 $printTemplate = get_post($templatePage);
			 $content = $printTemplate->post_content;
			 $savi_shortcodes = array();
			 foreach($GLOBALS['shortcode_tags'] as $keys => $values){
				if( substr( $keys, 0, 4 ) === "SAVI" ) $savi_shortcodes[] = $keys;
			 }
			 $default_atts = 'profile_id="'.$post_id.'" user_id="'.$user_id.'" user_pwd="'.$random_password.'" need_visa=="'.$requires_visa.'" post_type=="'.$post_type.'" opportunity_id="'.$volunteer_opportunity_id.'"';
			 foreach($savi_shortcodes as $saviCodes){
				if (has_shortcode($content, $saviCodes)) 
					$content = str_replace('['.$saviCodes,'['.$saviCodes.' '.$default_atts,$content);
			 }
			 $printContent = apply_filters('the_content',$content);
			
            $filename = ABSPATH ."/entry_letter/entryletter".$post_id.".pdf";
            
			$dompdf = new DOMPDF();
			$dompdf->load_html($printContent);
			$dompdf->render();
			$output = $dompdf->output();
			file_put_contents($filename, $output);
			echo $action;
      		exit;
      	endif;			
			if($action =='entry_group') :
			      $blogtime = current_time( 'mysql' ); 
			      update_post_meta($post_id,'entry_group_confirmation',$blogtime);
					echo $action;
      			exit;	
      	endif;		
      	if($action =='sealed_envelop_reference') :
      	     $blogtime = current_time( 'mysql' ); 
      	     update_post_meta($post_id,'sealed_envelop_reference',$blogtime); 
			 echo $action;
      			exit;			
			endif;  		

		}
	  public static function savi_visa_doc_redirect($location) {
      
      global $post ;
       if( $post->post_type != "view_5" ) return $location;
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
      $registered_mail = $_REQUEST['registered_mail'];
      $registered_mail_date = $_REQUEST['registered_mail_date'];
       if((isset($registered_mail) && !empty($registered_mail)) && 
        (isset($registered_mail_date) && !empty($registered_mail_date))) {
			wp_update_post(array('ID' => $_POST['post_ID'], 'post_status' => $post_status));
			wp_update_post(array('ID' => $_POST['post_ID'], 'post_type' => 'view_6'));
			update_post_meta($post_id,'view_6_created',current_time( 'mysql' ));
			update_post_meta($_POST['post_ID'],"registered_mail",$registered_mail);
			update_post_meta($_POST['post_ID'],"registered_mail_date",$registered_mail_date);
			
			$user_id= get_post_meta($_POST['post_ID'],'user_id',true);
			$user_info = get_userdata( $user_id );
			$clientEmail = $user_info->user_email;
			$site_url = get_bloginfo('wpurl');
			$htmlmessage = FRS_Custom_Bulk_Action::saviGetTemplate($_POST['post_ID'],$user_id,'','','view_6');
			add_filter( 'wp_mail_content_type', array(FRS_Custom_Bulk_Action,'set_html_content_type') );
			$subject = "Sending of Sealed VISA Request letter: ".$site_url."";
			
			$mail_headers[]='From: Auroville Learning Network <'. get_option( 'admin_email' ).'>' . "\r\n";
			//Check if this is a test site
			$test_mentor_email = get_option("test_mentor_email");
			if($test_mentor_email!="") wp_mail($test_mentor_email, $subject, $htmlmessage, $mail_headers);
			else wp_mail($clientEmail, $subject, $htmlmessage,$mail_headers);
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
  public static function savi_visa_doc_saved_notice() {
  	global $post ;
  	if( $post->post_type != "view_5" ) return ;
		if (isset($_GET['lbsmessage'])) {
			?>
			<div class="updated">
				<p>Post saved</p>
			</div>
			<?php
		}
	}  		
  }
?>
