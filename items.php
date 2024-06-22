<?php 
    ob_start();
    session_start();
    $pageTitle = 'Show Items';
    include "init.php";
    // Check If Get Request UserId Is Numeric and Get The Integer Value Of It
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ?  intval($_GET['itemid']) : 0;
    // Select All Data Depend On This ID
    $stmt = $con->prepare(" SELECT 
                                items.*, 
                                categories.Name AS Category_Name, 
                                users.Username AS Clint_Name
                            FROM 
                                items
                            INNER JOIN 
                                categories 
                            ON 
                                categories.ID = items.Cat_ID
                            INNER JOIN 
                                users 
                            ON 
                                users.UserID = items.Member_ID
                            WHERE 
                                Item_ID = ?
                            AND    
                                Approve = 1");
    // Execute Query
    $stmt->execute(array($itemid));
    $count = $stmt->rowcount();
    if ($count > 0) {
    // Fetch The Data
    $item = $stmt->fetch();
    
    ?>
    <h1 class="text-center"><?php echo $item["Name"]; ?></h1>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <img class="img-responsive img-thumbnail center-block" src="images/png.webp" alt="">
            </div>
            <div class="col-md-9 item-info">
                <h2><?php echo $item["Name"]; ?></h2>
                <p><?php echo $item["Description"]; ?></p>
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Added Date</span>: <?php echo $item["Add_Date"]; ?></li>
                    <li>
                        <i class="fa fa-money-bill fa-fw"></i>
                        <span>Price</span>: <?php echo $item["Price"]; ?></li>
                    <li>
                        <i class="fa fa-building fa-fw"></i>
                        <span>Made In</span>: <?php echo $item["Country_Made"]; ?></li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Category</span>: <a href="categories.php?pageid=' . <?php $item['Cat_ID']; ?>.  '"> <?php echo $item["Category_Name"]; ?> </a></li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Added By</span>: <a href="#"> <?php echo $item["Clint_Name"]; ?> </a></li>
                </ul>
            </div>
        </div>
        <hr class="custom-hr">
        <?php 
        if (isset($_SESSION['user'])) { 
        ?>
            <!-- Start Add Comment -->
            <div class="row">
                <div class="col-md-offset-3">
                    <div class="add_comment">
                        <h3>Add Your Comment</h3>
                        <form action="<?php echo $_SERVER['PHP_SELF'] . "?itemid=" . $item['Item_ID'] ?>" method="POST">
                            <textarea name="comment" id="" required></textarea>
                            <input class="btn btn-primary" type="submit" value="Add Comment">
                        </form>
                        <?php
                            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                                $comment   = filter_var($_POST["comment"], FILTER_SANITIZE_STRING);
                                $itemid    = $item['Item_ID'];
                                $userid    = $item["Member_ID"];
                                if (!empty($comment)) {
                                    $stmt = $con->prepare(" INSERT INTO 
                                                                comments(comment, status, comment_date, item_id, user_id)
                                                                VALUES(:zcomment, 0, NOW(), :zitem_id, :zuser_id)");
                                    $stmt->execute(array(
                                        'zcomment'   => $comment,
                                        'zitem_id'   => $itemid,
                                        'zuser_id'   => $userid
                                    ));
                                    if ($stmt) {
                                        echo "<div class='alert alert-success'>Comment Added</div>";
                                    }
                                }
                            } 
                        ?>
                    </div>
                </div>
            </div>
            <!-- End Add Comment -->
        <?php 
        } else {
            echo "<a href='login.php'>Login</a> Or <a href='login.php'>Regester</a> To Add Comment";
        }
        ?>
        <hr class="custom-hr">
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
                                    item_id = ?
                                AND 
                                    status = 1
                                ORDER BY 
                                    c_id DESC");
        // Execute The Statement
        $stmt->execute(array($item['Item_ID']));
        // Assign To Variable
        $comments = $stmt->fetchAll(); 
        ?>
        <?php
            foreach ($comments as $comment) {
                echo "<div class='comment_box'>";
                    echo '<div class="row">';
                        echo '<div class="col-sm-2 text-center">';
                            echo '<img class="img-responsive img-thumbnail img-circle" src="images/png.webp" alt="">';
                            echo $comment["Member"] . "<br>";
                        echo '</div>';
                        echo '<div class="col-sm-10">';
                            echo '<p class="lead">';
                                echo $comment["comment"] . "<br>";
                            echo '</p>';
                        echo '</div>';
                    echo '</div>';
                echo "</div>";
                echo "<hr class'custom-hr'>";
            }
        ?>
    </div> 
    <?php
    } else {
        echo "<div class='alert alert-danger'>There Is No Such ID Or This Item Is Waiting Approval</div>";
    }

    include $tpl . "footer.php"; 
    ob_end_flush();
?>