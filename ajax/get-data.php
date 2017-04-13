<?php

	if(isset($_POST['type'])){
		$page = isset($_POST['page']) ? (int) $_POST['page']-1 . '0' : 0;
		if($_POST['type'] === 'saved-video'){
			$data = get_saved_video(10, $page);
		}elseif($_POST['type'] === 'upload-video'){
			$data = get_video_upload(10, $page);
		}
		if(empty($data)) {
			json(false, ['message' => 'không thấy video']);
			die;
		}
		foreach ($data as $video) {
			$ids[] = $video['id'];
		}
		$list_id = implode(',', $ids);
		$info_video = CURL('https://www.googleapis.com/youtube/v3/videos?key=' . config('google_key_api') . '&part=contentDetails,statistics,snippet&id='.$list_id);
			$list_video = json_decode($info_video, true);
			if (empty($list_video['items'])){
				json(false, ['message' => 'Lỗi. không lấy được thông tin video']);
				die;
			}
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
			json(true, array_reverse($info));

	}else{
		json(false, ['message' => 'Lỗi. Vui lòng trở lại trang chủ để dùng tool']);
	}