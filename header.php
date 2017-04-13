<?php
require_once 'settings.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo config('home_title'); ?></title>
	<meta property="og:title" content="<?php echo config('home_title'); ?>"/>
	<meta property="og:image" content="<?php echo config('home_url'); ?>/assets/images/banner-ytb.jpg"/>
	<meta property="og:site_name" content="<?php echo config('home_title'); ?>"/>
	<meta property="og:description" content="<?php echo config('site_description'); ?>"/>
	<meta property="og:site_name" content="Youtube.Com"/>
	
	<!-- Latest compiled and minified CSS & JS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/app.css">
	<link rel="stylesheet" href="assets/css/alertify.core.css">
	<link rel="stylesheet" href="assets/css/alertify.default.css">
	<script src="//code.jquery.com/jquery.js"></script>
	<script src="assets/js/alertify.min.js"></script>
	<script>
		alertify.custom = alertify.extend("custom");
	</script>
</head>
<body>
	
<nav class="navbar-inverse">
	<div class="container-fluid">
		<a class="navbar-brand" href="#"><?php echo config('site_name'); ?></a>
	</div>
</nav>
<div class="container">
	<div class="row">
		<aside class="col-md-3">
			<div class="col-md-12">
				<div class="panel panel-danger">
					<div class="panel-heading">List Tool</div>
					<div class="panel-body">
						<?php
							foreach (asideMenu() as $tool) {
								$is_active = currentTool($tool, $_SERVER['QUERY_STRING']) ? 'active' : '';
								if(!isset($_GET['act']) && $tool['uri'] == './'){
									$is_active = 'active';
								}
								$href = ($tool['uri'] == './') ? $tool['uri'] : '?act=' . $tool['uri'];
								echo '<a class="list-group-item ' . $is_active . '" href="' . $href . '">' . $tool['title'] . '</a>';
							}

						?>
					</div>
				</div>
			</div>
		</aside>
		<section class="col-md-9">