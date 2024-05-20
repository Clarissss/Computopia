
// load page
window.addEventListener("load", function () {
    const loader = document.querySelector(".loader");
    setTimeout(function() {
      loader.classList.add("hidden");
    }, 350); // 350ms = 0.35 seconds delay
});

// login-register
const wrapper = document.querySelector('.wrapper');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');
const btnPopup = document.querySelector('.btnLogin-popup');
const iconClose = document.querySelector('.icon-close');

registerLink.addEventListener('click', () => {
  wrapper.classList.add('active');
});

loginLink.addEventListener('click', () => {
  wrapper.classList.remove('active');
});

btnPopup.addEventListener('click', () => {
  wrapper.classList.add('active-popup');
});

iconClose.addEventListener('click', () => {
  wrapper.classList.remove('active-popup');
});



// toggle class active untuk hamburger menu
const navbarNav = document.querySelector('.navbar-nav');

// ketika hamburger menu di klik
document.querySelector('#hamburger-menu').onclick = (e) => {
    navbarNav.classList.toggle('active');
    e.preventDefault();
};

// toggle class active untuk search form
const searchForm = document.querySelector('.search-form');
const searchBox = document.querySelector('#search-box');

document.querySelector('#search-button').onclick = (e) => {
    searchForm.classList.toggle('active');
    searchBox.focus();
    e.preventDefault();
}

// toggle class active untuk shoppping cart
const shoppingCart = document.querySelector('.shopping-cart');
document.querySelector('#shopping-cart-button').onclick = (e) => {
    shoppingCart.classList.toggle('active');
    e.preventDefault();
}

// klik di luar elemen
const hm = document.querySelector('#hamburger-menu');
const sb = document.querySelector('#search-button');
const sc = document.querySelector('#shopping-cart-button');

document.addEventListener('click', function(e){
    if(!hm.contains(e.target) && !navbarNav.contains(e.target)){
        navbarNav.classList.remove('active');
    }
    if(!sb.contains(e.target) && !searchForm.contains(e.target)){
        searchForm.classList.remove('active');
    }
    if(!sc.contains(e.target) && !shoppingCart.contains(e.target)){
        shoppingCart.classList.remove('active');
    }
});

// modal box
const itemDetailModal = document.querySelector('#item-detail-modal');

const itemDetailButtons = document.querySelectorAll('.item-detail-button');

itemDetailButtons.forEach((btn) =>  {
    btn.onclick = (e) => {
        itemDetailModal.style.display = 'flex';
        e.preventDefault();
    };
});


// klik tombol close modal
document.querySelector('.modal .close-icon').onclick = (e) => {
    itemDetailModal.style.display = 'none';
    e.preventDefault();
}

// klik di luar modal
window.onclick = (e) => {
    if (e.target === itemDetailModal) {
        itemDetailModal.style.display = 'none';
    }
};

const userDropdown = document.querySelector('#user-dropdown');
const userToggle = userDropdown.querySelector('.dropdown-toggle');
const userMenu = userDropdown.querySelector('.dropdown-menu');

userToggle.addEventListener('click', (event) => {
  event.preventDefault();
  userMenu.classList.toggle('show');
  userToggle.setAttribute('aria-expanded', userMenu.classList.contains('show'));
});

window.addEventListener('click', (event) => {
  if (!userDropdown.contains(event.target)) {
    userMenu.classList.remove('show');
    userToggle.setAttribute('aria-expanded', false);
  }
});

document.querySelectorAll('.add-to-cart-button').forEach(button => {
  button.addEventListener('click', event => {
    event.preventDefault();
    const productId = button.dataset.productId;
    $store.cart.add(productId);
  });
});

let cart = {
  items: [],

  add(productId) {
    fetch('add-to-cart.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ productId: productId })
    })
   .then(response => response.json())
   .then(data => {
      // Update the cart items array
      cart.items = data.items;
      // Update the cart total
      cart.total = data.total;
    })
   .catch(error => console.error('Error adding to cart:', error));
  }
};



//  window.onscroll = function() {
//     const scrollPosition = window.pageYOffset;
//     wrapper.style.transform = `translateY(${scrollPosition}px)`;
//   };

// // Remove the wrapper when close-btn is clicked
// document.getElementById('close-btn').addEventListener('click', function() {
//   wrapper.parentNode.removeChild(wrapper);
// });