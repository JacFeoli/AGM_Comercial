<?php
    define('PAGE_PER_OPERADOR', 10);
    function getPagination($count){
        $pagination_count = floor($count/PAGE_PER_OPERADOR);
        $pagination_mod_count = $count % PAGE_PER_OPERADOR;
        if(!empty($pagination_mod_count)){
            $pagination_count = $pagination_count + 1;
        }
        return $pagination_count;
    }
?>