<?php

function university_post_types() {
  register_post_type('event', array(
    'public' => true,
    'has_archive' => true,
    'rewrite' => array(
      'slug' => 'events'
    ),
    'labels' => array(
      'name' => 'Events',
      'singular_name' => 'Event',
      'add_new_item' => 'Add New Event',
      'edit_item' => 'Edit Event',
      'all_items' => 'All Events'
    ),
    'menu_icon' => 'dashicons-analytics',
    'supports' => array('title', 'editor', 'excerpt')
  ));
}


add_action('init', 'university_post_types');
