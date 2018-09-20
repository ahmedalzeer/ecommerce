<?php

//connect to database

    include 'connect.php';

//Routes

    $tpl   = 'includes/templates/';          //templates directory
    $lang  = 'includes/languages/';          // languages directory
    $func  = 'includes/functions/';          // functions directory
    $css   = 'layout/css/';                  //css directory
    $js    = 'layout/js/';                   // js directory

// import important files
    include $func.'function.php';
    include $lang.'english.php';
    include $tpl.'header.php';
    if (!isset($nonavbar))
    {
        include $tpl.'navbar.php';
    }


?>