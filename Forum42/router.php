<?php
// get the request URI from URL
$request = $_SERVER['REQUEST_URI'];
// get the QUERY_STING param from URL 
$value = strval($_SERVER["QUERY_STRING"]);

// look at each request URI and dictate if it is a valid
// URL, else redirect them to a 404 default page.
switch ($request) {
    case '/Forum42/' :
		$page_to_load = 'home.php';
        break;
    case '' :
		$page_to_load = 'home.php';
        break;
    case '/Forum42/create-account' :
		$page_to_load = 'createAccount.php';
        break;
    case '/Forum42/login' :
    $page_to_load = 'login.php';
        break;
    case '/Forum42/create-forum' :
        $page_to_load = 'createForum.php';
        break;
    // use query value to redirect to correct forum
    case "/Forum42/forum?$value" :
        $page_to_load = 'getForum.php';
        break;
    // use query value to know which forum this thread
    // is being created for
    case "/Forum42/create-thread?$value" :
        $page_to_load = 'createThread.php';
        break;
    // user query value to redirect to correct thread.
    case "/Forum42/view-thread?$value" :
        $page_to_load = 'getThread.php';
        break;
    case "/Forum42/manage-account?$value" :
        $page_to_load = 'manageAccount.php';
        break;
    case "/Forum42/contact-us" :
        $page_to_load = 'contactUs.php';
        break;
    case "/Forum42/view-contact-us" :
        $page_to_load = 'viewContactUsSubs.php';
        break;
    default: 
		$page_to_load = '404.php';
        break;
}
?>