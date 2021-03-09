<?php
$content = file_get_contents("php://input");
$json = json_decode($content);

function execPrint($command) {
    $result = array();
    exec($command, $result);
  echo json_encode($result);
}

if (!empty($json->commits)) {
    execPrint("git pull https://<USER_NAME>:<PASSWORD>@github.com/akbarali1/<REPOSITORY_NAME>.git");
}
