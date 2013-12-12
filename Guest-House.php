<?php

class Guest_house extends Listing
{

   public function __construct()
    {

        // Create a new Post Type  
        add_action( 'init', array($this, 'create_post_type' ));
       
       // Add the scripts of the Listing plugin (taken from Explorable Themes)
       add_action( 'admin_enqueue_scripts', array($this,'admin_scripts_styles') );
       
       // Call the Guest house Metabox when showing the post
       add_action( 'add_meta_boxes', array($this, 'init_metabox' ));
      
       // Call the Save Post when av_unit is being saved 
       add_action( 'save_post', array($this, 'save_postdata' ));

      // Hook into the 'init' action
       add_action( 'init', array($this,'savi_categories'), 0 );
    }

    public function create_post_type() {
        register_post_type( 'guest_house',
            array(
                'labels' => array(
                    'name' => __( 'Guest Houses' ),
                    'singular_name' => __( 'Guest House' )
                ),
                'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments','revisions'  ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'guest_houses'),
               // 'supports' => array('title')
            )
        );
    }
   
   function savi_categories()  {

	$labels = array(
		'name'                       => _x( 'Guest House Categories', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Guest House Category', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Guest House Categories', 'text_domain' ),
		'all_items'                  => __( 'All guest house categories', 'text_domain' ),
		'parent_item'                => __( 'Parent guest house category', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent guest house category:', 'text_domain' ),
		'new_item_name'              => __( 'New guest house category', 'text_domain' ),
		'add_new_item'               => __( 'Add new guest house category', 'text_domain' ),
		'edit_item'                  => __( 'Edit guest house category', 'text_domain' ),
		'update_item'                => __( 'Update guest house category', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate guest house categories with commas', 'text_domain' ),
		'search_items'               => __( 'Search guest house categories', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove guest house categories', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used guest house categories', 'text_domain' ),
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
	$labels_units = array(
		'name'                       => _x( 'Unit Categories', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Unit Category', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Unit Categories', 'text_domain' ),
		'all_items'                  => __( 'All Unit Categories', 'text_domain' ),
		'parent_item'                => __( 'Parent Unit category', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Unit category:', 'text_domain' ),
		'new_item_name'              => __( 'New Unit category', 'text_domain' ),
		'add_new_item'               => __( 'Add new Unit category', 'text_domain' ),
		'edit_item'                  => __( 'Edit Unit category', 'text_domain' ),
		'update_item'                => __( 'Update Unit category', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate Unit categories with commas', 'text_domain' ),
		'search_items'               => __( 'Search Unit categories', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove Unit categories', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used Unit categories', 'text_domain' ),
	);
	$args_units = array(
		'labels'                     => $labels_units,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'savi_guest', 'guest_house', $args );
	

}




   // Showing the contents of the Metaboxes for Guest Houses
    public function init_metabox() {
            global $meta_boxes;
            $meta_boxes = array();
             $meta_boxes[] = array(
                'title' => __( 'Advanced Fields', 'rwmb' ),
						// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
						'pages' => array( 'post','guest_house' ),
                'fields' => array(
                    // HEADING
                    array(
                        'type' => 'heading',
                        'name' => __( 'Heading', 'rwmb' ),
                        'id'   => 'fake_id', // Not used but needed for plugin
                    ),
                    
                    // Guest House Website

                    
		            array(
			            'name'  => __( 'Guest House Website', 'rwmb' ),
			            'id'    => "{$prefix}guest_house_website",
			            
			            'type'  => 'url',
			            'std'   => '',
		            ),

                    // Contact Name
                    array(
                        'name' => __( 'Contact Person', 'rwmb' ),
                        'id'   => "{$prefix}contact_name",
                        'type' => 'text',

                        'min'  => 0,
                        'step' => 5,
                    ),
                   // Contact Email
		            array(
			            'name'  => __( 'Contact Email', 'rwmb' ),
			            'id'    => "{$prefix}contact_email",
			            
			            'type'  => 'email',
			            'std'   => '',
		            ),
                     // Contact Number
                    array(
                        'name' => __( 'Contact Number', 'rwmb' ),
                        'id'   => "{$prefix}contact_number",
                        'type' => 'text',

                        'min'  => 0,
                        'step' => 5,
                    ), 
                    // Number of rooms
                    array(
                        'name' => __( 'Number of rooms', 'rwmb' ),
                        'id'   => "{$prefix}no_of_rooms",
                        'type' => 'number',

                        'min'  => 0,
                        'step' => 5,
                    ), 
                   // Archived Notes
                    array(
                        'name' => __( 'Archived Notes', 'rwmb' ),
                        'id'   => "{$prefix}arch_notes",
                        'type' => 'wysiwyg',
                        // Set the 'raw' parameter to TRUE to prevent data being passed through wpautop() on save
                        'raw'  => false,
                        'std'  => __( '', 'rwmb' ),

                        // Editor settings, see wp_editor() function: look4wp.com/wp_editor
                        'options' => array(
                            'textarea_rows' => 4,
                            'teeny'         => true,
                            'media_buttons' => false,
                        ),
                    ),
                     
                    
                   

                )
            );

        global $meta_boxes;
        foreach ( $meta_boxes as $meta_box ) {
            new RW_Meta_Box( $meta_box );
        }
        
        add_meta_box( 'unit_location', 'Unit Location', array($this,'et_listing_settings_meta_box'), 'guest_house', 'advanced', 'high' );
    
    }
    // Saving the meta data when saving the post
    public function save_postdata( $post_id ) {
        global $post ;
        
        if( $post->post_type != "guest_house" ) return $post_id;
        /*
        * We need to verify this came from the our screen and with proper authorization,
        * because save_post can be triggered at other times.
        */
	
        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
           return $post_id;

        // Check the user's permissions.
        if ( 'guest_house' == $_POST['post_type'] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) )
                return $post_id;
        
        } else {

            if ( ! current_user_can( 'edit_post', $post_id ) )
                return $post_id;
        }

        /* OK, its safe for us to save the data now. */

        // Sanitize user input.
        $guest_house_website = sanitize_text_field( $_POST['guest_house_website'] );
        $contact_name = sanitize_text_field( $_POST['contact_name'] );
        $contact_email = sanitize_text_field( $_POST['contact_email'] );
        $contact_number = sanitize_text_field( $_POST['contact_number'] );
        $no_of_rooms = sanitize_text_field( $_POST['no_of_rooms'] );
        $arch_notes = sanitize_text_field( $_POST['arch_notes'] );

	
        // Update the meta field in the database.
        
        update_post_meta( $post_id, 'guest_house_website', $guest_house_website);
        update_post_meta( $post_id, 'contact_name', $contact_name);
        update_post_meta( $post_id, 'contact_email', $contact_email);
        update_post_meta( $post_id, 'contact_number', $contact_number);
        update_post_meta( $post_id, 'no_of_rooms', $no_of_rooms);
        update_post_meta( $post_id, 'arch_notes', $arch_notes);
 
        $this->update_listing_fields($post_id);


     }   


}

?>
