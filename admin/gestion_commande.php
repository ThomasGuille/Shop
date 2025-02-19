<?php 
require_once('../include/init.php');

if(!adminConnected()){
  header('location:' . URL . 'index.php');
}

$dataOrder = $dbConnect->query("SELECT order.id_order, user.firstName, user.lastName, user.email, user.address, user.zipcode,  user.city, order.date, order.rising, order.state FROM `order` JOIN user ON order.user_id = user.id_user");
$orders = $dataOrder->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>'; print_r($orders); echo '</pre>';

$data = $dbConnect->query("SELECT order.id_order, product.picture, product.reference, product.title, order_details.quantity, product.price 
  FROM `order` JOIN order_details ON order_details.order_id = order.id_order
  JOIN product ON order_details.product_id = product.id_product
");
$orderDetails = $data->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>'; print_r($orderDetails); echo '</pre>';

$nbOrder = $dbConnect->query("SELECT * FROM `order`")->rowCount();

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
  <!-- <div class="notification is-primary">
    <button class="delete"></button>
    Lorem ipsum, dolor sit amet consectetur adipisicing elit.
  </div> -->
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
                    <td><?= $value ?></td>
                  <?php endforeach; ?>
                  <td class="is-actions-cell">
                    <div class="buttons is-right">
                      <a
                        class="button is-small is-primary"
                        href="?action=details&id=<?= $orderLine['id_order']; ?>">
                        <span class="icon"><i class="mdi mdi-eye"></i></span>
                      </a>
                      <a
                        class="button is-small is-danger jb-modal"
                        data-target="sample-modal-<?= $arrayProduct['id_product']; ?>"
                        type="button">
                        <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                        </a>
                    </div>
                  </td>
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

<?php if (isset($_GET['action']) && $_GET['action'] == 'details'): ?>
  <section class="section is-main-section">
    <div class="card has-table">
      <header class="card-header">
        <p class="card-header-title">
          <span class="icon"><span class="mdi mdi-cart-arrow-down"></span>
          </span>
          Détails commande numéro <?= $_GET['id']; ?>
        </p>
        <a href="#" class="card-header-icon">
          <span class="icon"><i class="mdi mdi-reload"></i></span>
        </a>
      </header>
      <div class="card-content">
        <div class="b-table has-pagination">
          <div class="table-wrapper has-mobile-cards">
            <table
              class="table is-fullwidth is-striped is-hoverable is-fullwidth">
              <thead>
                <tr>
                  <th>Image</th>
                  <th>Référence</th>
                  <th>Titre</th>
                  <th>Quantité</th>
                  <th>Prix unitaire</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($orderDetails as $keyDetails => $details): if($orderDetails[$keyDetails]['id_order'] == $_GET['id']): ?>
                    <tr>
                      <?php foreach($details as $detailsKey => $detailsValue): if($detailsKey != 'id_order'): ?>
                        <?php if($detailsKey == 'picture'): ?>
                          <td><img src="<?= $detailsValue ?>" alt="" class="picture__product"></td>
                        <?php else: ?>
                          <td><?= $detailsValue; ?></td>
                        <?php endif; ?>
                      <?php endif; endforeach; ?>
                    </tr>
                    <?php endif; endforeach; ?>
                
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
<?php endif; ?>

<!-- <section class="section is-main-section">
  <div class="card">
    <header class="card-header">
      <p class="card-header-title">
        <span class="icon"><i class="mdi mdi-ballot"></i></span>
        Modification commande
      </p>
    </header>
    <div class="card-content">
      <form method="get">
        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">From</label>
          </div>
          <div class="field-body">
            <div class="field">
              <p class="control is-expanded has-icons-left">
                <input class="input" type="text" placeholder="Name" />
                <span class="icon is-small is-left"><i class="mdi mdi-account"></i></span>
              </p>
            </div>
            <div class="field">
              <p
                class="control is-expanded has-icons-left has-icons-right">
                <input
                  class="input is-success"
                  type="email"
                  placeholder="Email"
                  value="alex@smith.com" />
                <span class="icon is-small is-left"><i class="mdi mdi-mail"></i></span>
                <span class="icon is-small is-right"><i class="mdi mdi-check"></i></span>
              </p>
            </div>
          </div>
        </div>
        <div class="field is-horizontal">
          <div class="field-label"></div>
          <div class="field-body">
            <div class="field is-expanded">
              <div class="field has-addons">
                <p class="control">
                  <a class="button is-static">+33</a>
                </p>
                <p class="control is-expanded">
                  <input
                    class="input"
                    type="tel"
                    placeholder="Your phone number" />
                </p>
              </div>
              <p class="help">Do not enter the first zero</p>
            </div>
          </div>
        </div>
        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Department</label>
          </div>
          <div class="field-body">
            <div class="field is-narrow">
              <div class="control">
                <div class="select is-fullwidth">
                  <select>
                    <option>Business development</option>
                    <option>Marketing</option>
                    <option>Sales</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Subject</label>
          </div>
          <div class="field-body">
            <div class="field">
              <div class="control">
                <input
                  class="input is-danger"
                  type="text"
                  placeholder="e.g. Partnership opportunity" />
              </div>
              <p class="help is-danger">This field is required</p>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Question</label>
          </div>
          <div class="field-body">
            <div class="field">
              <div class="control">
                <textarea
                  class="textarea"
                  placeholder="Explain how we can help you"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="field is-horizontal">
          <div class="field-label">
            <label class="label">Switch</label>
          </div>
          <div class="field-body">
            <div class="field">
              <label class="switch is-rounded"><input type="checkbox" value="false" />
                <span class="check"></span>
                <span class="control-label">Default</span>
              </label>
            </div>
          </div>
        </div>
        <hr />
        <div class="field is-horizontal">
          <div class="field-label">
            Left empty for spacing
          </div>
          <div class="field-body">
            <div class="field">
              <div class="field is-grouped">
                <div class="control">
                  <button type="submit" class="button is-primary">
                    <span>Submit</span>
                  </button>
                </div>
                <div class="control">
                  <button
                    type="button"
                    class="button is-primary is-outlined">
                    <span>Reset</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</section> -->

<?php require_once('include/footer.php'); ?>