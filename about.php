<?php
session_start();
include('server/connection.php');

if (isset($_SESSION['logged_in'])) {

}

// if (isset($_SESSION['logged_in_adm'])) {
//   header('Location: adminDashboard.php');
//   exit;
// }

if (isset($_POST['login_btn'])) {
  $login_credential = $_POST['login_credential'];
  $password = $_POST['password'];

  // Check if user is an admin
  $query = "SELECT * FROM admins WHERE admin_name =? AND admin_password =? LIMIT 1";
  $stmt_login = $conn->prepare($query);
  $stmt_login->bind_param('ss', $login_credential, $password);
  $stmt_login->execute();
  $stmt_login->store_result();

  if ($stmt_login->num_rows() == 1) {
    $stmt_login->bind_result($admin_id, $admin_name, $admin_password, $admin_phone, $admin_photo);
    $stmt_login->fetch();
    $_SESSION['admin_id'] = $admin_id;
    $_SESSION['admin_name'] = $admin_name;
    $_SESSION['admin_phone'] = $admin_phone;
    $_SESSION['admin_photo'] = $admin_photo;
    $_SESSION['logged_in_adm'] = true;
    header('Location: adminDashboard.php');
    exit;
  } else {
    // Check if user is a regular user
    $query = "SELECT * FROM users WHERE (user_email =? OR user_phone =?) AND user_password =? LIMIT 1";
    $stmt_login = $conn->prepare($query);
    $stmt_login->bind_param('sss', $login_credential, $login_credential, $password);
    $stmt_login->execute();
    $stmt_login->store_result();

    if ($stmt_login->num_rows() == 1) {
      $stmt_login->bind_result($user_id, $user_name, $user_email, $user_password, $user_address, $user_phone, $user_photo);
      $stmt_login->fetch();
      $_SESSION['user_id'] = $user_id;
      $_SESSION['user_name'] = $user_name;
      $_SESSION['user_email'] = $user_email;
      $_SESSION['user_address'] = $user_address;
      $_SESSION['user_phone'] = $user_phone;
      $_SESSION['user_photo'] = $user_photo;
      $_SESSION['logged_in'] = true;
      header('Location: index.php');
      exit;
    } else {
      header('Location: index.php?error=1');
      exit;
    }
  }
}

if (isset($_GET['logout'])) {
  if (isset($_SESSION['logged_in'])) {
    unset($_SESSION['logged_in']);
    unset($_SESSION['user_email']);
    header('location: index.php');
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Computopia</title>

    <!-- icon -->
    <link rel="icon" href="img/ajazz2.svg" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- feather icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- my style -->
    <link rel="stylesheet" href="css/style.css" />

    <!-- alpineJs -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- app -->
    <script src="src/app.js"></script>
  </head>
  <body>
    <!-- page loader start -->
    <div class="loader">
      <span class="loader_dot" style="--d: 200ms"></span>
      <span class="loader_dot" style="--d: 400ms"></span>
      <span class="loader_dot" style="--d: 600ms"></span>
      <span class="loader_dot" style="--d: 800ms"></span>
      <span class="loader_dot" style="--d: 1000ms"></span>
    </div>  
    <!-- page loader end -->

    <!-- navbar start -->
    <nav class="navbar" x-data>
      <a href="index.php" class="navbar-logo">Compu<span>topia</span> .</a>

      <div class="navbar-nav">
    <a href="index.php">Home</a>
    <a href="about.php">About</a>
    <a href="#products">Shop</a>
    <a href="contact.php">Contact</a>
    <button class="btnLogin-popup" x-show="!isLoggedIn"> Login </button>
  </div>
  <div class="navbar-extra">
    <a href="#" id="search-button"> <i data-feather="search"></i></a>
    <a href="#" id="shopping-cart-button">
      <i data-feather="shopping-cart"></i>
      <span class="quantity-badge" x-show="$store.cart.quantity" x-text="$store.cart.quantity"></span>
    </a>
    <div class="dropdown" id="user-dropdown" x-show="isLoggedIn">
      <a href="#" id="user" class="dropdown-toggle"> <i data-feather="user"></i></a>
      <ul class="dropdown-menu">
        <li><a href="userProfile.php" class="dropdown-item">User Profile</a></li>
        <li><a href="#" class="dropdown-item">Recent Orders</a></li>
        <li><a href="index.php?logout=1" class="dropdown-item">Logout</a></li>
      </ul>
    </div>
    <a href="#" id="hamburger-menu"> <i data-feather="menu"></i></a>
  </div>


      <!-- search form start -->
      <div class="search-form">
        <input type="search" id="search-box" placeholder="Search Here..." />
        <label for="search-box"><i data-feather="search"></i></label>
      </div>
      <!-- search form end -->

      <!-- shopping cart start -->
      <div class="shopping-cart">
        <template x-for="(item, index) in $store.cart.items" x-keys="index">
        <div class="cart-item">
          <img :src="`img/products/${item.img}`" :alt="item.name" />
          <div class="item-detail">
            <h3 x-text="item.name"></h3>
            <div class="item-price">
              <span x-text="rupiah(item.price)"></span> &times;
              <button id="remove" @click="$store.cart.remove(item.id)">&minus;</button>
              <span x-text="item.quantity"></span>
              <button id="add" @click="$store.cart.add(item)">&plus;</button> &equals;
              <span x-text="rupiah(item.total)"></span>
            </div>
          </div>
        </div>
      </template>
      <h4 x-show="!$store.cart.items.length" style="margin-top: 1rem;">Cart is Empty</h4>
      <h4 x-show="$store.cart.items.length">Total : <span x-text="rupiah($store.cart.total)"></span></h4>
      
      <div class="form-container" x-show="$store.cart.items.length">
        <form action="" id="checkoutForm">
          <h5>Customer Detail</h5>
          <label for="name">
            <span>Name</span>
            <input type="text" name="name" id="name">
          </label>

          <label for="email">
            <span>Email</span>
            <input type="email" name="email" id="email">
          </label>

          <label for="phone">
            <span>Phone</span>
            <input type="number" name="phone" id="phone" autocomplete="off">
          </label>

          <button class="checkout-button" type="button" id="checkout-button" value="checkout">
            Checkout
          </button>

        </form>
      </div>
    </div>
      <!-- shopping cart end -->
    </nav>
    <!-- navbar end -->
    
    <!-- hero section start -->
    <section class="hero" id="home">
    <!-- login pop-up start -->
    <div class="wrapper">
      <span span class="icon-close">
        <i data-feather="x-circle"></i>
      </span>

      <div class="form-box login">
        <h2>Login</h2>
        <form method="post">
          <div class="input-box">
            <span class="icon">
              <i data-feather="user"></i>
            </span>
              <input type="text" name="login_credential" required>
              <label>Username, Email, or Phone Number</label>
          </div>
          <div class="input-box">
            <span class="icon">
              <i data-feather="lock"></i>
            </span>
              <input type="password" name="password" required>
              <label>Password</label>
          </div>
          <div class="remember-forgot">
            <label><input type="checkbox"> Remember me </label>
            <a href="#">Forgot Password?</a>
          </div>
          <button type="submit" class="btnUser" name="login_btn">Login</button>
          <div class="login-register">
            <p>Don't have an account? <a href="#" class="register-link">Register</a></p>
          </div>
        </form>
      </div>

      <div class="form-box register">
        <h2>Register</h2>
        <form method="post" action="registerLog.php">
          <div class="input-box">
            <span class="icon">
              <i data-feather="user"></i>
            </span>
              <input type="text" name="user_name" required>
              <label>Username</label>
          </div>
          <div class="input-box">
            <span class="icon">
              <i data-feather="mail"></i>
            </span>
              <input type="mail" name="user_email" required>
              <label>Email</label>
          </div>
          <div class="input-box">
            <span class="icon">
              <i data-feather="lock"></i>
            </span>
              <input type="password" name="user_password" required>
              <label>Password</label>
          </div>
          <div class="remember-forgot">
            <label><input type="checkbox"> I agree to the terms & conditions </label>
          </div>
          <button type="submit" class="btnRegister">Register</button>
          <div class="login-register">
            <p>Already have an account? <a href="#" class="login-link">Login</a></p>
          </div>
        </form>
      </div>
    </div>
    <!-- login pop-up end -->

      <main class="content">
        <!-- <h1>Compu<span>topia</span> .</h1>
        <p>
          Solusi Produk Berkualitas Tinggi 
        </p>
        <p>
          dan Layanan Pelanggan Yang Tak
          Tertandingi
        </p> -->
        <br><br><br><br>
        <a href="#" class="cta">Buy Now</a>
      </main>
    </section>
    <!-- hero section end -->

    <!-- about section start -->
    <section id="about" class="about">
      <h2><span>About</span> Us</h2>

      <div class="row">
        <div class="about-img">
          <img src="img/login.svg" alt="About Us" />
        </div>
        <div class="content">
          <h3>Kenapa memilih product kami?</h3>
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum
            ratione delectus voluptas tempora nulla repudiandae, eius, ipsam
            minus sapiente debitis sunt eum ipsa quibusdam blanditiis et aut ut.
            Aliquam, esse.
          </p>
        </div>
      </div>
    </section>
    <!-- about section end -->

    <!-- shop section start -->
    <section id="shop" class="shop">
      <h2>Our<span> Favourite </span>Products</h2>
      <p>
        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Blanditiis nam
        nisi distinctio dolores a eum facere tempora quo alias temporibus illum
        minus et, labore veritatis voluptatem voluptas maxime? Maiores, quidem.
      </p>

      <div class="row">
        <div class="shop-card">
          <img src="img/shop/1.jpg" alt="Earphone" class="shop-card-img" />
          <h3 class="shop-card-tittle">- Earphone -</h3>
          <p class="shop-card-price">IDR 500K</p>
        </div>
        <div class="shop-card">
          <img src="img/shop/1.jpg" alt="Earphone" class="shop-card-img" />
          <h3 class="shop-card-tittle">- Earphone -</h3>
          <p class="shop-card-price">IDR 500K</p>
        </div>
        <div class="shop-card">
          <img src="img/shop/1.jpg" alt="Earphone" class="shop-card-img" />
          <h3 class="shop-card-tittle">- Earphone -</h3>
          <p class="shop-card-price">IDR 500K</p>
        </div>
        <div class="shop-card">
          <img src="img/shop/1.jpg" alt="Earphone" class="shop-card-img" />
          <h3 class="shop-card-tittle">- Earphone -</h3>
          <p class="shop-card-price">IDR 500K</p>
        </div>
      </div>
    </section>
    <!-- shop section end -->

    <!-- products section start -->
    <section id="products" class="products" x-data="products">
      <h2><span>Our </span> Shop</h2>
      <p>
        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Corporis
        doloremque vitae inventore nulla autem debitis consectetur, perferendis
        ipsum a quod rem, quae mollitia, repellat aperiam accusamus modi
        repellendus perspiciatis laboriosam.
      </p>

      <div class="row">
        <template x-for="(item, index) in items" x-key="index">
        <div class="product-card">
          <div class="product-icons">
            <a href="#" @click.prevent="$store.cart.add(item)">
              <svg
              width="24"
              height="24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round">
            <use href="img/feather-sprite.svg#shopping-cart" />
          </svg>
            </a>
            <a href="#" class="item-detail-button">
                <svg
                width="24"
                height="24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
              <use href="img/feather-sprite.svg#eye" />
            </svg>
            </a>
          </div>
          <div class="product-image">
            <img :src="`img/products/${item.img}`" :alt="item.name" >
          </div>
          <div class="product-content">
            <h3 x-text="item.name"></h3>
            <div class="product-stars">
              <svg
                width="24"
                height="24"
                fill="currentColor"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
              <use href="img/feather-sprite.svg#star" />
            </svg>
              <svg
                width="24"
                height="24"
                fill="currentColor"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
              <use href="img/feather-sprite.svg#star" />
            </svg>
              <svg
                width="24"
                height="24"
                fill="currentColor"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
              <use href="img/feather-sprite.svg#star" />
            </svg>
              <svg
                width="24"
                height="24"
                fill="currentColor"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
              <use href="img/feather-sprite.svg#star" />
            </svg>
              <svg
                width="24"
                height="24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
              <use href="img/feather-sprite.svg#star" />
            </svg>
            </div>
            <div class="product-price"><span x-text="rupiah(item.price)"></span></div>
          </div>
        </div>
        </template>
      </div>
    </section>
    <!-- products section end -->

    <!-- contact section start -->
    <section id="contact" class="contact">
      <h2><span>Our</span> Contact</h2>
      <p>
        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Blanditiis nam
        nisi distinctio dolores a eum facere tempora quo alias temporibus illum
        minus et, labore veritatis voluptatem voluptas maxime? Maiores, quidem.
      </p>

      <div class="row">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126748.56211042157!2d107.64315755000001!3d-6.903449449999993!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6398252477f%3A0x146a1f93d3e815b2!2sBandung%2C%20Kota%20Bandung%2C%20Jawa%20Barat!5e0!3m2!1sid!2sid!4v1714985267671!5m2!1sid!2sid"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          class="map"
        ></iframe>

        <form action="">
          <div class="input-group">
            <i data-feather="user"></i>
            <input type="text" placeholder="Name " />
          </div>
          <div class="input-group">
            <i data-feather="mail"></i>
            <input type="text" placeholder="Email " />
          </div>
          <div class="input-group">
            <i data-feather="phone"></i>
            <input type="text" placeholder="Phone Number " />
          </div>
          <button type="submit" class="btn">Send a Message</button>
        </form>
      </div>
    </section>
    <!-- contact section end -->

    <!-- footer start -->
    <footer>
      <div class="socials">
        <a href="#"><i data-feather="instagram"></i></a>
        <a href="#"><i data-feather="twitter"></i></a>
        <a href="#"><i data-feather="facebook"></i></a>
      </div>

      <div class="links">
        <a href="#home">Home</a>
        <a href="#about">About Us</a>
        <a href="#shop">Shop</a>
        <a href="contact.html">Contact</a>
      </div>

      <div class="credits">
        <p>Created by <a href="">Kelompok Computopia</a>. | &copy; 2024.</p>
      </div>
    </footer>
    <!-- footer end -->

    <!-- modal box item detail start -->
    <div class="modal" id="item-detail-modal">
      <div class="modal-container">
        <a href="#" class="close-icon"><i data-feather="x-circle"></i></a>
        <div class="modal-content">
          <img src="img/products/1.jpg" alt="Product 1" />
          <div class="product-content">
            <h3>Product 1</h3>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia
              deleniti cupiditate quia aliquam quisquam ratione cum nam,
              architecto obcaecati praesentium doloribus eligendi dolores ipsam
              velit iste, fugiat iusto molestiae. Nesciunt.
            </p>
            <div class="product-stars">
                <i data-feather="star" class="star-full"></i>
                <i data-feather="star" class="star-full"></i>
                <i data-feather="star" class="star-full"></i>
                <i data-feather="star"></i>
                <i data-feather="star"></i>
              </div>
              <div class="product-price">IDR 200K <span>IDR 55K</span></div>
              <a href="#"><i data-feather="shopping-cart"></i> <span>Add to Cart</span></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- modal box item detail end -->

    <!-- feather icons -->
    <script>
      feather.replace();
    </script>

    <!-- my javasript -->
    <script src="js/script.js"></script>
    <script>
    let isLoggedIn = <?php echo isset($_SESSION['logged_in']) ? 'true' : 'false'; ?>;
  </script>
  </body>
</html>
