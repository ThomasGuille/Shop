<?php 
require_once('include/init.php');

if(isset($_GET['id'])){
  $data = $dbConnect->prepare("SELECT * FROM product WHERE id_product = :id");
  $data->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
  $data->execute();
  if(!$data->rowCount()){
    header('location: index.php');
  }
  $dataProduct = $data->fetch(PDO::FETCH_ASSOC);
}else{
  header('location: index.php');
}

// echo '<pre>'; print_r($dataProduct); echo '</pre>';

if($dataProduct['stock'] < 10){
  $stock = $dataProduct['stock'];
}else{
  $stock = 10;
}

require_once('include/header.php');
?>

  <!-- inner page section -->
  <section class="inner_page_head">
    <div class="container_fuild">
      <div class="row">
        <div class="col-md-12">
          <div class="full">
            <h3>Fiche produit</h3>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end inner page section -->
  <!-- product section -->
  <section class="product_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>Détails <span>produit</span></h2>
      </div>
      <div class="row mb-3">
        <div class="col-sm-6 col-md-4 col-lg-4">
          <div class="box ">
            <div class="img-box">
              <img src="<?= $dataProduct['picture']; ?>" alt="" />
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-md-8 col-lg-8">
          <div class="detail-box">
            <h5><?= $dataProduct['title']; ?></h5>
            <h6>Prix: <?= $dataProduct['price']; ?>€</h6>
            <h6>Taille <?= $dataProduct['size']; ?></h6>
            <p>Couleur: <?= $dataProduct['color']; ?></p>
            <p><?= $dataProduct['description']; ?></p>
          </div>
        </div>
      </div>
      <div class="options">
        <?php if($dataProduct['stock'] == 0): ?>
          <p class="text__nostock">Cet article n'est plus disponible</p>
          <?php else : ?>
          <?php if($dataProduct['stock'] > 0 && $dataProduct['stock'] <= 5) : ?>
            <p class="text__nostock">Attention il ne reste que <?= $dataProduct['stock']; ?> article(s)</p>
          <?php endif; ?>
          <form class="nbArtForm" action="cart.php" method="post">
            <input type="hidden" name="id_product" value="<?= $dataProduct['id_product'] ?>">
            <div>
              <label class="title__nbArt" for="quantity">Nombre d'articles:</label>
              <select class="selector" name="quantity" id="quantity">
                <?php for($n = 1; $n <= $stock; $n++): ?>
                  <option value="<?= $n ?>"><?= $n ?></option>
                <?php endfor; ?>
              </select>
            </div>
            <input type="submit" name="addCart" class="option2" value="Ajouter au panier">
          </form>
        <?php endif; ?>
      </div>
      <div class="btn-box">
        <a href="product.php"> Tous nos produits </a>
      </div>
    </div>
  </section>
  <!-- end product section -->


<?php 
  require_once('include/footer.php');
?>