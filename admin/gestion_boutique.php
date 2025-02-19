<?php 
require_once('../include/init.php');

if(!adminConnected()){
  header('location:' . URL . 'index.php');
}

// echo '<pre>'; print_r($_SERVER['PHP_SELF']); echo '</pre>';

$_SESSION['msg'] = false;

// Requête de suppression
if(isset($_GET['action']) && $_GET['action'] == 'delete'){
  $dataDelete = $dbConnect->prepare("DELETE FROM product WHERE id_product = :id");
  $dataDelete->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
  $dataDelete->execute();
  $_SESSION['msgValid'] = "Le produit a bien été supprimé de la base de données";
  $_SESSION['msg'] = true;
  header('location: gestion_boutique.php');
}

if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
  // echo '<pre>'; print_r($_FILES); echo '</pre>';
  // echo '<pre>'; print_r($_POST); echo '</pre>';

  $pictureUrlDb = null;
  if(isset($_GET['action']) && $_GET['action'] == 'update'){
    $pictureUrlDb = $_POST['current_picture'];
  }

  // $_FILES est une super-globale permettant de stocker les données d'un fichier uploadé (nom, taille, format,...)
  if(!empty($_FILES['picture']['name'])){

    // Controle de l'extension du fichier
    $currentExtension = ['jpg','jpeg', 'png', 'webp'];
    $fileUploaded = new SplFileInfo($_FILES['picture']['name']);
    // SplFileInfo est une classe prédéfinie en PHP permettant de traiter les données d'un fichier uploadé. Elle contient ses propres méthodes
    // echo '<pre>'; print_r($fileUploaded); echo '</pre>';
    // echo '<pre>'; print_r(get_class_methods($fileUploaded)); echo '</pre>';
    $fileUploadedExtension = $fileUploaded->getExtension();
    // echo $fileUploadedExtension;

    // array_search() permet de comparer 1 valeur à un array et renvoie la position de la correspondance, si elle existe, sinon false
    $positionExtension = array_search($fileUploadedExtension, $currentExtension);
    // echo $positionExtension;

    if($positionExtension === false){
      $errorPicture = "<span class='has-text-danger'>extension non prise en charge (il faut jpg, jpeg, png ou webp)</span>";
    }else{
      $pictureName = $_POST['reference'] . '-' . $_FILES['picture']['name'];
      // echo $pictureName . '<br>';
  
      // On définit l'url de l'image qui sera stockée en BDD
      // http://localhost/PHP/Boutique/Shop/assets/images-produits/RB152-p4.png
      $pictureUrlDb = URL . "assets/images-produits/$pictureName";
      // echo $pictureUrlDb . '<br>';
  
      // On définit le chemin physique sur les serveur où sera copiée l'image
      // C:/xampp/htdocs/PHP/Boutique/Shop/assets/images-produits/RB152-p4.png 
      $pictureFolder = RACINE_SITE . "assets/images-produits/$pictureName";
      // echo $pictureFolder;
  
      /*
       La fonction prédéfinie copy() permet de copier un fichier dans un dossier.
       On lui envoie 2 arguments:
        1. le nom temporaire de l'image (source de l'image) accessible dans $_FILES
        2. le chemin physique du dossier dans lequel copier l'image sur le serveur
      */
      copy($_FILES['picture']['tmp_name'], $pictureFolder);
    }
  }

  // requête de modification
  if(isset($_GET['action']) && $_GET['action'] == 'update'){
    $data = $dbConnect->prepare("UPDATE product SET reference = :reference, category = :category, title = :title, description = :description, color = :color, size = :size, public = :public, picture = :picture, price = :price, stock = :stock WHERE id_product = :id");
    $data->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

    $_SESSION['msgValid'] = "Le produit a bien été modifié dans la base de données";
  }else{
    // Requête SQL d'insertion
    $data = $dbConnect->prepare("INSERT INTO product (reference, category, title, description, color, size, public, picture, price, stock) VALUES (:reference, :category, :title, :description, :color, :size, :public, :picture, :price, :stock)");
    
    $_SESSION['msgValid'] = "Le produit a bien été enregistré dans la base de données";
  }
  
  $data->bindValue(':reference', $_POST['reference'], PDO::PARAM_STR);
  $data->bindValue(':category', $_POST['category'], PDO::PARAM_STR);
  $data->bindValue(':title', $_POST['title'], PDO::PARAM_STR);
  $data->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
  $data->bindValue(':color', $_POST['color'], PDO::PARAM_STR);
  $data->bindValue(':size', $_POST['size'], PDO::PARAM_STR);
  $data->bindValue(':public', $_POST['public'], PDO::PARAM_STR);
  $data->bindValue(':picture', $pictureUrlDb, PDO::PARAM_STR);
  $data->bindValue(':price', $_POST['price']);
  $data->bindValue(':stock', $_POST['stock'], PDO::PARAM_INT);
  $data->execute();
  $_SESSION['msg'] = true;
  
  // header('location: gestion_boutique.php');
}

$data = $dbConnect->query("SELECT * FROM product");
$products = $data->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>'; print_r($products); echo '</pre>';

$nbProduct = $data->rowCount();
if($nbProduct <= 1){
  $txt = "$nbProduct produit";
}else{
  $txt = "$nbProduct produits";
}

if(isset($_GET['action']) && $_GET['action'] == 'update'){
  $dataUpdate = $dbConnect->prepare("SELECT * FROM product WHERE id_product = :id");
  $dataUpdate->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
  $dataUpdate->execute();

  $currentProduct = $dataUpdate->fetch(PDO::FETCH_ASSOC);
  // echo '<pre>'; print_r($currentProduct); echo '</pre>';
}

require_once('include/header.php');
?>

<section class="section is-title-bar">
  <div class="level">
    <div class="level-left">
      <div class="level-item">
        <ul>
          <li>Admin</li>
          <li>Boutique</li>
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
        <span class="icon"><span class="mdi mdi-shopping-outline"></span>
        </span>
        <?= $txt ?>
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
                <th class="is-checkbox-cell">
                  <label class="b-checkbox checkbox">
                    <input type="checkbox" value="false" />
                    <span class="check"></span>
                  </label>
                </th>
                <?php 
                  for($i = 0; $i < $data->columnCount(); $i++): 
                    $dataColumn = $data->getColumnMeta($i);
                    if($dataColumn['name'] != 'id_product'):
                ?>
                <th><?php echo ucfirst($dataColumn['name']); ?></th>
                <?php 
                    endif; 
                  endfor; 
                ?>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($products as $arrayProduct): ?>
                <tr>
                  <td class="is-checkbox-cell">
                    <label class="b-checkbox checkbox">
                      <input type="checkbox" value="false" />
                      <span class="check"></span>
                    </label>
                  </td>
                  <?php 
                    foreach($arrayProduct as $key => $value):
                      if($key != 'id_product'):
                  ?>
                    <td data-label="<?= ucFirst($key) ?>">
                      <?php if($key == 'picture'): ?>
                        <img src="<?= $value ?>" alt="<?= $arrayProduct['title']; ?>" class="picture__product">
                      <?php elseif($key == 'price'): ?>
                        <?= $value; ?>€
                      <?php else : ?>
                        <?= $value; ?>
                      <?php endif;  ?>
                    </td>
                  <?php 
                      endif; 
                    endforeach; 
                  ?>
                  <td class="is-actions-cell">
                    <div class="buttons is-right">
                      <a
                        class="button is-small is-primary"
                        href="?action=update&id=<?= $arrayProduct['id_product']; ?>">
                        <span class="icon"><i class="mdi mdi-pencil"></i></span>
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
                <div id="sample-modal-<?= $arrayProduct['id_product']; ?>" class="modal">
                  <div class="modal-background jb-modal-close"></div>
                  <div class="modal-card">
                    <div class="modal-card-head">
                      <p class="modal-card-title">Confirmez la suppression</p>
                      <button class="delete jb-modal-close" aria-label="close"></button>
                    </div>
                    <section class="modal-card-body">
                      <p>Voulez vous vraiment supprimer ce produit ?</p>
                    </section>
                    <div class="modal-card-foot">
                      <button class="button jb-modal-close">Annuler</button>
                      <a href="?action=delete&id=<?= $arrayProduct['id_product']; ?>" class="button is-danger jb-modal-close">Supprimer</a>
                    </div>
                  </div>
                    <button
                      class="modal-close is-large jb-modal-close"
                      aria-label="close"></button>
                  </div>
                </div>
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

<section class="section is-main-section">
  <div class="card">
    <header class="card-header">
      <p class="card-header-title">
        <span class="icon"><span class="mdi mdi-shopping-outline"></span></span>
        <?php if(isset($_GET['action']) && $_GET['action'] == 'update'): ?>
          Modification
        <?php else : ?>
          Ajout
        <?php endif; ?>
        Produit
      </p>
    </header>
    <div class="card-content">
      <!-- enctype="multipart/form-data" permet de récupérer en PHP les données d'un fichier uploadé (par ex une image) -->
      <form method="post" enctype="multipart/form-data">
        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Référence / Catégorie</label>
          </div>
          <div class="field-body">
            <div class="field">
              <input class="input" type="text" name="reference" placeholder="Entrer une référence produit" value="<?php if(isset($currentProduct['reference'])) echo $currentProduct['reference']; ?>" />
            </div>
            <div class="field">
              <input class="input" type="text" name="category" placeholder="Entrer une catégorie produit" value="<?php if(isset($currentProduct['category'])) echo $currentProduct['category']; ?>" />
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Titre / Couleur</label>
          </div>
          <div class="field-body">
            <div class="field">
              <input class="input" type="text" name="title" placeholder="Entrer un titre produit" value="<?php if(isset($currentProduct['title'])) echo $currentProduct['title']; ?>" />
            </div>
            <div class="field">
              <input class="input" type="text" name="color" placeholder="Entrer une couleur produit" value="<?php if(isset($currentProduct['color'])) echo $currentProduct['color']; ?>" />
            </div>
          </div>
        </div>
        
        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Taille / Genre</label>
          </div>
          <div class="field-body">
            <div class="field is-narrow">
              <div class="control">
                <div class="select is-fullwidth">
                  <select name="size">
                    <option value="S">S</option>
                    <option <?php if(isset($currentProduct['size']) && $currentProduct['size'] == 'M') echo 'selected'; ?> value="M">M</option>
                    <option <?php if(isset($currentProduct['size']) && $currentProduct['size'] == 'L') echo 'selected'; ?> value="L">L</option>
                    <option <?php if(isset($currentProduct['size']) && $currentProduct['size'] == 'XL') echo 'selected'; ?> value="XL">XL</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="field is-narrow">
              <div class="control">
                <div class="select is-fullwidth">
                  <select name="public">
                    <option value="homme">Homme</option>
                    <option <?php if(isset($currentProduct['public']) && $currentProduct['public'] == 'femme') echo 'selected'; ?> value="femme">Femme</option>
                    <option <?php if(isset($currentProduct['public']) && $currentProduct['public'] == 'mixte') echo 'selected'; ?> value="mixte">Mixte</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Image produit</label>
          </div>
          <div class="field-body">
            <div class="field">
              <div class="file has-name">
                <label class="file-label">
                  <input class="file-input" type="file" name="picture" />
                  <span class="file-cta">
                    <!-- <span class="file-icon">
                      <i class="fas fa-upload"></i>
                    </span> -->
                    <span class="file-label"> Choisir un fichier </span>
                  </span>
                  <span class="file-name"> Parcourir </span>
                </label>
              </div>
              <?php if(isset($errorPicture)) echo $errorPicture; ?>
            </div>
          </div>
        </div>

        <input type="hidden" name="current_picture" value="<?php if(isset($currentProduct['picture'])) echo $currentProduct['picture']; ?>">

        <?php if(isset($currentProduct['picture']) && !empty($currentProduct['picture'])): ?>
          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Produit actuel</label>
            </div>
            <div class="field-body">
              <div class="field">
                <img src="<?= $currentProduct['picture'] ?>" alt="<?php if(isset($currentProduct['title'])) echo $currentProduct['title']; ?>" class="picture__product">
              </div>
            </div>
          </div>
        <?php endif; ?>

        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Prix / Stock</label>
          </div>
          <div class="field-body">
            <div class="field">
              <input class="input" type="text" name="price" placeholder="Entrer un prix produit" value="<?php if(isset($currentProduct['price'])) echo $currentProduct['price']; ?>" />
            </div>
            <div class="field">
              <input class="input" type="text" name="stock" placeholder="Entrer une quantité produit" value="<?php if(isset($currentProduct['stock'])) echo $currentProduct['stock']; ?>" />
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label is-normal">
            <label class="label">Description</label>
          </div>
          <div class="field-body">
            <div class="field">
              <div class="control">
                <textarea
                  class="textarea"
                  placeholder="Entrer une description" name="description"><?php if(isset($currentProduct['description'])) echo $currentProduct['description']; ?></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- <div class="field is-horizontal">
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
        </div> -->

        <hr />

        <div class="field is-horizontal">
          <div class="field-label">
            <!-- Left empty for spacing -->
          </div>
          <div class="field-body">
            <div class="field">
              <div class="field is-grouped">
                <div class="control">
                  <button type="submit" name="submit" class="button is-primary">
                    <span>
                      <?php if(isset($_GET['action']) && $_GET['action'] == 'update'): ?>
                        Modifier
                      <?php else : ?>
                        Ajouter
                      <?php endif; ?>
                    </span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

<?php 
require_once('include/footer.php'); 
if($_SESSION['msg'] === false){
  unset($_SESSION['msgValid']);
}
?>
