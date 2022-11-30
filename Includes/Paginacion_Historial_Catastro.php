<?php
    define('PAGE_PER_HISTORIAL_CATASTRO', 10);
    function getPagination($count){
        $pagination_count = floor($count/PAGE_PER_HISTORIAL_CATASTRO);
        $pagination_mod_count = $count % PAGE_PER_HISTORIAL_CATASTRO;
        if(!empty($pagination_mod_count)){
            $pagination_count = $pagination_count + 1;
        }
        return $pagination_count;
    }
?>