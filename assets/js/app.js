/** @format */

(() => {
  //ヘッダーナビゲーション設定
  const $menuBtn = document.querySelector("#menu-btn");
  const $navBar = document.querySelector(".navbar");

  $menuBtn.addEventListener("click", () => {
    $navBar.classList.toggle("active");
    $menuBtn.classList.toggle("fa-times");
  });

  window.addEventListener("scroll", () => {
    $navBar.classList.remove("active");
    $menuBtn.classList.remove("fa-times");
  });

  //contactのアコーディオンメニュー設定
  const $names = document.querySelectorAll(".contact .row .faq .box h3");
  const $boxes = document.querySelectorAll(".contact .row .faq .box");
  
  $names.forEach((faqBox, index) => {
    faqBox.addEventListener("click", () => {
      $boxes.forEach((box, boxIndex) => {
        if (index === boxIndex) {
          box.classList.toggle("active"); 
        } else {
          box.classList.remove("active"); 
        }
      });
    });
  });
  

  //home-sectionのswiper設定

  var swiper = new Swiper(".home-silder", {
    loop: true,
    effect: "coverflow",
    grabCursor: true,
    spaceBetween: 30,
    coverflowEffect: {
      rotate: 50,
      stretch: 0,
      depth: 100,
      modifier: 1,
      slideShadows: false,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });

  //gallery-sectionのswiper設定

  var swiper = new Swiper(".gallery-slider", {
    loop: true,
    effect: "coverflow",
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: "auto",
    coverflowEffect: {
      rotate: 0,
      stretch: 0,
      depth: 100,
      modifier: 2,
      slideShadows: true,
    },
    pagination: {
      el: ".swiper-pagination",
    },
  });

  //reviews-sectionのswiper設定

  var swiper = new Swiper(".reviews-slider", {
    loop: true,
    slidesPerView: "auto",
    grabCursor: true,
    spaceBetween: 30,
    pagination: {
      el: ".swiper-pagination",
    },

    breakpoints: {
      768: {
        slidesPerView: 1,
      },
      991: {
        slidesPerView: 2,
      },
    },
  });


})();
