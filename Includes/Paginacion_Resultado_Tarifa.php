<?php
    define('PAGE_PER_RESULTADO_TARIFA', 10);
    function getPagination($count){
        $pagination_count = floor($count/PAGE_PER_RESULTADO_TARIFA);
        $pagination_mod_count = $count % PAGE_PER_RESULTADO_TARIFA;
        if(!empty($pagination_mod_count)){
            $pagination_count = $pagination_count + 1;
        }
        return $pagination_count;
    }
?>