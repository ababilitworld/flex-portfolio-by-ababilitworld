<?php
namespace Ababilitworld\FlexPortfolioByAbabilitworld\Package\Portfolio\Presentation\Template;

(defined('ABSPATH') && defined('WPINC')) || die();

use Ababilitworld\FlexTraitByAbabilitworld\Standard\Standard;
use function Ababilitworld\{
    FlexPackageInfoByAbabilitworld\Package\Service\service as plugin_info,
    FlexPortfolioByAbabilitworld\Package\package as package,
};
use const AbabilItWorld\FlexPortfolioByAbabilitworld\{
    PLUGIN_NAME,
    PLUGIN_DIR,
    PLUGIN_URL,
    PLUGIN_FILE,
    PLUGIN_PRE_UNDS,
    PLUGIN_PRE_HYPH,
    PLUGIN_VERSION
};

if (!class_exists('\Ababilitworld\FlexPortfolioByAbabilitworld\Package\Portfolio\Presentation\Template\Template')) 
{
    class Template 
    {
        use Standard;

        private $package;
        private $template_url;

        public function __construct() 
        {
            add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts' ) );
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts' ) );
        }

        public function enqueue_scripts()
        {
            wp_enqueue_script('jquery');

            wp_enqueue_style(
                PLUGIN_PRE_HYPH . '-template-style', 
                PLUGIN_URL . '/src/Package/Portfolio/Presentation/Template/Asset/css/style.css',
                array(), 
                time()
            );

            wp_enqueue_script(
                PLUGIN_PRE_HYPH . '-template-script', 
                PLUGIN_URL . '/src/Package/Portfolio/Presentation/Template/Asset/js/script.js',
                array(), 
                time(), 
                true
            );
        
            wp_enqueue_style(
                PLUGIN_PRE_HYPH . '-portfolio-template-style', 
                PLUGIN_URL.'/src/Package/Portfolio/Presentation/Template/Asset/css/portfolio-template.css', 
                array(), 
                time()
            );

            wp_enqueue_style(
                PLUGIN_PRE_HYPH . '-modal-style', 
                PLUGIN_URL.'/src/Package/Portfolio/Presentation/Template/Asset/css/modal.css', 
                array(), 
                time()
            );
            wp_enqueue_script(
                PLUGIN_PRE_HYPH . '-modal-script', 
                PLUGIN_URL.'/src/Package/Portfolio/Presentation/Template/Asset/js/modal.js', 
                array(), 
                time(), 
                true
            );

            wp_enqueue_style(
                PLUGIN_PRE_HYPH . '-lightbox-style', 
                PLUGIN_URL.'/src/Package/Portfolio/Presentation/Template/Asset/css/lightbox.css', 
                array(), 
                time()
            );
            wp_enqueue_script(
                PLUGIN_PRE_HYPH . '-lightbox-script', 
                PLUGIN_URL.'/src/Package/Portfolio/Presentation/Template/Asset/js/lightbox.js', 
                array(), 
                time(),
                true
            );

            wp_enqueue_style(
                PLUGIN_PRE_HYPH . '-category-style', 
                PLUGIN_URL.'/src/Package/Portfolio/Presentation/Template/Asset/css/category.css', 
                array(), 
                time()
            );
            wp_enqueue_script(
                PLUGIN_PRE_HYPH . '-category-script', 
                PLUGIN_URL.'/src/Package/Portfolio/Presentation/Template/Asset/js/category.js', 
                array('jquery'), 
                time(), 
                true
            );
            
            wp_localize_script(
                PLUGIN_PRE_HYPH . '-template-script', 
                PLUGIN_PRE_UNDS . '_template_localize', 
                array(
                    'adminAjaxUrl' => admin_url('admin-ajax.php'),
                    'ajaxUrl' => admin_url('admin-ajax.php'),
                    'ajaxNonce' => wp_create_nonce(PLUGIN_PRE_UNDS . '_nonce'),
                    // 'ajaxAction' => PLUGIN_PRE_UNDS . '_action',
                    // 'ajaxData' => PLUGIN_PRE_UNDS . '_data',
                    // 'ajaxError' => PLUGIN_PRE_UNDS . '_error',
                )
            );
        }

        public static function render_pagination(array $paginationData) 
        {
            ?>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php            
                        for ($i = 1; $i <= $paginationData['total_pages']; $i++) 
                        {
                    ?>
                    <li class="page-item <?php echo ($i == $paginationData['current_page'] ? 'active' : '') ?> " >
                    <a class="page-link" href="?page=<?php echo esc_attr($i) ?>"><?php echo esc_html($i); ?></a>
                    </li>
                    <?php
                        }
                    ?>
                
                </ul>
            </nav>
            <?php
        }

        public function default_pagination_template(array $data) 
        {
            if ($data['pagination_links'])
            {
            ?>
            
                <div class="pagination" data-current-page="<?php echo esc_attr($data['paged']); ?>"><?php join("\n", $data['pagination_links']); ?></div>
                
            <?php
            }
        }

        public static function category_list($query)
        {            
            ?>
                <div class="category-container">
                <?php
                    $categories = get_categories(array(
                        'taxonomy' => 'category',
                        'post_type' => 'fpfolio',
                        'hide_empty' => true,
                    ));

                    if (!empty($categories)) {
                        echo '<ul class="category-list">';
                        foreach ($categories as $category) {
                            echo '<li><a href="#" data-category-id="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</a></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<p>No categories found.</p>';
                    }
                ?>
                </div>
            <?php
        }

        public static function portfolio_default_list($query) 
        {
            ?>
                <div class="portfolio-container">
                    <?php
                    if($query->found_posts > 0) 
                    {
                        while ($query->have_posts()) 
                        {
                            $query->the_post();
                            $portfolio_id = get_the_ID();
                            $title = get_the_title();
                            $images = get_post_meta($portfolio_id, 'portfolio_images', true);
                            $image_urls = array();
                            if(is_array($images) && count($images))
                            {
                                foreach($images as $key => $image)
                                {
                                    $image_urls[] = wp_get_attachment_image_url($image, 'full');
                                }
                            }
                            ?>
                            <div class="portfolio-card" data-id="<?php echo $portfolio_id; ?>" data-title="<?php echo esc_attr($title); ?>" data-images="<?php echo esc_attr(json_encode($image_urls)); ?>">
                                <div class="header"></div>
                                <div class="content">
                                    <?php if (has_post_thumbnail($portfolio_id)): ?>
                                        <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($portfolio_id), 'single-post-thumbnail'); ?>
                                        <div>
                                            <img src="<?php echo $image[0] ?>" />
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="footer">
                                    <span><b><?php echo $title; ?></b></span>
                                </div>
                            </div>
                            <?php
                            wp_reset_postdata();
                        }                        
                    }
                    ?>
                </div>                
                        
            <?php
        }

        public static function lightbox()
        {
            ?>
                <div class="lightbox">
                    <div id="myModal" class="modal">
                        <div class="modal-content">
                            <span class="close cursor">&times;</span>
                            <div class="slider-row">
                                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                                <div class="slideshow"></div>
                                <a class="next" onclick="plusSlides(1)">&#10095;</a>
                            </div>
                            <div class="image-name-row">
                                <div class="image-name">
                                    <p id="caption"></p>
                                </div>
                            </div>
                            <div class="thumbnail-row"></div>
                        </div>
                    </div>
                </div>            
            <?php
        }
    }

    /**
     * Return the instance
     *
     * @return \Ababilitworld\FlexPortfolioByAbabilitworld\Package\Portfolio\Presentation\Template\Template
     */
    function template() 
    {
        return \Ababilitworld\FlexPortfolioByAbabilitworld\Package\Portfolio\Presentation\Template\Template::instance();
    }
}

?>