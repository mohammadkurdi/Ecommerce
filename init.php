<?php
    include 'connect.php';
    //Routes
    $tpl =  'includes/templates/';   // Template Directory
    $lang = 'includes/languages/';   // Language Directory
    $func = 'includes/functions/';   // Function Directory
    $css =  'layout/css/';           // CSS Directory
    $js  =  'layout/js/';            // JS Directory
 
    //include the important files
    include $func . 'functions.php';
    include $lang . 'en.php';
    include $tpl . 'header.php';
    
    //include navbar on all pages expect the no $navbar variable
    if (!isset($noNavbar)){include $tpl . 'navbar.php';}