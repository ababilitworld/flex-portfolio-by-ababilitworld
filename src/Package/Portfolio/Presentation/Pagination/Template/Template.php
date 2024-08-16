<?php
namespace Ababilitworld\FlexPortfolioByAbabilitworld\Package\Portfolio\Presentation\Pagination\Template;

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

if (!class_exists('\Ababilitworld\FlexPortfolioByAbabilitworld\Package\Portfolio\Presentation\Pagination\Template\Template')) 
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
            wp_enqueue_style(
                PLUGIN_PRE_HYPH . '-template-style', 
                PLUGIN_URL . '/src/Package/Portfolio/Presentation/Pagination/Template/Asset/css/pagination.css',
                array(), 
                time()
            );

            wp_enqueue_script(
                PLUGIN_PRE_HYPH . '-template-script', 
                PLUGIN_URL . '/src/Package/Portfolio/Presentation/Pagination/Template/Asset/js/pagination.js',
                array(), 
                time(), 
                true
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

        public static function default_pagination_template(array $data) 
        {
            if (array_key_exists('pagination_links',$data) && is_array($data['pagination_links']))
            {
                //echo "<Pre>";print_r($data);echo "</pre>";exit;
                $pagination_links = join("\n", $data['pagination_links']);
            ?>
            
                <div class="pagination" data-current-page="<?php echo esc_attr($data['paged']); ?>"><?php echo $pagination_links; ?></div>
                
            <?php
            }
        }
    }

    /**
     * Return the instance
     *
     * @return \Ababilitworld\FlexPortfolioByAbabilitworld\Package\Presentation\Pagination\Template\Template
     */
    function template() 
    {
        return Template::instance();
    }
}

?>