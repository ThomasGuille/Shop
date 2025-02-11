<?php 
require_once('include/init.php');

// 1- controler la réception des données du formulaire
echo '<pre>'; print_r($_POST); echo '</pre>';

if(isset($_POST['submit']) && $_SERVER["REQUEST_METHOD"] === 'POST'){
  $globalError = false;

  if(empty($_POST['firstName'])){$errorFirstName = "<small class='text-danger'>Merci de renseigner votre prénom</small>"; $globalError = true;}
  if(empty($_POST['lastName'])){$errorLastName = "<small class='text-danger'>Merci de renseigner votre nom</small>"; $globalError = true;}
  if(empty($_POST['address'])){$errorAddress = "<small class='text-danger'>Merci de renseigner votre adresse</small>"; $globalError = true;}
  if(empty($_POST['city'])){$errorCity = "<small class='text-danger'>Merci de renseigner votre ville</small>"; $globalError = true;}
  if(empty($_POST['zipcode']) || !is_numeric($_POST['zipcode'])){$errorZipcode = "<small class='text-danger'>Merci de renseigner votre code postal</small>"; $globalError = true;}

  // 2- controler la disponibilité de l'email
  $data = $dbConnect->prepare("SELECT COUNT(*) as nbMail FROM user WHERE email = :email");
  $data->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
  $data->execute();
  $dispoMail = $data->fetch(PDO::FETCH_ASSOC);

  if($dispoMail['nbMail']){$dispoEmail = "<small class='text-danger'>Cet email est déjà utilisé</small>"; $globalError = true;}

  /*
    Autre méthode
    $data = $dbConnect->prepare("SELECT * FROM user WHERE email = :email");
    $data->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $data->execute();

    if($data->rowCount()){
    $dispoEmail = "..."}
  */

  // 3- afficher un message d'erreur si le champ email est laissé vide
  if(empty($_POST['email'])){
    $erreurEmail = "<small class='text-danger'>Merci de renseigner une adresse email</small>";
    $globalError = true;
  }else
  // 4- controler la validité de l'email
  if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    $errValidEmail = "<small class='text-danger'>Merci de renseigner un email valide</small>";
    $globalError = true;
  }

  // 5- afficher un message d'erreur si le mdp est laissé vide
  if(empty($_POST['password'])){
    $erreurPsw = '<small class="text-danger">Merci de rentrer un mot de passe</small>';
    $globalError = true;
  }
  if(empty($_POST['passwordConfirm'])){
    $erreurPswRep = "<small class='text-danger'>Merci de confirmer votre mot de passe</small>";
    $globalError = true;
  }

  // force du mot de passe (au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial)
  $regPsw = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";
  if(!preg_match($regPsw, $_POST["password"])){
    $errorPswStrength = "<small class='text-danger'>Le mot de passe doit comporter au moins 8 caractères, une minuscule, une majuscule, un chiffre et un caractère spécial</small>";
  }

  // 6- controler que les champs mdp correspondent bien
  if($_POST['password'] !== $_POST['passwordConfirm']){
    $validPsw = "<small class='text-danger'>Les champs ne correspondent pas</small>";
    $globalError = true;
  }

  if($globalError == false){
    $data = $dbConnect->prepare("INSERT INTO user(password, firstName, lastName, email, city, zipcode, address) VALUES(:password, :firstName, :lastName, :email, :city, :zipcode, :address)");
    $data->bindValue(':password', $_POST['password'], PDO::PARAM_STR);
    $data->bindValue(':firstName', $_POST['firstName'], PDO::PARAM_STR);
    $data->bindValue(':lastName', $_POST['lastName'], PDO::PARAM_STR);
    $data->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $data->bindValue(':city', $_POST['city'], PDO::PARAM_STR);
    $data->bindValue(':zipcode', $_POST['zipcode'], PDO::PARAM_STR);
    $data->bindValue(':address', $_POST['address'], PDO::PARAM_STR);
    $data->execute();
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
            <h3>Créer votre compte</h3>
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
            <form method="post" action="">
              <fieldset>
                <label for="firstName">Prénom</label>
                <?php if(isset($errorFirstName)) echo $errorFirstName; ?>
                <input
                  type="text"
                  placeholder="Enter votre prénom"
                  name="firstName" value="<?php if(isset($_POST['submit'])) echo $_POST['firstName'] ?>"
                  class="<?php if(isset($errorFirstName)) echo "border-danger" ?>" />

                <label for="lastName">Nom</label>
                <?php if(isset($errorLastName)) echo $errorLastName; ?>
                <input
                  type="text"
                  placeholder="Enter votre nom"
                  name="lastName" value="<?php if(isset($_POST['submit'])) echo $_POST['lastName'] ?>"
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
                  name="email" value="<?php if(isset($_POST['submit'])) echo $_POST['email'] ?>"
                  class="<?php if(isset($erreurEmail) || isset($dispoEmail) || isset($errValidEmail)) echo "border-danger" ?>" />

                <label for="address">Adresse</label>
                <?php if(isset($errorAddress)) echo $errorAddress; ?>
                <input
                  type="text"
                  placeholder="Entrer votre adresse"
                  name="address" value="<?php if(isset($_POST['submit'])) echo $_POST['address'] ?>"
                  class="<?php if(isset($errorAddress)) echo "border-danger" ?>" />

                <label for="city">Ville</label>
                <?php if(isset($errorCity)) echo $errorCity; ?>
                <input
                  type="text"
                  placeholder="Entrer votre ville"
                  name="city" value="<?php if(isset($_POST['submit'])) echo $_POST['city'] ?>"
                  class="<?php if(isset($errorCity)) echo "border-danger"; ?>" />

                <label for="zipcode">Code postal</label>
                <?php if(isset($errorZipcode)) echo $errorZipcode; ?>
                <input
                  type="text"
                  placeholder="Entrer votre code postal"
                  name="zipcode" value="<?php if(isset($_POST['submit'])) echo $_POST['zipcode'] ?>"
                  class="<?php if(isset($errorZipcode)) echo "border-danger"; ?>" />

                <label for="password">Mot de passe</label>
                <?php if(isset($erreurPsw)){
                    echo $erreurPsw;
                  }elseif(isset($errorPswStrength)){
                    echo $errorPswStrength;
                  } ?>
                <input
                  type="password"
                  placeholder="Entrer votre mot de passe"
                  name="password"
                  class="<?php if(isset($erreurPsw) || isset($errorPswStrength)) echo "border-danger"; ?>" />

                <label for="passwordConfirm">Confirmez votre mot de passe</label>
                <?php if(isset($erreurPswRep)){
                    echo $erreurPswRep;
                  }elseif(isset($validPsw)){
                    echo $validPsw;} ?>
                <input
                  type="password"
                  placeholder="Répétez votre mot de passe"
                  name="passwordConfirm"
                  class="<?php if(isset($erreurPswRep) || isset($validPsw)) echo "border-danger"; ?>" />

                <input name="submit" type="submit" value="Submit" />
              </fieldset>
            </form>
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