window.addEventListener('load', function() {
    const heading = document.querySelector('.contact-card');
    if (heading) {
        heading.scrollIntoView({ behavior: 'smooth', block: 'end' });
    }
});
