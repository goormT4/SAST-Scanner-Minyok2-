<?php

if (!function_exists("dd")) {
    function dd(...$args)
    {
        http_response_code(500);

        if(defined("REST_REQUEST") && REST_REQUEST === true){
            echo  json_encode(["args"=>$args, "backtrace" => debug_backtrace(FALSE, 2)]);
        }else{
            foreach ($args as $x) {
                (new \WappoVendor\Illuminate\Support\Debug\Dumper)->dump($x);
            }
        }

        die(1);
    }
}

if (!function_exists("dds")) {
    function dds($content, $filename = "default")
    {
        $dirlog = WAPPOINTMENT_PATH . "logs";
        if(!is_dir($dirlog)){
            mkdir($dirlog, 0755);
        }
        file_put_contents($dirlog . DIRECTORY_SEPARATOR . time() . $filename, $content);
    }
}