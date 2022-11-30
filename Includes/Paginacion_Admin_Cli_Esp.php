<?php
    define('PAGE_PER_CLI_ESP', 20);
    function getPagination($count){
        $pagination_count = floor($count/PAGE_PER_CLI_ESP);
        $pagination_mod_count = $count % PAGE_PER_CLI_ESP;
        if(!empty($pagination_mod_count)){
            $pagination_count = $pagination_count + 1;
        }
        return $pagination_count;
    }
?>