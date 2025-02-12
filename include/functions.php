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