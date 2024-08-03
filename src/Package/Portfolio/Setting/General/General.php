<?php
namespace Ababilitworld\FlexPortfolioByAbabilitworld\Package\Portfolio\Setting\General;

use Ababilitworld\FlexPortfolioByAbabilitworld\Package\Portfolio\Template\Template;
use Ababilitworld\FlexTraitByAbabilitworld\Standard\Standard;
use function AbabilItWorld\{
    FlexPortfolioByAbabilitworld\Package\Portfolio\Helper\helper as portfolio_helper,
    FlexPortfolioByAbabilitworld\Package\Portfolio\Presentation\Template\helper as portfolio_template,
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

if (!class_exists('Ababilitworld\FlexPortfolioByAbabilitworld\Package\Portfolio\Setting\General\General')) 
{
    class General 
    {
        use Standard;

        public function __construct() 
        {
            $this->init();
        }

        private function init() 
        {
            add_action('admin_enqueue_scripts', array($this, 'enqueue'));
            add_action('setting_tab_item', array($this, 'tab_item'));
            add_action('setting_tab_content', array($this, 'tab_content'));
            add_action('setting_general_info', array($this, 'general_info'));
            add_action('save_post', array($this, 'save'));
            
        }

        public function enqueue() 
        {
            wp_enqueue_media();
            wp_enqueue_script(PLUGIN_PRE_HYPH.'portfolio-image-script', PLUGIN_URL . '/src/Package/Portfolio/Presentation/Template/Asset/js/image.js', array(), time(), true);
        }

        public function tab_item() 
        {
            ?>
                <li class="tab-item active" data-tabs-target="#setting_general_info"><?php esc_html_e('Portfolio Images','xyz-portfolio');?></li>
            <?php
        }
        
        public function tab_content($portfolio_id) 
        {
            ?>
            <div class="tab-content active" id="setting_general_info">
                <?php do_action('setting_general_info',$portfolio_id); ?>
            </div>
            <?php
        }

        public function general_info($portfolio_id) 
        {
            $images = get_post_meta($portfolio_id, 'portfolio_images', true);
            if(is_array($images))
            {
                $images = array_map('sanitize_text_field', $images);
            }
            

            ?>
            <div class="panel">
                <div class="stmfs-form-group">
                    <label for="portfolio-images">Portfolio Images:</label>
                    <input type="button" class="button" id="upload-images-button" value="Upload Images">
                    <ul id="portfolio-images-preview">
                        <?php
                        if ($images) {
                            foreach ($images as $image) {
                                echo '<li><img src="' . wp_get_attachment_url($image) . '" style="max-width: 150px;"><input type="hidden" name="portfolio-images[]" value="' . $image . '"><a href="#" class="remove-image">Remove</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <?php
        }

        

        public function save($post_id) 
        {
            if (get_post_type($post_id) == 'fpfolio') 
            {
                if (isset($_POST['portfolio-images']) && is_array($_POST['portfolio-images'])) 
                {
                    $images = array_map('sanitize_text_field', $_POST['portfolio-images']);
                    update_post_meta($post_id, 'portfolio_images', $images);
                }
            }
        }
    }
    
    /**
     * Return the instance
     *
     * @return \AbabilItWorld\FlexPortfolioByAbabilitworld\Package\Portfolio\Setting\General\General
     */
    function general() 
    {
        return \AbabilItWorld\FlexPortfolioByAbabilitworld\Package\Portfolio\Setting\General\General::instance();
    }
}
?>
