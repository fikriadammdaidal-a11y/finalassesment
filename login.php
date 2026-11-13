
<?php
session_start();
include "db.php";

$error = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = $conn->prepare(
        "SELECT * FROM users WHERE username = ?"
    );

    $sql->bind_param("s", $username);
    $sql->execute();

    $result = $sql->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password_hash'])) {

        $_SESSION['username'] = $user['username'];

        header("Location: index.php");
        exit();

    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
</head>
<body>

<h1>Student Grading System</h1>
<h2>Login</h2>

<?php
if (!empty($error)) {
    echo "<p style='color:red;'>$error</p>";
}
?>

<form method="POST">

    <label>Username:</label><br>
    <input type="text" name="username" required>
    <br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required>
    <br><br>

    <button type="submit" name="login">Login</button>

</form>

</body>
</html>
