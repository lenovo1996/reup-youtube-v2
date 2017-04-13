	<div class="col-md-12">
		<div class="panel panel-danger">
			<div class="panel-heading">List Playlist Đã Lưu</div>
			<div class="panel-body">
				<div class="form-group pull-right">
					<button class="btn btn-sm btn-primary" data-toggle="modal" href='#add-playlist'>Thêm Mới Playlist</button>
				</div>
				<div class="clearfix"></div>
				<div class="form-group">
					<div id="error" style="display: none" class="alert alert-danger" role="alert"></div>
				</div>
				<div class="form-group">
					<div id="Download">
						<div id="Download">
							<table class="table" id="ListVideo">
								<thead>
									<tr>
										<th style="font-size:14px">Result</th>
									</tr>
								</thead>
							<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="list-group" id="result">
					
				</div>
			</div>
			<div class="form-group text-center">
				<input type="text" hidden value="1" id="next-page">
				<button class="btn btn-sm btn-primary" id="nextpage">Next</button>
			</div>
		</div>
	</div>
	<div class="modal fade" id="add-playlist">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Create Playlist</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>Danh sách tiêu đề muốn tạo (1 playlist 1 dòng):</label>
						<textarea id="title" class="form-control"></textarea>
					</div>
					<div class="form-group">
						<label>Id video sẽ thêm vào playlist (1 video 1 dòng):</label>
						<textarea id="id-video" class="form-control"></textarea>
					</div>
					<div class="form-group">
						<label>Lưu vào channel:</label>
						<select id="select-channel" class="form-control"></select>
					</div>
					<div class="form-group">
						<label>Status:</label>
						<select id="status" class="form-control">
							<option value="public">Công khai</option>
							<option value="private">Chỉ mình tôi</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="save">Lưu</button>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function () {
			$.ajax({
				url: 'ajax.php?act=request-token',
				success: function (data) {
					$.each(data.response, function (key, value) {
						$('#select-channel').append('<option value="' + value.title + '">' + value.title + '</option>');
					});
				},
				error: function () {
					alertify.error('lỗi. vui lòng f5 lại trang và tiếp tục sử dụng!.');
				}
			});
			$.ajax({
				url: 'ajax.php?act=get-save-playlist',
				success: function (data) {
					if(data.result){
						addHTML(data);
					}else{
						alertify.error(data.response.message);
					}
				},
				error: function () {
					alertify.error('lỗi. vui lòng f5 lại trang và tiếp tục sử dụng!.');
				}
			});
		});

		$(document).on('click', '#load,#nextpage', function () {
			var next_page = $('#next-page').val();
			$.ajax({
				url: 'ajax.php?act=get-save-playlist',
				type: 'post',
				data: {
					page: next_page
				},
				beforeSend: function(){
							alertify.custom('Đang tải ...');
				},
				success: function (data) {
					if(data.result){
						addHTML(data);
						$('#nextpage').show();
					}else{
						alertify.error(data.response.message);
					}
					$("#load").prop("disabled", false).html('GET');
				},
				error: function () {
					$("#load").prop("disabled", false).html('GET');
					alertify.error('Gặp lỗi! Vui lòng F5 và thử lại');
				}
			});
		});
		
		function addHTML (data) {
			var list_playlist = data.response;
			$.each(list_playlist, function (key, playlist) {
				var html = '<div class="list-group-item"><span class="pull-left">'+playlist.title+'</span><span class="pull-right"><button class="btn btn-sm btn-primary" data-id="'+playlist.id+'" onclick="create(this)">Tạo ngay</button> <button data-id="'+playlist.id+'" class="btn btn-sm btn-danger" onclick="delete_playlist(this)">Xóa</button></span><div class="clearfix"></div></div>';
				$('#result').append(html);
			});
			var page = parseInt($('#next-page').val());
			$('#next-page').val(page + 1);
		}

		$(document).on('click', '#save', function () {
			var list_title = $('#title').val();
					list_title = list_title.split('\n');
			var list_id = $('#id-video').val();
			var channel = $('#select-channel').find('option:selected').val();
			var status = $('#status').find('option:selected').val();
			var data = new Array();
					data['list_title'] = list_title;
					data['list_id'] = list_id;
					data['channel'] = channel;
					data['status'] = status;
			save(data, 0);
		});

		function save(data, i){
			if(i > data['list_title'].length-1){
				count = data['list_title'].length;
				alertify.custom('Đã xử lý xong ' + count + ' playlist.');
			}else{
				$.ajax({
					url: 'ajax.php?act=save',
					type: 'post',
					data: {
						type: 'save-playlist',
						title: data['list_title'][i],
						list_id: data['list_id'],
						channel: data['channel'],
						status: data['status']
					},
					success: function (result) {
						if(result.result){
							alertify.success('Đã lưu playlist: ' + data['list_title'][i]);
						}else{
							alertify.error('Lưu playlist lỗi: ' + data['list_title'][i]);
						}
						i++;
						save(data, i);
					},
					error: function (){
						alertify.error('Lưu playlist lỗi. Đang thử lại');
						save(data, i);
					},
					fail: function (){
						alertify.error('Lưu playlist lỗi. Đang thử lại');
						save(data, i);
					}
				});
			}
		}

		function create(element){
			var id = $(element).attr('data-id');
			$.ajax({
				url: 'ajax.php?act=create-play-list',
				type: 'post',
				data: {
					id: id
				},
				success: function (data) {
					if(data.result){
						alertify.success('Đã tạo thành công playlist!');
						$(element).remove();
					}else{
						alertify.error('Không tạo được playlist!');
					}
				}
			});
		}

		function delete_playlist(element){
			var id = $(element).attr('data-id');
			$.ajax({
				url: 'ajax.php?act=delete',
				type: 'post',
				data: {
					type: 'saved-playlist',
					id: id
				},
				success: function (data) {
					if(data.result){
						alertify.success('Đã xóa playlist!');
						$(element).parent().parent().remove();
					}else{
						alertify.error('Không xóa được playlist!');
					}
				}
			});
		}
	</script>
