<?php 
    /* 
    ----------------------------------------------------------------
    //// Category Page 
    ----------------------------------------------------------------
    */
    ob_start();
    session_start();
    $pageTitle = 'categories';
    if (isset($_SESSION['Username'])) {
        include 'init.php';

        $do = isset($_GET["do"]) ? $_GET["do"] : "Manage";

        // If The Page Is Main Page // Manage Page 
        if ($do == "Manage") { 
            $sort = "ASC";
            $sort_array = array('ASC', 'DESC');
            if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
                $sort = $_GET['sort'];
            }
            $stmt = $con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
            $stmt->execute();
            $cats = $stmt->fetchAll(); 



            if (!empty($cats)) {
        ?>

            <h1 class="text-center">Manage Categories</h1>
            <div class="container categories">
                <div class="panel panel-default">
                    <div class="panel-heading">Manage Categories
                        <div class="orrr pull-right">
                            [<a class="<?php if($sort == 'ASC') {echo 'active';} ?>" href="?sort=ASC">Asc</a> | 
                            <a class="<?php if($sort == 'DESC') {echo 'active';} ?>" href="?sort=DESC">Desc</a>]
                            [<span class="active" data-view="full">Full</span> | 
                            <span data-view="classic">Classic</span>]
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php 
                            foreach($cats as $cat) {
                                echo "<div class='cat'>";
                                    echo "<div class='hidden-buttons'>";
                                        echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i>Edit</a>";
                                        echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i>Delete</a>";
                                    echo "</div>";
                                    echo "<h3>" . $cat["Name"] . "</h3>";
                                    echo "<div class='full-view'>";
                                        echo "<p>"; if($cat["Description"] == "") {echo "This Category Has No Description";} else {echo $cat["Description"];}; 
                                        echo "</p>";
                                        if($cat["Visibility"] == 1) {echo "<span class='visibility'><i class='fa fa-eye'></i>Hidden</span> <br>";};
                                        if($cat["Allow_Comment"] == 1) {echo "<span class='comment'><i class='fa fa-close'></i>Comment Disabled</span> <br>";};
                                        if($cat["Allow_Ads"] == 1) {echo "<span class='advertises'><i class='fa fa-close'></i>Ads Disabled</span>";};
                                    echo "</div>";
                                echo "</div>";
                                echo "<hr style='height:2px; border-width:0; color:gray; background-color:gray'>";
                            }
                        ?>
                    </div>
                </div>
                <a class="add_category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i>Add New Category</a>
            </div>



            <?php 
            } else {
                echo "<div class='container'>";
                echo "<div class='nice_msg alert alert-info'>There Is No Items To Show</div>";
                echo '<a href="categories.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>  New Category</a>';
                echo "</div>";
            }
        } 
        // Add Page 
        else if ($do == "Add") { ?>
            <h1 class="text-center">Add New Category</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" for="">Name</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name Of The Category">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" for="">Description</label>
                        <div class="col-sm-10">
                            <input type="text" name="description" class="form-control" placeholder="Describe The Category">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" for="">Ordering</label>
                        <div class="col-sm-10">
                            <input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories">
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" for="">Visible</label>
                        <div class="col-sm-10">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" checked>
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1">
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" for="">Allow Commenting</label>
                        <div class="col-sm-10">
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" checked>
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="commenting" value="1">
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label" for="">Allow Ads</label>
                        <div class="col-sm-10">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" checked>
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1">
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Category" class="btn btn-primary btn-lg">
                        </div>
                    </div>
                </form>
            </div>
        <?php
        } 
        // Insert Page 
        else if ($do == "Insert") { 
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                echo "<h1 class=\"text-center\">Insert Category</h1>";
                echo "<div class='container'>";

                // Get Variabels From The Form
                $name     = $_POST["name"];
                $disc     = $_POST["description"];
                $order    = $_POST["ordering"];
                $visible  = $_POST["visibility"];
                $comment  = $_POST["commenting"];
                $ads      = $_POST["ads"];

                // Check If Category Exist In Database  
                $check = checkItem("Name", "categories", $name); 
                if ($check == 1) { 
                    $theMsg = "<div class='alert alert-danger'>Sorry This Category Is Exist</div>";
                    redirecHome($theMsg, "back");
                } else {
                    // Insert User Info In Database
                    $stmt = $con->prepare(" INSERT INTO 
                                    categories(Name, Description, Ordering, Visibility, Allow_Comment, Allow_Ads) 
                                    VALUES(:zname, :zdesc, :zorder, :zvisible, :zcomment, :zads)");
                    $stmt->execute(array(
                        "zname"    => $name,
                        "zdesc"    => $disc,
                        "zorder"   => $order,
                        "zvisible" => $visible,
                        "zcomment" => $comment,
                        "zads"     => $ads
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
                redirecHome($theMsg, "back");
                echo "</div>";
            }
            echo "</div>";
        } 
        // Edit Page 
        else if ($do == "Edit") { 
            // Check If Get Request catid Is Numeric and Get The Integer Value Of It
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ?  intval($_GET['catid']) : 0;
            // Select All Data Depend On This ID
            $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");
            // Execute Query
            $stmt->execute(array($catid ));
            // Fetch The Data
            $cat = $stmt->fetch();
            // The Row Count
            $count = $stmt->rowcount();
            // If There's Such ID Show The Form
            if ($count > 0) { ?>
                <h1 class="text-center">Edit Category</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="catid" value="<?php echo $catid ?>">
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" required="required" placeholder="Name Of The Category" value="<?php echo $cat['Name'] ?>">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Description</label>
                            <div class="col-sm-10">
                                <input type="text" name="description" class="form-control" placeholder="Describe The Category" value="<?php echo $cat['Description'] ?>">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Ordering</label>
                            <div class="col-sm-10">
                                <input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories" value="<?php echo $cat['Ordering'] ?>">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Visible</label>
                            <div class="col-sm-10">
                                <div>
                                    <input id="vis-yes" type="radio" name="visibility" value="0" <?php if($cat['Visibility'] == 0) {echo "checked";} ?>>
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visibility" value="1" <?php if($cat['Visibility'] == 1) {echo "checked";} ?>>
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Allow Commenting</label>
                            <div class="col-sm-10">
                                <div>
                                    <input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment'] == 0) {echo "checked";} ?>>
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment'] == 1) {echo "checked";} ?>>
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Allow Ads</label>
                            <div class="col-sm-10">
                                <div>
                                    <input id="ads-yes" type="radio" name="ads" value="0"  <?php if($cat['Allow_Ads'] == 0) {echo "checked";} ?>>
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="ads" value="1"  <?php if($cat['Allow_Ads'] == 1) {echo "checked";} ?>>
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Save Data" class="btn btn-primary btn-lg">
                            </div>
                        </div>
                    </form>
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
        // Update Page 
        else if ($do == "Update") { 
            echo "<h1 class=\"text-center\">Update Category</h1>";
            echo "<div class='container'>";
            if ($_SERVER["REQUEST_METHOD"] == "POST") { 
                // Get Variabels From The Form 
                $id    = $_POST["catid"]; 
                $name  = $_POST["name"]; 
                $desc = $_POST["description"]; 
                $order  = $_POST["ordering"]; 

                $vis  = $_POST["visibility"]; 
                $com  = $_POST["commenting"]; 
                $ads  = $_POST["ads"]; 

                // Update The Database With This Info
                $stmt = $con->prepare("UPDATE 
                                            categories 
                                        SET 
                                            Name = ?,
                                            Description = ?, 
                                            Ordering = ?, 
                                            Visibility = ?, 
                                            Allow_Comment = ?, 
                                            Allow_Ads = ? 
                                        WHERE ID = ?");
                $stmt->execute(array($name, $desc, $order, $vis, $com, $ads, $id));
                // Echo Success Massage
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . " Record Updated</div>";
                redirecHome($theMsg, "back");


            } else {
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>Sorry You Can't Browse This Page Directly</div>";
                redirecHome($theMsg);
                echo "</div>";
            }
            echo "</div>";
        } 
        // Delete Page 
        else if ($do == "Delete") { 
            echo "<h1 class=\"text-center\">Delete Category</h1>";
            echo "<div class='container'>";
            // Check If Get Request UserId Is Numeric and Get The Integer Value Of It
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ?  intval($_GET['catid']) : 0;
            // Select All Data Depend On This ID
            $check = checkItem ("ID", "categories", $catid);
            // If There's Such ID Show The Form
            if ($check > 0) {
                $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
                $stmt->bindParam(":zid", $catid);
                $stmt->execute();
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . " Record Deleted</div>";
                redirecHome($theMsg, "back");
            } else {
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>This Id Is Not Exist</div>";
                redirecHome($theMsg, "back");
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

