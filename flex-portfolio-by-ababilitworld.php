<?php

	/**
	 * Flex Portfolio By Ababil IT World
	 *
	 * @package ababilitworld/flex-portfolio-by-ababilitworld
	 *
	 * @wordpress-plugin
	 * Plugin Name:       Flex Portfolio By Ababil IT World
	 * Plugin URI:        https://ababilitworld.com/wp-plugin/flex-portfolio-by-ababilitworld
	 * Description:       Flexible Portfolio Package For WordPress, Exclusively by Ababil IT World.
	 * Version:           1.0.0
	 * Requires at least: 5.2
	 * Requires PHP:      7.4
	 * WC requires at least: 3.0.9
	 * WC tested up to:   6.5
	 * Requires Plugins:  woocommerce/woocommerce.php
	 * Author:            Ababil IT World
	 * Author URI:        https://ababilitworld.com/
	 * Author Email:      ababilitworld@gmail.com
	 * License:           GPL v3 or later
	 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
	 * Text Domain:       flex-portfolio-by-ababilitworld
	 * Domain Path:       /language
	 *
	 * Contributors:
	 *  - Ababil IT World (ababilitworld@gmail.com, https://ababilitworld.com/)
	 *  - Md Shafiul Alam (cse.shafiul@gmail.com, https://ababilitworld.com/)
	 */


	/**
	 * Bootstrap the plugin.
	 */
	namespace Ababilitworld\FlexPortfolioByAbabilitworld;

	(defined('ABSPATH') && defined('WPINC')) || die();

	require_once __DIR__ . '/bootstrap.php';

	use Ababilitworld\{
		FlexPortfolioByAbabilitworld\Package\Package,
	};
	
	$package = Package::instance(); 
		
	register_activation_hook(__FILE__, [$package, 'activate']);
	register_deactivation_hook(__FILE__, [$package, 'deactivate']);

	