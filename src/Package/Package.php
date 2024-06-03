<?php
    namespace Ababilitworld\FlexPortfolioByAbabilitworld\Package;

    (defined( 'ABSPATH' ) && defined( 'WPINC' )) || exit();

	use Ababilitworld\FlexTraitByAbabilitworld\Trait\StaticTrait\StaticTrait;

	use const AbabilItWorld\FlexPortfolioByAbabilitworld\{
		PLUGIN_NAME,
		PLUGIN_DIR,
        PLUGIN_URL,
		PLUGIN_VERSION
	};

	if ( ! class_exists( '\AbabilItWorld\FlexPortfolioByAbabilitworld\Package\Package' ) ) 
	{
		/**
		 * Class Package
		 *
		 * @package AbabilItWorld\FlexPortfolioByAbabilitworld\Package
		 */
		class Package 
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
			 * Package version
			 *
			 * @var string
			 */
			public $version = '1.0.0';
	
			/**
			 * Constructor
			 */
			public function __construct() 
			{
				register_uninstall_hook(PLUGIN_FILE, array('self', 'uninstall'));                
			}
	
			/**
			 * Run the isntaller
			 * 
			 * @return void
			 */
			public static function run() 
			{
				$installed = get_option( PLUGIN_NAME.'-installed' );
	
				if ( ! $installed ) 
				{
					update_option( PLUGIN_NAME.'-installed', time() );
				}
	
				update_option( PLUGIN_NAME.'-version', PLUGIN_VERSION );
			}
	
			/**
			 * Activate The class
			 *
			 * @return void
			 */
			public static function activate(): void 
			{
				flush_rewrite_rules();
                self::run();
			}
	
			/**
			 * Dectivate The class
			 *
			 * @return void
			 */
			public static function deactivate(): void 
			{
				flush_rewrite_rules();
			}
	
			/**
			 * Uninstall the plugin
			 *
			 * @return void
			 */
			public static function uninstall(): void 
			{
				delete_option(PLUGIN_NAME . '-installed');
				delete_option(PLUGIN_NAME . '-version');
				flush_rewrite_rules();
			}
	
		}

		/**
		 * Return the instance
		 *
		 * @return \AbabilItWorld\FlexPortfolioByAbabilitworld\Package\Package
		 */
		function package() 
		{
			return Package::instance();
		}

	}
	
?>