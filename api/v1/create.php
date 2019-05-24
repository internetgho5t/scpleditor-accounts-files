<?php
// Create ScPL file
require( "../request.php" );
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if ( $auth === true ) {
	$name = $_POST[ 'name' ];
	$contents = $_POST[ 'contents' ];
	$type = $_POST[ 'type' ];
	if ( !$name || !$type ) {
		if ( !$name )echo json_response( "error", "No item name was recieved." );
		else if ( !$type )echo json_response( "error", "No item type was recieved." );
	} else {
		$file_id = randString( 20 );
		if ( mysqli_query( $connect, "insert into data.files (id,name,type,author) values ('" . $file_id . "','" . $name . "','$type','$id')" ) ) {
			if ( $type === "file" ) {
				if ( file_put_contents( "../../scpl-files/$id/$name", $contents ) )echo json_response( "success", "File $name was successfully created." );
				else echo json_response( "error", "Internal file system error creating file $name." );
			} else {
				if ( makeFolder( "../../scpl-files/$id/$name" ) )echo json_response( "success", "Folder $name was successfully created." );
				else echo json_response( "error", "Internal file system error creating folder $name." );
			}
		} else echo json_response( "error", "Internal database error creating file $name." );
	}
}