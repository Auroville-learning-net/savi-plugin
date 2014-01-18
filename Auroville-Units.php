<?php

class AV_units extends Listing
{

   public function __construct() {
        
        // Create a new Post Type
        add_action( 'init', array($this, 'create_post_type' ));
        
        // Add the scripts of the Listing plugin (taken from Explorable Themes)
        add_action( 'admin_enqueue_scripts', array($this,'admin_scripts_styles') );
        
        // Call the Units_Metabox when showing the post
        add_action( 'add_meta_boxes', array($this, 'int_unit_admin_metabox' ));  

        // Call the Units_Metabox when showing the post
        add_action( 'add_meta_boxes', array($this, 'init_av_units_metabox' ));

        // Call the Save Post when av_unit is being saved
        add_action( 'save_post', array($this, 'save_postdata' ));

       // Hook into the 'init' action
        add_action( 'init', array($this,'savi_units_categories'), 0 );

    }

    // Create a new Post Type 
    public function create_post_type() {
        register_post_type( 'av_unit',
            array(
                'labels' => array(
                    'name' => __( 'Av Units' ),
                    'singular_name' => __( 'Av Unit' )
                ),
                'supports' => array( 'title', 'editor', 'thumbnail'  ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'av_unit'),
            )
        );
   //  register_taxonomy_for_object_type( 'category', 'posts' );
    }
    
    function savi_units_categories()  {

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
	
	//register_taxonomy( 'savi_unit', 'av_unit', $args_units );
	
    $labels_unit_status = array(
        'name'                       => _x( 'Unit Status', 'Taxonomy General Name', 'text_domain' ),
        'singular_name'              => _x( 'Unit Status', 'Taxonomy Singular Name', 'text_domain' ),
        'menu_name'                  => __( 'Unit Status', 'text_domain' ),
        'all_items'                  => __( 'All Unit Status', 'text_domain' ),
        'parent_item'                => __( 'Parent Unit Status', 'text_domain' ),
        'parent_item_colon'          => __( 'Parent Unit Status:', 'text_domain' ),
        'new_item_name'              => __( 'New Unit Status', 'text_domain' ),
        'add_new_item'               => __( 'Add new Unit Status', 'text_domain' ),
        'edit_item'                  => __( 'Edit Unit Status', 'text_domain' ),
        'update_item'                => __( 'Update Unit Status', 'text_domain' ),
        'separate_items_with_commas' => __( 'Separate Unit Status with commas', 'text_domain' ),
        'search_items'               => __( 'Search Unit Status', 'text_domain' ),
        'add_or_remove_items'        => __( 'Add or remove Unit Status', 'text_domain' ),
        'choose_from_most_used'      => __( 'Choose from the most used Unit Status', 'text_domain' ),
    );
    $args_unit_status = array(
        'labels'                     => $labels_unit_status,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
    );
    
    register_taxonomy( 'savi_unit_cat_unit_status', 'av_unit', $args_unit_status );

}



    // Showing the contents of the Metaboxes for AV_Units
    public function init_av_units_metabox() {
        
         global $meta_boxes;
         $meta_boxes = array();
    
         $meta_boxes[] = array(
                'title' => __( 'Unit Information', 'rwmb' ),
                        // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
                        'pages' => array('av_unit' ),
                'fields' => array(

                     // Name Landphone
                    array(
                        'name' => __( 'Landphone', 'rwmb' ),
                        'id'   => "landphone",
                        'type' => 'text',

                    ),
                    // Unit Url

                    array(
                        'name'  => __( 'Unit Url', 'rwmb' ),
                        'id'    => "unit_url",
                        
                        'type'  => 'url',
                        'std'   => '',
                    ),
                                       
                     // Name Unit holder
                    array(
                        'name' => __( 'Coordinator Name', 'rwmb' ),
                        'id'   => "unit_name",
                        'type' => 'text',

                    ),

                    // Contact Email
                    array(
                        'name'  => __( 'Coordinator mail', 'rwmb' ),
                        'id'    => "contact_email",
                        
                        'type'  => 'email',
                        'std'   => '',
                    ),
                     // Contact Number
                    array(
                        'name' => __( 'Coordinator phone', 'rwmb' ),
                        'id'   => "contact_number",
                        'type' => 'text',

                    ),
                    
                                        
                    // Editor settings, see wp_editor() function: look4wp.com/wp_editor
                'options' => array(
                                'textarea_rows' => 4,
                                'teeny'         => true,
                                'media_buttons' => false,
                            ),
                       
                 'validation' => array(
		                            'rules' => array(
                                                   "contact_email" => array(
				                                                                  'required'  => true,
				                                                                   'minlength' => 7,
			                                                                   ),
		                                       ),
                               ),
                                     
                )
                
            );
                    
        add_meta_box( 'unit_location', 'Unit Location', array($this,'et_listing_settings_meta_box'), 'av_unit', 'advanced', 'high' );
        

        
        global $meta_boxes;
        foreach ( $meta_boxes as $meta_box ) {
            new RW_Meta_Box( $meta_box );
        }
    }    
    
    public function int_unit_admin_metabox() {
                   
        global $meta_boxes;
         $meta_boxes = array();
    
         $meta_boxes[] = array(
                'title' => __( 'Admin', 'rwmb' ),
                        // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
                        'pages' => array('av_unit' ),
                'fields' => array(

                                        
                    // Link with Units - Select Box
                    array(
                        'name'     => __( 'Link to other Units', 'rwmb' ),
                        'id'       => "link_with_units",
                        'type'     => 'select',
                        // Array of 'value' => 'Label' pairs for select box
                        'options'  => array(
                            'No Links' => __( 'No Links', 'rwmb' ),
                            'Affiliated Internal' => __( 'Affiliated Internal', 'rwmb' ),
                            'Affiliated External' => __( 'Affiliated External', 'rwmb' ),
                            
                        ),
                        
                        // Select multiple values, optional. Default is false.
                        'multiple'    => false,
                        'std'         => 'value2',
                        'placeholder' => __( 'Select an Item', 'rwmb' ),
                    ),
                    
                    // Affiliation NoteTEXTAREA
                    array(
                        'name' => __( 'Affiliation Note', 'rwmb' ),
                        'desc' => __( 'Affiliation Note', 'rwmb' ),
                        'id'   => "affiliation_note",
                        'type' => 'textarea',
                        'cols' => 20,
                        'rows' => 3,
                    ),
                    
                    // Affiliation NoteTEXTAREA
                    array(
                        'name' => __( 'Revision', 'rwmb' ),
                        'desc' => __( 'Revision Note', 'rwmb' ),
                        'id'   => "revision_note",
                        'type' => 'textarea',
                        'cols' => 20,
                        'rows' => 3,
                    ),
                     array(
                        'name' => __( 'Excerpt', 'rwmb' ),
                        'desc' => __( 'Excerpt', 'rwmb' ),
                        'id'   => "excerpt",
                        'type' => 'textarea',
                        'cols' => 20,
                        'rows' => 3,
                        // Editor settings, see wp_editor() function: look4wp.com/wp_editor
			'options' => array(
				'textarea_rows' => 4,
				'teeny'         => true,
				'media_buttons' => false,
			),
                    ),
                           
                    // Editor settings, see wp_editor() function: look4wp.com/wp_editor
                'options' => array(
                                'textarea_rows' => 4,
                                'teeny'         => true,
                                'media_buttons' => false,
                            ),
                 
                )
                
            );
       
          global $meta_boxes;
        foreach ( $meta_boxes as $meta_box ) {
            new RW_Meta_Box( $meta_box );
        }
    }
     
  
    
    // Saving the meta data when saving the post
    public function save_postdata( $post_id ) {
      global $post ;
        
        if( $post->post_type != "av_unit" ) return $post_id;

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
        $unit_name = sanitize_text_field( $_POST['unit_name'] );
        $unit_url = sanitize_text_field( $_POST['unit_url'] );
        $contact_email = sanitize_text_field( $_POST['contact_email'] );
        $contact_number = sanitize_text_field( $_POST['contact_number'] );
        $landphone = $_POST['landphone'];
        $link_with_units = $_POST['link_with_units'];
        $affiliation_note = $_POST['affiliation_note'];
        $revision_note = $_POST['revision_note'];
        $excerpt = $_POST['excerpt'];
        // Update the meta field in the database.

        update_post_meta( $post_id, 'unit_name', $unit_name);
        update_post_meta( $post_id, 'unit_url', $unit_url);
        update_post_meta( $post_id, 'contact_email', $contact_email);
        update_post_meta( $post_id, 'contact_number', $contact_number);
        update_post_meta( $post_id, 'landphone', $landphone);
        update_post_meta( $post_id, 'link_with_units', $link_with_units);
        update_post_meta( $post_id, 'affiliation_note', $affiliation_note);
        update_post_meta( $post_id, 'revision_note', $revision_note);
        update_post_meta( $post_id, 'excerpt', $excerpt);
        $this->update_listing_fields($post_id);


     }   

}



?>
