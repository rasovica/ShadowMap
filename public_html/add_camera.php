<?php
    header('Content-Type: application/json');
    $redis = new Redis();
    if($redis->pconnect('redis')){
        $curr_id = $redis->incr('curr_camera_id')-1;
        $data = Array();
        $data["lat"] = $_POST["lat"];
        $data["lng"] = $_POST["lng"];
        $data["n"] = $_POST["n"];
        $data["a"] = $_POST["a"];
        $data["u"] = $_POST["u"];
        $data["r"] = $_POST["r"];
        $data["d"] = $_POST["d"];
        $redis->lPush($_POST["x"].":".$_POST["y"], $curr_id);
        $redis->set($curr_id, json_encode($data));
        echo "{\"success\": \"Camera added\"}";
    }else{
        echo "{\"error\": \"Database is down\"}";
    }
?>
