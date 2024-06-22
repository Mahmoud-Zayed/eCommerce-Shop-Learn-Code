<?php 
    /* 
    ----------------------------------------------------------------
    //// Manage Members Page 
    //// You Can Add | Edit | Delete | Insert | Update | Activate Members From Here
    ----------------------------------------------------------------
    */
    ob_start();
    session_start();
    $pageTitle = 'Members';
    if (isset($_SESSION['Username'])) {
        include 'init.php';
        $do = isset($_GET["do"]) ? $_GET["do"] : "Manage";

        // If The Page Is Main Page 
        // Manage Page 
        if ($do == "Manage") { 

            $query = "";
            if (isset($_GET['page']) && $_GET["page"] == "Pending") {
                $query = "AND RegStatus = 0";
            }

            // Select All Users Expect Admin
            $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
            // Execute The Statement
            $stmt->execute();
            // Assign To Variable
            $rows = $stmt->fetchAll();
        
            if (!empty($rows)) {
        
        ?>

            <h1 class="text-center">Manage Member</h1>
            <div class="container member-manage-page-container"> 
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered manage_members">
                        <tr>
                            <td>#ID</td>
                            <td>Avatar</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Regesterd Date</td>
                            <td>Control</td>
                        </tr>
                        <?php 
                            foreach($rows as $row) {
                                echo "<tr>";
                                    echo "<td>" . $row["UserID"] . "</td>";
                                    echo "<td>";
                                        if (empty($row['avatar'])) {
                                            echo "No Image";
                                        } else {
                                            echo "<img src='uploads/avatars/" . $row["avatar"] . "' alt='' />";
                                        }
                                    echo "</td>";
                                    echo "<td>" . $row["Username"] . "</td>";
                                    echo "<td>" . $row["Email"] . "</td>";
                                    echo "<td>" . $row["FullName"] . "</td>";
                                    echo "<td>" . $row["Date"] . "</td>";
                                    echo "  <td> 
                                                <a href='members.php?do=Edit&userid=" . $row['UserID'] . " ' class=\"btn btn-success\"><i class='fa fa-edit'></i> Edit </a>
                                                <a href='members.php?do=Delete&userid=" . $row['UserID'] . " ' class=\"btn bt-op btn-danger confirm\"><i class='fa fa-close'></i> Delete </a> ";
                                                if ($row['RegStatus'] == 0) {
                                                    echo "<a href='members.php?do=Activate&userid=" . $row['UserID'] . " ' class=\"btn btn-warning\"><i class='fa fa-check'></i> Activate </a>";
                                                }
                                    echo "</td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>
                <a href='members.php?do=Add' class="btn btn-primary"><i class="fa fa-plus"></i>  New Member</a>
            </div>
        <?php 
            } else {
                echo "<div class='container'>";
                echo "<div class='nice_msg alert alert-info'>There Is No Members To Show</div>";
                echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>';
                echo "</div>";
            }
        } 
        // Add Page 
        else if ($do == "Add") { ?>
            <h1 class="text-center">Add New Member</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Username</label>
                            <div class="col-sm-10">
                                <input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="Username To Login Into Shop">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Password</label>
                            <div class="col-sm-10">
                                <input type="password" name="password" class="password form-control"  required="required" autocomplete="new-password" placeholder="Password Must Be Hard & Complex">
                                <i class="show-pass fa fa-eye"></i>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Email</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" class="form-control" required="required" placeholder="Email Must Be Valid">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Full Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="fullname" class="form-control" required="required" placeholder="Full Name Apear In Your Profile Page">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">User Avatar</label>
                            <div class="col-sm-10">
                                <input type="file" name="avatar" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Add Member" class="btn btn-primary btn-lg">
                            </div>
                        </div>
                    </form>
                </div>
        <?php 
        } 
        // Insert Page 
        else if ($do == "Insert") {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                echo "<h1 class=\"text-center\">Insert Member</h1>";
                echo "<div class='container'>";

                // Upload Variables
                $avatar = $_FILES["avatar"];
                $avatarName = $avatar["name"];
                $avatarSize = $avatar["size"];
                $avatarTmpName = $avatar["tmp_name"];
                $avatarType = $avatar["type"];
                // List Of Allowed File Type To Upload
                $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");
                // Get Avatar Extension
                $avatarExtension = strtolower(end(explode(".", $avatarName)));

                // Get Variabels From The Form
                $user  = $_POST["username"];
                $pass  = $_POST["password"];
                $email = $_POST["email"];
                $name  = $_POST["fullname"];

                $hachPass = sha1($_POST["password"]);

                // Validate The Form 
                $formErrors = array();
                if (strlen($user) < 4) {
                    $formErrors[] = "Usaername Cant Be Less Than <strong>4 Characters</strong>";
                }
                if (strlen($user) > 20) {
                    $formErrors[] = "Usaername Cant Be More Than <strong>20 Characters</strong>";
                }
                if (empty($user)) {
                    $formErrors[] = "Username Cant Be <strong>Empty</strong>";
                }
                if (empty($pass)) {
                    $formErrors[] = "Password Cant Be <strong>Empty</strong>";
                }
                if (empty($name)) {
                    $formErrors[] = "Full Name Cant Be <strong>Empty</strong>";
                }
                if (empty($email)) {
                    $formErrors[] = "Email Cant Be <strong>Empty</strong>";
                }
                if (!empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
                    $formErrors[] = "This Extension Is Not <strong>Allowed</strong>";
                }
                if (empty($avatarName)) {
                    $formErrors[] = "Avatar Is <strong>Required</strong>";
                }
                if ($avatarSize > 4194304) {
                    $formErrors[] = "Avatar Can Not Be Larger Than <strong>4MB</strong>";
                }

                foreach($formErrors as $error) {
                    echo "<div class='alert alert-danger'>" . $error . "</div>";
                }
                // Check If There's No Error Proceed The Update Operation 
                if (empty($formErrors)) {
                    // Check If User Exist In Database 
                    $avatarDb = rand(0, 10000) . "_" . $avatarName;
                    move_uploaded_file($avatarTmpName, "uploads\avatars\\" . $avatarDb);
                    $check = checkItem("Username", "users", $user); 
                    if ($check == 1) { 
                        $theMsg = "<div class='alert alert-danger'>Sorry This User Is Exist</div>";
                        redirecHome($theMsg, "back");
                    } else {
                        // Insert User Info In Database
                        $stmt = $con->prepare(" INSERT INTO users(Username, Password, Email, FullName, RegStatus, Date, avatar) 
                                                VALUES(:zuser, :zpass, :zmail, :zname, 1, now(), :zavatar)");
                        $stmt->execute(array(
                            "zuser" => $user,
                            "zpass" => $hachPass,
                            "zmail" => $email,
                            "zname" => $name,
                            "zavatar" => $avatarDb
                        ));
                        // Echo Success Massage
                        echo "<div class='container'>";
                        $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . " Record Inserted</div>";
                        redirecHome($theMsg, "back");
                        echo "</div>";
                    }
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
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ?  intval($_GET['userid']) : 0;
            // Select All Data Depend On This ID
            $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
            // Execute Query
            $stmt->execute(array($userid));
            // Fetch The Data
            $row = $stmt->fetch();
            // The Row Count
            $count = $stmt->rowcount();
            // If There's Such ID Show The Form
            if ($count > 0) { ?>
                <h1 class="text-center">Edit Member</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="userid" value="<?php echo $userid ?>">
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Username</label>
                            <div class="col-sm-10">
                                <input type="text" name="username" class="form-control" value="<?php echo $row["Username"] ?>" autocomplete="off" required="required">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Password</label>
                            <div class="col-sm-10">
                                <input type="hidden" name="oldpassword" value="<?php echo $row["Password"] ?>">
                                <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Dont't Want To Change">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Email</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" value="<?php echo $row["Email"] ?>" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label" for="">Full Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="fullname"  value="<?php echo $row["FullName"] ?>" class="form-control" required="required">
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
            echo "<h1 class=\"text-center\">Update Member</h1>";
            echo "<div class='container'>";
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Get Variabels From The Form
                $id    = $_POST["userid"];
                $user  = $_POST["username"];
                $email = $_POST["email"];
                $name  = $_POST["fullname"];

                // Password Trick ===>>> Condition ? True : False;
                $pass = empty($_POST["newpassword"]) ? $_POST["oldpassword"] : sha1($_POST["newpassword"]);

                // Validate The Form 
                $formErrors = array();
                if (strlen($user) < 4) {
                    $formErrors[] = "Usaername Cant Be Less Than <strong>4 Characters</strong>";
                }
                if (strlen($user) > 20) {
                    $formErrors[] = "Usaername Cant Be More Than <strong>20 Characters</strong>";
                }
                if (empty($user)) {
                    $formErrors[] = "Username Cant Be <strong>Empty</strong>";
                }
                if (empty($name)) {
                    $formErrors[] = "Full Name Cant Be <strong>Empty</strong>";
                }
                if (empty($email)) {
                    $formErrors[] = "Email Cant Be <strong>Empty</strong>";
                }
                foreach($formErrors as $error) {
                    echo "<div class='alert alert-danger'>" . $error . "</div>";
                }
                // Check If There's No Error Proceed The Update Operation
                if (empty($formErrors)) {

                    $stmt22 = $con->prepare("SELECT 
                                                * 
                                            FROM 
                                                users 
                                            WHERE  
                                                Username = ? 
                                            AND 
                                                UserID != ?");
                    $stmt22->execute(array($user, $id));
                    $count = $stmt22->rowCount();
                    if ($count == 1) {
                        $theMsg = "<div class='alert alert-danger'>Sorry This User Is Exist</div>";
                        redirecHome($theMsg, "back");
                    } else {
                        // Update The Database With This Info
                        $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
                        $stmt->execute(array($user, $email, $name, $pass, $id));
                        // Echo Success Massage
                        $theMsg = "<div class='alert alert-success'>" . $stmt->rowcount() . " Record Updated</div>";
                        redirecHome($theMsg, "back");
                    }
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
            echo "<h1 class=\"text-center\">Delete Member</h1>";
            echo "<div class='container'>";
            // Check If Get Request UserId Is Numeric and Get The Integer Value Of It
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ?  intval($_GET['userid']) : 0;
            // Select All Data Depend On This ID
            $check = checkItem ("UserID", "users", $userid);
            // If There's Such ID Show The Form
            if ($check > 0) {
                $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
                $stmt->bindParam(":zuser", $userid);
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
        // page Activate 
        else if ($do == "Activate") {
            echo "<h1 class=\"text-center\">Activate Member</h1>";
            echo "<div class='container'>";
            // Check If Get Request UserId Is Numeric and Get The Integer Value Of It
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ?  intval($_GET['userid']) : 0;
            // Select All Data Depend On This ID
            $check = checkItem ("UserID", "users", $userid);
            // If There's Such ID Show The Form
            if ($check > 0) {
                $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
                $stmt->execute(array($userid));
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