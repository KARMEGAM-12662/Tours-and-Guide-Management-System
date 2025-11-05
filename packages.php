<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All India Tour Packages - Tour & Guide Management System</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    body {font-family: Arial, Helvetica, sans-serif; background: #f7f7f7; margin: 0; color: #222;}
    .container {width: 95%; max-width: 1200px; margin: 0 auto; padding: 1rem;}
    h2 {text-align: center; margin: 1.5rem 0; color: #0b4661;}
    .grid {display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;}
    .card {background: #fff; padding: 16px; border: 1px solid #e6e6e6; width: 30%; box-sizing: border-box; border-radius: 8px; transition: 0.3s;}
    .card:hover {box-shadow: 0 4px 12px rgba(0,0,0,0.15);}
    .card img {width: 100%; height: 200px; object-fit: cover; border-radius: 6px;}
    .card h4 {margin: 12px 0 6px; color: #0b4661;}
    .card p {margin: 6px 0;}
    .btn {display: inline-block; background: #0b4661; color: #fff; padding: 8px 12px; border-radius: 6px; text-decoration: none;}
    .btn:hover {background: #093547;}
    @media(max-width: 900px){.card{width:45%;}}
    @media(max-width: 600px){.card{width:100%;}}
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
    /* ===== Footer ===== */
    .site-footer {
      background: #0b4661;
      color: #fff;
      text-align: center;
      padding: 15px;
      margin-top: 40px;
    }
  </style>
</head>
<body>
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
<div class="container">
  <h2>Explore India — State-wise Tour Packages</h2>
  <div class="grid">
  <?php
  // State name + Unsplash image links
  $states = [
    ["Andhra Pradesh","image/premium_photo-1661963629241-52c812d5c7f8.avif"],
    ["Arunachal Pradesh","image/photo-1730021618284-01c538117ccc.avif"],
    ["Assam","image/photo-1637043765564-a071ff91a09f.avif"],
    ["Bihar","image/photo-1622194993926-1801586d460f.avif"],
    ["Chhattisgarh","image/chhattisgarh-tourist-places-FEATURE-compressed.jpg"],
    ["Goa","image/photo-1532517891316-72a08e5c03a7.avif"],
    ["Gujarat","image/photo-1642841819300-20ed449c02a1.avif"],
    ["Haryana","image/Kurukshetra-1.jpg"],
    ["Himachal Pradesh","image/premium_photo-1661943546908-7f84a497f5e3.avif"],
    ["Jharkhand","image/jharkhund.avif"],
    ["Karnataka","image/download (8).jpeg"],
    ["Kerala","image/photo-1593872356907-d67e8587d0f9.avif"],
    ["Madhya Pradesh","image/Madhya Pradesh.jpg"],
    ["Maharashtra","image/Maharashtra.jpg"],
    ["Manipur","image/Manipur.jpg"],
    ["Meghalaya","image/Meghalaya.jpg"],
    ["Mizoram","image/Mizoram.jpg"],
    ["Nagaland","image/Nagaland.jpg"],
    ["Odisha","image/Odisha.jpg"],
    ["Punjab","image/Punjab.jpg"],
    ["Rajasthan","image/photo-1602643163983-ed0babc39797.avif"],
    ["Sikkim","image/Sikkim.jpg"],
    ["Tamil Nadu","image/download (7).jpeg"],
    ["Telangana","image/Telangana.jpg"],
    ["Tripura","image/Tripura.jpg"],
    ["Uttar Pradesh","image/Uttar Pradesh.jpg"],
    ["Uttarakhand","image/Uttarakhand.jpg"],
    ["West Bengal","image/West Bengal.jpg"],
    // Union Territories
    ["Andaman and Nicobar Islands","image/download.jpeg"],
    ["Chandigarh","image/download (1).jpeg"],
    ["Dadra and Nagar Haveli and Daman and Diu","image/download (2).jpeg"],
    ["Delhi","image/download (3).jpeg"],
    ["Jammu and Kashmir","image/photo-1598091383021-15ddea10925d.avif"],
    ["Ladakh","image/download (4).jpeg"],
    ["Lakshadweep","image/download (5).jpeg"],
    ["Puducherry","image/download (6).jpeg"]
  ];

  foreach ($states as $state) {
      echo "
      <article class='card'>
        <img src='{$state[1]}' alt='{$state[0]}'>
        <h4>{$state[0]}</h4>
        <p>Discover the best tourist attractions, travel deals, and guides in {$state[0]}.</p>
        <a href='state_packages.php?state=" . urlencode($state[0]) . "' class='btn'>View Packages</a>
      </article>";
  }
  ?>
  </div>
</div>
<!-- ===== FOOTER SECTION ===== -->
<footer class="site-footer">
  <div class="container">
    <p>&copy; <?= date('Y') ?> Tour and Guide | Contact: +91 98765 43210 | Email: tagms@gmail.com</p>
  </div>
</footer>
</body>
</html>
