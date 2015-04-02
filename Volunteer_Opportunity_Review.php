<?php
class Volunteer_Opportunity_Review extends Default_Profile  {

    public $custom_type = 'view_2';       
    public function __construct() {
        // Create a new Post Type
        add_action( 'init', array($this, 'create_post_type' ));
        // Call the Units_Metabox when showing the post
        add_action( 'add_meta_boxes', array($this, 'savi_vol_opp_rev_init_metabox' ));  
        // Call the Save Post when av_unit is being saved
        add_action( 'save_post', array($this, 'savi_vol_opp_rev_save_data' ));
        // Call the custom filter, filtering the posts by country        
        add_action( 'admin_enqueue_scripts', array($this,'savi_admin_dual_css_js'), 10, 1 ); 
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
                    'name' => __( 'Opportunity Reviews' ),
                    'singular_name' => __( 'Opportunity Reviews' )
                ),
                'supports' => array( 'title'),
                'public' => true,
				'menu_icon' => 'dashicons-format-aside',
                'has_archive' => true,
                'rewrite' => array('slug' => $this->custom_type),
            )
        );
        
    }
    public function savi_vol_opp_rev_show_metabox($post) {

        $post_id = $_GET['post'];//$wp_query->post->ID;
        $expressOpportunitiesMeta = get_post_meta( $post_id, 'express_opportunities', false );
        $postmetaArray = get_post_meta($post->ID);        
        $current_user_id=get_current_user_id();
        $post_user_id = $postmetaArray['user_id'][0];
		  $allexpressOpportunities = $expressOpportunitiesMeta[0];
		  $SelectOpportunityHTML='<select id="to_select_list" multiple="multiple" name="to_select_list[]" >';
		  $expressOpportunities = "";
		  $allexpressOpportunities_id = array();
        if (sizeof($allexpressOpportunities) > 0 && is_array($allexpressOpportunities)) {
        		foreach($allexpressOpportunities as $key=>$expressOpportunity) {
                        $expressOpportunitiesID = $expressOpportunity['express_opportunity'];
                        $expressOpportunitiesValue = get_the_title($expressOpportunitiesID); 
                         $SelectOpportunityHTML.= "<option value='$expressOpportunitiesID'>
                                                   $expressOpportunitiesValue</option>\n";
                        $allexpressOpportunities_id[] = $expressOpportunitiesID;                             
            }
            
        } 
         $SelectOpportunityHTML.= "</select>\n";  
        $Query = new WP_Query( array(
                    'post_type' => 'av_opportunity',
                    'posts_per_page' => -1,
                    'post_status' => array( 'publish' ),
                    ));
        $postIndex = 0;
        $projIndex = 0;
        $args = array(
	'show_option_all'    => '',
	'show_option_none'   => 'Show All Work Area',
	'orderby'            => 'ID', 
	'order'              => 'ASC',
	'show_count'         => 0,
	'hide_empty'         => 1, 
	'child_of'           => 0,
	'exclude'            => '',
	'echo'               => 1,
	'selected'           => 0,
	'hierarchical'       => 1, 
	'name'               => 'filter_workarea',
	'id'                 => 'filter_workarea',
	'class'              => 'postform',
	'depth'              => 0,
	'tab_index'          => 0,
	'taxonomy'           => 'savi_opp_cat_work_area',
	'hide_if_empty'      => false);
	$args1 = array(
	'show_option_all'    => '',
	'show_option_none'   => 'Show All Work Type',
	'orderby'            => 'ID', 
	'order'              => 'ASC',
	'show_count'         => 0,
	'hide_empty'         => 1, 
	'child_of'           => 0,
	'exclude'            => '',
	'echo'               => 1,
	'selected'           => 0,
	'hierarchical'       => 1, 
	'name'               => 'filter_worktype',
	'id'                 => 'filter_worktype',
	'class'              => 'postform',
	'depth'              => 0,
	'tab_index'          => 0,
	'taxonomy'           => 'savi_opp_cat_work_type',
	'hide_if_empty'      => false);
         $SelectHTML = '<select id="from_select_list" multiple="multiple" name="from_select_list[]">';
        if ($Query->found_posts > 0) {
            while ($Query->have_posts()) {
                $Query->the_post();
				$no_of_opportunities=get_post_meta( get_the_ID(), "no_of_opportunities", true );
				if($no_of_opportunities > 0 ){
					$name = get_the_title();
				    $ID = get_the_ID();
                

				   if(!in_array($ID,$allexpressOpportunities_id)) {
						$SelectHTML .= "<option value='$ID'>";
					$SelectHTML .= "$name</option>\n";
				   }
				}
           }
         }
    $SelectHTML .= "</select>\n"; 
   
    echo '<div class="disp-row">';
      echo '<div class="rwmb-input">';
            echo'<div class="main-input"><div class="left-select"><p>Opportunities associated profile</p>'.$SelectOpportunityHTML.
                            "\n</div>";
                echo '<div class="middle">';
                          echo '<input id="moveleft" type="button" value=">>" 
                           onclick="move_list_items(\'to_select_list\',\'from_select_list\');"  />
                           <input id="moveright" type="button"
                             value="<<" onclick="move_list_items(\'from_select_list\',\'to_select_list\');" /></div>';
            	 echo "<div class='right-select'>";
            	     wp_dropdown_categories( $args );
            	      echo "<br>";    
            	       wp_dropdown_categories( $args1 );
            	       echo "<input type='number' id ='filter_duration' name ='filter_duration' value='' /> Duration";
            	  echo "<p>Open Opportunities</p>".$SelectHTML."\n\n</div></div>";
      echo "</div>";
    echo "</div>";   
    
        if($current_user_id!=$post_user_id) {
?>
       <script type="text/javascript" >
      
       jQuery("#title").attr("readonly",true);
       </script>
<?php      
       }  
       ?>
     <script type="text/javascript">
      jQuery("#filter_workarea").change(function() {
          var post_id ='';
          var workarea_term_id = jQuery('#filter_workarea').val();
          var worktype_term_id = jQuery('#filter_worktype').val();
          var duration = jQuery('#filter_duration').val();
          alert(workarea_term_id +" "+worktype_term_id+" "+duration);
	    
       });
   
       </script>
    <?php  
   }
   public function savi_vol_opp_rev_init_metabox() {
       add_meta_box( 'volunteer_opportunity_review', "Opportunities opened"
                   , array($this,'savi_vol_opp_rev_show_metabox'), $this->custom_type, 'normal', 'low'); 
   }    
    // Saving the meta data when saving the post
   public function savi_vol_opp_rev_save_data( $post_id ) {
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
         $expressOpportunity_array = $_POST['to_select_list'];
         $arraySize = sizeof($expressOpportunity_array);
         $allexpressOpportunities = array();
         for ($arrayIndex=0;$arrayIndex<$arraySize; $arrayIndex++) {
            $express_opportunity = $expressOpportunity_array[$arrayIndex];

            if(trim($express_opportunity)!=""){
               $allexpressOpportunityInfo = array (  "express_opportunity" => $express_opportunity,
                           );
               $allexpressOpportunities[$arrayIndex] = $allexpressOpportunityInfo;
            }
          // echo $express_opportunity."<br>";
        }
      
       update_post_meta($post_id,'express_opportunities',$allexpressOpportunities);
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
	
  function savi_vol_opp_rev_edit_columns( $columns ) {
   	$columns = array(
			'cb' => '<input type="checkbox" />',
			'express_opportunities' => __( 'Express Opportunities' )
			
		   );
		return $columns;
	 } 
 }
?>