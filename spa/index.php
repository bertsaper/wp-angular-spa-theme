<?php get_header(); ?>
<header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner">
  <div class="container">
    <div class="navbar-header">
      <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
    </div>
  <!-- /.navbar-header-->    
    <nav id="nav-main" class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
      <ul class="nav navbar-nav">
        <?php include get_template_directory() . '/nav_sub_menu.htm';?>
      </ul>
    </nav>
  </div>
  <!-- /.container --> 
</header>
<a id="start_content"></a>
<div class="container bs-docs-container">
  <div class="row">
    <div class="col-md-9 " role="main">
      <div class="bs-docs-section padding-left-side-15">
        <div class="view-container" data-ng-controller="Home">
          <div class="reveal-animation" data-ng-view="" > </div>
        </div>
        <!-- /.view-container --> 
      </div>
      <!-- /.bs-docs-section --> 
    </div>
    <!-- /.col-md-9 --> 
  </div>
  <!-- /.row --> 
</div>
<!-- /.container -->
<?php get_footer(); ?>
