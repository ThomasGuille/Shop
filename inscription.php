<?php 
require_once('include/init.php');

// 1- controler la réception des données du formulaire
echo '<pre>'; print_r($_POST); echo '</pre>';

if(isset($_POST['submit'])){
  $globalError = false;

  if(empty($_POST['firstName'])){$errorFirstName = "Merci de renseigner votre prénom"; $globalError = true;}
  if(empty($_POST['lastName'])){$errorLastName = "Merci de renseigner votre nom"; $globalError = true;}
  if(empty($_POST['address'])){$errorAddress = "Merci de renseigner votre adresse"; $globalError = true;}
  if(empty($_POST['city'])){$errorCity = "Merci de renseigner votre ville"; $globalError++;}
  if(empty($_POST['zipcode'])){$errorZipcode = "Merci de renseigner votre code postal"; $globalError = true;}

  // 2- controler la disponibilité de l'email
  $data = $dbConnect->prepare("SELECT COUNT(email) as nbMail FROM user WHERE email = :email");
  $data->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
  $data->execute();
  $dispoMail = $data->fetch(PDO::FETCH_ASSOC);

  if($dispoMail['nbMail']){$dispoEmail = false; $globalError = true;}else{$dispoEmail = true;}

  // 3- afficher un message d'erreur si le champ email est laissé vide
  if(empty($_POST['email'])){
    $erreurEmail = "Merci de renseigner une adresse email valide";
    $globalError = true;
  }else
  // 4- controler la validité de l'email
  if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    $successEmail = true;
  }else{$erreurEmail = "l'email n'est pas valide"; $globalError = true;}

  // 5- afficher un message d'erreur si le mdp est laissé vide
  if(empty($_POST['password'])){
    $erreurPsw = 'Merci de rentrer un mot de passe';
    $globalError = true;
  }
  if(empty($_POST['passwordConfirm'])){
    $erreurPswRep = "Merci de confirmer votre mot de passe";
    $globalError = true;
  }

  // force du mot de passe (au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial)
  $regPsw = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";
  $pswStrength = preg_match($regPsw, $_POST["password"]);

  // 6- controler que les champs mdp correspondent bien
  if($_POST['password'] === $_POST['passwordConfirm'] && $pswStrength){
    $validPsw = true;
  }else{
    $validPsw = false;
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
                <small class="text-danger"><?php if(isset($errorFirstName)) echo $errorFirstName; ?></small>
                <input
                  type="text"
                  placeholder="Enter votre prénom"
                  name="firstName" value="<?php if(isset($_POST['submit'])) echo $_POST['firstName'] ?>" />

                <label for="lastName">Nom</label>
                <small class="text-danger"><?php if(isset($errorLastName)) echo $errorLastName; ?></small>
                <input
                  type="text"
                  placeholder="Enter votre nom"
                  name="lastName" value="<?php if(isset($_POST['submit'])) echo $_POST['lastName'] ?>" />

                <label for="email">Email</label>
                <small class="text-danger"><?php if(isset($erreurEmail)){
                    echo $erreurEmail;
                  }elseif(isset($dispoEmail) && $dispoEmail == false){
                    echo "Cet email est déjà pris";
                  } ?></small>
                <input
                  type="text"
                  placeholder="Entrez votre adresse e-mail"
                  name="email" value="<?php if(isset($_POST['submit'])) echo $_POST['email'] ?>" />

                <label for="address">Adresse</label>
                <small class="text-danger"><?php if(isset($errorAddress)) echo $errorAddress; ?></small>
                <input
                  type="text"
                  placeholder="Entrer votre adresse"
                  name="address" value="<?php if(isset($_POST['submit'])) echo $_POST['address'] ?>" />

                <label for="city">Ville</label>
                <small class="text-danger"><?php if(isset($errorCity)) echo $errorCity; ?></small>
                <input
                  type="text"
                  placeholder="Entrer votre ville"
                  name="city" value="<?php if(isset($_POST['submit'])) echo $_POST['city'] ?>" />

                <label for="zipcode">Code postal</label>
                <small class="text-danger"><?php if(isset($errorZipcode)) echo $errorZipcode; ?></small>
                <input
                  type="text"
                  placeholder="Entrer votre code postal"
                  name="zipcode" value="<?php if(isset($_POST['submit'])) echo $_POST['zipcode'] ?>" />

                <label for="password">Mot de passe</label>
                <small class="text-danger"><?php if(isset($erreurPsw)){
                    echo $erreurPsw;
                  }elseif(isset($pswStrength) && $pswStrength == false){
                    echo "Le mot de passe doit comporter au moins 8 caractères, une minuscule, une majuscule, un chiffre et un caractère spécial";
                  } ?></small>
                <input
                  type="password"
                  placeholder="Entrer votre mot de passe"
                  name="password" />

                <label for="passwordConfirm">Confirmez votre mot de passe</label>
                <small class="text-danger"><?php if(isset($erreurPswRep)){
                    echo $erreurPswRep;
                  }elseif(isset($validPsw) && $validPsw == false){
                    echo "Les champs ne correspondent pas";} ?></small>
                <input
                  type="password"
                  placeholder="Répétez votre mot de passe"
                  name="passwordConfirm" />

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