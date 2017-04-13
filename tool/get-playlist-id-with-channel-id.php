	<div class="col-md-12">
		<div class="panel panel-danger">
			<div class="panel-heading">Get List Playlist with Channel</div>
			<div class="panel-body">
				<div class="form-group">
					<div class="input-group"><span class="input-group-addon" id="sizing-addon1">Channel Id:</span>
						<input id="txtKeyword" type="text" class="form-control" placeholder="UCjLDsaRFbUhGeXHIZC6Xn6g" aria-describedby="sizing-addon1">
						<span class="input-group-btn"><button id="load" class="btn btn-default">GET</button></span>
					</div>
				</div>
				<div class="form-group">
					<div id="error" style="display: none" class="alert alert-danger" role="alert"></div>
				</div>
				<div id="Download" style=" margin-top: 25px">
					<table class="table" id="ListVideo">
						<thead>
							<tr>
								<th style="font-size:14px">Result</th>
							</tr>
						</thead>
					<tbody></tbody>
					</table>
				</div>
				<div class="form-group" style="display:none">
					<label style="margin-left: 10px" for="list-playlist-id">List Id:</label>
					<textarea id="list-playlist-id" class="form-control" readonly style="height:200px"></textarea>
				</div>
				<table class="table table-hover" style="display:none">
					<thead>
						<tr>
							<th style=" width: 15%; ">Thumbnail</th>
							<th style=" width: 65%; ">Info</th>
							<th style=" width: 25%; ">Channel ID</th>
						</tr>
					</thead>
					<tbody id="result">
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script>
		$(document).on('click', '#load', function () {
			$('#error').text('').hide();
			var keyword = $('#txtKeyword').val();
			$.ajax({
				url: 'ajax.php?act=<?php echo htmlentities($_GET['act']); ?>',
				type: 'post',
				data: {
					channel_id: keyword
				},
				beforeSend: function(){
			        $("#load").prop("disabled", true).html('<i class="glyphicon glyphicon-refresh gly-spin"></i>');
				},
				success: function (data) {
					if(data.result){
						addHTML(data);
						$('#list-playlist-id').parent().show();
						$('.table-hover').show();
					}else{
						$('#error').text(data.response.message).show();
					}
					$("#load").prop("disabled", false).html('GET');
				},
				error: function () {
					$("#load").prop("disabled", false).html('GET');
					alert('Gặp lỗi! Vui lòng F5 và thử lại');
				}
			});
		});
		
		function addHTML (data) {
			var list_video = data.response;
			$.each(list_video, function (key, item) {
				var html = '<tr><td><a href="http://youtube.com/playlist?list='  + item.id +  '"><img src="' + item.thumbnail + '" style=" width: 100%; "/></a></td> <td> <h5><a href="http://youtube.com/playlist?list=' + item.id + '" target="_blank">' + item.title + '</a></h5> <p><b>Playlist Id:</b> ' + item.id + '</p> <p><b>Publish at:</b> ' + item.publish + '</p><p><b>Tổng video:</b> ' + item.count_video + '</p>  </td> <td><p><b>Tên Channel:</b><br /><span class="label label-danger"><a href="http://youtube.com/channel/' + item.channel_id + '" target="_blank" style="color:white">' + item.channel_title + '</a></span></p></td></tr>';
				$('#result').append(html);
				$('#list-playlist-id').append(item.id + '\n');
			});
		}

	</script>
