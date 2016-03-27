  <footer class="bs-docs-footer" role="contentinfo">
  <div class="container centertext"> Copyright &copy; <?php echo date(Y); ?> &middot; All rights reserved. </div>
    <!-- /.container--> 
  </footer>
</div>  <!-- /.page-container-->
<?php wp_footer(); ?>
<script>
 $(function(){
   if ($(window).width() < 768) { 
     var navMain = $("#nav-main");
     navMain.on("click", "a", null, function () {
         navMain.collapse('hide');
     });
   }  
 });
</script>
</body></html>

