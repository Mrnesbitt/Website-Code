// JavaScript to toggle the hamburger menu
document.getElementById("hamburger").addEventListener("click", function() {
    // Toggle the active class on the hamburger and nav-links
    this.classList.toggle("active");
    document.querySelector(".nav-links").classList.toggle("active");
});
