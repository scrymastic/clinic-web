// Show success/fail message if present and auto-hide it after 3 seconds
window.onload = function() {
    const statusMessage = document.getElementById('status-message');
    if (statusMessage) {
        statusMessage.style.display = 'block'; // Show message
        setTimeout(function() {
            // Delete the message after 3 seconds
            statusMessage.remove();

            // Remove the status from the URL after 3 seconds
            const url = new URL(window.location.href);
            url.searchParams.delete('status'); // Remove the 'status' parameter
            window.history.replaceState({}, document.title, url); // Update the URL without reloading the page
        }, 3000);
    }
};