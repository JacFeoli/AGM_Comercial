<?php
    define('PAGE_PER_EMPRESA', 10);
    function getPagination($count){
        $pagination_count = floor($count/PAGE_PER_EMPRESA);
        $pagination_mod_count = $count % PAGE_PER_EMPRESA;
        if(!empty($pagination_mod_count)){
            $pagination_count = $pagination_count + 1;
        }
        return $pagination_count;
    }
?>