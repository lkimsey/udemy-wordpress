<?php

function university_register_search() {
  register_rest_route('university/v1', 'search', array(
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'universitySearchResults'
  ));
}

function universitySearchResults($data) {
  $query = new WP_Query(array(
    'post_type' => array('post', 'page', 'professor', 'program', 'campus', 'event'),
    's' => sanitize_text_field($data['term'])
  ));

  $results = array(
    'generalInfo' => array(),
    'professors' => array(),
    'programs' => array(),
    'events' => array(),
    'campuses' => array(),
  );

  while($query->have_posts()) {
    $query->the_post();

    $record = array(
      'title' => get_the_title(),
      'permalink' => get_the_permalink()
    );

    if('post' == get_post_type() || 'page' == get_post_type()) {
      array_push($results['generalInfo'], $record);
    }

    if('professor' == get_post_type()) {
      array_push($results['professors'], $record);
    }

    if('program' == get_post_type()) {
      array_push($results['programs'], $record);
    }

    if('campus' == get_post_type()) {
      array_push($results['campuses'], $record);
    }

    if('event' == get_post_type()) {
      array_push($results['events'], $record);
    }
  }

  return $results;
}


add_action('rest_api_init', 'university_register_search');
