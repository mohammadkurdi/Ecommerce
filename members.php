<?php
    /* 
    ==================================================
    == Manage members page.                         
    == You can Add | Edit | Delete members from here.
    ==================================================
    */
    ob_start();
    session_start();
    $pageTitle = 'Members';

    // Check if session exist
    if (isset($_SESSION['Username'])){
        include 'init.php';

        // Start manage page

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        if($do == 'Manage') { 
            // Select all members except Admin
            $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 AND Regstatus != 0");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            

?> 
            <!-- Manage Members page -->
            <h1 class="text-center">Manage Members</h1>
            <div class="container">
                <div class="table-responsive"> 
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Registerd Date</td>
                            <td>Type</td>
                            <td>Control</td>
                        </tr>
<?php
                        foreach ($rows as $row) {
                            $type = privilege($row['GroupID']);
                            echo "<tr>";
                                echo "<td>" . $row['UserID'] . "</td>";
                                echo "<td>" . $row['Username'] . "</td>";
                                echo "<td>" . $row['Email'] . "</td>";
                                echo "<td>" . $row['FullName'] . "</td>";
                                echo "<td>" . $row['Date'] . "</td>";
                                echo "<td>" . $type . "</td>";
                                echo "<td>
                                    <a href='members.php?do=Edit&userID=" . $row['UserID'] . "'class='btn btn-success'><i class= 'fa fa-edit'> Edit</i></a> 
                                    <a href='members.php?do=Delete&userID=" . $row['UserID'] . "'class='btn btn-danger confirm'><i class= 'fa fa-close'> Delete</i></a>                                     </td>";
                            echo "</tr>";
                        }
?>
                    </table>
                </div>
                <a href="members.php?do=Add" class="btn btn-primary"><i class= "fa fa-plus">  New Member</i></a>
                <a href="members.php?do=Pending" class="btn btn-secondary"><i class= "fa fa-thumb-tack">  Pending members <span><?php echo "[" . countItems('UserID','users','WHERE RegStatus = 0') . "]";?></span>
</i></a>

            </div>
            

<?php
            } elseif ($do == 'Add') {
            /*
            ===================================
            == You can Add Members from here ==
            ===================================
            */
?>
            <h1 class="text-center">Add New Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!-- start username field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="username" class="form-control" required='required' placeholder="To login"/>
                        </div>
                    </div>
                    <!-- end username field -->

                    <!-- start password field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="Password" name="password" class="password form-control" autocomplete="new-password" required="required" placeholder="Must contain numbers and special char"/>
                            <i class="show-pass fa fa-eye fa-2x"></i>
                        </div>
                    </div>
                    <!-- end password field -->

                    <!-- start Email field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="Email" name="email" class="form-control"  required="required" placeholder="Email must be valid"/>
                        </div>
                    </div>
                    <!-- end Email field -->

                    <!-- start Fullname field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="full" class="form-control" required="required" placeholder="Appear on your profile page"/>
                        </div>
                    </div>
                    <!-- end Fullname field -->

                    <!-- start AcountType field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Acount type</label>
                        <div class="form-check">
                            <input type="radio" name="type" class="form-check-input " required="required" value = "User" id="User1" checked="checked">
                            <label class="form-check-label" for="User1">User</label>
                            <input type="radio" name="type" class="form-check-input " required="required" value = "Admin" id="Admin1">
                            <label class="form-check-label" for="Admin1">Admin</label>                                                        
                            <input type="radio" name="type" class="form-check-input " required="required" value = "SuperAdmin" id="SuperAdmin1">
                            <label class="form-check-label" for="SuperAdmin1">SuperAdmin</label>
                        </div>
                    </div>
                    <!-- end AcountType field -->

                    <!-- start submit field -->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add new member" class="btn btn-primary btn-lg"/>
                        </div>
                    </div>
                    <!-- end submit field -->
                </form>
            </div>
<?php
        } elseif ($do == 'Insert') { //Insert Page
            /*
            ======================================
            == You can Insert Members from here ==
            ======================================
            */
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo '<h1 class="text-center">Insert Member</h1>';
                echo '<div class="container">';
                // Get variables from the form
                $user  = $_POST['username'];
                $pass  = $_POST['password'];
                $email = $_POST['email'];
                $name  = $_POST['full'];

                $hashPass = sha1($_POST['password']);

                $state = $_POST['type'];    //AcountType           
                $type = privilege($state);

                //check if strong password
                $number    = preg_match('@[0-9]@', $_POST['password']);
                $specialChars = preg_match('@[^\w]@', $_POST['password']);

                // Validate
                $formErrors = array();


                if (strlen($user) < 4 || strlen($user) > 11){
                    $formErrors[] =  'Username should be <strong>between 4 and 11 char</strong>';
                }                
                if (strlen($name) > 25){
                    $formErrors[] =  'FullName can\'t be <strong>more then 25 char</strong>';
                }
                if (!empty($_POST['password']) && (strlen($_POST['password']) < 6 || !$number || !$specialChars)){
                    $formErrors[] =  'Password can\'t be <strong>less than 6 char and contains numbers and special chars</strong>';
                }

                // Loop into error and echo it
                foreach($formErrors as $error) {
                    $theMsg =  '<div class="alert alert-danger">' . $error . '</div>' . '<br/>';
                    redirect($theMsg,'back');
                } 

                // if ther's no errors insert
                if(empty($formErrors)){
                    //Check if user exist
                    $check = checkItem("Username", "users", $user);
                    if ($check == 1){
                        $theMsg = '<div class="alert alert-danger">  Sorry this username already exist  </div>';
                        redirect($theMsg,'back');
                    } else {
                    // Insert the data base with this info
                        $stmt = $con->prepare("INSERT INTO
                                            users(Username, Password, Email, FullName, RegStatus, Date, GroupID)
                                            VALUES(:user, :pass, :email, :name, 1, now(), :type)");
                        $stmt->execute(array(
                            'user'  =>  $user, 
                            'pass'  =>  $hashPass, 
                            'email' =>  $email, 
                            'name'  =>  $name,
                            'type'  =>  $type,
                ));

                //echo success message
                $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted </div>";
                redirect($theMsg,'members.php');               
                    }
            }
            } else {
                    echo "<div class='container'>";
                    $theMsg ='<div class="alert alert-danger">  Sorry you can\'t browse this page directly  </div>';
                    redirect($theMsg);
                    echo "</div>";
            }
            echo "</div>";

        } elseif ($do == 'Edit') { // Edit page 
            /*
            ====================================
            == You can Edit Members from here ==
            ====================================
            */
            // Check if get request userID is numeric & get the integer value

            $userid = isset($_GET['userID']) && is_numeric($_GET['userID']) ? intval($_GET['userID']) : 0;
           
            // Select all the data from this ID

            $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
            $stmt->execute(array($userid));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();
            
            // if there's such ID show the form

            if ($stmt->rowCount() > 0) { 
?>
                <h1 class="text-center">Edit Member</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                        <!-- start username field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="hidden" name="olduser" class="form-control" value="<?php echo $row['Username']; ?>" required="required"/>
                                <input type="text" name="username" class="form-control" value="<?php echo $row['Username']; ?>" required="required"/>
                            </div>
                        </div>
                        <!-- end username field -->

                        <!-- start password field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="hidden" name="oldpassword" value="<?php echo $row['Password']; ?>"/>
                                <input type="Password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank if you don't want to change"/>
                            </div>
                        </div>
                        <!-- end password field -->

                        <!-- start Email field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="Email" name="email" class="form-control" value="<?php echo $row['Email'];?>" required="required"/>
                            </div>
                        </div>
                        <!-- end Email field -->

                        <!-- start Fullname field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Full Name</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="full" class="form-control" value="<?php echo $row['FullName'];?>" required="required"/>
                            </div>
                        </div>
                        <!-- end Fullname field -->
                       
                        <!-- start AcountType field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Acount type</label>
                            <div class="form-check">
                                <input type="radio" name="type" class="form-check-input " required="required" value = "User" id="User1" checked="checked"> 
                                <label class="form-check-label" for="User1">User</label>
                                <input type="radio" name="type" class="form-check-input " required="required" value = "Admin" id="Admin1">
                                <label class="form-check-label" for="Admin1">Admin</label>                                                        
                                <input type="radio" name="type" class="form-check-input " required="required" value = "SuperAdmin" id="SuperAdmin1">
                                <label class="form-check-label" for="SuperAdmin1">SuperAdmin</label>
                            </div>
                        </div>
                        <!-- end AcountType field -->
                               
                        <!-- start submit field -->
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Save" class="btn btn-primary btn-lg"/>
                            </div>
                        </div>
                        <!-- end subiet field -->
                    </form>
                </div>
<?php
            // else show error message
            } else {
                echo "<div class='container'>";
                $theMsg = '<div class="alert alert-danger">  Theres no such ID  </div>';
                redirect($theMsg);
                echo "</div>";
            }

?>

<?php
        } elseif ($do == 'Update') { // Update page
            /*
            ======================================
            == You can Update Members from here ==
            ======================================
            */
            echo '<h1 class="text-center">Update Member</h1>';
            echo '<div class="container">';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Get variables from the form
                $id    = $_POST['userid'];
                $user  = $_POST['username'];
                $email = $_POST['email'];
                $name  = $_POST['full'];

                //AcountType
                $state = $_POST['type'];               
                $type = privilege($state);

                //Password update
                $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

                //check if strong password
                $number    = preg_match('@[0-9]@', $_POST['newpassword']);
                $specialChars = preg_match('@[^\w]@', $_POST['newpassword']);

                // Validate
                $formErrors = array();


                if (strlen($user) < 4 || strlen($user) > 11){
                    $formErrors[] =  'Username should be <strong>between 4 and 11 char</strong>';
                }                
                if (strlen($name) > 25){
                    $formErrors[] =  'FullName can\'t be <strong>more then 25 char</strong>';
                }
                if (!empty($_POST['newpassword']) && (strlen($_POST['newpassword']) < 6 || !$number || !$specialChars)){
                    $formErrors[] =  'Password can\'t be <strong>less than 6 char and contains numbers and special chars</strong>';
                }

                // Loop into error and echo it
                foreach($formErrors as $error) {
                    $theMsg =  '<div class="alert alert-danger">' . $error . '</div>' . '<br/>';
                    redirect($theMsg,'back');
                } 

                //if ther's no errors update
                if(empty($formErrors)){
                    //Check if user exist
                    $check = checkItem("Username", "users", $user);
                    if ($check == 1 && $user != $_POST['olduser']){
                        $theMsg = '<div class="alert alert-danger">  Sorry this username already exist  </div>';
                        redirect($theMsg,'back');
                    } else {                    
                    // Update the data base with this info
                    $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ?, GroupID = ? WHERE UserID = ? ");
                    $stmt->execute(array($user, $email, $name, $pass, $type, $id ));

                    //echo success message
                    $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated </div>";
                    redirect($theMsg,'members.php');
                    }}
                echo "</div>";
            } else {
                $theMsg ='<div class="alert alert-danger">  Sorry you can\'t browse this page directly  </div>';
                redirect($theMsg);
            }
        } elseif ($do == 'Delete') { // Delete member page
                /*
                ======================================
                == You can Delete Members from here ==
                ======================================
                */
                echo '<h1 class="text-center">Delete Member</h1>';
                echo '<div class="container">';

                // Check if get request userID is numeric & get the integer value

                $userid = isset($_GET['userID']) && is_numeric($_GET['userID']) ? intval($_GET['userID']) : 0;
            
                // Select all the data from this ID

                $check = checkItem('userid', 'users', $userid);

                
                // if there's such ID show the form
                if ($check > 0) { 
                    $stmt=$con->prepare("Delete FROM users WHERE  UserID = :user");
                    $stmt->bindParam(":user", $userid);
                    $stmt->execute();

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Deleted </div>";
                    redirect($theMsg,'members.php');
                } else {
                    $theMsg ='<div class="alert alert-danger">  Theres no such ID  </div>';
                    redirect($theMsg);
                }
            echo '</div>';
        } elseif ($do == 'Pending') {
            /*
            ================================================
            == You can View Pending New Members from here ==
            ================================================
            */
            // Select all members except Admin
            $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 AND RegStatus = 0");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            

?> 
            <!-- Manage Pending Members page -->
            <h1 class="text-center">Pending Members</h1>
            <div class="container">
                <div class="table-responsive"> 
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Registerd Date</td>
                            <td>Type</td>
                            <td>Control</td>
                        </tr>
<?php
                        foreach ($rows as $row) {
                            $type = privilege($row['GroupID']);
                            echo "<tr>";
                                echo "<td>" . $row['UserID'] . "</td>";
                                echo "<td>" . $row['Username'] . "</td>";
                                echo "<td>" . $row['Email'] . "</td>";
                                echo "<td>" . $row['FullName'] . "</td>";
                                echo "<td>" . $row['Date'] . "</td>";
                                echo "<td>" . $type . "</td>";
                                echo "<td>
                                    <a href='members.php?do=Approve&userID=" . $row['UserID'] . "'class='btn btn-success'><i class= 'fa fa-check'> Approve</i></a> 
                                    <a href='members.php?do=Delete&userID=" . $row['UserID'] . "'class='btn btn-danger confirm'><i class= 'fa fa-close'> Delete</i></a>                                     </td>";
                            echo "</tr>";
                        }
?>
                    </table>
                </div>
                <a href="members.php" class="btn btn-primary"><i class= "fa fa-arrow-circle-left" >  Back to members page</i></a>

            </div>
            

<?php
        } elseif ($do == 'Approve'){

                /*
                ===========================================
                == You can Approve New Members from here ==
                ===========================================
                */
                echo '<h1 class="text-center">Approve Member</h1>';
                echo '<div class="container">';

                // Check if get request userID is numeric & get the integer value

                $userid = isset($_GET['userID']) && is_numeric($_GET['userID']) ? intval($_GET['userID']) : 0;
            
                // Select all the data from this ID

                $check = checkItem('userid', 'users', $userid);

                
                // if there's such ID show the form
                if ($check > 0) { 
                    $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = $userid ");
                    $stmt->execute();

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Approved </div>";
                    redirect($theMsg,'back',0);
                } else {
                    $theMsg ='<div class="alert alert-danger">  Theres no such ID  </div>';
                    redirect($theMsg);
                }
            echo '</div>';

            
        
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