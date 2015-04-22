<?php
class Ready_to_Board extends Default_Profile  {

    public $custom_type = 'view_7';       
    public function __construct() {
        // Create a new Post Type
        add_action( 'init', array($this, 'create_post_type' ));
        // Call the Units_Metabox when showing the post
        add_action( 'add_meta_boxes', array($this, 'savi_red_to_board_init_metabox' )); 
        add_action( 'add_meta_boxes', array($this, 'savi_red_to_board_action_metabox' ));  
        // Call the Save Post when av_unit is being saved
        add_filter('redirect_post_location', array($this, 'savi_red_to_board_redirect'), '99'); 
        add_action('admin_notices', array($this, 'savi_red_to_board_saved_notice'));  
        add_action( 'admin_enqueue_scripts', array($this,'savi_red_to_board_colorbox_css_js'), 10, 1 ); 
        
        //drive from Default_Profile class 
        add_action( 'init', array($this, 'generate_unserialize' ));
        //drive from Default_Profile class
        add_action('add_meta_boxes',  array($this,'savi_add_default_profile_meta_box'),10,2); 
        
         // Call the Save Post when av_unit is being saved
        add_action( 'save_post', array($this, 'savi_red_to_board_save_data' ));
    }
    // Create a new Post Type 
    public function create_post_type() {
        register_post_type( $this->custom_type,
            array(
                'labels' => array(
                    'name' => __( 'Arrival' ),
                    'singular_name' => __( 'Arrival' )
                ),
                'supports' => array( 'title'),
				'menu_icon' => 'dashicons-groups',
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => $this->custom_type),
                 'exclude_from_search' => false,
                 'publicly_queryable'=>false
            )
        );
        
    }
    public function savi_red_to_board_show_metabox($post) {
      
        $post_id = $_GET['post'];//$wp_query->post->ID;
        $postmetaArray = get_post_meta($post->ID);        
        $current_user_id=get_current_user_id();
        $post_user_id = $postmetaArray['user_id'][0];
		  $name = get_the_title($post_id);
        $volunteer_opportunityID = get_post_meta( $post_id, 'volunteer_opportunity', true );
		  $volunteer_opportunityName = get_the_title( $volunteer_opportunityID);
		  $unitID = (int)get_post_meta($volunteer_opportunityID,'av_unit',true);
		  $projectID = (int)get_post_meta($volunteer_opportunityID,'projectname',true);
		  $volunteer_UnitName = get_the_title( $unitID);
		  $volunteer_ProjectName = ($projectID >0)?get_the_title( $projectID):"General";
		  $visa_particular = get_post_meta($post_id,"visa_particulars",true);
		  $visa_particular_link = "<a href='".$visa_particular."'  class='visa_particular'>View Visa Particular</a>"; 
		  $savi_views_sta_details_special_visa = get_post_meta($post_id,"savi_views_stay-details_special-visa",true);
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
         echo "</div><br>";  
         if($savi_views_sta_details_special_visa=="Yes"):
             	$intership = get_post_meta( $post_id, 'savi_views_education-details_intership', true );  
				$time_of_duration = ( int ) get_post_meta( $post_id, 'savi_views_stay-details_duration', true );  
			    if( (strtolower($intership) == "no" && $time_of_duration < 6) ):
			       
			    else:
			       echo "<div class='disp-row'>";
						echo " <div class='rwmb-label'>";
							echo "<label for='name'><b>Visa Particulars :</b></label>&nbsp;&nbsp;".$visa_particular_link."\n";
						echo " </div>";
					echo "</div><br>"; 
			    endif;
		endif;
        if($current_user_id!=$post_user_id) {
		  ?>
       	<script type="text/javascript" >
       	
      		jQuery("#title").attr("readonly",true);
       	</script>
		  <?php      
       }  
   }
   public function savi_red_to_board_showaction_metabox($post) {
      $post_id = $_GET['post'];  	
   	$onboard_date = get_post_meta($post_id,'onboard_date',true);
      $induction_date = get_post_meta($post_id,'induction_date',true);
    echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo"Induction Date";
            echo " </div>";
             echo "<div class='rwmb-input'>\n";
              echo "<input type='text' class='rwmb-date hasDatepicker' name='induction_date' 
               id='induction_date' data-options='{&quot;dateFormat&quot;:&quot;yy-mm-dd&quot;,&quot;showButtonPanel&quot;:true,&quot;appendText&quot;:&quot;(yyyy-mm-dd)&quot;,&quot;changeMonth&quot;:true,&quot;changeYear&quot;:true}'
                value='$induction_date' />\n";
            echo "</div>";
  		echo "</div><br>";  
   	echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo"Onboard Date";
            echo " </div>";
             echo "<div class='rwmb-input'>\n";
              echo "<input type='text' class='rwmb-date hasDatepicker' name='onboard_date' 
               id='onboard_date' data-options='{&quot;dateFormat&quot;:&quot;yy-mm-dd&quot;,&quot;showButtonPanel&quot;:true,&quot;appendText&quot;:&quot;(yyyy-mm-dd)&quot;,&quot;changeMonth&quot;:true,&quot;changeYear&quot;:true}'
               value ='$onboard_date' />\n";
            echo "</div>";
  		echo "</div><br>";  
   	
   	echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<input type='submit' id='send_email' value='Send the mail to volunteer informing the induction date'
                  class='button' name='save-close' >\n";
                 echo "<input type='hidden' name='saveclose_referer' value='$_SERVER[HTTP_REFERER]'>"; 
            echo " </div>";
  		   echo "</div><br>";
  		     ?>
  		   	<script>
					jQuery(document).ready(function(){
					  jQuery('.visa_particular').colorbox({rel:'group5', transition:'none'})
				   });   	
				</script>		
    		  <?php	
   }
   public function savi_red_to_board_init_metabox() {
       add_meta_box( 'volunteer_opportunity_details', "Volunteer Opportunity Details"
                   , array($this,'savi_red_to_board_show_metabox'), $this->custom_type, 'normal', 'low');
      //remove_meta_box( 'submitdiv', $this->custom_type, 'side' );              
   }
   public function savi_red_to_board_action_metabox() {
       add_meta_box( 'volunteer_opportunity_action', "Volunteer Opportunity Action"
                   , array($this,'savi_red_to_board_showaction_metabox'), $this->custom_type, 'normal', 'low'); 
   }      
   public static function savi_red_to_board_redirect($location) {
      
      global $post ;
       if( $post->post_type != "view_7" ) return $location;
		if (!isset($_POST['save-close'])) return $location;

		// determine the post status (private if selected, else published)
		//$post_status = ($_POST['post_status'] == 'private') ? 'private' : 'publish';

		// we want to publish new posts
      $induction_date = $_REQUEST['induction_date'];
      $onboard_date = $_REQUEST['onboard_date'];
       if((isset($induction_date) && !empty($induction_date)) && 
        (isset($onboard_date) && !empty($onboard_date))) {
			
			 update_post_meta($_POST['post_ID'],"induction_date",$induction_date);
			 update_post_meta($_POST['post_ID'],"onboard_date",$onboard_date);
             $user_id= get_post_meta($_POST['post_ID'],'user_id',true);
			 $user_info = get_userdata( $user_id );
		     $clientEmail = $user_info->user_email;
			 $site_url = get_bloginfo('wpurl');
		     $htmlmessage = FRS_Custom_Bulk_Action::saviGetTemplate($_POST['post_ID'],$user_id,'','','view_7');
		     add_filter( 'wp_mail_content_type', array(FRS_Custom_Bulk_Action,'set_html_content_type') );
		    $blog_title = get_bloginfo('name');
			$option_name = 'Induction_Instructions';
			$templatePage = (int) get_option($option_name);
			$TemplateTitle = get_the_title($templatePage);
			$subject = $blog_title." - ".$TemplateTitle;
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
    public static function savi_red_to_board_saved_notice() {
    	global $post ;
    	 if( $post->post_type != "view_7" ) return ;
		if (isset($_GET['lbsmessage'])) {
			?>
			<div class="updated">
				<p>Post saved</p>
			</div>
			<?php
		}
	}
   function savi_red_to_board_colorbox_css_js(){
		$plugin_dir = plugins_url();

		wp_enqueue_script( 'savi-colorbox-js', $plugin_dir . '/savi-plugin/colorbox/jquery.colorbox.js',
		                                             array( 'jquery'), SAVI_2014_VERSION, false );
		wp_enqueue_style( 'savi-colorbox-css', $plugin_dir . '/savi-plugin/colorbox/colorbox.css', array(), 1.0 );
	}	  
	 // Saving the meta data when saving the post
   public function savi_red_to_board_save_data( $post_id ) {
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
