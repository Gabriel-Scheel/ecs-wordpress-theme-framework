<?php 
/**
 * Test Class
 * For testing ajax calls, etc. 
 *
 * @category   ECS_WordPress
 * @package    Core
 * @subpackage Common
 * @author     Roy Lindauer <hello@roylindauer.com>
 * @copyright  2013 Roy Lindauer
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 * @link       http://roylindauer.com
 */

namespace Ecs\Modules;

class Test
{
    /**
     * test ajax endpoint
     */
    public function ajaxDoThing()
    {
        return array('message' => 'testing');
    }
}

/* End of file Test.class.php */
/* Location: ./app/Ecs/Modules/Test.class.php */
