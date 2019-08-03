<?php declare(strict_types = 1); 
// Nicholas Raburn
// INEW-2334-001
// Final project - Forum 42
// Started: 2/17/2019
// Completed: 5/8/2019


// this is a template for all pages in the site
// it pulls the header, nav, router, page list and footer
// into the project to be viewed on each page.
// the WEB_ROUTER determines which page to load and returns
// the correct path to the main view.
// For more info on the website and how it operatesplease 
// turn to the the README file in the base of this directory

// reqire the config.php on all pages.
require( 'config.php' );
// include header.php
require_once( WEB_VIEW_HEADER );
// include nav.php
require_once( WEV_VIEW_NAV );
// include router.php
require_once( WEB_ROUTER );
// include correct web view
require_once( WEB_VIEWS . $page_to_load );
// include footer.php
require_once( WEB_VIEW_FOOTER );

?>