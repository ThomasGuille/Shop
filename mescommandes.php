<?php 
require_once('include/init.php');

if(!userConnected()){
  header('location: index.php');
}


require_once('include/header.php');
?>





<?php require_once('include/footer.php'); ?>