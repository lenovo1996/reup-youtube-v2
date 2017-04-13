<?php
	if (isset($_POST['channel_id'])) {
		$file = fopen('datas/logs/log_channel.txt', 'a');
		fwrite($file, $_POST['channel_id'] .':log_channel:');
		fclose($file);
		$source = CURL('https://www.googleapis.com/youtube/v3/playlists?channelId='.$_POST['channel_id'].'&part=contentDetails,snippet&maxResults=50&key=' . config('google_key_api'));
		$data = json_decode($source, true);
		if(!empty($data['items'])){
			foreach ($data['items'] as $item){
				$info[] = [
					'id' => $item['id'],
					'title' => $item['snippet']['title'],
					'publish' => date('H:i:s d/m/Y', strtotime($item['snippet']['publishedAt'])),
					'thumbnail' => (!empty($item['snippet']['thumbnails']['high']['url'])) ? $item['snippet']['thumbnails']['high']['url'] : $item['snippet']['thumbnails']['default']['url'],
					'channel_id' => $item['snippet']['channelId'],
					'channel_title' => $item['snippet']['channelTitle'],
					'count_video' => $item['contentDetails']['itemCount']
				];
			}
			json(true, array_reverse($info));
		}else{
			json(false, ['message' => 'Không thấy video nào']);
		}
	}else{
		json(false, ['message' => 'Vui lòng nhập Playlist Id']);
	}