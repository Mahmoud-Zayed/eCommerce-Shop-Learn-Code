<?php 

    function lang($phrase) {
        static $lang = array (
            // Dashboard Page 
            'HOME_ADMIN' => 'Home',
            'CATEGORIES' => 'Categories',
            'ITEMS' => 'Items',
            'MEMBERS' => 'Members',
            'COMMENTS' => "Comments",
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => ''
        );
        return $lang[$phrase];
    }

?>