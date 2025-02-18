<?php 
require_once('include/init.php');

// echo '<pre>'; print_r($_POST); echo '</pre>';

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
    for($i = 0; $i < count($_SESSION['cart']['id_product']); $i++){
        $data = $dbConnect->query("SELECT * FROM product WHERE id_product =" . $_SESSION['cart']['id_product'][$i]);
        $product = $data->fetch(PDO::FETCH_ASSOC);
        echo '<pre>'; print_r($product); echo '</pre>';

        $error = '';
        if($product['stock'] < $_SESSION['cart']['quantity'][$i]){
            $error .= "<div class='alert alert-danger'>Stock restant du produit " . $_SESSION['cart']['title'][$i] . ": <strong>" . $product['stock'] . "</strong></div>";
            $error .= "<div class='alert alert-danger mt-2'>Quantité commandée: <strong>" . $_SESSION['cart']['quantity'][$i] . "</strong></div>";
        }
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