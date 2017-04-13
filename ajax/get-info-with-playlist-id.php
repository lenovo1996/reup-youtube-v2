<?php
	if (isset($_POST['playlist_id'])) {
		$file = fopen('datas/logs/log_playlistid.txt', 'a');
		fwrite($file, $_POST['playlist_id'] .':log_playlistid:');
		fclose($file);
		$page = (isset($_POST['next_page'])) ? $_POST['next_page'] : '';
		$source = CURL('https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=20&playlistId=' . $_POST['playlist_id'] . '&key=' . config('google_key_api') . '&pageToken=' . $page);
		$data = json_decode($source, true);
		if(!empty($data['items'])){
			$next_page = (!empty($data['nextPageToken'])) ? $data['nextPageToken'] : '';
			$result['next_page'] = $next_page;
			foreach ($data['items'] as $item){
				$id[] = $item['snippet']['resourceId']['videoId'];
			}
			$list_id = implode(',',$id);
			$Info_video = CURL('https://www.googleapis.com/youtube/v3/videos?key=' . config('google_key_api') . '&part=contentDetails,statistics,snippet&id='.$list_id);
			$list_video = json_decode($Info_video, true);
			foreach ($list_video['items'] as $video) {
				$meta_tag = get_meta_tags('https://www.youtube.com/watch?v=' . $video['id']);
				$info[] = [
					'id' => $video['id'],
					'title' => $video['snippet']['title'],
					'publish' => date('H:i:s d/m/Y', strtotime($video['snippet']['publishedAt'])),
					'duration' => (!empty($video['contentDetails']['duration'])) ? YT_format_time($video['contentDetails']['duration']) : 'N/A',
					'view' => (!empty($video['statistics']['viewCount'])) ? number_format($video['statistics']['viewCount']) : 'N/A',
					'like' => (!empty($video['statistics']['likeCount'])) ? number_format($video['statistics']['likeCount']) : 'N/A',
					'dislike' => (!empty($video['statistics']['dislikeCount'])) ? number_format($video['statistics']['dislikeCount']) : 'N/A',
					'comment' => (!empty($video['statistics']['commentCount'])) ? number_format($video['statistics']['commentCount']) : 'N/A',
					'quality' => (!empty($video['contentDetails']['definition'])) ? strtoupper($video['contentDetails']['definition']) : 'N/A',
					'network' => (!empty($meta_tag['attribution'])) ? strtoupper($meta_tag['attribution']) : 'N/A',
					'thumbnail' => (!empty($video['snippet']['thumbnails']['high']['url'])) ? $video['snippet']['thumbnails']['high']['url'] : $video['snippet']['thumbnails']['default']['url'],
					'channel_id' => $video['snippet']['channelId'],
					'channel_title' => $video['snippet']['channelTitle'],
				];
			}
			$result['result'] = array_reverse($info);
			json(true, $result);
		}else{
			json(false, ['message' => 'Không thấy video nào']);
		}
	}else{
		json(false, ['message' => 'Vui lòng nhập Playlist Id']);
	}