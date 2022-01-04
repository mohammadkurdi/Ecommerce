<?php
    /* 
    ==================================================
    == Manage members page.                         
    == You can Add | Edit | Delete members from here.
    ==================================================
    */
    ob_start();
    session_start();
    $pageTitle = '';

    // Check if session exist
    if (isset($_SESSION['Username'])){
        include 'init.php';

        // Start manage page

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        if($do == 'Manage') { 
    

        } elseif ($do == 'Add') {
            /*
            ===================================
            ===================================
            */

        } elseif ($do == 'Insert') { //Insert Page
            /*
            ======================================
            ======================================
            */

        } elseif ($do == 'Edit') { // Edit page 
            /*
            ====================================
            ====================================
            */

        } elseif ($do == 'Update') { // Update page
            /*
            ======================================
            ======================================
            */

        } elseif ($do == 'Delete') { // Delete member page
            /*
            ======================================
            ======================================
            */

        } elseif ($do == 'Pending') {
            /*
            ================================================
            ================================================
            */

        } elseif ($do == 'Approve'){

                /*
                ===========================================
                ===========================================
                */

        } else {
            echo "<div class='container'>";
            $theMsg ='<div class="alert alert-danger">  ERROR 404 PAGE NOT FOUND  </div>';
            redirect($theMsg);
            echo "</div>";
        }
        include $tpl . "footer.php";
    } else {
        header('Location: index.php');
        exit();
    }
    ob_end_flush();
?>