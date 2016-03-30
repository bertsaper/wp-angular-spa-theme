<?php
//source files for this template found at
//https://github.com/nicopinto/wp-spa-boilerplate
// compiled with node.js

//remove the generator and wlwmanifest_link tags from the head
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');




class SinglePageApplicationTheme
{
    
    function enqueueScript()
    {
        wp_enqueue_script('jquery-custom');
        wp_enqueue_script('js-globals');
        wp_enqueue_script('ng-app');
        
        // Add the WP_DIRECTORY object directory.path to the script requirejs-config.
        // It allows us to configure the baseUrl of requirejs dynamically.
        //
        // Please refer to http://requirejs.org/docs/api.html#config
        // https://scotch.io/quick-tips/pretty-urls-in-angularjs-removing-the-hashtag
        // http://stackoverflow.com/questions/33694365/angularjs-routing-without-webserver
        $WP_DIRECTORY = array(
            'path' => get_stylesheet_directory_uri()
        );
        wp_localize_script('js-globals', 'directory', $WP_DIRECTORY);
    }
    
    function __construct()
    {
        if (!is_admin()) {
            
            wp_register_style('btstrp', get_stylesheet_directory_uri() . '/css/bootstrap.css');
            wp_enqueue_style('btstrp');
            wp_deregister_script('jquery');
            wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js', array(), '', true);
            wp_enqueue_script('jquery');
            wp_register_script('jqueryBootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js', array(), '', true);
            wp_enqueue_script('jqueryBootstrap');
            
            wp_register_script('ng', get_stylesheet_directory_uri() . '/js/lib/angular.js', array(), '', true);
            wp_register_script('js-globals', get_stylesheet_directory_uri() . '/js/globals.js', array(), '', true);
            wp_register_script('ng-route', get_stylesheet_directory_uri() . '/js/lib/angular-route.js', array(), '', true);
            wp_register_script('mapbox', get_stylesheet_directory_uri() . '/js/lib/mapbox/mapbox.js', array(), '', true);
            wp_register_script('slick', get_stylesheet_directory_uri() . '/js/lib/slick/slick.js', array(), '', true);
            
            wp_register_script('ng-config', get_stylesheet_directory_uri() . '/js/app.js', array(
                'ng',
                'ng-route',
                'mapbox',
                'slick'
            ), '', true);
            
            wp_register_script('ng-ctrl', get_stylesheet_directory_uri() . '/js/controllers/home.js', array(
                'ng-config'
            ), '', true);
            
            wp_register_script('ng-serv', get_stylesheet_directory_uri() . '/js/services/post-service.js', array(
                'ng-ctrl'
            ), '', true);
            
            wp_register_script('ng-dire-1', get_stylesheet_directory_uri() . '/js/directives/slick-base.js', array(
                'ng-serv'
            ), '', true);
            wp_register_script('ng-app', get_stylesheet_directory_uri() . '/js/directives/slick-team.js', array(
                'ng-dire-1'
            ), '', true);
            
            add_action('wp_enqueue_scripts', array(
                $this,
                'enqueueScript'
            ));
        } else {
            add_theme_support('post-thumbnails');
            wp_enqueue_style('global', get_stylesheet_directory_uri() . '/admin-custom.css');
        }
    }
}

// Create a menu in the Primary Menu slot
$singlePageApplicationTheme = new SinglePageApplicationTheme();
add_action('after_setup_theme', 'register_my_menu');
function register_my_menu()
{
    register_nav_menu('primary1', 'Primary Menu');
}

// from https://wordpress.org/support/topic/wp_nav_menu-to-string
// get_primary_menu creates a string that can be manipulated 
// into an array via the steps below.
// Setttings MUST be followed below
// WordPress Settings | Permalink Settings must be set for "Post name"
// and Appearance | Menus must have Primary Menu selected (checked)
// that that is converted into an array via decode_json().
// This array is used to build the angular routes and site menu. 
function get_primary_menu()
{
    $menu = get_transient('sms06202015_primary_menu');
    if (false === $menu) {
        $primary_menu_defaults = array(
            'theme_location' => 'primary1',
            'container' => false,
            'menu_class' => '',
            'container_class' => false,
            'menu_id' => false,
            'items_wrap' => '%3$s',
            'echo' => 0
        );
        $menu = wp_nav_menu($primary_menu_defaults);
        
        // Store the menu in a transient for 3 seconds
        set_transient('sms062020145_primary_menu', $menu, 03);
    }
    return $menu;
}
// Below strips classes and ids from menu output except <ul class="sub-menu">.
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}



// to update the primary menu
add_action('delete_post', 'my_get_menu_items', 1, 1);
add_action('delete_post', 'se_create_sitemap', 1, 1);
add_action('wp_update_nav_menu', 'se_create_sitemap');
add_action('wp_update_nav_menu', 'my_get_menu_items');
function my_get_menu_items($nav_menu_selected_id)
{
    $items = wp_get_nav_menu_items($nav_menu_selected_id);
    
    
    //get machine path to the theme so we can create and update static files
    $theme_path = get_template_directory();
    
    
    $menu_with_sub_menus = get_primary_menu();
    //add #/ for Angular navigation
    $site_path_to_update = get_site_url();
    $menu_with_sub_menus = str_replace($site_path_to_update, $site_path_to_update, $menu_with_sub_menus);
    
    $fp_menu_test = fopen($theme_path . '/nav_sub_menu.htm', 'w');
    fwrite($fp_menu_test, $menu_with_sub_menus);
    fclose($fp_menu_test);
    
    // from http://css-tricks.com/snippets/wordpress/remove-li-elements-from-output-of-wp_nav_menu/
    // This will output the raw link with no formatting. 
    // This is needed to write the routs on app.js
    $output_for_app_routes = strip_tags(get_primary_menu(), '<a>');
    
    
    
    
    $output_for_app_routes = trim($output_for_app_routes);
    
    // make an array out of the menu output string
    // site path
    
    $site_path_to_update   = get_site_url();
    //in case "www" is in menu output  
    $output_for_app_routes = str_replace('http://www.', 'http://', $output_for_app_routes);
    
    // start with end of link so we can grab the first closing bracket
    $output_for_app_routes = str_replace('</a>', '"],', $output_for_app_routes);
    
    // first closing quotation mark and bracket   
    $output_for_app_routes = str_replace('/">', '","', $output_for_app_routes);
    
    
    // remove the first part of all links except Home
    $output_for_app_routes = str_replace('<a href="' . $site_path_to_update . '/', '["', $output_for_app_routes);
    
    // just in case it's needed, remove the first part of Home link and create a slug called "home"
    $output_for_app_routes = str_replace('<a href="' . $site_path_to_update, '["home', $output_for_app_routes);
    
    // get rid of trailing white space  
    $output_for_app_routes = rtrim($output_for_app_routes);
    
    // get rid of last comma   
    $output_for_app_routes = substr($output_for_app_routes, 0, -1);
    
    // add enclosing brackets for json format 
    $output_for_app_routes = '[ ' . $output_for_app_routes . ' ]';
    
    //WordPress Settings | Permalink Settings must be set for "Post name"
    //and Appearance | Menus must have Primary Menu selected (checked)
    // create array from menu string   
    $output_for_app_routes = json_decode($output_for_app_routes);
    
    
    $top_array_elements = count($output_for_app_routes);
    
    
    
    
    if ($top_array_elements == 0)
        echo "<br> <strong>Oops, for the menus to work, WordPress Settings | Permalink Settings must be set for \"Post name\" and Appearance | Menus must have Primary Menu selected (checked)<strong>";
    
    
    
    
    $dollar_sign = "$"; // needed to add dollar sign to string
    
    $fp_app = fopen($theme_path . '/js/app.js', 'w');
    
    fwrite($fp_app, "'use strict';

var app = angular.module('app', [
  'ngRoute',
  'app.controllers',
  'app.services',
  'app.directives'
]).config(function(" . $dollar_sign . "locationProvider, " . $dollar_sign . "routeProvider) {" . chr(10) . chr(10));
    fwrite($fp_app, chr(10) . $dollar_sign . "locationProvider.html5Mode(true);" . chr(10));
    
    for ($x = 0; $x < $top_array_elements; $x++) {
        
        fwrite($fp_app, $dollar_sign . "routeProvider
    .when('/" . $output_for_app_routes[$x][0] . "', {
    templateUrl: globals.baseUrl + '/js/partials/" . $output_for_app_routes[$x][0] . ".html',");
        
        fwrite($fp_app, " 
    controller: 'Home',
    reloadOnSearch: false
  });");
        
    }
    
    fwrite($fp_app, "

" . $dollar_sign . "routeProvider
  .otherwise({
    redirectTo: '/home'
  });
});" . chr(10));
    
    fwrite($fp_app, "app.run(['" . $dollar_sign . "location', '" . $dollar_sign . "rootScope', function(" . $dollar_sign . "location, " . $dollar_sign . "rootScope) {
    " . $dollar_sign . "rootScope." . $dollar_sign . "on('" . $dollar_sign . "routeChangeSuccess', function (event, current, previous) {

        if (current.hasOwnProperty('" . $dollar_sign . $dollar_sign . "$route')) {

            " . $dollar_sign . "rootScope.title = current." . $dollar_sign . $dollar_sign . "route.title;
        }
    });
}]);");
    
}

// Create a custom Dashboard item to help users understand the Angular SPA Setup
function custom_dashboard_widget()
{
    echo "<p>This theme combines the ease and functionality of the WordPress CMS with the practicality of Angular.js code, an open-source 
    framework developed by Google. It was \"Forked\" from https://github.com/nicopinto/wp-spa-boilerplate, compiled and then modified to handle partial includes.</p>
    <p>This theme only allows pages to be created. Posts are not allowed.</p>
    <p>For this theme to work, <strong>WordPress Settings | Permalink Settings</strong> must be set for <strong>\"Post name\"</strong>
    and your menu under Appearance | Menus must have  <strong>Menu Settings | Theme locations | Primary Menu</strong> selected.</p>
    <p>Enter content as you would any WordPress installation. The steps are:</p>
    <ol>
    <li>Go to the Pages area and select or add a page.</li>
     <li>Create or edit your content.<br><em>Please Note: Page titles must be longer than 5 characters.</em></li>   
    <li>Click \"Publish\" or \"Update.\"</li>
    <li>Click \"Preview.\"</li>
    <li>Review your content on the Preview page.</li>
    <li>Add your page to the menu by going to Appearance|Menus.<br><em>Please Note: Sending a page to the \"Trash\" will not delete menu listing. Entering the \"Trash\" area and deleting the post will update the menu.</em></li>       
    </ol> 
    <p>The Preview page will not look like the page that displays for visitors. It will show the text and images that will be formatted for visitors.</p>
    <p>If you are not satisfied with the content, return to the edit form by closing the preview tab or window.</p>";
    
}
function add_custom_dashboard_widget()
{
    wp_add_dashboard_widget('custom_dashboard_widget', 'Angular SPA Instructions', 'custom_dashboard_widget');
}
add_action('wp_dashboard_setup', 'add_custom_dashboard_widget');

// Remove post menu
function remove_admin_links()
{
    remove_menu_page('edit.php'); //Posts
    remove_menu_page('edit-comments.php'); //Comments                             
}
add_action('admin_menu', 'remove_admin_links');

//But we want users to  have access to the Categories and Tags. This returns those links.
function return_categories_to_menu()
{
    add_menu_page('custom menu title', 'Categories', 'manage_options', 'edit-tags.php?taxonomy=category', '', get_template_directory_uri() . '/img/categories.png', 22);
}
add_action('admin_menu', 'return_categories_to_menu');

function return_tags_to_menu()
{
    add_menu_page('custom menu title', 'Tags', 'manage_options', 'edit-tags.php', '', get_template_directory_uri() . '/img/tags.png', 23);
}
add_action('admin_menu', 'return_tags_to_menu');

//Remove some Dashboard items
// Create the function to use in the action hook
function example_remove_dashboard_widget()
{
    remove_meta_box('dashboard_browser_nag', 'dashboard', 'side');
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');
}

// Hook into the 'wp_dashboard_setup' action to register our function
add_action('wp_dashboard_setup', 'example_remove_dashboard_widget');


// Add a reminder that page titles must be longer than 53 characters
function wptutsplus_text_after_title($post_type)
{
    echo "<p style=\"margin-top:4px\"><em>Page titles must be longer than 5 characters.</em></p>";
}
add_action('edit_form_after_title', 'wptutsplus_text_after_title');


// to create a partial for a post
add_action('wp_insert_post', 'partial_create', 10, 3);
function partial_create($pid)
{
    global $wpdb;
    $post       = get_post($pid);
    $slug       = $post->post_name;
    $pattern    = '*autosave-v*';
    $pattern2   = '*revision-v*';
    //get machine path to the theme so we can create and update static files
    $theme_path = get_template_directory();
    
    if (fnmatch($pattern, $slug) === false && fnmatch($pattern2, $slug) === false  ) {
        if (strlen($slug) > 5) {
            $content = apply_filters('the_content', $post->post_content);
            
            //This creates or updates the partials loaded by the spa
            $fp_partials = fopen($theme_path . '/js/partials/' . $slug . '.html', 'w');
            fwrite($fp_partials, $content);
            fclose($fp_partials);
        }
    }
    
}

// to update a partial for a post
add_action('save_post', 'partial_update', 10, 3);


function partial_update($pid)
{
    global $wpdb;
    $post       = get_post($pid);
    $slug       = $post->post_name;
    $pattern    = '*autosave-v*';
    $pattern2   = '*revision-v*';
    //get machine path to the theme so we can create and update static files
    $theme_path = get_template_directory();
    if (fnmatch($pattern, $slug) === false && fnmatch($pattern2, $slug) === false  ) {
        if (strlen($slug) > 5) {
            $content = apply_filters('the_content', $post->post_content);
            
            //This creates or updates the partials loaded by the spa
            $fp_partials = fopen($theme_path . '/js/partials/' . $slug . '.html', 'w');
            fwrite($fp_partials, $content);
            
            
            fclose($fp_partials);
            
        }
        
    }
    
}

// to delete a partial for a deleted post
add_action('delete_post', 'partial_delete', 10);
function partial_delete($pid)
{
    global $wpdb;
    $post    = get_post($pid);
    $slug    = $post->post_name;
    $dirrect = get_template_directory() . '/js/partials/';
    
    if (file_exists($dirrect . $slug . '.html')) {
        unlink($dirrect . $slug . '.html');
    }
    
}


// XML SITEMAP
// We need an XML sitemap that writes out the url

function se_create_sitemap()
{
    $postsForSitemap = get_posts(array(
        'numberposts' => -1,
        'orderby' => 'modified',
        'post_type' => array(
            'post',
            'page'
        ),
        'order' => 'DESC'
    ));
    
    
    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
    
    $sitemap .= "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    
    foreach ($postsForSitemap as $post) {
        setup_postdata($post);
        
        $category = get_the_category($post);
        
        $postdate = explode(" ", $post->post_modified);
        
        $sitemap .= "\t" . '<url>' . "\n" . "\t\t" . '<loc>' . get_permalink($post->ID) . '</loc>' . "\n\t\t" . '<lastmod>' . $postdate[0] . '</lastmod>' . "\n\t\t" . '<changefreq>monthly</changefreq>' . "\n\t" . '</url>' . "\n";
    }
    
    $sitemap .= '</urlset>';
    
    //  $site_path_to_update = get_site_url();
    
    //  $find    = $site_path_to_update;
    //  $replace = $site_path_to_update . '/#';
    
    //  $sitemap = str_replace($find, $replace, $sitemap);
    
    $fp = fopen(ABSPATH . "sitemap.xml", 'w');
    fwrite($fp, $sitemap);
    fclose($fp);
}
add_action("pre_post_update", "se_create_sitemap");

function load_custom_wp_admin_style()
{
    wp_register_style('custom_wp_admin_css', get_template_directory_uri() . '/admin-style.css', false, '1.0.0');
    wp_enqueue_style('custom_wp_admin_css');
}
add_action('admin_enqueue_scripts', 'load_custom_wp_admin_style');
?>
