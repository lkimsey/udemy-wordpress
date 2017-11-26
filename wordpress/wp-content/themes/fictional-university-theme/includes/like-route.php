<?php

function university_register_like() {
  register_rest_route('university/v1', 'like', array(
    'methods' => 'POST',
    'callback' => 'createLike'
  ));

  register_rest_route('university/v1', 'like', array(
    'methods' => 'DELETE',
    'callback' => 'deleteLike'
  ));
}

function createLike() {
}

function deleteLike() {
}


add_action('rest_api_init', 'university_register_like');
