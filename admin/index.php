<?php 
require_once('../include/init.php');

if(!adminConnected()){
  header('location:' . URL . 'index.php');
}

$dataClients = $dbConnect->query("SELECT * FROM user WHERE roles = 'user'");
$nbClients = $dataClients->rowCount();

$data = $dbConnect->query("SELECT rising FROM `order`");
$totalArray = $data->fetchALL(PDO::FETCH_ASSOC);

$totalSales = 0;
foreach($totalArray as $key){
  foreach($key as $value){
    $totalSales += $value;
  }
}

$dataTop = $dbConnect->query("SELECT SUM(quantity) AS nbArticle, product.reference, product.title, product.picture FROM order_details JOIN product WHERE order_details.product_id = product.id_product GROUP BY order_details.product_id ORDER BY nbArticle DESC");
$topArticle = $dataTop->fetch(PDO::FETCH_ASSOC);
// echo '<pre>'; print_r($topArticle); echo '</pre>';

$dataStock = $dbConnect->query("SELECT id_product, reference, title, size, stock FROM product WHERE stock < 10");
$stockLimit = $dataStock->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>'; print_r($stockLimit); echo '</pre>';

require_once('include/header.php');
?>


<section class="section is-title-bar">
  <div class="level">
    <div class="level-left">
      <div class="level-item">
        <ul>
          <li>Admin</li>
          <li>Dashboard</li>
        </ul>
      </div>
    </div>
  </div>
</section>
<section class="hero is-hero-bar">
  <div class="hero-body">
    <div class="level">
      <div class="level-left">
        <div class="level-item">
          <h1 class="title">Dashboard</h1>
        </div>
      </div>
      <div class="level-right" style="display: none">
        <div class="level-item"></div>
      </div>
    </div>
  </div>
</section>
<section class="section is-main-section">
  <div class="tile is-ancestor">
    <div class="tile is-parent">
      <div class="card tile is-child">
        <div class="card-content">
          <div class="level is-mobile">
            <div class="level-item">
              <div class="is-widget-label">
                <h3 class="subtitle is-spaced">Clients</h3>
                <h1 class="title"><?= $nbClients; ?></h1>
              </div>
            </div>
            <div class="level-item has-widget-icon">
              <div class="is-widget-icon">
                <span class="icon has-text-primary is-large"><i class="mdi mdi-account-multiple mdi-48px"></i></span>
              </div>
            </div>
          </div>
        </div>

        <hr>

        <div class="card-content">
          <div class="level is-mobile">
            <div class="level-item">
              <div class="is-widget-label">
                <h3 class="subtitle is-spaced">Total ventes</h3>
                <h1 class="title"><?= $totalSales ?>€</h1>
              </div>
            </div>
            <div class="level-item has-widget-icon">
              <div class="is-widget-icon">
                <span class="icon has-text-info is-large"><i class="mdi mdi-cart-outline mdi-48px"></i></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="tile is-parent">
      <div class="card tile is-child">
        <div class="card-content">
          <div class="level is-mobile">
            <div class="level-item">
              <div class="is-widget-label">
                <h2 class="subtitle is-spaced">Top article</h2>
                <h3 class=""><?= $topArticle['nbArticle']; ?> ventes</h3>
                <h3 class=""><?= $topArticle['reference']; ?></h3>
                <h3 class=""><?= $topArticle['title']; ?></h3>
              </div>
            </div>
            <div class="level-item has-widget-icon">
              <div class="is-widget-icon">
                <img class="dashTop" src="<?= $topArticle['picture']; ?>" alt="<?= $topArticle['title']; ?>">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card has-table has-mobile-sort-spaced">
    <header class="card-header">
      <p class="card-header-title">
        <span class="icon"><i class="mdi mdi-store-alert"></i></span>
        Produits stock insuffisant
      </p>
      <a href="#" class="card-header-icon">
        <span class="icon"><i class="mdi mdi-reload"></i></span>
      </a>
    </header>
    <div class="card-content">
      <div class="b-table has-pagination">
        <div class="table-wrapper has-mobile-cards">
          <table
            class="table is-fullwidth is-striped is-hoverable is-sortable is-fullwidth">
            <thead>
              <tr>
                <th></th>
                <th>Référence</th>
                <th>Nom de l'article</th>
                <th>Taille</th>
                <th>Stock</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($stockLimit as $article): ?>
                <tr>
                  <!-- <td class="is-image-cell">
                    <div class="image">
                      <img
                        src="https://avatars.dicebear.com/v2/initials/rebecca-bauch.svg"
                        class="is-rounded" />
                    </div>
                  </td> -->
                  <td></td>
                  <?php foreach($article as $key => $value): if($key != 'id_product'): ?>
                  <td class="<?php if($article['stock'] == 0) echo 'has-background-danger'; else echo 'has-background-warning'; ?>" name="<?= $key; ?>"><?= $value; ?></td>
                    <!-- <progress
                      max="100"
                      class="progress is-small is-primary"
                      value="79">
                      79
                    </progress> -->
                    <?php endif; endforeach; ?>
                    <td class="is-actions-cell <?php if($article['stock'] == 0) echo 'has-background-danger'; else echo 'has-background-warning'; ?>">
                      <div class="buttons is-right">
                        <a
                          href="gestion_boutique.php?action=update&id=<?= $article['id_product']; ?>"
                          class="button is-small is-primary"
                          type="button">
                          <span class="icon"><i class="mdi mdi-eye"></i></span>
                        </a>
                      </div>
                    </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once('include/footer.php'); ?>