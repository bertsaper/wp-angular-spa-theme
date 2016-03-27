<?php
if (wp_verify_nonce($_GET['preview_nonce'], 'post_preview_' . (int) $_GET['preview_id'])) {
    wp_head();
?>
<body>
<div class="container">
  <div class="row">
    <div class="page-container">
<div class="container bs-docs-container">
  <div class="row">
    <div class="col-md-9" role="main">
<?php
    if (have_posts()):
    // Start the Loop.
        while (have_posts()):
            the_post();
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
    <!-- #content --> 

  <!-- #primary -->
  <?php
    
    global $wp_query;
    $post_id = $wp_query->post->ID;
    
    $post = get_post($post_id);
    $slug = $post->post_name;
    
    //global $post;
    $slug = get_post($post)->post_name;
    
    
    $post = get_post($post_id);
    
    $content = apply_filters('the_content', get_the_content()); // displays the unsaved changes.
    
    echo '<p><span style="font-size:20px;">Please Note:</span> <em>You are previewing 
         the page content outside of its template.<br> 
         This post has not yet been published or updated.</em><br>&nbsp;</p>';
    
    
    echo $content;
?>
<?php
    if (isset($_POST['Cancel'])) {
        
        unset($_POST);
        echo "<script>window.close();</script>";
    }
?>
<form method="POST">
  <button name="Cancel" value="Cancel" type="submit class="btn">Close</button>  
</form>
    <!-- #content --> 

  <!-- #primary -->
      <!-- /.bs-docs-section --> 
    </div>
    <!-- /.col-md-9 --> 
  </div>
  <!-- /.row --> 
</div>
<!-- /.container -->

</body>
</html>
<?php
} else {
?>
<script type="text/javascript">
<!--
window.location = "/"
//-->
</script>
<?php
}
?>
