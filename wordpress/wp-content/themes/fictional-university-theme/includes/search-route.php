<?php

function university_register_search() {
  register_rest_route('university/v1', 'search', array(
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'universitySearchResults'
  ));
}

function universitySearchResults() {
  return 'Blah!!!@#$%^^';
}


add_action('rest_api_init', 'university_register_search');
