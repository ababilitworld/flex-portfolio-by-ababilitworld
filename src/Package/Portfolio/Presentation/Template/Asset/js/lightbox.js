jQuery(document).ready(function($) {
    var slideIndex = 1;
    showSlides(slideIndex);

    // Open modal
    function openModal() {
        $("#myModal").css("display", "block");
    }
    window.openModal = openModal;

    // Close modal
    function closeModal() {
        $("#myModal").css("display", "none");
    }
    window.closeModal = closeModal;

    // Next/previous controls
    function plusSlides(n) {
        showSlides(slideIndex += n);
    }
    window.plusSlides = plusSlides;

    // Thumbnail image controls
    function currentSlide(n) {
        showSlides(slideIndex = n);
    }
    window.currentSlide = currentSlide;

    // Show slides
    function showSlides(n) {
        var slides = $(".mySlides");
        var dots = $(".demo");
        var captionText = $("#caption");

        if (!slides.length) {
            console.error("No slides found");
            return;
        }

        if (n > slides.length) {
            slideIndex = 1;
        }
        if (n < 1) {
            slideIndex = slides.length;
        }

        slides.css("display", "none");
        dots.removeClass("active");

        if (slides.eq(slideIndex - 1).length) {
            slides.eq(slideIndex - 1).css("display", "block");
            if (dots.eq(slideIndex - 1).length) {
                dots.eq(slideIndex - 1).addClass("active");
                captionText.html(dots.eq(slideIndex - 1).attr("alt"));
            }
        } else {
            console.error("Slide index out of bounds:", slideIndex);
        }
    }
    window.showSlides = showSlides;
});
