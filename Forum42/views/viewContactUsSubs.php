<?php

// creates a instance of the ContactController class
// to send request and retrieve contact us information to display
// to the site admin in a stupid table when the page is loaded
require_once( WEB_CTLS_CONTACT_US );

    if(isset($_SESSION['userInfo'])){
        $userInfo = json_decode($_SESSION['userInfo'], true);
    }
    if($userInfo['is-site-admin'] != 1){
        header("Location: /Forum42/404.php");
        exit;
    }
    $create = new ContactController();
    $contactInfo = $create->getAllContactUsInfo();
?>
<div class="table-wrapper">
    <div class="table-container">
    <table style="width:100%">
    <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th> 
        <th>Email</th>
        <th>Type</th>
        <th>Comment</th>
    </tr>
    <?php
    foreach ($contactInfo['data'] as $info) {
        $id = $info['contact_us_id'];
        $firstName = $info['contact_us_fname'];
        $lastName = $info['contact_us_lname'];
        $email = $info['contact_us_email'];
        $desc = $info['contact_us_type_desc'];
        $comment = $info['contact_us_comment'];
        print("<tr>");
            print("<td>$id</td>");
            print("<td>$firstName</td>");
            print("<td>$lastName</td>");
            print("<td>$email</td>");
            print("<td>$desc</td>");
            print("<td>$comment</td>");
        print("</tr>");
    }
    
    ?>
    </table>
    </div>
</div>
