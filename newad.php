<?php 
    ob_start();
    session_start();
    $pageTitle = 'Create New Add';
    include "init.php";
    if (isset($_SESSION['user'])) {

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $formErrors      = array();
            $name           = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc            = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $price           = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            $country         = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
            $status          = filter_var( $_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $category        = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
            if (strlen($name) < 4) {
                $formErrors[] = "Item name Must Be At Least 4 Characters";
            }
            if (strlen($desc) < 10) {
                $formErrors[] = "Item description Must Be At Least 10 Characters";
            }
            if (strlen($country) < 2) {
                $formErrors[] = "Item country Must Be At Least 2 Characters";
            }
            if (empty($price)) {
                $formErrors[] = "Item price Must Be Not Empty";
            }
            if (empty($status)) {
                $formErrors[] = "Item status Must Be Not Empty";
            }
            if (empty($category)) {
                $formErrors[] = "Item category Must Be Not Empty";
            }
            // Check If There's No Error Proceed The Update Operation 
            if (empty($formErrors)) {
                // Insert User Info In Database
                $stmt = $con->prepare(" INSERT INTO 
                                        items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID) 
                                        VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember)");
                $stmt->execute(array(
                    "zname" => $name,
                    "zdesc" => $desc,
                    "zprice" => $price,
                    "zcountry" => $country,
                    "zstatus" => $status,
                    "zcat" => $category,
                    "zmember" => $_SESSION['uid']
                ));
                // Echo Success Massage
                echo "<div class='container'>";
                if ($stmt) {
                    $successMsg = "Item Added";
                }
                echo "</div>";
                
            }
        }

        ?>
            <h1 class="text-center">Create New Add</h1>
            <div class="create_add block">
                <div class="container">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Create New Add
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <form class="form-horizontal main_form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-2 control-label" for="">Name</label>
                                            <div class="col-sm-10">
                                                <input
                                                    pattern=".{4,}"
                                                    title="This Field Require At Least 4 Characters"
                                                    type="text" name="name" data-class=".live_title" class="form-control live" placeholder="Name Of The Item"
                                                    required
                                                    >
                                                <span class="asterisk">*</span>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-2 control-label" for="">Description</label>
                                            <div class="col-sm-10">
                                                <input 
                                                    pattern=".{10,}"
                                                    title="This Field Require At Least 10 Characters"
                                                    required
                                                    type="text" name="description" data-class=".live_desc" class="form-control live" placeholder="Describe The Item">
                                                <span class="asterisk">*</span>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-2 control-label" for="">Price</label>
                                            <div class="col-sm-10">
                                                <input required type="text" name="price" class="form-control live-price" placeholder="Price The Item">
                                                <span class="asterisk">*</span>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-2 control-label" for="">Country</label>
                                            <div class="col-sm-10">
                                                <input required type="text" name="country" class="form-control" placeholder="Country Of Made">
                                                <span class="asterisk">*</span>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-2 control-label" for="">Status</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="status" required> 
                                                    <option value="">...</option>
                                                    <option value="1">New</option>
                                                    <option value="2">Like New</option>
                                                    <option value="3">Used</option>
                                                    <option value="4">Old</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-lg">
                                            <label class="col-sm-2 control-label" for="">Category</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="category" required> 
                                                    <option value="">...</option>
                                                    <?php 
                                                        $cats = getAllFrom('*', 'categories', 'ID');
                                                        foreach($cats as $cat) {
                                                            echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option><br>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-lg">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <input type="submit" value="Add Item" class="btn btn-primary btn-lg">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                        <div class="thumbnail item_box live-preview">
                                            <span class="price">$ 0</span>
                                            <img src="images/png.webp" alt="">
                                            <div class="caption">
                                                <h3 class="live_title">Title</h3>
                                                <p class="live_desc">Description</p>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <!-- Start Looping Errors -->
                            <?php 
                                if (!empty($formErrors)) {
                                    foreach ($formErrors as $error) {
                                        echo '<div class="alert alert-danger">' . $error . '</div>';
                                    }
                                }
                                if (isset($successMsg)) {
                                    echo "<div class='alert alert-success'>" . $successMsg . "</div>";
                                }
                            ?>
                            <!-- End Looping Errors -->
                        </div>
                    </div>
                </div>
            </div>
        <?php
    } else {
        header('Location: login.php');
        exit();
    }

    include $tpl . "footer.php"; 
    ob_end_flush();
?>