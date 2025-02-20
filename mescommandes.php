<?php 
require_once('include/init.php');

if(!userConnected()){
  header('location: index.php');
}


require_once('include/header.php');
?>


<section class="inner_page_head">
    <div class="container_fuild">
      <div class="row">
        <div class="col-md-12">
          <div class="full">
            <h3>Mes commandes</h3>
          </div>
        </div>
      </div>
    </div>
  </section>


<?php require_once('include/footer.php'); ?>