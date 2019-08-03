<?php

// creates a instance of the CreateForumController class
// to send request to create forum on submit
require_once( WEB_CTLS_CREATE_FORUM );
$response = "";
$uploadResponse = "";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createForumForm'])){
	$create = new CreateForumController();
	$uploadResponse = $create->checkFileUpload('forumIcon','../ForumIcons/');
	if(strpos($uploadResponse, '../') !== false || empty($uploadResponse)){
		$response = $create->createForum($uploadResponse);
		$uploadResponse = "";
	}
}
?>
<div class="wrapper">
	<div class="signup-container">
		<div class="form-container">
			<form method="post" enctype="multipart/form-data">
				<input type="hidden" name="createForumForm" value="yes">
				<label>Forum Name:</label>
				<input type="text" id="forumName" name="forumName" onkeyup="removeSpaces('forumName')" required>
				<label>Forum Description:</label>
				<textarea rows="8" maxlength="255" id="forumDesc" name="forumDesc"></textarea>
				<div class="forum-photo-upload">
					<div id="forum-photo-upload">
						<label class="forum-file-upload">Upload Photo
							<input type="file" id="forumIcon" name="forumIcon">
						</label>
					</div>
				</div>
				<?php print("<p class = 'error'> $response $uploadResponse </p>"); ?>
				<input type="submit" value="Create Forum" name="submit">
			</form>
		</div>
	</div>
</div>