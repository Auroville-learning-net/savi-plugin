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
        
      add_action( 'wp_ajax_nopriv_savi_opportunity_filter_ajaxSubmission', array($this,'savi_opportunity_filter_ajaxSubmission') );  
		add_action( 'wp_ajax_savi_opportunity_filter_ajaxSubmission', array($this,'savi_opportunity_filter_ajaxSubmission') );   
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
                 'exclude_from_search' => false,
                 'publicly_queryable'=>false
            )
        );
        
    }
    public function savi_vol_opp_rev_show_metabox($post) {

        $post_id = $_GET['post'];
      //  $test_array = array('sathya' =>'name','vrata' =>'name','hari'=>'name');
      //  $serialize = serialize($test_array);
     //   echo $serialize;
       // $workarea = 'a:48:{s:19:"Natural Environment";s:0:"";s:20:"Education & Research";s:0:"";s:14:"Culture & Arts";s:0:"";s:12:"Technologies";s:0:"";s:24:"Services & Manufacturing";s:0:"";s:25:"Community & Social Change";s:0:"";s:15:"Integral Health";s:0:"";s:25:"Land & water preservation";s:19:"Natural Environment";s:28:"Spirituality & Integral Yoga";s:20:"Education & Research";s:11:"Visual arts";s:14:"Culture & Arts";s:18:"Renewable energies";s:12:"Technologies";s:17:"Social Enterprise";s:24:"Services & Manufacturing";s:17:"Rural Development";s:25:"Community & Social Change";s:27:"Ayurveda & Eastern Medicine";s:15:"Integral Health";s:22:"Farming & Permaculture";s:19:"Natural Environment";s:22:"Sri Aurobindo & Mother";s:20:"Education & Research";s:20:"Events & Exhibitions";s:14:"Culture & Arts";s:29:"Urban Planning & Architecture";s:12:"Technologies";s:26:"Crafts, Textiles & Fashion";s:24:"Services & Manufacturing";s:12:"Microfinance";s:25:"Community & Social Change";s:26:"Yoga, Wellness & Therapies";s:15:"Integral Health";s:32:"Forests, Ecology & Bio-diversity";s:19:"Natural Environment";s:14:"Indian Culture";s:20:"Education & Research";s:20:"Cinema & Documentary";s:14:"Culture & Arts";s:24:"Construction & Materials";s:12:"Technologies";s:22:"Catering & Hospitality";s:24:"Services & Manufacturing";s:9:"Mediation";s:25:"Community & Social Change";s:30:"Dentistry, Allopathic Medicine";s:15:"Integral Health";s:17:"Seed conservation";s:19:"Natural Environment";s:29:"Primary & Secondary Education";s:20:"Education & Research";s:15:"Performing arts";s:14:"Culture & Arts";s:28:"Water Treatment & Management";s:12:"Technologies";s:15:"Food Production";s:24:"Services & Manufacturing";s:18:"Community services";s:25:"Community & Social Change";s:25:"Public Health & Nutrition";s:15:"Integral Health";s:20:"Coastline protection";s:19:"Natural Environment";s:16:"Self-development";s:20:"Education & Research";s:9:"Recycling";s:12:"Technologies";s:16:"Tourism & Travel";s:24:"Services & Manufacturing";s:21:"Orientation & welcome";s:25:"Community & Social Change";s:27:"Veterinary & Animal welfare";s:15:"Integral Health";s:21:"Professional Training";s:20:"Education & Research";s:22:"Information Technology";s:12:"Technologies";s:18:"Telecommunications";s:24:"Services & Manufacturing";s:23:"Community-based Economy";s:25:"Community & Social Change";s:23:"Environmental education";s:20:"Education & Research";s:19:"Media Communication";s:24:"Services & Manufacturing";s:9:"Languages";s:20:"Education & Research";}';
      // echo "<pre>", print_r(unserialize($workarea)),"</pre>";
        $check_data ="";
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
                        $av_unit  = get_post_meta( $expressOpportunitiesID, "av_unit", true );
				        $av_unit_name  = trim(strtoupper(get_post_meta($av_unit,'unit_short_name',true)),"-"); 
                        $expressOpportunitiesValue = get_the_title($expressOpportunitiesID); 
                         $SelectOpportunityHTML.= "<option value='$expressOpportunitiesID'>
                                                   $av_unit_name - $expressOpportunitiesValue</option>\n";
                        $allexpressOpportunities_id[] = $expressOpportunitiesID;       
                        $check_data.=$expressOpportunitiesID."|";                      
            }
         $check_data=trim($check_data,"|");
        } 
        
         $SelectOpportunityHTML.= "</select>\n";  
        $Query = new WP_Query( array(
                    'post_type' => 'av_opportunity',
                    'posts_per_page' => -1,
                    'post_status' => array( 'publish' ),
                    'orderby' => 'date',
                    'order' => 'DESC',
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
				$opportunity_status = get_post_meta( get_the_ID(), "opportunity_status", true );
				$av_unit  = get_post_meta( get_the_ID(), "av_unit", true );
				$av_unit_name  = trim(strtoupper(get_post_meta($av_unit,'unit_short_name',true)),"-");
				if($opportunity_status == "opened" ){
					$name = get_the_title();
				    $ID = get_the_ID();
                

				   if(!in_array($ID,$allexpressOpportunities_id)) {
						$SelectHTML .= "<option value='$ID'>";
					    $SelectHTML .= "$av_unit_name - $name</option>\n";
					    
				   }
				}
           }
               
         }
     wp_reset_query();
    $SelectHTML .= "</select>\n"; 
   
    echo '<div class="disp-row">';
      echo '<div class="rwmb-input">';
            echo'<div class="main-input"><div class="left-select"><p>Opportunities associated profile';
            if($check_data!=""){ 
				  echo  "<span id='span_left_opp_ids' style='float:left;display:block'>
            	        <a href='javascript:void(0);' id='link_left_opp_ids' data-link='$check_data'>Opportunity Details</a></p>";
             echo $SelectOpportunityHTML."\n</div>";
		    }
            else{
				  echo  "<span id='span_left_opp_ids' style='float:left;display:none'>
            	        <a href='javascript:void(0);' id='link_left_opp_ids'>Opportunity Details</a></p>";
             echo $SelectOpportunityHTML."\n</div>";
			}
          
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
            	  echo "<p>Open Opportunities 
            	        <span id='span_opp_ids' style='float:right;display:none'>
            	        <a href='javascript:void(0);' id='link_opp_ids' >Opportunity Details</a>
            	       </p>".$SelectHTML."\n\n</div></div>";
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
      jQuery("#filter_workarea,#filter_worktype,#filter_duration").change(function() {
          var post_id ="<?php echo $post_id ?>";
          var workarea_term_id = jQuery('#filter_workarea').val();
          var worktype_term_id = jQuery('#filter_worktype').val();
          var duration = jQuery('#filter_duration').val();
         jQuery.ajax({
             	     url: "<?php echo get_bloginfo('url')?>/wp-admin/admin-ajax.php",
      	             data: {
            		    'post_id' : post_id,'workarea_term_id':workarea_term_id,'worktype_term_id':worktype_term_id,'duration':duration,
            		    'action' : 'savi_opportunity_filter_ajaxSubmission'
            		   },
                     success: function(respon) {
                     // alert(respon);
                        // if(respon!=""){
                         jQuery('#from_select_list').empty();
                         jQuery('#from_select_list').html(respon);
                         
                       //  } 
                       if (respon == "") {
	                         jQuery('span#span_opp_ids').hide();
               				 jQuery('a#link_opp_ids').attr('data-link',"");   	
                          }                      
                      
                     },
                     error: function(respon, ajaxOptions, thrownError) {
        		alert(respon.status);
        		alert(thrownError);
        		alert(ajaxOptions);
        	     }
                  });
	    
       });
      jQuery( "#from_select_list" ).change(function() {
    			var str = "";
    			jQuery( "#from_select_list option:selected" ).each(function() {
                 if(str == "") {
                   str = jQuery( this ).val();
                 }
                 else{
                   str += "|"+jQuery( this ).val();
                 }     				
      				
    			});
    			if(str !=""){
    			
                jQuery('span#span_opp_ids').show();
                jQuery('a#link_opp_ids').attr('data-link',str);   			
    			}
    		   else {
	              jQuery('span#span_opp_ids').hide();
            }
  });
      jQuery( "#to_select_list" ).change(function() {
    			var str = "";
    			jQuery( "#to_select_list option:selected" ).each(function() {
                 if(str == "") {
                   str = jQuery( this ).val();
                 }
                 else{
                   str += "|"+jQuery( this ).val();
                 }     				
      				
    			});
    			if(str !=""){
    			
                jQuery('span#span_left_opp_ids').show();
                jQuery('a#link_left_opp_ids').attr('data-link',str);   			
    			}
    		   else {
	              jQuery('span#span_left_opp_ids').hide();
            }
  });
     jQuery( "#link_opp_ids,#link_left_opp_ids" ).on('click', function(){
     	
     	  var ulrs = jQuery(this).attr('data-link');
     	  var links = ulrs.split('|');
     	   for(var i=0;i< links.length;i++){
              var url = "<?php echo get_bloginfo('url')?>/wp-admin/post.php?post="+links[i]+"&action=edit";
              window.open(url, '_blank');
    }
     	  
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
	 
	 function savi_opportunity_filter_ajaxSubmission() {
       global  $wpdb;
       $post_id = $_REQUEST['post_id'];
       $workarea_term_id = $_REQUEST['workarea_term_id'];
       $worktype_term_id = $_REQUEST['worktype_term_id'];
	    $duration = $_REQUEST['duration'];
	    
	    /*======================================================================
	        checking the combination workarea and worktype  and
	        duration are selected case
	     =======================================================================*/
	    
	    if($workarea_term_id !=-1 && $worktype_term_id !=-1 && $duration!="" ){
	    		$Query = new WP_Query( array(
                    'post_type' => 'av_opportunity',
                    'posts_per_page' => -1,
                    'post_status' => array( 'publish' ),
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'meta_query' => array(
        												array(
            											 'key' => 'duration',
            											 'value' => $duration,
            											 'type' => NUMERIC,
            											 'compare' => '='
                                          )
                                    ),
    					'tax_query' => array(
        										  'relation' => 'AND',
                                       array(
                                             'taxonomy'  => 'savi_opp_cat_work_area',
                                             'field'     => 'id',
                                             'terms'     => $workarea_term_id,
                                            
                                            ),
                                      array(
                                             'taxonomy'  => 'savi_opp_cat_work_type',
                                             'field'     => 'id',
                                             'terms'     => $worktype_term_id,
                                             
                                            ),      
                                )
              ));                                
	    }
	    
	    
	    /*======================================================================
	     checking the combination workarea and worktype and duration 
	     are not selected case$av_unit  = get_post_meta( get_the_ID(), "av_unit", true );
				$av_unit_name  = get_the_title($av_unit);
	    =======================================================================*/
	    
	    if($workarea_term_id ==-1 && $worktype_term_id ==-1 && $duration == "" ){
	    		$Query = new WP_Query( array(
                    'post_type' => 'av_opportunity',
                    'posts_per_page' => -1,
                    'post_status' => array( 'publish' ),
                    'orderby' => 'date',
                    'order' => 'DESC',
                    ));
	    }

	    /*======================================================================
	      checking the combination workarea and duration are selected and 
	      worktype is not selected case
	    =======================================================================*/	    
	    
	    if($workarea_term_id !=-1 && $worktype_term_id ==-1 && $duration!="" ){
	    		$Query = new WP_Query( array(
                    'post_type' => 'av_opportunity',
                    'posts_per_page' => -1,
                    'post_status' => array( 'publish' ),
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'meta_query' => array(
        												array(
            											 'key' => 'duration',
            											 'value' => $duration,
            											 'type' => NUMERIC,
            											 'compare' => '='
                                          )
                                    ),
    					'tax_query' => array(
        										   array(
                                             'taxonomy'  => 'savi_opp_cat_work_area',
                                             'field'     => 'id',
                                             'terms'     => $workarea_term_id,
                                             
                                            ),
                                    
                                )
              ));    
	    }
	    
	    /*======================================================================
	      checking the combination worktype and duration are selected and 
	      workarea is not selected case
	    =======================================================================*/
	    
	    if($workarea_term_id ==-1 && $worktype_term_id !=-1 && $duration!="" ){
	    		$Query = new WP_Query( array(
                    'post_type' => 'av_opportunity',
                    'posts_per_page' => -1,
                    'post_status' => array( 'publish' ),
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'meta_query' => array(
        												array(
            											 'key' => 'duration',
            											 'value' => $duration,
            											 'type' => NUMERIC,
            											 'compare' => '='
                                          )
                                    ),
    					'tax_query' => array(
        										   array(
                                             'taxonomy'  => 'savi_opp_cat_work_type',
                                             'field'     => 'id',
                                             'terms'     => $worktype_term_id,
                                            
                                            ),
                                    
                                )
              ));    
	    }	
	    
	    /*======================================================================
	      checking the combination workarea is selected, duration and 
	      worktype are not selected case
	    =======================================================================*/	
	    
	    if($workarea_term_id !=-1 && $worktype_term_id ==-1 && $duration == "" ){
	    		$Query = new WP_Query( array(
                    'post_type' => 'av_opportunity',
                    'posts_per_page' => -1,
                    'post_status' => array( 'publish' ),
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'tax_query' => array(
        										   array(
                                             'taxonomy'  => 'savi_opp_cat_work_area',
                                             'field'     => 'id',
                                             'terms'     => $workarea_term_id,
                                            
                                            ),
                                    
                                )
              ));    
	    }
       /*======================================================================
	      checking the combination worktype is selected, duration and 
	      workarea are not selected case
	    =======================================================================*/	 	    
	    
	    if($workarea_term_id ==-1 && $worktype_term_id !=-1 && $duration == "" ){
	    		$Query = new WP_Query( array(
                    'post_type' => 'av_opportunity',
                    'posts_per_page' => -1,
                    'post_status' => array( 'publish' ),
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'tax_query' => array(
        										   array(
                                             'taxonomy'  => 'savi_opp_cat_work_type',
                                             'field'     => 'id',
                                             'terms'     => $worktype_term_id,
                                             
                                            ),
                                    
                                )
              ));    
	    } 
	    
	     /*======================================================================
	      checking the combination worktype and workarea are selected
	      and duration is not selected case
	    =======================================================================*/	 	    
	    
	    if($workarea_term_id !=-1 && $worktype_term_id !=-1 && $duration == "" ){
	    
	    		$Query = new WP_Query( array(
                    'post_type' => 'av_opportunity',
                    'posts_per_page' => -1,
                    'post_status' => array( 'publish' ),
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'tax_query' => array(
        										  'relation' => 'AND',
                                       array(
                                             'taxonomy'  => 'savi_opp_cat_work_area',
                                             'field'     => 'id',
                                             'terms'     => $workarea_term_id,
                                             
                                            ),
                                      array(
                                             'taxonomy'  => 'savi_opp_cat_work_type',
                                             'field'     => 'id',
                                             'terms'     => $worktype_term_id,
                                            
                                            ),      
                                )
              ));       
	    } 
	    
	     /*======================================================================
	      checking the combination worktype and workarea are not selected,duration
	      and duration is selected case
	    =======================================================================*/	 	    
	    
	    if($workarea_term_id ==-1 && $worktype_term_id ==-1 && $duration != "" ){
	    		$Query = new WP_Query( array(
                    'post_type' => 'av_opportunity',
                    'posts_per_page' => -1,
                    'post_status' => array( 'publish' ),
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'meta_query' => array(
        												array(
            											 'key' => 'duration',
            											 'value' => $duration,
            											 'type' => NUMERIC,
            											 'compare' => '='
                                          )
                                    ),
    					
                 ));       
	    } 
	     
	     $expressOpportunitiesMeta = get_post_meta( $post_id, 'express_opportunities', false );
        $postmetaArray = get_post_meta($post->ID);        
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
	     
	    if ($Query->found_posts > 0) {
            while ($Query->have_posts()) {
                $Query->the_post();
				$opportunity_status = get_post_meta( get_the_ID(), "opportunity_status", true );
				$av_unit  = get_post_meta( get_the_ID(), "av_unit", true );
				$av_unit_name  = get_the_title($av_unit);
				if($opportunity_status == "opened" ){
					$name = get_the_title();
				    $ID = get_the_ID();
             
				   if(!in_array($ID,$allexpressOpportunities_id)) {
						$SelectHTML.= "<option value='$ID'>";
					   $SelectHTML.= "$av_unit_name - $name</option>\n";
				   }
				}
           }
         }
								
    
        echo $SelectHTML;

     exit();
}
 }
?>
