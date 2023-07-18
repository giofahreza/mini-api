<?php
    // Connect to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "indonesia";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Define default result
    // $buildFuncName = ucfirst(uri(1));
    // call_user_func($func);
    $code = 404;
    $data = ["message" => "Function not found."];

    // Handle GET requests to retrieve user information
    if ($method === "GET" && uri(1) === 'province') {
        // Retrieve user information from the database
        $sql = "SELECT * FROM province";
        $result = $conn->query($sql);

        // // Check if any users are found
        // if ($result->num_rows > 0) {
        //     // Fetch user data and store in an array
        //     $users = array();
        //     while ($row = $result->fetch_assoc()) {
        //         $users[] = $row;
        //     }

        //     // Return the user data as JSON response
        //     header("Content-Type: application/json");
        //     echo json_encode($users);
        // } else {
        //     // No users found
        //     echo "No users found.";
        // }
    }

    // Close the database connection
    $conn->close();
?>