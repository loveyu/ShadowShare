<?php
/**
 * @var $this UView\Home
 */
$this->get_header("分享你的图片和你希望写下的内容");
?>
	<div class="share-box">
		<h1>分享你的图片和咔咔</h1>

		<form id="SharePicture" ondragenter="return false" ondragover="return false" ondrop="dropIt(event)"
			  action="<?php echo get_url('Api', 'Create', 'picture_text') ?>" method="post" enctype="multipart/form-data">
			<div class="share-file-select-box share-file-select-box-hover row">
				<div class="col-sm-4 hide">
					<img class="img-thumbnail" id="PreviewImage"
						 src="data:image/gif;base64,R0lGODlhAQABAIAAAP///////yH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
				</div>
				<div>
					<p class="info">拖拽你的图片到此，<br>或选择你的图片，最大<span>200KB</span>。</p>

					<div id="UploadProgress" class="progress progress-striped active" style="display: none">
						<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">
							<span class="text-danger"></span>
						</div>
					</div>
				</div>
			</div>
			<input id="InputFile" type="file" style="display: none" name="file">

			<div id="InputTextBox" style="display: none;">
				<div class="form-group row">

					<div class=" col-lg-offset-2 col-lg-8 text-left">
						<label for="InputText">写下语录(5-200字) <span id="InputLeftChar">剩余200</span>: </label>
						<textarea id="InputText" class="form-control" rows="4"></textarea>
					</div>

				</div>
				<div class="form-inline">
					<div class="form-group">
						<label class="sr-only" for="InputPosition">对齐方式</label>

						<div class="input-group">
							<div class="input-group-addon">图片对齐方式</div>
							<select id="InputPosition" class="form-control">
								<option value="0">顶部居中</option>
								<option value="1">左浮动</option>
								<option value="2">右浮动</option>
								<option value="3">底部居中</option>
							</select>
						</div>
					</div>
					<button type="button" class="btn btn-primary">分享</button>
				</div>
			</div>
		</form>
		<div id="Result" style="display: none"></div>
		<script>
			var file_save = null;
			var text;
			function dropIt(event) {
				select_file(event.dataTransfer.files.item(0));
				event.stopPropagation();
				event.preventDefault();
			}
			function select_file(file) {
				if (typeof file['name']=="undefined") {
					alert("请选择正确的图片");
					return;
				}
				var type = file.type.split('/');
				if (type[0] !== 'image') {
					alert(file.name + " 不是图片文件");
					return;
				}
				if (file.size > 204800) {
					alert("文件大于200KB");
					return;
				}
				$(".share-file-select-box p.info").html("文件 : " + file.name + "<br>" + (file.type != "" ? ("类型 : " +
				file.type + "<br>") : "") + "大小 : " + file.size).addClass("text-left");
				file_save = file;
				var obj = $(".share-file-select-box div");
				$(obj[0]).removeClass('hide');
				$(obj[1]).addClass('col-sm-8');
				var imageReader = new FileReader();
				imageReader.onload = function (e) {
					$("#PreviewImage")[0].src = e.target.result;
				};
				imageReader.readAsDataURL(file);
				$("#UploadProgress").slideDown();
				$("#InputTextBox").slideDown();
				var text_change = function(){
					$("#InputLeftChar").html("剩余"+(200-$("#InputText").val().length));
				};
				$("#InputText").change(text_change).keyup(text_change);
			}
			$("#InputFile").change(function () {
				select_file(this.files.item(0));
			});
			$(".share-file-select-box").click(function () {
				$("#InputFile").click();
			});
			$("#SharePicture button.btn").click(function () {
				text = $("#InputText").val();
				if(text.length>200){
					alert("字符超过两百字符");
					return;
				}
				if(text.length<5){
					alert("字符不足5个长度");
					return;
				}
				if (file_save == null) {
					alert("图片未选中");
				} else {
					var xhr = new XMLHttpRequest(); //创建请求对象
					xhr.upload.addEventListener("progress", e_process, false);
					xhr.addEventListener("load", e_complete, false);
					xhr.addEventListener("error", e_failed, false);
					xhr.addEventListener("abort", e_canceled, false);
					xhr.open("POST", $("#SharePicture").attr('action'), true);
					var fd = new FormData(); //创建表单
					fd.append("text", text);
					fd.append("position", $("#InputPosition").val());
					fd.append("picture", file_save);
					xhr.send(fd);
				}
			});
			function e_failed(evt) {
				alert("出错:" + this.status + " - " + this.statusText);
			}
			function e_canceled(evt) {
				alert("被取消:" + this.status + " - " + this.statusText);
			}
			function e_complete(evt) {
				var data = $.parseJSON(this.response);
				if (!data.status) {
					alert('分享失败，请重试:' + data.msg);
					return true;
				}
				$("#InputTextBox").hide();
				$("#UploadProgress").slideUp();
				$(".share-file-select-box").unbind("click").removeClass("share-file-select-box-hover");
				$(".share-file-select-box p.info").slideUp("fast", function () {
					$(this).html("分享地址为 : <span>" + data.data.url + "</span><br>图片地址为 : <span>"
					+ data.data.image + "</span><br>HTML对象地址为 : <span>"
					+ data.data.html + "</span>").slideDown("fast").removeClass("text-left").css("padding-top", "15px");
				}).before("<div id='PictureTextBox' class='picture-text-box-preview'>"+(text.replace(/[\n|\r]/g,"<br>"))+"</div>");
			}
			function e_process(evt) {
				if (evt.lengthComputable) {
					var percentComplete = Math.round(evt.loaded * 100 / evt.total) + '%';
					$("#UploadProgress .progress-bar").width(percentComplete);
					if (percentComplete == "100%") {
						percentComplete = "上传结束，处理中！";
					}
					$("#UploadProgress .progress-bar span").html(percentComplete);
				}
			}
		</script>
	</div>
<?php $this->get_footer();