<?php
require_once 'header.php';
?>

	<div class="col-md-12">
		<div class="panel panel-danger">
			<div class="panel-heading">List upload video</div>
			<div class="panel-body">
				<div class="form-group">
				<table class="table table-hover">
					<thead>
						<tr>
							<th style=" width: 15%; ">Thumbnail</th>
							<th style=" width: 65%; ">Info</th>
							<th style=" width: 25%; ">Channel ID</th>
							<th style=" width: 25%; ">Action</th>
						</tr>
					</thead>
					<tbody id="result">
					</tbody>
				</table>
			</div>
			<div class="form-group text-center">
				<input id="page" value="2" hidden>
				<button class="btn btn-sm btn-primary" style="display:none" id="next">Next</button>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function () {
			$.ajax({
				url: 'ajax.php?act=get-data',
				type: 'post',
				data: {
					type: 'upload-video'
				},
				success: function (data) {
					if(data.result){
						addHTML(data);
						$('#next').show();
					}else{
						alert(data.response.message);
					}
				},
				error: function () {
					alert('Gặp lỗi! Vui lòng F5 và thử lại');
				}
			});
		});

		$(document).on('click', '#next', function () {
			var page = $('#page').val();
			$.ajax({
				url: 'ajax.php?act=get-data',
				type: 'post',
				data: {
					type: 'upload-video',
					page: page
				},
				beforeSend: function(){
			        $("#next").prop("disabled", true).html('<i class="glyphicon glyphicon-refresh gly-spin"></i>');
				},
				success: function (data) {
					if(data.result){
						$('#page').val(parseInt(page) + 1);
						$("#next").prop("disabled", false).html('Next');
						addHTML(data);
					}else{
						alert(data.response.message);
						$("#next").remove();
					}
				},
				error: function () {
					alert('Gặp lỗi! Vui lòng F5 và thử lại');
				}
			});
		});
		
		function addHTML (data) {
			var list_video = data.response;
			$.each(list_video, function (key, video) {
				var html = '<tr id="video_'  + video.id +  '"> <td><a href="http://youtu.be/'  + video.id +  '"><img src="' + video.thumbnail + '" style=" width: 100%; "/></a></td> <td> <h5><a href="http://youtu.be/' + video.id + '" target="_blank">' + video.title + '</a></h5> <p><b>views:</b> ' + video.view + ' - <b>like:</b> ' + video.like + ' - <b>dislike:</b> ' + video.dislike + ' - <b>comment:</b> ' + video.comment + ' - <b>duration:</b> ' + video.duration + '<br /><b>quality:</b> <span class="label label-warning">' + video.duration + '</span><br /><b>Publish at:</b> ' + video.publish + '</p> </td> <td><p><b>Tên Channel:</b><br /><span class="label label-danger"><a href="http://youtube.com/channel/' + video.channel_id + '" target="_blank" style="color:white">' + video.channel_title + '</a></span></p> <p><b>Network:</b><br /><span class="label label-success">'+ video.network +'</span></p></td><td><button id="' + video.id + '" onclick="delete_video(\'' + video.id + '\');" class="btn btn-sm btn-danger">Xóa</button></td></tr>';
				$('#result').append(html);
			});
		}

		function delete_video (id) {
			$.ajax ({
				url: 'ajax.php?act=delete',
				type: 'post',
				data: {
					id: id,
					type: 'upload-video'
				},
				success: function (data) {
					if(data.result){
						$("#video_" + id).remove();
					}else{
						alert(data.response.message);
					}
				},
				error: function () {
					alert('Gặp lỗi! Vui lòng thử lại');
				}
			});
		}
	</script>
<?php
require_once 'footer.php';