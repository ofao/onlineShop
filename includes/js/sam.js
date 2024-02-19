const openMenuButton = document.getElementById('menu');
const sidemenuBar = document.getElementById("side-menubar");
const menubar = document.getElementById("menubar");
const content = document.getElementById("content");
const mask = document.getElementById("mask");

mask.addEventListener('click', closeMenu);
openMenuButton.onclick = openMenu;
window.matchMedia('(max-width: 750px)').addEventListener('change', function (event) {
	console.log(window.innerWidth);
  if (window.innerWidth > 750) {
  	openMenuButton.style.display = 'none';
  	menubar.style.display = 'inline-block';
  	content.style.width='100%';
  	mask.style.display = 'none';
  	sidemenuBar.style.width = "0%";
  }
  else {
  	openMenuButton.style.display = 'inline-block';
  	menubar.style.display = 'none';
  }
});
function openMenu() {
  sidemenuBar.style.width = "30%";
  content.style.width='70%';
  mask.style.display = 'inline-block';
  content.style.zIndex = '100';
  openMenuButton.onclick = closeMenu;
}

function closeMenu() {
  sidemenuBar.style.width = "0%";
  mask.style.display = 'none';
  content.style.width='100%';
  openMenuButton.onclick = openMenu;
}
