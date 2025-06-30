<?php

  session_start();

  if (!isset($_SESSION['user_id'])) {
      header("Location: ../login.php");
      exit();
  }
  
  include "../connection.php"; 

  $sql = "SELECT q.question_id, q.title, q.body, q.created_at, u.username 
          FROM questions q
          JOIN users u ON q.user_id = u.user_id
          ORDER BY q.created_at DESC
          LIMIT 10";
  $result = $conn->query($sql);
  
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Farming Forum</title>
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
      <a class="navbar-brand ms-5" href="#">Farming Community Forum</a>
      <?php
        if ($_SESSION['role'] == 'student') {
            echo '<a class="navbar-brand me-5" href="../userpage.php">Profile</a>';
        } elseif ($_SESSION['role'] == 'agriculturist') {
            echo '<a class="navbar-brand me-5" href="../Admin/agriculturistpage.php">Profile</a>';
        } else {
            echo '<a class="navbar-brand me-5" href="../login.php">Profile</a>';
        }
      ?>
    </div>
  </nav>

  <main class="container my-4">
    <div class="card mb-4">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0">Start a New Discussion</h5>
      </div>
      <div class="card-body">
        <form method="POST" action="addquestion.php">
          <div class="mb-3">
              <label for="threadTitle" class="form-label">Thread Title</label>
              <input type="text" id="threadTitle" name="title" class="form-control" placeholder="Enter title" required>
          </div>
          <div class="mb-3">
              <label for="threadMessage" class="form-label">Message</label>
              <textarea id="threadMessage" name="body" class="form-control" rows="5" placeholder="Write your message here..." required></textarea>
          </div>
          <button type="submit" class="btn btn-success">Create Thread</button>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header bg-success text-white">
          <h5 class="mb-0">Recent Discussions</h5>
      </div>
      <ul class="list-group list-group-flush">
          <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                      <div>
                          <h6 class="mb-1">
                              <a href="thread.php?id=<?= $row['question_id'] ?>" class="text-decoration-none">
                                  <?= htmlspecialchars($row['title']) ?>
                              </a>
                          </h6>
                          <small class="text-muted">
                              Posted by <?= htmlspecialchars($row['username']) ?> on <?= date('M d, Y', strtotime($row['created_at'])) ?>
                          </small>
                      </div>
                      <?php if ($_SESSION['role'] === 'agriculturist'): ?>
                          <a href="delete.php?type=question&id=<?= $row['question_id'] ?>" 
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to delete this question?');">
                              Delete
                          </a>
                      <?php endif; ?>
                  </li>
              <?php endwhile; ?>
          <?php else: ?>
              <li class="list-group-item">
                  <p class="mb-0 text-muted">No questions yet. Start the first discussion!</p>
              </li>
          <?php endif; ?>
      </ul>
  </div>
  </main>
  <footer class="bg-success text-center text-white py-3 mt-auto">
    <p class="mb-0">&copy; 2024 Farming Community. All rights reserved.</p>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
