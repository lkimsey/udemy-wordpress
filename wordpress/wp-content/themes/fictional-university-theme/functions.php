<?php

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

add_action('wp_enqueue_scripts', 'university_files');
