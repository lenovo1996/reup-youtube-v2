<?php

    $client = new Google_Client();
    $client->setClientId($client_id);
    $client->setClientSecret($client_secret);
    $client->setScopes('https://www.googleapis.com/auth/youtube');
    $client->setApprovalPrompt('auto');
    $client->setPrompt('consent');
    $client->setRedirectUri('http://videoviet.org/authentical.php');
    $client->setAccessType('offline');
    $youtube = new Google_Service_YouTube($client);
    if (isset($_GET['code'])) {
        if (strval($_SESSION['state']) !== strval($_GET['state'])) {
            die('The session state did not match.');
        }
        $client->authenticate($_GET['code']);
        $_SESSION['token'] = $client->getAccessToken();
    }

    if (isset($_SESSION['token'])) {
        $client->setAccessToken($_SESSION['token']);
    }
    if (!$client->getAccessToken()) {
        $state = mt_rand();
        $client->setState($state);
        $_SESSION['state'] = $state;
        $authUrl = $client->createAuthUrl();
        header('location: '.$authUrl);
    }else{
        $info = json_decode($client->getAccessToken(), true);
        header('location: /?act=get-auth&token=' . $info['refresh_token']);
    }