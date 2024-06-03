<?php
namespace Ababilitworld\FlexPortfolioByAbabilitworld\Package\Presentation\Template;

(defined('ABSPATH') && defined('WPINC')) || die();

use Ababilitworld\FlexTraitByAbabilitworld\Trait\StaticTrait\StaticTrait;
use function Ababilitworld\{
    FlexPackageInfoByAbabilitworld\Package\Service\service as plugin_info,
    FlexPortfolioByAbabilitworld\Package\package as package,
};

if (!class_exists('\Ababilitworld\FlexPortfolioByAbabilitworld\Package\Presentation\Template\Template')) 
{
    class Template 
    {
        use StaticTrait;

        private $package;
        private $template_url;

        public function __construct() 
        {
            $this->package = package();
            add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts' ) );
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts' ) );
        }

        public function enqueue_scripts()
        {
            wp_enqueue_style(
                $this->package::$package_pre_hyph . '-template-style', 
                $this->package::$package_url . '/Presentation/Template/Asset/css/style.css',
                array(), 
                time()
            );

            wp_enqueue_script(
                $this->package::$package_pre_hyph . '-template-script', 
                $this->package::$package_url . '/Presentation/Template/Asset/js/script.js',
                array(), 
                time(), 
                true
            );
            
            wp_localize_script(
                $this->package::$package_pre_hyph . '-template-script', 
                $this->package::$package_pre_unds . '_template_localize', 
                array(
                    'adminAjaxUrl' => admin_url('admin-ajax.php'),
                    'ajaxUrl' => admin_url('admin-ajax.php'),
                    'ajaxNonce' => wp_create_nonce($this->package::$package_pre_unds . '_nonce'),
                    'ajaxAction' => $this->package::$package_pre_unds . '_action',
                    'ajaxData' => $this->package::$package_pre_unds . '_data',
                    'ajaxError' => $this->package::$package_pre_unds . '_error',
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
    }

    //new Template();
	
    /**
     * Return the instance
     *
     * @return \Ababilitworld\FlexPaginationByAbabilitworld\Package\Presentation\Template\Template
     */
    function template() 
    {
        return Template::instance();
    }

    // take off
    //template();
}

?>