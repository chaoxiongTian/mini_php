<?php

$db = new PDO("mysql:host=localhost;dbname=mini_project;charset=utf8", 'mini', 'mini123', array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8'));

if(strval($_REQUEST['isAdd']) == 'true'){
    $sql = "insert into Action (owner,content,action_time,position,position_dec,longitude,latitude,notice_time,create_time,other_info,is_join,is_delete) 
values(:owner,:content,:action_time,:position,:position_dec,:longitude,:latitude,:notice_time,now(),:other_info,true,false)";
    $prepare = $db->prepare($sql);
    $prepare->bindParam(':owner', $_REQUEST['wxid'], PDO::PARAM_STR);
    $prepare->bindParam(':content', $_REQUEST['actionName'], PDO::PARAM_STR);
    $prepare->bindParam(':action_time', $_REQUEST['actionTime'], PDO::PARAM_STR);
    $prepare->bindParam(':position', $_REQUEST['actionPosName'], PDO::PARAM_STR);
    $prepare->bindParam(':position_dec', $_REQUEST['actionPosDec'], PDO::PARAM_STR);
    $prepare->bindParam(':longitude', $_REQUEST['longitude'], PDO::PARAM_STR);
    $prepare->bindParam(':latitude', $_REQUEST['latitude'], PDO::PARAM_STR);
    $prepare->bindParam(':notice_time', $_REQUEST['noticeTime'], PDO::PARAM_INT);
    $prepare->bindParam(':other_info', $_REQUEST['otherInfo'], PDO::PARAM_STR);
    $prepare->execute();
    $num = $db->lastInsertId();
    $sql = "insert into User_Relate_Action (wxid,action_id) values(:wxid,:action_id)";
    $prepare = $db->prepare($sql);
    $prepare->bindParam(':wxid', $_REQUEST['wxid'], PDO::PARAM_STR);
    $prepare->bindParam(':action_id', $num, PDO::PARAM_INT);
    $prepare->execute();
    echo json_encode(array(
        'actionId' => $num
    ));
} else{
//    $sql = "insert into User_Relate_Action (wxid,action_id) values(:wxid,:action_id)";
//    $prepare = $db->prepare($sql);
//    $prepare->bindParam(':wxid', $_REQUEST['wxid'], PDO::PARAM_STR);
//    $prepare->bindParam(':action_id', $_REQUEST['actionID'], PDO::PARAM_INT);
//    $prepare->execute();
    $sql = "update Action set owner=:owner,content=:content,action_time=:action_time,position=:position,position_dec=:position_dec,longitude=:longitude,latitude=:latitude,notice_time=:notice_time,update_time=now(),other_info=:other_info
 where action_id=:action_id";
    $prepare = $db->prepare($sql);
    $prepare->bindParam(':owner', $_REQUEST['wxid'], PDO::PARAM_STR);
    $prepare->bindParam(':content', $_REQUEST['actionName'], PDO::PARAM_STR);
    $prepare->bindParam(':action_time', $_REQUEST['actionTime'], PDO::PARAM_STR);
    $prepare->bindParam(':position', $_REQUEST['actionPosName'], PDO::PARAM_STR);
    $prepare->bindParam(':position_dec', $_REQUEST['actionPosDec'], PDO::PARAM_STR);
    $prepare->bindParam(':longitude', $_REQUEST['longitude'], PDO::PARAM_STR);
    $prepare->bindParam(':latitude', $_REQUEST['latitude'], PDO::PARAM_STR);
    $prepare->bindParam(':notice_time', $_REQUEST['noticeTime'], PDO::PARAM_INT);
    $prepare->bindParam(':other_info', $_REQUEST['otherInfo'], PDO::PARAM_STR);
    $prepare->bindParam(':action_id', $_REQUEST['actionID'],PDO::PARAM_INT);
    $prepare->execute();
    echo json_encode(array(
        'actionId' => $_REQUEST['actionID']
    ));
}
