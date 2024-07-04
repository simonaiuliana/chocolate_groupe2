$('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    autoplay: true,
    autoplayHoverPause: true,
    autoplaySpeed: 1000,
    responsive:{
        0:{
            items:1,
            stagePadding: 0, // Supprime le padding pour les petits écrans
            margin: 0, // Supprime la marge pour les petits écrans
        },
        600:{
            items:2
        },
        1000:{
            items:3
        }
    }
});
AOS.init({
    duration: 1000,
    once: true
});

document.addEventListener('DOMContentLoaded', function() {
    var checkboxes = document.querySelectorAll('.list-group-item input[type="checkbox"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            if (checkbox.checked) {
                checkbox.parentElement.classList.add('strikethrough');
            } else {
                checkbox.parentElement.classList.remove('strikethrough');
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const steps = document.querySelectorAll('.steps-list li');

    steps.forEach(step => {
        step.addEventListener('click', function() {
            this.classList.toggle('completed');
        });
    });
});

$(document).ready(function() {
    $('#show-form-btn').click(function() {
        $('#form-container').show(); // Show the form
        $(this).hide(); // Hide the show form button
    });

    $('#close-form-btn').click(function() {
        $('#form-container').hide(); // Hide the form
        $('#show-form-btn').show(); // Show the show form button
    });
});



/** Comment */

$(document).ready(function() {
    // Fonctionnalité de notation par étoiles
    $('.star-rating .fa-star').on('click', function() {
        var rating = $(this).data('rating');
        $('#rating').val(rating);
        $('.star-rating .fa-star').each(function() {
            if ($(this).data('rating') <= rating) {
                $(this).addClass('checked');
            } else {
                $(this).removeClass('checked');
            }
        });
    });

    // Soumission du formulaire
    $('#comment-form').on('submit', function(e) {
        e.preventDefault();
        
        var name = $('#name').val();
        var email = $('#email').val();
        var subject = $('#subject').val();
        var comment = $('#comment').val();
        var rating = parseInt($('#rating').val()); // Asigurăm că rating-ul este un număr întreg
        
        var date = new Date().toLocaleDateString();

        var commentHtml = '<div class="comment">' +
        '<div><strong>[' + escapeHtml(name) + ']</strong> ' + escapeHtml(subject) + ' - <span class="comment-date">' + date + '</span></div>' +
        '<div class="comment-rating">' + getStars(rating) + '</div>' +
        '<div>' + escapeHtml(comment) + '</div>' +
    '</div>';

        $(commentHtml).hide().appendTo('#comments-list').fadeIn(1000);

        // Réinitialiser le formulaire
        $('#comment-form')[0].reset();
        $('#rating').val(0);
        $('.star-rating .fa-star').removeClass('checked');
    });

    // Fonction pour générer les étoiles
    function getStars(rating) {
        var starsHtml = '';
        for (var i = 1; i <= rating; i++) {
            if (i <= rating) {
                starsHtml += '<i class="fa fa-star checked"></i>';
            } else {
                starsHtml += '<i class="fa fa-star"></i>';
            }
        }
        return starsHtml;
    }

    // Fonction pour échapper les caractères HTML dans les commentaires
    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;',
            '/': '&#x2F;',
            '`': '&#x60;',
            '=': '&#x3D;'
        };
        return text.replace(/[&<>"'`=\/]/g, function(m) { return map[m]; });
    }
});

