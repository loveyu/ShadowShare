<?php
/**
 * @var $this UView\Home
 */
$this->get_header("分享一张图片");
?>
	<div class="share-box">

		<form id="SharePicture" ondragenter="return false" ondragover="return false" ondrop="dropIt(event)"
			  action="<?php echo get_url('Api', 'Create', 'picture') ?>" method="post" enctype="multipart/form-data">
			<div class="share-file-select-box share-file-select-box-hover row">
				<div class="col-sm-4 hide">
					<img class="img-thumbnail" id="PreviewImage"
						 src="data:image/gif;base64,R0lGODlhAQABAIAAAP///////yH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
				</div>
				<div>
					<p class="info">拖拽你的图片到此，<br>或选择你的图片，最大<span>5MB</span>。</p>

					<div id="UploadProgress" class="progress progress-striped active" style="display: none">
						<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">
							<span class="text-danger"></span>
						</div>
					</div>
				</div>
			</div>
			<input id="InputFile" type="file" style="display: none" name="file">
			<button type="button" class="btn btn-primary" style="display: none;">分享</button>
		</form>
		<div id="Result" style="display: none"></div>
		<script>
			var file_save = null;
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
				if (file.size > 1024 * 1024 * 5) {
					alert("文件大于5MB");
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
				$("#SharePicture button.btn").show();
				$("#UploadProgress").slideDown();
			}
			$("#InputFile").change(function () {
				select_file(this.files.item(0));
			});
			$(".share-file-select-box").click(function () {
				$("#InputFile").click();
			});
			$("#SharePicture button.btn").click(function () {
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
				$("#SharePicture button.btn").hide();
				$("#UploadProgress").slideUp();
				$(".share-file-select-box").unbind("click").removeClass("share-file-select-box-hover");
				$(".share-file-select-box p.info").slideUp("fast", function () {
					$(this).html("分享地址为 : <span>" + data.data.url + "</span><br>图片地址为 : <span>"
					+ data.data.image + "</span>").slideDown("fast").removeClass("text-left").css("padding-top","15px");
				});
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