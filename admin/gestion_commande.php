<?php 
require_once('../include/init.php');

if(!adminConnected()){
  header('location:' . URL . 'index.php');
}

$_SESSION['msg'] = false;

$dataOrder = $dbConnect->query("SELECT order.id_order, user.firstName, user.lastName, user.email, user.address, user.zipcode,  user.city, order.date, order.rising, order.state FROM `order` JOIN user ON order.user_id = user.id_user ORDER BY order.id_order DESC");
$orders = $dataOrder->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>'; print_r($orders); echo '</pre>';

$data = $dbConnect->query("SELECT order.id_order, product.picture, product.reference, product.title, order_details.quantity, product.price 
  FROM `order` JOIN order_details ON order_details.order_id = order.id_order
  JOIN product ON order_details.product_id = product.id_product
");
$orderDetails = $data->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>'; print_r($orderDetails); echo '</pre>';

$nbOrder = $dbConnect->query("SELECT * FROM `order`")->rowCount();

if(isset($_POST['submit'])){
  // echo '<pre>'; print_r($_POST); echo '</pre>';
  $stateUpdate = $dbConnect->prepare("UPDATE `order` SET state = :state WHERE id_order = :id_order");
  $stateUpdate->bindValue(':state', $_POST['state'], PDO::PARAM_STR);
  $stateUpdate->bindValue(':id_order', $_POST['id_order'], PDO::PARAM_INT);
  $stateUpdate->execute();
  $_SESSION['msgValid'] = "L'état de la commande a bien été changé";
  $_SESSION['msg'] = true;
  header('location: gestion_commande.php');
}

require_once('include/header.php');
?>


<section class="section is-title-bar">
  <div class="level">
    <div class="level-left">
      <div class="level-item">
        <ul>
          <li>Admin</li>
          <li>Commandes</li>
        </ul>
      </div>
    </div>
    <!-- <div class="level-right">
        <div class="level-item">
          <div class="buttons is-right">
            <a
              href="https://github.com/vikdiesel/admin-one-bulma-dashboard"
              target="_blank"
              class="button is-primary"
            >
              <span class="icon"
                ><i class="mdi mdi-github-circle"></i
              ></span>
              <span>GitHub</span>
            </a>
          </div>
        </div>
      </div> -->
  </div>
</section>
<section class="section is-main-section">
  <?php if(isset($_SESSION['msgValid'])): ?>
    <div class="notification is-primary">
      <button class="delete"></button>
      <?= $_SESSION['msgValid']; ?>
    </div>
  <?php endif; ?>
  <div class="card has-table">
    <header class="card-header">
      <p class="card-header-title">
        <span class="icon"><span class="mdi mdi-cart-outline"></span>
        </span>
        <?php if($nbOrder <= 1): ?>
          <?= $nbOrder; ?> commande
        <?php else : ?>
          <?= $nbOrder; ?> commandes
        <?php endif; ?>
      </p>
    
    </header>
    <div class="card-content">
      <div class="b-table has-pagination">
        <div class="table-wrapper has-mobile-cards">
          <table
            class="table is-fullwidth is-striped is-hoverable is-fullwidth">
            <thead>
              <tr>
                <th class="is-checkbox-cell">
                  <label class="b-checkbox checkbox">
                    <input type="checkbox" value="false" />
                    <span class="check"></span>
                  </label>
                </th>
                <th>Numéro</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Adresse</th>
                <th>Code postal</th>
                <th>Ville</th>
                <th>Date de la commande</th>
                <th>Montant total</th>
                <th>Etat</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($orders as $key => $orderLine): ?>
                <tr>
                  <td>
                    <label class="b-checkbox checkbox">
                      <input type="checkbox" value="false" />
                      <span class="check"></span>
                    </label>
                  </td>
                  <?php foreach($orderLine as $key => $value): ?>
                    <?php if($key == 'rising'): ?>
                      <td><?= $value ?>€</td>
                    <?php elseif($key == 'state'): ?>
                      <td>
                        <form action="" method="post">
                          <input type="hidden" name="id_order" value="<?= $orderLine['id_order']; ?>">
                          <select name="state" id="">
                            <option <?php if($value == 'treatment') echo 'selected'; ?> value="treatment">En cours de traitement</option>
                            <option <?php if($value == 'sent') echo 'selected'; ?> value="sent">Envoyée</option>
                            <option <?php if($value == 'delivered') echo 'selected'; ?> value="delivered">Livrée</option>
                          </select>
                          <button class="" type="submit" name="submit">OK</button>
                        </form>
                      </td>
                    <?php else: ?>
                      <td><?= $value; ?></td>
                  <?php endif; endforeach; ?>
                  <td class="is-actions-cell">
                    <div class="buttons is-right">
                      <a
                        class="button is-small is-primary"
                      <?php if(!isset($_GET['action']))
                        echo "href='?action=details&id=$orderLine[id_order]'";
                      else
                        echo "href='gestion_commande.php'";
                      ?>>
                        <span class="icon"><i class="mdi mdi-eye"></i></span>
                      </a>
                    </div>
                  </td>
                  <?php if (isset($_GET['action']) && $_GET['action'] == 'details' && $orderLine['id_order'] == $_GET['id']): ?>
                    <!-- <section class="section is-main-section"> -->
                      <!-- <div class="card has-table"> -->
                        <div class="card-content">
                          <div class="b-table has-pagination">
                            <div class="table-wrapper has-mobile-cards">
                                  <tr>
                                    <td></td>
                                    <th>Détails</th>
                                    <td></td>
                                    <th>Image</th>
                                    <th>Référence</th>
                                    <th>Titre</th>
                                    <th>Quantité</th>
                                    <th>Prix unitaire</th>
                                  </tr>
                                  <?php foreach($orderDetails as $keyDetails => $details): if($orderDetails[$keyDetails]['id_order'] == $_GET['id']): ?>
                                    <tr>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <?php foreach($details as $detailsKey => $detailsValue): if($detailsKey != 'id_order'): ?>
                                        <?php if($detailsKey == 'picture'): ?>
                                          <td><img src="<?= $detailsValue ?>" alt="" class="picture__product"></td>
                                        <?php elseif($detailsKey == 'price'): ?>
                                          <td><?= $detailsValue; ?>€</td>
                                        <?php else: ?>
                                          <td><?= $detailsValue ?></td>
                                        <?php endif; ?>
                                      <?php endif; endforeach; ?>
                                    </tr>
                                  <?php endif; endforeach; ?>
                            </div>
                          </div>
                        </div>
                      <!-- </div> -->
                    <!-- </section> -->
                  <?php endif; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <!-- <div class="notification">
            <div class="level">
              <div class="level-left">
                <div class="level-item">
                  <div class="buttons has-addons">
                    <button type="button" class="button is-active">
                      1
                    </button>
                    <button type="button" class="button">2</button>
                    <button type="button" class="button">3</button>
                  </div>
                </div>
              </div>
              <div class="level-right">
                <div class="level-item">
                  <small>Page 1 of 3</small>
                </div>
              </div>
            </div>
          </div> -->
      </div>
    </div>
  </div>
</section>


<?php 
require_once('include/footer.php'); 
if($_SESSION['msg'] == false) unset($_SESSION['msgValid']); 
?>