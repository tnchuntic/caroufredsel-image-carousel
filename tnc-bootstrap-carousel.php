<?php

/**
 * Plugin Name: TNC Bootstrap Carousel + ACF
 * Plugin URI: http://chuntic.com/plugins/tnc-bootstrap-carousel-acf
 * Description: Bootstrap carousel with admin management using acf.
 * Version: 1.0.0
 * Author: Thomas Chuntic
 * Author URI: http://chuntic.com
 * License: 

  Copyright 2014  Thomas Chuntic  (email : tnchuntic@hotmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


defined('ABSPATH') or die("Direct access not permitted.");

require_once dirname(__FILE__).'/includes/widget.php';
require_once dirname(__FILE__).'/includes/vc-tnc-carousel.php';

// Register custom style
function add_tnc_carousel_style() {
    wp_register_style('tnc-carousel-style', plugins_url('/css/tnc-carousel-style.css',  __FILE__), array(), '1.0.0', 'all');
    wp_enqueue_style('tnc-carousel-style');
}

add_action('wp_enqueue_scripts', 'add_tnc_carousel_style', 100);

// Register boostrap script
function add_tnc_carousel_cript() {
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
        wp_register_script('tnc-carouse-script', plugins_url('/js/tnc-carousel-scripts.js',  __FILE__), array('jquery'), '1.5.2', true);
        wp_enqueue_script('tnc-carouse-script');
    }
}

add_action('wp_enqueue_scripts', 'add_tnc_carousel_cript');


function create_carousel_post_type() {
    register_post_type('tnc-carousel', array(
        'labels' => array(
            'name' => __('Carousels'),
            'singular_name' => __('Carousel')
        ),
        'menu_icon' => 'dashicons-slides',
        'public' => true,
            )
    );
}

add_action('init', 'create_carousel_post_type');


function get_tnc_carousel_html($id, $widget_id="") {
    $str_ret = '';
    if (function_exists('get_field')) {
//        $id = get_field('carousel_slider', 'widget_' . $widget_id);
        $widget_id = !empty($widget_id)?$widget_id.'-':'';
        $carousel_id = $widget_id.'carousel-' . $id;
        // carousel settings
        $duration = get_field('carousel_duration', $id);

        $main_bg = get_field('car_main_bgcolor', $id);

        $str_ret .='<!-- Carousel ' . $id . ' -->';
        $str_ret .='<div id="' . $carousel_id . '" class="carousel slide" data-ride="carousel">';
        
        // carousel images
        if ($rows = get_field('carousel_images', $id)) {

//            HELPER::print_array($rows);

            $str_items = $str_indicators = '';
            $ctr = 0;

            foreach ($rows as $image) {
                if ($ctr == 0) {
                    $active = 'active';
                } else {
                    $active = '';
                }
                
                $styles = !empty($image['car_image'])?'background-image: url(' . wp_get_attachment_image_src($image['car_image'], 'full')[0] . ');':'';
                $styles .= !empty($image['car_bgcolor'])?'background-color: ' . $image['car_bgcolor'].';':'';
                $styles .= !empty($image['car_v_align'])?'background-position-y: ' . $image['car_v_align'].';':'';
                $styles .= !empty($image['car_h_align'])?'background-position-x: ' . $image['car_h_align'].';':'';
//                $styles .= !empty($image['car_bgsize'])?'background-size: ' . $image['car_bgsize'].';':'';
                
                $str_items .= '<div class="item ' . $active . '" style="'.$styles.'">';

                if ($addons = $image['ca_block']) {
                    $str_items .='<div class="car-cap-wrap">';
                    $str_items .='<div class="car-caption container">';
                    foreach ($addons as $addon) {
                        
                        switch($addon['acf_fc_layout']){
                            case 'add_button':
                                $link = ($addon['ca_btn_link'] == 'internal') ? $addon['ca_bi_link'].$addon['ca_bi_inline_id'] : $addon['ca_be_link'];
                                $str_items .= '<div class="car-button '.HELPER::get_bootstrap_grid_columns($addon['ca_col_width']).'"><a href="' . $link . '" class="carousel-link '.$addon['ca_btn_class'].'">' . $addon['ca_btn_label'] . '</a></div>';
                                break;
                            case 'add_content':
                                $str_items .='<div class="car-text '.HELPER::get_bootstrap_grid_columns($addon['ca_col_width']).'">'.$addon['ca_ct_desc'].'</div>';
                                break;
                        }
                    }
                    $str_items .= '</div>';
                    $str_items .= '</div>';
                }

                $str_items .= '</div>';

                $str_indicators .= '<li data-target="#' . $carousel_id . '" data-slide-to="' . $ctr . '"></li>';

                $ctr++;
//                HELPER::print_array($image);
            }
        }
        
        if(get_field('carousel_display_btn_nav', $id)){
            $str_ret .='<!-- Indicators -->
            <ol class="carousel-indicators">' . $str_indicators . '</ol>' . PHP_EOL;
        }
        
        $str_ret .= '<!-- Wrapper for slides -->' . PHP_EOL;
        $str_ret .= '<div class="carousel-inner">' . $str_items . '</div>' . PHP_EOL;
        
        if(get_field('carousel_display_arr_nav', $id)){
            $str_ret .= '<!-- Controls -->
                <a class="left carousel-control" href="#' . $carousel_id . '" role="button" data-slide="prev"><i class="fa fa-chevron-left"></i></a>
                <a class="right carousel-control" href="#' . $carousel_id . '" role="button" data-slide="next"><i class="fa fa-chevron-right"></i></a>';
        }
        
        if($gen_caption = get_field('car_gen_caption', $id)){
            $str_ret .= '<div class="generic-caption '.get_field('car_gen_caption_class', $id).'"><div class="container">'.$gen_caption.'</div></div>';
        }
        $str_ret .= '</div><!-- /.carousel -->';
    }
    return $str_ret;
}
