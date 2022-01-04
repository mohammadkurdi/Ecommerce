<?php
    ob_start();
    session_start();
    // Check if session exist
    if (isset($_SESSION ['Username'])){
        $pageTitle = 'Dashboard';       
        include 'init.php';
        
        $latestUsers = 5;
        $theLatestUsers = getLatest('*','users','UserID', $latestUsers) ;

        $theLatestPending = getLatest('*','users','UserID', $latestUsers, 'WHERE RegStatus = 0') ;


        /* Start dashboard page */
?>
        <div class = 'home-stats'>
            <div class = 'container text-center'>
                <h1>Dashboard</h1>
                <div class = 'row'>
                    <div class = 'col-md-3'>
                        <div class = 'stat st-members'>
                            Total members
                            <span><a href="members.php"><?php echo countItems('UserID','users');?></a></span>
                        </div>
                    </div>
                    <div class = 'col-md-3'>
                        <div class = 'stat st-pendings'>
                            Pending members
                            <span><a href="members.php?do=Pending"><?php echo countItems('UserID','users','WHERE RegStatus = 0');?></a></span>
                        </div>
                    </div>
                    <div class = 'col-md-3'>
                        <div class = 'stat st-items'>
                            Total items
                            <span>1500</span>
                        </div>
                    </div>
                    <div class = 'col-md-3'>
                        <div class = 'stat st-comments'>
                            Total comments
                            <span>3000</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class= 'latest'>
            <div class = 'container '>
                <div class = 'row'>
                    <div class = 'col-sm-6'>
                        <div class = 'panel panel-default'>
                            <div class = 'panel-heading'>
                                <i class = 'fa fa-users'></i> Latest <?php echo $latestUsers ?> registerd users
                            </div>
                            <div class = 'panel body'>
                                <ul class="list-unstyled latest-users">
<?php 
                                    foreach ($theLatestUsers as $user) { 
                                        echo "<li>" . $user['FullName'] ;
                                            echo "<a href='members.php?do=Edit&userID=" . $user['UserID'] . "'>";
                                                echo "<span class='btn btn-success pull-right'>";
                                                    echo "<i class='fa fa-edit'></i>  Edit";
                                                echo "</span>";
                                            echo "</a>";
                                        echo "</li>";
                                    }
?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class = 'col-sm-6'>
                        <div class = 'panel panel-default'>
                            <div class = 'panel-heading'>
                                <i class = 'fa fa-tag'></i> Latest items
                            </div>
                            <div class = 'panel body'>
                                Test
                            </div>
                        </div>
                    </div>
                </div>
                <div class = 'row'>
                    <div class = 'col-sm-6'>
                        <div class = 'panel panel-default'>
                            <div class = 'panel-heading'>
                                <i class = 'fa fa-thumb-tack'></i> Latest <?php echo $latestUsers ?> Pending users
                            </div>
                            <div class = 'panel body'>
                                <ul class="list-unstyled latest-users">
<?php 
                                    foreach ($theLatestPending as $user) { 
                                        echo "<li>" . $user['FullName'] ;
                                        echo "<a href='members.php?do=Delete&userID=" . $user['UserID'] . "'>";
                                            echo "<span class='btn btn-danger confirm pull-right'>";
                                                echo "<i class='fa fa-close'></i>  Delete";
                                            echo "</span>";
                                        echo "</a>";
                                        echo "<a href='members.php?do=Approve&userID=" . $user['UserID'] . "'>";
                                            echo "<span class='btn btn-success  pull-right'>";
                                                echo "<i class='fa fa-check'></i>  Approve";
                                            echo "</span>";
                                        echo "</a>";
                                    }
?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class = 'col-sm-6'>
                        <div class = 'panel panel-default'>
                            <div class = 'panel-heading'>
                                <i class = 'fa fa-tag'></i> Latest items
                            </div>
                            <div class = 'panel body'>
                                Test
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
        include $tpl . "footer.php";
    } else {
        header('Location: index.php');
        exit();
    }

    ob_end_flush();
?>