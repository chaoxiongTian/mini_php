<?php
$db = new PDO("mysql:host=localhost;dbname=mini_project;charset=utf8", 'mini', 'mini123');
$sql = "select * from User where wxid in (select wxidA from Friend where wxidB=:wxidB) 
or wxid in (select wxidB from Friend where wxidA=:wxidA)";
$prepare = $db->prepare($sql);
$prepare->bindParam(':wxidA', $_REQUEST['wxid'], PDO::PARAM_STR);
$prepare->bindParam(':wxidB', $_REQUEST['wxid'], PDO::PARAM_STR);
$prepare->execute();
$result = $prepare->fetchAll();
$return = array(
  'count' => count($result),
  'frientId' => array(),
  'nickname' => array(),
  'friendAvator' => array()
);
foreach ($result as $value){
    array_push($return['frientId'], $value['wxid']);
    array_push($return['nickname'], $value['nickname']);
    array_push($return['friendAvator'], $value['avator']);
}
echo json_encode($return);