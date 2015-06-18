<?php
/**
 * Wordpess Theme init
 */

require 'app/Ecs/common.php';

// Init a theme object by passing in a unique name for the theme. This name will be used as the lang key
$theme = new Ecs\Modules\Theme('my-theme-name');

// Execute thg theme
$theme->run();

// Store theme in registry to make it easier to retreive in templates, if needed. 
\Ecs\Helpers\register_object('Theme', $theme);

/* End of file functions.php */
/* Location: ./functions.php */
