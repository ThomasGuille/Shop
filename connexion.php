<?php 
require_once('include/init.php');

// echo '<pre>'; print_r($_POST); echo '</pre>';

if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
  $data = $dbConnect->prepare("SELECT * FROM user WHERE email = :email");
  $data->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
  $data->execute();

  if($data->rowCount()){
    $user = $data->fetch(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($user); echo '</pre>';
    if(password_verify($_POST['password'], $user['password'])){ // password_verify(1, 2) vérifie si 1.le mdp entré dans le formulaire et 2.le mdp présent dans la BDD (donc hashé) sont bien identiques
      foreach($user as $key => $value){
        $_SESSION['user'][$key] = $value; // on enregistre à l'index user dans la session toutes les données de l'utilisateur afin d'y avoir accès sur n'importe quelle page du site, tant qu'elles ne sont pas supprimées
      }
      // echo '<pre>'; print_r($_SESSION); echo '</pre>';
      header('location: index.php');
    }else{
      $error = "<div class='background-danger p-3 mb-3 text-white text-center'>Email ou mot de passe invalide</div>";
    }
  }else{
    $error = "<div class='background-danger p-3 mb-3 text-white text-center'>Email ou mot de passe invalide</div>";
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
            <h3>Identifiez-vous</h3>
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

          <?php if(isset($_SESSION['msgRegisterValid'])) echo $_SESSION['msgRegisterValid']; ?>
          <?php if(isset($error)) echo $error; ?>

          <div class="full">
            <form method="post" action="">
              <fieldset>
                <input
                  type="text"
                  placeholder="Entrez votre adresse e-mail"
                  name="email"
                  class="<?php if(isset($error)) echo 'border-danger'; ?>"
                  value="<?php if(isset($_POST['email'])) echo $_POST['email'] ?>"
                />
                <input
                  type="password"
                  placeholder="Enter votre mot de passe"
                  name="password"
                  class="<?php if(isset($error)) echo 'border-danger'; ?>"
                />
                <input type="submit" name="submit" value="Continuer" />
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
  unset($_SESSION['msgRegisterValid']); // ici on supprime le message de validation du fichier de session pour qu'il ne soit plus affiché aux prochains chargements de la page
?>