<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
include('../connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            height: 100vh;
            flex-direction: column;
        }
        .main {
            display: flex;
            flex: 1;
            overflow: hidden;
        }
        .sidebar {
            width: 140px;
            background-color: #343a40;
            color: white;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
        }
        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-5">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="main">
        <div class="sidebar ">
            <div class="text-center py-3">
                <img src="../../images/clearteenalogo.png" alt="Admin Logo" class="img-fluid" style="max-width: 80px;">
                <h4>Admin</h4>
            </div>
            <ul class="navbar-nav ms-3 fw-semibold">
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="showSection('user-management')">User Management</a>
                    <a class="nav-link" href="#" onclick="showSection('module-management')">Module Management</a>
                    <a class="nav-link" href="#" onclick="showSection('forum-management')">Forum Management</a>
                    <a class="nav-link" href="#" onclick="showSection('plantinder-management')">Plantinder Management</a>
                    <a class="nav-link" href="#" onclick="showSection('suggestions')">Suggestions</a>
                </li>
            </ul>
        </div>
        <div class="content">
            <section id="user-management" class="content-section">
                <h2>User Management</h2>
                <div class="mb-3">
                    <input type="text" id="search" class="form-control" placeholder="Search user profiles...">
                </div>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Date Created</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="userTable">
                        <?php
                        $result = $conn->query("SELECT * FROM users");
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td>
                                        <select class='form-select' onchange='updateUser(this, " . $row['user_id'] . ", \"role\")'>
                                            <option value='admin'" . ($row['role'] == 'admin' ? ' selected' : '') . ">Admin</option>
                                            <option value='student'" . ($row['role'] == 'student' ? ' selected' : '') . ">Student</option>
                                            <option value='agriculturist'" . ($row['role'] == 'agriculturist' ? ' selected' : '') . ">Agriculturist</option>
                                        </select>
                                    </td>";
                            echo "<td>" . htmlspecialchars($row['date_created']) . "</td>";
                            echo "<td>
                                <select class='form-select' onchange='updateUser(this, " . $row['user_id'] . ", \"status\")'>
                                    <option value='active'" . ($row['status'] == 'active' ? ' selected' : '') . ">Active</option>
                                    <option value='inactive'" . ($row['status'] == 'inactive' ? ' selected' : '') . ">Inactive</option>
                                </select>
                            </td>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>
            <section id="module-management" class="content-section">
                <h2>Module Management</h2>
                <div class="mb-3">
                    <input type="text" id="module-search" class="form-control" placeholder="Search modules...">
                </div>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Content</th>
                            <th>Image</th>
                            <th>Created_at</th>
                            <th>Updated_at</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody id="moduleTable">
                        <?php
                        $result2 = $conn->query("SELECT * FROM modules");
                        while ($row = $result2->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['content']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['image_path']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['updated_at']) . "</td>";
                            echo "<td>
                                    <a href='editmodule.php?id=" . $row['module_id'] . "' class='btn btn-sm btn-warning'>Edit</a>
                                  </td>";
                            echo "<td>
                                    <a href='deletemodule.php?id=" . $row['module_id'] . "' class='btn btn-sm btn-danger'>Archive</a>
                                  </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href="addmodule.php" class="btn btn-dark">Add New</a>
            </section>   
            <section id="forum-management" class="content-section">
                <h2>Forum Management</h2>
                <h4 class="ms-3">Manage Questions</h4>
                <div class="mb-3">
                    <input type="text" id="question-search" class="form-control" placeholder="Search questions...">
                </div>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Body</th>
                            <th>User</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="questionTable">
                        <?php
                        $questions = $conn->query("SELECT q.*, u.username FROM questions q JOIN users u ON q.user_id = u.user_id");
                        while ($row = $questions->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['question_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['body']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                            echo "<td>
                                    <a href='deletequestions.php?id=" . $row['question_id'] . "' class='btn btn-sm btn-danger'>Delete</a>
                                </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <h4 class="ms-3">Manage Replies</h4>
                <div class="mb-3">
                    <input type="text" id="reply-search" class="form-control" placeholder="Search replies...">
                </div>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Question ID</th>
                            <th>Body</th>
                            <th>User</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="replyTable">
                        <?php
                            $replies = $conn->query("SELECT r.*, u.username FROM reply r JOIN users u ON r.user_id = u.user_id");
                            while ($row = $replies->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['reply_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['question_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['body']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                echo "<td>
                                        <a href='deletereply.php?id=" . $row['reply_id'] . "' class='btn btn-sm btn-danger'>Delete</a>
                                    </td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </section>
            <section id="plantinder-management" class="content-section">
                <h2 class="mb-2">Plant Management</h2>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Id</th>
                            <th>Plant Name</th>
                            <th>Description</th>
                            <th>Image Path</th>
                            <th>Container & Soil</th>
                            <th>Watering</th>
                            <th>Sunlight</th>
                            <th>Tips</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody id="plantTable">
                        <?php
                        $result = $conn->query("SELECT * FROM plant");
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['plant_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['image']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['container_soil']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['watering']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['sunlight']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['tips']) . "</td>";
                            echo "<td>
                                    <a href='editplant.php?id=" . $row['plant_id'] . "' class='btn btn-sm btn-warning'>Edit</a>
                                  </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href="addplant.php" class="btn btn-dark">Add New</a>
            </section>
            <section id="suggestions" class="content-section">
                <h2>Suggestions</h2>
                <div class="mb-3">
                    <input type="text" id="suggestion-search" class="form-control" placeholder="Search suggestions...">
                </div>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="suggestionTable">
                        <?php
                        $suggestions = $conn->query("SELECT * FROM suggestions");
                        while ($row = $suggestions->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['suggestion_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                            echo "<td>
                                    <select class='form-select' onchange='updateStatus1(this, " . $row['suggestion_id'] . ")'>
                                        <option value='pending'" . ($row['status'] == 'pending' ? ' selected' : '') . ">Pending</option>
                                        <option value='approved'" . ($row['status'] == 'approved' ? ' selected' : '') . ">Approved</option>
                                        <option value='rejected'" . ($row['status'] == 'rejected' ? ' selected' : '') . ">Rejected</option>
                                    </select>
                                </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script>
        document.getElementById('search').addEventListener('keyup', function() {
            var searchValue = this.value.toLowerCase();
            var rows = document.getElementById('userTable').getElementsByTagName('tr');
            
            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName('td');
                var match = false;
                
                for (var j = 0; j < cells.length; j++) {
                    if (cells[j].innerText.toLowerCase().includes(searchValue)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
        document.getElementById('module-search').addEventListener('keyup', function() {
            var searchValue = this.value.toLowerCase();
            var rows = document.getElementById('moduleTable').getElementsByTagName('tr');
            
            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName('td');
                var match = false;
                
                for (var j = 0; j < cells.length; j++) {
                    if (cells[j].innerText.toLowerCase().includes(searchValue)) {
                        match = true;
                        break;
                    }
                }
                
                if (match) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
        function updateUser(select, userId, field) {
            var value = select.value;
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "updateuser.php?id=" + userId + "&field=" + field + "&value=" + value, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        alert(field.charAt(0).toUpperCase() + field.slice(1) + " updated successfully.");
                    } else {
                        alert("Error updating " + field + ": " + xhr.responseText);
                    }
                }
            };
            xhr.send();
        }
        document.getElementById('question-search').addEventListener('keyup', function() {
            var searchValue = this.value.toLowerCase();
            var rows = document.getElementById('questionTable').getElementsByTagName('tr');

            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName('td');
                var match = false;

                for (var j = 0; j < cells.length; j++) {
                    if (cells[j].innerText.toLowerCase().includes(searchValue)) {
                        match = true;
                        break;
                    }
                }

                if (match) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
        document.getElementById('reply-search').addEventListener('keyup', function() {
            var searchValue = this.value.toLowerCase();
            var rows = document.getElementById('replyTable').getElementsByTagName('tr');

            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName('td');
                var match = false;

                for (var j = 0; j < cells.length; j++) {
                    if (cells[j].innerText.toLowerCase().includes(searchValue)) {
                        match = true;
                        break;
                    }
                }

                if (match) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
        document.getElementById('suggestion-search').addEventListener('keyup', function() {
            var searchValue = this.value.toLowerCase();
            var rows = document.getElementById('suggestionTable').getElementsByTagName('tr');
            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName('td');
                var match = false;
                for (var j = 0; j < cells.length; j++) {
                    if (cells[j].innerText.toLowerCase().includes(searchValue)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
        function showSection(sectionId) {
                var sections = document.querySelectorAll('.content-section');
                sections.forEach(function(section) {
                    section.classList.remove('active');
                });
                var activeSection = document.getElementById(sectionId);
                activeSection.classList.add('active');
            }
            window.onload = function() {
                showSection('user-management'); 
            };
        function updateStatus1(select, suggestionId) {
            var newStatus = select.value; 
            var formData = new FormData();
            formData.append('suggestion_id', suggestionId);
            formData.append('status', newStatus);
            fetch('suggestionstatus.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    alert('Status updated successfully!');
                } else {
                    alert('Error updating status.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
