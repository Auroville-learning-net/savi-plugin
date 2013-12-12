<?php
class Program
{

   private $languages = array('English', 'French', 'Tamil');
   private $custom_type = 'program';
   
   public function __construct()
    {
        // Create a hook to create custom Post Type 
        add_action( 'init', array($this, 'create_post_type' ));
        
        // Create a hook to create metabox for the custom post type
        add_action( 'add_meta_boxes', array($this, 'init_metabox' ));
        
        // Create a hook to save the custom post type
        add_action( 'save_post', array($this, 'save_programs_postdata' ),1,2);
 
        // Hook into the 'init' action
        add_action( 'init', array($this,'savi_programs_taxonomy'), 0 );


       // Hook into the 'init' action
       add_action( 'init', array($this,'savi_program_categories'), 0 );
 
       // Hook into the 'init' action  
       add_action('init',array($this,'admin_enqueue_scripts')) ; 
 
    }

    // Create a Custom Post Type
    public function create_post_type() {
        register_post_type( $this->custom_type,
            array(
                'labels' => array(
                    'name' => __( 'Programs' ),
                    'singular_name' => __( 'Program' )
                ),
                'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments','revisions'  ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => $this->custom_type),
            )
        );
    }
    

   // Register Custom Taxonomy
public function savi_programs_taxonomy()  {

	$labels = array(
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
	register_taxonomy( 'savi_skills', 'opportunity', $args );
	register_taxonomy( 'savi_soft', 'opportunity', $args_soft );

}




// Register Custom Taxonomy
public function savi_program_categories()  {

	$labels = array(
		'name'                       => _x( 'Work Areas', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Work Area', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Work Areas', 'text_domain' ),
		'all_items'                  => __( 'All work areas', 'text_domain' ),
		'parent_item'                => __( 'Parent work area', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent work area:', 'text_domain' ),
		'new_item_name'              => __( 'New work area', 'text_domain' ),
		'add_new_item'               => __( 'Add new work area', 'text_domain' ),
		'edit_item'                  => __( 'Edit work area', 'text_domain' ),
		'update_item'                => __( 'Update work area', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate work areas with commas', 'text_domain' ),
		'search_items'               => __( 'Search work areas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove work areas', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used work areas', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	//register_taxonomy( 'savi_work', 'opportunity', $args );

}



    // Create a new Metabox for the Custom Post Type
    public function init_metabox() {
        // Callback function hooked to opportunity_metabox function 
        add_meta_box( 'Programs', "Programs", array($this,'Programs_metabox'), $this->custom_type, 'advanced', 'high');
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
		}

    // Called when the custom posttype is saved
    public function save_programs_postdata( $post_id ) {
        global $post ;
        
        if( $post->post_type != "program" ) return $post_id;
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
        $av_units = sanitize_text_field( $_POST['av_unit'] );
        $unit_project = sanitize_text_field( $_POST['projectname'] );
        $contact_name = sanitize_text_field( $_POST['contactPerson'] );
        $contact_email = sanitize_text_field( $_POST['email'] );
        $contact_number = sanitize_text_field( $_POST['phone'] );
        /*if ( is_array($_POST['language']) && !empty($_POST['language']) ){     
            
           $language = implode('","',$_POST['language']);
        } else {$language = '';}*/
        $language = $_POST['language'];
        $comments = sanitize_text_field( $_POST['comments'] );
        $startdate = sanitize_text_field( $_POST['startdate']);
        $program_web_page = sanitize_text_field( $_POST['program_web_page']);
        $duration = sanitize_text_field( $_POST['duration']);  
        $otherlanguage  = sanitize_text_field( $_POST['otherlanguage']);    
     //  print_r( $_POST['language']); 

       // update_post_meta( $post_id, 'opportunity_title', $opptun_title);
        update_post_meta( $post_id, 'av_unit', $av_units);
        update_post_meta( $post_id, 'projectname', $unit_project);
        update_post_meta( $post_id, 'contactPerson', $contact_name);
        update_post_meta( $post_id, 'email', $contact_email);
        update_post_meta( $post_id, 'phone', $contact_number);
        update_post_meta( $post_id, 'language', $language);
        update_post_meta( $post_id, 'comments', $comments);
        update_post_meta( $post_id, 'startdate', $startdate);
        update_post_meta( $post_id, 'program_web_page', $program_web_page);
        update_post_meta( $post_id, 'duration', $duration);
        update_post_meta( $post_id, 'otherlanguage', $otherlanguage);
          
    }   

    public function programs_metabox($post) {
        global  $wpdb;
        $postmetaArray = get_post_meta($post->ID);
        $projects = get_post_meta($post->ID, 'language', false);
        $allProjects = $projects[0];
        if (sizeof($postmetaArray) > 0) {
          //  $opportunity_title = $postmetaArray['opportunity_title'][0];
            $av_unit = $postmetaArray['av_unit'][0];
            $projname = $postmetaArray['projectname'][0];
            $contactPerson = $postmetaArray['contactPerson'][0];
            $email = $postmetaArray['email'][0];
            $phone = $postmetaArray['phone'][0];
            $language= unserialize($postmetaArray['language'][0]);
            $comments = $postmetaArray['comments'][0];
            $startdate = $postmetaArray['startdate'][0];
            $enddate = $postmetaArray['enddate'][0];
            $duration = $postmetaArray['duration'][0];  
            $otherlanguage  = $postmetaArray['otherlanguage'][0];
            $program_web_page = $postmetaArray['program_web_page'][0];   
        } else {
          //  $opportunity_title = '';
            $av_unit = '';
            $projname = '';
            $contactPerson = '';
            $email = '';
            $phone = '';
            $language= '';
            $comments = '';
            $startdate = '';
            $enddate = '';
            $duration = '';  
            $otherlanguage = '';
            $program_web_page = '';
        }

        $query = new WP_Query( array(
                    'post_type' => 'av_unit',
                    'posts_per_page' => -1,
                    'post_status' => array( 'publish' ),
                    ));
        $postIndex = 0;
        $projIndex = 0;


        // Start constructing the Select options for AV Units
        $unitSelectHTML = "<select id='av_unit' name='av_unit' onchange='unitChanged()'>\n";
        
        // Start constructing the select options for Projects (within a particular AV Unit)
        $projSelectHTML = "<select name='projectname' id='projectname'>\n";
        
        // Start Constructing the select options for all projects (to be used as the template when the user select any AV Unit
        $projhiddenSelectHTML = "<select style='display:none;' id='projectname_all'>\n";
        
        if ($query->found_posts > 0) {
            while ($query->have_posts()) {
                $query->the_post();
                $unitPostID = get_the_ID();
                $unitname = get_the_title();
                $projects = get_post_meta($unitPostID, 'projects_info', false);
                $allProjects = $projects[0];
                $firstrow = true;
                
                // Construct the options for the select for AV Unit
                $unitSelectHTML .= "<option value='$unitname'";
                
                // If the exisitng value of the AV Unit is equal to the current loop value then select the option
                if ($unitname == $av_unit)
                    $unitSelectHTML .= "selected";
                
                // End the Construct for the option
                $unitSelectHTML .= ">$unitname</option>\n";

                // Start Constructing the options for all Projects 
                $projhiddenSelectHTML .= "<optgroup id='optgroup{$unitname}'>";
                
                // Start Constructing the Select options for Projects (all AV Units)
                if (sizeof($allProjects) > 0) {
                    foreach($allProjects as $key=>$projectinfo) {
                        $tmpprojname = $projectinfo['projectname'];
                        if ($unitname == $av_unit) {
                            // If the current AV Unit (saved in the DB is equal to the loop's AV Unit value then construct the Project Select
                            $projSelectHTML .= "<option value='$tmpprojname'";
                            // If the current project name is equal to the loop's Project Name then select the option
                            if ($tmpprojname == $projname) {
                                $projSelectHTML .= "selected";
                            }
                            // End the construct of the option
                            $projSelectHTML .= ">$tmpprojname</option>\n";
                        }
                        // Construct the options for all all project select
                        $projhiddenSelectHTML .= "<option value='$tmpprojname'>$tmpprojname</option>\n";
                    }
                    $projhiddenSelectHTML .= "</optgroup>\n";
                }
            }
                
            $unitSelectHTML .= "<option value=''";
            if ('' == $av_unit) { 
                $unitSelectHTML .= " selected";
            }
            $unitSelectHTML .= "> </option>\n</select>\n";
            

            $projSelectHTML .= "</select>\n";
            $projhiddenSelectHTML .= "</select>\n";
        }
        wp_reset_query();
        
        ?>
        <style>
        .disp-row {

        
        }
        
        .label {
            display: inline;
            width: 40%;
            float:left;
        
        }
        
        .input {
                display: inline-block;
                width:50%;
        }
        
        </style>
        <?php 
        // Field definition for Opportunity
       /* echo "<div class='disp-row'>";
            echo "<div class='label'>\n";
            echo "<label for='opportunity_title'>Opportunity Title</label>\n";
            echo "</div>\n";
        
            echo "<div class='input'>\n";
            echo "<input type='text' name='opportunity_title' value='$opportunity_title' />\n";
            echo "</div>\n";
        echo "</div>"; */
        
        // Field definition for AV Units
        echo "<div class='disp-row'>";
            echo "<div class='label'>\n";
                echo "<label for='av_unit'>Unit</label>";
            echo "</div>\n";

            echo "<div class='input'>\n";
                echo $unitSelectHTML; // We have constructed this earlier
            // This is used for filtering the project name based on the AV Unit Selected
                echo $projhiddenSelectHTML;
            echo "</div>";
        echo "</div>";
            
        
        // Field Defintion for Project Name
        echo "<div class='disp-row'>";
            echo "<div class='label'>\n";
                echo "<label for='project_name'>Unit Project</label>\n";
            echo "</div>\n";

            echo "<div class='input'>\n";
                echo $projSelectHTML; // We have constructed this earlier
            echo "</div>";
        echo "</div>";
        
         // Field Defintion for end date
         echo "<div class='disp-row'>";
            echo "<div class='label'>\n";
                echo "<label for='enddate'>Program Web Page </label>\n";
            echo "</div>\n"; 
             echo "<div class='input'>\n";
               echo "<input type='text' name='program_web_page' value='$program_web_page' />\n";
               echo "(http://www.example.com)"; 
            echo "</div>";
        echo "</div>";          
        
          // Field Defintion for start date
         echo "<div class='disp-row'>";
            echo "<div class='label'>\n";
                echo "<label for='startdate'>Start Date</label>\n";
            echo "</div>\n"; 
             echo "<div class='input'>\n";
                echo'<input type="text" data-options="{&quot;dateFormat&quot;:&quot;yy-mm-dd&quot;,&quot;showButtonPanel&quot;:true,&quot;appendText&quot;:&quot;(yyyy-mm-dd)&quot;,&quot;changeMonth&quot;:true,&quot;changeYear&quot;:true}" size="30" id="dp1382523606529" value="'.$startdate.'" name="startdate" class="rwmb-date hasDatepicker">';
            echo "</div>";
        echo "</div>";  
 
       
       
      // Field Definition for Duration
        echo "<div class='disp-row'>";
            echo "<div class='label'>\n";
                echo "<label for='duration'>Duration</label>\n";
            echo "</div>\n";

            echo "<div class='input'>\n";
                echo "<input type='number' name='duration' value='".$duration."'/>(months)\n";
                
            echo "</div>";
        echo "</div>";


        // Field Definition for Contact Person
        echo "<div class='disp-row'>";
            echo "<div class='label'>\n";
                echo "<label for='contactPerson'>Contact Person</label>\n";
            echo "</div>\n";

            echo "<div class='input'>\n";
                echo "<input type='text' name='contactPerson' value='$contactPerson' />\n";
            echo "</div>";
        echo "</div>";

        // Field Definition for Title
        echo "<div class='disp-row'>";
            echo "<div class='label'>\n";
                echo "<label for='email'>Contact Email</label>\n";
            echo "</div>\n";

            echo "<div class='input'>\n";
                echo "<input type='text' name='email' value='$email' />\n";
            echo "</div>";
        echo "</div>";
        
        // Field Definition for Phone
        echo "<div class='disp-row'>";
            echo "<div class='label'>\n";
                echo "<label for='phone'>Contact Phone</label>\n";
            echo "</div>\n";

            echo "<div class='input'>\n";
                echo "<input type='text' name='phone' value='$phone' />\n";
            echo "</div>";
        echo "</div>";

        // Field Definition for Language
        echo "<div class='disp-row'>";
            echo "<div class='label' style='vertical-align:top;'>\n";
                echo "<label for='language'>Language</label>\n";
            echo "</div>\n";

            echo "<div class='input'>\n";
             /*   echo "<select id='language' name='language'>\n";
                foreach ($this->languages as $tmplang) {
                    echo "<option value='$tmplang' ";
                    if ($tmplang == $language) {
                        echo " selected";
                    }
                    echo ">$tmplang</option>\n";
                }
                echo "</select>\n";*/
               
              $checked_value1 = "";
              $checked_value2 = "";
              $checked_value3 = "";         
              if (sizeof($language) > 0) {
              	for($i=0;$i<=count($language);$i++) {   
                
                if( $language[$i] == "value1" ){$checked_value1 = "checked ='checked'";}                 
                if( $language[$i] == "value2" ){$checked_value2 = "checked ='checked'";} 
                if( $language[$i] == "value3" ){$checked_value3 = "checked ='checked'";}  
                 
               }  
              }   
              echo "<label>";
                    echo '<input type="checkbox" value="value1" name="language[]" class="rwmb-checkbox-list"'.$checked_value1.'> English';
              echo "</label><br>";
              echo "<label>";
                    echo '<input type="checkbox" value="value2" name="language[]" class="rwmb-checkbox-list"'.$checked_value2.'> French';
              echo "</label><br>";
              echo "<label>";
                    echo '<input type="checkbox" value="value3" name="language[]" class="rwmb-checkbox-list"'.$checked_value3.'> Tamil';
              echo "</label><br>";
               echo "<label>";
      
                    echo '<input type="text" value="'.$otherlanguage.'" name="otherlanguage" class="rwmb-other-language"> Others';
              echo "</label>";  
            echo "</div>";
        echo "</div>";
            
        
        echo "<div class='disp-row'>";
        // Field Definition for Comments
            echo "<div class='label'>\n";
                echo "<label for='comments'>Revisions</label>\n";
            echo "</div>\n";

            echo "<div class='input'>\n";
            echo "<textarea rows='4' cols='50' name='comments'>$comments</textarea>";
//            echo "<input type='text' name='comments' value='$comments' />\n";        
            echo "</div>";
        echo "</div>";
        
        ?>
        
        <script>
        function unitChanged() {
            // this is called when AV Unit is changed. 
            $unitname = jQuery("#av_unit").val();

            // Remove the current set of options in the Project Select
            jQuery("#projectname options").remove();
            if ($unitname != '') {
                // Get the options from the hidden project selects which pertains to the current AV Unit
                $newselect = jQuery("#optgroup"+$unitname).html();
            } else {
                $newselect = "<option value=''> </option>";
            }
            // Assign the new set of options pertaining to the current AV Unit
            jQuery("#projectname").html($newselect);

        }
        </script>
        <?php 
        
    }     

}

?>
