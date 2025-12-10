<?php
session_start();
require 'db.php';
require 'helpers.php';
update_cart_count($pdo);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Cozy Drip</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    body{font-family:Poppins,system-ui,Arial;margin:0;color:#1f1f1f;background:#fff8ef}
    .container{max-width:1200px;margin:0 auto;padding:0 20px}
    .hero{background-image:url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1600');background-size:cover;background-position:center;color:white;padding:80px 0}
    .hero .inner{max-width:900px}
    .hero h1{margin:0 0 12px;font-size:36px}
    .hero p{margin:0 0 18px;font-size:18px;opacity:0.95}
    .cta{background:#7b3a12;color:white;padding:10px 18px;border-radius:8px;text-decoration:none}

    .about {display:flex;gap:40px;align-items:center;padding:60px 0;background:linear-gradient(#fff7ea,#fff7ea);border-top:1px solid rgba(0,0,0,0.02)}
    .about img{width:45%;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,0.08)}
    .about .text{width:55%}
    .icons{display:flex;gap:30px; margin-top:24px}
    .icons .one{background:#7b3a12;color:white;border-radius:999px;padding:14px;width:56px;height:56px;display:flex;align-items:center;justify-content:center}

    /* contact form */
    .contact-wrap{padding:60px 0;background:#fffbe8}
    .form-card{max-width:640px;margin:20px auto;background:white;padding:28px;border-radius:10px;box-shadow:0 8px 30px rgba(0,0,0,0.06)}
    input,textarea{width:100%;padding:12px;border:1px solid #eee;border-radius:8px;margin-top:8px}
    .btn{background:#7b3a12;color:white;padding:12px 18px;border:none;border-radius:8px;cursor:pointer;margin-top:12px}

    footer{margin-top:40px}
  </style>
</head>
<body>
  <?php include 'navbar.php'; ?>

  <section class="hero">
    <div class="container inner">
      <h1>Welcome to Cozy Drip</h1>
      <p>Experience the perfect blend of comfort and quality. Every cup is crafted with passion and served with warmth.</p>
      <a class="cta" href="menu.php">View Menu</a>
      <?php if(!isset($_SESSION['user_id'])): ?>
        <a style="margin-left:12px;color:white;text-decoration:underline;" href="login.php">Login to order</a>
      <?php endif; ?>
    </div>
  </section>

  <section id="about" class="about container">
    <img src="https://images.unsplash.com/photo-1653762377140-fdc924220c45?w=1000" alt="">
    <div class="text">
      <h2>Our Story</h2>
      <p>It all started in 2024 when a group of passionate classmates gathered over coffee, discussing their dreams and aspirations... Today every cup we serve carries the warmth of friendship and dedication.</p>

      <div class="icons">
        <div class="one">☕</div>
        <div>
          <h4>Sustainable</h4>
          <p>Ethically sourced beans</p>
        </div>
        <div class="one">🏆</div>
        <div>
          <h4>Premium</h4>
          <p>Award-winning quality</p>
        </div>
        <div class="one">❤️</div>
        <div>
          <h4>Crafted</h4>
          <p>Made with love</p>
        </div>
      </div>
    </div>
  </section>

  <section id="contact" class="contact-wrap">
    <div class="container">
      <h3 style="text-align:center">Get in Touch</h3>

      <?php if (isset($_GET['success'])): ?>
  <p style="color:green; text-align:center; margin-bottom:15px;">
    Your message has been sent successfully!
  </p>
<?php endif; ?>

      <div class="form-card">
        <form id="contactForm" action="contact_process.php" method="post">
          <label>Name</label>
          <input name="name" required>
          <label>Email</label>
          <input type="email" name="email" required>
          <label>Message</label>
          <textarea name="message" rows="6" required></textarea>
          <button class="btn" type="submit">Send Message</button>
        </form>
      </div>
    </div>
  </section>

  <?php include 'footer.php'; ?>

  <script>
    // simple contact handler fallback (progressive)
    document.getElementById('contactForm')?.addEventListener('submit', function(e){
      // allow server to handle; could add AJAX if desired
    });
  </script>
</body>
</html>
