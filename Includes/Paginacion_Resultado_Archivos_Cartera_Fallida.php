<?php
    define('PAGE_PER_RESULTADO_ARCHIVO_CARTERA_FALLIDA', 20);
    function getPagination($count) {
        $pagination_count = floor($count/PAGE_PER_RESULTADO_ARCHIVO_CARTERA_FALLIDA);
        $pagination_mod_count = $count % PAGE_PER_RESULTADO_ARCHIVO_CARTERA_FALLIDA;
        if(!empty($pagination_mod_count)){
            $pagination_count = $pagination_count + 1;
        }
        return $pagination_count;
    }
?>