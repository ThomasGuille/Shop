<?php 
require_once('include/init.php');

// echo '<pre>'; print_r($_SESSION); echo '</pre>';
// unset($_SESSION['cart']);

if(isset($_POST['addCart'])){
    $data = $dbConnect->prepare("SELECT * FROM product WHERE id_product = :id");
    $data->bindValue(':id', $_POST['id_product'], PDO::PARAM_INT);
    $data->execute();

    $product = $data->fetch(PDO::FETCH_ASSOC);
    $data->bindValue(':id', $product['id_product'], PDO::PARAM_INT);
    // echo '<pre>'; print_r($product); echo '</pre>';
    
    addProductCart($product['id_product'], $product['title'], $product['picture'], $product['reference'], $_POST['quantity'], $product['price']);
    
    header('location: cart.php');
}

if(isset($_POST['checkOut'])){
    $error = '';
    for($i = 0; $i < count($_SESSION['cart']['id_product']); $i++){
        $data = $dbConnect->query("SELECT * FROM product WHERE id_product =" . $_SESSION['cart']['id_product'][$i]);
        $product = $data->fetch(PDO::FETCH_ASSOC);
        // echo '<pre>'; print_r($product); echo '</pre>';

        // Comparaison du stock restant avec la quantité demandée
        if($product['stock'] < $_SESSION['cart']['quantity'][$i]){
            $error .= "<div class='alert alert-danger text-center'>Stock restant du produit " . $_SESSION['cart']['title'][$i] . ": <strong>" . $product['stock'] . "</strong></div>";
            $error .= "<div class='alert alert-warning text-center'>Quantité commandée: <strong>" . $_SESSION['cart']['quantity'][$i] . "</strong></div>";

            if($product['stock'] > 0){
                // si il en reste en stock
                $_SESSION['cart']['quantity'][$i] = $product['stock'];
                $error .= "<div class='alert alert-success text-center'>La quantité du produit: " . $_SESSION['cart']['title'][$i] . " a été réduite car il n'y en a plus asseez en stock</div>";
            }else{
                // si rupture de stock
                removeProductCart($_SESSION['cart']['id_product'][$i]);
                $error .= "<div class='alert alert-success text-center'>Le produit: " . $_SESSION['cart']['title'][$i] . " a été supprimé car il est en rupture de stock</div>";
                $i--;   // on décrémente la boucle après la suppression car array_splice supprime l'article et remonte les indices. Cela permet de ne pas oublier de contrôler un article qui aurait changé d'indice
            }
        }
    }

    // Requête insertion commande en BDD
    if(empty($error)){
        $data = $dbConnect->exec("INSERT INTO `order` (user_id, rising, date, state) VALUES (" . $_SESSION['user']['id_user'] . ", " . totalCartPrice() . ", NOW(), 'treatment')");
        $idOrder = $dbConnect->lastInsertId();  // on récupère la dernière id générée en BDD pour l'enregistrer dans la table order_details afin de lier chaque produit à la bonne commande
        // print_r($idOrder);

        for($i = 0; $i < count($_SESSION['cart']['id_product']); $i++){
            $data = $dbConnect->exec("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES ($idOrder, " . $_SESSION['cart']['id_product'][$i] . ", " . $_SESSION['cart']['quantity'][$i] . ", " . $_SESSION['cart']['price'][$i] . ")");

            $data = $dbConnect->exec("UPDATE product SET stock = stock - " . $_SESSION['cart']['quantity'][$i] . " WHERE id_product = " . $_SESSION['cart']['id_product'][$i]);
        }

        unset($_SESSION['cart']);
        $_SESSION['validOrderMsg'] = "<div class='alert alert-success text-center'>Votre commande a bien été prise en compte. Numéro de la commande: <strong>FAMMS$idOrder</strong></div>";
    }
}

// echo '<pre>'; print_r($_SESSION); echo '</pre>';

// unset($_SESSION['cart']);

require_once('include/header.php');
?>

<!-- inner page section -->
<section class="inner_page_head">
    <div class="container_fuild">
        <div class="row">
            <div class="col-md-12">
                <div class="full">
                    <h3>Panier</h3>
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
            <h2>Validez vos <span>achats</span></h2>
        </div>

        <?php 
            if(isset($error)) echo $error; 
            if(isset($_SESSION['validOrderMsg'])) echo $_SESSION['validOrderMsg'];
            unset($_SESSION['validOrderMsg']);
        ?>

        <div class="row">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Image</th>
                        <th>Référence</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Prix total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                    <?php if(empty($_SESSION['cart']['id_product'])): ?>
                        <tr>
                            <td colspan="7" class="text-center">Votre panier est vide</td>
                        </tr>
                    <?php else: 
                        for($i = 0; $i < count($_SESSION['cart']['id_product']); $i++): ?>
                            <tr>
                                <td><?= $_SESSION['cart']['title'][$i]; ?></td>
                                <td><img src="<?= $_SESSION['cart']['picture'][$i]; ?>" class="picture_product" alt="<?= $_SESSION['cart']['title'][$i]; ?>"></td>
                                <td><?= $_SESSION['cart']['reference'][$i]; ?></td>
                                <td><?= $_SESSION['cart']['price'][$i]; ?>€</td>
                                <td><?= $_SESSION['cart']['quantity'][$i]; ?></td>
                                <td><strong><?= $_SESSION['cart']['price'][$i] * $_SESSION['cart']['quantity'][$i]; ?>€</strong></td>
                                <td><a href=""><i class="fa-solid fa-trash"></i></a></td>
                            </tr>
                        <?php endfor; ?>
                        <tr>
                            <th>Montant total</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th><?= totalCartPrice(); ?></th>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if(!empty($_SESSION['cart']['id_product'])): ?>
            <div class="btn-box">
                <?php if(userConnected() ): ?>
                    <form action="" method="post">
                        <input type="submit" name="checkOut" value="Procéder au paiement">
                    </form>
                <?php else : ?>
                    <p>Veuillez vous <a href="inscription.php">inscrire</a> ou vous <a href="connexion.php">connecter</a> pour valider le paiement</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="btn-box">
            <a href="product.php"> Continuez vos achats </a>
        </div>
    </div>
    </div>
</section>
<!-- end product section -->

<?php require_once('include/footer.php'); ?>