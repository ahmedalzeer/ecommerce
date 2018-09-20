<?php
//active errors report
ini_set('display_errors','on');
error_reporting(E_ALL);

//session
    $sessionuser = '';
    if (isset($_SESSION['user']))
    {
        $sessionuser = $_SESSION['user'];
    }
//connect to database

    include 'admin/connect.php';

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


?>