<?php
require( "global.php" );
$session_token = randString( 10 );

// Account management backend
// if ( $_SERVER[ 'SERVER_ADDR' ] != $_SERVER[ 'REMOTE_ADDR' ] ) {
// 	$this->output->set_status_header( 400, 'No Remote Access Allowed' );
// 	exit; //just for good measure
// } else {
	if ( $action === "createuser" ) {
		$username = e( clean( $_POST[ 'username' ] ) );
		$email = e( $_POST[ 'email' ] );
		$raw_password = $_POST[ 'password' ];
		$options = [ 'cost' => 12, ];
		$password = password_hash( $raw_password, PASSWORD_BCRYPT, $options );
		$check_username = dataArray( "users", $username, "username" );
		$check_email = dataArray( "users", $email, "email" );
		if ( !$check_username && !$check_email ) {
			if ( mysqli_query( $connect, "insert into data.users (username,email,password) values ('" . $username . "','" . $email . "','" . $password . "')" ) ) {
				$user_id = mysqli_insert_id( $connect );
				$confirm_token = randString( 10 );
				if ( mysqli_query( $connect, "insert into data.tokens (user_id,token) values ('" . $user_id . "','" . $session_token . "')" ) && mysqli_query( $connect, "insert into data.tokens (user_id,token) values ('" . $user_id . "','" . $confirm_token . "')" ) ) {
					$link = "<a href='https://account.scpl.dev/confirm-email/$confirm_token' class='link-btn'>Confirm Email Address</a>";
					if ( sendEmail( $_POST[ 'email' ], "donotreply@scpl.dev", "Activate Your Account", "Account activation", "Here's the link to confirm your email and activate your account:<br/><br/>$link<br/><br/>Don't share this with anyone." ) ) {
						$_SESSION[ 'user_id' ] = $user_id;
						$token_id = mysqli_insert_id( $connect );
						echo $token_id;
					} else echo "Error sending confirmation email.";
				} else echo "Error creating user token.";
			} else echo "Error creating account.";
		} else if ( $check_email && $check_username )echo "Username and email address are both taken.";
		else if ( $check_username )echo "Sorry, but someone beat you to that username.";
		else if ( $check_email )echo "Email is already in use.";
	}
	if ( $action === "startsession" ) {
		$email = e( $_POST[ 'email' ] );
		$raw_password = $_POST[ 'password' ];
		$account = dataArray( "users", $email, "email" );
		if ( $account ) {
			if ( password_verify( $raw_password, $account[ 'password' ] ) ) {
				$user_id = $account[ 'id' ];
				if ( mysqli_query( $connect, "insert into data.tokens (user_id,token) values ('" . $user_id . "','" . $session_token . "')" ) ) {
					$_SESSION[ 'user_id' ] = $user_id;
					$token_id = mysqli_insert_id( $connect );
					echo $token_id;
				} else echo "Error creating user token";
			} else echo "Incorrect Password";
		} else echo "No account for that email address";
	}
	if ( $action === "updatefields" ) {
		if ( $_SESSION ) {
			$username = e( clean( $_POST[ 'username' ] ) );
			$email = e( $_POST[ 'email' ] );
			$cu = mysqli_query( $connect, "select * from data.users where username = '$username' and id != '$id'" );
			$ce = mysqli_query( $connect, "select * from data.users where email = '$email' and id != '$id'" );
			if ( mysqli_num_rows( $cu ) === 0 )$check_username = false;
			else $check_username = true;
			if ( mysqli_num_rows( $ce ) === 0 )$check_email = false;
			else $check_email = true;
			if ( !$check_username && !$check_email ) {
				if ( mysqli_query( $connect, "update data.users set username = '" . $username . "', email = '" . $email . "' where id = '$id'" ) )echo "saved";
				else echo "Error updating your account.";
			} else if ( $check_email && $check_username )echo "Username and email address are both taken.";
			else if ( $check_username )echo "Sorry, but someone beat you to that username.";
			else if ( $check_email )echo "$email is already being used for another account.";
			else echo "Unknown error.";
		} else echo "You appear to be logged out. Please refresh the page and try again.";
	}
	if ( $action === "sendpasswordlink" ) {
		$email = $_POST[ 'email' ];
		$account = dataArray( "users", $email, "email" );
		$user_id = $account[ 'id' ];
		$link = "<a href='https://account.scpl.dev/reset-password/$session_token' class='link-btn'>Reset Your Password</a>";
		if ( mysqli_query( $connect, "insert into data.tokens (user_id,token) values ('$user_id','$session_token')" ) ) {
			if ( sendEmail( $email, "donotreply@scpl.dev", "Reset Password Link", "Reset Your Password", "Here's the link to reset your password:<br/><br/>$link<br/><br/>Don't share this with anyone." ) )echo "sent";
			else echo "Error sending reset password link.";
		} else echo "Error creating reset password link.";
	}
	if ( $action === "resetpassword" ) {
		$thistoken = dataArray( "tokens", $_POST[ 'token' ], "token" );
		if ( $thistoken ) {
			$token = $_POST[ 'token' ];
			$token_id = $thistoken[ 'id' ];
			$user_id = $thistoken[ 'user_id' ];
			$raw_password = $_POST[ 'password' ];
			$options = [ 'cost' => 12, ];
			$password = password_hash( $raw_password, PASSWORD_BCRYPT, $options );
			if ( mysqli_query( $connect, "update data.users set password = '$password' where id = '$user_id'" ) ) {
				if ( mysqli_query( $connect, "delete from data.tokens where id = '$token_id'" ) )echo "reset";
				else echo "Error removing token." . mysqli_error( $connect );
			} else echo "Error updating your account." . mysqli_error( $connect );
		} else echo "Invalid reset token.";
	}
//}
