jQuery(document).ready(function($) {
    $('.fpba .category-list a').on('click', function(e) {
        e.preventDefault();

        var categoryId = $(this).data('category-id');
        var paged = 1; // Default to page 1

        loadPortfolioByCategory(categoryId, paged);
    });

    // Function to handle pagination click event
    $(document).on('click', '.fpba .pagination a', function(e) {
        var categoryId = $('.fpba .category-list a.active').data('category-id');
        
        if (categoryId) {
            e.preventDefault();
            var paged = $(this).attr('href').split('paged=')[1].split('&')[0];
            loadPortfolioByCategory(categoryId, paged);
        }
    });

    function loadPortfolioByCategory(categoryId, paged) {
        $.ajax({
            url: window.flex_portfolio_by_ababilitworld_template_localize.ajaxUrl,
            type: 'POST',
            data: {
                action: 'load_portfolio_by_category',
                category_id: categoryId,
                paged: paged
            },
            success: function(response) {
                $('.fpba .portfolio-wrap').html(response);
                $('.fpba .category-list a').removeClass('active');
                $('.fpba .category-list a[data-category-id="' + categoryId + '"]').addClass('active');

                // Highlight the current pagination link
                updatePaginationLinks(paged);
            }
        });
    }

    function updatePaginationLinks(currentPage) {
        $('.fpba .pagination a').removeClass('current');
        $('.fpba .pagination span').removeClass('current');

        $('.fpba .pagination a').each(function() {
            var page = $(this).attr('href').split('paged=')[1].split('&')[0];
            if (page == currentPage) {
                $(this).addClass('current');
            }
        });

        $('.fpba .pagination span').each(function() {
            var page = $(this).text();
            if (page == currentPage) {
                $(this).addClass('current');
            }
        });
    }
});
