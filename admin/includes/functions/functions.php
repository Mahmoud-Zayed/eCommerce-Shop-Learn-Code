<?php 
    /*
    // Title Function v1.0
    // That Echo The Page Title In Case Page
    // Has The Variable $pageTitle And Echo Defult Title For Other Pages
    */
    function getTitle() {
        global $pageTitle;
        if (isset($pageTitle)) {
            echo $pageTitle;
        }
        else {
            echo "Default";
        }
    }

    /* 
    // Home Redirect Function v2.0
    // This Function Accept Parameters
    // $theMsg = Echo The Message
    // $url = The Link You Want To Redirect To
    // $seconds = Seconds Before Redirecting 
    */
    function redirecHome($theMsg, $url = null, $seconds = 3) {
        if ($url === null) {
            $url = "index.php";
            $link = "Home Page";
        } else {
            if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== "") {
                $url = $_SERVER['HTTP_REFERER'];
                $link = "Previous Page";
            } else {
                $url = "index.php";
                $link = "Home Page";
            }
        }
        echo $theMsg;
        echo "<div class='alert alert-info'>You  Will Be Redirected To $link After $seconds Seconds</div>";
        header("refresh: $seconds; url=$url");
        exit();
    }

    /* 
    // Check Item Function v1.0
    // Function To Check Item In Database
    // $select = The Item To Select [ Example: user, item, category ]
    // $from = The Table To Select From [ Example: users, items, categories ]
    // $value = The Value Of Select [ Example: Mahmoud , Box, Electronics ]
    */
    function checkItem($select, $from, $value) {
        global $con;
        $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
        $statement->execute(array($value));
        $count = $statement->rowCount();
        return $count;
    }

    /* 
    // Count Numbers Of Items Function v1.0 
    // Function To Count Numbers Of Items Rows 
    // $item = The Item To Count 
    // $table = The Table To Choose From 
    */
    function countItems($item, $tabel) {
        global $con;
        $stmt2 = $con->prepare("SELECT COUNT($item) FROM $tabel");
        $stmt2->execute();
        return $stmt2->fetchColumn();
    }

    /*
    // Get Latest Records Function v1.0 
    // Function To Get Latest Items From Database [Users, Items, Comments] 
    // $select = Find To Select 
    // $table = The Table To Choose From 
    // $order = The Desc Ordaring 
    // $limit = Number Of Records To Get 
    */ 
    function getLatest($select, $table, $order, $limit = 5) {
        global $con;
        $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
        $getStmt->execute();
        $rows = $getStmt->fetchAll();
        return $rows;
    }





?>