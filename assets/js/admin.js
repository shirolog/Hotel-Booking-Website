/** @format */

(() => {
  //入力文字数の制限設定
  document.querySelectorAll("input[type]").forEach((inputNumber) => {
    inputNumber.oniput = () => {
      if (inputNumber.ariaValueMax.length > inputNumber.maxLength) {
        inputNumber.value = inputNumber.value.slice(0, inputNumber.maxLength);
      }
    };
  });

  //ヘッダーナビゲーション設定
  const $navBar = document.querySelector(".header .flex .navbar");
  const $menuBtn = document.querySelector("#menu-btn");

  $menuBtn.addEventListener("click", () => {
    $navBar.classList.toggle("active");
    $menuBtn.classList.toggle('fa-times');
  });

   window.addEventListener('scroll', ()=>{
    $navBar.classList.remove("active");
    $menuBtn.classList.remove('fa-times');
   })
})();
