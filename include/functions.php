<?php
// ------------ FONCTION UTILISATEUR AUTHENTIFIE
// permet de savoir si l'utilisateur est authentifié ou non
function userConnect(){
    if(!isset($_SESSION['user'])) return false;
    return true;
}