<?php
include('config.php'); // Ensure this includes your database connection setup

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // This will default to 'teacher'

    // Validate data
    if (!empty($name) && !empty($username) && !empty($password)) {
        // Check if username already exists
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            // Username already exists
            $stmt->close();
            echo '<!DOCTYPE html>
            <html>
            <head>
                <title>Registration Error</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                        background-color: #f4f4f4;
                    }
                    .message {
                        background-color: white;
                        padding: 20px;
                        border-radius: 10px;
                        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                        text-align: center;
                    }
                    .message h2 {
                        margin: 0;
                    }
                    .message a {
                        color: #007BFF;
                        text-decoration: none;
                    }
                </style>
            </head>
            <body>
                <div class="message">
                    <h2>Username already exists!</h2>
                    <p>The username you entered is already in use. <a href="register.html">Go back</a> and choose a different username.</p>
                </div>
            </body>
            </html>';
        } else {
            // Username is available, proceed with registration
            $stmt->close();

            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert data into the database
            $sql = "INSERT INTO users (name, username, password, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("SQL Error: " . $conn->error);
            }
            $stmt->bind_param("ssss", $name, $username, $hashed_password, $role);
            $stmt->execute();
            $stmt->close();

            // Redirect with a message
            echo '<!DOCTYPE html>
            <html>
            <head>
                <title>Registration Successful</title>
                <meta http-equiv="refresh" content="3;url=login.html">
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                        background-color: #f4f4f4;
                    }
                    .message {
                        background-color: white;
                        padding: 20px;
                        border-radius: 10px;
                        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                        text-align: center;
                    }
                    .message h2 {
                        margin: 0;
                    }
                    .message a {
                        color: #007BFF;
                        text-decoration: none;
                    }
                </style>
            </head>
            <body>
                <div class="message">
                    <h2>Registration successful!</h2>
                    <p>You will be redirected to the <a href="login.html">login page</a> shortly.</p>
                </div>
            </body>
            </html>';
        }
    } else {
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Registration Error</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                    background-color: #f4f4f4;
                }
                .message {
                    background-color: white;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                    text-align: center;
                }
                .message h2 {
                    margin: 0;
                }
                .message a {
                    color: #007BFF;
                    text-decoration: none;
                }
            </style>
        </head>
        <body>
            <div class="message">
                <h2>Error!</h2>
                <p>All fields are required. <a href="register.html">Go back</a> and try again.</p>
            </div>
        </body>
        </html>';
    }
}
?>
