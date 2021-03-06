<?php
// List files shared with the user
require( "../request.php" );
if ( $auth === true ) {
	$shared_files = mysqli_query( $connect, "select * from data.files where type = 'file'" );
	$files = array();
	while ( $file = mysqli_fetch_array( $shared_files ) ) {
		$collab = explode( ",", $file[ 'collab' ] );
		if ( in_array( $id, $collab ) === true ) {
			unset( $updated );
			unset( $relative_updated );
			$fid = $file[ 'id' ];
			$timestamp = $file[ 'timestamp' ];
			$relative = timeago( $timestamp );
			if ( $file[ 'updated' ] ) {
				$updated = $file[ 'updated' ];
				$relative_updated = timeago( $updated );
			}
			$name = $file;
			if ( $itemdata[ 'path' ] )$itempath = $file[ 'path' ] . "/";
			$path = "../../files/$id/$itempath$name";
			$size = formatSize( filesize( $path ) );
			$this_file = array(
				"id" => $fid,
				"type" => "file",
				"name" => $name,
				"size" => $size,
				"owner" => $file[ 'author' ],
				"timestamp" => $timestamp,
				"updated" => $updated,
				"relativeTimestamp" => $relative,
				"updated" => $updated,
				"relativeUpdated" => $relative_updated
			);
			array_push( $files, $this_file );
		}
	}
	if ( count( $files ) === 0 ) {
		echo json_response( "error", "No files have been shared with this user." );
		http_response_code( 404 );
	} else {
		echo json_encode( $files );
		http_response_code( 200 );
	}
}