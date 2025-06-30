<?php
    include 'connection.php';

    $Type = isset($_POST['Type']) ? $conn->real_escape_string($_POST['Type']) : '';
    $Category = isset($_POST['Category']) ? $conn->real_escape_string($_POST['Category']) : '';
    $sortOption = isset($_POST['sortOption']) ? $conn->real_escape_string($_POST['sortOption']) : '';

    $sql = "SELECT * FROM modules WHERE 1=1";

    if ($Type != '') {
        $sql .= " AND type = '$Type'";
    }
    if ($Category != '') {
        $sql .= " AND category = '$Category'";
    }
    if ($sortOption != '') {
        if ($sortOption == 'title') {
            $sql .= " ORDER BY title ASC";
        } elseif ($sortOption == 'date') {
            $sql .= " ORDER BY created_at DESC";
        }
    }

    $result = $conn->query($sql);

    if ($result === false) {
        die("Error: " . $conn->error);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
            font-family: 'Poppins', sans-serif;
            font-family: 'Irish Grover';
        }
        .container-fluid {
            flex: 1;
        }
        .footer-bg {
            background-color: rgba(247, 195, 95, 1);
        }
        .teenanimlogo {
            height: 50px;
            width: 50px;
            object-fit: cover;
            margin-left: 10px;
            margin-bottom: 0px;
        }
        .bg-sidenav{
            background-color: rgba(40, 167, 69, .9);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top px-5 bg-success text-white">
        <div class="container-fluid">
            <a class="navbar-brand mx-5 text-white" href="../index.php">
                <img src="../images/clearteenalogo.png" class="teenanimlogo" alt="home logo" style="width: 50px; height: 50px;">
                <strong class="fs-5 ms-3">TEEN-ANIM</strong>
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-white" href="Forum/community.php">Farming Community</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-white mx-5" href="simulator.php">Simulation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-white me-5" href="plantinder.php">Plantinder</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-white me-5" href="modulepage.php">Module</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-white me-5" href="userpage.php">Profile</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 py-5 px-4 bg-sidenav">
                <h5>Filters</h5>
                <form method="POST" action="modulepage.php" id="filterForm">
                    <div class="mb-3">
                        <label for="Type" class="form-label">Module Type</label>
                        <select id="Type" class="form-select" name="Type" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All</option>
                            <option value="Concept/Overview" <?= $Type == 'Concept/Overview' ? 'selected' : '' ?>>Concept/Overview</option>
                            <option value="Practical/Hands-on" <?= $Type == 'Practical/Hands-on' ? 'selected' : '' ?>>Practical/Hands-on</option>
                            <option value="Strategy/Planning" <?= $Type == 'Strategy/Planning' ? 'selected' : '' ?>>Strategy/Planning</option>
                            <option value="Management/Prevention" <?= $Type == 'Management/Prevention' ? 'selected' : '' ?>>Management/Prevention</option>
                            <option value="Community/Social" <?= $Type == 'Community/Social' ? 'selected' : '' ?>>Community/Social</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="Category" class="form-label">Category</label>
                        <select id="Category" class="form-select" name="Category" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All</option>
                            <option value="Urban Agriculture Fundamentals" <?= $Category == 'Urban Agriculture Fundamentals' ? 'selected' : '' ?>>Urban Agriculture Fundamentals</option>
                            <option value="Design and Planning" <?= $Category == 'Design and Planning' ? 'selected' : '' ?>>Design and Planning</option>
                            <option value="Techniques and Practices" <?= $Category == 'Techniques and Practices' ? 'selected' : '' ?>>Techniques and Practices</option>
                            <option value="Management/Prevention" <?= $Category == 'Management/Prevention' ? 'selected' : '' ?>>Management/Prevention</option>
                            <option value="Strategy/Planning" <?= $Category == 'Strategy/Planning' ? 'selected' : '' ?>>Strategy/Planning</option>
                            <option value="Community/Social" <?= $Category == 'Community/Social' ? 'selected' : '' ?>>Community/Social</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sortOptions" class="form-label">Sort by</label>
                        <select id="sortOptions" class="form-select w-auto" name="sortOption" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Sort by</option>
                            <option value="title" <?= $sortOption == 'title' ? 'selected' : '' ?>>Title</option>
                        </select>
                    </div>
                </form>
            </div>
    <div class="container-fluid  justify-content-center d-flex">
        <?php
            if ($result->num_rows > 0) {
                $counter = 0;
                echo '<div class="col-md-10 p-5">';
                while ($row = $result->fetch_assoc()) {
                    if ($counter % 2 == 0) {
                        echo '<div class="row gap-4 mb-4">';
                    }
                    echo <<<HTML
                    <div class="card col d-flex flex-column shadow pt-3 bg-sidenav">
                        <img src="{$row['image_path']}" class="card-img-top shadow shadow" alt="{$row['title']}">
                        <div class="card-body d-flex flex-column flex-grow-1">
                            <h5 class="card-title fw-bold fst-italic">{$row['title']}</h5>
                            <p class="card-text">{$row['description']}</p>
                            <div class="mt-auto">
                                <a href="{$row['content']}" class="btn btn-warning mt-auto">View Module</a>
                            </div>
                        </div>
                    </div>
                    HTML;
                    $counter++;
                    if ($counter % 2 == 0) {
                        echo '</div>';
                    }
                }
                $remaining = 2 - ($counter % 2);
                if ($remaining < 2) {
                    for ($i = 0; $i < $remaining; $i++) {
                        echo '<div class="col-md-4 mb-4"></div>';
                    }
                }
            } else {
                echo "<h1 class=' mt-5'>No modules found.</h1>";
            }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
