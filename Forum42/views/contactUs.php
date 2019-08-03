<?php

// creates a instance of the ContactController class
// to get the dropdown from the db and displays it to
// to the end user
require_once( WEB_CTLS_CONTACT_US );
$create = new ContactController();
$dropDown = $create->getContactUsDropdown();
$response = "";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contactUs'])){
	$response = $create->updateContactUs();
}
?>
<div class="wrapper">
	<div class="signup-container">
		<div class="form-container">
			<form method="post">
                <input type="hidden" name="contactUs" value="yes">
				<label>First Name:</label>
                <input type="text" id="firstName" name="firstName">
                <label>Last Name:</label>
                <input type="text" id="lastName" name="lastName">
                <label>Email:</label>
                <input type="text" id="email" name="email">
                <label>What Was The Issue:</label>
                <select id="typeId" name="typeId" required>
                    <?php
                    foreach($dropDown['data'] as $item ) {
                        $getId = $item['contact_us_type_id'];
                        $getDesc = $item['contact_us_type_desc'];
                        if($getId != 1){
                            print("<option value='$getId'>$getDesc</option>");
                        }
                        else{
                            print("<option disabled selected value='$getId'>$getDesc</option>"); 
                        }
                    }
                    ?>
                </select>
				<label>Comment:</label>
				<textarea rows="5" id="comment" name="comment" required></textarea>
				<?php print("<p class = 'error'> $response </p>"); ?>
				<input type="submit"  value="Contact Us">
			</form>
		</div>
	</div>
</div>