<?php
    /*
    ** Title function v1.0
    ** Title function that echo the page title in case 
    ** the page has the variable $pageTitle and echo defult title for other pages
    */

    function getTitle() {
        global $pageTitle;
        if (isset($pageTitle)){
            echo $pageTitle;
        } else {
            echo 'Default';
        }
    }

    /*
    ** Redirect Function v1.0
    ** [This function accept parameters]
    ** $errorMsg = echo the errore message
    ** $seconds = seconds before redirecting
    

   function redirectHome($errorMsg, $seconds = 3) {
        echo "<div class='alert alert-danger'>$errorMsg</div>";
        echo "<div class='alert alert-info'>You will be redirected to Homepage after $seconds seconds.</div>";
        header("refresh:$seconds;url=index.php");

        exit();
    } 
    */


    /*    
    ** Redirect Function v2.0
    ** [This function accept parameters]
    ** $theMsg = echo the message
    ** $url = the link to redirect to
    ** $seconds = seconds before redirecting
    */

    function redirect($theMsg, $url = null, $seconds = 3) {
        echo $theMsg;

        if ($url === null) {
            $url = 'index.php';
            echo "<div class='alert alert-info'>You will be redirected to the Home page after $seconds seconds.</div>";

        } elseif ($url === 'back') {
            $url = $_SERVER['HTTP_REFERER'];
            echo "<div class='alert alert-info'>You will be redirected to the Previous page after $seconds seconds.</div>";
        }
        header("refresh:$seconds;url=$url");
        exit();
    } 

    /*
    ** Check items function v1.0
    ** Function to check items in DB [Accept parameters]
    ** $select = the item to select [user, item, category]
    ** $from = the table to select from [users, items, categories]
    ** $value = the value of select [osama, box, electornics]
    */

    function checkItem($select, $from, $value) {
        global $con;
        $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
        $statement->execute(array($value));
        $count = $statement->rowCount();
        return $count;
    }

    /*
    ** Privileges  GroupID v1.0
    */
    
    function privilege($state) {
        if ($state == 'Admin') {
            return '2';        
        } elseif ($state == 'SuperAdmin'){
            return '1';
        }  elseif ($state == 'User') {
            return '0';
        } elseif ($state == '0') {
            return 'User';
        } elseif ($state == '1') {
            return  'SuperAdmin';
        } elseif ($state == '2') {
            return 'Admin';
        }
    }

    /* 
    ** Count number of items v1.0
    ** Function to count number of items row
    ** $item = the item to count
    ** $table = the table to choose from
    */
/*
    function countItems($item, $table) {
        global $con;
        $stmt = $con->prepare("SELECT COUNT($item) FROM $table");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
*/
    /* Count number of item v2.0
    ** Function to count number of items row with condition
    ** $item = the item to count
    ** $table = the table to choose from
    ** $condition = the condition
    */
    function countItems($item, $table, $condition = '') {
        global $con;
        $stmt = $con->prepare("SELECT COUNT($item) FROM $table $condition");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /* Get latest records function v1.0
    ** Function to get latest items from DB
    ** $item = the item to select
    ** $table = the table to choose from
    ** $limit = limit of rows you want
    ** $order = DESC or ASC
    ** $condition = the condition
    */

    function getLatest($item, $table, $order, $limit = 5, $condition = '' ){
        global $con;
        $stmt = $con->prepare("SELECT $item FROM $table $condition ORDER BY $order DESC LIMIT $limit ");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
    }