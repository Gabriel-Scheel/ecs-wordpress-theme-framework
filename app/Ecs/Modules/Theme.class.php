<?php
namespace Ecs\Modules;

/**
 * Custom Theme, extends Base Theme
 *
 * @category   ECS_WordPress
 * @package    Modules
 * @subpackage Theme
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */
class Theme extends \Ecs\Core\Theme
{
    /**
     * Extend base run method. Bootstraps theme.
     */
    public function run($config)
    {
        parent::run($config);

        ///// Add Hooks Below /////
    }
}

/* End of file Theme.class.php */
/* Location: ./app/Ecs/Modules/Theme.class.php */
