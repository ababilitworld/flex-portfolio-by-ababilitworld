jQuery(document).ready(function($) {
    $('.stmfs .category-list a').on('click', function(e) {
        e.preventDefault();

        var categoryId = $(this).data('category-id');
        var paged = 1; // Default to page 1

        loadPortfolioByCategory(categoryId, paged);
    });

    // Function to handle pagination click event
    $(document).on('click', '.stmfs .pagination a', function(e) {
        var categoryId = $('.stmfs .category-list a.active').data('category-id');
        
        if (categoryId) {
            e.preventDefault();
            var paged = $(this).attr('href').split('paged=')[1].split('&')[0];
            loadPortfolioByCategory(categoryId, paged);
        }
    });

    function loadPortfolioByCategory(categoryId, paged) {
        $.ajax({
            url: window.xyz_portfolio_localize_script.ajaxUrl,
            type: 'POST',
            data: {
                action: 'load_portfolio_by_category',
                category_id: categoryId,
                paged: paged
            },
            success: function(response) {
                $('.stmfs .portfolio-wrap').html(response);
                $('.stmfs .category-list a').removeClass('active');
                $('.stmfs .category-list a[data-category-id="' + categoryId + '"]').addClass('active');

                // Highlight the current pagination link
                updatePaginationLinks(paged);
            }
        });
    }

    function updatePaginationLinks(currentPage) {
        $('.stmfs .pagination a').removeClass('current');
        $('.stmfs .pagination span').removeClass('current');

        $('.stmfs .pagination a').each(function() {
            var page = $(this).attr('href').split('paged=')[1].split('&')[0];
            if (page == currentPage) {
                $(this).addClass('current');
            }
        });

        $('.stmfs .pagination span').each(function() {
            var page = $(this).text();
            if (page == currentPage) {
                $(this).addClass('current');
            }
        });
    }
});
