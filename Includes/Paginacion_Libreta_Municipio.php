<?php
    define('PAGE_PER_LIBRETA_MUNICIPIO', 10);
    function getPagination($count){
        $pagination_count = floor($count/PAGE_PER_LIBRETA_MUNICIPIO);
        $pagination_mod_count = $count % PAGE_PER_LIBRETA_MUNICIPIO;
        if(!empty($pagination_mod_count)){
            $pagination_count = $pagination_count + 1;
        }
        return $pagination_count;
    }
?>