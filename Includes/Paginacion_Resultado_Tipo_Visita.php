<?php
    define('PAGE_PER_RESULTADO_TIPO_VISITA', 25);
    function getPagination($count) {
        $pagination_count = floor($count/PAGE_PER_RESULTADO_TIPO_VISITA);
        $pagination_mod_count = $count % PAGE_PER_RESULTADO_TIPO_VISITA;
        if(!empty($pagination_mod_count)){
            $pagination_count = $pagination_count + 1;
        }
        return $pagination_count;
    }
?>