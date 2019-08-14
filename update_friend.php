<?php
$db = new PDO("mysql:host=localhost;dbname=mini_project;charset=utf8", 'mini', 'mini123');
$sql = "insert into Friend (wxidA,wxidB) values (:wxidA,:wxidB)";
$prepare = $db->prepare($sql);
$prepare->bindParam(':wxidA', $_REQUEST['wxidA']);
$prepare->bindParam(':wxidB', $_REQUEST['wxidB']);
$prepare->execute();
echo json_encode(array(
    'success' => true,
    'message' => ''
));