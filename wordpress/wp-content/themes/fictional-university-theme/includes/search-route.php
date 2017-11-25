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

  $programIDs = array();

  while($query->have_posts()) {
    $query->the_post();

    $record = array(
      'title' => get_the_title(),
      'permalink' => get_the_permalink()
    );

    if('post' == get_post_type() || 'page' == get_post_type()) {
      $record['postType'] = get_post_type();
      $record['authorName'] = get_the_author();

      array_push($results['generalInfo'], $record);
    }

    if('professor' == get_post_type()) {
      $record['image'] = esc_url(get_the_post_thumbnail_url(0, 'professorLandscape'));

      array_push($results['professors'], $record);
    }

    if('program' == get_post_type()) {
      $programIDs[] = get_the_ID();
      $relatedCampuses = get_field('related_campuses');

      if(!empty($relatedCampuses)) {
        foreach($relatedCampuses as $campus) {
          array_push($results['campuses'], array(
            'title' => get_the_title($campus),
            'permalink' => get_the_permalink($campus)
          ));
        }
      }

      array_push($results['programs'], $record);
    }

    if('campus' == get_post_type()) {
      array_push($results['campuses'], $record);
    }

    if('event' == get_post_type()) {
      $eventDate = new DateTime(get_field('event_date'));
      $record['month'] = $eventDate->format('M');
      $record['day'] = $eventDate->format('d');

      if(has_excerpt()) {
        $record['description'] = get_the_excerpt();
      }
      else {
        $record['description'] = wp_trim_words(get_the_content(), 18);
      }

      array_push($results['events'], $record);
    }

    wp_reset_postdata();
  }

  if(!empty($programIDs)) {
    $programRelationshipQuery = new WP_Query(array(
      'post_type' => array('professor', 'event'),

      /**
        * 'meta_query' => array(
        *   'relation' => 'OR',
        *   array(
        *     'key' => 'related_programs',
        *     'compare' => 'LIKE',
        *     'value' => '"<program_id>"'
        *   ),
        *   array(
        *     'key' => 'related_programs',
        *     'compare' => 'LIKE',
        *     'value' => '"<program_id>"'
        *   )
        * )
        */
      'meta_query' => array_merge(
        array(
          'relation' => 'OR'
        ),
        array_map(
          function($pid) {
            return array(
              'key' => 'related_programs',
              'compare' => 'LIKE',
              'value' => '"' . $pid . '"',
            );
          },
          $programIDs
        )
      )
    ));

    while($programRelationshipQuery->have_posts()) {
      $programRelationshipQuery->the_post();

      $record = array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink()
      );

      if('professor' == get_post_type()) {
        $record['image'] = esc_url(get_the_post_thumbnail_url(0, 'professorLandscape'));

        array_push($results['professors'], $record);
      }

      if('event' == get_post_type()) {
        $eventDate = new DateTime(get_field('event_date'));
        $record['month'] = $eventDate->format('M');
        $record['day'] = $eventDate->format('d');

        if(has_excerpt()) {
          $record['description'] = get_the_excerpt();
        }
        else {
          $record['description'] = wp_trim_words(get_the_content(), 18);
        }

        array_push($results['events'], $record);
      }

      wp_reset_postdata();
    }
  }

  // Deduplicate results
  $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
  $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
  $results['campuses'] = array_values(array_unique($results['campuses'], SORT_REGULAR));

  return $results;
}


add_action('rest_api_init', 'university_register_search');
