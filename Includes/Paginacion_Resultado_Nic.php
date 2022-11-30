<?php
    define('PAGE_PER_RESULTADO_NIC', 12);
    function getPagination($count){
        $pagination_count = floor($count/PAGE_PER_RESULTADO_NIC);
        $pagination_mod_count = $count % PAGE_PER_RESULTADO_NIC;
        if(!empty($pagination_mod_count)){
            $pagination_count = $pagination_count + 1;
        }
        return $pagination_count;
    }
?>