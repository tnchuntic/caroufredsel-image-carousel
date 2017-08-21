<?php
//echo plugin_dir_url( __FILE__ );
add_action( 'vc_before_init', 'tnc_carousel_integrateWithVC' );

function tnc_carousel_integrateWithVC() {

    $pages = get_posts(
            array(
                'sort_order' => 'ASC',
                'sort_column' => 'post_title',
                'hierarchical' => 1,
                'post_type' => 'tnc-carousel',
                'post_status' => 'publish'
            )
        );
    $arr_pages = [];
    foreach($pages as $page){
        $arr_pages[$page->post_title] = $page->ID;
    }
    
//    echo '<pre>'.print_r($arr_pages,true).'</pre>';
   vc_map( array(
      "name" => __( "TNC Carousel", "mediafox" ),
      "base" => "tnc_carousel",
      "class" => "",
       "icon" => "icon-mf-page",
      "category" => __( "Mediafox", "mediafox"),
      "description" => __("This will display carousel content", "mediafox"),
//      'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
      'admin_enqueue_css' => array('/css/tnc-carousel-style.css'),
      "params" => array(
         array(
            "type" => "dropdown",
            "class" => "",
            "heading" => __( "Carousel", "mediafox" ),
            "param_name" => "tnc_carousel",
             'admin_label' => true,
            "value" => $arr_pages,
            "description" => __( "Choose carousel to display", "mediafox" )
         )
      )
   ) );
}


add_shortcode( 'tnc_carousel', 'get_tnc_carousel_inVC' );
function get_tnc_carousel_inVC( $atts) { // New function parameter $content is added!
   $tnc_carousel = '';
   extract( shortcode_atts( array(
      'tnc_carousel' => 'Default Page'
   ), $atts ) );
  
   $content = get_tnc_carousel_html($tnc_carousel);

   return $content;
}