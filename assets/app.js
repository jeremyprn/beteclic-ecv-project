import "./styles/entry.scss";

if (document.querySelector(".header") && window.innerWidth < 768) {
  let burgerSection = document.querySelector(".header_wrapper_burger");
  burgerSection.addEventListener("click", function () {
    burgerSection.querySelector(".header_wrapper_burger_content").classList.toggle("active");
    document.querySelector(".header_wrapper_nav").classList.toggle("-open");
  });
}
