<?php 
require_once('include/init.php');

if(!userConnected() || !isset($_GET['id'])){
  header('location: index.php');
}

$data = $dbConnect->prepare("SELECT * FROM `order` WHERE user_id = :userid ORDER BY date DESC");
$data->bindValue(':userid', $_GET['id'], PDO::PARAM_INT);
$data->execute();
$dataOrder = $data->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>'; print_r($dataOrder); echo '</pre>';

$dataDetails = $dbConnect->prepare("SELECT order.id_order, product.id_product, product.picture, product.title, product.price, order_details.quantity
FROM user JOIN `order` ON user.id_user = order.user_id
JOIN order_details ON order.id_order = order_details.order_id
JOIN product ON order_details.product_id = product.id_product
WHERE user.id_user = :id
ORDER BY order.date DESC");
$dataDetails->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
$dataDetails->execute();

$dataOrderDetails = $dataDetails->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>'; print_r($dataOrderDetails); echo '</pre>';



require_once('include/header.php');
?>


<section class="inner_page_head">
    <div class="container_fuild">
        <div class="row">
        <div class="col-md-12">
            <div class="full">
            <h3>Mes commandes</h3>
            </div>
        </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <?php foreach($dataOrder as $keyOrder => $valueOrder): ?>
                <table>
                    <tr>
                        <td>Date de la commande: <?= $valueOrder['date']; ?></td>
                    </tr>
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Total article</th>
                    </tr>
                    <?php foreach($dataOrderDetails as $keyDetails => $valueDetails): if($valueOrder['id_order'] == $valueDetails['id_order']): ?>
                        <tr>
                            <?php foreach($valueDetails as $key => $value): if($key != 'id_order' && $key != 'id_product'): ?>
                                <?php if($key == 'picture'): ?>
                                    <td><img src="<?= $valueDetails['picture']; ?>" alt="<?= $valueDetails['title']; ?>"></td>
                                <?php elseif($key == 'price'): ?>
                                    <td><?= $value; ?>€</td>
                                <?php else: ?>
                                    <td><?= $value; ?></td>
                                <?php endif; ?>
                            <?php endif; endforeach; ?>
                            <td><?= $valueDetails['price'] * $valueDetails['quantity']; ?>€</td>
                        </tr>
                    <?php endif; endforeach; ?>
                    <tr>
                        <td>Etat de la commande: <?= $valueOrder['state']; ?></td>
                    </tr>
                </table>
            <?php endforeach; ?>
        </div>
    </div>
</section>


<?php require_once('include/footer.php'); ?>