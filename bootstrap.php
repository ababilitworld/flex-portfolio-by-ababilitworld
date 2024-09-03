<?php

namespace Ababilitworld\FlexFoundationByAbabilitworld\Bootstrap;

class BootstrapFlexPortfolioByAbabilitworld 
{
    private $prefix;
    private $base_dir;

    public function __construct() 
    {
        //add_action('admin_menu', array($this, 'register_loaded_classes_menu'));
    }

    public function init($prefix, $base_dir) 
    {
        $this->prefix = $prefix;
        $this->base_dir = rtrim($base_dir, '/') . '/';
    }

    public function load_class($class_name) 
    {
        $len = strlen($this->prefix);
        if (strncmp($this->prefix, $class_name, $len) !== 0) 
        {
            return;
        }

        $relative_class = substr($class_name, $len);
        $file = $this->base_dir . str_replace('\\', '/', $relative_class) . '.php';

        if (file_exists($file)) 
        {
            require $file;
        }
    }

    public function include_files_in_directory($directory) 
    {
        $directory = rtrim($directory, '/') . '/';
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) 
        {
            if ($file->isDir()) 
            {
                continue;
            }

            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') 
            {
                $relative_path = str_replace([$this->base_dir, '.php'], '', $file->getPathname());
                $namespace = str_replace('/', '\\', $relative_path);
                require_once $file;
            }
        }
    }

    public function register($method = 'spl') 
    {
        switch ($method) 
        {
            case 'spl':
                spl_autoload_register([$this, 'load_class']);
                break;
            case 'prepend':
                spl_autoload_register([$this, 'load_class'], true, true);
                break;
            case 'custom':
                spl_autoload_register([$this, 'load_class'], true, true);
                break;
            default:
                throw new \InvalidArgumentException("Unknown autoload method: $method");
        }
    }

    public static function unregister($method = 'spl') 
    {
        switch ($method) 
        {
            case 'spl':
            case 'prepend':
            case 'custom':
                spl_autoload_unregister([self::class, 'load_class']);
                break;
            default:
                throw new \InvalidArgumentException("Unknown autoload method: $method");
        }
    }

    public function register_loaded_classes_menu() 
    {
        add_menu_page(
            'Loaded Classes',
            'Loaded Classes',
            'manage_options',
            'loaded-classes',
            array($this, 'display_loaded_classes'),
            'dashicons-admin-generic',
            9
        );
    }

    public function include_composer_autoload() 
    {
        if (file_exists(__DIR__ . '/vendor/autoload.php')) 
        {
            require __DIR__ . '/vendor/autoload.php';
        }
    }

    public function list_loaded_classes() 
    {
        $this->include_composer_autoload();
        return get_declared_classes();
    }

    public function display_loaded_classes() 
    {
        $classes = $this->list_loaded_classes();

        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('Loaded Classes', 'textdomain') . '</h1>';
        echo '<pre>';
        foreach ($classes as $class) 
        {
            if( strpos($class,"Flex") !== false)
            {

                echo esc_html($class) . "\n";
            }
        }
        echo '</pre>';
        echo '</div>';
    }
}

// Instantiate the autoload
$bootstrap = new BootstrapFlexPortfolioByAbabilitworld();
$bootstrap->init('AbabilItWorld\\FlexPortfolioByAbabilitworld', __DIR__ . '/src');
//$bootstrap->include_composer_autoload();
$bootstrap->include_files_in_directory(__DIR__ . '/src');


