<?php

    header('Access-Control-Allow-Origin: http://localhost:3000');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: X-Requested-With");

    // -- Database config -----------------
		$db_servername = "localhost";
		$db_username = "volta_admin";
		$db_password = "O&WK5wIa*HigxN@k%iZfSI";
		$db_dbName = "volta-admin";
	// ------------------------------------

    $data = json_decode($_POST['json']);
    $user_name = $data -> name;
    $user_password = $data -> password;

    $conn = new MySQLi($db_servername, $db_username, $db_password, $db_dbName);
        $getUser = "SELECT * FROM users WHERE user_name = ?";
        $getUser = $conn -> prepare($getUser);
        $getUser -> bind_param('s', $user_name);
        $getUser -> execute();
        $users = $getUser -> get_result();
        $row_count = $users -> num_rows;
        $user = $users -> fetch_assoc();
    $conn -> close();

    if ($row_count) {
        $authenticated = password_verify($user_password, $user['user_password']);
        $token = rand();
    } else {
        $authenticated = false;
    }

    if ($authenticated) {
        $returndata = '{"authentication": {"user_name": "' . $user_name . '","token": "' . $token . '", "authenticated": true}}';
    } else {
        $returndata = '{"authentication": {"authenticated": false}}';
    }

    echo json_encode($returndata);
?>
