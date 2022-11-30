<?php
    define('PAGE_PER_RESULTADO_MUNICIPIO', 25);
    function getPagination($count) {
        $pagination_count = floor($count/PAGE_PER_RESULTADO_MUNICIPIO);
        $pagination_mod_count = $count % PAGE_PER_RESULTADO_MUNICIPIO;
        if(!empty($pagination_mod_count)){
            $pagination_count = $pagination_count + 1;
        }
        return $pagination_count;
    }
?>