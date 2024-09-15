<?php
    namespace Ababilitworld\FlexPortfolioByAbabilitworld\Package\Portfolio\Service;

    (defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

	use Ababilitworld\{
		FlexTraitByAbabilitworld\Standard\Standard
	};
    
	if ( ! class_exists( __NAMESPACE__.'\Service' ) ) 
	{
		/**
		 * Class Service
		 *
		 * @package Ababilitworld\FlexPortfolioByAbabilitworld\Service
		 */
		class Service
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
	}