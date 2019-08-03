<?php
// creates a instance of the CreateCommentController class
// to send request to create comment on submit
require_once( WEB_CTLS_CREATE_COMMENT);
$response = "";
$getDepth = 0;
$depth = 0;

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST[$commentForumId])){
	$create = new CreateCommentController();
	$threadId = intval($_SERVER["QUERY_STRING"]);
	$response = $create->createComment($threadId);
}
?>

<div class="comment-container">
	<form method="post">
		
		<?php
		print("<input type='hidden' name=" .json_encode($commentForumId) ."value='yes'>");
		if(isset($comments['comment_link_depth'])){
			$getDepth = intval($comments['comment_link_depth']);
			$depth = $getDepth + 1;
		}
		if(isset($comments['comment_link_root'])){
			print("<input type='hidden' name='commentLinkRoot' value=" .json_encode($comments['comment_link_root']) .">");
		}
		if(isset($comments['comment_link_parent'])){
			print("<input type='hidden' name='commentLinkParent' value=" .json_encode($comments['comment_link_parent']) .">");
		}
		if(isset($comments['comment_link_depth'])){
			print("<input type='hidden' name='commentLinkDepth' value=" .json_encode($depth) .">");
		}
		print("<textarea rows='8' maxlength='20000' id=" .json_encode($commentBodyId)  ."name='commentBody' required></textarea>");
		?>
		
		<?php print("<p class = 'error'> $response </p>"); ?>
		<input type="submit" value="S" name="comment">
		<i class="material-icons">send</i>
	</form>
</div>
