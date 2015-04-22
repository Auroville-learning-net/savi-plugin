<?php
class Dormant_Volunteers extends Default_Profile {

    public $custom_type = 'view_4';       
    public function __construct() {
        // Create a new Post Type
        add_action( 'init', array($this, 'create_post_type' ));
        // Call the Units_Metabox when showing the post
      
         //drive from Default_Profile class 
        add_action( 'init', array($this, 'generate_unserialize' ));
        //drive from Default_Profile class
        add_action('add_meta_boxes',  array($this,'savi_add_default_profile_meta_box'),10,2); 
        
         add_action('admin_head',array($this,'savi_dor_vol_read_only_title'));
          // Call the Save Post when av_unit is being saved
        add_action( 'save_post', array($this, 'savi_dor_vol_save_data' ));
    }
    // Create a new Post Type 
    public function create_post_type() {
        register_post_type( $this->custom_type,
            array(
                'labels' => array(
                    'name' => __( 'Dormant' ),
                    'singular_name' => __( 'Dormant' )
                ),
                'supports' => array( 'title'),
                'public' => true,
				'menu_icon' => 'dashicons-clock',
                'has_archive' => true,
                'rewrite' => array('slug' => $this->custom_type),
                 'exclude_from_search' => false,
                 'publicly_queryable'=>false
            )
        ); 
        
    }
    public function savi_dor_vol_read_only_title() {

         if(get_post_type()=="view_4"):
?>
      <script type="text/javascript" >
       jQuery(document).ready(function($) {
      	 jQuery("#title").attr("readonly",true);
       }); 	 
       </script>
<?php      
       endif; 
   }
   public function savi_dor_vol_save_data( $post_id ) {
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
    
 }
?>
