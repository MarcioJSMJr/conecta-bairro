document.addEventListener('DOMContentLoaded', () => {
  "use strict";

  if (typeof AOS !== 'undefined') {
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  }

  const sidebarToggleButtons = document.querySelectorAll('.sidebar-toggle-btn');

  sidebarToggleButtons.forEach(button => {
    button.addEventListener('click', event => {
      event.preventDefault();
      document.getElementById('wrapper').classList.toggle('sidebar-toggled');
    });
  });

});