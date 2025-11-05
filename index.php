<?php

session_start();
?>
<!-- ===== Custom Navbar HTML (overrides header.php) ===== -->
<header>
  <nav>
    <div class="logo">Tour and Guide Management System</div>
    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      <li><a href="packages.php">Packages</a></li>

      <?php if(isset($_SESSION['user'])): ?>
      <li><a href="#">Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></a></li>
      <li><a href="logout.php">Logout</a></li>
      <?php else: ?>
      <li><a href="login.php">Login</a></li>
      <li><a href="register.php">Register</a></li>
      <?php endif; ?>

      
    </ul>

  </nav>
</header>
<!-- ===== HERO SECTION ===== -->
<section class="hero">
  <div class="overlay">
    <h1>EXPLORE BEAUTIFUL PLACES</h1>
    <p>Choose your favorite state and discover its top destinations</p>
  </div>
</section>

<!-- ===== SEARCH BAR ===== -->
<div class="search-bar">
  <input type="text" placeholder="Search by State Name">
  <button>Search</button>
</div>

<!-- ===== STATE-WISE PACKAGES ===== -->
<section class="popular container">
  <h2>Explore by State</h2>
  <div class="dest-grid">

    <!-- Tamil Nadu -->
    <div class="card">
      <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=800&q=80" alt="Tamil Nadu">
      <div class="card-content">
        <h3>Tamil Nadu</h3>
        <p>Rich temples, beaches, and hill stations.</p>
        <a href="packages.php?state=Tamil%20Nadu" class="visit-btn">Visit</a>
      </div>
    </div>

    <!-- Kerala -->
    <div class="card">
      <img src="https://images.unsplash.com/photo-1558449033-96a8d6e19197?auto=format&fit=crop&w=800&q=80" alt="Kerala">
      <div class="card-content">
        <h3>Kerala</h3>
        <p>Backwaters, tea gardens, and houseboats.</p>
        <a href="packages.php?state=Kerala" class="visit-btn">Visit</a>
      </div>
    </div>

    <!-- Goa -->
    <div class="card">
      <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80" alt="Goa">
      <div class="card-content">
        <h3>Goa</h3>
        <p>Beaches, nightlife, and adventure sports.</p>
        <a href="packages.php?state=Goa" class="visit-btn">Visit</a>
      </div>
    </div>

    <!-- Rajasthan -->
    <div class="card">
      <img src="https://images.unsplash.com/photo-1587314168485-3236a6af72f0?auto=format&fit=crop&w=800&q=80" alt="Rajasthan">
      <div class="card-content">
        <h3>Rajasthan</h3>
        <p>Palaces, deserts, and royal heritage.</p>
        <a href="packages.php?state=Rajasthan" class="visit-btn">Visit</a>
      </div>
    </div>

    <!-- Himachal Pradesh -->
    <div class="card">
      <img src="https://images.unsplash.com/photo-1600093463592-8e5f5e2cb8fb?auto=format&fit=crop&w=800&q=80" alt="Himachal Pradesh">
      <div class="card-content">
        <h3>Himachal Pradesh</h3>
        <p>Snow peaks, trekking, and mountain escapes.</p>
        <a href="packages.php?state=Himachal%20Pradesh" class="visit-btn">Visit</a>
      </div>
    </div>

    <!-- Andaman & Nicobar -->
    <div class="card">
      <img src="https://images.unsplash.com/photo-1589308078059-be1415eab4c3?auto=format&fit=crop&w=800&q=80" alt="Andaman & Nicobar">
      <div class="card-content">
        <h3>Andaman & Nicobar</h3>
        <p>Crystal blue waters and tropical islands.</p>
        <a href="packages.php?state=Andaman%20and%20Nicobar" class="visit-btn">Visit</a>
      </div>
    </div>

    <!-- Delhi -->
    <div class="card">
      <img src="https://images.unsplash.com/photo-1503437313881-503a91226402" alt="Delhi">
      <div class="card-content">
        <h3>Delhi Heritage Walk</h3>
        <p>Witness India’s capital filled with monuments, bazaars, and culture.</p>
        <a href="packages.php?state=Delhi" class="btn">Visit</a>
      </div>
    </div>

  </div>
</section>

<!-- ===== CTA SECTION ===== -->
<section class="cta">
  <h2>Ready to Plan Your Trip?</h2>
  <a href="register.php" class="btn primary-btn">Get Started</a>
  <a href="packages.php" class="btn" style="margin-left:10px; background:#fff; color:#0b4661;">View All Packages</a>
</section>

<!-- ===== FOOTER SECTION ===== -->
<footer class="site-footer">
  <div class="container">
    <p>&copy; <?= date('Y') ?> Tour and Guide | Contact: +91 98765 43210 | Email: tagms@gmail.com</p>
  </div>
</footer>

<!-- ===== CSS STYLING ===== -->
<style>
body {
  font-family: 'Poppins', sans-serif;
  margin: 0;
  background: #f7f9fb;
  color: #222;
}

/* ===== Navigation Bar ===== */
header {
  background: linear-gradient(90deg, #0b4661, #1a7ca5);
  padding: 15px 50px;
}

nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* Center logo text */
.logo {
  flex: 1;
  text-align: center;
  font-size: 28px;
  font-weight: bold;
  color: #fff;
  letter-spacing: 1px;
}

/* Right side menu */
.nav-links {
  display: flex;
  justify-content: flex-end;
  gap: 25px;
  list-style: none;
  margin: 0;
  padding: 0;
}

.nav-links li a {
  color: #ffffff;
  text-decoration: none;
  font-weight: 500;
  background: rgba(255, 255, 255, 0.15);
  padding: 8px 14px;
  border-radius: 8px;
  transition: 0.3s;
}

.nav-links li a:hover {
  background: #fff;
  color: #0b4661;
}

/* ===== Hero Section ===== */
.hero {
  background: url('banner2.jpeg') center/cover no-repeat;
  text-align: center;
  color: white;
  padding: 120px 20px;
  position: relative;
}
.hero .overlay {
  background: rgba(0,0,0,0.45);
  padding: 60px 60px;
  border-radius: 30px;
  display: inline-block;
}

/* ===== Search Bar ===== */
.search-bar {
  display: flex;
  justify-content: center;
  align-items: center;
  background: #fff;
  border-radius: 40px;
  padding: 10px;
  margin: 0px auto 20px;
  box-shadow: 0px 5px 15px rgba(0,0,0,0.1);
  width: 60%;
}
.search-bar input, .search-bar button {
  border: none;
  outline: none;
  padding: 12px;
  margin: 5px;
  border-radius: 10px;
  font-size: 20px;
}
.search-bar button {
  background: #1a7ca5;
  color: #fff;
  cursor: pointer;
}

/* ===== Grid Cards ===== */
.dest-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 25px;
  padding: 40px;
}
.card {
  background: #fff;
  border-radius: 15px;
  box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
  overflow: hidden;
  transition: transform 0.3s;
}
.card:hover {
  transform: translateY(-6px);
}
.card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}
.card-content {
  padding: 20px;
  text-align: center;
}
.card-content h3 {
  margin: 30px 0;
  color: #0b4661;
}
.card-content p {
  font-size: 14px;
  color: #555;
}
.visit-btn {
  background: linear-gradient(90deg, #0b4661, #1a7ca5);
  color: white;
  padding: 10px 16px;
  border: none;
  border-radius: 8px;
  text-decoration: none;
  font-weight: bold;
  display: inline-block;
}

/* ===== CTA Section ===== */
.cta {
  text-align: center;
  background: linear-gradient(90deg, #1a7ca5, #0b4661);
  color: white;
  padding: 60px 20px;
  border-radius: 0;
}
.cta .btn {
  background: #fff;
  color: #0b4661;
  padding: 12px 24px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: bold;
}
/* ===== Footer ===== */
.site-footer {
  background: #0b4661;
  color: #fff;
  text-align: center;
  padding: 15px;
  margin-top: 40px;
}
</style>
