<?php
require_once 'header.php';
?>

<?php
	if(isset($_GET['act'])){
		$function = 'tool/' . $_GET['act'] . '.php';
		if(file_exists($function)){
			include($function);
		}else{
			redirect ('./');
		}
	}else{
?>
	<div class="col-xs-12 col-md-8">
		<div class="panel panel-danger">
			<div class="panel-heading">Đồ thị video upload thành công</div>
			<div class="panel-body">
				<canvas id="VideoChart" width="500" height="300" ></canvas>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-md-4">
		<div class="panel panel-danger">
			<div class="panel-heading">Video Upload Gần Đây</div>
			<div class="panel-body">
				<?php
					$recent_upload_video = get_video_upload(config('show_recent_video'));
					foreach ($recent_upload_video as $video) {
						echo '<a href="http://youtu.be/' . $video['id'] . '" class="list-group-item">' . $video['title'] . '</a>';
					}
					if(($recent_upload_video)) {
						echo '<a href="?act=all-upload-video" class="text-center list-group-item">Xem tất cả</a>';
					}
				?>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-md-6">
		<div class="panel panel-danger">
			<div class="panel-heading">Video Đã Lưu</div>
			<div class="panel-body">
				<?php
					$recent_saved_video = get_saved_video(config('show_recent_saved_video'));
					foreach ($recent_saved_video as $video) {
						echo '<a href="http://youtu.be/' . $video['id'] . '" class="list-group-item">' . $video['title'] . '</a>';
					}
					if(($recent_saved_video)) {
						echo '<a href="?act=all-saved-video" class="text-center list-group-item">Xem tất cả</a>';
					}
				?>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-md-6">
		<div class="panel panel-danger">
			<div class="panel-heading">Quản lý channel</div>
			<div class="panel-body">
				<?php
					$recent_channel = get_channel(config('show_recent_channel'));
					foreach ($recent_channel as $channel) {
						echo '<a href="http://youtu.be/' . $channel['id'] . '" class="list-group-item">' . $channel['title'] . '</a>';
					}
					if(($recent_channel)) {
						echo '<a href="?act=manage-multi-channel" class="text-center list-group-item">Xem tất cả</a>';
					}
				?>
			</div>
		</div>
	</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8" async="" defer="">
    <?php $data = chart_upload_video (); ?>
    var ctx = document.getElementById("VideoChart");
	var myChart = new Chart(ctx, {
	    type: 'bar',
	    data: {
	        labels: [
			<?php
		        	foreach (array_reverse($data['date']) as $date){
		        		echo '\'' .$date. '\', ';         	
		        	}
		       ?>
	       	],
	        datasets: [{
	            label: 'Uploaded',
	            data: [
	            	<?php
		        	foreach (array_reverse($data['count']) as $count){
		        		echo '\'' .$count. '\', ';         	
		        	}
		       ?>
	        	],
	            borderWidth: 1
	        }]
	    },
	    options: {
	        legend: {
	            display: true,
	        }
	    }
	});
</script>
<?php
	}
?>
<?php
require_once 'footer.php';