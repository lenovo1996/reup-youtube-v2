<?php
	$page = isset($_POST['page']) ? (int) $_POST['page']-1 . '0' : 0;
	$data = get_saved_playlist(10, $page);
	if(empty($data)){
		json(false, ['message' => 'không có playlist']);
		die;
	}
	json(true, $data);

