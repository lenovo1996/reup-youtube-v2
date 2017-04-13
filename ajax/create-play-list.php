<?php

if(isset($_POST['id'])){
$info1 = file_get_contents('datas/saved_playlist/' . $_POST['id'] . '.txt');
$data = explode('|split|', $info1);
$info['title'] = $data[0];
$info['list_id'] = preg_split ('/$\R?^/m', $data[1]);
$info['channel'] = $data[2];
$info['status'] = $data[3];
$id_pll = $_POST['id'];
}else{
$info = get_saved_playlist(1);
if(empty($info)){
die;
}
$info = $info[0];
$id_pll = $info['id'];
}

$refresh_token = file_get_contents('datas/token/' . $info['channel'] . '.txt');
$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setScopes('https://www.googleapis.com/auth/youtube');
$redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
$client->setRedirectUri($redirect);
$client->setAccessType('offline');
$youtube = new Google_Service_YouTube($client);
$client->refreshToken($refresh_token);
$token = $client->getAccessToken();
if (isset($token)) {
    $client->setAccessToken($token);
}
if ($client->getAccessToken()) {
  try {
    $playlistSnippet = new Google_Service_YouTube_PlaylistSnippet();
    $playlistSnippet->setTitle($info['title']);
    $playlistSnippet->setDescription($info['title'] .'
'.$info['title'].'
'.$info['title']);
    $playlistStatus = new Google_Service_YouTube_PlaylistStatus();
    $playlistStatus->setPrivacyStatus($info['status']);
    $youTubePlaylist = new Google_Service_YouTube_Playlist();
    $youTubePlaylist->setSnippet($playlistSnippet);
    $youTubePlaylist->setStatus($playlistStatus);
    $playlistResponse = $youtube->playlists->insert('snippet,status', $youTubePlaylist, array());
    $playlistId = $playlistResponse['id'];
    $file = fopen('datas/created_playlist/' . $playlistResponse['id']. '.txt');
    fwrite($file, $playlistResponse['snippet']['title']);
    fclose($file);
    foreach($info['list_id'] as $id){
      if(CheckVideo($id)){
          $resourceId = new Google_Service_YouTube_ResourceId();
          $resourceId->setVideoId($id);
          $resourceId->setKind('youtube#video');
          $playlistItemSnippet = new Google_Service_YouTube_PlaylistItemSnippet();
          $playlistItemSnippet->setTitle('First video in the test playlist');
          $playlistItemSnippet->setPlaylistId($playlistId);
          $playlistItemSnippet->setResourceId($resourceId);
          $playlistItem = new Google_Service_YouTube_PlaylistItem();
          $playlistItem->setSnippet($playlistItemSnippet);
          $playlistItemResponse = $youtube->playlistItems->insert('snippet,contentDetails', $playlistItem, array());
      }
    }
    unlink('datas/saved_playlist/' . $id_pll . '.txt');
    json(true, ['message' => 'Tạo thành công' . $playlistId]);
  } catch (Google_Service_Exception $e) {
    $file = fopen('datas/logs/error_log.txt', 'a');
    fwrite($file, 'Lỗi tạo playlist: ' . $e->getMessage() . '
');
    fclose($file);
    json(false, ['message' => 'Tạo không thành công']);
  } catch (Google_Exception $e) {
    $file = fopen('datas/logs/error_log.txt', 'a');
    fwrite($file, 'Lỗi tạo playlist: ' . $e->getMessage() . '
');
    fclose($file);
    json(false, ['message' => 'Tạo không thành công']);
  }
}