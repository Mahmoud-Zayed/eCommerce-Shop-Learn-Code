<?php 

    /* 
        Categories => [ Manage | Edit | Update | Add | Insert | Delete | Stats ]
        condation ? true : false;
    */

    $do = isset($_GET["do"]) ? $_GET["do"] : $do = "Manage";
    
    // If The Page Is Main Page
    if ($do == "Manage") {
        echo "Welcome You Are In Manage Category Page <br>";
        echo "<a href='?do=Add'>Add New Category  </a> <br>";
        echo "<a href='?do=Edit'>Edit Category  </a> <br>";
        echo "<a href='?do=Update'>Update Category </a> <br>";
        echo "<a href='?do=Insert'>Insert Category  </a> <br>";
        echo "<a href='?do=Delete'>Delete Category  </a> <br>";
        echo "<a href='?do=Stats'>Stats Category </a> <br>";
    } 
    // else if ($do == "Add") {
    //     echo "Welcome You Are In Add Category Page";
    // } 
    // else if ($do == "Edit") {
    //     echo "Welcome You Are In Edit Category Page";
    // } 
    // else if ($do == "Update") {
    //     echo "Welcome You Are In Update Category Page";
    // } 
    // else if ($do == "Insert") {
    //     echo "Welcome You Are In Insert Category Page";
    // } 
    // else if ($do == "Delete") {
    //     echo "Welcome You Are In Delete Category Page";
    // } 
    else if ($do == "Stats") {
        echo "Welcome You Are In Stats Category Page";
    } 
    else {
        echo "Error Page Not Found Name Page";
    }

?>