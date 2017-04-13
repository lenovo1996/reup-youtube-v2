<?php

	$page = isset($_POST['page']) ? (int) $_POST['page']-1 . '0' : 0;
	$data = get_channel(10, $page);
	if(empty($data)){
		json(false, ['message' => 'không có channel']);
		die;
	}
	foreach($data as $channel){
		$list_id[] = $channel['id'];
	}
	$list_id = implode(',', $list_id);
	$info_channel = CURL('https://www.googleapis.com/youtube/v3/channels?part=snippet,contentDetails,statistics&id=' . $list_id . '&key=' . config('google_key_api'));
	$info_channel = json_decode($info_channel, true);
	if(empty($info_channel['items'])){
		json(false, ['message' => 'không thấy thông tin channel']);
		die;
	}
	foreach ($info_channel['items'] as $channel) {
		$info[] = [
			'id' => $channel['id'],
			'title' => $channel['snippet']['title'],
			'publish' => date('H:i:s d/m/Y', strtotime($channel['snippet']['publishedAt'])),
			'views' => number_format($channel['statistics']['viewCount']),
			'comments' => number_format($channel['statistics']['commentCount']),
			'subscribes' => number_format($channel['statistics']['subscriberCount']),
			'videos' => number_format($channel['statistics']['videoCount']),
		];
	}
	json(true, $info);