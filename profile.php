<?php 
    ob_start();
    session_start();
    $pageTitle = 'Profile';
    include "init.php";
    if (isset($_SESSION['user'])) {
        $getUser = $con->prepare('  SELECT
                                        *
                                    From 
                                        users
                                    WHERE 
                                        Username = ?');
        $getUser->execute(array($sessionUser));
        $info = $getUser->fetch();
        ?>
        <h1 class="text-center">My Profile</h1>
        <div class="info block">
            <div class="container">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        My Information
                    </div>
                    <div class="panel-body">
                        <ul class="list-unstyled">
                            <li>
                                <i class="fa fa-unlock-alt fa-fw"></i>
                                <span>Login Name:</span> <?php echo $info['Username']; ?> 
                            </li>
                            <li>
                                <i class="fa fa-envelope fa-fw"></i>
                                <span>Email:</span> <?php echo $info['Email']; ?> 
                            </li>
                            <li>
                                <i class="fa fa-user fa-fw"></i>
                                <span>FullName:</span> <?php echo $info['FullName']; ?> 
                            </li>
                            <li>
                                <i class="fa fa-calender fa-fw"></i>
                                <span>Register Date:</span> <?php echo $info['Date']; ?> 
                            </li>
                            <li>
                                <i class="fa fa-tags fa-fw"></i>
                                <span>Favourite Category:</span> <?php echo $info['UserID']; ?> 
                            </li> 
                        </ul>
                        <a href="#" class="btn btn-default my_btnn">
                            Edit Information
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div id="my_adss" class="my_ads block">
            <div class="container">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        My Ads
                    </div>
                    <div class="panel-body">
                        <?php 
                            $items = getItems('Member_ID', $info["UserID"]);
                            if (!empty($items)) {
                                echo '<div class="row">';
                                foreach (getItems('Member_ID', $info["UserID"], 1) as $item) {
                                    
                                    echo '<div class="col-sm-6 col-md-3">';
                                        echo '<div class="thumbnail item_box">';
                                            if ($item['Approve'] == 0) {
                                                echo "<span class='approve_status'>Waiting Approval</span>";
                                            };
                                            echo '<span class="price">pound ' . $item["Price"] . '</span>';
                                            echo '<img class="img-responsive" src="images/png.webp" alt="">';
                                            echo '<div class="caption">';
                                                echo '<h3><a href="items.php?itemid=' . $item['Item_ID'] .'">' . $item['Name'] . '</a></h3>';
                                                echo '<p class="pra">' . $item['Description']  . '</p>';
                                                echo '<p class="date">' . $item['Add_Date']  . '</p>';
                                            echo '</div>';
                                        echo '</div>';
                                    echo '</div>';
                                }
                                echo '</div>';
                            } else {
                                echo "There Is No Ads To Show, Create <a href='newad.php'>New Add</a>";
                            }
                        ?> 
                    </div>
                </div>
            </div>
        </div>

        <div class="my_comments block">
            <div class="container"> 
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Latest Comments
                    </div>
                    <div class="panel-body">
                        <?php 
                            // Select All Users Expect Admin
                            $stmt = $con->prepare(" SELECT 
                                                        comment
                                                    FROM 
                                                        comments
                                                    WHERE 
                                                        user_id = ?");
                            // Execute The Statement 
                            $stmt->execute(array($info["UserID"]));
                            // Assign To Variable 
                            $comments = $stmt->fetchAll(); 
                            if (!empty($comments)) {
                                foreach ($comments as $comment) {
                                    echo "<p>" . $comment['comment'] . "</p>";
                                }
                            } else {
                                echo "There Is No Comments To Show";
                            }
                        ?> 
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