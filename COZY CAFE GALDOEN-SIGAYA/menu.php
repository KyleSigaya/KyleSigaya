<?php
session_start();
require 'db.php';
require 'navbar.php';
require 'helpers.php';

// fetch items grouped by category
$stmt = $pdo->query("SELECT id,name,description,price,img,category FROM menu_items ORDER BY category, name");
$items = $stmt->fetchAll();

$byCat = [];
foreach ($items as $it) $byCat[$it['category']][] = $it;

function h($s){ return htmlspecialchars($s); }
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Menu — Cozy Drip</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <style>
    :root{--accent:#7b3a12;--card:#fff;--bg:#fff8ef;--text:#2b1f17}
    body{font-family:Poppins,Arial;background:var(--bg);margin:0;color:var(--text)}
    .container{max-width:1200px;margin:0 auto;padding:20px}
    h2.section{margin:28px 0 12px}
    .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px}
    .card{background:var(--card);border-radius:10px;padding:0;box-shadow:0 8px 24px rgba(0,0,0,0.06);overflow:hidden}
    .card img{width:100%;height:160px;object-fit:cover;display:block}
    .card-body{padding:14px}
    .card-body h3{margin:0 0 6px;font-size:18px}
    .price{color:var(--accent);font-weight:700;margin-top:8px}
    .btn{background:var(--accent);color:#fff;padding:8px 12px;border-radius:8px;border:none;cursor:pointer}
    .cat-title{margin-top:28px;margin-bottom:10px;color:#4b3a2f}
    footer{margin-top:40px}
  </style>
</head>
<body>

<div class="container">
    <h1>Our Menu</h1>

    <?php foreach ($byCat as $cat => $list): ?>
      <h2 class="cat-title"><?= h(ucfirst($cat)) ?></h2>
      <div class="grid">
        <?php foreach ($list as $it): ?>
          <div class="card" data-id="<?= (int)$it['id'] ?>">
            <img src="<?= h($it['img']) ?>" alt="<?= h($it['name']) ?>">
            <div class="card-body">
              <h3><?= h($it['name']) ?></h3>
              <p style="color:#666"><?= h($it['description']) ?></p>
              <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px">
                <div class="price">₱<?= number_format($it['price'],2) ?></div>
                <button class="btn addBtn" data-id="<?= (int)$it['id'] ?>">Add to Cart</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
</div>

<?php include 'footer.php'; ?>

<script>
document.querySelectorAll('.addBtn').forEach(btn => {
  btn.addEventListener('click', async function(){
    const id = this.dataset.id;

    const fd = new FormData();
    fd.append('id', id);
    fd.append('qty', 1);

    try{
      const res = await fetch('add_to_cart.php', { method:'POST', body:fd });
      const data = await res.json();

      if (data.success) {
        document.querySelector('.navbar-cart-count').textContent = data.count;
        this.textContent = 'Added';
        setTimeout(() => this.textContent = 'Add to Cart', 800);
      }
    } catch (e){
      alert('Network error');
    }
  });
});
</script>

</body>
</html>
