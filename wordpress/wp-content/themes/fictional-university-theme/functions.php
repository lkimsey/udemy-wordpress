<?php

$IMAGE_SIZES = array(
  'professorLandscape' => array('w' => 400, 'h' => 260),
  'professorPotrait' => array('w' => 480, 'h' => 640),
  'pageBanner' => array('w' => 1500, 'h' => 350)
);

function university_files() {
  wp_enqueue_script(
    /* script nickname      */ 'main-university-js',
    /* URI                  */ get_theme_file_uri('/js/scripts-bundled.js'),
    /* dependencies         */ null,
    /* version              */ '1.0',
    /* load script in body? */ true
  );

  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('university_main_styles', get_stylesheet_uri());
}

function university_features() {
  global $IMAGE_SIZES;

  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');

  foreach($IMAGE_SIZES as $key => $size) {
    add_image_size($key, $size['w'], $size['h'], true);
  }

  register_nav_menu('header_menu', 'Header Menu');
  register_nav_menu('footer_menu_1', 'Footer Menu 1');
  register_nav_menu('footer_menu_2', 'Footer Menu 2');
}

function university_adjust_queries($query) {
  // 1. NOT in admin area
  // 2. ONLY in `event` archive page
  // 3. ONLY for main query created by Wordpress (no custom queries)
  if(!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
    $today = date('Ymd');

    $query->set('meta_key', 'event_date');
    $query->set('orderby', 'meta_value');
    $query->set('order', 'asc');
    $query->set('meta_query', array(
      'key' => 'event_date',
      'compare' => '>=',
      'value' => $today,
      'type' => 'numeric'
    ));
  }

  // 1. NOT in admin area
  // 2. ONLY in `program` archive page
  // 3. ONLY for main query created by Wordpress (no custom queries)
  if(!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
    $query->set('orderby', 'title');
    $query->set('order', 'asc');
    $query->set('posts_per_page', -1);
  }
}

function university_remove_default_image_sizes($sizes) {
  return array_filter(
    $sizes,
    function($sizeKey) {
      global $IMAGE_SIZES;

      return in_array($sizeKey, array_keys($IMAGE_SIZES));
    },
    ARRAY_FILTER_USE_KEY
  );
}


add_action('wp_enqueue_scripts', 'university_files');
add_action('after_setup_theme', 'university_features');
add_action('pre_get_posts', 'university_adjust_queries');

add_filter('intermediate_image_sizes_advanced', 'university_remove_default_image_sizes');
