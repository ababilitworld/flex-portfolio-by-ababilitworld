<?php
namespace Ababilitworld\FlexPortfolioByAbabilitworld;

class Bootstrap
{
    private $prefix;
    private $base_dir;

    public function __construct() 
    {
        add_action('admin_menu', array($this, 'admin_menu'));
    }

    public function admin_menu() 
    {
        global $menu;
        
        $menu_slug = 'loaded-classes';

        $menu_exists = false;
        foreach ($menu as $item) 
        {
            if (isset($item[2]) && $item[2] === $menu_slug) 
            {
                $menu_exists = true;
                break;
            }
        }

        if (!$menu_exists) 
        {
            add_menu_page(
                'Loaded Classes',
                'Loaded Classes',
                'manage_options',
                $menu_slug,
                array($this, 'display_loaded_classes'),
                'dashicons-admin-generic',
                9
            );
        }
    }

    public function display_loaded_classes() 
    {
        $classes = get_declared_classes();

        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('Loaded Classes', 'textdomain') . '</h1>';
        
        foreach ($classes as $class) 
        {
            if( strpos($class,"Flex") !== false)
            {

                echo esc_html($class) . "<br>";
            }
        }
        echo '</div>';
    }
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) 
{
    require __DIR__ . '/vendor/autoload.php';
}

new Bootstrap();

