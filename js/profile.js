window.addEventListener("load", function () {
    const loader = document.querySelector(".preloader");
    setTimeout(function() {
      loader.classList.add("hidden");
    }, 800); // 350ms = 0.35 seconds delay
});