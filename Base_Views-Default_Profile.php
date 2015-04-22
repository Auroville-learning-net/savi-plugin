<?php
class Default_Profile  {  
  private $unserialize_array = array();     
  private $field_label = array('Contact Details|First Name',
								'Contact Details|Last Name',
								'Contact Details|Gender',
								'Contact Details|Nationality',
								'Contact Details|DOB',
								'Contact Details|Age',
								'Contact Details|Address',
								'Contact Details|Occupation',
								'Contact Details|Phone number',
								'Contact Details|Email',						
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
								'savi_views_contact-details_email',
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
	function generate_unserialize() {
		
		if(!isset($_REQUEST['post'])) return;
		global $wpdb;
		$post_id = $_REQUEST['post'];
		/* ======================Retrive the Work area and work type taxonomy for this post=================== */	 	 
		$sql467="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 467 AND item_id
				=(SELECT id FROM wp_frm_items WHERE form_id = 19 AND post_id=$post_id)";
		$results467=$wpdb->get_results($sql467,ARRAY_A);
		$meta_value467 =$results467[0]['meta_value']; 
		$iterm_value467=unserialize($meta_value467);
		
		if(count($iterm_value467) >1 && is_array($iterm_value467)  ) :
			for($i=0;$i<count($iterm_value467);$i++){
				$term = get_term( $iterm_value467[$i], 'savi_opp_cat_work_area' );
				$table_value467.="<tr><td>".$iterm_value467[$i]."</td><td>".$term->name."</td></tr>";   
			}
		else:
			if($meta_value467 == "savi0"):
				$table_value467.="<tr><td>savi0</td><td>Any Workareas</td></tr>";
			else:
				$term = get_term( $meta_value467, 'savi_opp_cat_work_area' );
				$table_value467.="<tr><td>".$meta_value467."</td><td>".$term->name."</td></tr>";
			endif;	  
		endif;	
		  
		$this->unserialize_array[467]=$table_value467;
		$sql468="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 468 AND item_id
				=(SELECT id FROM wp_frm_items WHERE form_id = 19 AND post_id=$post_id)";
		$results468=$wpdb->get_results($sql468,ARRAY_A);
		$meta_value468 =$results468[0]['meta_value']; 
		$iterm_value468=unserialize($meta_value468);
		if(count($iterm_value468) >1 && is_array($iterm_value468)  ) :
			for($j=0;$j<count($iterm_value468);$j++){
				$term1 = get_term( $iterm_value468[$j], 'savi_opp_cat_work_type' );
				$table_value468.="<tr><td>".$iterm_value468[$j]."</td><td>".$term1->name."</td></tr>";   
			}
		else:
			$term1 = get_term( $meta_value468, 'savi_opp_cat_work_type' );
			$table_value468.="<tr><td>".$meta_value468."</td><td>".$term1->name."</td></tr>";
		endif;
		$this->unserialize_array[468]=$table_value468;
		/* ======================Retrive the Educational details meta values for this post=================== */	

		$sql414="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 414 AND item_id
				=(SELECT id FROM wp_frm_items WHERE form_id = 19 AND post_id=$post_id)";
		$results414=$wpdb->get_results($sql414,ARRAY_A);
		$meta_value414 =$results414[0]['meta_value']; 
		$iterm_value414=unserialize($meta_value414);
		for($i=0;$i<count($iterm_value414);$i++){
			
			$table_value414.="<tr><td>".$iterm_value414[$i][0]."</td>";
			$table_value414.="<td>".$iterm_value414[$i][1]."</td>";
			$table_value414.="<td>".$iterm_value414[$i][2]."</td>";
			$table_value414.="<td>".$iterm_value414[$i][3]."</td>";
			$table_value414.="<td>".$iterm_value414[$i][4]."</td></tr>";  
		}
		$this->unserialize_array[414]=$table_value414;
		/* ======================Retrive the other languages meta values for this post=================== */

		$sql409="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 409 AND item_id
				=(SELECT id FROM wp_frm_items WHERE form_id = 19 AND post_id=$post_id)";
		$results409=$wpdb->get_results($sql409,ARRAY_A);
		$meta_value409 =$results409[0]['meta_value']; 
		$iterm_value409=unserialize($meta_value409);
		for($i=0;$i<count($iterm_value409);$i++){
			$table_value409.="<tr><td>".$iterm_value409[$i][0]."</td>";
			$table_value409.="<td>".$iterm_value409[$i][1]."</td></tr>";   
		}
		$this->unserialize_array[409]=$table_value409;
		/* ======================Retrive the Softwares  meta values for this post=================== */  	

		$sql422="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 422 AND item_id
				=(SELECT id FROM wp_frm_items WHERE form_id = 19 AND post_id=$post_id)";
		$results422=$wpdb->get_results($sql422,ARRAY_A);
		$meta_value422 =$results422[0]['meta_value']; 
		$iterm_value422=unserialize($meta_value422);
		for($i=0;$i<count($iterm_value422);$i++){
			$table_value422.="<tr><td>".$iterm_value422[$i][0]."</td>";
			$table_value422.="<td>".$iterm_value422[$i][1]."</td></tr>";   
		}
		$this->unserialize_array[422]=$table_value422;
		/* ======================Retrive the professional experience meta values for this post=================== */
		$sql424="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 424 AND item_id
				=(SELECT id FROM wp_frm_items WHERE form_id = 19 AND post_id=$post_id)";
		$results424=$wpdb->get_results($sql424,ARRAY_A);
		$meta_value424 =$results424[0]['meta_value']; 
		$iterm_value424=unserialize($meta_value424);
		for($i=0;$i<count($iterm_value424);$i++){
			$table_value424.="<tr><td>".$iterm_value424[$i][0]."</td>";
			$table_value424.="<td>".$iterm_value424[$i][1]."</td>";
			$table_value424.="<td>".$iterm_value424[$i][2]."</td>";
			$table_value424.="<td>".$iterm_value424[$i][3]."</td></tr>";  
		}
		$this->unserialize_array[424]=$table_value424;	
	}
	function savi_add_default_profile_meta_box($post) {
		
		if(!isset($_REQUEST['post'])) return;
		if(!is_admin() && get_post_type()!=$this->custom_type) return;
		global $wpdb;
		$post_id = $_REQUEST['post'];
		$post_meta_array = get_post_meta( $post_id);   
		$show_field=array();
		$old_label="";
		$cur_label="";
		$string="";
		$cnt =count($this->field_label);
		$view_0_tab =array("Contact Details","Stay Details","Motivations","Skills");
		$view_0_metabox_fields = array("Duration (months)",
										"fields of interest",
										"Goals/skills to be gained",
										"First Name",
										"Last Name",
										"Gender",
										"Nationality",
										"DOB",
										"Age",
										"Address",
										"Occupation",
										"Phone number",
										"Email"
									);
		for($i=0;$i<$cnt ;$i++){

			$explode_label = $this->field_label[$i];
			$label="";
			$label= explode("|",$explode_label);
			$curr_label = $label[0];
			$old_label =($old_label==""?$curr_label:$old_label);
			if((get_post_type()=='view_0' && in_array($curr_label,$view_0_tab)) ||(get_post_type()!="view_0")):
				if($i == $cnt-1 ){
					$tmp =$this->field_value[$i];
					$value = $post_meta_array[$tmp][0];
					$all_admin_contents = unserialize($value);
					$admin_content=""; 
					if (sizeof($all_admin_contents) > 0 && is_array($all_admin_contents)) {
						$admin_content="<ul>";
						foreach($all_admin_contents as $key=>$content) {
							$user_id = $content['admin_note_author'];	
							$user_name = get_userdata($user_id )->display_name;
							$date = $content['admin_note_time']; 
							$date = strtotime( $date );
							$format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
							$link = gravatar_url($user_id,48);
							if(empty($link)) {
								$link =plugins_url()."/savi-plugin/images/default.jpg";
							}	
							$date_html = sprintf( '<time datetime="%1$s" title="%1$s">%2$s</time>',
													date( 'c', $date ), date_i18n( $format, $date ) );
							$admin_content.= '<li class="savi-admin-note">';
							$admin_content.= '<div class="savi-admin-note-avatar"><img src="'.$link.'"  with="50" height="50"/></div>';
							$admin_content.= '<div class="savi-admin-note-container">';
							$admin_content.= '<div class="savi-admin-note-meta">';
							$admin_content.= '<span class="savi-admin-note-author">'.$user_name.'</span>';
							$admin_content.='<span class="savi-admin-note-separator">|</span>';
							$admin_content.='<span class="savi-admin-note-date">'.$date_html.'</span>';
							$admin_content.='</div>';
							$admin_content.='<div class="cpm-comment-content">'.$content['admin_note_content'];
							$admin_content.='</div>';               
							$admin_content.='</div>';               
							$admin_content.='</li>';
						} 
						$admin_content.="</ul>";
					}
					$string.="<div class='mainDiv'><div class='LabelDiv syl_title'>".$label[1]."</div>";
					if($curr_label !="Admin Details"):
						$string.="<div class='ValueDiv'>".$value."</div></div>";
					else:
						//echo "<pre>",print_r(unserialize($value)),"</pre>";
						$string.="<div class='ValueDiv syl_content'>".$admin_content."</div>";
						$string.="<div class='ValueDiv'>"."<textarea rows='4' cols='50' name='savi_views_admin_details_admin_notes'
						id='savi_views_admin_details_admin_notes' 
						class='rwmb-textarea large-text'></textarea>"."</div></div>";
					endif;
					$show_field[] = $string;   
					$metaboxID =  preg_replace('/\s+/', '', $curr_label);
					add_meta_box( 'mymetabox'."_".$metaboxID,$curr_label,array($this,'showfields'),$this->custom_type,
																'normal','low', $show_field);
				}       
				else{
					$tmp =$this->field_value[$i];
					$value = $post_meta_array[$tmp][0];
					if((get_post_type()=='view_0' && in_array($label[1],$view_0_metabox_fields)) 
						|| get_post_type()!='view_0'){
						switch($tmp){
							case 'savi_views_contact-details_nationality':

								if(is_numeric($value)):
									$country_sql="SELECT meta_value FROM wp_frm_item_metas WHERE field_id = 738 AND item_id=$value";
									$country_results = $wpdb->get_results($country_sql,ARRAY_A);
									$country = $country_results[0]['meta_value'];		
									$string.="<div class='mainDiv'><div class='LabelDiv'>".$label[1]."</div>";
									$string.="<div class='ValueDiv'>".$country."</div></div>";
								else:
									$string.="<div class='mainDiv'><div class='LabelDiv'>".$label[1]."</div>";
									$string.="<div class='ValueDiv'>".$value."</div></div>";	
								endif;

								break;
							case 'savi_views_skills_work-categories':

								$field_id =467;
								$table_value467=$this->unserialize_array[467];
								$table_head="<table class='frm-table' width='100%'><thead><tr><th>Item ID</th><th>Item Name</th></tr></thead>";
								$table_body="<tbody>". $table_value467."</tbody>";
								$table_footer="</table>";
								$string.="<div class='mainDiv'><div class='LabelDiv'>Work Area</div>";
								$string.="<div class='ValueDiv'>".$table_head.$table_body.$table_footer."</div></div>";

								/* =============== Work type ============================================== */
								$field_id =468;
								$table_value468=$this->unserialize_array[468];
								$table_head_work_type="<table class='frm-table' width='100%'><thead><tr><th>Item ID</th><th>Item Name</th>
														</tr></thead>";
								$table_body_work_type="<tbody>". $table_value468."</tbody>";
								$table_footer_work_type="</table>";
								$string.="<div class='mainDiv'><div class='LabelDiv'>Work Type</div>";
								$string.="<div class='ValueDiv'>".
												$table_head_work_type.$table_body_work_type.$table_footer_work_type."</div></div>";
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
								$table_head="<table class='frm-table' width='100%'><thead><tr><th>Work title</th>
													<th>Achievements</th><th>Dates</th>";
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
					} 
					$explode_nextlabel = $this->field_label[$i+1];
					$nextlabel= explode("|",$explode_nextlabel);
					$next_label = $nextlabel[0];
					if(trim(strtolower($curr_label))!=trim(strtolower($next_label)) ){
						$show_field[] = $string; 
						$metaboxID =  preg_replace('/\s+/', '', $curr_label);
						add_meta_box( 'mymetabox'."_".$metaboxID,$curr_label,array($this,'showfields'),$this->custom_type,
																'normal','low', $show_field);
						$string="";
						unset($show_field);
					}
				}
				$old_label = $curr_label; 
			endif;
		}
	}
	function showfields($post,$metabox){
		global $wpdb;
		echo"<div class='Wrapper'>";
		for($i=0;$i<count($metabox);$i++){
			echo $metabox['args'][$i];
		}
		echo"</div>";
	}
}
?>
