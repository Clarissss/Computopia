<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Web Gacor</title>

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
      <a href="#" class="navbar-logo">Compu<span>topia</span> .</a>

      <div class="navbar-nav">
        <a href="index.php">Home</a>
        <a href="about.html">About</a>
        <a href="#shop">Shop</a>
        <a href="#products">Products</a>
        <a href="contact.php">Contact</a>
      </div>

      <div class="navbar-extra">
        <a href="#" id="search-button"> <i data-feather="search"></i></a>
        <a href="#" id="shopping-cart-button">
          <i data-feather="shopping-cart"></i>
          <span class="quantity-badge" x-show="$store.cart.quantity" x-text="$store.cart.quantity"></span>
        </a>
        <a href="#" id="user"> <i data-feather="user"></i></a>
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
        <a href="index.html">Home</a>
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
  </body>
</html>
