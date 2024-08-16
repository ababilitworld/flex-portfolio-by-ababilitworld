jQuery(document).ready(function($) {
    var modal = $("#myModal");

    // Close modal function
    function closeModal() {
        modal.hide();
    }

    // Event listener for close button
    modal.on("click", ".close", closeModal);

    // Event listener for window click to close modal
    $(window).on("click", function(event) {
        if (event.target === modal[0]) {
            closeModal();
        }
    });

    // Event delegation for portfolio card click
    $(document).on("click", ".portfolio-card", function() {
        var title = $(this).data("title");
        var images = $(this).data("images");

        if (Array.isArray(images)) {
            showModal(title, images);
        } else {
            console.error("Invalid images data:", images);
        }
    });

    // Show modal function
    function showModal(title, images) {
        var slideshow = modal.find(".slideshow");
        var thumbnailRow = modal.find(".thumbnail-row");
        var caption = modal.find("#caption");

        // Clear existing content
        slideshow.empty();
        thumbnailRow.empty();

        // Set modal title
        caption.text(title);

        // Populate slideshow and thumbnails
        $.each(images, function(index, image) {
            var slide = $('<div class="mySlides">' +
                '<div class="numbertext">' + (index + 1) + ' / ' + images.length + '</div>' +
                '<img src="' + image + '" class="slider-image">' +
                '</div>');
            slideshow.append(slide);

            var thumbnail = $('<img class="demo" src="' + image + '" alt="Thumbnail">');
            thumbnail.on("click", function() {
                currentSlide(index + 1);
            });
            thumbnailRow.append(thumbnail);
        });

        // Show modal
        modal.show();

        // Show first slide by default
        currentSlide(1);
    }

    var slideIndex = 1;

    // Set current slide function
    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    // Show slides function
    function showSlides(n) {
        var slides = modal.find(".mySlides");
        var dots = modal.find(".demo");

        if (n > slides.length) {
            slideIndex = 1
        }
        if (n < 1) {
            slideIndex = slides.length
        }

        slides.hide();
        dots.removeClass("active");

        slides.eq(slideIndex - 1).show();
        dots.eq(slideIndex - 1).addClass("active");
    }
});
