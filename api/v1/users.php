<?php
// Search user accounts
require( "../request.php" );
if ( $auth === true ) {
	$query = $_POST[ 'query' ];
	if ( !$query ) {
		echo json_response( "error", "No query was recieved." );
	} else {
		$response = array();
		$users = mysqli_query( $connect, "select * from data.users where username like '%$query%' and id<>'$id' order by username asc limit 50" );
		while ( $user = mysqli_fetch_array( $users ) ) {
			$this_user = array(
				"id" => $user[ 'id' ],
				"username" => $user[ 'username' ]
			);
			array_push( $response, $this_user );
		}
		echo json_encode( $response );
		http_response_code( 200 );
	}
}
