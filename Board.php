<?php
class Board  {

    private $custom_type = 'view_8';       
    public function __construct() {
        // Create a new Post Type
        add_action( 'init', array($this, 'create_post_type' ));
        // Call the Units_Metabox when showing the post
        add_action( 'add_meta_boxes', array($this, 'savi_board_init_metabox' ));  
        // Call the Save Post when av_unit is being saved
        add_action( 'save_post', array($this, 'savi_board_save_data' ));
        // Call the custom filter, filtering the posts by country        
        add_action( 'admin_enqueue_scripts', array($this,'savi_admin_dual_css_js'), 10, 1 ); 
    }
    // Create a new Post Type 
    public function create_post_type() {
        register_post_type( $this->custom_type,
            array(
                'labels' => array(
                    'name' => __( 'Boards' ),
                    'singular_name' => __( 'Board' )
                ),
                'supports' => array( 'title'),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => $this->custom_type),
            )
        );
        
    }
    public function savi_board_show_metabox($post) {

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
        $SelectHTML = '<select id="from_select_list" multiple="multiple" name="from_select_list[]">';
        if ($Query->found_posts > 0) {
            while ($Query->have_posts()) {
                $Query->the_post();
                $name = get_the_title();
				    $ID = get_the_ID();
                

               if(!in_array($ID,$allexpressOpportunities_id)) {
                	$SelectHTML .= "<option value='$ID'>";
               	$SelectHTML .= "$name</option>\n";
               }	
           }
         }
    $SelectHTML .= "</select>\n"; 
    echo '<div class="disp-row">';
      echo '<div class="rwmb-input">';
            echo'<div class="main-input"><div class="left-select"><p>Interest Shown</p>'.$SelectOpportunityHTML.
                            "\n</div>";
                echo '<div class="middle">';
                          echo '<input id="moveleft" type="button" value=">>" 
                           onclick="move_list_items(\'to_select_list\',\'from_select_list\');"  />
                           <input id="moveright" type="button"
                             value="<<" onclick="move_list_items(\'from_select_list\',\'to_select_list\');" /></div>';
            	 echo'<div class="right-select"><p>Open Opportunities</p>'.$SelectHTML."\n\n</div></div>";
      echo "</div>";
    echo "</div>";     
        if($current_user_id!=$post_user_id) {
?>
       <script type="text/javascript" >
      
       jQuery("#title").attr("readonly",true);
       </script>
<?php      
       }  
   }
   public function savi_board_init_metabox() {
       add_meta_box( 'volunteer_opportunity_review', "Opportunities opened"
                   , array($this,'savi_board_show_metabox'), $this->custom_type, 'normal', 'low'); 
   }    
    // Saving the meta data when saving the post
   public function savi_board_save_data( $post_id ) {
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
           echo $express_opportunity."<br>";
        }
       update_post_meta($post_id,'express_opportunities',$allexpressOpportunities);
    }   
    function savi_admin_dual_css_js(){
		$template_dir = get_stylesheet_directory_uri();

		wp_enqueue_script( 'savi-dual-js', $template_dir . '/js/jquery.bootstrap-duallistbox.js',
		                                             array( 'jquery'), SAVI_2014_VERSION, false );
		wp_enqueue_style( 'savi-dual-css', $template_dir . '/css/bootstrap-duallistbox.css', array(), 1.0 );
	}
 
 }
?>