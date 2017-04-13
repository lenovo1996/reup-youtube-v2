<?php

	if(isset($_POST['id'])){
		$video_id = $_POST['id'];
	}else{
		$video = get_saved_video(1);
if(empty($video)){
die;
}
		$video_id = $video[0]['id'];
	}
	$data = file_get_contents('datas/saved_video/' . $video_id . '.txt');
	$data = explode('|split|', $data);
	if(file_exists('datas/token/' . $data[2] . '.txt')){
		$refresh_token = file_get_contents('datas/token/' . $data[2] . '.txt');
		if(!empty($refresh_token)){
			$client = new Google_Client();
	    $client->setClientId($client_id);
	    $client->setClientSecret($client_secret);
	    $client->setScopes('https://www.googleapis.com/auth/youtube');
	    $client->setRedirectUri(filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'], FILTER_SANITIZE_URL));
	    $client->setAccessType('offline');
	    $youtube = new Google_Service_YouTube($client);
	    $client->refreshToken($refresh_token);
  		$token = $client->getAccessToken();
	    if (isset($token)) {
	        $client->setAccessToken($token);
	    }
	    if ($client->getAccessToken()) {
        try{
        	if(!copy(get_link_download_form_id($video_id), 'datas/download/video/' . $video_id . '.mp4')){
						$file = fopen('datas/logs/error_log.txt', 'a');
						fwrite($file, 'download video: ' . $video_id . 'lỗi.
');
						fclose($file);
		    		json(false, ['message' => 'Upload lỗi: không download được video']);
						die;
					}
					if(!copy('http://img.youtube.com/vi/' . $video_id . '/mqdefault.jpg', 'datas/download/thumbnail/' . $video_id . '.jpg')){
						$file = fopen('datas/logs/error_log.txt', 'a');
						fwrite($file, 'download thumbnail: ' . $video_id . 'lỗi.
');
						fclose($file);
		    		json(false, ['message' => 'Upload lỗi: không download được thumbnail']);
						die;
					}
					$file = fopen('datas/download/video/' . $video_id . '.mp4', 'a');
					fwrite($file, '   ');
					fclose($file);
					$info_video = get_info_video_form_id($video_id);
if($data[1] !== 'default'){
					$title = translate($info_video['name'], $data[1]);
					$description = translate($info_video['desc'], $data[1]);
					$tags = translate($info_video['tags2'], $data[1]);
					$tags2 = explode(', ', $tags);
}else{

					$title = $info_video['name'];
					$description = $info_video['desc'];
					$tags = $info_video['tags2'];
					$tags2 = explode(', ', $tags);
}
          $videoPath = 'datas/download/video/' . $video_id . '.mp4'; 
          $snippet = new Google_Service_YouTube_VideoSnippet();
          $snippet->setTitle($title);
          $snippet->setDescription($description . $tags);
          $snippet->setTags($tags2);
          $snippet->setCategoryId('22');
          $status = new Google_Service_YouTube_VideoStatus();
          $status->privacyStatus = "public";
          $video = new Google_Service_YouTube_Video();
          $video->setSnippet($snippet);
          $video->setStatus($status);
          $chunkSizeBytes = 1 * 10240 * 10240;
          $client->setDefer(true);
          $insertRequest = $youtube->videos->insert("status,snippet", $video);
          $media = new Google_Http_MediaFileUpload(
            $client,
            $insertRequest,
            'video/*',
            null,
            true,
            $chunkSizeBytes
          );
          $media->setFileSize(filesize($videoPath));
          $status = false;
          $handle = fopen($videoPath, "rb");
          while (!$status && !feof($handle)) {
            $chunk = fread($handle, $chunkSizeBytes);
            $status = $media->nextChunk($chunk);
          }
          fclose($handle);
          $videoId = $status->id;
          $imagePath = 'datas/download/thumbnail/' . $video_id . '.jpg';
          $client->setDefer(true);
	    		$setRequest = $youtube->thumbnails->set($videoId);
	    		$media = new Google_Http_MediaFileUpload(
	        $client,
	        $setRequest,
	        'image/jpeg',
	        null,
	        true,
	        $chunkSizeBytes
		    );
		    $media->setFileSize(filesize($imagePath));
		    $status = false;
		    $handle = fopen($imagePath, "rb");
		    while (!$status && !feof($handle)) {
		      $chunk = fread($handle, $chunkSizeBytes);
		      $status = $media->nextChunk($chunk);
		    }
		    fclose($handle);
		    $client->setDefer(false);
		    $count = (int) file_get_contents('datas/chart/' . date('d-m-Y') . '.txt');
		    $file = fopen('datas/chart/' . date('d-m-Y') . '.txt', 'w');
		    fwrite($file, $count + 1);
		    fclose($file);
		    $file = fopen('datas/uploaded/' . $videoId . '.txt', 'w');
		    fwrite($file, $title);
		    fclose($file);
		    unlink('datas/saved_video/' . $video_id . '.txt');
		    unlink('datas/download/video/' . $video_id . '.mp4');
		    unlink('datas/download/thumbnail/' . $video_id . '.jpg');
		    json(true, ['message' => 'Upload thành công']);
        } catch (Google_ServiceException $e) {
        	$file = fopen('datas/logs/error_log.txt', 'a');
					fwrite($file, 'upload thumbnail: ' . $videoId . 'lỗi.
');
					fclose($file);
		    json(true, ['message' => 'Upload thành công']);
        } catch (Google_Exception $e) {
					if(preg_match('#access token has expired#is',$e->getMessage())){
						$file = fopen('datas/logs/error_log.txt', 'a');
						fwrite($file, 'Token channel '. $data[2] .' hết hạn...
');
						fclose($file);
		    json(false, ['message' => 'Token channel '. $data[2] .' hết hạn...']);
					}else if (preg_match('#exceeded#is',$e->getMessage())){
						$file = fopen('datas/logs/error_log.txt', 'a');
						fwrite($file, 'Hết quotas api.
');
						fclose($file);
		    json(false, ['message' => 'Upload lỗi: Hết Quotas API.']);
					}elseif(preg_match('#thumbnails/set#is',$e->getMessage())){
						$file = fopen('datas/logs/error_log.txt', 'a');
						fwrite($file, 'Lỗi không upload được thumbnail
');
						fclose($file);
						$count = (int) file_get_contents('datas/chart/' . date('d-m-Y') . '.txt');
				    $file = fopen('datas/chart/' . date('d-m-Y') . '.txt', 'w');
				    fwrite($file, $count + 1);
				    fclose($file);
				    $file = fopen('datas/uploaded/' . $videoId . '.txt', 'w');
				    fwrite($file, $title);
				    fclose($file);
				    unlink('datas/saved_video/' . $video_id . '.txt');
				    unlink('datas/download/video/' . $video_id . '.mp4');
				    unlink('datas/download/thumbnail/' . $video_id . '.jpg');
		    json(true, ['message' => 'Upload Thành công: không set được thumbnail']);
					}else{
						$file = fopen('datas/logs/error_log.txt', 'a');
						fwrite($file, strip_tags('Lỗi không rõ: ' . $e->getMessage()) . '
');
						fclose($file);
		    json(false, ['message' => 'Upload lỗi: Lỗi không rõ']);
					}
        }
	    }else{
	    	$file = fopen('datas/logs/error_log.txt', 'a');
				fwrite($file, 'Không lấy được token: '. $data[2] .'
');
				fclose($file);
		    json(false, ['message' => 'Upload lỗi: Không lấy được token: '. $data[2] .'']);
	    }
		}else{
		  json(false, ['message' => 'Upload Lỗi: Token trống']);
		}
	}else{
		json(false, ['message' => 'Upload Lỗi: Không tồn tại token']);
	}