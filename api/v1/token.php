<?php
// Return authentication token
require( "../request.php" );
header( "Access-Control-Max-Age: 3600" );
header( "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With" );
if ( $auth === true ) {
	$key = $_POST[ 'key' ];
	if ( !$key ) {
		echo json_response( "error", "No key was received." );
	} else {
		$token = dataArray( "tokens", $key, "id" );
		if ( !$token ) echo json_response( "error", "Invalid token key." );
		else {
			echo json_encode( array( "token" => $token[ 'token' ] ) );
			http_response_code( 200 );
		}
	}
}
