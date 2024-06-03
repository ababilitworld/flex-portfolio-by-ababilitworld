<?php
    namespace Ababilitworld\FlexPortfolioByAbabilitworld\Package\Portfolio\Helper;

    (defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

	use Ababilitworld\FlexTraitByAbabilitworld\Trait\StaticTrait\StaticTrait;
    use Ababilitworld\FlexPaginationByAbabilitworld\Package\Abstract\Pagination;
	use const AbabilItWorld\FlexPortfolioByAbabilitworld\{
		PLUGIN_NAME,
		PLUGIN_DIR,
        PLUGIN_URL,
		PLUGIN_VERSION
	};
    use function Ababilitworld\{
        FlexPortfolioByAbabilitworld\Package\Portfolio\Presentation\Template\template as pagination_template,
    };

	if ( ! class_exists( '\AbabilItWorld\FlexPortfolioByAbabilitworld\Package\Portfolio\Helper\Helper' ) ) 
	{
		/**
		 * Class Helper
		 *
		 * @package AbabilItWorld\FlexPortfolioByAbabilitworld\Helper
		 */
		class Helper extends Pagination
		{
			use StaticTrait;

			/**
			 * Objcet wp_error
			 *
			 * @var object
			 */
			private $wp_error;

			/**
			 * Objcet wp_function
			 *
			 * @var object
			 */
			private $wp_function;
	
			/**
			 * Helper version
			 *
			 * @var string
			 */
			public $version = '1.0.0';
	
			/**
             * Constructor.
             */
            public function __construct()
            {
                // Constructor can initialize other settings if needed
            }

            /**
             * Initialize the service with query and attributes.
             *
             * @param array $data Initialization data including 'query' and 'attribute'.
             */
            public function init($data)
            {
                $this->query = $data['query'];
                $this->attribute = $data['attribute'];
                $this->paginate();
                $this->paginationLinks = $this->pagination_links();
            }

            /**
             * Paginate the query results.
             */
            public function paginate()
            {
                $this->currentPage = max(1, intval($this->query->get('paged', 1)));
                $this->totalPages = intval($this->query->max_num_pages);
                $this->query->set('paged', $this->currentPage);
            }

            /**
             * Generate pagination links.
             *
             * @return array Pagination links.
             */
            public function pagination_links()
            {
                $big = 999999999;
                $base = str_replace($big, '%#%', esc_url(get_pagenum_link($big)));

                if (is_admin()) {
                    $base = add_query_arg(
                        array(
                            'paged' => '%#%',
                        ),
                        admin_url('admin.php')
                    );
                }

                return paginate_links(
                    array(
                        'base' => $base,
                        'format' => '?paged=%#%',
                        'current' => $this->currentPage,
                        'total' => $this->totalPages,
                        'prev_text' => __('« Previous'),
                        'next_text' => __('Next »'),
                        'type' => 'array',
                    )
                );
            }

            /**
             * Render the pagination.
             */
            public function render()
            {
                $paginationTemplate = pagination_template();
                $paginationTemplate->default_pagination_template(
                    array(
                        'paged' => $this->currentPage,
                        'pagination_links' => $this->paginationLinks,
                    )
                );
            }
	
		}

        //new Helper();
	
		/**
		 * Return the instance
		 *
		 * @return \AbabilItWorld\FlexPortfolioByAbabilitworld\Package\Portfolio\Helper\Helper
		 */
		function helper() 
		{
			return Helper::instance();
		}
	
		// take off
		//helper();
	}
	
?>