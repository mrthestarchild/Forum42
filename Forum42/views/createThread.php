<?php

// creates a instance of the CreateThreadController class
// to send request to create thread on submit
require_once( WEB_CTLS_CREATE_THREAD );
$response = "";
$uploadResponse = "";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createThreadForm'])){
    $create = new CreateThreadController();
    $forumName = strval($_SERVER["QUERY_STRING"]);
	$uploadResponse = $create->checkFileUpload("threadPhoto","../ThreadPhotos/$forumName/");
	if(strpos($uploadResponse, '../') !== false || empty($uploadResponse)){
		$response = $create->createThread($forumName, $uploadResponse);
		$uploadResponse = "";
	}
}
?>
<div class="wrapper">
	<div class="signup-container thread-signup-container">
		<div class="form-container">
			<form method="post" enctype="multipart/form-data" id="create-thread-form">
				<input type="hidden" name="createThreadForm" value="yes" >
				<label>Thread Title:</label>
				<input type="text" id="threadTitle" name="threadTitle" required>
				<label>Thread Body:</label>
				<textarea rows="8" maxlength="25000" id="threadBody" name="threadBody"></textarea>
				<p>You can either upload a link or a URL for your post. Select the type you would like to upload.</p>
				<div class="radio-group-create-thread" onchange="chooseUploadType()">
					<div class="radio-container">
						<label class="radio-label">URL
							<span class="custom-radio"><span class="custom-radio-selected" id="url-radio"></span></span>
							<input type="radio" id="url" name="uploadSelect" value="url">
						</label>
					</div>
					<div class="radio-container">	
						<label class="radio-label">Image
							<span class="custom-radio"><span class="custom-radio-selected" id="photo-radio"></span></span>
							<input type="radio" id="image" name="uploadSelect" value="image">
						</label>
					</div>
				</div>
				<div id="url-upload">
					<label>URL:</label>
					<input type="text" id="threadURL" name="threadURL">
				</div>
				<div id="photo-upload">
					<label class="file-upload">Upload Photo
						<input type="file" id="threadPhoto" name="threadPhoto">
					</label>
				</div>
				<?php print("<p class = 'error'> $response $uploadResponse </p>"); ?>
				<input type="submit" value="Create Thread" name="createThread">
			</form>
		</div>
	</div>
</div>