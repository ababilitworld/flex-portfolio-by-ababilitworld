(function ($, portfolioTabItems, portfolioTabContents) {
    "use strict";

    portfolioTabItems.forEach((tabItem) => {
        tabItem.addEventListener('click', () => {
        
            portfolioTabItems.forEach((item) => {
                item.classList.remove('active');
            });
            portfolioTabContents.forEach((content) => {
                content.classList.remove('active');
            });
        
            const target = tabItem.getAttribute('data-tabs-target');
            tabItem.classList.add('active');
            document.querySelector(target).classList.add('active');
            
            window.location.hash = target;
        });
    });
    
    const currentHash = window.location.hash;
    
    if(currentHash.length > 0)
    {
        portfolioTabItems.forEach((tabItem) => {
            tabItem.classList.remove('active');
        });
    
        portfolioTabContents.forEach((tabContent) => {
            tabContent.classList.remove('active');
        });
    
        portfolioTabItems.forEach((tabItem) => {
            const target = tabItem.getAttribute('data-tabs-target');
            if (target === currentHash) {
                tabItem.classList.add('active');
            }
        });
    
        portfolioTabContents.forEach((tabContent) => {
            if (tabContent.getAttribute('id') === currentHash.substring(1)) {
                tabContent.classList.add('active');
            }
        });
    }
    
    function xyz_portfolio_loader(target) {
        target.addClass('fpba loader loader-spinner');
    }
    
    function xyz_portfolio_loader_remove(target) {
        target.each(function () {
            target.removeClass('fpba loader loader-spinner');
        })
    }
    
    function handleEditButtonClick(portfolioId) {
        // Populate modal with portfolio data for editing
        // Fetch portfolio data using AJAX request and populate form fields
    
        // Show modal
        $('#portfolio-modal').show();
    }
    
    $(document).ready(function () {
    
        $('.fpba #portfolio-form').submit(function(event) {
            event.preventDefault();
    
            var formData = {
                title: $('#portfolio-title').val(),
                content: $('#portfolio-content').val(),
                duration: $('#portfolio-duration').val(),
                status: $('#portfolio-status').val(),
                portfolio_id: $('#portfolio-id').val()
            };
    
            $.ajax({
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                method: 'POST',
                data: {
                    action: 'handle_portfolio_form',
                    formData: formData
                },
                success: function(response) 
                {
                    console.log(response);                    
                    $('#portfolio-modal').hide();
                },
                error: function(error) {
                    console.error(error);
                }
            });
        });
    
        $('.fpba #portfolio-form .close').click(function() {
            $('#portfolio-modal').hide();
        });
    
        $(document).on('click', '.fpba-install-activate', function (e) {
            e.preventDefault();
    
            let install_action = $(this).data('install-action');
            let target = $(this).closest('.install');
    
            if (install_action) {
                $.ajax({
                    type: 'POST',
                    url: xyz_portfolio_ajax_url,
                    data: {
                        "action": 'xyz_portfolio_install_and_activate_woocommerce',
                        "install_action":install_action
                    },
                    beforeSend: function () {
                        $('.fpba .loader-container').show();
                    },
                    success: function (data) {
                        let jsonStartIndex = data.indexOf('{"status":');
                        let jsonString = data.substring(jsonStartIndex);
                        let parsed_data = JSON.parse(jsonString);
                        $('.fpba .loader-container').hide();
                        target.html(parsed_data.message);
                        if (parsed_data.status == 'success') {
                            window.location.replace(xyz_portfolio_site_url);
                        }
                    },
                });
            }
        });
    
    });

})(jQuery, document.querySelectorAll('.fpba .tab-item'), document.querySelectorAll('.fpba .tab-content'));
