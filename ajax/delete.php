<?php
	if(isset($_POST['type'])){
		if($_POST['type'] === 'saved-video'){
			$file_name = 'datas/saved_video/' . $_POST['id'] . '.txt';
		}elseif ($_POST['type'] === 'upload-video') {
			$file_name = 'datas/uploaded/' . $_POST['id'] . '.txt';
		}elseif ($_POST['type'] === 'channel') {
			$file_name = 'datas/channel/' . $_POST['id'] . '.txt';
		}elseif ($_POST['type'] === 'channel-token') {
			$file_name = 'datas/token/' . $_POST['id'] . '.txt';
		}elseif ($_POST['type'] === 'saved-playlist') {
			$file_name = 'datas/saved_playlist/' . $_POST['id'] . '.txt';
		}
		if(file_exists($file_name)){
			unlink($file_name);
			json(true, ['message' => 'Xóa thành công id: ' . $_POST['id']]);
		}else{
			json(false, ['message' => 'Không tồn tại id: ' . $_POST['id']]);
		}
	}else{
		json(false, ['message' => 'Lỗi. Vui lòng trở về trang chủ']);
	}