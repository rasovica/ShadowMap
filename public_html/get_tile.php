<?php
    header('Content-Type: application/json');
    $redis = new Redis();
    if($redis->pconnect('redis')){
        $cameras = Array();
        foreach ($redis->lRange($_POST["x"].":".$_POST["y"], 0, -1) as $value){
            array_push($cameras, $redis->get($value));
        }
        echo json_encode($cameras);
    }else{
        echo "{\"error\": \"Database is down\"}";
    }
?>
