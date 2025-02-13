<?php 
// -------- Connexion BDD
$dbConnect = new PDO("mysql:host=localhost;dbname=shop", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
]);
// echo '<pre>'; print_r($dbConnect); echo '</pre>';

// -------- SESSION

session_start();

// -------- CHEMIN
// echo '<pre>'; print_r($_SERVER); echo '</pre>';

define('RACINE_SITE', $_SERVER['DOCUMENT_ROOT'] . '/PHP/Boutique/Shop/');
// echo '<pre>'; print_r(RACINE_SITE); echo '</pre>';
// Cette constante retourne le chemin physique du dossier htdocs sur le serveur
// Lors de l'enregistrement d'image/photo, nous avons besoin du chemin complet du dossier
// echo RACINE_SITE . 'Boutique/Shop/assets/images/nom_de_image' par exemple, pour le stockage physique des images

define("URL", "http://localhost/PHP/Boutique/Shop/");
// <img url="URL . assets/images/images.jpg">
// Cette constante servira à enregistrer l'URL d'une image dans la BDD. Dans la mesure où on ne peut pas enregistrer physiquement une image dans une BDD, on lui définit un chemin d'accès

// -------- VARIABLES
$content = '';

// -------- FAILLES XSS
foreach($_POST as $key => $value){
    $_POST[$key] = htmlentities(addslashes(trim($value)));
}
foreach($_GET as $key => $value){
    $_GET[$key] = htmlentities(addslashes(trim($value)));
}
// trim() fonction prédéfinie qui supprime les espaces en début et fin de chaine de caractères

// -------- INCLUSION FONCTIONS
require_once("functions.php");
?>