<?php 
    /* 
    ----------------------------------------------------------------
    //// Manage Comments Page 
    //// You Can Edit | Delete | Approve Comments From Here
    ----------------------------------------------------------------
    */
    ob_start();
    session_start();
    $pageTitle = 'Comments';
    if (isset($_SESSION['Username'])) {
        include 'init.php';
        $do = isset($_GET["do"]) ? $_GET["do"] : "Manage";

        // If The Page Is Main Page 
        // Manage Page 
        if ($do == "Manage") { 


            // Select All Users Expect Admin
            $stmt = $con->prepare(" SELECT 
                                        comments.*, 
                                        items.Name AS Item_Name,
                                        users.Username AS Member
                                    FROM 
                                        comments
                                    INNER JOIN 
                                        items
                                    ON 
                                        items.Item_ID = comments.item_id
                                    INNER JOIN 
                                        users
                                    ON 
                                        users.UserID = comments.user_id
                                    ORDER BY 
                                        c_id DESC");
            // Execute The Statement
            $stmt->execute();
            // Assign To Variable
            $comments = $stmt->fetchAll();

            if (!empty($comments)) {
        
        ?>

            <h1 class="text-center">Manage Comments</h1>
            <div class="container member-manage-page-container"> 
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Comment</td>
                            <td>Item Name</td>
                            <td>Username</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>
                        <?php 
                            foreach($comments as $comment) {
                                echo "<tr>";
                                    echo "<td>" . $comment["c_id"] . "</td>";
                                    echo "<td>" . $comment["comment"] . "</td>";
                                    echo "<td>" . $comment["Item_Name"] . "</td>";
                                    echo "<td>" . $comment["Member"] . "</td>";
                                    echo "<td>" . $comment["comment_date"] . "</td>";
                                    echo "  <td> 
                                                <a href='comments.php?do=Edit&comid=" . $comment['c_id'] . " ' class=\"btn btn-success\"><i class='fa fa-edit'></i> Edit </a>
                                                <a href='comments.php?do=Delete&comid=" . $comment['c_id'] . " ' class=\"btn bt-op btn-danger confirm\"><i class='fa fa-close'></i> Delete </a> ";
                                                if ($comment['status'] == 0) {
                                                    echo "<a href='comments.php?do=Approve&comid=" . $comment['c_id'] . " ' class=\"btn btn-warning\"><i class='fa fa-check'></i> Approve </a>";
                                                }
                                    echo "</td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>
            </div>
        <?php 
            } else {
                    echo "<div class='container'>";
                    echo "<div class='nice_msg alert alert-info'>There Is No Comments To Show</div>";
                    echo "</div>";
                }
        } 
        // Edit Page 
        else if ($do == "Edit") {  
            // Check If Get Request UserId Is Numeric and Get The Integer Value Of It
            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ?  intval($_GET['comid']) : 0;
            // Select All Data Depend On This ID
            $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?");
            // Execute Query
            $stmt->execute(array($comid));
            // Fetch The Data
            $row = $stmt->fetch();
            // The Row Count
            $count = $stmt->rowcount();
            // If There's Such ID Show The Form
            if ($count > 0) { ?>
                <h1 class="text-center">Edit Comment</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="comid" value="<?php echo $comid ?>">
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Comment</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="comment" id=""><?php echo $row['comment'] ?></textarea>
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
        // page Update 
        else if ($do == 'Update') {
            echo "<h1 class=\"text-center\">Update Comment</h1>";
            echo "<div class='container'>";
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Get Variabels From The Form
                $comid   = $_POST["comid"];
                $comment  = $_POST["comment"];
                // Update The Database With This Info
                $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");
                $stmt->execute(array($comment, $comid));
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
        // page Delete 
        else if ($do == "Delete") {
            echo "<h1 class=\"text-center\">Delete Comment</h1>";
            echo "<div class='container'>";
            // Check If Get Request comid Is Numeric and Get The Integer Value Of It
            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ?  intval($_GET['comid']) : 0;
            // Select All Data Depend On This ID
            $check = checkItem ("c_id", "comments", $comid);
            // If There's Such ID Show The Form
            if ($check > 0) {
                $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zid");
                $stmt->bindParam(":zid", $comid);
                $stmt->execute();
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . " Record Deleted</div>";
                redirecHome($theMsg, "back");
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
            echo "<h1 class=\"text-center\">Approve Comment</h1>";
            echo "<div class='container'>";
            // Check If Get Request UserId Is Numeric and Get The Integer Value Of It
            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ?  intval($_GET['comid']) : 0;
            // Select All Data Depend On This ID
            $check = checkItem ("c_id", "comments", $comid);
            // If There's Such ID Show The Form 
            if ($check > 0) { 
                $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");
                $stmt->execute(array($comid));
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . " Record Approved</div>";
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