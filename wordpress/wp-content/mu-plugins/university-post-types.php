<?php

function university_post_types() {
  // Event Post Type
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

  // Program Post Type
  register_post_type('program', array(
    'public' => true,
    'has_archive' => true,
    'rewrite' => array(
      'slug' => 'programs'
    ),
    'labels' => array(
      'name' => 'Programs',
      'singular_name' => 'Event',
      'add_new_item' => 'Add New Program',
      'edit_item' => 'Edit Program',
      'all_items' => 'All Programs'
    ),
    'menu_icon' => 'dashicons-carrot',
    'supports' => array('title', 'editor')
  ));

  // Professor Post Type
  register_post_type('professor', array(
    'public' => true,
    'has_archive' => false,
    'labels' => array(
      'name' => 'Professors',
      'singular_name' => 'Event',
      'add_new_item' => 'Add New Professor',
      'edit_item' => 'Edit Professor',
      'all_items' => 'All Professors'
    ),
    'menu_icon' => 'dashicons-businessman',
    'supports' => array('title', 'editor', 'thumbnail')
  ));
}


add_action('init', 'university_post_types');
