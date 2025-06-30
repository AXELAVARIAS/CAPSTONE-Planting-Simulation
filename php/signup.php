<?php
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('connection.php');

    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];

    if ($password != $repassword) {
        $error_message = "Passwords do not match!";
    } elseif (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        $error_message = "Username should contain only letters and numbers.";
    } elseif (strlen($password) < 8 || strlen($password) > 20) {
        $error_message = "Password must be between 8 and 20 characters long.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, username, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param("sss", $fullname, $username, $hashed_password);

        if ($stmt->execute()) {
            echo "Sign up successful!";
            header("Location: userpage.php"); 
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="../css/signup.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top nav-bg nav-background">
        <div class="container-fluid">
            <a class="navbar-brand mx-5" href="../index.php">
                <img src="../images/clearteenalogo.png" class="teenanimlogo" alt="home logo" class="d-inline-block align-text-top">
                <strong class="fs-5 ms-3 font-style">TEEN-ANIM</strong>
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto me-5">
                    <li class="nav-item mx-3">
                        <a href="../index.php" class="btn btn-success font-style">Back</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid d-flex justify-content-center align-items-center border vh-100">
        <div class="border rounded px-5 py-3 white shadow blurred" style="width: 500px;">
            <h3>Sign Up</h3>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>
            <form action="signup.php" method="post">
                <label for="fullname" class="form-label">Full Name:</label>
                <input class="form-control mb-3" type="text" name="fullname" placeholder="Full name..." aria-label=".form-control-lg example" id="fullname" required>
                
                <label for="username" class="form-label">Enter Username:</label>
                <input class="form-control mb-3" type="text" name="username" placeholder="Username..." aria-label=".form-control-lg example" id="username" required>
                
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" id="password" class="form-control" aria-describedby="passwordtext" placeholder="Type your password..." required>
                
                <div id="passwordtext" class="form-text mb-3">
                    Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.
                </div>
                
                <label for="repassword" class="form-label">Re-enter Password:</label>
                <input type="password" name="repassword" id="repassword" class="form-control mb-3" aria-describedby="passwordtext" placeholder="Re-enter your password..." required>
                
                <div class="row">
                    <div class="col d-flex align-items-end">
                        <a href="login.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover">Already have an account?</a>
                    </div>
                    <div class="col d-flex justify-content-end">
                        <button type="submit" class="btn btn-success mt-2">Sign Up</button>
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
