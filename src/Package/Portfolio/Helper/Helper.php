<?php
    namespace Ababilitworld\FlexPortfolioByAbabilitworld\Package\Portfolio\Helper;

    (defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

	use Ababilitworld\FlexTraitByAbabilitworld\Standard\Standard;
	use const AbabilItWorld\FlexPortfolioByAbabilitworld\{
		PLUGIN_NAME,
		PLUGIN_DIR,
        PLUGIN_URL,
		PLUGIN_FILE,
		PLUGIN_VERSION
	};
    use function Ababilitworld\{
        FlexPortfolioByAbabilitworld\Package\Portfolio\Presentation\Pagination\Template\template as pagination_template,
    };

	if ( ! class_exists( '\AbabilItWorld\FlexPortfolioByAbabilitworld\Package\Portfolio\Helper\Helper' ) ) 
	{
		/**
		 * Class Helper
		 *
		 * @package AbabilItWorld\FlexPortfolioByAbabilitworld\Helper
		 */
		class Helper
		{
			use Standard;
	
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
                
            }

            public static function wp_query($attribute) 
            {
                $args = array(
                    'post_type'      => $attribute['post_type'],
                    'posts_per_page' => $attribute['posts_per_page'],
                    'paged'          => $attribute['paged'],
                    'orderby'        => $attribute['orderby'],
                    'order'          => $attribute['order'],
                );

				if (isset($attribute['category']) && !empty($attribute['category'])) 
				{
					$args['category_name'] = $attribute['category'];
				}

				if (isset($attribute['category_id']) && !empty($attribute['category_id']) && is_int($attribute['category_id'])) 
				{
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'category',
							'field' => 'term_id',
							'terms' => $attribute['category_id'],
						),
					);
				}

				//echo "<pre>";print_r(array('args'=>$args,'attr'=>$attribute));echo "</pre>";

                return new \WP_Query($args);
			}
        }
	
		/**
		 * Return the instance
		 *
		 * @return \AbabilItWorld\FlexPortfolioByAbabilitworld\Package\Portfolio\Helper\Helper
		 */
		function helper() 
		{
			return Helper::instance();
		}
	}
	
?>