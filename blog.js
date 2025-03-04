// Wait for the page to load
window.onload = function() {
    // Set a 2-second delay before showing the popup
    setTimeout(function() {
        // Show the popup overlay and start the fade-in animation
        let popupOverlay = document.querySelector('.popup-overlay');
        popupOverlay.style.display = 'flex';
        // Use a slight delay to trigger the fade-in effect
        setTimeout(function() {
            popupOverlay.style.opacity = '1'; // Fade-in effect
        }, 50); // Small delay to ensure display is set to 'flex' before opacity change
    }, 900); // 900 milliseconds = 2 seconds
};

// Close the popup when the close button is clicked
document.querySelector('.close-popup')?.addEventListener('click', function() {
    let popupOverlay = document.querySelector('.popup-overlay');
    popupOverlay.style.opacity = '0'; // Fade-out effect
    setTimeout(function() {
        popupOverlay.style.display = 'none'; // Hide the popup completely after fade-out
    }, 2000); // Wait for the fade-out to finish (2s)
});

// Close the popup if the user clicks outside the popup content
document.querySelector('.popup-overlay')?.addEventListener('click', function(e) {
    if (e.target === document.querySelector('.popup-overlay')) {
        let popupOverlay = document.querySelector('.popup-overlay');
        popupOverlay.style.opacity = '0'; // Fade-out effect
        setTimeout(function() {
            popupOverlay.style.display = 'none'; // Hide the popup completely after fade-out
        }, 2000); // Wait for the fade-out to finish (2s)
    }
});
