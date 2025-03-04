// Show the pop-up
document.getElementById('popup').style.display = 'flex';

// Close the pop-up when the close button is clicked
document.getElementById('closePopup').onclick = function() {
    document.getElementById('popup').style.display = 'none';
};

// Optional: Close the pop-up when clicking outside of it
window.onclick = function(event) {
    if (event.target === document.getElementById('popup')) {
        document.getElementById('popup').style.display = 'none';
    }
};


