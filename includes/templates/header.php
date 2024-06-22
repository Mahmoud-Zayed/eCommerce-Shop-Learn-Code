<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poetsen+One&display=swap" rel="stylesheet">
        <title>Ecomerce Shop <?php echo getTitle() ?></title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">        <link rel="stylesheet" href="<?php echo $css ?>all.min.css">
        <link rel="stylesheet" href="<?php echo $css ?>frontend.css">
    </head>
<body>
    <div class="upper_bar">
        <div class="container">
            <?php 
            if (isset($_SESSION['user'])) {
            ?>
            <img class="img-responsive img-circle img-thumbnail my_avatar" src="images/png.webp" alt="">
            <div class="btn-group my-infoo">
                <span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <?php echo $sessionUser ?>
                <span class="caret"></span>
                </span>
                <ul class="dropdown-menu">
                    <li><a href="profile.php">My Profile</a></li>
                    <li><a href="newad.php">New Item</a></li>
                    <li><a href="profile.php#my_adss">My Items</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
            


            <?php
            } else { 
                ?>
                <a href="login.php">
                    <span class="pull-right ">Login & Signup</span>
                </a> 
                <?php 
            }
            ?>
        </div>
    </div>
    <nav class="navbar navbar-inverse">
        <div class="container">
            <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Home</a>
            </div>
            <div class="collapse navbar-collapse" id="app-nav">
            <ul class="nav navbar-nav navbar-right">
            <?php
            $categories = getCat();
            foreach ($categories as $cat) {
                echo '<li>
                        <a href="categories.php?pageid=' . $cat['ID'] .'">
                            ' . $cat['Name'] . '
                        </a>
                    </li>';
            } 
            ?>
            </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>





