<?php
	require_once('settings.php');
	if(isset($_GET['act'])){
		$ajax_function = 'ajax/' . $_GET['act'] . '.php';
		if(file_exists($ajax_function)){
			include $ajax_function;
		}else{
			echo json(false, ['message' => 'Function không tồn tại']);
		}
	}elseif(isset($_GET['code'])){
		redirect('/ajax.php?act=authentical&state=' . $_GET['state'] . '&code=' . $_GET['code']);
	}else{
		echo json(false, ['message' => 'Gặp lỗi. vui lòng quay về trang chủ để sử dụng tiếp']);
	}