	<div class="col-md-12">
		<div class="panel panel-danger">
			<div class="panel-heading">Get authentical channel youtube</div>
			<div class="panel-body">
				<div class="form-group">
					<div class="input-group"><span class="input-group-addon" id="sizing-addon1">Channel name:</span>
						<input id="txtChannelName" type="text" class="form-control" placeholder="Channel name" aria-describedby="sizing-addon1">
					</div>
				</div>
				<div class="form-group">
					<div class="input-group"><span class="input-group-addon" id="sizing-addon1">Channel Token:</span>
						<input id="txtChannelToken" type="text" class="form-control" placeholder="Channel token" aria-describedby="sizing-addon1" <?php $data = isset($_GET['token']) ? 'value="' . $_GET['token'] . '" readonly' : ''; echo $data; ?>>
					</div>
				</div>
				<div class="form-group">
					<div class="pull-left"><code>Click Get Token để lấy quyền quản lý kênh youtube của bạn</code></div>
					<div class="pull-right"><button id="load" class="btn btn-primary">Lưu</button> <button onclick="window.location.href = 'ajax.php?act=authentical';" class="btn btn-danger">Get Token</button></div>
					<div class="clearfix"></div>
				</div>
				<div class="form-group">
					<div id="error" style="display: none" class="alert alert-danger" role="alert"></div>
				</div>
				<div class="form-group">
					<table class="table table-hover" style="display:none">
						<thead>
							<tr>
								<th style=" width: 65%; ">Info</th>
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
	</div>
	<script>
		$(document).on('click', '#load', function () {
			$('#error').text('').hide();
			var channelName = $('#txtChannelName').val();
			var channelToken = $('#txtChannelToken').val();
			$.ajax({
				url: 'ajax.php?act=save',
				type: 'post',
				data: {
					channel_name: channelName,
					channel_token: channelToken,
					type: 'save-token'
				},
				beforeSend: function(){
			        $("#load").prop("disabled", true).html('<i class="glyphicon glyphicon-refresh gly-spin"></i>');
				},
				success: function (data) {
					if(data.result){
						alert('Thành công! trang sẽ tự reload sau 3 giây nữa');
						setTimeout(function () { window.location.reload(); }, 3000);
					}else{
						$('#error').text(data.response.message).show();
					}
					$("#load").prop("disabled", false).html('Lưu');
				},
				error: function () {
					$("#load").prop("disabled", false).html('Lưu');
					alert('Gặp lỗi! Vui lòng F5 và thử lại');
				}
			});
		});
		
		$(document).ready(function () {
			$.ajax({
				url: 'ajax.php?act=get-auth',
				success: function (data) {
					if(data.result){
						addHTML(data);
						$('.table-hover').show();
					}else{
						$('#error').text(data.response.message).show();
					}
					$("#load").prop("disabled", false).html('Lưu');
				},
				error: function () {
					$("#load").prop("disabled", false).html('Lưu');
					alert('Gặp lỗi! Vui lòng F5 và thử lại');
				}
			});
		});

		$(document).on('click', '#next', function () {
			var page = $('#page').val();
			$.ajax({
				url: 'ajax.php?act=get-auth',
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
			$.each(list_video, function (key, item) {
				var html = '<tr id="token-'+item.title+'"><td><p><b>Channel Name</b>: <span class="label label-success">' + item.title + '</span></p></td><td><button id="' + item.title + '" onclick="delete_token(\'' + item.title + '\');" class="btn btn-sm btn-primary">Xóa</button></td></tr>';
				$('#result').append(html);
			});
		}

		function delete_token(id){
			$.ajax ({
				url: 'ajax.php?act=delete',
				type: 'post',
				data: {
					id: id,
					type: 'channel-token'
				},
				beforeSend: function(){
			        $("#" + id).prop("disabled", true).html('<i class="glyphicon glyphicon-refresh gly-spin"></i>');
				},
				success: function (data) {
					if(data.result){
						window.location.reload();
					}else{
						$("#" + id).prop("disabled", false).html('Lỗi').removeClass('btn-danger').addClass('btn-primary');
					}
				},
				error: function () {
					alert('Gặp lỗi! Vui lòng thử lại');
				}
			});
		}
	</script>
