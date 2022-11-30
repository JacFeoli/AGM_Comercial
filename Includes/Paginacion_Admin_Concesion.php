<?php
    define('PAGE_PER_CONCESION', 10);
    function getPagination($count){
        $pagination_count = floor($count/PAGE_PER_CONCESION);
        $pagination_mod_count = $count % PAGE_PER_CONCESION;
        if(!empty($pagination_mod_count)){
            $pagination_count = $pagination_count + 1;
        }
        return $pagination_count;
    }
?>