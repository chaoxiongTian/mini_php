<?php
$a = false;
$db = new PDO("mysql:host=localhost;dbname=mini_project;charset=utf8", 'mini', 'mini123');
if($_REQUEST['isDelete'] == 'true'){
    $sql = "update Action set is_delete=true,update_time=now()
 where action_id=:action_id and owner=:owner";
    $prepare = $db->prepare($sql);
    $prepare->bindParam(':owner', $_REQUEST['wxid'], PDO::PARAM_STR);
    $prepare->bindParam(':action_id', $_REQUEST['actionId'], PDO::PARAM_INT);
    $a = $prepare->execute();
    var_dump($a);
} else if ($_REQUEST['isJoin'] == 'true'){
    $sql = "insert into User_Relate_Action (wxid,action_id) values(:wxid,:action_id)";
    $prepare = $db->prepare($sql);
    $prepare->bindParam(':wxid', $_REQUEST['wxid'], PDO::PARAM_STR);
    $prepare->bindParam(':action_id', $_REQUEST['actionId'], PDO::PARAM_INT);
    $a = $prepare->execute();
} else if($_REQUEST['isJoin'] == 'false'){
    $sql = "delete from User_Relate_Action where action_id=:action_id and wxid=:wxid";
    $prepare = $db->prepare($sql);
    $prepare->bindParam(':wxid', $_REQUEST['wxid'], PDO::PARAM_STR);
    $prepare->bindParam(':action_id', $_REQUEST['actionId'], PDO::PARAM_INT);
    $prepare->execute();
    if ($prepare->rowCount()) {
        $a = true;
    } else {
        $a = false;
    }
}
if (!$a){
    echo json_encode(array(
        'success' => false,
        'message' => '没有权限'
    ));
} else {
    echo json_encode(array(
        'success' => true,
        'message' => ''
    ));
}
