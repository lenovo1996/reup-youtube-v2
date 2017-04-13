	<div class="col-md-12">
		<div class="panel panel-danger">
			<div class="panel-heading">Get information video with keyword</div>
			<div class="panel-body">
				<div class="form-group">
					<div class="input-group"><span class="input-group-addon" id="sizing-addon1">Keyword:</span>
						<input id="txtKeyword" type="text" class="form-control" placeholder="Game, Story, Music, Movie, ..." aria-describedby="sizing-addon1">
						<span class="input-group-btn"><button id="load" class="btn btn-default">GET</button></span>
					</div>
				</div>
				<div class="form-group">
					<div id="error" style="display: none" class="alert alert-danger" role="alert"></div>
				</div>
				<div class="form-group">
					<div id="Download" style=" margin-top: 25px">
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
					</div>
					<table class="table table-hover" style="display:none">
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
			</div>
			<div class="form-group text-center">
				<input type="text" hidden id="next-page">
				<button class="btn btn-sm btn-primary" id="nextpage" style="display:none">Next</button>
			</div>
		</div>
	</div>
	<div class="modal fade" id="add-video">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Save Video</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>Id Video:</label>
						<input id="id-video" value="" class="form-control">
					</div>
					<div class="form-group">
						<input id="title-video" value="" class="form-control">
					</div>
					<div class="form-group">
						<label>Lưu vào channel:</label>
						<select id="select-channel" class="form-control"></select>
					</div>
					<div class="form-group">
						<label>Dịch sang ngôn ngữ:</label>
						<select id="language" class="form-control">
							<option value="default">Không đổi</option>
							<option value="af">Tiếng Afrikaans</option>
							<option value="sq">Tiếng Albanian</option>
							<option value="ar">Tiếng Arabic</option>
							<option value="hy">Tiếng Armenian</option>
							<option value="az">Tiếng Azerbaijani</option>
							<option value="eu">Tiếng Basque</option>
							<option value="be">Tiếng Belarusian</option>
							<option value="bn">Tiếng Bengali</option>
							<option value="bg">Tiếng Bulgarian</option>
							<option value="ca">Tiếng Catalan</option>
							<option value="zh-CN">Tiếng Chinese (Simplified)</option>
							<option value="zh-TW">Tiếng Chinese (Traditional)</option>
							<option value="hr">Tiếng Croatian</option>
							<option value="cs">Tiếng Czech</option>
							<option value="da">Tiếng Danish</option>
							<option value="nl">Tiếng Dutch</option>
							<option value="en">Tiếng English</option>
							<option value="et">Tiếng Estonian</option>
							<option value="tl">Tiếng Filipino</option>
							<option value="fi">Tiếng Finnish</option>
							<option value="fr">Tiếng French</option>
							<option value="gl">Tiếng Galician</option>
							<option value="ka">Tiếng Georgian</option>
							<option value="de">Tiếng German</option>
							<option value="el">Tiếng Greek</option>
							<option value="gu">Tiếng Gujarati</option>
							<option value="ht">Tiếng Haitian Creole</option>
							<option value="iw">Tiếng Hebrew</option>
							<option value="hi">Tiếng Hindi</option>
							<option value="hu">Tiếng Hungarian</option>
							<option value="is">Tiếng Icelandic</option>
							<option value="id">Tiếng Indonesian</option>
							<option value="ga">Tiếng Irish</option>
							<option value="it">Tiếng Italian</option>
							<option value="ja">Tiếng Japanese</option>
							<option value="kn">Tiếng Kannada</option>
							<option value="ko">Tiếng Korean</option>
							<option value="la">Tiếng Latin</option>
							<option value="lv">Tiếng Latvian</option>
							<option value="lt">Tiếng Lithuanian</option>
							<option value="mk">Tiếng Macedonian</option>
							<option value="ms">Tiếng Malay</option>
							<option value="mt">Tiếng Maltese</option>
							<option value="no">Tiếng Norwegian</option>
							<option value="fa">Tiếng Persian</option>
							<option value="pl">Tiếng Polish</option>
							<option value="pt">Tiếng Portuguese</option>
							<option value="ro">Tiếng Romanian</option>
							<option value="ru">Tiếng Russian</option>
							<option value="sr">Tiếng Serbian</option>
							<option value="sk">Tiếng Slovak</option>
							<option value="sl">Tiếng Slovenian</option>
							<option value="es">Tiếng Spanish</option>
							<option value="sw">Tiếng Swahili</option>
							<option value="sv">Tiếng Swedish</option>
							<option value="ta">Tiếng Tamil</option>
							<option value="te">Tiếng Telugu</option>
							<option value="th">Tiếng Thai</option>
							<option value="tr">Tiếng Turkish</option>
							<option value="uk">Tiếng Ukrainian</option>
							<option value="ur">Tiếng Urdu</option>
							<option value="vi">Tiếng Vietnamese</option>
							<option value="cy">Tiếng Welsh</option>
							<option value="yi">Tiếng Yiddish</option>
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
		});

		$(document).on('click', '#load,#nextpage', function () {
			$('#error').text('').hide();
			var keyword = $('#txtKeyword').val();
			var next_page = $('#next-page').val();
			$.ajax({
				url: 'ajax.php?act=<?php echo htmlentities($_GET['act']); ?>',
				type: 'post',
				data: {
					keyword: keyword,
					next_page: next_page
				},
				beforeSend: function(){
							alertify.custom('Đang tải ...');
			        $(this).prop("disabled", true).html('<i class="glyphicon glyphicon-refresh gly-spin"></i>');
				},
				success: function (data) {
					if(data.result){
						addHTML(data);
						$('.table-hover').show();
						$('#nextpage').show();
					}else{
						$('#error').text(data.response.message).show();
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
			var list_video = data.response.result;
			$.each(list_video, function (key, video) {
				var html = '<tr> <td><a href="http://youtu.be/'  + video.id +  '"><img src="' + video.thumbnail + '" style=" width: 100%; "/></a></td> <td> <h5><a href="http://youtu.be/' + video.id + '" target="_blank">' + video.title + '</a></h5> <p><b>views:</b> ' + video.view + ' - <b>like:</b> ' + video.like + ' - <b>dislike:</b> ' + video.dislike + ' - <b>comment:</b> ' + video.comment + ' - <b>duration:</b> ' + video.duration + '<br /><b>quality:</b> <span class="label label-warning">' + video.duration + '</span><br /><b>Publish at:</b> ' + video.publish + '</p> </td> <td><p><b>Tên Channel:</b><br /><span class="label label-danger"><a href="http://youtube.com/channel/' + video.channel_id + '" target="_blank" style="color:white">' + video.channel_title + '</a></span></p> <p><b>Network:</b><br /><span class="label label-success" id="network_'+video.id+'">'+ video.network +'</span></p></td><td><button id="' + video.id + '" data-title="'+ video.title +'" onclick="save(this);" class="btn btn-sm btn-primary">Lưu</button></td></tr>';
				$('#result').append(html);
			});
			$('#next-page').val(data.response.next_page);
		}

		function save(elem) {
			$('#id-video').val($(elem).attr('id')).prop('readonly', true);
			$('#title-video').val($(elem).attr('data-title')).hide();
			$('#add-video').modal('toggle');
		}

		$(document).on('click', '#save', function () {
			var id = $('#id-video').val();
			var title = $('#title-video').val();
			var channel = $('#select-channel').find('option:selected').val();
			var language = $('#language').find('option:selected').val();
			$.ajax ({
				url: 'ajax.php?act=save',
				type: 'post',
				data: {
					id: id,
					type: 'saved-video',
					language: language,
					channel: channel,
					title: title
				},
				beforeSend: function(){
					alertify.custom('Đang lưu Id: ' + id);
				},
				success: function (data) {
					$('#add-video').modal('toggle');
					if(data.result){
						alertify.success('Lưu thành công Id: ' + id);
					}else{
						alertify.error('Lưu thất bại Id: ' + id);
					}
				},
				error: function () {
					alertify.error('Gặp lỗi! Vui lòng thử lại');
				}
			});
		});
	</script>
