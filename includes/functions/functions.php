<?php 
    

    /*
    // Get All Function v2.0 
    // Function To Get All From Any Database Table 
    */ 
    function getAllFrom($field, $table, $orderfieldd, $ordering = "DESC") {
        global $con;
        $getAll = $con->prepare("SELECT $field FROM $table ORDER BY $orderfieldd $ordering");
        $getAll->execute();
        $all = $getAll->fetchAll();
        return $all;
    }


    /*
    // Get Categories Function v1.0 
    // Function To Get Categories From Database 
    */ 
    function getCat() {
        global $con;
        $getCat = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");
        $getCat->execute();
        $cats = $getCat->fetchAll();
        return $cats;
    }


    /* 
    // Get AD Items Function v1.0 
    // Function To Get AD Items From Database 
    */ 
    function getItems($where, $value, $approve = NULL) { 
        global $con; 
        if ($approve == NULL) {
            $sql = "AND Approve = 1";
        } else {
            $sql = NULL;
        }
        $getItems = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY Item_ID DESC"); 
        $getItems->execute(array($value)); 
        $items = $getItems->fetchAll(); 
        return $items; 
    } 


    /*
    // Check If User Is Not Activated  
    // Function To Check The RegStatus Of The User 
    */
    function checkUserStatus ($user) {
        global $con;
        // Check If The User Exist In Database 
        $stmtx = $con->prepare("SELECT 
                                    Username, RegStatus 
                                FROM 
                                    users 
                                WHERE 
                                    Username = ? 
                                AND 
                                    RegStatus = 0 ");
        $stmtx->execute(array($user));
        $status = $stmtx->rowcount();
        return $status;
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
?>