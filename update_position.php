<?php
$db = new PDO("mysql:host=localhost;dbname=mini_project;charset=utf8", 'mini', 'mini123');
//$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(strval($_REQUEST['longitude']) != '0'){
    $sql = "replace into User (wxid,nickname,avator,longitude,latitude) values
(:wxid,:nickname,:avator,:longitude,:latitude)";
    $prepare = $db->prepare($sql);
    $prepare->bindParam(':wxid', $_REQUEST['wxid'], PDO::PARAM_STR);
    $prepare->bindParam(':avator', $_REQUEST['avator'],PDO::PARAM_STR);
    $prepare->bindParam(':nickname', $_REQUEST['nickname'], PDO::PARAM_STR);
    $prepare->bindParam(':longitude', $_REQUEST['longitude'], PDO::PARAM_STR);
    $prepare->bindParam(':latitude', $_REQUEST['latitude'], PDO::PARAM_STR);
    $prepare->execute();
}
$db = new PDO("mysql:host=localhost;dbname=mini_project;charset=utf8", 'mini', 'mini123');
$sql = "select * from User where wxid=:wxid";
$prepare = $db->prepare($sql);
$prepare->bindParam(':wxid', $_REQUEST['wxid'], PDO::PARAM_STR);
$prepare->execute();
$result = $prepare->fetchAll();
$return = array(
    'actionId' => array(),
    'actionName' => array(),
    'longitude' => $result[0]['longitude'],
    'latitude' => $result[0]['latitude'],
);

$db = new PDO("mysql:host=localhost;dbname=mini_project;charset=utf8", 'mini', 'mini123');
$sql = "select * from Action where is_delete=false and action_id in (select action_id from User_Relate_Action where wxid=:wxid)";
$prepare = $db->prepare($sql);
$prepare->bindParam(':wxid', $_REQUEST['wxid'], PDO::PARAM_STR);
$prepare->execute();
$result = $prepare->fetchAll();

foreach ($result as $value){
    if($value['action_time'] < time()) continue;
    array_push($return['actionId'], $value['action_id']);
    array_push($return['actionName'], $value['content']);
    array_push($return['longitude'], $value['longitude']);
    array_push($return['latitude'], $value['latitude']);
}
$return['hasAction'] = (count($result) != 0);
echo json_encode($return);