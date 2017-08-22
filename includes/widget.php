<?php

class TNC_Carousel extends WP_Widget {

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        parent::__construct(
                'tnc_carousel', // Base ID
                __('Carousel', 'tncweb'), // Name
                array('description' => __('Add Carousel Slider to your dynamic sidebar.', 'tncweb'),) // Args
        );
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance) {
//        HELPER::print_array($args);
        global $post;
        $widget_id = $args['widget_id'];
       
        if(function_exists('get_field')){
            if($showhide = get_field('car_show_hide','widget_' . $widget_id)){
                if($showhide == 'none' || ($showhide=='show' && in_array($post->ID, get_field('show_hide_in_page','widget_' . $widget_id))) || ($showhide=='hide' && !in_array($post->ID, get_field('show_hide_in_page','widget_' . $widget_id)))){
                    $id = get_field('carousel_slider', 'widget_' . $widget_id);
                    echo get_tnc_carousel_html($id, $widget_id);
                }
            }
        }
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form($instance) {
        // outputs the options form on admin
    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     */
    public function update($new_instance, $old_instance) {
        // processes widget options to be saved
    }

}
// ================
// end of class

function register_tnc_carousel_widget() {
    register_widget('TNC_Carousel');
}

add_action('widgets_init', 'register_tnc_carousel_widget');



