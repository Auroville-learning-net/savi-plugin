<?php
class Custom_Views_Column  {

           
    public function __construct() {
    	 
        add_action( 'manage_view_0_posts_custom_column', array($this,'savi_views01_manage_columns'), 10, 2 );         
        add_filter( 'manage_edit-view_0_columns',array($this,'savi_views0_edit_columns') ) ;
        // set custom columns to view_0 custom post type 
        add_action( 'manage_view_1_posts_custom_column', array($this,'savi_views01_manage_columns'), 10, 2 );        
        add_filter( 'manage_edit-view_1_columns',array($this,'savi_views1_edit_columns') ) ;
        // set custom columns to view_1 custom post type
        add_action( 'manage_view_2_posts_custom_column', array($this,'savi_views234_manage_columns'), 10, 2 );
        add_filter( 'manage_edit-view_2_columns',array($this,'savi_views2_edit_columns') ) ;
        // set custom columns to view_2 custom post type
        add_action( 'manage_view_3_posts_custom_column', array($this,'savi_views234_manage_columns'), 10, 2 );
        add_filter( 'manage_edit-view_3_columns',array($this,'savi_views34_edit_columns') ) ;
        // set custom columns to view_3 custom post type
        add_action( 'manage_view_4_posts_custom_column', array($this,'savi_views234_manage_columns'), 10, 2 );
        add_filter( 'manage_edit-view_4_columns',array($this,'savi_views34_edit_columns') ) ;
        // set custom columns to view_4 custom post type 
        add_action( 'manage_view_5_posts_custom_column', array($this,'savi_views5678_manage_columns'), 10, 2 );
        add_filter( 'manage_edit-view_5_columns',array($this,'savi_views5678_edit_columns') ) ;
        // set custom columns to view_5 custom post type
        add_action( 'manage_view_6_posts_custom_column', array($this,'savi_views5678_manage_columns'), 10, 2 );
        add_filter( 'manage_edit-view_6_columns',array($this,'savi_views5678_edit_columns') ) ;
        // set custom columns to view_6 custom post type 
        add_action( 'manage_view_7_posts_custom_column', array($this,'savi_views5678_manage_columns'), 10, 2 );
        add_filter( 'manage_edit-view_7_columns',array($this,'savi_views5678_edit_columns') ) ;
        // set custom columns to view_7 custom post type
        add_action( 'manage_view_8_posts_custom_column', array($this,'savi_views5678_manage_columns'), 10, 2 );
        add_filter( 'manage_edit-view_8_columns',array($this,'savi_views5678_edit_columns') ) ;
        // set custom columns to view_8 custom post type   
    }
    // Create a new Post Type 
   function savi_views01_manage_columns( $column, $post_id ) {
		global $post,$wpdb;
      switch( $column ) {
           case 'savi_views_contact-details_age' :
						$age = get_post_meta( $post_id, 'savi_views_contact-details_age', true );
         			echo  $age;
				      break;
				case 'savi_views_stay-details_duration' :
						$time_of_stay = get_post_meta( $post_id, 'savi_views_stay-details_duration', true );
         			echo  $time_of_stay;
				      break;	
				case 'savi_views_initial-contact_prior-unit-contact' :
						$prior_unit = get_post_meta( $post_id, 'savi_views_initial-contact_prior-unit-contact', true );
         			echo  $prior_unit;
				      break;	
				case 'savi_views_education-details_intership' :
						$intership = get_post_meta( $post_id, 'savi_views_education-details_intership', true );
         			echo  $intership;
				      break;	            
				      
				case 'savi_views_contact-details_nationality' :
						$country = get_post_meta( $post_id, 'savi_views_contact-details_nationality', true );
						if(is_numeric($country)):
						 $country_sql="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 738 AND item_id=$country";
						 $country_results = $wpdb->get_results($country_sql,ARRAY_A);
						 $country = $country_results[0]['meta_value'];	
						// echo "<pre>",print_r($country_results),"</pre>".$country_sql;
						endif;
						echo  $country;
						break;
				
				 case 'newaction' :
                                       $Motivation = get_post_meta($post_id,'savi_views_motivations_goals-skills-to-be-gained',true);
        				$Skills = get_post_meta($post_id,'savi_views_skills_fields-of-interest',true);
        				$Motivation = str_replace('&quot;', '"', $Motivation);
                                        $Motivation = htmlspecialchars( $Motivation);
                                        $Motivation = nl2br( $Motivation);
                                        $Skills = str_replace('&quot;', '"', $Skills);
                                        $Skills = htmlspecialchars( $Skills);
                                        $Skills =nl2br( $Skills );
        				$skills_content ="<div class='popbox' id=skill_".$post_id."><h2>Skills</h2>".$Skills."
                                                          </div>";
        				$motivation_content ="<div class='popbox' id=motivation_".$post_id.">    
                                                                     <h2>Motivation</h2>".$Motivation."</div>";
				 if($post->post_type == 'view_0') :
					
        				$action = "<a href='javascript:void' class='popper'  
                                                    data-popbox=skill_".$post_id.">Skills</a>".$skills_content." | ";
        				$action.= "<a href='javascript:void' class='popper' 
                                                    data-popbox=motivation_".$post_id.">Motivation</a>".$motivation_content;

                                  
                                else:

                                       $workarea_sql = "SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 467 AND item_id
                                                           =(SELECT id FROM wp_frm_items WHERE form_id = 19 AND post_id=$post_id)";
		                       $workarea_results = $wpdb->get_results($workarea_sql,ARRAY_A);
		                       $workarea_meta_value =$workarea_results[0]['meta_value']; 
		                       $workarea_iterm_value=unserialize($workarea_meta_value);
		                       for($i=0;$i<count($workarea_iterm_value);$i++){
        		                       $term = get_term( $workarea_iterm_value[$i], 'savi_opp_cat_work_area' );
				               $workarea.="<p>".$term->name."</p>";   
                                       }
  	                               $worktype_sql="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 468 AND item_id
                                                          =(SELECT id FROM wp_frm_items WHERE form_id = 19 AND post_id=$post_id)";
		                       $worktype_results=$wpdb->get_results($worktype_sql,ARRAY_A);
		                       $worktype_meta_value =$worktype_results[0]['meta_value']; 
		                       $worktype_iterm_value=unserialize($worktype_meta_value);
		                       for($j=0;$j<count($worktype_iterm_value);$j++){
        		                       $term1 = get_term( $worktype_iterm_value[$j], 'savi_opp_cat_work_type' );
				               $worktype.="<p>".$term1->name."</p>";   
                                       }
                                       $workarea_content ="<div class='popbox' id=workarea_".$post_id.">
                                                         <h2>Work Areas</h2>".$workarea."</div>";
        			       $worktype_content ="<div class='popbox' id=worktype_".$post_id.">    
                                                                     <h2>Work Types</h2>".$worktype."</div>";

                                       $action = "<a href='javascript:void' class='popper'  
                                                    data-popbox=skill_".$post_id.">Skills</a>".$skills_content." | ";
        				$action.= "<a href='javascript:void' class='popper' 
                                                    data-popbox=motivation_".$post_id.">Motivation</a>".$motivation_content;


         			       $action.= " | <a href='javascript:void' class='popper'  
                                                    data-popbox=workarea_".$post_id.">Work Areas</a>".$workarea_content." | ";
        			       $action.= "<a href='javascript:void' class='popper' 
                                                    data-popbox=worktype_".$post_id.">Work Types</a>".$worktype_content;
                     
    
        		        endif;		
                  echo $action; 
				      break;     
			  	default :
			  		  break; 
	  }
  } 
	function savi_views234_manage_columns( $column, $post_id ) {
		global $post,$wpdb;
		//echo "<pre>",print_r($post),"</pre>";
		
		
		
      switch( $column ) {
      	                   case 'savi_views_contact-details_email' :
      	                   $email = get_post_meta( $post_id, 'savi_views_contact-details_email', true );
                     	echo  $email;
				      break;
				case 'savi_views_contact-details_phone-number-in-india' :
						$phone = get_post_meta( $post_id, 'savi_views_contact-details_phone-number-in-india', true );
						if(empty($phone)):
						   $phone = get_post_meta( $post_id, 'savi_views_contact-details_phone-number', true );
						endif;
						echo  $phone;
						break; 
				case 'savi_views_contact-details_nationality' :
						$country = get_post_meta( $post_id, 'savi_views_contact-details_nationality', true );
						if(is_numeric($country)):
						 $country_sql="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 738 AND item_id=$country";
						 $country_results = $wpdb->get_results($country_sql,ARRAY_A);
						  $country = $country_results[0]['meta_value'];		
						endif;
						echo  $country;
						break;
				case 'savi_views_stay-details_planned-arrival' :
						$date_of_arrival = get_post_meta( $post_id, 'savi_views_stay-details_planned-arrival', true );
         			echo  $date_of_arrival;
				      break;	
				      
				case 'savi_views_education-details_intership' :
						$intership = get_post_meta( $post_id, 'savi_views_education-details_intership', true );
         			echo  $intership;
				      break;	
			   case 'savi_views_stay-details_duration' :
						$time_of_stay = get_post_meta( $post_id, 'savi_views_stay-details_duration', true );
         			    echo  $time_of_stay;
				      break;	
				 case 'newaction' :
                                       $Motivation = get_post_meta($post_id,'savi_views_motivations_goals-skills-to-be-gained',true);
        				$Skills = get_post_meta($post_id,'savi_views_skills_fields-of-interest',true);
        				$Motivation = str_replace('&quot;', '"', $Motivation);
                                        $Motivation = htmlspecialchars( $Motivation);
                                        $Motivation = nl2br( $Motivation);
                                        $Skills = str_replace('&quot;', '"', $Skills);
                                        $Skills = htmlspecialchars( $Skills);
                                        $Skills =nl2br( $Skills );
        				$skills_content ="<div class='popbox' id=skill_".$post_id."><h2>Skills</h2>".$Skills."</div>";
        				$motivation_content ="<div class='popbox' id=motivation_".$post_id.">    
                                                                     <h2>Motivation</h2>".$Motivation."</div>";
				
        				$action = "<a href='javascript:void' class='popper'  
                                                    data-popbox=skill_".$post_id.">Skills</a>".$skills_content." | ";
        				$action.= "<a href='javascript:void' class='popper' 
                                                    data-popbox=motivation_".$post_id.">Motivation</a>".$motivation_content;

                                       $workarea_sql = "SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 467 AND item_id
                                                           =(SELECT id FROM wp_frm_items WHERE form_id = 19 AND post_id=$post_id)";
		                       $workarea_results = $wpdb->get_results($workarea_sql,ARRAY_A);
		                       $workarea_meta_value =$workarea_results[0]['meta_value']; 
		                       $workarea_iterm_value=unserialize($workarea_meta_value);
		                       for($i=0;$i<count($workarea_iterm_value);$i++){
        		                       $term = get_term( $workarea_iterm_value[$i], 'savi_opp_cat_work_area' );
				               $workarea.="<p>".$term->name."</p>";   
                                       }
  	                               $worktype_sql="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 468 AND item_id
                                                          =(SELECT id FROM wp_frm_items WHERE form_id = 19 AND post_id=$post_id)";
		                       $worktype_results=$wpdb->get_results($worktype_sql,ARRAY_A);
		                       $worktype_meta_value =$worktype_results[0]['meta_value']; 
		                       $worktype_iterm_value=unserialize($worktype_meta_value);
		                       for($j=0;$j<count($worktype_iterm_value);$j++){
        		                       $term1 = get_term( $worktype_iterm_value[$j], 'savi_opp_cat_work_type' );
				               $worktype.="<p>".$term1->name."</p>";   
                                       }
                                       $workarea_content ="<div class='popbox' id=workarea_".$post_id.">
                                                         <h2>Work Areas</h2>".$workarea."</div>";
        			       $worktype_content ="<div class='popbox' id=worktype_".$post_id.">    
                                                                     <h2>Work Types</h2>".$worktype."</div>";

                                       $action = "<a href='javascript:void' class='popper'  
                                                    data-popbox=skill_".$post_id.">Skills</a>".$skills_content." | ";
        				$action.= "<a href='javascript:void' class='popper' 
                                                    data-popbox=motivation_".$post_id.">Motivation</a>".$motivation_content;


         			       $action.= " | <a href='javascript:void' class='popper'  
                                                    data-popbox=workarea_".$post_id.">Work Areas</a>".$workarea_content." | ";
        			       $action.= "<a href='javascript:void' class='popper' 
                                                    data-popbox=worktype_".$post_id.">Work Types</a>".$worktype_content;
                     
    	
                  echo $action; 
				      break;     	      
           case 'express_opportunities' :
					/* Get the genres for the post. */
					$expressOpportunitiesMeta = get_post_meta( $post_id, 'express_opportunities', false );
					$allexpressOpportunities = $expressOpportunitiesMeta[0];
					$expressOpportunitiesValue = "";
                if (sizeof($allexpressOpportunities) > 0 && is_array($allexpressOpportunities)) {
                    foreach($allexpressOpportunities as $key=>$expressOpportunity) {
                        $expressOpportunitiesID = $expressOpportunity['express_opportunity'];
                        $unitID = (int)get_post_meta($expressOpportunitiesID,'av_unit',true);
                        $volunteer_UnitName = get_the_title( $unitID);
                        $expressOpportunitiesValue.= get_the_title($expressOpportunitiesID)." ( ".$volunteer_UnitName." )<br/>";
                     }
					 $opportunities = ($post->post_type == "view_3")? "Ordered Opportunity":"Preferred Opportunity";
                 $content ="<div class='popbox' id=express_opportunity_".$post_id."><h2>".$opportunities."</h2>". nl2br($expressOpportunitiesValue)."</div>";
        				
        			  $action = "<a href='javascript:void' class='popper' data-popbox=express_opportunity_".$post_id.">".$opportunities."</a>"
                                                                               . nl2br($content);
                 echo $action ; 
               } 
					break;
					
			 	 		
			  default :
			  		break; 	
	  }
  }
  function savi_views5678_manage_columns( $column, $post_id ) {
		global $post,$wpdb;
      switch( $column ) {
                          	 case 'savi_views_contact-details_email' :
                                       echo  $email;
				      break;
				case 'savi_views_contact-details_phone-number-in-india' :
						$phone = get_post_meta( $post_id, 'savi_views_contact-details_phone-number-in-india', true );
						if(empty($phone)):
						   $phone = get_post_meta( $post_id, 'savi_views_contact-details_phone-number', true );
						endif;
						echo  $phone;
						break; 
				case 'savi_views_contact-details_nationality' :
						$country = get_post_meta( $post_id, 'savi_views_contact-details_nationality', true );
						if(is_numeric($country)):
						 $country_sql="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 738 AND item_id=$country";
						 $country_results = $wpdb->get_results($country_sql,ARRAY_A);
						  $country = $country_results[0]['meta_value'];	
						endif;
						echo  $country;
						break;
				case 'savi_views_stay-details_duration' :
						$time_of_stay = get_post_meta( $post_id, 'savi_views_stay-details_duration', true );
         			echo  $time_of_stay;
				      break;		
           case 'volunteer_opportunity' :
					/* Get the genres for the post. */
					$volunteer_opportunityID = get_post_meta( $post_id, 'volunteer_opportunity', true );
					$volunteer_opportunityName = get_the_title( $volunteer_opportunityID);
					$unitID = (int)get_post_meta($volunteer_opportunityID,'av_unit',true);
					$projectID = (int)get_post_meta($volunteer_opportunityID,'projectname',true);
					$volunteer_UnitName = get_the_title( $unitID);
					$volunteer_ProjectName = ($projectID >0)?get_the_title( $projectID):"General"; 
					$content ="<div class='popbox' id=volunteer_opportunity_".$post_id.">
					           <h2>Volunteer Opportunity</h2><p>Opportunity Name :".$volunteer_opportunityName."</p>
					           <p>Unit Name :".$volunteer_UnitName."</p><p>Project Name :".$volunteer_ProjectName."</p></div>";
        				
        			  $action = "<a href='javascript:void' class='popper' data-popbox=volunteer_opportunity_".$post_id.">
        			             Volunteer Opportunity</a>".$content;
					echo  $action; 
                
					break;
				default :
			  		break; 	
	  }
  } 
  function savi_views0_edit_columns( $columns ) {
   
   	 		$columns = array(
					'cb' => '<input type="checkbox" />',
					'title' => __( 'Name' ),
					'savi_views_contact-details_age' => __( 'Age' ),
					'savi_views_stay-details_duration' => __( 'Time of Stay' ),
					'savi_views_contact-details_nationality' => __( 'Country' ),
					'newaction' => __( 'Action' )
		   	);
      
		return $columns;
	 } 
  function savi_views1_edit_columns( $columns ) {
   
   	 	
		        $columns = array(
					'cb' => '<input type="checkbox" />',
					'title' => __( 'Name' ),
					'savi_views_initial-contact_prior-unit-contact' => __( 'Prior Unit'),
					'savi_views_education-details_intership' => __( 'Intership' ),
					'savi_views_contact-details_nationality' => __( 'Country' ),
					'newaction' => __( 'Action' )
				   	);
		        
     
		return $columns;
	 } 
	  function savi_views2_edit_columns( $columns ) {
	  global $post;
	  $opportunities = ($post->post_type == "view_3")? "Ordered Opportunities":"Preferred Opportunities";
      $columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Name' ),
			'savi_views_stay-details_planned-arrival' => __( 'Date of Arrival' ),
			'savi_views_education-details_intership' => __( 'Internship' ),
			'savi_views_stay-details_duration' => __( 'Time of Stay' ),
			'newaction' => __( 'Action' ),
			'express_opportunities' => __( $opportunities )
			
		   );
		return $columns;
	 } 
	  function savi_views34_edit_columns( $columns ) {
	  global $post;
	  $opportunities = ($post->post_type == "view_3")? "Ordered Opportunities":"Express Opportunities";
   	$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Name' ),
			'savi_views_contact-details_email' => __( 'Email' ),
			'savi_views_contact-details_phone-number-in-india' => __( 'Phone' ),
			'savi_views_contact-details_nationality' => __( 'Country' ),
			'savi_views_stay-details_duration' => __( 'Time of Stay' ),
			'express_opportunities' => __( $opportunities )
			
		   );
		return $columns;
	 } 
	 function savi_views5678_edit_columns( $columns ) {
   	$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Name' ),
			'savi_views_contact-details_email' => __( 'Email' ),
			'savi_views_contact-details_phone-number-in-india' => __( 'Phone' ),
			'savi_views_contact-details_nationality' => __( 'Country' ),
			'savi_views_stay-details_duration' => __( 'Time of Stay' ),
			'volunteer_opportunity' => __( 'Volunteer Opportunities' )
			
		   );
		return $columns;
	 } 
 }
?>
