<?php

require_once(get_theme_file_path('/includes/search-route.php'));

$IMAGE_SIZES = array(
  'professorLandscape' => array('w' => 400, 'h' => 260),
  'professorPotrait' => array('w' => 480, 'h' => 640),
  'pageBanner' => array('w' => 1500, 'h' => 350)
);

function pageBanner($args=null) {
  if(empty($args['title'])) {
    $args['title'] = get_the_title();
  }

  if(empty($args['subtitle'])) {
    $args['subtitle'] = get_field('page_banner_subtitle');
  }

  if(empty($args['background'])) {
    $background = get_field('page_banner_background_image');

    if(!empty($background)) {
      $args['background'] = $background['sizes']['pageBanner'];
    }
    else {
      $args['background'] = get_theme_file_uri('/images/ocean.jpg');
    }
  }
  ?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['background']; ?>);"></div>
    <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
      <div class="page-banner__intro">
      <p><?php echo $args['subtitle']; ?></p>
      </div>
    </div>
  </div>
  <?php
}

function university_files() {
  wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyCBnSAiZHc5gETMeUszzC9qaH3Vq_PG1Lw', null, '1.0', true);
  wp_enqueue_script(
    /* script nickname      */ 'main-university-js',
    /* URI                  */ get_theme_file_uri('/js/scripts-bundled.js'),
    /* dependencies         */ null,
    /* version              */ '1.0',
    /* load script in body? */ true
  );

  wp_localize_script('main-university-js', 'UNIVERSITY_DATA', array(
    'rootUrl' => esc_url(get_site_url())
  ));

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

  // 1. NOT in admin area
  // 2. ONLY in `campus` archive page
  // 3. ONLY for main query created by Wordpress (no custom queries)
  if(!is_admin() && is_post_type_archive('campus') && $query->is_main_query()) {
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

function university_custom_rest() {
  register_rest_field('post', 'authorName', array(
    'get_callback' => function() { return get_the_author(); }
  ));
}

function redirect_subscribers_to_frontend() {
  $currentUser = wp_get_current_user();

  // User have one role and it is "Subscriber"
  if(1 == count($currentUser->roles) && 'subscriber' == $currentUser->roles[0]) {
    wp_redirect(esc_url(site_url('/')));

    exit;
  }
}

function no_subscriber_admin_bar() {
  $currentUser = wp_get_current_user();

  // User have one role and it is "Subscriber"
  if(1 == count($currentUser->roles) && 'subscriber' == $currentUser->roles[0]) {
    show_admin_bar(false);
  }
}

function university_header_url() {
  return esc_url(esc_url(site_url('/')));
}

function university_header_title() {
  return get_bloginfo('name');
}

function university_login_css() {
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('university_main_styles', get_stylesheet_uri());
}


add_action('wp_enqueue_scripts', 'university_files');
add_action('after_setup_theme', 'university_features');
add_action('pre_get_posts', 'university_adjust_queries');
add_action('rest_api_init', 'university_custom_rest');

// Redirect subscriber accounts out of admin and onto homepage
add_action('admin_init', 'redirect_subscribers_to_frontend');
add_action('wp_loaded', 'no_subscriber_admin_bar');

// Customize login screen
add_action('login_enqueue_scripts', 'university_login_css');
add_filter('login_headerurl', 'university_header_url');
add_filter('login_headertitle', 'university_header_title');

// Disable default image sizes
add_filter('intermediate_image_sizes_advanced', 'university_remove_default_image_sizes');
