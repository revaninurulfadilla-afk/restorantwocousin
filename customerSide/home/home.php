<?php include_once('../components/header.php')?>
<!-- Hero Section with Video Background and Text Overlay -->
<section id="hero" style="position: relative;">
    <video autoplay loop muted playsinline poster="your-poster-image.jpg" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
        <source src="../image/SteakOnGrillCloseup.mp4" type="video/mp4">
        <!-- Add additional source elements for 
        1.  SteakOnGrillCloseup

        other video formats if needed -->
    </video>
    <div class="hero container" style="position: relative; z-index: 1;">
        <div>
            <h1><strong><h1 class="text-center" style="font-family:Copperplate; color:whitesmoke;"> TWO COUSIN</h1><span></span></strong></h1>
            <h1><strong style="color:white;">MEAT GRILL & BAR<span></span></strong></h1>
            <a href="#projects" type="button" class="cta">MENU</a>
        </div>
    </div>
</section>
<!-- End Hero Section -->
  
  
  
  <!-- menu Section -->
  <section id="projects">
  <div class="projects container">
    <div class="projects-header">
      <h1 class="section-title">Me<span>n</span>u</h1>
    </div>

    <select style="text-align:center;" id="menu-category" class="menu-category">
      <option value="blue">ALL ITEMS</option>
      <option value="yellow">MAIN DISHES</option>
      <option value="red">SIDE SNACKS</option>
      <option value="green">DRINKS</option>
    </select>

    <!-- ALL ITEMS -->
<div class="blue msg">
 <h2 class="menu-title">ALL ITEMS</h2>

  <div class="menu-grid">
    <?php
      // gabung semua array menu
      $allItems = array_merge($mainDishes, $sides, $drinks);
    ?>

    <?php foreach ($allItems as $item): ?>
  <?php
$stok = (int)($item['stok'] ?? 0);
?>

<div class="menu-card">

  <img src="../image/<?= htmlspecialchars($item['foto'] ?? 'default-food.png'); ?>"
       onerror="this.src='../image/default-food.png';"
       alt="menu">

  <div>
    <h3><?= htmlspecialchars($item['nama_menu']); ?></h3>

    <div class="price">
      Rp<?= number_format($item['harga'],0,',','.'); ?>
    </div>

    <p class="desc"><?= htmlspecialchars($item['deskripsi'] ?? '-'); ?></p>

    <?php if ($stok > 0): ?>
      <p class="stok-tersedia">Tersedia (<?= $stok ?>)</p>

      <a class="btn-order" href="../CustomerOrder/add_to_cart.php?id=<?= (int)$item['id_menu']; ?>">
        Order Now
      </a>

    <?php else: ?>
      <p class="stok-habis">Habis</p>

      <button class="btn-order btn-disabled" disabled>
        Tidak Tersedia
      </button>
    <?php endif; ?>

  </div>
</div>

<?php endforeach; ?>

  </div>
</div>


    <div class="yellow msg">
  <h2 class="menu-title">MAIN DISHES</h2>

  <div class="menu-grid">
    <?php foreach ($mainDishes as $item): ?>
      <div class="menu-card">

        <img src="../image/<?= htmlspecialchars($item['foto'] ?? 'default-food.png'); ?>"
       onerror="this.src='../image/default-food.png';"
       alt="menu">

        <div>
          <h3><?= htmlspecialchars($item['nama_menu']); ?></h3>

          <div class="price">
            Rp<?= number_format($item['harga'],0,',','.'); ?>
          </div>

          <p class="desc"><?= htmlspecialchars($item['deskripsi'] ?? '-'); ?></p>

          <?php if ($stok > 0): ?>
            <p class="stok-tersedia">Tersedia (<?= $stok ?>)</p>

            <a class="btn-order" href="../CustomerOrder/add_to_cart.php?id=<?= (int)$item['id_menu']; ?>">
              Order Now
            </a>

          <?php else: ?>
            <p class="stok-habis">Habis</p>

            <button class="btn-order btn-disabled" disabled>
              Tidak Tersedia
            </button>
          <?php endif; ?>

        </div>
      </div>

    <?php endforeach; ?>
  </div>
</div>


<div class="red msg">
  <h2 class="menu-title">SIDE SNACKS</h2>

  <div class="menu-grid">
    <?php foreach ($sides as $item): ?>
      <div class="menu-card">

        <img src="../image/<?= htmlspecialchars($item['foto'] ?? 'default-food.png'); ?>"
       onerror="this.src='../image/default-food.png';"
       alt="menu">

        <div>
          <h3><?= htmlspecialchars($item['nama_menu']); ?></h3>

          <div class="price">
            Rp<?= number_format($item['harga'],0,',','.'); ?>
          </div>

          <p class="desc"><?= htmlspecialchars($item['deskripsi'] ?? '-'); ?></p>

          <?php if ($stok > 0): ?>
            <p class="stok-tersedia">Tersedia (<?= $stok ?>)</p>

            <a class="btn-order" href="../CustomerOrder/add_to_cart.php?id=<?= (int)$item['id_menu']; ?>">
              Order Now
            </a>

          <?php else: ?>
            <p class="stok-habis">Habis</p>

            <button class="btn-order btn-disabled" disabled>
              Tidak Tersedia
            </button>
          <?php endif; ?>

        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<div class="green msg">
  <h2 class="menu-title">DRINKS</h2>

  <div class="menu-grid">
    <?php foreach ($drinks as $item): ?>
      <div class="menu-card">

        <img src="../image/<?= htmlspecialchars($item['foto'] ?? 'default-food.png'); ?>"
       onerror="this.src='../image/default-food.png';"
       alt="menu">

        <div>
          <h3><?= htmlspecialchars($item['nama_menu']); ?></h3>

          <div class="price">
            Rp<?= number_format($item['harga'],0,',','.'); ?>
          </div>

          <p class="desc"><?= htmlspecialchars($item['deskripsi'] ?? '-'); ?></p>

          <?php if ($stok > 0): ?>
            <p class="stok-tersedia">Tersedia (<?= $stok ?>)</p>

            <a class="btn-order" href="../CustomerOrder/add_to_cart.php?id=<?= (int)$item['id_menu']; ?>">
              Order Now
            </a>

          <?php else: ?>
            <p class="stok-habis">Habis</p>

            <button class="btn-order btn-disabled" disabled>
              Tidak Tersedia
            </button>
          <?php endif; ?>

        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

  </div>
</section>

  <!-- End menu Section -->


  
  <!-- About Section -->
<section id="about" ">
  <div class="about container">
    <div class="col-right">
        <h1 class="section-title" >About <span>Us</span></h1>
        <h2>TWO COUSIN Company History:</h2>
 <p>TWO COUSINS Meat Grill & Bar berdiri di atas landasan persaudaraan yang kuat dan hasrat yang tak tertandingi terhadap kualitas daging panggang yang autentik. Berawal dari visi dua sepupu yang ingin menghadirkan tempat makan yang jujur, kami mendedikasikan diri untuk menjadi destinasi utama bagi para pencinta kuliner yang menghargai detail, mulai dari pemilihan bahan hingga proses penyajian di atas meja. Kami memahami bahwa memanggang bukan sekadar memasak dengan api, melainkan sebuah seni untuk mengeluarkan karakter terbaik dari setiap serat daging melalui teknik grilling yang presisi dan bumbu rahasia yang telah kami sempurnakan.

 </p>
 <p>Di setiap sudut TWO COUSINS, Anda akan merasakan atmosfer yang maskulin, elegan, namun tetap hangat—sebuah ruang yang dirancang khusus untuk menciptakan momen kebersamaan yang berkesan. Fokus utama kami adalah menyajikan pilihan daging premium dengan standar marbling tinggi yang dipadukan dengan konsep bar modern. Kami percaya bahwa hidangan panggangan yang sempurna harus didampingi dengan minuman yang tepat, sehingga setiap kunjungan menjadi sebuah pengalaman sensorik yang lengkap, di mana aroma asap kayu yang khas bertemu dengan kenyamanan layanan kelas atas.
 </p>
 <p>Lebih dari sekadar restoran, TWO COUSINS Meat Grill & Bar adalah tempat di mana tradisi keluarga dan standar modern bersatu. Kami berkomitmen untuk terus menjaga integritas rasa dan keramahan yang tulus, memastikan setiap tamu yang datang merasa seperti bagian dari keluarga besar kami. Baik untuk perayaan pencapaian besar maupun sekadar makan malam santai di akhir pekan, kami mengundang Anda untuk duduk, bersantai, dan menikmati kelezatan daging panggang terbaik yang diproses langsung dari hati kami menuju piring Anda.
 </p>
 
    
      </div>
    </div>
  </section>
  <!-- End About Section -->
  
  
 <!-- Contact Section -->
<section id="contact">
  <div class="contact container">
    <div>
      <h1 class="section-title">Contact <span>info</span></h1>
    </div>
    <div class="contact-items">
      <div class="contact-item contact-item-bg">
        <div class="contact-info">
          <div class='icon'><img src="../image/icons8-phone-100.png" alt=""/></div>
          <h1>Phone</h1>
          <h2>+62 838-9042-3200</h2>
        </div>
      </div>
      
      <div class="contact-item contact-item-bg"> 
        <div class="contact-info">
          <div class='icon'><img src="../image/icons8-email-100.png" alt=""/></div>
          <h1>Email</h1>
          <h2>TwoCousin@gmail.com</h2> 
        </div>
      </div>
      
      <div class="contact-item contact-item-bg">
        <div class="contact-info">
          <div class='icon'> <img src="../image/icons8-home-address-100.png" alt=""/></div>
          <h1>Address</h1>
          <h2>Jl. Kemang Raya No. 21, RT 004/RW 002
Kel. Bangka, Kec. Mampang Prapatan
Jakarta Selatan 12730
Indonesia</h2>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- End Contact Section -->

<?php 
include_once('../components/footer.php');
?>