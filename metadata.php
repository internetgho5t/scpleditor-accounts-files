<?php
if ( $page === "login" ) {
	$title = "Login";
} else if ( $page === "sign-up" ) {
	$title = "Sign Up";
} else if ( $page === "forgot" ) {
	$title = "Forgot Password";
} else if ( $page === "reset" ) {
	$title = "Reset Password";
} else if ( $page === "confirm" ) {
	$title = "Email Confirmation";
} else if ( $page === "files" ) {
	$title = "Files";
	$files_active = " active-side-item";
} else if ( $page === "shared" ) {
	$title = "Shared with me";
	$shared_active = " active-side-item";
}
if ( !$nav_title )$nav_title = $title;
if ( $title )echo "<title>$title - ScPL Editor</title>";
else {
	$nav_title = "Account Settings";
	echo "<title>Account Settings - ScPL Editor</title>";
	$account_active = " active-side-item";
}
