<?php
class View_Profile {
					private $field_label = array('Contact Details|First Name',
							'Contact Details|Last Name',
							'Contact Details|Gender',
							'Contact Details|Nationality',
							'Contact Details|DOB',
							'Contact Details|Age',
							'Contact Details|Address',
							'Contact Details|Occupation',
							'Contact Details|Phone number',
							'Contact Details|Phone number in India',
							'Contact Details|Email',
							'Contact Details|Emergency contact person',
							'Contact Details|Emergency contact number',
							'Stay Details|Planned arrival',
							'Stay Details|planned departure',
							'Stay Details|Duration (months)',
							'Stay Details|Commitment awareness',
							'Stay Details|Special visa',
							'Stay Details|Stay funding',
							'Stay Details|Has family',
							'Stay Details|Family dependent',
							'Stay Details|Health issue',
							'Stay Details|Health details',
							'Stay Details|Can extend stay',
							'Motivations|Prior knowledge of Auroville',
							'Motivations|Expectations of stay',
							'Motivations|Goals/skills to be gained',
                                                        'Skills|fields of interest',
							'Skills|Work Categories',
							'Skills|English language',
							'Skills|Languages Known',
							'Skills|professional skills',
							'Skills|computer skills',
							'Skills|personal strengths',
							'Education Details|Degrees',
							'Education Details|intership',
							'Education Details|Department and Univeristy',
							'Education Details|Subject of study',
							'Education Details|Professor name',
							'Education Details|Professor phone',
							'Education Details|Professor email',
							'Education Details|architecture semester',
							'Education Details|Softwares',
							'Professional Exp|Professional Experience',
						        'Visa Details|passport number',
						        'Visa Details|issue place',
						        'Visa Details|date of issue',
						        'Visa Details|date of validity',
						        'Visa Details|address of consulate',
						       'Initial Contact|prior unit contact',
						       'Initial Contact|Details',
						       'Initial Contact|has agreed to conditions',
						       'Other Details|Comments',
						       'Admin Details|Admin Notes'
 					 );
 					 
                      private $field_value = array('savi_views_contact-details_first-name',
						   'savi_views_contact-details_last-name',
						   'savi_views_contact-details_gender',
						   'savi_views_contact-details_nationality',
						   'savi_views_contact-details_dob',
						   'savi_views_contact-details_age',
						   'savi_views_contact-details_address',
						   'savi_views_contact-details_occupation',
						   'savi_views_contact-details_phone-number',
						   'savi_views_contact-details_phone-number-in-india',
						   'savi_views_contact-details_email',
						   'savi_views_contact-details_emergency-contact',
						   'savi_views_contact-details_emergency-contact-number',
						   'savi_views_stay-details_planned-arrival',
					           'savi_views_stay-details_planned-departure',
					           'savi_views_stay-details_duration',
					           'savi_views_stay-details_commitment-awareness',
					           'savi_views_stay-details_special-visa',
					           'savi_views_stay-details_stay-funding',
					           'savi_views_stay-details_has-family',
					           'savi_views_stay-details_family-dependent',
					           'savi_views_stay-details_health-issue',
					           'savi_views_stay-details_health-details',
					           'savi_views_stay-details_can-extend-stay',
					           'savi_views_motivations_prior-knowledge-of-auroville',
					           'savi_views_motivations_expectations-of-stay',
					           'savi_views_motivations_goals-skills-to-be-gained',
                                                   'savi_views_skills_fields-of-interest',
                                                   'savi_views_skills_work-categories', 
                                                   'savi_views_skills_english',
                                                   'savi_views_skills_other-languages',
                                                   'savi_views_skills_professional-skills',
                                                   'savi_views_skills_computer-skills',
                                                   'savi_views_skills_personal-strengths',
                                                   'savi_views_education-details_degrees',
                                                   'savi_views_education-details_intership',
                                                   'savi_views_education-details_department-university',
                                                   'savi_views_education-details_subject-of-study',
                                                   'savi_views_education-details_professor-name',
                                                   'savi_views_education-details_professor-phone',
                                                  'savi_views_education-details_professor-email',
                                                  'savi_views_education-details_architecture-semester',
                                                  'savi_views_education-details_softwares',
                                                  'savi_views_professional-experiences',
                                                  'savi_views_visa-details_passport-number',
                                                  'savi_views_visa-details_issue-place',
                                                  'savi_views_visa-details_date-of-issue',
                                                  'savi_views_visa-details_date-of-validity',
                                                  'savi_views_visa-details_address-of-consulate',
                                                  'savi_views_initial-contact_prior-unit-contact',
                                                 'savi_views_initial-contact_details',
                                                 'savi_views_initial-contact_has-agreed-to-conditions',
                                                 'savi_views_other-details_comments',
                                                 'savi_views_admin-details_admin-notes'

                  );  
  public function __construct() {
  	add_action("admin_menu", array($this,"savi_vw_pro_add_pages"));
	//add_action('admin_init',  array($this,'add_my_meta_box'),10,2);
	//add_action( 'init', array($this, 'generate_unserialize' ));
  }
  
   function savi_vw_pro_add_pages() {
       // This piece of code attaches the menu page to the menu
       $this->generate_unserialize();
      add_menu_page('View User Profile', 'Edit profile', 'manage_options', 'savi_vw_pro_view_profile',     array($this,'savi_vw_pro_view_profile'), WP_CONTENT_URL . "/plugins/wp-ultimate-csv-importer/images/icon.png");     
      remove_menu_page( 'savi_vw_pro_view_profile','savi_vw_pro_view_profile' );
      // Hide link on listing page
    
    }
   function savi_vw_pro_view_profile() {
   	$redirectURL = "edit.php?post_type=".$_REQUEST['post_type'];
    echo "<h2>View User Profile<a href='".$redirectURL."'class='button' style='float:right;margin-right:50px;'>back to post</a></h2>";
     $post_id = $_GET['id'];//$wp_query->post->ID;
     
     $postmetaArray = get_post_meta($post_id);
        if (sizeof($postmetaArray) > 0) {
            //$name = get_the_title($post_id);
            $fname = $postmetaArray['savi_views_contact-details_first-name'][0];
            $lname = $postmetaArray['savi_views_contact-details_last-name'][0];
            $gender = $postmetaArray['savi_views_contact-details_gender'][0]; 
            $dob = $postmetaArray['savi_views_contact-details_dob'][0]; 
            $age = $postmetaArray['savi_views_contact-details_age'][0]; 
            $address = $postmetaArray['savi_views_contact-details_address'][0]; 
            $occupation = $postmetaArray['savi_views_contact-details_occupation'][0]; 
            $phone = $postmetaArray['savi_views_contact-details_phone-number'][0]; 
            $emg_contact = $postmetaArray['savi_views_contact-details_savi_views_contact-details_emergency-contact'][0]; 
            $emg_contact_phone = $postmetaArray['savi_views_contact-details_emergency-contact-number'][0]; 
            $email = $postmetaArray['savi_views_contact-details_email'][0];
            $country = $postmetaArray['savi_views_contact-details_nationality'][0];
            $phone_in_india = $postmetaArray['savi_views_contact-details_phone-number-in-india'][0];
            $time_of_stay = $postmetaArray['time_of_stay'][0];
            $skills = $postmetaArray['savi_views_skills_fields-of-interest'][0];
            $motivation = $postmetaArray['savi_views_motivations_goals-skills-to-be-gained'][0];  
            
      /*       echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='name'><strong>Name</strong></label>\n";
            echo " </div>";
            echo "<div class='rwmb-input'>\n";
              echo  $fname.$lname."\n";
            echo "</div>";
             echo "</div>";
             echo "<div class='disp-row rwmb-textarea-wrapper'>";
            // Field Definition for Excerpt
                echo " <div class='rwmb-label'>";
                    echo "<label for='gender'><b>Gender</b></label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                    echo $gender."\n";
                echo "</div>";
            echo "</div>";
             echo "<div class='disp-row rwmb-textarea-wrapper'>";
            // Field Definition for Excerpt
                echo " <div class='rwmb-label'>";
                    echo "<label for='dob'><b>Dob</b></label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                    echo $dob."\n";
                echo "</div>";
            echo "</div>";
             echo "<div class='disp-row rwmb-textarea-wrapper'>";
            // Field Definition for Excerpt
                echo " <div class='rwmb-label'>";
                    echo "<label for='age'><b>Age</b></label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                    echo $age."\n";
                echo "</div>";
            echo "</div>";
             echo "<div class='disp-row rwmb-textarea-wrapper'>";
            // Field Definition for Excerpt
                echo " <div class='rwmb-label'>";
                    echo "<label for='address'><b>Address</b></label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                    echo $address."\n";
                echo "</div>";
            echo "</div>";
             echo "<div class='disp-row rwmb-textarea-wrapper'>";
            // Field Definition for Excerpt
                echo " <div class='rwmb-label'>";
                    echo "<label for='occupation'><b>Phone</b></label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                    echo $phone."\n";
                echo "</div>";
            echo "</div>";
             echo "<div class='disp-row rwmb-textarea-wrapper'>";
            // Field Definition for Excerpt
                echo " <div class='rwmb-label'>";
                    echo "<label for='email'><b>Email</b></label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                    echo $email."\n";
                echo "</div>";
            echo "</div>";
              echo "<div class='disp-row rwmb-textarea-wrapper'>";
            // Field Definition for Excerpt
                echo " <div class='rwmb-label'>";
                    echo "<label for='Emergency contact person'><b>Emergency contact person</b></label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                    echo $emg_contact."\n";
                echo "</div>";
            echo "</div>";
             echo "<div class='disp-row rwmb-textarea-wrapper'>";
            // Field Definition for Excerpt
                echo " <div class='rwmb-label'>";
                    echo "<label for='Emergency contact number'><b>Emergency contact number</b></label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                    echo  $emg_contact_phone."\n";
                echo "</div>";
            echo "</div>";
 
             echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='country'><b>Country</b></label>\n";
            echo " </div>";
            echo "<div class='rwmb-input'>\n";
              echo $country."\n";
            echo "</div>";
        echo "</div>";
        
         echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='phone'><b>Phone in India</b></label>\n";
            echo " </div>";
            echo "<div class='rwmb-input'>\n";
              echo $phone_in_india."\n";
                
            echo "</div>";
        echo "</div>";       
        
              echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='skills'><b>Skills</b></label>\n";
            echo " </div>";
            echo "<div class='rwmb-input'>\n";
                
                echo $skills."\n";
            echo "</div>";
        echo "</div>";
            echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='motivation'><b>Motivation</b></label>\n";
            echo " </div>";
            echo "<div class='rwmb-input'>\n";
                
                echo $motivation."\n";
            echo "</div>";
        echo "</div>";            
            
       // echo "</div>";           
    */
      $this->add_my_meta_box();
      $type = $_REQUEST['post_type'];
     ?>
     <script type="text/javascript">
        var post_type = ["view_0", "view_1", "view_2", "view_3", "view_4", "view_5", "view_6", "view_7", "edit-view_0"
		          , "edit-view_1", "edit-view_2", "edit-view_3", "edit-view_4", "edit-view_5", "edit-view_6", "edit-view_7"]; 
		   var current_post_type ='<?php echo $type ?>';      
			jQuery(document).ready(function($) {
			
			   if(jQuery.inArray(current_post_type, post_type) != -1) {
					jQuery('#toplevel_page_main-menu_page').addClass('wp-has-current-submenu wp-menu-open menu-top menu-top-first').removeClass('wp-not-current-submenu');
					jQuery('#toplevel_page_main-menu_page > a').addClass('wp-has-current-submenu').removeClass('wp-not-current-submenu');
	   		}		
				 jQuery('a[href$="edit.php?post_type='+current_post_type+'"]').parent().addClass('current');
		       jQuery('a[href$="edit.php?post_type='+current_post_type+'"]').addClass('current');
			});
	</script>			
     <?php
   }   
  }
      function generate_unserialize() {
  	global $wpdb;
  	$post_id = $_REQUEST['id'];
  	
        $sql467="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 467 AND item_id
                 =(SELECT id FROM wp_frm_items WHERE form_id = 19 AND post_id=$post_id)";
        $results467=$wpdb->get_results($sql467,ARRAY_A);
        $meta_value467 =$results467[0]['meta_value']; 
        $iterm_value467=unserialize($meta_value467);
  	
  	for($i=0;$i<count($iterm_value467);$i++){
                      
                      $table_value467.="<tr><td>".$iterm_value467[$i][0]."</td><td>".$iterm_value467[$i][1]."</td></tr>";   
       }
  	$this->unserialize_array[467]=$table_value467;
        $sql414="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 414 AND item_id
                 =(SELECT id FROM wp_frm_items WHERE form_id = 19 AND post_id=$post_id)";
        $results414=$wpdb->get_results($sql414,ARRAY_A);
        $meta_value414 =$results414[0]['meta_value']; 
        $iterm_value414=unserialize($meta_value414);
  	
  	for($i=0;$i<count($iterm_value414);$i++){
                      
                    $table_value414.="<tr><td>".$iterm_value414[$i][0]."</td><td>".$iterm_value414[$i][1]."</td><td>".$iterm_value414[$i][2]."</td><td>".$iterm_value414[$i][3]."</td><td>".$iterm_value414[$i][4]."</td>        </tr>";  
       }
  	$this->unserialize_array[414]=$table_value414;
  	$sql409="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 409 AND item_id
                 =(SELECT id FROM wp_frm_items WHERE form_id = 19 AND post_id=$post_id)";
        $results409=$wpdb->get_results($sql409,ARRAY_A);
        $meta_value409 =$results409[0]['meta_value']; 
        $iterm_value409=unserialize($meta_value409);
  	
  	for($i=0;$i<count($iterm_value409);$i++){
                      
                      $table_value409.="<tr><td>".$iterm_value409[$i][0]."</td><td>".$iterm_value409[$i][1]."</td></tr>";   
       }
  	$this->unserialize_array[409]=$table_value409;
  	$sql422="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 422 AND item_id
                 =(SELECT id FROM wp_frm_items WHERE form_id = 19 AND post_id=$post_id)";
        $results422=$wpdb->get_results($sql422,ARRAY_A);
        $meta_value422 =$results422[0]['meta_value']; 
        $iterm_value422=unserialize($meta_value422);
  	
  	for($i=0;$i<count($iterm_value422);$i++){
                      
                      $table_value422.="<tr><td>".$iterm_value422[$i][0]."</td><td>".$iterm_value422[$i][1]."</td></tr>";   
       }
  	$this->unserialize_array[422]=$table_value422;
  	$sql424="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 424 AND item_id
                 =(SELECT id FROM wp_frm_items WHERE form_id = 19 AND post_id=$post_id)";
        $results424=$wpdb->get_results($sql424,ARRAY_A);
        $meta_value424 =$results424[0]['meta_value']; 
        $iterm_value424=unserialize($meta_value424);
  	
  	for($i=0;$i<count($iterm_value424);$i++){
                      
                     $table_value424.="<tr><td>".$iterm_value424[$i][0]."</td><td>".$iterm_value424[$i][1]."</td><td>".$iterm_value424[$i][2]."</td><td>".$iterm_value424[$i][3]."</td></tr>";  
       }
  	$this->unserialize_array[424]=$table_value424;	
    }
  function add_my_meta_box() {
  global $wpdb;
  $post_id = $_REQUEST['id'];
  $post_meta_array = get_post_meta( $post_id);   
  $show_field=array();
  $old_label="";
  $cur_label="";
  $string="";
  $cnt =count($this->field_label);
  echo '<div class="postbox-container" id="postbox-container-2" style="width:95%">';
    echo ' <div class="meta-box-sortables ui-sortable" id="normal-sortables">';
  for($i=0;$i<$cnt ;$i++){
       
      $explode_label = $this->field_label[$i];
       
       $label="";
       $label= explode("|",$explode_label);
       $curr_label = $label[0];
       $old_label =($old_label==""?$curr_label:$old_label);
      
       if($i == $cnt-1 ){
            $tmp =$this->field_value[$i];
            $value = $post_meta_array[$tmp][0];
           
     	      	         $string.="<div class='mainDiv1'><div class='LabelDiv1'>".$label[1]."</div>";
         		 $string.="<div class='ValueDiv1'>".$value."</div></div>";
            	  
                         $show_field[] = $string;   
            	  
           			echo '<div class="postbox closed" id="mymetabox_'.$curr_label.'">';
							echo '<div title="Click to toggle" class="handlediv"><br></div>';
							echo '<h3 class="hndle" style="padding: 8px 12px;"><span>'.$curr_label.'</span></h3>';
							echo '<div class="inside">';
						 		echo '<div class="Wrapper">';	
								 		echo $string;
					     		echo '</div>';	 												
					   	echo '</div>';	 												
                 echo '</div>';
           
        }       
       else{
               $tmp =$this->field_value[$i];
               $value = $post_meta_array[$tmp][0];
             switch($tmp){
           
                case 'savi_views_skills_work-categories':
               
                     $field_id =467;
                     $table_value467=$this->unserialize_array[467];
                     $table_head="<table class='frm-table' width='100%'><thead><tr><th>Work Area</th><th>Work type</th></tr></thead>";
                     $table_body="<tbody>". $table_value467."</tbody>";
                     $table_footer="</table>";
                     $string.="<div class='mainDiv'><div class='LabelDiv'>Work Area & Work Type</div>";
                     $string.="<div class='ValueDiv'>".$table_head.$table_body.$table_footer."</div></div>";
                   
                     break;
                     
                 case'savi_views_education-details_degrees':
                     $field_id =414;
                    $table_value414=$this->unserialize_array[414];
                     $table_head="<table class='frm-table' width='100%'><thead><tr><th>Subject</th><th>College</th><th>Country</th>";
                     $table_head.="<th>Year</th><th>Degree</th></tr></thead>"; 
                     $table_body="<tbody>".$table_value414."</tbody>";
                     $table_footer="</table>";
                     $string.="<div class='mainDiv'><div class='LabelDiv'>Subject known</div>";
                     $string.="<div class='ValueDiv'>".$table_head.$table_body.$table_footer."</div></div>";
                    
                     break;            
     	        case 'savi_views_skills_other-languages':
                     $field_id =409;
                     $table_value409=$this->unserialize_array[409];
                     $table_head="<table class='frm-table' width='100%'><thead><tr><th>Language</th><th>Levels</th></tr></thead>";
                     $table_body="<tbody>".$table_value409."</tbody>";
                     $table_footer="</table>";
                     $string.="<div class='mainDiv'><div class='LabelDiv'>Languages known</div>";
                     $string.="<div class='ValueDiv'>".$table_head.$table_body.$table_footer."</div></div>";
                     
                     break;
                 case'savi_views_education-details_softwares':
                     $field_id =422;
                     $table_value422=$this->unserialize_array[422];
                     $table_head="<table class='frm-table' width='100%'><thead><tr><th>Software</th><th>Levels</th></tr></thead>";
                     $table_body="<tbody>".$table_value422."</tbody>";
                     $table_footer="</table>";
                     $string.="<div class='mainDiv'><div class='LabelDiv'>Software known</div>";
                     $string.="<div class='ValueDiv'>".$table_head.$table_body.$table_footer."</div></div>";
                    
                     break;     
                case 'savi_views_professional-experiences':
                     $field_id =424;
                     $table_value424=$this->unserialize_array[424];
                      $table_head="<table class='frm-table' width='100%'><thead><tr><th>Work title</th><th>Achievements</th><th>Dates</th>";
                     $table_head.="<th>Duration</th></tr></thead>"; 
                     $table_body="<tbody>".$table_value424."</tbody>";
                     $table_footer="</table>";
                     $string.="<div class='mainDiv'><div class='LabelDiv'>Subject known</div>";
                     $string.="<div class='ValueDiv'>".$table_head.$table_body.$table_footer."</div></div>";
                   
                     break;
                   	     
     	        default:
     	      	         $string.="<div class='mainDiv'><div class='LabelDiv'>".$label[1]."</div>";
         		 $string.="<div class='ValueDiv'>".$value."</div></div>";
            	        
            	         break;		 
            	        
             }  
               $explode_nextlabel = $this->field_label[$i+1];
       
                 $nextlabel= explode("|",$explode_nextlabel);
                 $next_label = $nextlabel[0];
      
               
      		 if(trim(strtolower($curr_label))!=trim(strtolower($next_label)) ){
          		   $show_field[] = $string; 
          		   echo '<div class="postbox closed" id="mymetabox_'.$curr_label.'">';
							echo '<div title="Click to toggle" class="handlediv"><br></div>';
							echo '<h3 class="hndle" style="padding: 8px 12px;"><span>'.$curr_label.'</span></h3>';
							echo '<div class="inside">';
						 		echo '<div class="Wrapper">';	
								 		echo $string;
					     		echo '</div>';	 												
					   	echo '</div>';	 												
                 echo '</div>';
       	  		  /* add_meta_box( 'mymetabox'."_".$curr_label,$curr_label,array($this,'showfields'),'savi_vw_pro_view_profile',
           																'normal','low', $show_field);*/
            	           $string="";
             		    unset($show_field);
                  }
       }
      
       
       $old_label = $curr_label; 
  
  }
  echo '</div>';
    echo '</div>';
}

}
?>