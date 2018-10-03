<?php
/*Plugin Name: Create Worship Service Post Type
Description: This plugin registers the worship post type.
Version: 1.0.1
License: GPLv2
GitHub Plugin URI: https://github.com/aaronaustin/worship-service
*/

// register custom post type to work with
function create_worship_post_type() {
	// set up labels
	$labels = array(
 		'name' => 'Worship Service',
    	'singular_name' => 'Worship Service',
    	'add_new' => 'Add New Worship Service',
    	'add_new_item' => 'Add New Worship Service',
    	'edit_item' => 'Edit Worship Service',
    	'new_item' => 'New Worship Service',
    	'all_items' => 'All Worship Service',
    	'view_item' => 'View Worship Service',
    	'search_items' => 'Search Worship Service',
    	'not_found' =>  'No Worship Service Found',
    	'not_found_in_trash' => 'No Worship Service found in Trash',
    	'parent_item_colon' => '',
        'menu_name' => 'Worship Service'
    );
    //register post type
	register_post_type( 'worship', array(
		'labels' => $labels,
        'show_in_rest' => true,
		'has_archive' => true,
 		'public' => true,
		'taxonomies' => '',
		'exclude_from_search' => true,
		'capability_type' => 'post',
		'rewrite' => array( 'slug' => 'worship'),
		'rest_base' => 'worship',
        'rest_controller_class' => '',
        'menu_icon' => 'dashicons-admin-multisite',
        'supports' => array( 'title', 'thumbnail','worship_start_datetime' )
		)
	);
}
add_action( 'init', 'create_worship_post_type' );

// Add the custom columns to the worship post type:
add_filter( 'manage_worship_posts_columns', 'set_custom_edit_worship_columns' );
function set_custom_edit_worship_columns($columns) {
    unset( $columns['author'] );
    // unset( $columns['categories'] );
    unset( $columns['comments'] );
    // unset( $columns['tags'] );
    unset( $columns['date'] );
    // $columns['worship_start_datetime'] = __( 'Start Date', 'worship_start_datetime' );
    // $columns['end_date'] = __( 'End', 'your_text_domain' );

    return $columns;
}


add_action( 'wp_ajax_nopriv_worship_elements_search', 'my_worship_elements_search' );
add_action( 'wp_ajax_worship_elements_search', 'my_worship_elements_search' );

function my_worship_elements_search() {
  global $wp_query;
//   $search = $_POST['search_val'];
  $args = array(
    // 's' => $search,
    'posts_per_page' => 10,
    // 'cat' => 12, 
    'post_type' => array( 'post'  )
  );
  $wp_query = new WP_Query( $args );

    echo json_encode($wp_query->posts);

    //set up template to return
//   get_template_part( plugin_dir_path( __FILE__ ) . 'elements-list' );

  exit;
}

//add styles and scripts
function worship_post_type_assets() {
    wp_register_style('worship_post_type_style', plugins_url('style.css',__FILE__ ));
    wp_enqueue_style('worship_post_type_style');
    wp_register_script( 'worship_post_type_script', plugins_url('script.js',__FILE__ ));
    wp_enqueue_script('worship_post_type_script');
    wp_localize_script( 'worship_post_type_script', 'worshipPostTypeScript', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' )
    ));  
}

add_action( 'admin_init','worship_post_type_assets');

include 'element.php';

//Custom Template for the worship
add_filter('single_template', 'worship_template');
function worship_template($template) {
    global $post;
    if ($post->post_type == "worship" && $template !== locate_template(array("worship_template.php"))){
        return plugin_dir_path( __FILE__ ) . "worship_template.php";
    }
    return $template;
}



?>
