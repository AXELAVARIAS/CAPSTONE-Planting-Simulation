<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Swipe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body{
            background:radial-gradient(circle,rgba(40, 167, 69, .9),rgba(40, 167, 69, .5));
        }
        .card {
            width: 500px;
            margin: auto;
        }
        .swipe-container {
            position: relative;
            width: 100%;
            height: 400px;
            margin-top: 20px;
        }
        .card-img-top {
            height: 300px;
            object-fit: cover;
        }
        .buttons {
            display: flex;
            justify-content: space-around;
            margin-top: 50px;
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
    <div class="container">
        <h1 class="text-center my-5 ">Swipe Plants</h1>
        <div id="swipe-container" class="swipe-container">
        </div>
        <div class="buttons">
            <button id="dislike-btn" class="btn btn-danger">
                <i class="fas fa-times"></i>
            </button>
            <button id="like-btn" class="btn btn-success">
                <i class="fas fa-heart"></i>
            </button>
        </div>
    </div>
    <script>
        const swipeContainer = document.getElementById('swipe-container');
        const likeBtn = document.getElementById('like-btn');
        const dislikeBtn = document.getElementById('dislike-btn');
        let plantCards = [];
        let currentIndex = 0;

        function fetchPlants() {
            fetch('fetchplants.php')
                .then(response => response.text())
                .then(data => {
                    swipeContainer.innerHTML = data;
                    plantCards = Array.from(document.querySelectorAll('.card'));
                    showNextPlant();
                })
                .catch(error => {
                    console.error('Error fetching plants:', error);
                    swipeContainer.innerHTML = '<p>Error loading plants.</p>';
                });
        }

        function showNextPlant() {
            plantCards.forEach((card, index) => {
                card.style.display = index === currentIndex ? 'block' : 'none';
            });
        }

        likeBtn.addEventListener('click', () => {
            if (plantCards.length > 0) {
                addFavorite(plantCards[currentIndex]);
                currentIndex = (currentIndex + 1) % plantCards.length; 
                showNextPlant();
            }
        });

        dislikeBtn.addEventListener('click', () => {
            if (plantCards.length > 0) {
                currentIndex = (currentIndex + 1) % plantCards.length; 
                showNextPlant();
            }
        });

        function addFavorite(card) {
            const plantId = card.querySelector('input[type="hidden"]').value;
            const plantName = card.querySelector('.card-title').textContent;
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "add_favorite.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log(xhr.responseText);
                }
            };
            xhr.send(`plant_id=${encodeURIComponent(plantId)}&plant_name=${encodeURIComponent(plantName)}`);
        }

        fetchPlants();
    </script>
</body>
</html>