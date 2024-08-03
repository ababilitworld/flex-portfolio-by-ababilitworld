jQuery(document).ready(function($) {
    let adminAjaxUrl = window.xyz_portfolio_localize_script.adminAjaxUrl;

    // Pagination click event handler
    // $(document).on('click', '.pagination-links a', function(e) {
    //     e.preventDefault();
    //     var page = $(this).attr('href').split('page/')[1];

    //     $.ajax({
    //         url: adminAjaxUrl,
    //         type: 'post',
    //         data: {
    //             action: 'load_portfolio_page',
    //             paged: page,
    //         },
    //         success: function(response) {
    //             $('.portfolio-container').html(response);
    //         },
    //         error: function(xhr, status, error) {
    //             console.log(xhr.responseText);
    //         }
    //     });
    // });
});
