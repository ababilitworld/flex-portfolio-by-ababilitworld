<?php
    namespace Ababilitworld\FlexPortfolioByAbabilitworld\Package\Portfolio;

    use Ababilitworld\FlexPortfolioByAbabilitworld\Package\Portfolio\Template\Template;

    use Ababilitworld\{
        FlexTraitByAbabilitworld\Standard\Standard,
        FlexWordpressByAbabilitworld\Package\Pagination\Concrete\Pagination as PaginationService,
        FlexPortfolioByAbabilitworld\Package\Portfolio\Setting\Setting as Setting,
        FlexPortfolioByAbabilitworld\Package\Portfolio\Service\Service as PortfolioService,
        FlexPortfolioByAbabilitworld\Package\Portfolio\Presentation\Template\Template as PortfolioTemplate
    };

    use const Ababilitworld\{
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
            private $pagination_service;
            private $portfolio_service;
            private $portfolio_template;
            private $settings;

            public function __construct()
            {
                $this->init();
            }

            private function init()
            {
                $this->init_hooks();
                $this->init_services();
            }

            private function init_hooks()
            {
                add_action('init', [$this, 'post_type']);
                add_action('wp_loaded', [$this, 'page']);
                add_action('admin_menu', [$this, 'admin_menu']);
                add_shortcode('flex-portfolio-by-ababilitworld-list', [$this, 'render']);
                add_action('wp_ajax_load_portfolio_by_category', [$this, 'load_portfolio_by_category']);
                add_action('wp_ajax_nopriv_load_portfolio_by_category', [$this, 'load_portfolio_by_category']);
                add_filter('use_block_editor_for_post_type', [$this, 'disable_gutenberg'], 10, 2);
            }

            private function init_services()
            {
                $this->pagination_service = PaginationService::instance();
                $this->portfolio_service = PortfolioService::instance();
                $this->portfolio_template = PortfolioTemplate::instance();
                $this->settings = Setting::instance();
            }

            public function post_type() 
            {
                add_theme_support('post-thumbnails', array('fpfolio'));

                $post_menu_icon = "dashicons-admin-post";
                $post_slug = "fpfolio";

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

                register_taxonomy_for_object_type('category', 'fpfolio');
                register_taxonomy_for_object_type('post_tag', 'fpfolio');
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

            private function get_attributes()
            {
                // Default panel as front-end
                $panel = 'front';
                $paged = 1; // Default page number

                // Detect HTTP Method
                $method = $_SERVER['REQUEST_METHOD'];

                if (is_admin()) 
                {
                    $panel = 'admin';

                    // Admin panel pagination based on HTTP method
                    if ($method === 'POST') {
                        $paged = isset($_POST['paged']) ? absint($_POST['paged']) : 1;
                    } else {
                        $paged = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
                    }

                } 
                else 
                {
                    // Front-end pagination handling for archives, shortcodes, and custom loops
                    $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;

                    // Fallback to 'page' query var if 'paged' is not set
                    if ($paged === 1) {
                        $paged = get_query_var('page') ? absint(get_query_var('page')) : 1;
                    }
                }

                // Get current active theme name
                $current_theme = wp_get_theme();
                $current_theme_name = $current_theme->get('Name');

                // Prepare attributes for portfolio list
                return array(
                    'theme_name'     => $current_theme_name,
                    'panel'          => $panel,
                    'post_type'      => 'fpfolio',
                    'posts_per_page' => 2,
                    'paged'          => $paged,
                    'page'           => 'flex-portfolio-by-ababilitworld',
                    'admin_url'      => admin_url('edit.php?post_type=fpfolio&page=flex-portfolio-by-ababilitworld'),
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                );
            }


            public function render()
            {
               $attribute = $this->get_attributes();

                // Debug: Check attributes
                //echo "<pre>"; print_r($attribute); echo "</pre>";

                // Output the portfolio list (assuming this method returns HTML)
                echo $this->portfolio_list($attribute);
            }


            public function portfolio_list($attribute) 
            {
                $query = $this->portfolio_service::wp_query($attribute);

                //echo "<pre>";print_r($query);echo "</pre>";exit;
                
                ob_start();
                ?>
                <?php 
                if(is_admin() ||  $attribute['theme_name'] !== "Flex Theme By Ababilitworld")
                {
                ?>
                    <div class="ababilitworld">
                <?php
                }
                ?>
                    
                    <div class="fpba">
                        <div class="portfolio-template-wrap">
                            <!-- <div class="header">
                                <h3>Our Portfolio</h3>
                            </div> -->
                        <?php
                        if ($query->have_posts()) 
                        {
                            //echo "<pre>"; print_r($query); echo "</pre>";

                            $this->portfolio_template::category_list($query);
                            ?>
                            <div class="portfolio-wrap">
                            <?php
                            $this->portfolio_template::portfolio_default_list($query);
                            $this->pagination_service->init(array('query'=>$query,'attribute'=>$attribute));
                            $this->pagination_service->paginate();
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
                <?php 
                if(is_admin() ||  $attribute['theme_name'] !== "Flex Theme By Ababilitworld")
                {
                ?>
                    </div>
                <?php
                }
                ?>
                <?php
                return ob_get_clean();
            }

            public function category_portfolio_list($attribute) 
            {
                $query = $this->portfolio_service::wp_query($attribute);

                //echo "<pre>";print_r($query);echo "</pre>";            
                
                ob_start();
                ?>
                <?php 
                if(is_admin() ||  $attribute['theme_name'] !== "Flex Theme By Ababilitworld")
                {
                ?>
                    <div class="ababilitworld">
                <?php
                }
                ?>
                
                    
                    <div class="fpba">
                        <div class="portfolio-template-wrap">
                            <!-- <div class="header">
                                <h3>Our Portfolio</h3>
                            </div> -->
                        <?php
                        if ($query->have_posts()) 
                        {
                            //echo "<pre>"; print_r($query); echo "</pre>";

                            //$this->portfolio_template::category_list($query);
                            ?>
                            <div class="portfolio-wrap">
                            <?php
                            $this->portfolio_template::portfolio_default_list($query);
                            $this->pagination_service->init(array('query'=>$query,'attribute'=>$attribute));
                            $this->pagination_service->paginate();
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
                <?php 
                if(is_admin() ||  $attribute['theme_name'] !== "Flex Theme By Ababilitworld")
                {
                ?>
                    </div>
                <?php
                }
                ?>
                <?php
                return ob_get_clean();
            }

            public function load_portfolio_by_category()
            {
                
                $attribute = $this->get_attributes();

                $category_id = intval($_POST['category_id']);

                if(is_int($category_id))
                {
                    $attribute['category_id']= $category_id;
                }
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
    }