<?php 
require_once('include/init.php');

if(!userConnected()){
  header('location: index.php');
}

// echo '<pre>'; print_r($_SESSION); echo '</pre>';

$_SESSION['msg'] = false;

if(isset($_POST['submit']) && $_SERVER["REQUEST_METHOD"] === 'POST'){

  if(empty($_POST['firstName'])){$errorFirstName = "<small class='text-danger'>Merci de renseigner votre prénom</small>"; $globalError = true;}
  if(empty($_POST['lastName'])){$errorLastName = "<small class='text-danger'>Merci de renseigner votre nom</small>"; $globalError = true;}
  if(empty($_POST['address'])){$errorAddress = "<small class='text-danger'>Merci de renseigner votre adresse</small>"; $globalError = true;}
  if(empty($_POST['city'])){$errorCity = "<small class='text-danger'>Merci de renseigner votre ville</small>"; $globalError = true;}
  if(empty($_POST['zipcode']) || !is_numeric($_POST['zipcode'])){$errorZipcode = "<small class='text-danger'>Merci de renseigner votre code postal</small>"; $globalError = true;}

  $data = $dbConnect->prepare("SELECT * FROM user WHERE email = :email");
  $data->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
  $data->execute();
  $dispoMail = $data->fetch(PDO::FETCH_ASSOC);

  if($data->rowCount() && $_POST['email'] != $dispoMail['email']){$dispoEmail = "<small class='text-danger'>Cet email est déjà utilisé</small>"; $globalError = true;}

  if(empty($_POST['email'])){
    $erreurEmail = "<small class='text-danger'>Merci de renseigner une adresse email</small>";
    $globalError = true;
  }else
  if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    $errValidEmail = "<small class='text-danger'>Merci de renseigner un email valide</small>";
    $globalError = true;
  }

  if(!isset($globalError)){

    $data = $dbConnect->prepare("UPDATE user SET firstName = :firstName, lastName = :lastName, email = :email, city = :city, zipcode = :zipcode, address = :address WHERE id_user = :id");
    $data->bindValue(':firstName', $_POST['firstName'], PDO::PARAM_STR);
    $data->bindValue(':lastName', $_POST['lastName'], PDO::PARAM_STR);
    $data->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $data->bindValue(':city', $_POST['city'], PDO::PARAM_STR);
    $data->bindValue(':zipcode', $_POST['zipcode'], PDO::PARAM_INT);
    $data->bindValue(':address', $_POST['address'], PDO::PARAM_STR);
    $data->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $data->execute();

    $dataUser = $dbConnect->prepare("SELECT * FROM user WHERE id_user = :id_user");
    $dataUser->bindValue(':id_user', $_GET['id'], PDO::PARAM_INT);
    $dataUser->execute();
    $user = $dataUser->fetch(PDO::FETCH_ASSOC);

    foreach($user as $key => $value){
      $_SESSION['user'][$key] = $value;
    }

    $_SESSION['msgValid'] = '<div class="bg-success p-3 text-white text-center mb-3">Votre compte a été modifié avec succès</div>';
    $_SESSION['msg'] = true;
    header('location: profil.php');
  }
}

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
    <?php if(isset($_SESSION['msgValid'])) echo $_SESSION['msgValid']; ?>
    <div class="container">
      <?php if(isset($_GET['action']) && $_GET['action'] == 'update'): ?>
        <div class="row">
          <div class="col-lg-8 offset-lg-2">
            <div class="full">
              <form method="post" action="">
                <fieldset>
                  <label for="firstName">Prénom</label>
                  <?php if(isset($errorFirstName)) echo $errorFirstName; ?>
                  <input
                    type="text"
                    placeholder="Enter votre prénom"
                    name="firstName" value="<?= $_SESSION['user']['firstName']; ?>"
                    class="<?php if(isset($errorFirstName)) echo "border-danger" ?>" />

                  <label for="lastName">Nom</label>
                  <?php if(isset($errorLastName)) echo $errorLastName; ?>
                  <input
                    type="text"
                    placeholder="Enter votre nom"
                    name="lastName" value="<?= $_SESSION['user']['lastName']; ?>"
                    class="<?php if(isset($errorLastName)) echo "border-danger" ?>" />

                  <label for="email">Email</label>
                  <?php if(isset($erreurEmail)){
                      echo $erreurEmail;
                    }elseif(isset($dispoEmail)){
                      echo $dispoEmail;
                    }elseif(isset($errValidEmail)) echo $errValidEmail ?>
                  <input
                    type="text"
                    placeholder="Entrez votre adresse e-mail"
                    name="email" value="<?= $_SESSION['user']['email']; ?>"
                    class="<?php if(isset($erreurEmail) || isset($dispoEmail) || isset($errValidEmail)) echo "border-danger" ?>" />

                  <label for="address">Adresse</label>
                  <?php if(isset($errorAddress)) echo $errorAddress; ?>
                  <input
                    type="text"
                    placeholder="Entrer votre adresse"
                    name="address" value="<?= $_SESSION['user']['address']; ?>"
                    class="<?php if(isset($errorAddress)) echo "border-danger" ?>" />

                  <label for="city">Ville</label>
                  <?php if(isset($errorCity)) echo $errorCity; ?>
                  <input
                    type="text"
                    placeholder="Entrer votre ville"
                    name="city" value="<?= $_SESSION['user']['city']; ?>"
                    class="<?php if(isset($errorCity)) echo "border-danger"; ?>" />

                  <label for="zipcode">Code postal</label>
                  <?php if(isset($errorZipcode)) echo $errorZipcode; ?>
                  <input
                    type="text"
                    placeholder="Entrer votre code postal"
                    name="zipcode" value="<?= $_SESSION['user']['zipcode']; ?>"
                    class="<?php if(isset($errorZipcode)) echo "border-danger"; ?>" />

                  <input name="submit" type="submit" value="Submit" />
                </fieldset>
              </form>
            </div>
          </div>
        </div>
      <?php else: ?>
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
          <div class="btn-box">
            <a href="?action=update&id=<?= $_SESSION['user']['id_user']; ?>"> Modifiez votre profil </a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>
  <!-- end why section -->
  <!-- arrival section -->
  <!-- end arrival section -->


<?php 
  require_once('include/footer.php');
  if($_SESSION['msg'] == false) unset($_SESSION['msgValid']);
?>