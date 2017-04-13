<?php
	$page = isset($_POST['page']) ? (int) $_POST['page']-1 . '0' : 0;
	$data = get_token(10, $page);
	if(empty($data)){
		json(false, ['message' => 'Không có token']);
		die;
	}
	json(true, $data);