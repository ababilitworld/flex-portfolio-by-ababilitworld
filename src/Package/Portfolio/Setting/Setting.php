<?php
    namespace Ababilitworld\FlexPortfolioByAbabilitworld\Package\Portfolio\Setting;

    use Ababilitworld\{
        FlexTraitByAbabilitworld\Standard\Standard,
        FlexPortfolioByAbabilitworld\Package\Portfolio\Setting\General\General as GeneralSetting
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

    if (!class_exists(__NAMESPACE__.'\Setting')) 
    {
        class Setting 
        {
            use Standard;
            private $general_setting;

            public function __construct() 
            {
                $this->init();
            }

            private function init() 
            {
                $this->general_setting = GeneralSetting::instance();
                add_action('add_meta_boxes', array($this, 'meta_box'));
                        
            }

            public function meta_box() 
            {
                add_meta_box(
                    PLUGIN_PRE_UNDS.'_meta_box', 
                    '<span class="fas fa-cogs"></span>' . esc_html__(' Portfolio Information : ', 'flex-portfolio-by-ababilitworld') . get_the_title(get_the_id()),
                    array($this, 'settings'));
            }
            
            public function settings() 
            {
                $portfolio_id = get_the_ID();
                ?>
                <div class="fpba">
                    <div class="meta-box">
                        <div class="loader-container">
                            <div class="loader-spinner"></div>
                        </div>
                        <div class="tab-container">
                            <ul class="tab-menu">
                                <h3><?php esc_html_e('Portfolio Info','flex-portfolio-by-ababilitworld');?></h3>
                                <?php do_action('setting_tab_item'); ?>
                            </ul>
                            <div class="tab-content-container">
                                <?php do_action('setting_tab_content',$portfolio_id); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }