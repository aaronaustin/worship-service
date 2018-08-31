<?php


// register custom post type to work with
function create_element_post_type() {
	// set up labels
	$labels = array(
 		'name' => 'Worship Element ',
    	'singular_name' => 'Worship Element ',
    	'add_new' => 'Add New Worship Element ',
    	'add_new_item' => 'Add New Worship Element ',
    	'edit_item' => 'Edit Worship Element ',
    	'new_item' => 'New Worship Element ',
    	'all_items' => 'All Worship Element ',
    	'view_item' => 'View Worship Element ',
    	'search_items' => 'Search Worship Element ',
    	'not_found' =>  'No Worship Element  Found',
    	'not_found_in_trash' => 'No Worship Element  found in Trash',
    	'parent_item_colon' => '',
    	'menu_name' => 'Worship Element '
    );
    //register post type
	register_post_type( 'element', array(
		'labels' => $labels,
        'show_in_rest' => true,
		'has_archive' => true,
 		'public' => true,
        'taxonomies' => array('types'),
		'exclude_from_search' => true,
		'capability_type' => 'post',
		'rewrite' => array( 'slug' => 'element'),
		'rest_base' => 'element',
        'rest_controller_class' => '',
        'supports' => array( 'title', 'editor', 'thumbnail','element_start_datetime' ),
        'show_in_menu'  =>	'edit.php?post_type=worship', 
		)
	);
}
add_action( 'init', 'create_element_post_type' );


// Add the custom columns to the element post type:
add_filter( 'manage_element_posts_columns', 'set_custom_edit_element_columns' );
function set_custom_edit_element_columns($columns) {
    unset( $columns['author'] );
    unset( $columns['categories'] );
    unset( $columns['comments'] );
    // unset( $columns['tags'] );
    // unset( $columns['date'] );
    $columns['type'] = __( 'Type', 'type' );
    // $columns['end_date'] = __( 'End', 'your_text_domain' );

    return $columns;
}


// Add the data to the custom columns for the element post type:
    add_action( 'manage_element_posts_custom_column' , 'custom_element_column', 10, 2 );
    function custom_element_column( $column, $post_id ) {
        switch ( $column ) {
            
            case 'type' :
            $type_text = get_post_meta( $post_id, 'type', true );
            echo '<a class="btn-tag" href="edit.php?post_type=element&type='.$type_text.'">'.$type_text.'</a>';
            break;
            
        }
    }
    
    add_filter( 'manage_edit-element_sortable_columns', 'set_custom_element_sortable_columns' );
    function set_custom_element_sortable_columns( $columns ) {
        $columns['type'] = 'type';
    return $columns;
}

add_action( 'pre_get_posts', 'element_custom_orderby' );
function element_custom_orderby( $query ) {
  if ( ! is_admin() )
    return;
  $orderby = $query->get( 'orderby');
  if ( 'type' == $orderby ) {
    $query->set( 'meta_key', 'type');
    $query->set( 'orderby', 'meta_value' );
  }
}

add_action('pre_get_posts', 'my_pre_get_posts');

function my_pre_get_posts( $query ) {
    if( is_admin() ) {
        return;
    }

    $type_format = $query->get('meta_query');
        if( !empty($_GET['type']) ) {
            $type_format[] = array(
                'key'       => 'type',
                'value'     => $_GET['type'],
                'compare'   => 'IN',
            );
        }

    $query->set('meta_query', $type_format);
    return;
}

add_action( 'wp_ajax_nopriv_element_elements_search', 'my_element_elements_search' );
add_action( 'wp_ajax_element_elements_search', 'my_element_elements_search' );



//filter columns
add_filter( 'parse_query', 'prefix_parse_filter' );
function  prefix_parse_filter($query) {
   global $pagenow;
   $current_page = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';

   if ( is_admin() && 
     'element' == $current_page &&
     'edit.php' == $pagenow && 
      isset( $_GET['type'] ) && 
      $_GET['type'] != '') {

    $type_name = $_GET['type'];
    $query->query_vars['meta_key'] = 'type';
    $query->query_vars['meta_value'] = $type_name;
    $query->query_vars['meta_compare'] = '=';
  }
}





//add styles and scripts
function element_post_type_assets() {
    wp_register_style('element_post_type_style', plugins_url('style.css',__FILE__ ));
    wp_enqueue_style('element_post_type_style');
    wp_register_script( 'element_post_type_script', plugins_url('element-script.js',__FILE__ ));
    wp_enqueue_script('element_post_type_script');
    wp_register_script( 'element_hyphen_script', plugins_url('node_modules/hyphen/hyphen.js',__FILE__ ));
    wp_enqueue_script('element_hyphen_script');
    wp_register_script( 'element_hyphen_pattern_script', plugins_url('node_modules/hyphen/patterns/en-us.js',__FILE__ ));
    wp_enqueue_script('element_hyphen_pattern_script');
    wp_localize_script( 'element_post_type_script', 'elementPostTypeScript', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' )
    ));
    
}


add_action( 'admin_init','element_post_type_assets');




?>
