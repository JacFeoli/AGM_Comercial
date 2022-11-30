<?php
    define('PAGE_PER_TIPO_CONTRATO', 10);
    function getPagination($count){
        $pagination_count = floor($count/PAGE_PER_TIPO_CONTRATO);
        $pagination_mod_count = $count % PAGE_PER_TIPO_CONTRATO;
        if(!empty($pagination_mod_count)){
            $pagination_count = $pagination_count + 1;
        }
        return $pagination_count;
    }
?>