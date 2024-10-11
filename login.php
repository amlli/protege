<?php
session_start(); // Start the session

$host = "127.0.0.1";
$user = "user";
$passwd = "password";
$database = "fr";

$conn = new mysqli($host, $user, $passwd, $database);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$error = '';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $error = 'ok!!';
            $_SESSION['userid'] = $row['userid'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['name'] = $row['name'];
            header('Location: homepage.php');
            exit(); 
        } else {
            $error = 'Invalid password!';
        }
    } else {
        $error = 'Invalid email!';
    }

    $stmt->close(); // Close the statement
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        .container h2 {
            margin-bottom: 25px;
            font-size: 24px;
        }
        .container form {
            display: flex;
            flex-direction: column;
        }
        .container form input {
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            font-size: 16px;
        }
        .container form button {
            padding: 15px;
            background: #007bff;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .container form button:hover {
            background: #0056b3;
        }
        .switch {
            margin-top: 15px;
        }
        .switch a {
            color: #A9BD93;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <input type="text" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit" name="login">Login</button>
    </form>
    <?php if ($error): ?>
        <div style="color: red;"><?php echo $error; ?></div>
    <?php endif; ?>
    <div class="switch">
    </div>
</div>

</body>
</html>
