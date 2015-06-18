# Overview

Do not edit core. Do not edit anything under Ecs/Core. 

All customizations and new functionaly should be under Ecs/Modules.

# Init Theme

Ecs/Modules/Theme.class.php. 

Any hooks and functions defined in Ecs/Modules/Theme::run() will be executed in theme initialization. 

```
class Theme
{
    public function run()
    {
        parent::run();

        ///// Add Hooks Below /////
        add_action('init', array(&$this, 'myInitFunction'));
        add_action(...);
    }

    public function myInitFunction()
    {
        // do stuff...
    }
}
```

The theme initialization occurs in functions.php

```
$theme = new Ecs\Modules\Theme();
```

To initialize the theme you must now call the run method

```
$theme->run();
```

Finall add the theme object to the class registry. Objects in the registry can be retreived for use in templates and tin other classes without relying on "global". 

```
\Ecs\Helpers\register_object('Theme', $theme);
```

# Helpers
* render_partial
* __
* pr
* debug
* get_instance
* register_object
* date
* json_response

