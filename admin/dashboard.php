<?php 

    ob_start(); // Output Buffering Start

    session_start();
    if (isset($_SESSION['Username'])) {
        $pageTitle = 'Dashboard';
        include 'init.php';
        // Number Of Latest Users 
        $numUsers = 3;
        // Latest Users Array 
        $latestUsers = getLatest("*", "users", "UserID", $numUsers);

        $numItems = 6;
        $latestItems = getLatest("*", "items", "Item_ID", $numItems);

        $numComments = 4;


        ?>

        <div class="container home-stats text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                        <i class="fa fa-users"></i>
                        <div class="info">
                            Total Members
                            <span><a href="members.php"><?php echo countItems('UserID', 'users') ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            Pending Members
                            <span><a href="members.php?do=Manage&page=Pending">
                                <?php echo checkItem ('RegStatus', 'users', '0') ?>
                            </a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                        <i class="fa fa-tag"></i>
                        <div class="info">
                            Total Items
                            <span><a href="items.php"><?php echo countItems('Item_ID', 'items') ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments">
                        <i class="fa fa-comments"></i>
                        <div class="info">
                            Total Comments
                            <span><a href="comments.php"><?php echo countItems('c_id', 'comments') ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="latest">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-users"></i> Latest <?php echo $numUsers ?> Regesterd Users
                                <span class="pull-right toggle-info"><i class="fa fa-plus fa-lg"></i></span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                <?php 
                                    if (!empty($latestUsers)) {
                                        foreach($latestUsers as $user) {
                                        echo '<li>';
                                            echo $user['Username'];
                                            echo '<a href="members.php?do=Edit&userid=' . $user['UserID'] . '">';
                                            echo '<span class="btn btn-success pull-right">';
                                                echo '<i class="fa fa-edit"></i>Edit';
                                                if ($user['RegStatus'] == 0) {
                                                    echo "<a href='members.php?do=Activate&userid=" . $user['UserID'] . " ' class=\"btn btn-warning  pull-right activate\"><i class='fa fa-check'></i> Activate </a>";
                                                }
                                            echo '</span>';
                                            echo '</a>';
                                        echo '</li>';
                                        }
                                    } else {
                                        echo '<div class="nice_msg">There Is No Record To Show</div>';
                                    }
                                ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-tag"></i> Latest <?php echo $numItems ?> Items 
                                <span class="pull-right toggle-info"><i class="fa fa-plus fa-lg"></i></span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                <?php 
                                    if (!empty($latestItems)) {
                                        foreach($latestItems as $item) {
                                            echo '<li>';
                                                echo $item['Name'];
                                                echo '<a href="items.php?do=Edit&itemid=' . $item['Item_ID'] . '">';
                                                echo '<span class="btn btn-success pull-right">';
                                                    echo '<i class="fa fa-edit"></i>Edit';
                                                    if ($item['Approve'] == 0) {
                                                        echo "<a href='items.php?do=Approve&itemid=" . $item['Item_ID'] . " ' class=\"btn btn-warning  pull-right activate\"><i class='fa fa-check'></i> Approve </a>";
                                                    }
                                                echo '</span>';
                                                echo '</a>';
                                            echo '</li>';
                                        }
                                    } else {
                                        echo '<div class="nice_msg">There Is No Record To Show</div>';
                                    }
                                ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa-regular fa-comments"></i> 
                                Latest <?php echo $numComments ?> Comment
                                <span class="pull-right toggle-info"><i class="fa fa-plus fa-lg"></i></span>
                            </div>
                            <div class="panel-body">
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
                                                            ORDER BY 
                                                                c_id DESC
                                                            LIMIT
                                                                $numComments");
                                    // Execute The Statement 
                                    $stmt->execute();
                                    // Assign To Variable 
                                    $comments = $stmt->fetchAll(); 
                                    if (!empty($comments)) {
                                        foreach ($comments as $comment) {
                                            echo "<div class='comment-box'>"; 
                                                // echo    '<span class="member_n">
                                                //         <a href="members.php?do=Edit&userid=' . $comment['user_id'] . '">' . $comment['Member'] . 
                                                //         '</a></span>';
                                                echo    '<span class="member_n">' . $comment["Member"] . '</span>';
                                                echo "<span class='member_c'>" . $comment['comment'] . "</span>";
                                            echo "</div>";
                                        }
                                    } else {
                                        echo '<div class="nice_msg">There Is No Record To Show</div>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-tag"></i> Latest Items 
                                <span class="pull-right toggle-info"><i class="fa fa-plus fa-lg"></i></span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                <?php 
                                    foreach($latestItems as $item) {
                                        echo '<li>';
                                            echo $item['Name'];
                                            echo '<a href="items.php?do=Edit&itemid=' . $item['Item_ID'] . '">';
                                            echo '<span class="btn btn-success pull-right">';
                                                echo '<i class="fa fa-edit"></i>Edit';
                                                if ($item['Approve'] == 0) {
                                                    echo "<a href='items.php?do=Approve&itemid=" . $item['Item_ID'] . " ' class=\"btn btn-warning  pull-right activate\"><i class='fa fa-check'></i> Approve </a>";
                                                }
                                            echo '</span>';
                                            echo '</a>';
                                        echo '</li>';
                                    }
                                ?>
                                </ul>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        






        <?php
        include $tpl . "footer.php";
    } else {
        header("Location: index.php");
        exit();
    }

    ob_end_flush();

?>