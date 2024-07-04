document.addEventListener('DOMContentLoaded', function() {
    var comments = [];

    var commentForm = document.getElementById('comment-form');
    var showCommentBtn = document.getElementById('show-comment-form');

    showCommentBtn.addEventListener('click', function() {
        // Toggle 'hidden' class for the comment form
        commentForm.classList.toggle('hidden');
        
        // Change button text based on form state
        if (commentForm.classList.contains('hidden')) {
            showCommentBtn.textContent = 'Laissez un commentaire';
        } else {
            showCommentBtn.textContent = 'Masquer le formulaire';
        }
    });

    commentForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Stop the default form submission behavior
        commentForm.classList.add('hidden'); // Hide the form after submission
        showCommentBtn.textContent = 'Laissez un commentaire'; // Reset button text to the initial state
    });

    // Functionality for star rating
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

    // Form submission
    $('#comment-form').on('submit', function(e) {
        e.preventDefault();
        
        var name = $('#name').val();
        var email = $('#email').val();
        var subject = $('#subject').val();
        var comment = $('#comment').val();
        var rating = parseFloat($('#rating').val()); // Ensure rating is a float
        var date = new Date().toLocaleDateString();

        var commentData = {
            name: name,
            comment: comment,
            rating: rating,
            date: date
        };

        comments.push(commentData);

        var commentHtml = '<div class="comment">' +
                            '<div><strong>' + escapeHtml(name) + '</strong> <span class="comment-date">' + date + '</span></div>' +
                            '<div class="comment-rating">' + getStars(rating) + '</div>' +
                            '<div>' + escapeHtml(comment) + '</div>' +
                          '</div>';

        $(commentHtml).hide().appendTo('#comments-list').fadeIn(1000);

        updateSummary();

        // Reset the form
        $('#comment-form')[0].reset();
        $('#rating').val(0);
        $('.star-rating .fa-star').removeClass('checked');
    });

    // Function to generate stars
    function getStars(rating) {
        var starsHtml = '';
        for (var i = 1; i <= rating; i += 1) {
            if (i <= rating) {
                starsHtml += '<i class="fa fa-star checked"></i>';
            } else {
                starsHtml += '<i class="fa fa-star"></i>';
            }
        }
        return starsHtml;
    }

    // Function to escape HTML characters in comments
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

    // Function to update the average rating and number of comments
    function updateSummary() {
        var totalRating = comments.reduce(function(sum, comment) {
            return sum + comment.rating;
        }, 0);

        var averageRating = (totalRating / comments.length).toFixed(1);
        $('#average-rating').text('Moyenne des étoiles : ' + averageRating);
        $('#comments-count').text('Nombre de commentaires : ' + comments.length);
    }
});



// Funcția pentru calculul ingredientelor în funcție de numărul de persoane
function calculateIngredients() {
    // Obținem numărul de persoane din câmpul input
    var nrPersonnes = parseInt(document.getElementById('nr-personnes').value);

    // Lista de ingrediente cu cantitățile asociate
    var ingredients = {
        "pate": [
            { ingredient: "œufs (g)", quantite: 36 },
            { ingredient: "sucre (g)", quantite: 18 },
            { ingredient: "une pincée de sel", quantite: 1 },
            { ingredient: "huile (ml)", quantite: 4 },
            { ingredient: "cacao (g)", quantite: 10 },
            { ingredient: "farine (g)", quantite: 13 }
        ],
        "creme": [
            { ingredient: "chocolat noir 60% de cacao (g)", quantite: 25 },
            { ingredient: "crème pour la chantilly (ml)", quantite: 25 },
            { ingredient: "beurre (g)", quantite: 20 },
            { ingredient: "ampoule d'essence de rhum", quantite: 1/10 }
        ],
        "sirop": [
            { ingredient: "eau (ml)", quantite: 35 },
            { ingredient: "sucre (g)", quantite: 15 },
            { ingredient: "fiole d'essence de rhum", quantite: 1/10 }
        ],
        "glacage": [
            { ingredient: "chocolat ménager (g)", quantite: 10 },
            { ingredient: "huile (ml)", quantite: 8 }
        ]
    };

    // Funcție pentru generarea listei de ingrediente calculate
    function genereListe(ingredients) {
        var output = "<ul>";
        ingredients.forEach(function(item) {
            var quantiteCalculee = item.quantite * nrPersonnes;
            output += "<li>" + quantiteCalculee + " " + item.ingredient + "</li>";
        });
        output += "</ul>";
        return output;
    }

    // Calculăm cantitățile pentru fiecare categorie de ingrediente
    var resultat = "<h3>Pâte :</h3>" + genereListe(ingredients.pate);
    resultat += "<h3>Crème :</h3>" + genereListe(ingredients.creme);
    resultat += "<h3>Sirop :</h3>" + genereListe(ingredients.sirop);
    resultat += "<h3>Glaçage :</h3>" + genereListe(ingredients.glacage);

    // Afișăm rezultatul în div-ul cu id-ul "resultat"
    var resultatDiv = document.getElementById('resultat');
    resultatDiv.innerHTML = resultat;
    resultatDiv.style.display = 'block';

    // Schimbăm textul butonului după afișarea rezultatelor
    var toggleButton = document.getElementById('toggle-results');
    toggleButton.textContent = 'Masquer les ingrédients';
    toggleButton.style.display = 'inline-block'; // Afișăm butonul după afișarea rezultatelor
}

// Event listener pentru câmpul de introducere a numărului de persoane
document.getElementById('nr-personnes').addEventListener('input', function() {
    calculateIngredients(); // La fiecare schimbare, recalculăm ingredientele
});

// Toggle pentru afișarea/ascunderea rezultatelor
var toggleButton = document.getElementById('toggle-results');
toggleButton.addEventListener('click', function() {
    var resultatDiv = document.getElementById('resultat');
    if (resultatDiv.style.display === 'none') {
        // Dacă rezultatele sunt ascunse, le afișăm
        calculateIngredients(); // Apelăm funcția de calcul pentru a afișa rezultatele
        toggleButton.textContent = 'Masquer les ingrédients'; // Schimbăm textul butonului
    } else {
        // Dacă rezultatele sunt deja afișate, le ascundem
        resultatDiv.style.display = 'none';
        toggleButton.textContent = 'Afficher les ingrédients'; // Resetăm textul butonului
    }
});

// Ascundem butonul inițial până când sunt calculate și afișate rezultatele
toggleButton.style.display = 'none';
toggleButton.textContent = 'Afficher les ingrédients'; // Textul inițial al butonului