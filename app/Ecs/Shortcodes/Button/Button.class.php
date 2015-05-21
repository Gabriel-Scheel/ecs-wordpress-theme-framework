<?php

namespace Ecs\Shortcodes\Button;

class Button extends \Ecs\Core\Shortcode
{
    /**
     * @param string
     */
    public $shortcode_tag = 'btn';

    /**
     * @param string
     */
    public $class = 'primary-button';

    /**
     * handler
     * @param array
     * @param string
     */
    public function shortcodeHandler($args, $content = '')
    {
        if (empty($args['link'])) {
            wp_die('You must pass in a link for this shortcode');
        }
        if (empty($args['btntext'])) {
            wp_die('You must include the button text');
        }

        if (empty($args['color'])) {
            $args['color'] = '';
        }

        if (empty($args['size'])) {
            $args['size'] = '';
        }

        // build our classes
        $args['class'] = $this->class . ' '. join(' ', array(
            $args['color'],
            $args['size'],
        ));

        // Build output
        $output = '<a href="' . $args['link'] . '"';
        $output .= ' class="' . $args['class'] . '"';
        $output .= '>';
        $output .= $args['btntext'];
        $output .= '</a>';

        return $output;
    }
}