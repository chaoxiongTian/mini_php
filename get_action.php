<?php

$db = new PDO("mysql:host=localhost;dbname=mini_project;charset=utf8", 'mini', 'mini123');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "select * from Action where is_delete=false and action_id=:action_id";
$prepare = $db->prepare($sql);
$prepare->bindParam(':action_id', $_REQUEST['actionID'], PDO::PARAM_INT);
$prepare->execute();
$result = $prepare->fetchAll();

if($result == null){
    echo json_encode(array(
        'success' => false,
        'message' => 'no result'
    ));
} else {
    $return['actionID'] = $result[0]['action_id'];
    $return['actionName'] = $result[0]['content'];
    $return['actionTime'] = $result[0]['action_time'];
    $return['latitude'] = $result[0]['latitude'];
    $return['longitude'] = $result[0]['longitude'];
    $return['actionPosName'] = $result[0]['position'];
    $return['actionPosDec'] = $result[0]['position_dec'];
    $return['noticeTime'] = $result[0]['notice_time'];
    $return['otherInfo'] = $result[0]['other_info'];
    //$return['isJoin'] = $result[0]['is_join'];
    $return['isOwner'] = ($result[0]['owner'] == $_REQUEST['wxid']);
    $return['isOldAction'] = ($result[0]['action_time'] <= time());
    $return['users'] = array();
    $sql0 = "select * from User_Relate_Action where wxid=:wxid and action_id=:action_id";
    $prepare0 = $db->prepare($sql0);
    $prepare0->bindParam(':wxid', $_REQUEST['wxid'], PDO::PARAM_STR);
    $prepare0->bindParam(':action_id', $_REQUEST['actionID'], PDO::PARAM_INT);
    $prepare0->execute();
    $t = $prepare0->fetchAll();
    $return['isJoin'] = !empty($t);
    $sql1 = "select * from User where wxid=:wxid or wxid in (select wxid from User_Relate_Action where action_id=:action_id)";
    $prepare1 = $db->prepare($sql1);
    $prepare1->bindParam(':action_id', $_REQUEST['actionID'], PDO::PARAM_INT);
    $prepare1->bindParam(':wxid', $result[0]['owner'], PDO::PARAM_STR);
    $prepare1->execute();
    $result1 = $prepare1->fetchAll();

    foreach ($result1 as $value){
        array_push($return['users'], array(
            'wxid' => $value['wxid'],
            'longitude' => $value['longitude'],
            'latitude' => $value['latitude'],
            'avator' => $value['avator'],
            'nickname' => $value['nickname'],
        ));
    }
    echo json_encode($return);
}