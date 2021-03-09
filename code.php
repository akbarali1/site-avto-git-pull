<?php
//MOdul testlandi
define("TOKEN", "asjdhasacf5adf54a");   
define("GIT_USERNAME", "<GIT_USERNAME>");   
define("GIT_PASSWORD", "<GIT_PASSWORD>");   
define("REPOSITORY_NAME", "<REPOSITORY_NAME>");   

$token   = false;
$sha     = false;

$content = file_get_contents("php://input");
$json = json_decode($content);
function execPrint($command) {
    $result = array();
    exec($command, $result);
  echo json_encode($result);
}

// retrieve the token
if (!$token && isset($_SERVER["HTTP_X_HUB_SIGNATURE"])) {
  list($algo, $token) = explode("=", $_SERVER["HTTP_X_HUB_SIGNATURE"], 2) + array("", "");
} elseif (isset($_SERVER["HTTP_X_GITLAB_TOKEN"])) {
  $token = $_SERVER["HTTP_X_GITLAB_TOKEN"];
} elseif (isset($_GET["token"])) {
  $token = $_GET["token"];
}

// Check for a GitHub signature
if (!empty(TOKEN) && isset($_SERVER["HTTP_X_HUB_SIGNATURE"]) && $token !== hash_hmac($algo, $content, TOKEN)) {
  http_response_code(404);
  echo  "X-Hub-Signature does not match TOKEN";
// Check for a GitLab token
} elseif (!empty(TOKEN) && isset($_SERVER["HTTP_X_GITLAB_TOKEN"]) && $token !== TOKEN) {
  http_response_code(404);
  echo  "X-GitLab-Token does not match TOKEN";
// Check for a $_GET token
} elseif (!empty(TOKEN) && isset($_GET["token"]) && $token !== TOKEN) {
  http_response_code(404);
  echo  "\$_GET[\"token\"] does not match TOKEN";

// if none of the above match, but a token exists, exit
} elseif (!empty(TOKEN) && !isset($_SERVER["HTTP_X_HUB_SIGNATURE"]) && !isset($_SERVER["HTTP_X_GITLAB_TOKEN"]) && !isset($_GET["token"])) {
  http_response_code(404);
  echo  "No token detected";
} else {
//  file_put_contents('deploy.log', json_encode($json, true), FILE_APPEND);

if (!empty($json->commits)) {
    execPrint("git pull https://".GIT_USERNAME.":".GIT_PASSWORD."@github.com/".GIT_USERNAME."/".REPOSITORY_NAME.".git");
}

}
