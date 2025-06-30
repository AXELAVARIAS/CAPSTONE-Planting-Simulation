<?php
session_start();
include "../connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); 
    exit;
}

if (isset($_GET['id'])) {
    $question_id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT q.title, q.body, q.created_at, u.username FROM Questions q JOIN Users u ON q.user_id = u.user_id WHERE q.question_id = ?");
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $stmt->bind_result($title, $body, $created_at, $username);
    $stmt->fetch();
    $stmt->close();

    $reply_stmt = $conn->prepare("SELECT r.reply_id, r.body, r.created_at, u.username FROM reply r JOIN Users u ON r.user_id = u.user_id WHERE r.question_id = ? ORDER BY r.created_at ASC");
    $reply_stmt->bind_param("i", $question_id);
    $reply_stmt->execute();
    $replies = $reply_stmt->get_result();
} else {
    echo "No discussion selected.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $reply_body = $_POST['reply'];
    $user_id = $_SESSION['user_id'];

    $insert_reply = $conn->prepare("INSERT INTO reply (question_id, user_id, body) VALUES (?, ?, ?)");
    $insert_reply->bind_param("iis", $question_id, $user_id, $reply_body);

    if ($insert_reply->execute()) {
        header("Location: thread.php?id=" . $question_id);
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
    $insert_reply->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion - <?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container-fluid">
        <a class="navbar-brand ms-5" href="community.php">Farming Community Forum</a>
        <a class="navbar-brand me-5" href="community.php">Back</a>
        </div>
    </nav>
    <main class="container my-4">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5><?= htmlspecialchars($title) ?></h5>
            </div>
            <div class="card-body">
                <p><?= nl2br(htmlspecialchars($body)) ?></p>
                <small class="text-muted">Posted by <?= htmlspecialchars($username) ?> on <?= $created_at ?></small>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h6>Replies</h6>
            </div>
            <ul class="list-group list-group-flush">
            <?php while ($reply = $replies->fetch_assoc()): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <p><?= htmlspecialchars($reply['body']) ?></p>
                        <small class="text-muted">Replied by <?= htmlspecialchars($reply['username']) ?> on <?= date('M d, Y', strtotime($reply['created_at'])) ?></small>
                    </div>
                    <?php if ($_SESSION['role'] === 'agriculturist'): ?>
                        <a href="delete.php?type=reply&id=<?= $reply['reply_id'] ?>" 
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Are you sure you want to delete this reply?');">
                            Delete
                        </a>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
            </ul>
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6>Post a Reply</h6>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <textarea name="reply" class="form-control" rows="4" placeholder="Write your reply..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Submit Reply</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <p class="text-danger">You must <a href="../login.php">log in</a> to post a reply.</p>
        <?php endif; ?>
    </main>

    <footer class="bg-dark text-center text-white py-3 mt-auto">
        <p class="mb-0">&copy; 2024 Farming Community. All rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
