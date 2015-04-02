<?php
class Enquiry_Review extends Default_Profile {
   public $custom_type = 'view_0';       
   public function __construct() {
        // Create a new Post Type
        add_action( 'init', array($this, 'create_post_type' ));
        // Call the Units_Metabox when showing the post
      // Call the Save Post when av_unit is being saved
        add_action( 'save_post', array($this, 'savi_enq_rev_save_data' ));
        // Call the custom filter, filtering the posts by country    
        
          add_action('admin_head',array($this,'savi_enq_rev_read_only_title'));
        
         //drive from Default_Profile class 
        add_action( 'init', array($this, 'generate_unserialize' ));
        //drive from Default_Profile class
        add_action('add_meta_boxes',  array($this,'savi_add_default_profile_meta_box'),10,2); 
        add_action('add_meta_boxes',  array($this,'savi_enq_rev_meta_box'),10,2); 
        
   }
   // Create a new Post Type 
   public function create_post_type() {
        register_post_type( $this->custom_type,
            array(
                'labels' => array(
                    'name' => __( 'Initial Enquiry' ),
                    'singular_name' => __( 'Initial Enquiry' )
                ),
                'supports' => array( 'title'),
                'public' => true,
				'menu_icon' => 'dashicons-info',
                'has_archive' => true,
                'rewrite' => array('slug' => $this->custom_type),
            )
        );
       
  }
   public function savi_enq_rev_meta_box(){
  		remove_meta_box( 'submitdiv', $this->custom_type, 'side' );
  }
  
  public function savi_enq_rev_read_only_title() {

     
      
   if(get_post_type()=="view_0"):
?>
       <script type="text/javascript" >
       jQuery(document).ready(function($) {
      	 jQuery("#title").attr("readonly",true);
       }); 	 
       </script>
<?php      
    endif;         
   }
   public function savi_enq_rev_init_metabox() {
     add_meta_box( 'enquiry_review', "Profile information", array($this,'savi_enq_rev_show_metabox')
                                                               , $this->custom_type, 'normal', 'low'); 
   }    
   // Saving the meta data when saving the post
   public function savi_enq_rev_save_data( $post_id ) {
      global $post ;
      if( $post->post_type != $this->custom_type ) return $post_id;
		 /*
        * We need to verify this came from the our screen and with proper authorization,
        * because save_post can be triggered at other times.add_action( 'manage_view_0_custom_column', 'my_manage_view0_columns', 10, 2 );
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
        $Name = sanitize_text_field( $_POST['post_title'] );
        $Email = sanitize_text_field( $_POST['email'] );
        $Country = sanitize_text_field( $_POST['country'] );
        $Phone = sanitize_text_field( $_POST['phone'] );
        $Time_of_stay = sanitize_text_field($_POST['time_of_stay']);
        $Motivation = sanitize_text_field($_POST['motivation']);
        $Skills = sanitize_text_field($_POST['skills']);
        $skills_content ="<div class='popbox' id=skill_".$post_id."><h2>Skills</h2>".$Skills."</div>";
        $motivation_content ="<div class='popbox' id=motivation_".$post_id."><h2>Motivation</h2>".$Motivation."</div>";
        $action = "<a href='javascript:void' class='popper' data-popbox=skill_".$post_id.">Skills</a>"
                                                                               .$skills_content." | ";
        $action.= "<a href='javascript:void' class='popper' data-popbox=motivation_".$post_id.">Motivation</a>"
                                                                                    .$motivation_content;         
        update_post_meta($post_id,'newaction',$action); 
        update_post_meta($post_id,'name',$Name);
        update_post_meta($post_id,'email',$Email);
        update_post_meta($post_id,'country',$Country);
        update_post_meta($post_id,'phone',$Phone);
        update_post_meta($post_id,'time_of_stay',$Time_of_stay);
        update_post_meta($post_id,'motivation',$Motivation);
        update_post_meta($post_id,'skills',$Skills);
   }   
}
?>