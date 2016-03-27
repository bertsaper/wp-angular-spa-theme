<?php
/*
Template Name: Partial
*/

/*
* Page entries that use this template will trigger updates to these static files
* theme/js/app.js, where the Angular routes will be  written
* theme/js/partial/*.html, where * is the name of the page slug
* theme.navii.html, which is used as an include on index.php to write out the main menu.
* Updated pages must be previewed to update the static files.
*/

?>
<div id="main-content" class="main-content">

<?php echo '$theme_path: ' . $theme_path . ' should be here.'; ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

		<?php
			if ( have_posts() ) :
				// Start the Loop.
				while ( have_posts() ) : the_post();

			//	get_post( $id, $output, $filter ); 
					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
				//	get_template_part( 'content', get_post_format() );

				endwhile;
           endif;
		?>

		</div><!-- #content -->
	</div><!-- #primary -->
	<?php 
    
    global $wp_query;
$post_id = $wp_query->post->ID ;

//$post = get_post( $post_id );
//$slug = $post->post_name;

//global $post;
$slug = get_post( $post )->post_name;


$post = get_post($post_id); 
$content = apply_filters('the_content', $post->post_content); 
echo $content;  

echo $slug;
   // get_template_part( 'featured-content' );
  //  get_template_part( 'content', 'page' );
   // get_sidebar( 'content' ); 
   // echo 'Get get_sidebar Above';?>
</div><!-- #main-content -->

<?php 

//get machine path to the theme so we can create and update static files
$theme_path = get_template_directory();

// get_primary_menu is defined on functions.php. It creates a
//  string that can be manipulated into an array via the steps below.
$content1 = get_primary_menu();

// from http://css-tricks.com/snippets/wordpress/remove-li-elements-from-output-of-wp_nav_menu/
// This will output the raw link with no formatting. 
$content1 = strip_tags(get_primary_menu( $menuParameters ), '<a>' );

//This creates or updates the partials loaded by the spa
$fp_partials = fopen( $theme_path . '/js/partials/' . $slug . '.html' , 'w');
fwrite($fp_partials, $content);
fclose($fp_partials);





if (is_array($menu))
{
   foreach ($menus as $menu)
    {       $slug1    = $menu->slug;
        $id         = $menu->id;
        $title      = $menu->title;

   }
}?>

<h1> <?php echo $slug; ?> </h1>
<h1> <?php echo $title; ?></h1>
<?php 
  $content1 = trim($content1);
//  echo $content1; 

  
  // make an array out of the menu output string
  // site path

  $site_path_to_remove = get_site_url();
  //in case "www" is in menu output  
  $content1 = str_replace('http://www.', 'http://', $content1); 
  
  // start with end of link so we can grab the first closing bracket
  $content1 = str_replace('</a>', '"],', $content1);
  
  // first closing quotation mark and bracket   
  $content1 = str_replace('/">', '","', $content1);
  
  // remove the first part of all links except Home
  $content1 = str_replace('<a href="' . $site_path_to_remove . '/', '["', $content1);
  
  // just in case it's needed, remove the first part of Home link and create a slug called "home"
  $content1 = str_replace('<a href="' . $site_path_to_remove , '["home', $content1);

  // get rid of trailing white space  
  $content1 = rtrim($content1);

  // get rid of last comma   
  $content1 = substr($content1, 0, -1); 
  
  // add enclosing brackets for json format 
  $content1 = '[ ' . $content1 . ' ]';
  
  //WordPress Settings | Permalink Settings must be set for "Post name"
  //and Appearance | Menus must have Primary Menu selected (checked)
  // create array from menu string   
  $content1 = json_decode($content1);
  
  echo chr(10); 
  
  $top_array_elements = count($content1); 
  
  echo chr(10); 
  
  echo "$top_array_elements: " . $top_array_elements; 
  
 
  if ($top_array_elements == 0) echo "<br> <strong>Oops, for the menus to work, WordPress Settings | Permalink Settings must be set for \"Post name\" and Appearance | Menus must have Primary Menu selected (checked)<strong>"; 

  echo chr(10);
  
 // $recursive_array_elements = count($content1, COUNT_RECURSIVE);
  
 // echo $recursive_array_elements;
  
  echo chr(10); 
  
  for($x=0;$x<$top_array_elements;$x++)
  
    {
    
      echo "<br>" . chr(10); 
      
      echo "Slug: " . $content1[$x][0] . ": Title: " . $content1[$x][1]; 
  
      echo "<br>" . chr(10);
  
    }
  
  echo chr(10);




$dollar_sign = "$"; // needed to add dollar sign to string

$fp_app = fopen( $theme_path . '/js/app.js' , 'w');

      fwrite($fp_app, "//'use strict';

angular.module('app', [
  'ngRoute',
  'app.controllers',
  'app.services',
  'app.directives'
]).config(function (" . $dollar_sign . "routeProvider) {" 
. chr(10) 
. chr(10) );
 
  
  for($x=0;$x<$top_array_elements;$x++)
  
    {
    
      fwrite($fp_app,  
  $dollar_sign . "routeProvider
    .when('/" . $content1[$x][0] . "', {
    templateUrl: globals.baseUrl + '/js/partials/" . $content1[$x][0] . ".html',");       
      
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
});

var underscore = angular.module('underscore', []);
underscore.factory('_', function() {
  return window._; // assumes underscore has already been loaded on the page
});"
. chr(10));
    
    
fclose($fp_app);


   
// create the menu using the same array  
$fp_nav = fopen( $theme_path . '/navii.html' , 'w');
fwrite($fp_nav, "<ul>");
 for($x=0;$x<$top_array_elements;$x++)
  
  {
      fwrite($fp_nav, " 
      <li><a href=\"/#/" . $content1[$x][0] . "\">" . $content1[$x][1] . "</a></li>" . chr(10));
  } 

fwrite($fp_nav, "</ul>");
fclose($fp_nav);  

echo chr(10);

  

  echo chr(10);
  var_dump($content1);
  echo chr(10);
  print_r ($content1);
  echo chr(10);
  
?>

