<?php
$db = new PDO("mysql:host=localhost;dbname=mini_project;charset=utf8", 'mini', 'mini123');

$sql = "select * from Action where is_delete=false and action_id in (select action_id from User_Relate_Action where wxid=:wxid)";
$prepare = $db->prepare($sql);
$prepare->bindParam(':wxid', $_REQUEST['wxid'], PDO::PARAM_STR);
$prepare->execute();
$result = $prepare->fetchAll();
$return = array();
if($result != null){
    foreach ($result as $value){
        if(strval($_REQUEST['all']) == 'false') {
            if($value['action_time'] > time()) continue;
        }
        $tmp = array();
        $tmp['actionID'] = $value['action_id'];
        $tmp['actionName'] = $value['content'];
        $tmp['actionTime'] = $value['action_time'];
        $tmp['latitude'] = $value['latitude'];
        $tmp['longitude'] = $value['longitude'];
        $tmp['actionPosName'] = $value['position'];
        $tmp['actionPosDec'] = $value['position_dec'];
        $tmp['noticeTime'] = $value['notice_time'];
        $tmp['otherInfo'] = $value['other_info'];
        //$tmp['isJoin'] = $value['is_join'];
        $tmp['isOwner'] = ($value['owner'] == $_REQUEST['wxid']);
        $tmp['isOldAction'] = ($value['action_time'] <= time());
        $tmp['users'] = array();
        $sql0 = "select * from User_Relate_Action where wxid=:wxid and action_id=:action_id";
        $prepare0 = $db->prepare($sql0);
        $prepare0->bindParam(':wxid', $_REQUEST['wxid'], PDO::PARAM_STR);
        $prepare0->bindParam(':action_id', $value['action_id'], PDO::PARAM_INT);
        $prepare0->execute();
        $t = $prepare0->fetchAll();
        if(empty($t)) $tmp['isJoin'] = 'false';
        else $tmp['isJoin'] = 'true';
        $sql1 = "select * from User where wxid in (select wxid from User_Relate_Action where action_id=:action_id)";
        $prepare1 = $db->prepare($sql1);
        $prepare1->bindParam(':action_id', $value['action_id'], PDO::PARAM_INT);
        $prepare1->execute();
        $result1 = $prepare1->fetchAll();
        foreach ($result1 as $value1){
            array_push($tmp['users'], array(
                'wxid' => $value1['wxid'],
                'longitude' => $value1['longitude'],
                'latitude' => $value1['latitude'],
                'avator' => $value1['avator'],
                'nickname' => $value1['nickname'],
            ));
        }
        array_push($return, $tmp);
    }
}
echo json_encode($return);