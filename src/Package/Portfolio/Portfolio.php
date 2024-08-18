<?php
namespace Ababilitworld\FlexPortfolioByAbabilitworld\Package\Portfolio;

use Ababilitworld\FlexPortfolioByAbabilitworld\Package\Portfolio\Template\Template;
use Ababilitworld\FlexTraitByAbabilitworld\Standard\Standard;
use function AbabilItWorld\{
    FlexPaginationByAbabilitworld\Package\Service\service as pagination_helper,
    FlexPortfolioByAbabilitworld\Package\Portfolio\Helper\helper as portfolio_helper,
    FlexPortfolioByAbabilitworld\Package\Portfolio\Setting\setting as setting,
    FlexPortfolioByAbabilitworld\Package\Portfolio\Presentation\Template\template as portfolio_template,
    FlexPaginationByAbabilitworld\Package\Service\service as pagination
};

use const AbabilItWorld\{
    FlexPortfolioByAbabilitworld\PLUGIN_NAME,
    FlexPortfolioByAbabilitworld\PLUGIN_DIR,
    FlexPortfolioByAbabilitworld\PLUGIN_URL,
    FlexPortfolioByAbabilitworld\PLUGIN_FILE,
    FlexPortfolioByAbabilitworld\PLUGIN_PRE_UNDS,
    FlexPortfolioByAbabilitworld\PLUGIN_PRE_HYPH,
    FlexPortfolioByAbabilitworld\PLUGIN_VERSION
};

(defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

if (!class_exists(__NAMESPACE__.'\Portfolio')) 
{
    class Portfolio 
    {
        use Standard;
        private $pagination_helper;
        private $portfolio_helper;
        private $portfolio_template;
        private $settings;

        public function __construct() 
        {
            $this->init();   
            
        }

        private function init() 
        {
            add_action('init', array($this, 'post_type'));
            add_action('wp_loaded', array($this, 'page'));
            add_action('admin_menu', array($this, 'admin_menu'));
            add_shortcode('flex-portfolio-by-ababilitworld-list' , array($this,'render') );
            add_action('wp_ajax_load_portfolio_by_category', array($this,'load_portfolio_by_category'));
            add_action('wp_ajax_nopriv_load_portfolio_by_category', array($this,'load_portfolio_by_category'));
            add_filter('use_block_editor_for_post_type', array($this, 'disable_gutenberg'), 10, 2);
            $this->pagination_helper = pagination_helper();
            $this->portfolio_helper = portfolio_helper();
            $this->portfolio_template = portfolio_template();
            $this->settings = setting(); 
            add_theme_support('post-thumbnails');           
        }

        public function post_type() 
        {
            $post_menu_icon = "dashicons-admin-post";
            $post_slug = "";

            $labels = [
                'name' => esc_html__('Portfolios', 'xyz-portfolio'),
                'singular_name' => esc_html__('Portfolio', 'xyz-portfolio'),
                'menu_name' => esc_html__('Portfolios', 'xyz-portfolio'),
                'name_admin_bar' => esc_html__('Portfolios', 'xyz-portfolio'),
                'archives' => esc_html__('Portfolio List', 'xyz-portfolio'),
                'attributes' => esc_html__('Portfolio List', 'xyz-portfolio'),
                'parent_item_colon' => esc_html__('Portfolio Item : ', 'xyz-portfolio'),
                'all_items' => esc_html__('All Portfolio', 'xyz-portfolio'),
                'add_new_item' => esc_html__('Add new Portfolio', 'xyz-portfolio'),
                'add_new' => esc_html__('Add new Portfolio', 'xyz-portfolio'),
                'new_item' => esc_html__('New Portfolio', 'xyz-portfolio'),
                'edit_item' => esc_html__('Edit Portfolio', 'xyz-portfolio'),
                'update_item' => esc_html__('Update Portfolio', 'xyz-portfolio'),
                'view_item' => esc_html__('View Portfolio', 'xyz-portfolio'),
                'view_items' => esc_html__('View Portfolios', 'xyz-portfolio'),
                'search_items' => esc_html__('Search Portfolios', 'xyz-portfolio'),
                'not_found' => esc_html__('Portfolio Not found', 'xyz-portfolio'),
                'not_found_in_trash' => esc_html__('Portfolio Not found in Trash', 'xyz-portfolio'),
                'featured_image' => esc_html__('Portfolio Feature Image', 'xyz-portfolio'),
                'set_featured_image' => esc_html__('Set Portfolio Feature Image', 'xyz-portfolio'),
                'remove_featured_image' => esc_html__('Remove Feature Image', 'xyz-portfolio'),
                'use_featured_image' => esc_html__('Use as Portfolio featured image', 'xyz-portfolio'),
                'insert_into_item' => esc_html__('Insert into Portfolio', 'xyz-portfolio'),
                'uploaded_to_this_item' => esc_html__('Uploaded to this ', 'xyz-portfolio'),
                'items_list' => esc_html__('Portfolio list', 'xyz-portfolio'),
                'items_list_navigation' => esc_html__('Portfolio list navigation', 'xyz-portfolio'),
                'filter_items_list' => esc_html__('Filter Portfolio List', 'xyz-portfolio')
            ];

            $args = array(
                'public' => true,
                'labels' => $labels,
                'menu_icon' => $post_menu_icon,
                'rewrite' => array('slug' => $post_slug),
                'supports' => array('title', 'thumbnail', 'editor'), // 'thumbnail' ensures featured image support
                'taxonomies' => array('category', 'post_tag'),
            );

            register_post_type('fpfolio', $args);
        }

        public function page()
        {
            $portfolio_page = get_page_by_path(PLUGIN_PRE_HYPH.'-list');

            if (!$portfolio_page) 
            {
                $portfolio_page_args = array(
                    'post_type' => 'page',
                    'post_name' => PLUGIN_PRE_HYPH.'-list',
                    'post_title' => 'Portfolio List',
                    'post_content' => '[flex-portfolio-by-ababilitworld-list]',
                    'post_status' => 'publish',
                );

                wp_reset_postdata();
                wp_insert_post($portfolio_page_args);
                wp_reset_postdata();
            }
        }

        public function admin_menu()
        {
            add_submenu_page(
                'edit.php?post_type=fpfolio', 
                esc_html__('Portfolio List', 'flex-portfolio-by-ababilitworld'), 
                esc_html__('Portfolio List', 'flex-portfolio-by-ababilitworld'),
                'manage_options',
                'flex-portfolio-by-ababilitworld',
                array($this, 'render'),
                98
            );
        }

        public function render()
        {
            if  (is_admin()) 
            {
                $paged = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
            }
            else 
            {
                $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            }

            $attribute = array(
                'post_type'      => 'fpfolio',
                'posts_per_page' => 1,
                'paged'          => $paged,
                'page'           => 'flex-portfolio-by-ababilitworld',
                'admin_url'      => admin_url('edit.php?post_type=fpfolio&page=flex-portfolio-by-ababilitworld'),
                'orderby'        => 'date',
                'order'          => 'DESC',
            );
            //echo "<pre>";print_r($attribute);echo "</pre>";
            echo $this->portfolio_list($attribute);
        }

        public function portfolio_list($attribute) 
        {
            $query = $this->portfolio_helper::wp_query($attribute);

            //echo "<pre>";print_r($query);echo "</pre>";//exit;
            
            ob_start();
            ?>
            <div class="ababilitworld">
                <div  class="fpba">

                
            <div class="stmfs">
                <div class="portfolio-template-wrap">
                    <div class="header">
                        <h3>Our Portfolio</h3>
                    </div>
            <?php
            if ($query->have_posts()) 
            {
                //echo "<pre>"; print_r($query); echo "</pre>";

                $this->portfolio_template::category_list($query);
                ?>
                <div class="portfolio-wrap">
                <?php
                $this->portfolio_template::portfolio_default_list($query);
                $this->pagination_helper->init(array('query'=>$query,'attribute'=>$attribute));
                $this->pagination_helper->paginate();
                ?>
                </div>
                <?php
                $this->portfolio_template::lightbox();
                wp_reset_postdata();
            }
            else
            {
                ?>
                <div>No portfolios found</div>
                <?php
            }
            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            return ob_get_clean();
        }

        public function category_portfolio_list($attribute) 
        {
            $query = $this->portfolio_helper::wp_query($attribute);
            
            ob_start();
            if ($query->have_posts()) 
            {                
                $this->portfolio_template::portfolio_default_list($query);
                $this->portfolio_template::render($query,$attribute);
                wp_reset_postdata();
            }
            else
            {
                ?>
                <div>No portfolios found</div>
                <?php
            }
            ?>
            </div>
            <?php
            return ob_get_clean();
        }

        public function load_portfolio_by_category()
        {
            $category_id = intval($_POST['category_id']);
            $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

            $attribute = array(
                'post_type'      => 'flex-portfolio-by-ababilitworld',
                'posts_per_page' => 1,
                'paged'          => $paged,
                'page'           => 'flex-portfolio-by-ababilitworld-list',
                'orderby'        => 'date',
                'order'          => 'DESC',
                'category_id'       => $category_id,
            );
            //echo "<pre>";print_r($attribute);echo "</pre>";
            echo $this->category_portfolio_list($attribute);
            exit;
        }

        public function disable_gutenberg($current_status, $post_type)
        {
            if ($post_type === 'fpfolio') 
            {
                return false;
            }
            return $current_status;
        }
    }
    
    /**
     * Return the instance
     *
     * @return \AbabilItWorld\FlexPortfolioByAbabilitworld\Package\Portfolio\Portfolio
     */
    function portfolio() 
    {
        return \AbabilItWorld\FlexPortfolioByAbabilitworld\Package\Portfolio\Portfolio::instance();
    }
}
?>
