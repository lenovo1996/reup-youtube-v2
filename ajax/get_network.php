<?php

	if(isset($_POST['id'])){
		$meta_tag = get_meta_tags('https://www.youtube.com/watch?v=' . $_POST['id']);
		$network = (!empty($meta_tag['attribution'])) ? strtoupper($meta_tag['attribution']) : 'N/A';
		json(true, ['network' => $network]);
		return;
	}
	json(false, ['message' => 'Vui lòng nhập id']);