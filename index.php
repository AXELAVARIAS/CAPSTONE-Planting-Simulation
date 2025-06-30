<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teen-Anim</title>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/homepage.css">
    </head>
</head>
<body>
    <section class="container-fluid hero-page "> 
      <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
          <nav class="navbar navbar-expand-lg sticky-top px-5 bg-success text-white">
              <div class="container-fluid">
                  <a class="navbar-brand mx-5 text-white" href="index.php">
                      <img src="images/clearteenalogo.png" class="teenanimlogo" alt="home logo">
                      <strong class="fs-5 ms-3">TEEN-ANIM</strong>
                  </a>
                  <div class="collapse navbar-collapse" id="navbarNav">
                      <ul class="navbar-nav ms-auto">
                          <li class="nav-item">
                              <a class="nav-link fw-semibold text-white" href="php/Forum/community.php">Farming Community</a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link fw-semibold text-white mx-5" href="php/simulator.php">Simulation</a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link fw-semibold text-white me-5" href="php/plantinder.php">Plantinder</a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link fw-semibold text-white me-5" href="php/modulepage.php">Module</a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link fw-semibold text-white me-5" href="php/userpage.php">Profile</a>
                          </li>
                      </ul>
                  </div>
              </div>
          </nav>
        <?php else: ?>
          <nav class="navbar navbar-expand-lg sticky-top px-5 bg-success text-white">
              <div class="container-fluid">
                  <a class="navbar-brand mx-5" href="index.php">
                      <img src="images/clearteenalogo.png" class="teenanimlogo" alt="home logo">
                      <strong class="fs-5 ms-3">TEEN-ANIM</strong>
                  </a>
                  <div class="collapse navbar-collapse" id="navbarNav">
                      <ul class="navbar-nav ms-auto me-5">
                          <li class="nav-item mx-3">
                              <a href="php/login.php" class="btn btn-warning">Sign In</a>
                          </li>
                      </ul>
                  </div>
              </div>
          </nav>
        <?php endif; ?>
        <div class="hero-page-text text-center text-white d-flex flex-column justify-content-center align-items-center"> 
          <div>
              <h1 class="display-1 mt-5 mb-5">Welcome to Teen-Anim</h1> 
              <p class="lead my-4 fs-3">Empowering the next generation of farmers</p> 
              <p class="mb-5 fs-3">Join us in exploring the exciting world of agriculture. Learn, grow, and connect with fellow young farmers. Together, we can cultivate a sustainable future.</p> 
              <?php if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true): ?>
                  <a href="php/signup.php" class="btn btn-success btn-lg">Get Started</a> 
              <?php endif; ?>
          </div>
      </div>
    </section>
    <section>
      <div class="container-fluid text-center">
        <div class="row">
          <div class="col px-5 grey-background">
            <div class="my-4">
              <h2>Seamless Start</h2>
              <p>Farming Made Easy. Get started with our simple online resources, tools, and community support to kickstart your journey in agriculture.</p>
            </div>
          </div>
          <div class="col px-5">
            <div class="my-4">
              <h2>Our Promise</h2>
              <p>Empowering Your Success. Whether you're planting your first seed or scaling your garden, we're here to support every step of the way.</p>
            </div>
          </div>
          <div class="col px-5 grey-background">
            <div class="my-4">
              <h2>Guided Growth</h2>
              <p>Learn from the Best. Access expert tips, articles, and videos on sustainable practices, modern farming techniques, and how to grow your own food.</p>
            </div>    
          </div>
        </div>
      </div>
    </section>
    <section id="module-page">
      <div class="container-fluid">
        <div class="row">
          <div class="col ms-5">
            <img src="images/ModulePage.png" alt="module" class="image-fluid " width="854px" height="600px">
          </div>
          <div class="col me-5 d-flex justify-content-center">
            <div>
              <h2 class="my-5">Start Your Journey with our<br>Farming Modules</h2>
              <p class="mb-5 fs-5">Explore our modules and grow your farming skills from the ground up.</p>
              <p class="mb-5 fs-5">Our farming modules simplify your journey into agriculture, <br>covering essentials from soil prep to sustainable practices. <br>Perfect for beginners or those refining their skills, each guide <br>provides practical, step-by-step knowledge you can apply immediately. <br>Learn at your pace and grow your success—one module at a time!</p>
              <div class="">
                <a class="btn btn-success mt-5" href="php/modulepage.php" role="button">Learn Now</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section id="community-page">
      <div class="container-fluid">
        <div class="row">
          <div class="col ms-5 d-flex justify-content-center">
            <div>
              <h2 class="my-5">Join the Community!</h1>
              <p class="mb-5 fs-4">Log in to connect with fellow growers, access <br>exclusive resources, and unlock all the tools <br>you need to thrive in farming. Together, we’re <br>building a supportive space for learning, sharing, <br>and growing!</p>
              <div>
                <a class="btn btn-success mt-5" href="php/Forum/community.php" role="button">Join Now</a>
              </div>
            </div>
          </div>
          <div class="col me-5">
            <img src="images/AboutPage.png" alt="about" class="image-fluid" width="854px" height="600px">
          </div>
        </div>
      </div>
    </section>
    <section id="about-page">
      <div class="container-fluid text-center px-5 grey-background">
        <h2 class="my-5">About Teen-Anim</h2>
        <p class="mb-5 fs-5">Empowering young minds to lead the future of sustainable farming.</p>
        <p class="mb-4 fs-5">At Teen-Anim, we believe agriculture should be exciting, innovative, and accessible for today’s youth. Our mission is to spark <br>interest in farming by offering insights, resources, and a supportive community to explore modern agriculture.</p>
        <p class="mb-4 fs-5">Young people need guidance on where to start, how to grow, and what role technology can play in farming. They want to understand <br>sustainable practices, build practical skills, and discover the opportunities agriculture offers for a brighter, greener future.</p>
        <p class="mb-5 fs-5">Teen-Anim provides youth with the knowledge and tools to make informed decisions, experiment with hands-on techniques, and see the <br>impact they can make through farming. We’re here to cultivate the next generation of leaders in agriculture, one step at a time.</p>
      </div>
    </section>
    <section id="contact-page" class="contact-bg">
      <div class="container-fluid">
        <div class="row">
          <div class="col d-flex justify-content-center">
            <div>
              <p class="text-center mt-3">Contact Us Now</p>
              <h2 class="text-center mb-4">Leave Us A Message</h2>
              <p class="text-center fs-4 mb-5">At FarmFuture, we’re always looking to improve <br>and grow, just like you. Have ideas on how we can <br>make farming more exciting for young people? <br>Want to see new features, resources, or topics <br>covered? We’d love to hear your thoughts!</p>
              <p class="fs-5">Email: teenanim2024@gmail.com</p>
              <p class="fs-5">Phone Number: 09956957814</p>
            </div>
          </div>
          <div class="col">
            <h2 class="mt-5 mb-3">Share Your Ideas</h2>
            <form action="php/suggestion.php" method="post">
              <label for="suggestion" class="form-label">Enter your suggestions:</label>
              <textarea class="form-control" id="suggestion" rows="10" style="width: 700px;" name="message"></textarea>
              <button type="submit" class="btn btn-success mb-5 mt-3">Send Suggestion</button>
            </form>
          </div>
        </div>
      </div>
    </section>
    <footer>
      <div class="contianer-fluid footer-bg">
        <div class="pt-3 mx-5 d-flex justify-content-around">
          <p>Copyright 2024</p>
          <img src="images/clearteenalogo.png" class="teenanimlogo mb-2" alt="TEENANIM LOGO">
          <p>Terms & Conditions / Privacy Policy</p>
        </div>
      </div>
    </footer>
</body>
</html>