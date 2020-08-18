<?php

require_once 'autoload.php';
require_once '../../config.php';
include_once '../../functions.php';
include_once '../../autoload.php';
include_once '../../class/model/database/DB.functions.php';

if (!session_id()) {
    session_start();
}
$fb = new Facebook\Facebook([
    'app_id' => '', // Replace {app-id} with your app id
    'app_secret' => '',
    'default_graph_version' => 'v2.2',
        ]);
$helper = $fb->getRedirectLoginHelper();
//$_SESSION['FBRLH_state'] = $_GET['state'];
try {
    $accessToken = $helper->getAccessToken();
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (!isset($accessToken)) {
    if ($helper->getError()) {
        header('HTTP/1.0 401 Unauthorized');
        echo "Error: " . $helper->getError() . "\n";
        echo "Error Code: " . $helper->getErrorCode() . "\n";
        echo "Error Reason: " . $helper->getErrorReason() . "\n";
        echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
    }
    exit;
}

/* var_dump($accessToken->getValue()); */

$oAuth2Client = $fb->getOAuth2Client();
$tokenMetadata = $oAuth2Client->debugToken($accessToken);

/* var_dump($tokenMetadata); */

$tokenMetadata->validateAppId(""); // Replace {app-id} with your app id
$tokenMetadata->validateExpiration();

if (!$accessToken->isLongLived()) {
    // Exchanges a short-lived access token for a long-lived one
    try {
        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
        exit;
    }
}

try {
    // Returns a `Facebook\FacebookResponse` object
    $response = $fb->get('/me?fields=id,name,email', $accessToken = (string) $accessToken);
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

$user = $response->getGraphUser();



$facebookConn = "$user[id]:$user[email]";


Transacao::open();

$login = select(array("sql" => "select * from login where facebookConn=?", "values" => array($facebookConn)), "stdClass");
if (empty($login)) {
    try {
        $res = query("INSERT INTO `people`(nome) VALUES (?)", array($user["name"]));
        $res = query("INSERT INTO `login`(`facebookConn`,idPeople) VALUES (?,?)", array($facebookConn, $res->lastInsertId));
        $_SESSION["idUser"] = $res->lastInsertId;
    } catch (Exception $e) {
        echo "error: " . $e->getMessage();
    }
} else {
    $_SESSION["idUser"] = $login->id;

    Transacao::rollback();
}
Transacao::close();
header("Location: " . urldecode($_GET["urlReturn"]));
