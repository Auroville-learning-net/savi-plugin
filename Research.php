<?php
class Research
{

   private $custom_type = 'research';

   public function __construct()
    {
    	 
        // Create a hook to create custom Post Type
        add_action( 'init', array($this, 'create_post_type' ));

        // Create a hook to create metabox for the custom post type
        add_action( 'add_meta_boxes', array($this, 'savi_research_metabox' ));

       // Create a hook to save the custom post type
        add_action( 'save_post', array($this, 'save_research_postdata' ),1,2);
/*
        // Hook into the 'init' action
        add_action( 'init', array($this,'savi_research_taxonomy'), 0 );


       // Hook into the 'init' action
       add_action( 'init', array($this,'savi_research_categories'), 0 );

*/
    }

 // Create a Custom Post Type
    public function create_post_type() {
    	$supports = array( 'title', 'editor', 'comments','thumbnail', 'author' );
        register_post_type( $this->custom_type,
            array(
                'labels' => array(
                    'name' => __( 'Research' ),
                    'singular_name' => __( 'Research' )
                ),
                'supports' =>$supports,
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => $this->custom_type),
            )
        );
    }


   // Register Custom Taxonomy
public function savi_research_taxonomy()  {
	
		// ==================================
		// = labels for event langauge tag taxonomy =
		// ==================================
	
	$languages_research_tag_labels = array(
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

  // ================================
		// = args for event feeds taxonomy =
		// ================================
		$languages_research_tag_args = array(
			'labels'       => $languages_research_tag_labels,
			'hierarchical' => false,
			'rewrite'      => array( 'slug' => 'savi_research_tag_languages' ),
			'public'                     => true,
		   'show_ui'                    => true,
		   'show_admin_column'          => true,
		   'show_in_nav_menus'          => true,
		   'show_tagcloud'              => true,
		);
    // ================================
		// = register event language tags taxonomy =
		// ================================
		register_taxonomy(
			'savi_research_tag_languages',
			$this->custom_type,
			$languages_research_tag_args
		);
   
	}

function savi_research_categories()  {


	// ========================================
		// = labels for event Work Areas categories taxonomy =
		// ========================================
		$work_areas_research_categories_labels = array(
		 'name'                      => _x( 'Work Areas', 'Work Areas taxonomy' ),
		'singular_name'              => _x( 'Work Area', 'Work Areas taxonomy (singular)' ),
		'menu_name'                  => __( 'Work Areas' ),
		'all_items'                  => __( 'All Work Areas' ),
		'parent_item'                => __( 'Parent Work Area' ),
		'parent_item_colon'          => __( 'Parent Work Area:' ),
		'new_item_name'              => __( 'New Work Area' ),
		'add_new_item'               => __( 'Add new work area' ),
		'edit_item'                  => __( 'Edit work area' ),
		'update_item'                => __( 'Update work area' ),
		'separate_items_with_commas' => __( 'Separate Work Areas with commas' ),
		'search_items'               => __( 'Search Work Areas' ),
		'add_or_remove_items'        => __( 'Add or remove Work Area' ),
		'choose_from_most_used'      => __( 'Choose from the most used Work Areas' )
		);
     
        // ========================================
		// = labels for event Work Areas categories taxonomy =
		// ========================================  
       
       $work_type_research_categories_labels= array(
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
	
		// ======================================
		// = args for event Work Areas categories taxonomy =
		// ======================================
		$work_areas_research_categories_args = array(
			'labels'       => $work_areas_research_categories_labels,
			'hierarchical' => true,
			'rewrite'      => array( 'slug' => 'savi_research_cat_work_area' ),
		   'public'                     => true,
		   'show_ui'                    => true,
		   'show_admin_column'          => true,
		   'show_in_nav_menus'          => true,
		   'show_tagcloud'              => true,
		);
		
		// ======================================
		// = args for event Work Type categories taxonomy =
		// ======================================
		$work_type_research_categories_args = array(
			'labels'       => $work_type_research_categories_labels,
		    'hierarchical' => true,
			'rewrite'      => array( 'slug' => 'savi_research_cat_work_type' ),
		   'public'                     => true,
		   'show_ui'                    => true,
		   'show_admin_column'          => true,
		   'show_in_nav_menus'          => true,
		   'show_tagcloud'              => true,
		);

		// ======================================
		// = register event work areas categories taxonomy =
		// ======================================
		register_taxonomy(
			'savi_research_cat_work_area',
			$this->custom_type,
			$work_areas_research_categories_args
		); 
		// ======================================
		// = register event work areas categories taxonomy =
		// ======================================
		register_taxonomy(
			'savi_research_cat_work_type',
			$this->custom_type,
			$work_type_research_categories_args
		);         
		

}


    // Create a new Metabox for the Custom Post Type
public function savi_research_metabox() {
        remove_meta_box( 'commentsdiv',$this->custom_type,'normal' );
        add_meta_box(
			$this->custom_type."_1",
			 'Research Details',
			array($this,'savi_research_meta_box__details_view'),
			$this->custom_type,
			'normal',
			'high'
		);

		add_meta_box(
			$this->custom_type."_2",
			'Research Description',
		array($this,'savi_research_meta_box_description_view'),
			$this->custom_type,
			'normal',
			'high'
		);
		add_meta_box(
			$this->custom_type."_3",
			'Admin',
			array($this,'savi_research_meta_box_admin_view'),
			$this->custom_type,
			'normal',
			'high'
		);
        
   
   }
function savi_research_meta_box_description_view() {
	//global $post;
		 $post_id = $_GET['post'];//$wp_query->post->ID;
        $postmetaArray = get_post_meta($post_id);
      //echo "<pre>",print_r($postmetaArray),"</pre>";
        if (sizeof($postmetaArray) > 0) {
         	$saved_savi_research_description_purpose = $postmetaArray['savi_research_description_purpose'][0];         
        		$saved_savi_research_description_domain_content = $postmetaArray['savi_research_description_domain_content'][0];
        		$saved_savi_research_description_approach = $postmetaArray['savi_research_description_approach'][0];
        		$saved_savi_research_description_team = $postmetaArray['savi_research_description_team'][0];
        }
        else {
           $saved_savi_research_description_purpose = "";         
        		$saved_savi_research_description_domain_content = "";
        		$saved_savi_research_description_approach = "";
        		$saved_savi_research_description_team = "";
        }
            echo "<div class='disp-row rwmb-textarea-wrapper'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='Purpose'>Purpose of Research</label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                  echo "<textarea rows='4' cols='50' name='savi_research_description_purpose' id='savi_research_description_purpose' 
                        class='rwmb-textarea large-text'>$saved_savi_research_description_purpose</textarea>";
                echo "</div>";
            echo "</div>";
 
             echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='domain_content'>Domain & Content</label>\n";
            echo " </div>";
            echo "<div class='rwmb-input'>\n";
               echo "<textarea rows='4' cols='50' name='savi_research_description_domain_content' 
                       id='savi_research_description_domain_content' 
                        class='rwmb-textarea large-text'>$saved_savi_research_description_domain_content</textarea>";
            echo "</div>";
        echo "</div>";
        
         echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='Approach'>Approach</label>\n";
            echo " </div>";
            echo "<div class='rwmb-input'>\n";
               echo "<textarea rows='4' cols='50' name='savi_research_description_approach' 
                       id='savi_research_description_approach' 
                        class='rwmb-textarea large-text'>$saved_savi_research_description_approach</textarea>";
            echo "</div>";
        echo "</div>";  
          echo "<div class='disp-row'>";
            echo " <div class='rwmb-label'>";
                echo "<label for='team'>Team</label>\n";
            echo " </div>";
            echo "<div class='rwmb-input'>\n";
               echo "<textarea rows='4' cols='50' name='savi_research_description_team' 
                       id='savi_research_description_team' 
                        class='rwmb-textarea large-text'>$saved_savi_research_description_team</textarea>";
            echo "</div>";
        echo "</div>";  
	}
function savi_research_meta_box_admin_view() {
	//global $post;
		 $post_id = $_GET['post'];//$wp_query->post->ID;
        $postmetaArray = get_post_meta($post_id);
        if (sizeof($postmetaArray) > 0) {
         	$saved_savi_research_admin_timestamp_comments = $postmetaArray['savi_research_admin_timestamp_comments'][0];         
        		
        }
        else {
            $saved_savi_research_admin_timestamp_comments = "";         
        		
        }
            echo "<div class='disp-row rwmb-textarea-wrapper'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='admin_notes_revisions'>Admin notes/Revisions</label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                  echo "<textarea rows='4' cols='50' name='savi_research_admin_timestamp_comments'
                        id='savi_research_admin_timestamp_comments' 
                        class='rwmb-textarea large-text'>$saved_savi_research_admin_timestamp_comments</textarea>";
                echo "</div>";
            echo "</div>";
 
            
	}
function savi_research_meta_box__details_view() {
	//global $post;
	 $post_id = $_GET['post'];//$wp_query->post->ID;
        $postmetaArray = get_post_meta($post_id);
        if (sizeof($postmetaArray) > 0):
         	$saved_savi_research_details_abbreviation = $postmetaArray['savi_research_details_abbreviation'][0];   
         	$saved_savi_research_details_av_unit = $postmetaArray['savi_research_details_av_unit'][0];
            $saved_savi_research_details_projectname = $postmetaArray['savi_research_details_projectname'][0];      
            $saved_savi_research_details_daily_tasks = $postmetaArray['savi_research_details_daily_tasks'][0];      
            $saved_savi_research_details_skills_gained = $postmetaArray['savi_research_details_skills_gained'][0];      
            $saved_savi_research_details_contact_person = $postmetaArray['savi_research_details_contact_person'][0];      
            $saved_savi_research_details_contact_email = $postmetaArray['savi_research_details_contact_email'][0];      
            $saved_savi_research_details_contact_phone = $postmetaArray['savi_research_details_contact_phone'][0];      
            $saved_savi_research_details_start_date = $postmetaArray['savi_research_details_start_date'][0];      
            $saved_savi_research_details_end_date = $postmetaArray['savi_research_details_end_date'][0];      
            $saved_savi_research_details_positions = $postmetaArray['savi_research_details_positions'][0];      
            $saved_savi_research_details_duration = $postmetaArray['savi_research_details_duration'][0];      
       else:
           $saved_savi_research_details_abbreviation = "";         
           $saved_savi_research_details_av_unit      = "";
           $saved_savi_research_details_projectname  = "";
            $saved_savi_research_details_daily_tasks ="";      
            $saved_savi_research_details_skills_gained = "";      
            $saved_savi_research_details_contact_person = "";      
            $saved_savi_research_details_contact_email = "";      
            $saved_savi_research_details_contact_phone = "";      
            $saved_savi_research_details_start_date = "";      
            $saved_savi_research_details_end_date = "";      
            $saved_savi_research_details_positions = "";      
            $saved_savi_research_details_duration = "";            
       endif;
       $AVUnitQuery = new WP_Query( array(
                    'post_type' => 'av_unit',
                    'posts_per_page' => -1,
                    'post_status' => array( 'publish' ),
                    ));
        $postIndex = 0;
        $projIndex = 0;
       // echo"<pre>",print_r($AVUnitQuery),"</pre>";
        // Start constructing the Select options for AV Units
        $unitSelectHTML = "<select class='combobox' name='savi_research_details_av_unit' id='savi_research_details_av_unit' onchange='unitChanged()' name='inline'> \n<option></option>\n";

        if ($AVUnitQuery->found_posts > 0) {
            while ($AVUnitQuery->have_posts()) {
                $AVUnitQuery->the_post();
                $unitname = get_the_title();
				    $unitID = get_the_ID();
                $firstrow = true;

                // Construct the options for the select for AV Unit
                $unitSelectHTML .= "<option value='$unitID'";

                // If the exisitng value of the AV Unit is equal to the current loop value then select the option
                if ($unitID == $saved_savi_research_details_av_unit  ||  $unitname == $saved_savi_research_details_av_unit)
                    $unitSelectHTML .= "selected";

                // End the Construct for the option
                $unitSelectHTML .= ">$unitname</option>\n";
                $unitProjectArray[] = new unitProject($unitID, 0);
            }

        }
     //echo"<pre>",print_r($unitProjectArray),"</pre>";
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
        $projSelectHTML = "<select class='combobox' id='savi_research_details_projectname' name='savi_research_details_projectname'> \n<option></option>\n";
        $pr_avUnitName = "";

        for ($arrayIndex=0;$arrayIndex < sizeof($unitProjectArray);$arrayIndex++) {
            $avUnitName = $unitProjectArray[$arrayIndex]->unit;
            $tmpUnit = str_replace(" ", "_", $avUnitName);
            $projID   = $unitProjectArray[$arrayIndex]->project; 
            $projname = ($unitProjectArray[$arrayIndex]->project) == 0?"General":get_post($unitProjectArray[$arrayIndex]->project)->post_title;
            if ($avUnitName == $saved_savi_research_details_av_unit) {
                $projSelectHTML .= "<option value='$projID'";
                if ($projID == $saved_savi_research_details_projectname) {
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
 
        echo "<div class='disp-row rwmb-textarea-wrapper'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='daily_tasks'>Daily Tasks</label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                  echo "<textarea rows='4' cols='50' name='savi_research_details_daily_tasks'
                        id='savi_research_details_daily_tasks' 
                        class='rwmb-textarea large-text'>$saved_savi_research_details_daily_tasks</textarea>";
                echo "</div>";
            echo "</div>";
        
        echo "<div class='disp-row rwmb-textarea-wrapper'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='skills_gained'>Skills gained</label>\n";
                echo " </div>";
                echo "<div class='rwmb-input'>\n";
                  echo "<textarea rows='4' cols='50' name='savi_research_details_skills_gained'
                        id='savi_research_details_timestamp_comments' 
                        class='rwmb-textarea large-text'>$saved_savi_research_details_skills_gained</textarea>";
                echo "</div>";
            echo "</div>";
 
       echo "<div class='disp-row'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='Abbreviation'>Research Abbreviation</label>\n";
                echo " </div>";
                 echo "<div class='input'>\n";
                 echo "<input type='text' name='savi_research_details_abbreviation' id='savi_research_details_abbreviation'
                     value='$saved_savi_research_details_abbreviation' />\n";
                echo "</div>";
     echo "</div>";
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
        echo "<div class='disp-row'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='contact_person'>Contact person</label>\n";
                echo " </div>";
                 echo "<div class='input'>\n";
                 echo "<input type='text' name='savi_research_details_contact_person' id='savi_research_details_contact_person'
                     value='$saved_savi_research_details_contact_person' />\n";
                echo "</div>";
     echo "</div>";
     echo "<div class='disp-row'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='contact_email'>Contact Email</label>\n";
                echo " </div>";
                 echo "<div class='input'>\n";
                 echo "<input type='text' name='savi_research_details_contact_email' id='savi_research_details_contact_email'
                     value='$saved_savi_research_details_contact_email' />\n";
                echo "</div>";
     echo "</div>";
     echo "<div class='disp-row'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='contact_phone'>Contact Phone</label>\n";
                echo " </div>";
                 echo "<div class='input'>\n";
                 echo "<input type='text' name='savi_research_details_contact_phone' id='savi_research_details_contact_phone'
                     value='$saved_savi_research_details_contact_phone' />\n";
                echo "</div>";
     echo "</div>";
     echo "<div class='disp-row'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='start_date'>Starting date </label>\n";
                echo " </div>";
                 echo "<div class='input'>\n";
                 echo "<input type='text' data-options='{&quot;dateFormat&quot;:&quot;yy-mm-dd&quot;,&quot;showButtonPanel&quot;:true,&quot;appendText&quot;:&quot;(yyyy-mm-dd)&quot;,&quot;changeMonth&quot;:true,&quot;changeYear&quot;:true}' 
                 name='savi_research_details_start_date' id='savi_research_details_start_date'
                     value='$saved_savi_research_details_start_date' class='rwmb-date hasDatepicker' />\n";
                echo "</div>";
     echo "</div>";
     echo "<div class='disp-row'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='end_date'>End date (optional)</label>\n";
                echo " </div>";
                 echo "<div class='input'>\n";
                 echo "<input type='text' data-options='{&quot;dateFormat&quot;:&quot;yy-mm-dd&quot;,&quot;showButtonPanel&quot;:true,&quot;appendText&quot;:&quot;(yyyy-mm-dd)&quot;,&quot;changeMonth&quot;:true,&quot;changeYear&quot;:true}'
                   name='savi_research_details_end_date' id='savi_research_details_end_date'
                     value='$saved_savi_research_details_end_date' class='rwmb-date hasDatepicker'  /> \n";
                     
                echo "</div>";
     echo "</div>";
     echo "<div class='disp-row'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='positions'>Number of positions</label>\n";
                echo " </div>";
                 echo "<div class='input'>\n";
                 echo "<input type='text' name='savi_research_details_positions' id='savi_research_details_positions'
                     value='$saved_savi_research_details_positions' />\n";
                echo "</div>";
     echo "</div>";
     echo "<div class='disp-row'>";
            
                echo " <div class='rwmb-label'>";
                    echo "<label for='duration'>Duration</label>\n";
                echo " </div>";
                 echo "<div class='input'>\n";
                 echo "<input type='text' name='savi_research_details_duration' id='savi_research_details_duration'
                     value='$saved_savi_research_details_duration' />(months)\n";
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
                $unitname = jQuery("#savi_research_details_av_unit").val();
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
                jQuery("#savi_research_details_projectname").html($newselect);
                jQuery('#savi_research_details_projectname').data('combobox').refresh();

            }
            </script>
        <?php
}
  
function save_research_postdata() {
	
	global  $post;
	if( get_post_type() != $this->custom_type ) return $post->ID;
 
      $savi_research_details_abbreviation = sanitize_text_field( $_POST['savi_research_details_abbreviation'] );      
      $savi_research_details_av_unit = sanitize_text_field( $_POST['savi_research_details_av_unit'] );      
      $savi_research_details_projectname = sanitize_text_field( $_POST['savi_research_details_projectname'] );      
      $savi_research_description_purpose = sanitize_text_field( $_POST['savi_research_description_purpose'] );      
      $savi_research_description_domain_content = sanitize_text_field( $_POST['savi_research_description_domain_content'] );      
      $savi_research_description_approach = sanitize_text_field( $_POST['savi_research_description_approach'] );      
      $savi_research_description_team = sanitize_text_field( $_POST['savi_research_description_team'] );      
      $savi_research_admin_timestamp_comments = sanitize_text_field( $_POST['savi_research_admin_timestamp_comments'] );      
      $savi_research_details_daily_tasks =sanitize_text_field( $_POST['savi_research_details_daily_tasks']);      
      $savi_research_details_skills_gained =sanitize_text_field( $_POST['savi_research_details_skills_gained']);      
      $savi_research_details_contact_person = sanitize_text_field( $_POST['savi_research_details_contact_person']);      
      $savi_research_details_contact_email = sanitize_text_field( $_POST['savi_research_details_contact_email']);      
      $savi_research_details_contact_phone = sanitize_text_field( $_POST['savi_research_details_contact_phone']);      
      $savi_research_details_start_date = sanitize_text_field( $_POST['savi_research_details_start_date']);      
      $savi_research_details_end_date =sanitize_text_field( $_POST['savi_research_details_end_date']);      
      $savi_research_details_positions = sanitize_text_field( $_POST['savi_research_details_positions']);      
      $savi_research_details_duration = sanitize_text_field( $_POST['savi_research_details_duration']);      
      
      
      update_post_meta( $post->ID, 'savi_research_details_abbreviation', $savi_research_details_abbreviation);
      update_post_meta( $post->ID, 'savi_research_details_av_unit', $savi_research_details_av_unit);
      update_post_meta( $post->ID, 'savi_research_details_projectname',$savi_research_details_projectname );
      update_post_meta( $post->ID, 'savi_research_description_purpose', $savi_research_description_purpose);
      update_post_meta( $post->ID, 'savi_research_description_domain_content', $savi_research_description_domain_content);
      update_post_meta( $post->ID, 'savi_research_description_approach', $savi_research_description_approach);
      update_post_meta( $post->ID, 'savi_research_description_team', $savi_research_description_team);
      update_post_meta( $post->ID, 'savi_research_admin_timestamp_comments', $savi_research_admin_timestamp_comments);
      update_post_meta( $post->ID, 'savi_research_details_daily_tasks', $savi_research_details_daily_tasks);
      update_post_meta( $post->ID, 'savi_research_details_skills_gained', $savi_research_details_skills_gained);
      update_post_meta( $post->ID, 'savi_research_details_contact_person', $savi_research_details_contact_person);
      update_post_meta( $post->ID, 'savi_research_details_contact_email', $savi_research_details_contact_email);
      update_post_meta( $post->ID, 'savi_research_details_contact_phone', $savi_research_details_contact_phone);
      update_post_meta( $post->ID, 'savi_research_details_start_date', $savi_research_details_start_date);
      update_post_meta( $post->ID, 'savi_research_details_end_date', $savi_research_details_end_date);
      update_post_meta( $post->ID, 'savi_research_details_positions', $savi_research_details_positions);
      update_post_meta( $post->ID, 'savi_research_details_duration', $savi_research_details_duration);

}   
}
?>