<?php
    /* 
    ==================================================
    == Manage Category page.                         
    ==================================================
    */
    ob_start();
    session_start();
    $pageTitle = 'Categories';

    // Check if session exist
    if (isset($_SESSION['Username'])){
        include 'init.php';

        // Start manage page

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        if($do == 'Manage') { 
            $sort = 'ASC';
            $sort_array= array('ASC','DESC');
            if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){
                $sort=$_GET['sort'];
            }
            $stmt = $con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
            $stmt->execute();
            $cats = $stmt->fetchAll();
?>
            <h1 class="text-center">Manage Categories</h1>
            <div class="container categories">
                <div class="panel panel-default">
                    <div class="panel-heading ">
                        Manage Categories
                        <div class="ordering pull-right">
                            <span class="sort"></span><a href="?sort=ASC" ><i class='glyphicon glyphicon-sort'></i></a>
                            <a href="?sort=DESC"><i class='fa fa-sort-desc fa-lg'></i></a>
                        </div>
                    </div>
                    <div class="panel-body">
<?php
                        foreach($cats as $cat){
                            echo "<div class='cat'>";
                                echo "<div class='hidden-buttons'>";
                                    echo "<a href='categories.php?do=Edit&id=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                                    echo "<a href='categories.php?do=Delete&id=" . $cat['ID'] . "' class='btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
                                echo "</div>";
                                echo "<h3>" . $cat['Name'] . "</h3>";
                                echo "<div class='full-view'>";
                                    echo "<p>" . $cat['Description'] . "</p>";
                                    if($cat['Visibility'] == 1){echo "<span class='visibility'>Hidden</span>";};
                                    if($cat['Allow_comment'] == 1){echo "<span class='commenting'>Comments Disabled</span>";};
                                    if($cat['Visibility'] == 1){echo "<span class='advertises'>Ads Disabled</span>";};
                                echo "</div>";
                            echo "</div>";
                            echo "<hr>";
                        }
?>
                    </div>
                </div>
                <a href="categories.php?do=Add" class="add-category btn btn-primary"><i class="fa fa-plus"></i> Add New Category</a>
            </div>
<?php
        } elseif ($do == 'Add') {
            /*
            ====================
            ==Add New Category==
            ====================
            */
?>
            <h1 class="text-center">Add New Category</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!-- start name field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="name" class="form-control" required='required' placeholder="Name of the category"/>
                        </div>
                    </div>
                    <!-- end name field -->

                    <!-- start description field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="description" class="form-control" required='required' placeholder="Descripe the category"/>
                        </div>
                    </div>
                    <!-- end description field -->

                    <!-- start ordering field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="ordering" class="form-control" placeholder="Number to Arrange the categories"/>
                        </div>
                    </div>
                    <!-- end ordering field -->

                    <!-- start visibility field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">visible</label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" checked /> 
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1" /> 
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- end visibility field -->
                    
                    <!-- start commenting field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow commenting</label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" checked /> 
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="commenting" value="1" /> 
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!--end commenting field -->

                    <!-- start  Ads field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Ads</label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="Ads-yes" type="radio" name="ads" value="0" checked /> 
                                <label for="Ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="Ads-no" type="radio" name="ads" value="1" /> 
                                <label for="Ads-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- end  Ads field -->

                    <!-- start submit field -->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add new category" class="btn btn-primary btn-lg"/>
                        </div>
                    </div>
                    <!-- end submit field -->
                </form>
            </div>


<?php
        } elseif ($do == 'Insert') { //Insert Page
            /*
            =======================
            ==Insert new category==
            =======================
            */
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo '<h1 class="text-center">Insert Category</h1>';
                echo '<div class="container">';
                // Get variables from the form
                $name  = $_POST['name'];
                $desc  = $_POST['description'];
                $order = $_POST['ordering'];
                $visi  = $_POST['visibility'];
                $comm  = $_POST['commenting'];
                $ads   = $_POST['ads'];

                // Validate

                if (strlen($name) < 2 || strlen($name) > 11){
                    $error =  'Category name should be <strong>between 2 and 11 char</strong>';
                    $theMsg =  '<div class="alert alert-danger">' . $error . '</div>' . '<br/>';
                    redirect($theMsg,'back');
                }                


                //Check if user exist
                $check = checkItem("Name", "categories", $name);
                if ($check == 1){
                    $theMsg = '<div class="alert alert-danger">  Sorry this category already exist  </div>';
                    redirect($theMsg,'back');
                } else {
                // Insert the data base with this info
                    $stmt = $con->prepare("INSERT INTO
                                        categories(Name, Description, Ordaring, Visibility, Allow_comment, Allow_Ads)
                                        VALUES(:name, :desc, :order, :visi, :comm, :ads)");
                    $stmt->execute(array(
                        'name'  =>  $name, 
                        'desc'  =>  $desc, 
                        'order' =>  $order, 
                        'visi'  =>  $visi,
                        'comm'  =>  $comm,
                        'ads'  =>  $ads,
                    ));

                //echo success message
                $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted </div>";
                redirect($theMsg,'categories.php');               
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
            =================
            ==Edit category==
            =================
            */
            $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

            // Select all the data from this ID

            $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ? LIMIT 1");
            $stmt->execute(array($id));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();
            
            // if there's such ID show the form

            if ($stmt->rowCount() > 0) { 
                ?>
                <h1 class="text-center">Edit Category</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <!-- start username field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="hidden" name="oldname" class="form-control" value="<?php echo $row['Name']; ?>" required="required"/>
                                <input type="text" name="name" class="form-control" value="<?php echo $row['Name']; ?>" required="required"/>
                            </div>
                        </div>
                        <!-- end username field -->

                        <!-- start description field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="description" class="form-control" required='required' value="<?php echo $row['Description']; ?>"/>
                            </div>
                        </div>
                        <!-- end description field -->

                        <!-- start ordering field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Ordering</label>
                            <div class="col-sm-10 col-md-4">
                                <input type="text" name="ordering" class="form-control" value="<?php echo $row['Ordering']; ?>"/>
                            </div>
                        </div>
                        <!-- end ordering field -->

                        <!-- start visibility field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">visible</label>
                            <div class="col-sm-10 col-md-4">
                                    <div>
                                        <input id="vis-yes" type="radio" name="visibility" value="0" <?php if($row['Visibility'] == 0){echo 'checked';} ?> /> 
                                        <label for="vis-yes">Yes</label>
                                    </div>
                                    <div>
                                        <input id="vis-no" type="radio" name="visibility" value="1" <?php if($row['Visibility'] == 1){echo 'checked';} ?>/> 
                                        <label for="vis-no">No</label>
                                    </div> 
                            </div>
                        </div>
                        <!-- end visibility field -->

                        <!-- start commenting field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Allow commenting</label>
                            <div class="col-sm-10 col-md-4">
                                    <div>
                                        <input id="vis-yes" type="radio" name="commenting" value="0" <?php if($row['Allow_comment'] == 0){echo 'checked';} ?> /> 
                                        <label for="vis-yes">Yes</label>
                                    </div>
                                    <div>
                                        <input id="vis-no" type="radio" name="commenting" value="1" <?php if($row['Allow_comment'] == 1){echo 'checked';} ?>/> 
                                        <label for="vis-no">No</label>
                                    </div> 

                            </div>
                        </div>
                        <!-- end commenting field -->

                        <!-- start Ads field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Allow Ads</label>
                            <div class="col-sm-10 col-md-4">
                                    <div>
                                        <input id="vis-yes" type="radio" name="ads" value="0" <?php if($row['Allow_Ads'] == 0){echo 'checked';} ?> /> 
                                        <label for="vis-yes">Yes</label>
                                    </div>
                                    <div>
                                        <input id="vis-no" type="radio" name="ads" value="1" <?php if($row['Allow_Ads'] == 1){echo 'checked';} ?>/> 
                                        <label for="vis-no">No</label>
                                    </div> 

                            </div>
                        </div>
                        <!-- end Ads field -->

                        <!-- start submit field -->
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Edit category" class="btn btn-primary btn-lg"/>
                            </div>
                        </div>
                        <!-- end submit field -->
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


        } elseif ($do == 'Update') { // Update page
            /*
            ===================
            ==Update category==
            ===================
            */
            echo '<h1 class="text-center">Update Category</h1>';
            echo '<div class="container">';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Get variables from the form
                $id    = $_POST['id'];
                $name  = $_POST['name'];
                $desc  = $_POST['description'];
                $order = $_POST['ordering'];
                $visi  = $_POST['visibility'];
                $comm  = $_POST['commenting'];
                $ads   = $_POST['ads'];

                // Validate
                $formErrors = array();


                if (strlen($name) < 2 || strlen($name) > 11){
                    $error =  'Category name should be <strong>between 2 and 11 char</strong>';
                    $theMsg =  '<div class="alert alert-danger">' . $error . '</div>' . '<br/>';
                    redirect($theMsg,'back');
                }                

                //Check if user exist
                $check = checkItem("Name", "categories", $name);
                if ($check == 1 && $name != $_POST['oldname']){
                    $theMsg = '<div class="alert alert-danger">  Sorry this category already exist  </div>';
                    redirect($theMsg,'back');
                } else {                    
                // Update the data base with this info
                $stmt = $con->prepare("UPDATE categories SET Name = ?, Description = ?, Ordering = ?, Visibility = ?, Allow_comment = ?, Allow_Ads = ? WHERE id = ? ");
                $stmt->execute(array($name, $desc, $order, $visi, $comm, $ads, $id ));

                //echo success message
                $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated </div>";
                redirect($theMsg,'categories.php');
                }
            echo "</div>";
            } else {
                $theMsg ='<div class="alert alert-danger">  Sorry you can\'t browse this page directly  </div>';
                redirect($theMsg);
            }
        } elseif ($do == 'Delete') { // Delete member page
            /*
            ===================
            ==Delete category==
            ===================
            */
            echo '<h1 class="text-center">Delete Category</h1>';
            echo '<div class="container">';

            // Check if get request userID is numeric & get the integer value

            $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
        
            // Select all the data from this ID

            $check = checkItem("ID", "categories", $id);

            
            // if there's such ID show the form
            if ($check > 0) { 
                $stmt=$con->prepare("Delete FROM categories WHERE  ID = :id");
                $stmt->bindParam(":id", $id);
                $stmt->execute();

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Deleted </div>";
                redirect($theMsg,'categories.php');
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