<?php
	include 'settings.php';
	$id = isset($_GET['id']) ? $_GET['id'] : 'Ai_DGniDhjA';
	$Info_video = CURL('https://www.googleapis.com/youtube/v3/videos?key=AIzaSyCc7bEYUZ_zD7oB7AG6x211C0HnbtbRCA0&part=contentDetails,statistics,snippet&id='.$id);
	$list_video = json_decode($Info_video, true);
	foreach ($list_video['items'] as $video) {
		$title = $video['snippet']['title'];
		$desc = $video['snippet']['description'];
	}
	$title = empty($title) ? 'Title Not Found' : $title;
	$desc = empty($desc) ? 'Description Not Found' : $desc;

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $title; ?></title>
	<meta name="description" itemprop="description" content="<?php echo $desc; ?>"/>
	<meta property="og:title" content="<?php echo $title; ?>"/>
	<meta property="og:image" content="https://i.ytimg.com/vi/<?php echo $id; ?>/hqdefault.jpg"/>
	<meta property="og:site_name" content="JAVLIBRARY.COM"/>
	<meta property="og:url" content="<?php echo $_SERVER['HTTP_HOST']; ?>/?id=<?php echo $id?>" />
	<meta property="og:description" content="<?php echo $desc; ?>"/>
	<meta name="twitter:card" content="summary">
	<meta name="twitter:site" content="@publisher_handle">
	<meta name="twitter:title" content="<?php echo $title; ?>">
	<meta name="twitter:description" content="<?php echo $desc; ?>">
	<meta name="twitter:creator" content="@author_handle">
	<meta name="twitter:image" content="https://i.ytimg.com/vi/<?php echo $id; ?>/mqdefault.jpg">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="//code.jquery.com/jquery.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<style>
		.container {
			margin-right: 0px;
    	margin-left: 0px;
    	width: 100%;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#"><?php echo $title; ?></a>
			</div>
	
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<form class="navbar-form navbar-left" role="search">
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Search">
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				</form>
			</div><!-- /.navbar-collapse -->
		</div>
	</nav>

	<div class="container" style="margin-top:70px">
		<div class="row">
			<div class="col-md-9">
				<iframe id="ytplayer" type="text/html" width="100%" height="500"
src="https://www.youtube.com/embed/<?php echo $id; ?>?autoplay=1&fs=0&rel=0&showinfo=0"
frameborder="0" allowfullscreen></iframe>
				<h3><?php echo $title; ?><span class="pull-right"><div class="g-ytsubscribe" data-channel="GoogleDevelopers" data-layout="default" data-count="default"></div></span></h3>
				
				<div class="row" style="margin-top:30px">
					<?php
						$list_channel = get_channel(1000, 0);
						$channel = $list_channel[rand(0, count($list_channel)-1)]['id'];
						$data = CURL('https://www.googleapis.com/youtube/v3/search?part=snippet&key=AIzaSyCc7bEYUZ_zD7oB7AG6x211C0HnbtbRCA0&channelId='.$channel.'&maxResults=12&type=video');
						$data = json_decode($data, true);
						if(!empty($data['items'])){
							$id = [];
							foreach ($data['items'] as $item){
								$id[] = $item['id']['videoId'];
							}
							$list_id = implode(',',$id);
							$Info_video = CURL('https://www.googleapis.com/youtube/v3/videos?key=AIzaSyCc7bEYUZ_zD7oB7AG6x211C0HnbtbRCA0&part=contentDetails,statistics,snippet&id='.$list_id);
							$list_video = json_decode($Info_video, true);
							foreach ($list_video['items'] as $video) {
								echo '<div class="col-md-4" style="margin-bottom:10px">
									<a href="'.$video['id'].'.html">
										<img src="https://i.ytimg.com/vi/'.$video['id'].'/mqdefault.jpg" alt="'.$video['snippet']['title'].'" style="width:100%; height:100%"/>
									</a>
								</div>';
							}
						}

					?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="row">
					<?php
						$list_channel = get_channel(1000, 0);
						$channel = $list_channel[rand(0, count($list_channel)-1)]['id'];
						$data = CURL('https://www.googleapis.com/youtube/v3/search?part=snippet&key=AIzaSyCc7bEYUZ_zD7oB7AG6x211C0HnbtbRCA0&channelId='.$channel.'&maxResults=7&type=video');
						$data = json_decode($data, true);
						if(!empty($data['items'])){
							$id = [];
							foreach ($data['items'] as $item){
								$id[] = $item['id']['videoId'];
							}
							$list_id = implode(',',$id);
							$Info_video = CURL('https://www.googleapis.com/youtube/v3/videos?key=AIzaSyCc7bEYUZ_zD7oB7AG6x211C0HnbtbRCA0&part=contentDetails,statistics,snippet&id='.$list_id);
							$list_video = json_decode($Info_video, true);
							foreach ($list_video['items'] as $video) {
								echo '<div class="col-md-12" style="margin-bottom:10px">
									<a href="'.$video['id'].'.html">
										<img src="https://i.ytimg.com/vi/'.$video['id'].'/mqdefault.jpg" alt="'.$video['snippet']['title'].'" style="width:100%; height:100%"/>
									</a>
								</div>';
							}
						}

					?>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-id">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title text-center">Subscribe Me For Next Video. Thanks You!</h4>
				</div>
				<div class="modal-body text-center">
					<div class="g-ytsubscribe" data-channel="GoogleDevelopers" data-layout="default" data-count="default" data-onytevent="ClearOpenModal"></div>
					<p>(click subscribe for disable modal)</p>
				</div>
			</div>
		</div>
	</div>



	<script>
		$(document).ready(function () {
			var interval = setInterval(function () {
				$('#modal-id').modal('toggle');
			}, 25000);

			function ClearOpenModal(){
				clearInterval(interval);
			}
		});
	</script>
	<script src="https://apis.google.com/js/platform.js"></script>
</body>
</html>