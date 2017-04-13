<?php
	
	if(isset($_POST['type'])){
		if ($_POST['type'] === 'channel'){
			$file_name = 'datas/channel/' . $_POST['id'] . '.txt';
			$content = '';
		}elseif ($_POST['type'] === 'saved-video') {
			$file_name = 'datas/saved_video/' . $_POST['id'] . '.txt';
			$content = $_POST['title'].'|split|'.$_POST['language'].'|split|'.$_POST['channel'];
		}elseif ($_POST['type'] === 'upload-video') {
			$file_name = 'datas/uploaded/' . $_POST['id'] . '.txt';
			$content = '';
		}elseif($_POST['type'] === 'save-token'){
			$file_name = 'datas/token/' . $_POST['channel_name'] . '.txt';
			$content = $_POST['channel_token'];
		}elseif($_POST['type'] === 'save-playlist'){
			$file_name = 'datas/saved_playlist/' . mt_rand() . '.txt';
			$content = $_POST['title'] . '|split|' . $_POST['list_id'] . '|split|' . $_POST['channel'] . '|split|' . $_POST['status'];
		}
		
		$file = fopen($file_name, 'w');
		fwrite($file, $content);
		fclose($file);
		if(file_exists($file_name)){
			json(true, array('message' => 'Lưu thành công'));
		}else{
			json(false, array('message' => 'Lưu thất bại'));
		}
	}
