<?php

// creates a instance of the CreateCommentController class
// to send request to create comment on submit
require_once( WEB_CTLS_CREATE_COMMENT);
$response = "";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createRootCommentForm'])){
	$create = new CreateCommentController();
	$threadId = intval($_SERVER["QUERY_STRING"]);
	$response = $create->createComment($threadId);
}
?>

<div class="comment-container">
	<form method="post">
		<input type="hidden" name="createRootCommentForm" value="yes">
		<input type='hidden' name='commentLinkRoot' value="0">
		<input type='hidden' name='commentLinkParent' value="0">
		<input type='hidden' name='commentLinkDepth' value="0">
		<textarea rows="8" maxlength="20000" id="commentBody" name="commentBody" required></textarea>
		<?php print("<p class = 'error'> $response </p>"); ?>
		<input type="submit" value="S" name="comment">
		<i class="material-icons">send</i>
	</form>
</div>
