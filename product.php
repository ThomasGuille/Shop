<?php 
require_once('include/init.php');

$data = $dbConnect->query("SELECT * FROM product");
$dataProduct = $data->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>'; print_r($dataProduct); echo '</pre>';

require_once('include/header.php');
?>

  <!-- inner page section -->
  <section class="inner_page_head">
    <div class="container_fuild">
      <div class="row">
        <div class="col-md-12">
          <div class="full">
            <h3>Grille de produits</h3>
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
        <h2>Nos <span>produits</span></h2>
      </div>
      <div class="row">
        <?php foreach($dataProduct as $product): ?>
          <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="box">
              <div class="option_container">
                <div class="options">
                  <a href="fiche_produit.php?id=<?= $product['id_product']; ?>" class="option1"> Voir plus </a>
                  <a href="cart.php" class="option2">Acheter maintenant</a>
                </div>
              </div>
              <div class="img-box">
                <img src="<?= $product['picture']; ?>" alt="" />
              </div>
              <div class="detail-box">
                <h5><?= $product['title']; ?></h5>
                <h6><?= $product['price']; ?>€</h6>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="btn-box">
        <a href=""> Voir tous les produits </a>
      </div>
    </div>
  </section>
  <!-- end product section -->


<?php 
  require_once('include/footer.php');
?>