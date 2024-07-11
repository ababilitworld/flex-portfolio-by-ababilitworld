<?php

namespace Ababilitworld\FlexPaginationByAbabilitworld\Package\Presentation;

(defined('ABSPATH') && defined('WPINC')) || die();

use Ababilitworld\FlexTraitByAbabilitworld\Standard\Standard;
use function Ababilitworld\{
    FlexPackageInfoByAbabilitworld\Package\Service\service as plugin_info,
    FlexPaginationByAbabilitworld\Package\package as package,
};

if (!class_exists('\Ababilitworld\FlexPaginationByAbabilitworld\Package\Presentation\Presentation')) 
{
    class Presentation 
    {
        use Standard;

        private $package;

        public function __construct() 
        {
            $this->package = package();
        }
    }

    //new Presentation();
	
    /**
     * Return the instance
     *
     * @return \Ababilitworld\FlexPaginationByAbabilitworld\Package\Presentation\Presentation
     */
    function presentation() 
    {
        return Presentation::instance();
    }

    // take off
    //presentation();
}

?>
