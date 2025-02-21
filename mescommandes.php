<?php 
require_once('include/init.php');

if(!userConnected() || !isset($_GET['id'])){
  header('location: index.php');
}

$data = $dbConnect->prepare("SELECT id_order, DATE_FORMAT(date, '%d-%m-%Y') as date, rising, state FROM `order` WHERE user_id = :userid ORDER BY date DESC");
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
        <div class="row d-flex flex-column">
            <?php foreach($dataOrder as $keyOrder => $valueOrder): ?>
                <div class="order__table">
                    <div class="date__number">
                        <p class="date__order"><span class="details__title">Date de la commande: </span><?= $valueOrder['date']; ?></p>
                        <p class="date__order"><span class="details__title">Numéro de commande: </span>FAMMS<?= $valueOrder['id_order']; ?></p>
                    </div>
                    <table class="order__row">
                        <tr>
                            <th class="order__text"></th>
                            <th class="order__text">Nom de l'article</th>
                            <th class="order__text">Prix unitaire</th>
                            <th class="order__text">Quantité</th>
                            <th class="order__text">Total article</th>
                        </tr>
                        <?php foreach($dataOrderDetails as $keyDetails => $valueDetails): if($valueOrder['id_order'] == $valueDetails['id_order']): ?>
                            <tr class="order__details">
                                <?php foreach($valueDetails as $key => $value): if($key != 'id_order' && $key != 'id_product'): ?>
                                    <?php if($key == 'picture'): ?>
                                        <td class="picture__product__order"><img class="picture_product" src="<?= $valueDetails['picture']; ?>" alt="<?= $valueDetails['title']; ?>"></td>
                                    <?php elseif($key == 'price'): ?>
                                        <td class="order__text"><?= $value; ?>€</td>
                                    <?php else: ?>
                                        <td class="order__text"><?= $value; ?></td>
                                    <?php endif; ?>
                                <?php endif; endforeach; ?>
                                <td class="order__text"><?= $valueDetails['price'] * $valueDetails['quantity']; ?>€</td>
                            </tr>
                        <?php endif; endforeach; ?>
                    </table>
                    <hr>
                    <div class="total__state d-flex justify-content-between">
                        <p><span class="details__title">Etat de la commande: </span><?php switch($valueOrder['state']){
                            case 'treatment' :
                                echo 'en cours de traitement';
                            break;
                            case 'sent' :
                                echo 'envoyée';
                            break;
                            case 'delivered' :
                                echo '<span class="order__delivered">livrée</span>';
                            break;
                        } ?></p>
                        <p><span class="details__title">Montant total de la commande: </span><?= $valueOrder['rising']; ?>€</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>


<?php require_once('include/footer.php'); ?>