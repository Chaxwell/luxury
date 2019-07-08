const path = window.location.href;
const menuLinks = document.querySelectorAll('.sidebar-sticky ul li a');


menuLinks.forEach(link => {
    if (link.href == path) {
        link.classList.add('active');
    } else {
        link.classList.remove('active');
    }
});
