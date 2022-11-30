<?php
    define('PAGE_PER_RESULTADO_ARCHIVO_REFACTURACION', 20);
    function getPagination($count) {
        $pagination_count = floor($count/PAGE_PER_RESULTADO_ARCHIVO_REFACTURACION);
        $pagination_mod_count = $count % PAGE_PER_RESULTADO_ARCHIVO_REFACTURACION;
        if(!empty($pagination_mod_count)){
            $pagination_count = $pagination_count + 1;
        }
        return $pagination_count;
    }
?>