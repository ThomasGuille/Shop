<?php
// ------------ FONCTION UTILISATEUR AUTHENTIFIE
// permet de savoir si l'utilisateur est authentifié ou non
function userConnected(){
    if(isset($_SESSION['user'])) return true;
    else
    return false;
}

// ------------ FONCTION ADMIN AUTHENTIFIE
// permet de savoir si un administrateur est authentifié ou non
function adminConnected(){
    if(userConnected() && $_SESSION['user']['roles'] == 'admin') return true;
    else
    return false;
}

// FONCTION CREATION PANIER

function createCart(){
    // Si l'indice cart n'est pas définit cela veut dire que l'utilisateur n'a pas ajouté d'article dans son panier
    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] = [];
        $_SESSION['cart']['id_product'] = [];
        $_SESSION['cart']['title'] = [];
        $_SESSION['cart']['picture'] = [];
        $_SESSION['cart']['reference'] = [];
        $_SESSION['cart']['quantity'] = [];
        $_SESSION['cart']['price'] = [];
    }
}

function addProductCart($id_product, $title, $picture, $reference, $quantity, $price){
    createCart(); // On contrôle si le panier existe dans la session

    $positionProduct = array_search($id_product, $_SESSION['cart']['id_product']);
    // var_dump($positionProduct);

    // Si $positionProduct est true, cela veut dire que le produit existe déjà dans le panier, donc on ajoute à la quantité
    if($positionProduct !== false){
        $_SESSION['cart']['quantity'][$positionProduct] += $quantity;
    }else{
        // Les [vide] permettent de créer des indices numériques dans les tableaux array
        $_SESSION['cart']['id_product'][] = $id_product;
        $_SESSION['cart']['title'][] = $title;
        $_SESSION['cart']['picture'][] = $picture;
        $_SESSION['cart']['reference'][] = $reference;
        $_SESSION['cart']['quantity'][] = $quantity;
        $_SESSION['cart']['price'][] = $price;
    }
}

// FONCTION SUPPRESSION EN CAS DE RUPTURE
function removeProductCart($id_product){
    // on cherche à quel indice se trouve l'id du produit à supprimer
    $positionProduct = array_search($id_product, $_SESSION['cart']['id_product']);
    // var_dump($positionProduct);

    // si il y a une correspondance, on supprime chaque élément de la session ayant pour indice celui du produit
    if($positionProduct != false){
        // array_splice() est une fonction prédéfinie permettant de supprimer un élément dans un array à l'indice donné (ici $positionProduct) et réattribue les indices pour ne pas en avoir de laissé vide (les éléments de l'indice 3 remontent à l'indice 2, par exemple)
        array_splice($_SESSION['cart']['id_product'], $positionProduct, 1);
        array_splice($_SESSION['cart']['title'], $positionProduct, 1);
        array_splice($_SESSION['cart']['picture'], $positionProduct, 1);
        array_splice($_SESSION['cart']['reference'], $positionProduct, 1);
        array_splice($_SESSION['cart']['quantity'], $positionProduct, 1);
        array_splice($_SESSION['cart']['price'], $positionProduct, 1);
    }
}


// CALCUL DU MONTANT TOTAL DU PANIER
function totalCartPrice(){
    $total = 0;
    for($i = 0; $i < count($_SESSION['cart']['id_product']); $i++){
        $total += $_SESSION['cart']['price'][$i] * $_SESSION['cart']['quantity'][$i];
    }
    return round($total, 2);
}

// FONCTION LIEN ACTIF DANS LA NAV
function activeNavLink($url){
    if($_SERVER['PHP_SELF'] == $url){
        echo 'active';
    }
}

function activeBackLink($url){
    if($_SERVER['PHP_SELF'] == $url){
        echo 'is-active';
    }
}

function activeBackIcon($url){
    if($_SERVER['PHP_SELF'] == $url){
        echo 'has-update-mark';
    }
}
