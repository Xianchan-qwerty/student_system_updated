document.addEventListener('DOMContentLoaded', function() {
    // Add fade-in effect to elements with .fade-in-element class
    // This script ensures the animation triggers after the DOM is fully loaded.
    const fadeInElements = document.querySelectorAll('.fade-in-element');
    fadeInElements.forEach(function(element) {
        // By adding 'animated' class, we ensure the CSS animation defined
        // for 'fade-in-element' and its delays are applied.
        element.classList.add('animated');
    });

    // Optional: Smooth scroll for any anchor links if you add them later
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth' // Smooth scrolling effect
            });
        });
    });

    // You can add more interactive JavaScript animations or effects here.
    // For example, a simple hover effect for table rows (already done in CSS)
    // or dynamic form validation.
});
