	<div class="col-md-12">
		<div class="panel panel-danger">
			<div class="panel-heading">Manage Multi Channel</div>
			<div class="panel-body">
				<div class="form-group">
					<div class="input-group"><span class="input-group-addon" id="sizing-addon1">Thêm mới:</span>
						<input id="txtKeyword" type="text" class="form-control" placeholder="UCjLDsaRFbUhGeXHIZC6Xn6g" aria-describedby="sizing-addon1">
						<span class="input-group-btn"><button id="load" class="btn btn-default">THÊM</button></span>
					</div>
				</div>
				<div class="form-group">
					<div id="error" style="display: none" class="alert alert-danger" role="alert"></div>
				</div>
				<div id="Download" style=" margin-top: 25px">
					<table class="table" id="ListVideo">
						<thead>
							<tr>
								<th style="font-size:14px">List channel</th>
							</tr>
						</thead>
					<tbody></tbody>
					</table>
				</div>
				<div class="form-group">
					<table class="table table-hover">
						<thead>
							<tr>
								<th style="width:90%">Info</th>
								<th>Action</th>
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
	</div>
	<script>
		$(document).ready(function () {
			$.ajax({
				url: 'ajax.php?act=<?php echo htmlentities($_GET['act']); ?>',
				success: function (data) {
					if(data.result){
						addHTML(data);
						$('.table-hover').show();
					}else{
						$('.table-hover').hide();
						$('#error').text(data.response.message).show();
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
				url: 'ajax.php?act=<?php echo htmlentities($_GET['act']); ?>',
				type: 'post',
				data: {
					page: page
				},
				beforeSend: function(){
			        $("#next").prop("disabled", true).html('<i class="glyphicon glyphicon-refresh gly-spin"></i>');
				},
				success: function (data) {
					if(data.result){
						$('#page').val(page + 1);
						$("#next").prop("disabled", false).html('Next');
						addHTML(data);
					}else{
						$('#error').text(data.response.message).show();
						$("#next").remove();
					}
				},
				error: function () {
					alert('Gặp lỗi! Vui lòng F5 và thử lại');
				}
			});
		});
		
		$(document).on('click', '#load', function () {
			var keyword = $('#txtKeyword').val();
			$.ajax({
				url: 'ajax.php?act=save',
				type: 'post',
				data: {
					id: keyword,
					type: 'channel',
					title: ' '
				},
				beforeSend: function(){
			        $("#load").prop("disabled", true).html('<i class="glyphicon glyphicon-refresh gly-spin"></i>');
				},
				success: function (data) {
					if(data.result){
						alert(data.response.message);
					}else{
						$('#error').text(data.response.message).show();
					}
					$("#load").prop("disabled", false).html('THÊM');
				},
				error: function () {
					alert('Gặp lỗi! Vui lòng F5 và thử lại');
				}
			});
		});

		function addHTML (data) {
			var list_video = data.response;
			$.each(list_video, function (key, item) {
				var html = '<tr id="channel_'+item.id+'"><td><p><b>ID:</b> <a href="http://youtube.com/channel/' + item.id + '" target="_blank">' + item.id + '</a></p><p><b>Tên Channel:</b> <span class="label label-success"><a href="http://youtube.com/channel/' + item.id + '" target="_blank" style="color:white">' + item.title + '</a></span></p><p><b>Publish at:</b> ' + item.publish + ' - <b>Tổng video:</b> ' + item.videos + ' - <b>Tổng views:</b> ' + item.views + ' - <b>Tổng comments:</b> ' + item.comments + ' - <b>Tổng theo giõi:</b> ' + item.subscribes + '</p></td><td><button onclick="delete_channel(\'' + item.id + '\')" id="'+item.id+'" class="btn btn-sm btn-danger">Xóa</button></td></tr>';
				$('#result').append(html);
				$('#list-playlist-id').append(item.id + '\n');
			});
		}

		function delete_channel(id){
			$.ajax ({
				url: 'ajax.php?act=delete',
				type: 'post',
				data: {
					id: id,
					type: 'channel',
				},
				beforeSend: function(){
			        $("#" + id).prop("disabled", true).html('<i class="glyphicon glyphicon-refresh gly-spin"></i>');
				},
				success: function (data) {
					if(data.result){
						$("#channel_" + id).remove();
					}else{
						$("#" + id).prop("disabled", false).html('Lỗi').removeClass('btn-primary').addClass('btn-danger');
					}
				},
				error: function () {
					alert('Gặp lỗi! Vui lòng thử lại');
				}
			});
		}
	</script>
