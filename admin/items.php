<?php 
    /* 
    ----------------------------------------------------------------
    //// Manage Items Page 
    //// You Can Add | Edit | Delete | Insert | Update | Approve Members From Here 
    ----------------------------------------------------------------
    */ 
    ob_start();
    session_start();
    $pageTitle = 'Items';
    if (isset($_SESSION['Username'])) {
        include 'init.php';
        $do = isset($_GET["do"]) ? $_GET["do"] : "Manage";

        // If The Page Is Main Page // Manage Page 
        // Manage Page 
        if ($do == "Manage") { 
            // Select All Users Expect Admin
            $stmt = $con->prepare("SELECT 
                                        items.*, 
                                        categories.Name AS Category_Name, 
                                        users.Username AS Clint_Name
                                    FROM items
                                    INNER JOIN 
                                        categories 
                                    ON 
                                        categories.ID = items.Cat_ID
                                    INNER JOIN 
                                        users 
                                    ON 
                                        users.UserID = items.Member_ID
                                    ORDER BY Item_ID DESC");
            // Execute The Statement
            $stmt->execute();
            // Assign To Variable
            $items = $stmt->fetchAll();

            if (!empty($items)) {
        ?>

            <h1 class="text-center">Manage Items</h1>
            <div class="container member-manage-page-container"> 
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Price</td>
                            <td>Adding Date</td>
                            <td>Category</td>
                            <td>Username</td>
                            <td>Control</td>
                        </tr>
                        <?php 
                            foreach($items as $item) {
                                echo "<tr>";
                                    echo "<td>" . $item["Item_ID"] . "</td>";
                                    echo "<td>" . $item["Name"] . "</td>";
                                    echo "<td>" . $item["Description"] . "</td>";
                                    echo "<td>" . $item["Price"] . "</td>";
                                    echo "<td>" . $item["Add_Date"] . "</td>";
                                    echo "<td>" . $item["Category_Name"] . "</td>";
                                    echo "<td>" . $item["Clint_Name"] . "</td>";
                                    echo "  <td> 
                                                <a href='items.php?do=Edit&itemid=" . $item['Item_ID'] . " ' class=\"btn btn-success\"><i class='fa fa-edit'></i> Edit </a>
                                                <a href='items.php?do=Delete&itemid=" . $item['Item_ID'] . " ' class=\"btn bt-op btn-danger confirm\"><i class='fa fa-close'></i> Delete </a> ";
                                                if ($item['Approve'] == 0) {
                                                    echo "<a href='items.php?do=Approve&itemid=" . $item['Item_ID'] . " ' class=\"btn btn-warning\"><i class='fa fa-check'></i> Approve </a>";
                                                }
                                    echo "</td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>
                <a href='items.php?do=Add' class="btn btn-primary"><i class="fa fa-plus"></i>  New Item</a>
            </div>
        <?php 
            } else {
                echo "<div class='container'>";
                echo "<div class='nice_msg alert alert-info'>There Is No Items To Show</div>";
                echo '<a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>  New Item</a>';
                echo "</div>";
            }
        }
        // Add Page 
        else if ($do == "Add") { ?>
            <h1 class="text-center">Add New Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" for="">Name</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" placeholder="Name Of The Item">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" for="">Description</label>
                        <div class="col-sm-10">
                            <input type="text" name="description" class="form-control" placeholder="Describe The Item">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" for="">Price</label>
                        <div class="col-sm-10">
                            <input type="text" name="price" class="form-control" placeholder="Price The Item">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" for="">Country</label>
                        <div class="col-sm-10">
                            <input type="text" name="country" class="form-control" placeholder="Country Of Made">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" for="">Status</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="status"> 
                                <option value="0">...</option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4">Old</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" for="">Member</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="member"> 
                                <option value="0">...</option>
                                <?php 
                                    $stmt = $con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach($users as $user) {
                                        echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option><br>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" for="">Category</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="category"> 
                                <option value="0">...</option>
                                <?php 
                                    $stmt2 = $con->prepare("SELECT * FROM categories");
                                    $stmt2->execute();
                                    $cats = $stmt2->fetchAll();
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
        <?php
        } 
        // Insert Page 
        else if ($do == "Insert") {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                echo "<h1 class=\"text-center\">Insert Item</h1>";
                echo "<div class='container'>";
                // Get Variabels From The Form
                $name  = $_POST["name"];
                $desc  = $_POST["description"];
                $price = $_POST["price"];
                $country  = $_POST["country"];
                $status = $_POST["status"];
                $member = $_POST["member"];
                $category = $_POST["category"];


                // Validate The Form 
                $formErrors = array(); // Declaring a variable for an array
                if (empty($name)) { 
                    $formErrors[] = "Name Can't Be <strong>Empty</strong>";
                }
                if (empty($desc)) {
                    $formErrors[] = "Description Can't Be <strong>Empty</strong>";
                }
                if (empty($price)) {
                    $formErrors[] = "Price Can't Be <strong>Empty</strong>";
                }
                if (empty($country)) {
                    $formErrors[] = "Country Can't Be <strong>Empty</strong>";
                }
                if ($status == 0) {
                    $formErrors[] = "You Must Choose The <strong>Status</strong>";
                }
                if ($member == 0) {
                    $formErrors[] = "You Must Choose The <strong>Member</strong>";
                }
                if ($category == 0) {
                    $formErrors[] = "You Must Choose The <strong>Category</strong>";
                }
                foreach($formErrors as $error) {
                    echo "<div class='alert alert-danger'>" . $error . "</div>";
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
                        "zmember" => $member
                    ));
                    // Echo Success Massage
                    echo "<div class='container'>";
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . " Record Inserted</div>";
                    redirecHome($theMsg, "back");
                    echo "</div>";
                    
                }
            } else {
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>Sorry You Can't Browes This Page Directly</div>";
                redirecHome($theMsg);
                echo "</div>";
            }
            echo "</div>";
        } 
        // Edit Page 
        else if ($do == "Edit") {  
            // Check If Get Request UserId Is Numeric and Get The Integer Value Of It
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?  intval($_GET['itemid']) : 0;
            // Select All Data Depend On This ID
            $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
            // Execute Query
            $stmt->execute(array($itemid));
            // Fetch The Data
            $item = $stmt->fetch();
            // The Row Count
            $count = $stmt->rowcount();
            // If There's Such ID Show The Form
            if ($count > 0) { ?>
                <h1 class="text-center">Edit Items</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="itemid" value="<?php echo $itemid ?>">
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Name</label>
                            <div class="col-sm-10">
                                <input 
                                    type="text" 
                                    name="name" 
                                    class="form-control" 
                                    placeholder="Name Of The Item"
                                    value="<?php echo $item['Name'] ?>">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Description</label>
                            <div class="col-sm-10">
                                <input 
                                    type="text" 
                                    name="description" 
                                    class="form-control" 
                                    placeholder="Describe The Item"
                                    value="<?php echo $item['Description'] ?>">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Price</label>
                            <div class="col-sm-10">
                                <input 
                                    type="text" 
                                    name="price" 
                                    class="form-control" 
                                    placeholder="Price The Item"
                                    value="<?php echo $item['Price'] ?>">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Country</label>
                            <div class="col-sm-10">
                                <input 
                                    type="text" 
                                    name="country" 
                                    class="form-control" 
                                    placeholder="Country Of Made"
                                    value="<?php echo $item['Country_Made'] ?>">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Status</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="status"> 
                                    <option value="1" <?php if ($item['Status'] == 1) { echo 'selected';} ?>>New</option>
                                    <option value="2" <?php if ($item['Status'] == 2) { echo 'selected';} ?>>Like New</option>
                                    <option value="3" <?php if ($item['Status'] == 3) { echo 'selected';} ?>>Used</option>
                                    <option value="4" <?php if ($item['Status'] == 4) { echo 'selected';} ?>>Old</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Member</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="member"> 
                                    <?php 
                                        $stmt = $con->prepare("SELECT * FROM users");
                                        $stmt->execute();
                                        $users = $stmt->fetchAll();
                                        foreach($users as $user) {
                                            echo "<option value='" . $user['UserID'] . "'"; 
                                            if ($item['Member_ID'] == $user['UserID']) { echo 'selected';} 
                                            echo">" . $user['Username'] . "</option><br>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Category</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="category"> 
                                    <?php 
                                        $stmt2 = $con->prepare("SELECT * FROM categories");
                                        $stmt2->execute();
                                        $cats = $stmt2->fetchAll();
                                        foreach($cats as $cat) {
                                            echo "<option value='" . $cat['ID'] . "'"; 
                                            if ($item['Cat_ID'] == $cat['ID']) { echo 'selected';} 
                                            echo ">" . $cat['Name'] . "</option><br>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Save Data" class="btn btn-primary btn-lg">
                            </div>
                        </div>
                    </form>

                    <?php
                    // Select All Users Expect Admin
                    $stmt = $con->prepare(" SELECT 
                                                comments.*, 
                                                users.Username AS Member
                                            FROM 
                                                comments
                                            INNER JOIN 
                                                users
                                            ON 
                                                users.UserID = comments.user_id
                                            WHERE 
                                                item_id = ?");
                    // Execute The Statement 
                    $stmt->execute(array($itemid));
                    // Assign To Variable 
                    $rows = $stmt->fetchAll(); 

                    if (!empty($rows)) {

                        
                    ?>
                    <h1 class="text-center">Manage <?php echo $item['Name'] ?> Comments</h1>
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>Comment</td>
                                <td>Username</td>
                                <td>Added Date</td>
                                <td>Control</td>
                            </tr>
                            <?php 
                                foreach($rows as $row) {
                                    echo "<tr>";
                                        echo "<td>" . $row["comment"] . "</td>";
                                        echo "<td>" . $row["Member"] . "</td>";
                                        echo "<td>" . $row["comment_date"] . "</td>";
                                        echo "  <td> 
                                                    <a href='comments.php?do=Edit&comid=" . $row['c_id'] . " ' class=\"btn btn-success\"><i class='fa fa-edit'></i> Edit </a>
                                                    <a href='comments.php?do=Delete&comid=" . $row['c_id'] . " ' class=\"btn bt-op btn-danger confirm\"><i class='fa fa-close'></i> Delete </a> ";
                                                    if ($row['status'] == 0) {
                                                        echo "<a href='comments.php?do=Approve&comid=" . $row['c_id'] . " ' class=\"btn btn-warning\"><i class='fa fa-check'></i> Approve </a>";
                                                    }
                                        echo "</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </table>
                    </div>
                    <?php     } ?>
                </div>
            <?php 
            } 
            // If There's No Such ID Show Error Massage
            else {
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>There Is No Such ID</div>";
                redirecHome($theMsg, "back");
                echo "</div>";
            }
        }
        // page Update 
        else if ($do == 'Update') {
            echo "<h1 class=\"text-center\">Update Item</h1>";
            echo "<div class='container'>";
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Get Variabels From The Form
                $id    = $_POST["itemid"];
                $name  = $_POST["name"];
                $desc = $_POST["description"];
                $price  = $_POST["price"];
                $country  = $_POST["country"];
                $status  = $_POST["status"];
                $category = $_POST["category"];
                $member  = $_POST["member"];


                // Validate The Form 
                $formErrors = array(); // Declaring a variable for an array
                if (empty($name)) { 
                    $formErrors[] = "Name Can't Be <strong>Empty</strong>";
                }
                if (empty($desc)) {
                    $formErrors[] = "Description Can't Be <strong>Empty</strong>";
                }
                if (empty($price)) {
                    $formErrors[] = "Price Can't Be <strong>Empty</strong>";
                }
                if (empty($country)) {
                    $formErrors[] = "Country Can't Be <strong>Empty</strong>";
                }
                if ($status == 0) {
                    $formErrors[] = "You Must Choose The <strong>Status</strong>";
                }
                if ($member == 0) {
                    $formErrors[] = "You Must Choose The <strong>Member</strong>";
                }
                if ($category == 0) {
                    $formErrors[] = "You Must Choose The <strong>Category</strong>";
                }
                foreach($formErrors as $error) {
                    echo "<div class='alert alert-danger'>" . $error . "</div>";
                }


                // Check If There's No Error Proceed The Update Operation
                if (empty($formErrors)) {
                    // Update The Database With This Info
                    $stmt = $con->prepare(" UPDATE 
                                                items 
                                            SET Name = ?, 
                                                Description = ?, 
                                                Price = ?, 
                                                Country_Made = ?,
                                                Status = ?, 
                                                Cat_ID = ?, 
                                                Member_ID = ? 
                                            WHERE 
                                                Item_ID = ?");
                    $stmt->execute(array($name, $desc, $price, $country, $status, $category, $member, $id));
                    // Echo Success Massage
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . " Record Updated</div>";
                    redirecHome($theMsg, "back");
                
                }


            } else {
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>Sorry You Can't Browse This Page Directly</div>";
                redirecHome($theMsg);
                echo "</div>";
            }
            echo "</div>";
        } 
        // page Delete 
        else if ($do == "Delete") {
            echo "<h1 class=\"text-center\">Delete Item</h1>";
            echo "<div class='container'>";
            // Check If Get Request UserId Is Numeric and Get The Integer Value Of It
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?  intval($_GET['itemid']) : 0;
            // Select All Data Depend On This ID
            $check = checkItem ("Item_ID", "items", $itemid);
            // If There's Such ID Show The Form
            if ($check > 0) {
                $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zid");
                $stmt->bindParam(":zid", $itemid);
                $stmt->execute();
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . " Record Deleted</div>";
                redirecHome($theMsg, 'back');
            } else {
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>This Id Is Not Exist</div>";
                redirecHome($theMsg);
                echo "</div>";
            }
            echo "</div>";

        }  
        // Approve Page 
        else if ($do == "Approve") { 
            echo "<h1 class=\"text-center\">Approve Item</h1>";
            echo "<div class='container'>";
            // Check If Get Request UserId Is Numeric and Get The Integer Value Of It
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?  intval($_GET['itemid']) : 0;
            // Select All Data Depend On This ID
            $check = checkItem ("Item_ID", "items", $itemid);
            // If There's Such ID Show The Form
            if ($check > 0) { 
                $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");
                $stmt->execute(array($itemid));
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . " Record Updated</div>";
                redirecHome($theMsg, "back");
            } else { 
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>This Id Is Not Exist</div>";
                redirecHome($theMsg);
                echo "</div>";
            }
            echo "</div>";
        } 



        include $tpl . "footer.php"; 
    } 
    else { 
        header("Location: index.php"); 
        exit(); 
    } 
    ob_end_flush();
?>

