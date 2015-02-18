<?php
/**
 * @var $this UView\Home
 */
$this->get_header("分享一个文件");
?>
	<div class="share-box">

		<form id="ShareFile" ondragenter="return false" ondragover="return false" ondrop="dropIt(event)"
			  action="<?php echo get_url('Api', 'Create', 'file') ?>" method="post" enctype="multipart/form-data">
			<div class="share-file-select-box">
				<p class="info">拖拽你的文件，或点击此处，<br>选择你要上传的文件，最大<span>1024KB</span>。</p>

				<div id="UploadProgress" class="progress progress-striped active" style="display: none">
					<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">
						<span class="text-danger"></span>
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
				if (!file.hasOwnProperty('name')) {
					alert("请选择正确的文件");
					return;
				}
				if (file.size > 1024 * 1024) {
					alert("文件大于1024KB");
					return;
				}
				$(".share-file-select-box p.info").html("文件 : " + file.name + "<br>" + (file.type != "" ? ("类型 : " +
				file.type + "<br>") : "") + "大小 : " + file.size);
				file_save = file;
				$("#ShareFile button.btn").show();
				$("#UploadProgress").slideDown();
			}
			$("#InputFile").change(function () {
				select_file(this.files.item(0));
			});
			$(".share-file-select-box").click(function () {
				$("#InputFile").click();
			});
			$("#ShareFile button.btn").click(function () {
				if (file_save == null) {
					alert("文件未选中");
				} else {
					var xhr = new XMLHttpRequest(); //创建请求对象
					xhr.upload.addEventListener("progress", e_process, false);
					xhr.addEventListener("load", e_complete, false);
					xhr.addEventListener("error", e_failed, false);
					xhr.addEventListener("abort", e_canceled, false);
					xhr.open("POST", $("#ShareFile").attr('action'), true);
					var fd = new FormData(); //创建表单
					fd.append("file", file_save);
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
				console.log(data);
				if (!data.status) {
					alert('分享失败，请重试:' + data.msg);
					return true;
				}
				$("#ShareFile button.btn").hide();
				$("#UploadProgress").slideUp();
				$(".share-file-select-box").unbind("click");
				$(".share-file-select-box p.info").slideUp("fast",function(){
					$(this).html("分享地址为 : <span>" + data.data.url + "</span><br>文件地址为 : <span>" + data.data.download + "</span>").slideDown("fast");
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