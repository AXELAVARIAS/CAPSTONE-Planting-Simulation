<?php
session_start();
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('connection.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, password, role, status FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $role, $status);
        $stmt->fetch();
        
        if ($status == 'inactive') {
            $error_message = "Your account is deactivated. Please contact the administrator.";
        } elseif (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            $_SESSION['logged_in'] = true;

            if ($role == 'admin') {
                header("Location: Admin/adminpage.php");
                exit();
            } elseif ($role == 'agriculturist') {
                header("Location: Admin/agriculturistpage.php");
                exit();
            }else {
                header("Location: ../index.php");
                exit();
            }
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "Username not found.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="../css/signup.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top nav-bg nav-background">
        <div class="container-fluid">
            <a class="navbar-brand mx-5" href="../index.php">
                <img src="../images/clearteenalogo.png" class="teenanimlogo" alt="home logo" class="d-inline-block align-text-top">
                <strong class="fs-5 ms-3">TEEN-ANIM</strong>
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto me-5">
                    <li class="nav-item mx-3">
                        <a href="../index.php" class="btn btn-success">Home Page</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid d-flex justify-content-center align-items-center border vh-100">
        <div class="border rounded p-5 white shadow blurred" style="width: 500px;">
            <h3>Sign In</h3>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="post">
                <label for="username" class="form-label">Enter Username:</label>
                <input class="form-control mb-2" type="text" name="username" placeholder="Username..." aria-label=".form-control-lg example" id="username" required>
                
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" id="password" class="form-control" aria-describedby="passwordtext" placeholder="Type your password..." required>
                
                <div class="row">
                    <div class="col d-flex align-items-end">
                        <a href="signup.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover">Don't have an account?</a>
                    </div>
                    <div class="col d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mt-3">Sign In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <footer>
        <div class="container-fluid footer-bg fixed-bottom">
            <div class="pt-1 mx-5 d-flex justify-content-around align-items-center">
                <p>Copyright 2024</p>
                <img src="../images/clearteenalogo.png" class="teenanimlogo mb-2" alt="TEENANIM LOGO">
                <p>Terms & Conditions / Privacy Policy</p>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>
