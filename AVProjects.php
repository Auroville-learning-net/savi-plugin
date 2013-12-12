<?php

class AV_projects extends Listing
{

   public function __construct() {
        
        // Create a new Post Type
        add_action( 'init', array($this, 'create_post_type' ));
        
        // Add the scripts of the Listing plugin (taken from Explorable Themes)
        add_action( 'admin_enqueue_scripts', array($this,'enqueue_scripts_css') );
        
      // Call the Units_Metabox when showing the post
        add_action( 'add_meta_boxes', array($this, 'init_av_projects_metabox' ));
 
        // Call the Units_Metabox when showing the post
        add_action( 'add_meta_boxes', array($this, 'init_av_projects_admin_metabox'));

        // Call the Units_Metabox when showing the post
        add_action( 'add_meta_boxes', array($this, 'init_av_projects_parentunits_metabox' ));
        
        // Call the Save Post when av_unit is being saved
        add_action( 'save_post', array($this, 'save_postdata' ));

       

    }
    
    function enqueue_scripts_css() {
        $plugin_dir = plugins_url('', __FILE__ );
        $dir =  get_bloginfo('url') . '/wp-content/plugins';    
         wp_enqueue_style( 'style-name', $plugin_dir .'/css/bootstrap.css', array(), "1.00");        
         wp_enqueue_style( 'style-name-1', $plugin_dir .'/css/bootstrap-combobox.css', array(), "1.00");
         wp_enqueue_style( 'style-name-3', $dir .'/meta-box/css/select.css', array(), "4.33");        
         wp_enqueue_style( 'style-name-4', $dir .'/meta-box/css/style.css', array(), "4.33");        
         // wp_enqueue_script( 'script-name-1', 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', array('jquery'), '1.9.1', false );
         wp_enqueue_script( 'script-name-2', $plugin_dir .'/js/bootstrap.js', array('jquery'), '1.0.0', false );
         wp_enqueue_script( 'script-name-3', $plugin_dir .'/js/bootstrap-combobox.js', array('jquery'), '1.0.0', false );    
    }

    // Create a new Post Type 
    public function create_post_type() {
        register_post_type( 'av_project',
            array(
                'labels' => array(
                    'name' => __( 'Projects' ),
                    'singular_name' => __( 'Project' )
                ),
                'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments','revisions'  ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'av_project'),
            )
        );
    }
    
   

    
    
    
    
  // Showing the contents of the Metaboxes for AV_Units
  
 public function init_av_projects_metabox () {
 	
add_meta_box( 'unit_Project_Information', "Project Information", array($this,'av_projects_metabox'), 'av_project', 'normal', 'low');
 	
 }  
  
   public function av_projects_metabox () {
   	
   	$post_id = $_GET['post'];//$wp_query->post->ID;
     
        $postmetaArray = get_post_meta($post_id);
        if (sizeof($postmetaArray) > 0) {
            $proj_abbr = $postmetaArray['proj_abbr'][0];
            $start_date = $postmetaArray['start_date'][0];
            $end_date = $postmetaArray['end_date'][0];
            $proj_head_name = $postmetaArray['proj_head_name'][0];
            $proj_head_email = $postmetaArray['proj_head_email'][0];
            $proj_head_number = $postmetaArray['proj_head_number'][0];
            $team_members = $postmetaArray['team_members'][0];  
        }
        else {
          $proj_abbr = "";
            $start_date = "";
            $end_date = "";
            $proj_head_name = "";
            $proj_head_email = "";
            $proj_head_number = "";
            $team_members = "";  
        }
?>
   	
     <div data-autosave="false" class="rwmb-meta-box"><input type="hidden" value="43d778ddf0" name="nonce_project-information" id="nonce_project-information">
     <input type="hidden" value='<?php echo "/savi/wp-admin/post.php?post=$_GET[post]";?>&amp;action=edit&amp;message=1' name="_wp_http_referer">
     <div class="rwmb-field rwmb-text-wrapper">
         <div class="rwmb-label">
		       <label for="proj_abbr">Project Abbreviation</label>
	      </div>
	       <div class="rwmb-input">
	          <input type="text" size="30" value="<?php echo $proj_abbr; ?>" id="proj_abbr" name="proj_abbr" class="rwmb-text">
	       </div>
	  </div>
	  <div class="rwmb-field rwmb-date-wrapper required">
	      <div class="rwmb-label">
			    <label for="start_date">Start Date</label>
		   </div>
			<div class="rwmb-input">
			    <input type="text" data-options="{&quot;dateFormat&quot;:&quot;dd-mm-yy&quot;,&quot;showButtonPanel&quot;:true,&quot;appendText&quot;:&quot;(dd-mm-yyyy)&quot;,&quot;changeMonth&quot;:true,&quot;changeYear&quot;:true}" size="30" id="dp1385976672444" value="<?php echo $start_date; ?>" name="start_date" class="rwmb-date hasDatepicker">
			    <span class="ui-datepicker-append">
			       (dd-mm-yyyy)
			     </span>
			</div>
    </div>
    <div class="rwmb-field rwmb-date-wrapper">
       <div class="rwmb-label">
	    <label for="end_date">End Date</label>
	 </div>
	<div class="rwmb-input">
	    <input type="text" data-options="{&quot;dateFormat&quot;:&quot;dd-mm-yy&quot;,&quot;showButtonPanel&quot;:true,&quot;appendText&quot;:&quot;(dd-mm-yyyy)&quot;,&quot;changeMonth&quot;:true,&quot;changeYear&quot;:true}" size="30" id="dp1385976672445" value="<?php echo $end_date; ?>" name="end_date" class="rwmb-date hasDatepicker">
	    <span class="ui-datepicker-append">
	       (dd-mm-yyyy)
	    </span>
	    </div>
	</div>
	<div class="rwmb-field rwmb-text-wrapper">
	    <div class="rwmb-label">
		     <label for="proj_head_name">Project Head Name</label>
		 </div>
		 <div class="rwmb-input">
		     <input type="text" size="30" value="<?php echo $proj_head_name; ?>" id="proj_head_name" name="proj_head_name" class="rwmb-text">
		 </div>
  </div>
  <div class="rwmb-field rwmb-email-wrapper">
      <div class="rwmb-label">
		    <label for="proj_head_email">Project Head Email</label>
		</div>
		<div class="rwmb-input">
		    <input type="email" size="30" value="<?php echo $proj_head_email; ?>" id="proj_head_email" name="proj_head_email" class="rwmb-email">
		</div>
  </div>
  <div class="rwmb-field rwmb-text-wrapper">
      <div class="rwmb-label">
		    <label for="proj_head_number">Project Head Phone</label>
		</div>
		<div class="rwmb-input">
		    <input type="text" size="30" value="<?php echo $proj_head_number; ?>" id="proj_head_number" name="proj_head_number" class="rwmb-text">
		</div>
  </div>
  <div class="rwmb-field rwmb-textarea-wrapper">
      <div class="rwmb-label">
		    <label for="team_members">Team Members</label>
		</div>
		<div class="rwmb-input">
		    <textarea rows="5" cols="20" id="team_members" name="team_members" class="rwmb-textarea large-text">
		    <?php echo $team_members; ?>
		    </textarea>
		    <p class="description" id="team_members_description">Team Members - One Name per line please</p>
		</div>
  </div>
  <div class="rwmb-field rwmb--wrapper">
       <div class="rwmb-input"></div>
  </div>
</div>
  <?php 
   
   }



  /*  public function init_av_projects_metabox() {

             
         global $meta_boxes;
         $meta_boxes = array();
    
         $meta_boxes[] = array(
                'title' => __( 'Project Information', 'rwmb' ),
                        // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
                        'pages' => array('av_project' ),
                'fields' => array(

                     // Name Landphone
                    array(
                        'name' => __( 'Project Abbreviation', 'rwmb' ),
                        'id'   => "proj_abbr",
                        'type' => 'text',

                    ),

                    // Start Date
                    array(
                        'name' => __( 'Start Date', 'rwmb' ),
                        'id'   => "start_date",
                        'type' => 'date',
                        'required'  => true,
                        
                        // jQuery date picker options. See here http://api.jqueryui.com/datepicker
                        'js_options' => array(
                            'appendText'      => __( '(dd-mm-yyyy)', 'rwmb' ),
                            'dateFormat'      => __( 'dd-mm-yy', 'rwmb' ),
                            'changeMonth'     => true,
                            'changeYear'      => true,
                            'showButtonPanel' => true,
                        ),
                    ),

                    // End Date
                    array(
                        'name' => __( 'End Date', 'rwmb' ),
                        'id'   => "end_date",
                        'type' => 'date',
                        
                        // jQuery date picker options. See here http://api.jqueryui.com/datepicker
                        'js_options' => array(
                            'appendText'      => __( '(dd-mm-yyyy)', 'rwmb' ),
                            'dateFormat'      => __( 'dd-mm-yy', 'rwmb' ),
                            'changeMonth'     => true,
                            'changeYear'      => true,
                            'showButtonPanel' => true,
                        ),
                    ),
                    
                     // Project Head Name
                    array(
                        'name' => __( 'Project Head Name', 'rwmb' ),
                        'id'   => "proj_head_name",
                        'type' => 'text',

                    ),

                    // Project Head Email
                    array(
                        'name'  => __( 'Project Head Email', 'rwmb' ),
                        'id'    => "proj_head_email",
                        
                        'type'  => 'email',
                        'std'   => '',
                    ),
                    
                     // Project Head Number
                    array(
                        'name' => __( 'Project Head Phone', 'rwmb' ),
                        'id'   => "proj_head_number",
                        'type' => 'text',

                    ),
                   
                    // Affiliation NoteTEXTAREA
                    array(
                        'name' => __( 'Team Members', 'rwmb' ),
                        'desc' => __( 'Team Members - One Name per line please', 'rwmb' ),
                        'id'   => "team_members",
                        'type' => 'textarea',
                        'cols' => 20,
                        'rows' => 5,
                    ),
                    
                    // Editor settings, see wp_editor() function: look4wp.com/wp_editor
                'options' => array(
                                'textarea_rows' => 5,
                                'teeny'         => true,
                                'media_buttons' => false,
                            ),
                       
                 
                                     
                )
                
            );
                    
        
        
        global $meta_boxes;
        foreach ( $meta_boxes as $meta_box ) {
            new RW_Meta_Box( $meta_box );
        }
    } */
   // Showing the contents of the Metaboxes for AV_Projects Admin Metabox
    public function init_av_projects_admin_metabox() {
   

    add_meta_box( 'unit_admin', "Admin", array($this,'admin_metabox'), 'av_project', 'normal', 'low');   
    }

   public function init_av_projects_parentunits_metabox(){
   
    global $meta_boxes;

    add_meta_box( 'parent_units', "Parent Units", array($this,'parent_units_metabox'), 'av_project', 'normal', 'low');

   }
    
   public function admin_metabox() {
     
        $post_id = $_GET['post'];//$wp_query->post->ID;
     
        $postmetaArray = get_post_meta($post_id);
        if (sizeof($postmetaArray) > 0) {
            $revisions = $postmetaArray['revision_note'][0];  
        }
        else {
          $revisions = '';
        }

?>

             <div class="rwmb-field rwmb-textarea-wrapper">
                 <div class="rwmb-label">
					<label for="revision_note">Revisions</label>
				 </div>
				 <div class="rwmb-input">
                     <textarea rows="3" cols="20" id="revision_note" name="revision_note" class="rwmb-textarea large-text">
                        <?php echo $revisions; ?>
                     </textarea>
                     <p class="description" id="revision_note_description">Revision Note</p>
                </div>
            </div>
<?php
   }
 
    function parent_units_metabox($post) {
        $parentUnitsMeta = get_post_meta($post->ID, 'parent_unit', false);
        $allParentUnits = $parentUnitsMeta[0];
        $avUnitQuery = new WP_Query(array('post_type' => 'av_unit',));
        $avOptions = "";
                
        
        if($avUnitQuery->have_posts()) {
            while ( $avUnitQuery->have_posts() ) {
                $avUnitQuery->the_post();
                $avUnitName = get_the_title();
                $avOptions .= "<option value='$avUnitName'>$avUnitName</option>\n";

            }
        }
        wp_reset_query();


        ?>        
        <label>Select the Parent Unit</label>
        <select class="combobox" id="unitComboBox" name="inline">
            <option></option>
            <?php echo $avOptions ?>
        </select>
        <input type='button' value='Add this Unit to the Project' onclick='addonemore()'>
        
        <?php 
        echo "<div class='repeat-wrapper'>\n";
        
        if (sizeof($allParentUnits) > 0) {
            foreach($allParentUnits as $key=>$parentUnits) {
            
                echo "<div class='repeat-fields'>\n";
                echo "<input class='parent_units' name='parent_unit[]' enabled=false type='text' style='vertical-align:top;' value='".$parentUnits['parent_unit']."'>\n";
                echo "<input type='button' value='Unselect this Unit' onclick='removethis(".$key.")'>";
                echo "</div>";
             
            }
        }
        
        echo "</div> <!-- repeat-wrapper -->\n";

        ?>

        <?php 
         ?>
        
        <script type="text/javascript">
        //<![CDATA[
                
             jQuery(document).ready(function(){
                jQuery('.combobox').combobox()
             });

            function addonemore() {
                size = jQuery(".repeat-fields").size();
                
                selectedUnit = jQuery("select#unitComboBox option").filter(":selected").text();
                unitIsPresent = false;
                jQuery("input.parent_units").each(function(i) { 
                    if (selectedUnit == this.value) {
                        unitIsPresent = true;
                    }
                });

                if (!unitIsPresent) {
                    htmlText = '<div class="repeat-fields">'
                    htmlText += '<input class="parent_units" name="parent_unit[]" enabled=false style="vertical-align:top;" value="' + selectedUnit + '" type="text" ">';
                    htmlText += '<input type="button" value="Unselect this Unit" onclick="removethis('+size+')">';
                    htmlText += '</div>';
                    jQuery(".repeat-wrapper").append(htmlText);
                } else {
                    alert ("Unit has already been added..");
                    
                }
            }

            function removethis(removeIndex,obj) {
                size = jQuery(".repeat-fields").size();
                if (size == 1) 
                    return
                    
                repObj = jQuery(".repeat-fields").get(removeIndex);
                repObj.remove();
            }
        //]]>
            
        </script>
        <?php
   
    }
    // Saving the meta data when saving the post
    public function save_postdata( $post_id ) {
      global $post ;
        
        if( $post->post_type != "av_project" ) return $post_id;

        /*
        * We need to verify this came from the our screen and with proper authorization,
        * because save_post can be triggered at other times.
        */
        // Comment by Venkat - Removed the Nonce for the moment. Will do it later...

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
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

        /* OK, its safe for us to save the data now. */

       
        // Sanitize user input.
        $proj_abbr = sanitize_text_field( $_POST['proj_abbr'] );
        $start_date = sanitize_text_field( $_POST['start_date'] );
        $end_date = sanitize_text_field( $_POST['end_date'] );
        $proj_head_name = sanitize_text_field( $_POST['proj_head_name'] );
        $proj_head_email = sanitize_text_field( $_POST['proj_head_email']);
        $proj_head_number = sanitize_text_field( $_POST['proj_head_number']);
        $team_members = sanitize_text_field( $_POST['team_members']);
        $revision_note = sanitize_text_field( $_POST['revision_note']);        
        $parentUnit_array = $_POST['parent_unit'];
       

        $arraySize = sizeof($parentUnit_array);
        $allParentUnits = array();
        for ($arrayIndex=0;$arrayIndex<$arraySize; $arrayIndex++) {
            $parent_unit = $parentUnit_array[$arrayIndex];

            if(trim($parent_unit)!=""){
               $parentUnitInfo = array (  "parent_unit" => $parent_unit,
                           );
            $allParentUnits[$arrayIndex] = $parentUnitInfo;
            }
        }   

        // Update the meta field in the database.

        update_post_meta( $post_id, 'proj_abbr', $proj_abbr);
        update_post_meta( $post_id, 'start_date', $start_date);
        update_post_meta( $post_id, 'end_date', $end_date);
        update_post_meta( $post_id, 'proj_head_name', $proj_head_name);
        update_post_meta( $post_id, 'proj_head_email', $proj_head_email);
        update_post_meta( $post_id, 'proj_head_number', $proj_head_number);
        update_post_meta( $post_id, 'team_members', $team_members);
        update_post_meta( $post_id, 'revision_note', $revision_note);
        update_post_meta( $post_id, 'parent_unit', $allParentUnits);
 



     }   

}



?>
