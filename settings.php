<?php
ini_set("memory_limit",-1);
session_start();
	include 'config/google_api/src/Google/Client.php';
  include 'config/google_api/src/Google/Service/YouTube.php';
	include 'config/init.php';
	$client_id = config('client_id');
	$client_secret = config('client_secret');


	function config($variable) {
		return $GLOBALS['config'][$variable];
	}

	/**
	* Get list menu function
	* return array
	* author: Lê Phi
	*/
	function asideMenu () {
		return $GLOBALS['config']['aside_menu'];
	}

	/**
	* Check current tool function
	* return boolean
	* author: Lê Phi
	*/
	function currentTool ($tool, $current_uri) {
		if(strpos($current_uri, $tool['uri'])){
			return true;
		}
		return false;
	}

	/**
	* Get chart video uploaded function
	* return array
	* author: Lê Phi
	*/
	function chart_upload_video () {
		$files = scan_dir('datas/chart'); 
		$data = [];
		foreach ($files as $file) { 
			$count = file_get_contents('datas/chart/' . $file);
			$date = explode('.', $file);
			$data['count'][] = $count;
			$data['date'][] = $date[0];
		}
		return $data;
	}

	/**
	* Get list recent video upload function
	* return array
	* author: Lê Phi
	*/
	function get_video_upload ($show = 10000000, $page = 0) {
		$files = scan_dir('datas/uploaded');
		$i = 1;
		$data = [];
		foreach (array_slice($files, $page) as $file) {
			$title = file_get_contents('datas/uploaded/' . $file);
			$id = explode('.', $file);
			$data[] = [
				'title' => $title,
				'id' => $id[0]
			];
			$i++;
			if($i > (int) $show){
				break;
			}
		}
		return $data;
	}

	/**
	* Get list recent channel function
	* return array
	* author: Lê Phi
	*/
	function get_channel ($show = 10000000, $page = 0) {
		$files = scan_dir('datas/channel');
		$i = 1;
		$data = [];
		foreach (array_slice($files, $page) as $file) {
			$id = explode('.', $file);
			$data[] = [
				'title' => $id[0],
				'id' => $id[0]
			];
			$i++;
			if($i > (int) $show){
				break;
			}
		}
		return $data;
	}

	/**
	* Get list recent save video function
	* return array
	* author: Lê Phi
	*/
	function get_saved_video ($show = 10000000, $page = 0) {
		$files = scan_dir('datas/saved_video');
		$i = 1;
		$data = [];
		foreach (array_slice($files, $page) as $file) {
			$title = file_get_contents('datas/saved_video/' . $file);
			$title = explode('|split|', $title);
			$id = explode('.', $file);
			$data[] = [
				'title' => $title[0],
				'id' => $id[0]
			];
			$i++;
			if($i > (int) $show){
				break;
			}
		}
		return $data;
	}

	/**
	* Get list recent save video function
	* return array
	* author: Lê Phi
	*/
	function get_saved_playlist ($show = 10000000, $page = 0) {
		$files = scan_dir('datas/saved_playlist');
		$i = 1;
		$data = [];
		foreach (array_slice($files, $page) as $file) {
			$info = file_get_contents('datas/saved_playlist/' . $file);
			$info = explode('|split|', $info);
			$id = explode('.', $file);
			$list_id = preg_split ('/$\R?^/m', $info[1]);
			$data[] = [
				'id' => $id[0],
				'title' => $info[0],
				'list_id' => $list_id,
				'channel' => $info[2],
				'status' => $info[3]
			];
			$i++;
			if($i > (int) $show){
				break;
			}
		}
		return $data;
	}

	/**
	* Get list token function
	* return array
	* author: Lê Phi
	*/
	function get_token ($show = 10000000, $page = '') {
		$files = scan_dir('datas/token');
		$i = 1;
		$data = [];
		foreach (array_slice($files, $page) as $file) {
			$title = file_get_contents('datas/token/' . $file);
			$id = explode('.', $file);
			$data[] = [
				'title' => $id[0],
				'id' => $title
			];
			$i++;
			if($i > (int) $show){
				break;
			}
		}
		return $data;
	}

	function get_info_video_form_id ($id = 'empty') {
		$info = utf8_encode(CURL('https://www.googleapis.com/youtube/v3/videos?key='.config('google_key_api').'&part=snippet,contentDetails,statistics&id='.$id));
		$i = [];
		$s = json_decode($info, true);
    if(is_array($s['items'])){
			$i['name'] = utf8_decode($s['items']['0']['snippet']['title']);
			$i['desc'] = utf8_decode($s['items']['0']['snippet']['description']);
			if(!empty($s['items']['0']['snippet']['tags'])){
				$i['tags'] = $s['items']['0']['snippet']['tags'];
				$i['tags2'] = utf8_decode(implode(', ', $i['tags']));
			}
    }
		return $i;
	}

	function get_link_download_form_id($id){
		$source = file_get_contents('http://api.waptube.net/video-mp4?v='.$id);
		if(preg_match_all('#<td width="65%">(.+?)</td>#is', $source, $title)){
			preg_match_all('#href="(.+?)"#is', $source, $link);
			$info['link'] = $link[1][0];
			return $link[1][0];
		}
	}

	function translate ($text, $language) {
		$text = strtolower($text);
		if($language === 'default'){
			return $text;
		}
		$data = CURL('https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=' . $language . '&&dt=t&dt=bd&dt=rm&dj=1&q=' . urlencode($text));
		$data = json_decode($data, true);
		if(empty($data['sentences'][0]['trans'])){
			return $text;
		}
		return ucwords($data['sentences'][0]['trans']);
	}
	/**
	*	Function get file in folder sort by time create
	*	return array
	*	Author: Undefined (source in stackoverflow.com)
	*/
	function scan_dir ($dir) {
	    $ignored = array('.', '..', '.svn', '.htaccess');

	    $files = array();    
	    foreach (scandir($dir) as $file) {
	        if (in_array($file, $ignored)) continue;
	        $files[$file] = filemtime($dir . '/' . $file);
	    }

	    arsort($files);
	    $files = array_keys($files);

	    return ($files) ? $files : array();
	}

	/**
	*	Function redirect page
	*	Author: Lê Phi
	*/
	function redirect ($uri) {
		header('location: ' . $uri);
	}

	/**
	*	Function CURL get content of page with link
	*	Author: Lê Phi
	*/
	function CURL ($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		return curl_exec($ch);
	}

	/**
	*	Function Youtube Format Time
	*	Author: Lê Phi
	*/
	function YT_format_time($t) {
		$time = str_replace('PT','',$t);
		$time = str_replace('H',' giờ ',$time);
		$time = str_replace('M',' phút ',$time);
		$time = str_replace('S',' giây',$time);
		return $time;
	}

	/**
	*	Function Json response
	*	Author: Lê Phi
	*/
	function json($result, $array) {
		header('Content-Type: application/json');
		$data = [
			'result' => $result,
			'response' => $array
		];
		echo json_encode($data);
	}

	function CheckVideo($id){
        $info = get_headers('https://i.ytimg.com/vi/'.$id.'/default.jpg');
        if(preg_match('#404#is', $info[0])){
            return false;  
        }else{
					return true;
        }
    }