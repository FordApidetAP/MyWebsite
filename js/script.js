let navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btnn').onclick = () =>{
   navbar.classList.toggle('active');
}

window.onscroll = () =>{
   navbar.classList.remove('active');
}

document.querySelectorAll('input[type="number"]').forEach(inputNumber => {
   inputNumber.oninput = () =>{
      if(inputNumber.value.length > inputNumber.maxLength) inputNumber.value = inputNumber.value.slice(0, inputNumber.maxLength);
   };
});

let profileeDropdownList = document.querySelector(".profilee-dropdown-list");
let btnn = document.querySelector(".profilee-dropdown-btnn");

let classList = profileeDropdownList.classList;

const toggle = () => classList.toggle("active");

window.addEventListener("click", function (e) {
  if (!btnn.contains(e.target)) classList.remove("active");
});
