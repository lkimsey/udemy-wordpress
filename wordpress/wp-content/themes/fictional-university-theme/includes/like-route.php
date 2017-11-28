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

function createLike($data) {
  if(!is_user_logged_in()) {
    die('Only logged in users can create a like.');
  }

  $professorId = sanitize_text_field($data['professorId']);

  if('professor' != get_post_type($professorId)) {
    die('Invalid professor');
  }

  $iLike = new WP_Query(array(
    'post_type' => 'like',
    'author' => get_current_user_id(),
    'meta_query' => array(
      array(
        'key' => 'liked_professor_id',
        'compare' => '=',
        'value' => $professorId
      )
    )
  ));

  if(0 != $iLike->found_posts) {
    die('You already like this professor');
  }

  return wp_insert_post(array(
    'post_type' => 'like',
    'post_status' => 'publish',
    'post_title' => 'User ' . get_current_user_id() . ' likes professor ' . $professorId,
    'meta_input' => array(
      'liked_professor_id' => $professorId
    )
  ));
}

function deleteLike() {
}


add_action('rest_api_init', 'university_register_like');
