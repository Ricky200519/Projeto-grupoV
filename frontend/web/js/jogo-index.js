document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.accordion-collapse').forEach(collapse => {
        const button = collapse.previousElementSibling.querySelector('.accordion-button');
        const icon = button.querySelector('.arrow');

        if (collapse.classList.contains('show')) {
            icon.style.transform = 'rotate(90deg)';
            button.classList.remove('collapsed');
        }

        collapse.addEventListener('show.bs.collapse', () => {
            icon.style.transform = 'rotate(90deg)';
        });

        collapse.addEventListener('hide.bs.collapse', () => {
            icon.style.transform = 'rotate(0deg)';
        });
    });
});
