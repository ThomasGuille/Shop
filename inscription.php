<?php 
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
            <form action="">
              <fieldset>
                <input
                  type="text"
                  placeholder="Enter votre prénom"
                  name="firstName"
                  required />
                <input
                  type="text"
                  placeholder="Enter votre nom"
                  name="lastName"
                  required />
                <input
                  type="text"
                  placeholder="Entrez votre adresse e-mail"
                  name="email"
                  required />
                <input
                  type="text"
                  placeholder="Entrer votre adresse"
                  name="address"
                  required />
                <input
                  type="text"
                  placeholder="Entrer votre ville"
                  name="city"
                  required />
                <input
                  type="text"
                  placeholder="Entrer votre code postal"
                  name="zipcode"
                  required />
                <input
                  type="password"
                  placeholder="Enter votre mot de passe"
                  name="subject"
                  required />
                <input
                  type="repeat_password"
                  placeholder="Répétez votre mot de passe"
                  name="subject"
                  required />
                <input type="submit" value="Submit" />
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