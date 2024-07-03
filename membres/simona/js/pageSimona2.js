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


 //calculateur ingredients
 document.getElementById('calculateButton').addEventListener('click', function() {
    const ingredientSection = document.getElementById('ingredientSection');

    if (ingredientSection.classList.contains('hidden')) {
        ingredientSection.classList.remove('hidden');
        this.textContent = 'Masquer les ingrédients';
    } else {
        ingredientSection.classList.add('hidden');
        this.textContent = 'Calculez les ingrédients en fonction du nombre de personnes';
    }
});

document.getElementById('numPersons').addEventListener('input', calculateIngredients);

function calculateIngredients() {
    const numPersons = parseInt(document.getElementById('numPersons').value, 10);
    const basePersons = 8; // Numărul de persoane pentru rețeta de bază
    const pricePerPerson = 1.80;
    const ingredientList = document.getElementById('ingredient-list').getElementsByTagName('li');

    Array.from(ingredientList).forEach(function(ingredient) {
        if (ingredient.hasAttribute('data-base-quantity')) {
            const baseQuantity = parseFloat(ingredient.getAttribute('data-base-quantity'));
            const newQuantity = (baseQuantity / basePersons) * numPersons;
            ingredient.querySelector('.ingredient_qte').textContent = newQuantity.toFixed(2) + (ingredient.querySelector('.ingredient_label').textContent === 'Oeuf(s)' ? '' : ' g');
        }
    });

    const totalPrice = (pricePerPerson * numPersons).toFixed(2);
    document.getElementById('js-price-value').textContent = totalPrice;
}

// Inițializează calculul inițial când pagina este încărcată
calculateIngredients();

    //cards
    $(document).ready(function(){
$(document).on("keypress", function(event) {
// If 'alt + g' keys are pressed:
if (event.which === 169){
    $('#toggle-grid').toggle();
 }
});

$('#toggle-grid').on("click"
, function() {
  $('.pixel-grid').toggle();
  $('#toggle-grid').toggleClass('orange');
});
});
var main = function () {
$('.push-bar').on('click', function(event){
  if (!isClicked){
    event.preventDefault();
    $('.arrow').trigger('click');
    isClicked = true;
  }
});

$('.arrow').css({
  'animation': 'bounce 2s infinite'
});
$('.arrow').on("mouseenter", function(){
    $('.arrow').css({
            'animation': '',
            'transform': 'rotate(180deg)',
            'background-color': 'black'
       });
});
 $('.arrow').on("mouseleave", function(){
    if (!isClicked){
        $('.arrow').css({
                'transform': 'rotate(0deg)',
                'background-color': 'black'
           });
    }
});

var isClicked = false;

$('.arrow').on("click", function(){
    if (!isClicked){
        isClicked = true;
        $('.arrow').css({
            'transform': 'rotate(180deg)',
            'background-color': 'black',
       });

        $('.bar-cont').animate({
            top: "-15px"
        }, 300);
        $('.main-cont').animate({
            top: "0px"
        }, 300);
        // $('.news-block').css({'border': '0'});
        // $('.underlay').slideDown(1000);

    }
    else if (isClicked){
        isClicked = false;
        $('.arrow').css({
            'transform': 'rotate(0deg)',       'background-color': 'black'
       });

        $('.bar-cont').animate({
            top: "-215px"
        }, 300);
        $('.main-cont').animate({
            top: "-215px"
        }, 300);
    }
console.log('isClicked= '+isClicked);
});

$('.card').on('mouseenter', function() {
  $(this).find('.card-text').slideDown(300);
});

$('.card').on('mouseleave', function(event) {
   $(this).find('.card-text').css({
     'display': 'none'
   });
 });
};

$(document).ready(main);