<?php
    define('PAGE_PER_RESULTADO_ARCHIVO_RECAUDO', 20);
    function getPagination($count) {
        $pagination_count = floor($count/PAGE_PER_RESULTADO_ARCHIVO_RECAUDO);
        $pagination_mod_count = $count % PAGE_PER_RESULTADO_ARCHIVO_RECAUDO;
        if(!empty($pagination_mod_count)){
            $pagination_count = $pagination_count + 1;
        }
        return $pagination_count;
    }
?>