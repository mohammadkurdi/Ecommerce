<?php
    session_start();
    $noNavbar = '';
    $pageTitle = 'Login page';
    // Check if session exist
    if (isset($_SESSION ['Username'])){
        header('Location: dashboard.php');
    }
    include "init.php";
    

    // Check if user coming from http post request
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = $_POST['user'];
        $password = $_POST['pass'];   
        $hashedPass = sha1($password);

        // Check if the user exist in DB
        $stmt = $con->prepare("SELECT  
                                    Username, Password, UserID, Fullname
                               FROM 
                                    users
                               WHERE
                                    Username = ? AND Password = ? AND GroupID = 1
                               LIMIT 1");
        $stmt->execute(array($username, $hashedPass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0){
            $_SESSION['Username'] = $username; // Register Session Name
            $_SESSION['ID'] = $row['UserID']; // Register ID
            $_SESSION['Fullname'] = $row['Fullname']; //Register Fullname
            header('Location: dashboard.php'); // Redirect to Dashboard Page
            exit();
        };
    };
?>
    <!-- Login page -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <h4 CLASS="text-center">Admin login</h4>
        <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off" />
        <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password" />
        <input class="btn btn-primary btn-block" type="submit" value="login" />
    </form>

<?php include $tpl . "footer.php"; ?>