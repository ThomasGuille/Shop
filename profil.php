<?php 
require_once('include/init.php');

if(!userConnected()){
  header('location: index.php');
}

// echo '<pre>'; print_r($_SESSION); echo '</pre>';

require_once('include/header.php');
?>

  <!-- inner page section -->
  <section class="inner_page_head">
    <div class="container_fuild">
      <div class="row">
        <div class="col-md-12">
          <div class="full">
            <h3>Mes informations personnelles</h3>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end inner page section -->
  <!-- why section -->
  <section class="why_section layout_padding">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-lg-2">
          <div class="full">
            <card class="card p-3">
              <div class="user__info d-flex justify-content-between align-items-center">
                <h5>Prénom: </h5>
                <p><?= $_SESSION['user']['firstName']; ?></p>
              </div>
              <div class="user__info d-flex justify-content-between align-items-center">
                <h5>Nom: </h5>
                <p><?= $_SESSION['user']['lastName']; ?></p>
              </div>
              <div class="user__info d-flex justify-content-between align-items-center">
                <h5>Email: </h5>
                <p><?= $_SESSION['user']['email']; ?></p>
              </div>
              <div class="user__info d-flex justify-content-between align-items-center">
                <h5>Ville: </h5>
                <p><?= $_SESSION['user']['city']; ?></p>
              </div>
              <div class="user__info d-flex justify-content-between align-items-center">
                <h5>Code postal: </h5>
                <p><?= $_SESSION['user']['zipcode']; ?></p>
              </div>
              <div class="user__info d-flex justify-content-between align-items-center">
                <h5>Adresse: </h5>
                <p><?= $_SESSION['user']['address']; ?></p>
              </div>

              <?php if(adminConnected()): ?>
                <p>Vous êtes connecté en tant qu'administrateur</p>
              <?php endif; ?>
            </card>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end why section -->
  <!-- arrival section -->
  <!-- end arrival section -->


<?php 
  require_once('include/footer.php');
?>