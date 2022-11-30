<?php
    define('PAGE_PER_RECA_MUN', 20);
    function getPagination($count){
        $pagination_count = floor($count/PAGE_PER_RECA_MUN);
        $pagination_mod_count = $count % PAGE_PER_RECA_MUN;
        if(!empty($pagination_mod_count)){
            $pagination_count = $pagination_count + 1;
        }
        return $pagination_count;
    }
?>