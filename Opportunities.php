<?php
class Opportunity
{

   private $custom_type = 'av_opportunity';

   public function __construct()
    {
        // Create a hook to create custom Post Type
        add_action( 'init', array($this, 'create_post_type' ));
  
       /* add_action( 'init', array($this, 'change_prerequest' ));*/

        // Create a hook to create metabox for the custom post type
        add_action( 'add_meta_boxes', array($this, 'init_metabox' ));

       // Call the Units_Metabox when showing the post
        add_action( 'add_meta_boxes', array($this, 'init_admin_metabox'));

        // Create a hook to save the custom post type
        add_action( 'save_post', array($this, 'save_opportunity_postdata' ),1,2);

        // Hook into the 'init' action
        add_action( 'init', array($this,'savi_opportunity_taxonomy'), 0 );


       // Hook into the 'init' action
       add_action( 'init', array($this,'savi_opportunity_categories'), 0 );


    }

     public function change_prerequest() {
     
     global $wpdb;
     $my_query = new WP_Query('post_type=av_opportunity&posts_per_page=-1');
      while ($my_query->have_posts()) : 
      	$my_query->the_post(); 
      	$postID = get_the_ID();
        $prereuest = get_post_meta($postID,'savi_opp_tag_prerequisites',true);
      	if(!empty($prereuest)){
                $array_prereuest = explode("|",$prereuest);
                $saved_prerequisites="";
       		for($i=0;$i<count($array_prereuest);$i++){
            	   $saved_prerequisites.= $array_prereuest[$i]." ";
       		}
       		echo "Prereuqest :".$saved_prerequisites."<br>";
        	$wpdb->query("UPDATE wp_postmeta SET meta_key ='prerequisites' WHERE post_id = '$postID' and meta_key ='savi_opp_tag_prerequisites' ");
        	$prerequisites = sanitize_text_field( $saved_prerequisites);
        	$wpdb->query("UPDATE wp_postmeta SET meta_value ='$saved_prerequisites' WHERE post_id = '$postID' and meta_key ='prerequisites' ");
      	}
      endwhile;  
      wp_reset_query(); 
     
    
     
     }



    // Create a Custom Post Type
    public function create_post_type() {
        register_post_type( $this->custom_type,
            array(
                'labels' => array(
                    'name' => __( 'Opportunities' ),
                    'singular_name' => __( 'Opportunity' )
                ),
                'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments','revisions'),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => $this->custom_type),
			
            )
        );
    }


   // Register Custom Taxonomy
public function savi_opportunity_taxonomy()  {

	/*$labels = array(
		'name'                       => _x( 'Skills', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Skill', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Skills', 'text_domain' ),
		'all_items'                  => __( 'All skills', 'text_domain' ),
		'parent_item'                => __( 'Parent skill', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent skill:', 'text_domain' ),
		'new_item_name'              => __( 'New skill', 'text_domain' ),
		'add_new_item'               => __( 'Add new skill', 'text_domain' ),
		'edit_item'                  => __( 'Edit skill', 'text_domain' ),
		'update_item'                => __( 'Update skill', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate skills with commas', 'text_domain' ),
		'search_items'               => __( 'Search skills', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove skills', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used skills', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	*/
	$labels_soft = array(
		'name'                       => _x( 'Softwares', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Software', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Softwares', 'text_domain' ),
		'all_items'                  => __( 'All softwares', 'text_domain' ),
		'parent_item'                => __( 'Parent software', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent software:', 'text_domain' ),
		'new_item_name'              => __( 'New software', 'text_domain' ),
		'add_new_item'               => __( 'Add new software', 'text_domain' ),
		'edit_item'                  => __( 'Edit software', 'text_domain' ),
		'update_item'                => __( 'Update software', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate softwares with commas', 'text_domain' ),
		'search_items'               => __( 'Search softwares', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove softwares', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used softwares', 'text_domain' ),
	);
	$args_soft = array(
		'labels'                     => $labels_soft,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
    $labels_languages = array(
		'name'                       => _x( 'Languages', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Language', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Languages', 'text_domain' ),
		'all_items'                  => __( 'All languages', 'text_domain' ),
		'parent_item'                => __( 'Parent language', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent language:', 'text_domain' ),
		'new_item_name'              => __( 'New language', 'text_domain' ),
		'add_new_item'               => __( 'Add new languages', 'text_domain' ),
		'edit_item'                  => __( 'Edit languages', 'text_domain' ),
		'update_item'                => __( 'Update languages', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate languages with commas', 'text_domain' ),
		'search_items'               => __( 'Search languages', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove languages', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used languages', 'text_domain' ),
	);
	$args_languages = array(
		'labels'                     => $labels_languages,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);


	//register_taxonomy( 'savi_opp_tag_skills', $this->custom_type, $args );
	register_taxonomy( 'savi_opp_tag_soft', $this->custom_type, $args_soft );
    register_taxonomy( 'savi_opp_tag_languages', $this->custom_type, $args_languages );


}


/*

// Register Custom Taxonomy

*/

function savi_opportunity_categories()  {

	$labels_units = array(
		'name'                       => _x( 'Work Type', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Work Type', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Work Type', 'text_domain' ),
		'all_items'                  => __( 'All Work Type', 'text_domain' ),
		'parent_item'                => __( 'Parent Work Type', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Work Type:', 'text_domain' ),
		'new_item_name'              => __( 'New Work Type', 'text_domain' ),
		'add_new_item'               => __( 'Add new work type', 'text_domain' ),
		'edit_item'                  => __( 'Edit work type', 'text_domain' ),
		'update_item'                => __( 'Update work type', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate Work Type with commas', 'text_domain' ),
		'search_items'               => __( 'Search Work Type', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove Work Type', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used Work Type', 'text_domain' ),
	);
	$args_units = array(
		'labels'                     => $labels_units,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
		register_taxonomy( 'savi_opp_cat_work_type', $this->custom_type, $args_units );

		$labels_area = array(
		'name'                       => _x( 'Work Areas', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Work Area', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Work Areas', 'text_domain' ),
		'all_items'                  => __( 'All Work Areas', 'text_domain' ),
		'parent_item'                => __( 'Parent Work Area', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Work Area:', 'text_domain' ),
		'new_item_name'              => __( 'New Work Area', 'text_domain' ),
		'add_new_item'               => __( 'Add new work area', 'text_domain' ),
		'edit_item'                  => __( 'Edit work area', 'text_domain' ),
		'update_item'                => __( 'Update work area', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate Work Areas with commas', 'text_domain' ),
		'search_items'               => __( 'Search Work Areas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove Work Area', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used Work Areas', 'text_domain' ),
	);
	$args_area = array(
		'labels'                     => $labels_area,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
		register_taxonomy( 'savi_opp_cat_work_area', $this->custom_type, $args_area );


}


    // Create a new Metabox for the Custom Post Type
    public function init_metabox() {
        // Callback function hooked to opportunity_metabox function
        add_meta_box( 'opportunities', "Opportunities", array($this,'opportunity_metabox'), $this->custom_type, 'advanced', 'high');
    }

   public function init_admin_metabox() {


    add_meta_box( 'opportunity_admin', "Admin", array($this,'admin_metabox'), $this->custom_type, 'normal', 'low');
    }


   // include js and css for display the datepicker
   static function admin_enqueue_scripts()
		{
			$url = RWMB_CSS_URL . 'jqueryui';
			wp_register_style( 'jquery-ui-core', "{$url}/jquery.ui.core.css", array(), '1.8.17' );
			wp_register_style( 'jquery-ui-theme', "{$url}/jquery.ui.theme.css", array(), '1.8.17' );
			wp_enqueue_style( 'jquery-ui-datepicker', "{$url}/jquery.ui.datepicker.css", array( 'jquery-ui-core', 'jquery-ui-theme' ), '1.8.17' );

			// Load localized scripts
			$locale = str_replace( '_', '-', get_locale() );
			$file_path = 'jqueryui/datepicker-i18n/jquery.ui.datepicker-' . $locale . '.js';
			$deps = array( 'jquery-ui-datepicker' );
			if ( file_exists( RWMB_DIR . 'js/' . $file_path ) )
			{
				wp_register_script( 'jquery-ui-datepicker-i18n', RWMB_JS_URL . $file_path, $deps, '1.8.17', true );
				$deps[] = 'jquery-ui-datepicker-i18n';
			}

			wp_enqueue_script( 'rwmb-date', RWMB_JS_URL . 'date.js', $deps, RWMB_VER, true );
			wp_localize_script( 'rwmb-date', 'RWMB_Datepicker', array( 'lang' => $locale ) );

			// Required for the dynamic dropdown
			$plugin_dir = plugins_url('', __FILE__ );
          $dir =  get_bloginfo('url') . '/wp-content/plugins';
            //wp_enqueue_style( 'style-name', $plugin_dir .'/css/bootstrap.css', array(), "1.00");
            wp_enqueue_style( 'style-name-1', $plugin_dir .'/css/bootstrap-combobox.css', array(), "1.00");
            wp_enqueue_style( 'style-name-3', $dir .'/meta-box/css/select.css', array(), "4.33");
            wp_enqueue_style( 'style-name-4', $dir .'/meta-box/css/style.css', array(), "4.33");
            wp_enqueue_script( 'script-name-2', $plugin_dir .'/js/bootstrap.js', array('jquery'), '1.0.0', false );
            wp_enqueue_script( 'script-name-3', $plugin_dir .'/js/bootstrap-combobox.js', array('jquery'), '1.0.0', false );
		}

	 public function admin_metabox($post) {

        $post_id = $_GET['post'];//$wp_query->post->ID;

        $postmetaArray = get_post_meta($post->ID);
        if (sizeof($postmetaArray) > 0) {
            $revisions = $postmetaArray['comments'][0];
            $excerpt = $postmetaArray['excerpt'][0];
          
            
        }
        else {
          $revisions = '';
          $excerpt = '';
        }
        echo "<div class='disp-row rwmb-textarea-wrapper'>";
            // Field Definition for Comments
                echo " <div class='rwmb-label'>";
                    echo "<label for='revisions'>Revisions</label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                    echo "<textarea rows='4' cols='50' name='revisions' class='rwmb-textarea large-text'>$revisions</textarea>";
                echo "</div>";
            echo "</div>";

           /* echo "<div class='disp-row rwmb-textarea-wrapper'>";
            // Field Definition for Excerpt
                echo " <div class='rwmb-label'>";
                    echo "<label for='excerpt'>Excerpt</label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                    echo "<textarea rows='4' cols='50' name='excerpt' id='excerpt' class='rwmb-textarea large-text'>$excerpt</textarea>";
                echo "</div>";
            echo "</div>";
         */
                
          

   }

    // Called when the custom posttype is saved
    public function save_opportunity_postdata( $post_id ) {
        global $post,$wpdb ;

        if( $post->post_type != $this->custom_type ) return $post_id;
        /*
        * We need to verify this came from the our screen and with proper authorization,
        * because save_post can be triggered at other times.
        */
        // Comment by Venkat - Removed the Nonce for the moment. Will do it later...

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            echo "Autosaved....";
            return $post_id;
        }

        // Check the user's permissions.
        if ( $this->custom_type == $_POST['post_type'] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) )
                return $post_id;

        } else {

            if ( ! current_user_can( 'edit_post', $post_id ) )
                return $post_id;
        }

        /* OK, its safe for us to save the data now. */

        // Sanitize user input.


        //$opptun_title = sanitize_text_field( $_POST['opportunity_title'] );
        $av_units = $_POST['av_unit'];
        $unit_project = $_POST['projectname'];
        $contact_name = $_POST['contactPerson'];
        $contact_email = sanitize_text_field( $_POST['contactEmail'] );
        $contact_phone = sanitize_text_field( $_POST['contactPhone'] );

        $revisions = sanitize_text_field( $_POST['revisions'] );
        $startdate = sanitize_text_field( $_POST['startdate']);
        $enddate = sanitize_text_field( $_POST['enddate']);
        $duration = sanitize_text_field( $_POST['duration']);
        $number_of_volunteers = sanitize_text_field( $_POST['number_of_volunteers']);
        $computer_required = sanitize_text_field( $_POST['computerrequired']);
        $timing = sanitize_text_field( $_POST['timing']);
        $morning_timings = sanitize_text_field( $_POST['morningtimings']);
        $afternoon_timings = sanitize_text_field( $_POST['afternoontimings']);
        $skillsGain = sanitize_text_field( $_POST['skills_gain']);
        $dailyTasks = sanitize_text_field( $_POST['daily_tasks']);
        $previousunit = sanitize_text_field( $_POST['previous_unit']);        
        $previousproject = sanitize_text_field( $_POST['previous_project']);  
        $prerequisites = sanitize_text_field( $_POST['prerequisites']);
        $no_of_opportunities = sanitize_text_field( $_POST['no_of_opportunities']); 
        $opportunity_status = sanitize_text_field( $_POST['opportunity_status']); 
         
        //Get the assoiciate opportunities for that unit   
        $associateOpportunityMeta = get_post_meta($av_units, 'unit_opportunity', false);
        $unitAllOpportunities = $associateOpportunityMeta[0];
        $is_unit_opp ="no";
     
        //Get the assoiciate opportunities for that project
        $associateProjectMeta = get_post_meta($unit_project, 'project_opportunity', false);
        $projectAllOpportunities = $associateProjectMeta[0];
        $is_project_opp ="no"; 
   
        /*This section saving the opportunity to units starts */
       
        if (sizeof($unitAllOpportunities) > 0 && is_array($unitAllOpportunities)) : // already this unit having opportunities
            foreach($unitAllOpportunities as $key=>$unitOpportunity) {
              if($unitOpportunity['unit_opportunity'] == $post_id ) : // check the opportunity is exits in the array
                $is_unit_opp ="yes";
              endif; 
            }
            if( $is_unit_opp == "no" ) : // if the opportunity not exists in the array it will add the opportunity to the array
                $opportunityInfo = array ("unit_opportunity" => $post_id,);  
                $unitAllOpportunities[] = $opportunityInfo;   
              endif;    
        else : // Insert new opportunity to the unit
             $opportunityInfo = array ("unit_opportunity" => $post_id,);  
             $unitAllOpportunities[] = $opportunityInfo;         
        endif;
       update_post_meta( $av_units, 'unit_opportunity', $unitAllOpportunities);  // saving current unit opportunity 
       if( $previousunit != $av_units ) :
       // check the previous unit with current unit if both are not equal then remove the opportunity from the unit
          $associatePreviousOpportunityMeta = get_post_meta($previousunit, 'unit_opportunity', false);
          $previousUnitAllOpportunities = $associatePreviousOpportunityMeta[0];
       
          $currentUnitAllOpportunities = array(); 
          if (sizeof( $previousUnitAllOpportunities ) > 0 && is_array( $previousUnitAllOpportunities )) :
              foreach($previousUnitAllOpportunities as $key=>$unitPreviousOpportunity) {
                  if( $unitPreviousOpportunity['unit_opportunity'] != $post_id ) :
                      $updateopportunityInfo = array ("unit_opportunity" => $unitPreviousOpportunity['unit_opportunity'],);  
                      $currentUnitAllOpportunities[] = $updateopportunityInfo;   
                  endif; 
              }
         endif;
            
         update_post_meta( $previousunit, 'unit_opportunity', $currentUnitAllOpportunities); // update the previous unit opportunities 
      endif; 
     /*This section saving the opportunity to units ends */  
    
     /*This section saving the opportunity to projects starts */

        if (sizeof($projectAllOpportunities) > 0 && is_array($projectAllOpportunities)) : // already this project having opportunities
            foreach($projectAllOpportunities as $key=>$projectOpportunity) {
              if($projectOpportunity['project_opportunity'] == $post_id ) : // check the opportunity is exits in the array
                $is_project_opp ="yes";
              endif; 
            }
            if( $is_project_opp == "no" ) : // if the opportunity not exists in the array it will add the opportunity to the array
              $opportunityprojectInfo = array ("project_opportunity" => $post_id,);  
              $projectAllOpportunities[] = $opportunityprojectInfo;     
              endif;    
        else : // Insert new opportunity to the project
             $opportunityprojectInfo = array ("project_opportunity" => $post_id,);  
             $projectAllOpportunities[] = $opportunityprojectInfo;         
        endif;
        if($unit_project != 0) :  //Not update the post meta for General Project
         update_post_meta( $unit_project, 'project_opportunity', $projectAllOpportunities); // saving current project opportunity 
        endif;
        if( $previousproject != $unit_project ) :
         
         // check the previous project with current project if both are not equal then remove the opportunity from the unit
          $associatePreviousProjectOpportunityMeta = get_post_meta($previousproject, 'project_opportunity', false);
          $projectPreviousAllOpportunities = $associatePreviousProjectOpportunityMeta[0];
          $currentProjectAllOpportunities = array(); 
          if (sizeof($projectPreviousAllOpportunities) > 0 && is_array($projectPreviousAllOpportunities)) :
              foreach($projectPreviousAllOpportunities as $key=>$projectPreviousOpportunity) {
                  if( $projectPreviousOpportunity['project_opportunity'] != $post_id) :
                      $updateopportunityInfo = array ("project_opportunity" => $projectPreviousOpportunity['project_opportunity'],);  
                      $currentProjectAllOpportunities[] = $updateopportunityInfo;   
                  endif; 
              }
         endif;
          if($previousproject != 0) :  //Not update the post meta for General Project
            update_post_meta( $previousproject, 'project_opportunity', $currentProjectAllOpportunities);
          endif;        
          // update the previous project opportunities
      endif; 
      

     /*This section saving the opportunity to projects ends */    

        update_post_meta( $post_id, 'av_unit', $av_units);
        update_post_meta( $post_id, 'projectname', $unit_project);
        update_post_meta( $post_id, 'contactPerson', $contact_name);
        update_post_meta( $post_id, 'contactEmail', $contact_email);
        update_post_meta( $post_id, 'contactPhone', $contact_phone);

        update_post_meta( $post_id, 'revisions', $revisions);
        update_post_meta( $post_id, 'startdate', $startdate);
        update_post_meta( $post_id, 'enddate', $enddate);
        update_post_meta( $post_id, 'duration', $duration);
        update_post_meta( $post_id, 'number_of_volunteers', $number_of_volunteers);
        update_post_meta( $post_id, 'architect_semester', $architect_semester);

        update_post_meta( $post_id, 'computerrequired', $computer_required);
        update_post_meta( $post_id, 'timing', $timing);
        update_post_meta( $post_id, 'morningtimings', $morning_timings);
        update_post_meta( $post_id, 'afternoontimings', $afternoon_timings);
        update_post_meta( $post_id, 'skills_gain', $skillsGain);
        update_post_meta( $post_id, 'daily_tasks', $dailyTasks);
        update_post_meta( $post_id, 'prerequisites', $prerequisites);
        update_post_meta( $post_id,'no_of_opportunities',$no_of_opportunities);
        update_post_meta( $post_id,'opportunity_status',$opportunity_status);
        
        if(isset($_POST['hidden_contact_user']) &&  $_POST['hidden_contact_user'] !=""){
			update_post_meta( $post_id,'contact_user',$_POST['hidden_contact_user']);
		}
        
        /* ==========================================================================
           check the mentor already create or not, if not exists create new mentor 
           user
        ============================================================================*/
        
        if (email_exists($contact_email) == false && !empty($contact_email) ) :
        			
     	        $current_increment_id = $wpdb->get_var("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".DB_NAME."' AND TABLE_NAME = 'wp_users'" );
                		
                $mentorName = "mentor".$current_increment_id;
          		$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
	       		$user_id = wp_create_user( $mentorName, $random_password, $contact_email );
	       	
                $Name =  $contact_name;
	       		wp_update_user( array( 'ID' => $user_id, 'user_nicename' => $Name ,"display_name" => $Name) );
	       		$wp_user_object = new WP_User($user_id);
          		
          		add_user_meta( $user_id, 'profile_post_id', $post_id); 
          		add_user_meta( $user_id, 'savi_role', 'opportunity-owner'); 
          		add_user_meta( $user_id, 'mentor_phone',$contact_phone ); 
				update_post_meta( $post_id,'contact_user',$user_id);
				   
          		/*$htmlmessage = $this->saviGetTemplate($postID,$user_id,$random_password);
          		
          		$site_url = get_bloginfo('wpurl');
          		add_filter( 'wp_mail_content_type', array($this,'set_html_content_type') );
          		$subject = "New User Created: ".$site_url."";
          		wp_mail($clientEmail, $subject, $htmlmessage);*/
          
        else:
        
           if($contact_email!=""){
			 
			    $contact_user = get_post_meta($post_id,'contact_user',true);
			   
			    if($contact_user ==""){
				     
				   $user_id = get_user_by( 'email', $contact_email )->ID;
				   update_post_meta( $post_id,'contact_user',$user_id);	
			    }		
			   
		   }    
                       
        endif; 

    }

    public function opportunity_metabox($post) {
        global  $wpdb;
        $postmetaArray = get_post_meta($post->ID);
        $projects = get_post_meta($post->ID, 'language', false);
        $allProjects = $projects[0];
        if (sizeof($postmetaArray) > 0) {

            $saved_AVUnit = $postmetaArray['av_unit'][0];
            $saved_projname = $postmetaArray['projectname'][0];
            $saved_contactPerson = $postmetaArray['contactPerson'][0];
            $saved_email = $postmetaArray['contactEmail'][0];
            $saved_phone = $postmetaArray['contactPhone'][0];
            //$saved_language= unserialize($postmetaArray['language'][0]);
            $saved_comments = $postmetaArray['revisions'][0];
            $saved_startdate = $postmetaArray['startdate'][0];
            $saved_enddate = $postmetaArray['enddate'][0];
            $saved_duration = $postmetaArray['duration'][0];
            $saved_number_of_volunteers = $postmetaArray['number_of_volunteers'][0];
            $saved_architect_semester  = $postmetaArray['architect_semester'][0];
            $computer_required = $postmetaArray['computerrequired'][0];
            $timing = $postmetaArray['timing'][0];
            $morning_timings = $postmetaArray['morningtimings'][0];
            $afternoon_timings = $postmetaArray['afternoontimings'][0];
            $saved_architect_semester = $postmetaArray['architect_semester'][0];
            $saved_skillsGain = $postmetaArray['skills_gain'][0];
            $saved_dailyTasks = $postmetaArray['daily_tasks'][0];
            $saved_prerequisites = $postmetaArray['prerequisites'][0];
            $saved_no_of_opportunities = $postmetaArray['no_of_opportunities'][0];
            $opportunity_status = $postmetaArray['opportunity_status'][0];
             

        } else {

            $saved_AVUnit = '';
            $saved_projname = '';
            $saved_contactPerson = '';
            $saved_email = '';
            $saved_phone = '';
            //$saved_language= '';
            $saved_comments = '';
            $saved_startdate = '';
            $saved_enddate = '';
            $saved_duration = '';
            //$saved_otherlanguage = '';
            $computer_required = '';
            $timing = '';
            $morning_timings = '';
            $afternoon_timings = '';
            $saved_architect_semester = '';
            $saved_skillsGain = "";
            $saved_dailyTasks = "";
            $saved_prerequisites ="";
            $saved_no_of_opportunities ="";
            $opportunity_status =""; 
        }

        $AVUnitQuery = new WP_Query( array(
                    'post_type' => 'av_unit',
                    'posts_per_page' => -1,
                    'post_status' => array( 'publish' ),
                    ));
        $postIndex = 0;
        $projIndex = 0;

        // Start constructing the Select options for AV Units
        $unitSelectHTML = "<select class='combobox' name='av_unit' id='av_unit' onchange='unitChanged()' name='inline'> \n<option></option>\n";

        if ($AVUnitQuery->found_posts > 0) {
            while ($AVUnitQuery->have_posts()) {
                $AVUnitQuery->the_post();
                $unitname = get_the_title();
				$unitID = get_the_ID();
                $firstrow = true;

                // Construct the options for the select for AV Unit
                $unitSelectHTML .= "<option value='$unitID'";

                // If the exisitng value of the AV Unit is equal to the current loop value then select the option
                if ($unitID == $saved_AVUnit  ||  $unitname == $saved_AVUnit)
                    $unitSelectHTML .= "selected";

                // End the Construct for the option
                $unitSelectHTML .= ">$unitname</option>\n";
                $unitProjectArray[] = new unitProject($unitID, 0);
            }

        }
        $unitSelectHTML .= "</Select>";
        wp_reset_query();
       // echo"<pre>",print_r($unitProjectArray),"</pre>".sizeof( $AVUnitQuery);
        // Now we start on the Projects - The confusing mama ....
        $AVProjectQuery = new WP_Query( array(
                    'post_type' => 'av_project',
                    'posts_per_page' => -1,
                    'post_status' => array( 'publish' ),
                    ));


        // First we assemble the list of projects and the associated AV Units in an object Array
        if ($AVProjectQuery->found_posts > 0) {
            while ($AVProjectQuery->have_posts()) {
                $AVProjectQuery->the_post();
                $projname = get_the_title();
                $postID = get_the_ID();

                $parentUnitsMeta = get_post_meta($postID, 'parent_unit', false);
                $allParentUnits = $parentUnitsMeta[0];
                if (sizeof($allParentUnits) > 0) {
                    foreach($allParentUnits as $key=>$parentUnits) {
                        $unitProjectArray[] = new unitProject($parentUnits['parent_unit'], $postID);
                    }

                }
            }
          wp_reset_query();
        }

        // Now We add a "General" project to all the unitSelectHTML


        // Get the Object Array sorted on UnitName
        //usort($unitProjectArray, 'psort');

        if (sizeof($unitProjectArray) > 0) {
            foreach ($unitProjectArray as $key => $row) {
              $unit[$key]  = $row->unit;
              $project[$key] = $row->project;
            }

            array_multisort($unit, SORT_ASC, $project, SORT_ASC, $unitProjectArray);
        }

        // Start Constructing the select options for all projects (to be used as the template when the user select any AV Unit
        $projhiddenSelectHTML = "<select style='display:none;' id='projectname_all'>\n";
        $projSelectHTML = "<select class='combobox' id='projectname' name='projectname'> \n<option></option>\n";
        $pr_avUnitName = "";

        for ($arrayIndex=0;$arrayIndex < sizeof($unitProjectArray);$arrayIndex++) {
            $avUnitName = $unitProjectArray[$arrayIndex]->unit;
            $tmpUnit = str_replace(" ", "_", $avUnitName);
            $projID   = $unitProjectArray[$arrayIndex]->project; 
            $projname = ($unitProjectArray[$arrayIndex]->project) == 0?"General":get_post($unitProjectArray[$arrayIndex]->project)->post_title;
            if ($avUnitName == $saved_AVUnit) {
                $projSelectHTML .= "<option value='$projID'";
                if ($projID == $saved_projname) {
                    $projSelectHTML .= "selected";
                }
                // End the construct of the option
                $projSelectHTML .= ">$projname</option>\n";
            }
            if ($avUnitName != $pr_avUnitName) {
                if (!firstrow) {
                    $projhiddenSelectHTML .= "</optgroup>\n";
                }

                $projhiddenSelectHTML .= "<optgroup id='optgroup{$tmpUnit}'>";
                // $projhiddenSelectHTML .= "<option value='General'>General</option>\n";
                $pr_avUnitName = $avUnitName;
            }

            $projhiddenSelectHTML .= "<option value='$projID'>$projname</option>\n";
        }

        $projhiddenSelectHTML .= "</select>";

        wp_reset_query();

        // Start constructing the select options for Projects (within a particular AV Unit)

	/*
        if ($saved_projname != "") {
            $projSelectHTML .= "<option value='General' ";
            if ($saved_projname == 'General') {
                $projSelectHTML .= "Selected";
            }
            $projSelectHTML .= ">General</option>\n";
        }*/

        $projSelectHTML .= "</select>\n";


        ?>
        <style>
        .disp-row {


        }

        input {
            height: auto !important;
        }
        .label {
            display: inline;
            width: 300px;
            color: black;

        }

        .input {
                display: inline-block;
        }

        </style>


        <?php 

        // Field definition for AV Units
        echo "<div class='disp-row'>";
          echo " <div class='rwmb-label'>";
             echo "<label for='av_unit'>Unit filter</label>";
           echo "</div>";
            echo "<div class='rwmb-input'>\n";
                echo $unitSelectHTML; // We have constructed this earlier
                echo "<input type='hidden' name='previous_unit' value='$saved_AVUnit' />\n";
            echo "</div>";
        echo "</div>";

        // This is used for filtering the project name based on the AV Unit Selected
        echo $projhiddenSelectHTML;


        // Field Defintion for Project Name
        echo "<div class='disp-row'>";
          echo " <div class='rwmb-label'>";
            echo "<label for='project_name'>Project</label>\n";
          echo " </div>";
            echo "<div class='input'>\n";
                echo $projSelectHTML; // We have constructed this earlier
                 echo "<input type='hidden' name='previous_project' value='".($saved_projname ==""?0:$saved_projname)."' />\n"; 
            echo "</div>";
        echo "</div>";

          // Field Definition for Mentor users shows while creating new opportunity
       if(!isset($_REQUEST['post'])):
        $mentor_users =  get_users( array('meta_key' => 'savi_role','meta_value' => 'opportunity-owner','meta_compare' 
        =>'=','meta_type' => CHAR ) );
       // echo"<pre>",print_r($mentor_users),"</pre>";
       foreach($mentor_users as $mentor_user){
		   if($mentor_user->ID !=1){
			   $user_details =$mentor_user->ID."|".$mentor_user->display_name."|".$mentor_user->user_email."|"
									 .get_user_meta($mentor_user->ID,'mentor_phone',true);
			   $mentor_user_select.="<option value = '$user_details'>".$mentor_user->display_name."</option>";
		   }
	   }
        echo "<div class='disp-row'>";
          echo " <div class='rwmb-label'>";
            echo "<label for='contactPerson'>Mentor Users</label>\n";
          echo " </div>";
            echo "<div class='input'>\n";
             echo "<input type='hidden' name='hidden_contact_user' id='hidden_contact_user' value='' />";
               echo  '<select  id="mentor_user" name="mentor_user" class="rwmb-select" onchange="mentor_userChanged()">';
               echo "<option value=''>Select Mentor User to update the  opportunity</option>";
                echo $mentor_user_select;
                echo "<option value='add_new_user'>Add New User</option>";
                echo '</select>';
            echo "</div>";
        echo "</div>";
        endif;
          // Field Definition for Contact Person
        echo "<div class='disp-row'>";
          echo " <div class='rwmb-label'>";
            echo "<label for='contactPerson'>Contact Person</label>\n";
          echo " </div>";
            echo "<div class='input'>\n";
                echo "<input type='text' name='contactPerson' value='$saved_contactPerson' />\n";
            echo "</div>";
        echo "</div>";

        // Field Definition for Title
        echo "<div class='disp-row'>";
             echo " <div class='rwmb-label'>";
                echo "<label for='contactEmail'>Contact Email</label>\n";
             echo " </div>";
            echo "<div class='input'>\n";
                echo "<input type='email' name='contactEmail' value='$saved_email' />\n";
            echo "</div>";
        echo "</div>";

        // Field Definition for Phone
        echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='contactPhone'>Contact Phone</label>\n";
            echo " </div>";
            echo "<div class='input'>\n";
                echo "<input type='phone' name='contactPhone'  value='$saved_phone' />\n";
            echo "</div>";
        echo "</div>";

          // Field Defintion for start date
         echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='startdate'>Start Date</label>\n";
            echo " </div>";
            echo "<div class='input'>\n";
                echo'<input type="text" data-options="{&quot;dateFormat&quot;:&quot;yy-mm-dd&quot;,&quot;showButtonPanel&quot;:true,&quot;appendText&quot;:&quot;(yyyy-mm-dd)&quot;,&quot;changeMonth&quot;:true,&quot;changeYear&quot;:true}" size="30" id="dp1382523606529" value="'.$saved_startdate.'" name="startdate" class="rwmb-date hasDatepicker">';
            echo "</div>";
        echo "</div>";

        // Field Defintion for end date
         echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
               echo "<label for='enddate'>End Date (optional)</label>\n";
            echo " </div>";
            echo "<div class='input'>\n";
                echo'<input type="text" data-options="{&quot;dateFormat&quot;:&quot;yy-mm-dd&quot;,&quot;showButtonPanel&quot;:true,&quot;appendText&quot;:&quot;(yyyy-mm-dd)&quot;,&quot;changeMonth&quot;:true,&quot;changeYear&quot;:true}" size="30" id="dp1382523606529" value="'.$saved_enddate.'" name="enddate" class="rwmb-date hasDatepicker">';
            echo "</div>";
        echo "</div>";

      // Field Definition for Duration
        echo "<div class='disp-row'>";
           echo " <div class='rwmb-label'>";
                echo "<label for='duration'>Duration</label>\n";
           echo " </div>";
            echo "<div class='input'>\n";
                echo "<input type='number' name='duration' value='".$saved_duration."'/>(months)\n";
            echo "</div>";
        echo "</div>";

          // Field Definition for Number of Volunteers
        echo "<div class='disp-row'>";
           echo " <div class='rwmb-label'>";
                echo "<label for='Number of Volunteers'>Number of Volunteers</label>\n";
           echo " </div>";
            echo "<div class='input'>\n";
                echo "<input type='number' name='number_of_volunteers' value='".$saved_number_of_volunteers."'/>\n";
            echo "</div>";
        echo "</div>";

       // Field Definition for Architect Semester
        echo "<div class='disp-row'>";
           echo " <div class='rwmb-label'>";
                echo "<label for='Architect Semester'>Architect Semester</label>\n";
           echo " </div>";
            echo "<div class='input'>\n";
                echo "<input type='number' name='architect_semester' value='".$saved_architect_semester."'/>\n";
            echo "</div>";
        echo "</div>";

        echo "<div class='disp-row'>";
           echo " <div class='rwmb-label'>";
                echo "<label for='duration'>Computer Required</label>\n";
           echo " </div>";
            echo "<div class='input'>\n";
           $cmreq_no=($computer_required == "NO")?"selected='selected'":"";
           $cmreq_yes=($computer_required == "YES")?"selected='selected'":"";
           $cmreq_welcome=($computer_required == "WELCOME")?"selected='selected'":"";
           $tm_ft=($timing == "FT")?"selected='selected'":"";
           $tm_ht=($timing == "HT")?"selected='selected'":"";
           $tm_either=($timing == "EITHER")?"selected='selected'":"";
         ?>

           <select size="0" id="computerrequired" name="computerrequired" class="rwmb-select">
             <option value="" <?php if($cmreq_no =="" && $cmreq_yes =="" && $cmreq_welcome ==""): ?> selected="selected" <?php endif; ?> >Select a Computer Required</option>
             <option value="NO"  <?php echo $cmreq_no;?> >NO</option>
             <option value="YES" <?php echo $cmreq_yes;?>>YES</option>
             <option value="WELCOME" <?php echo $cmreq_welcome;?>>WELCOME</option> 
           </select>

         <?php
            echo "</div>";
        echo "</div>";

        echo "<div class='disp-row'>";
           echo " <div class='rwmb-label'>";
                echo "<label for='duration'>Timing</label>\n";
           echo " </div>";
            echo "<div class='input'>\n";
         ?>

           <select size="0" id="timing" name="timing" class="rwmb-select">
             <option value="" <?php if($tm_ft =="" && $tm_ht ==""): ?>selected="selected" <?php endif; ?> >Select a Timing</option>
             <option value="FT" <?php echo $tm_ft;?>>FT</option>
             <option value="HT" <?php echo $tm_ht;?>>HT</option>
             <option value="EITHER"  <?php echo $tm_either;?> >EITHER</option>
           </select>

         <?php
            echo "</div>";
        echo "</div>";

         echo "<div class='disp-row rwmb-textarea-wrapper'>";
            // Field Definition for Comments
                echo " <div class='rwmb-label'>";
                    echo "<label for='comments'>Morning Timings</label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                    echo "<textarea rows='4' cols='50' name='morningtimings' class='rwmb-textarea large-text'>$morning_timings</textarea>";
                echo "</div>";
            echo "</div>";

             echo "<div class='disp-row rwmb-textarea-wrapper'>";
            // Field Definition for Comments
                echo " <div class='rwmb-label'>";
                    echo "<label for='afternoontimings'>Afternoon Timings</label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                    echo "<textarea rows='4' cols='50' name='afternoontimings' class='rwmb-textarea large-text'>$afternoon_timings</textarea>";
                echo "</div>";
            echo "</div>";
              echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='skillsGain'>Skills Gain</label>\n";
            echo " </div>";
            echo "<div class='rwmb-input'>\n";
                
                echo "<textarea rows='4' cols='50' name='skills_gain' id='skills_gain' class='rwmb-textarea large-text'>$saved_skillsGain</textarea>";
            echo "</div>";
        echo "</div>";
        
         echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='dailyTasks'>Daily Tasks</label>\n";
            echo " </div>";
            echo "<div class='rwmb-input'>\n";
              echo "<textarea rows='4' cols='50' name='daily_tasks' id='daily_tasks' class='rwmb-textarea large-text'>$saved_dailyTasks</textarea>";
                
            echo "</div>";
        echo "</div>";  
        echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='prerequisites'>Prerequisites</label>\n";
            echo " </div>";
            echo "<div class='rwmb-input'>\n";
              echo "<textarea rows='4' cols='50' name='prerequisites' id='prerequisites' class='rwmb-textarea large-text'>$saved_prerequisites</textarea>";
                
            echo "</div>";
        echo "</div>";  
        
         echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='No of Opportunities'>No of Opportunities</label>\n";
            echo " </div>";
            echo "<div class='rwmb-input'>\n";
              echo "<input type='text' name='no_of_opportunities' id='no_of_opportunities' class='rwmb-text' value='$saved_no_of_opportunities' />";
                
            echo "</div>";
        echo "</div>";  
       
        echo "<div class='disp-row'>";
           echo " <div class='rwmb-label'>";
                echo "<label for='duration'>Opportunity Status</label>\n";
           echo " </div>";
            echo "<div class='input'>\n";
           $opened=($opportunity_status == "opened")?"selected='selected'":"";
           $closed=($opportunity_status == "closed")?"selected='selected'":"";
          ?>

           <select size="0" id="opportunity_status" name="opportunity_status" class="rwmb-select">
             
             <option value="opened"  <?php echo $opened;?> >Opened</option>
             <option value="closed" <?php echo $closed;?>>Closed</option>
             
           </select>

         <?php
            echo "</div>";
        echo "</div>";

        ?>

            <script>

            jQuery(document).ready(function(){
               // jQuery('.combobox').combobox();
               // jQuery('#av_unit').combobox('selectByText', '<?php echo $saved_AVUnit; ?>');
               // jQuery("#projname").combobox('selectByText', '<?php echo $saved_projname ?>');
            });

            function unitChanged() {
                // this is called when AV Unit is changed.
                $unitname = jQuery("#av_unit").val();
                // Remove the current set of options in the Project Select
                jQuery("#projectname options").remove();
                if ($unitname != '') {
                    //
                    // Get the options from the hidden project selects which pertains to the current AV Unit
                    $unitname = $unitname.replace(" ", "_");
                    $newselect = jQuery("#optgroup"+$unitname).html();
                } else {
                    $newselect = "<option value=''> </option>";
                }

                // Assign the new set of options pertaining to the current AV Unit
                jQuery("#projectname").html($newselect);
                jQuery('#projectname').data('combobox').refresh();

            }
             function mentor_userChanged() {
				  jQuery("input[name=contactPerson]").attr('value','');
					 jQuery("input[name=contactEmail]").attr('value','');
					 jQuery("input[name=contactPhone]").attr('value','');
					  jQuery("input[name=hidden_contact_user]").attr('value','');
				 var mentor_user = jQuery("#mentor_user").val();
				 if(mentor_user != "add_new_user"){
					 var mentors = mentor_user.split("|");
					  jQuery("input[name=contactPerson]").attr('readonly',true);
					 jQuery("input[name=contactEmail]").attr('readonly',true);
					 jQuery("input[name=contactPhone]").attr('readonly',true);
					 jQuery("input[name=hidden_contact_user]").val(mentors[0]);
					 
					 jQuery("input[name=contactPerson]").val(mentors[1]);
					 jQuery("input[name=contactEmail]").val(mentors[2]);
					 jQuery("input[name=contactPhone]").val(mentors[3]);
			     }else{
					 jQuery("input[name=contactPerson]").attr('readonly',false);
					 jQuery("input[name=contactEmail]").attr('readonly',false);
					 jQuery("input[name=contactPhone]").attr('readonly',false);
				 }
		     }		 
            </script>
        <?php

    }

}

class unitProject {
    public $unit = null;
    public $project = null;

    function __construct($unitName, $projectName){
        $this->unit = $unitName;
        $this->project = $projectName;
    }
}
function psort($a,$b){
 if($a->unit == $b->unit) return 0;
 return ($a->project > $b->project ? 1 : -1);
}

?>
