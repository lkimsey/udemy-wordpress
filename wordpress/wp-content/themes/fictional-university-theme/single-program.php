<?php

get_header();

pageBanner();

while(have_posts()) {
  the_post();
  ?>
  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
    <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs</a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>

    <div class="generic-content"><?php the_content(); ?></div>

    <?php
    $today = date('Ymd');

    $relatedProfessors = new WP_Query(array(
      'post_type' => 'professor',
      'posts_per_page' => -1,
      'orderby' => 'title',
      'order' => 'asc',
      'meta_query' => array(
        // Post type relationships are stored as PHP array, and this comparison
        // is done as SQL type LIKE on the serialized string form of that array.
        //
        // In this array, post id's are in double quotes. Therefor in order to
        // avoid matching 11 to both 11 & 111 we wrap the search value in double
        // quotes.
        array(
          'key' => 'related_programs',
          'compare' => 'LIKE',
          'value' => '"' . get_the_ID() . '"',
        )
      )
    ));

    if($relatedProfessors->have_posts()):
    ?>

    <hr class="section-break" />
    <h2 class="headline headline--medium"><?php the_title(); ?> Professors</h2>

    <ul class="professor-cards">
    <?php
    while($relatedProfessors->have_posts()) {
      $relatedProfessors->the_post();
    ?>
    <li class="professor-card__list-item">
      <a class="professor-card" href="<?php the_permalink(); ?>">
        <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>" />
        <span class="professor-card__name"><?php the_title(); ?></span>
      </a>
    </li>
    <?php
    }
    ?>
    </ul>
    <?php

    wp_reset_postdata();

    endif;
    ?>

    <?php
    $today = date('Ymd');

    $relatedEvents = new WP_Query(array(
      'post_type' => 'event',
      'meta_key' => 'event_date',
      'orderby' => 'meta_value_num',
      'order' => 'asc',
      'meta_query' => array(
        array(
          'key' => 'event_date',
          'compare' => '>=',
          'value' => $today,
          'type' => 'numeric'
        ),

        // Post type relationships are stored as PHP array, and this comparison
        // is done as SQL type LIKE on the serialized string form of that array.
        //
        // In this array, post id's are in double quotes. Therefor in order to
        // avoid matching 11 to both 11 & 111 we wrap the search value in double
        // quotes.
        array(
          'key' => 'related_programs',
          'compare' => 'LIKE',
          'value' => '"' . get_the_ID() . '"',
        )
      )
    ));

    if($relatedEvents->have_posts()):
    ?>

    <hr class="section-break" />
    <h2 class="headline headline--medium">Upcoming <?php the_title(); ?> Programs</h2>

    <?php
    while($relatedEvents->have_posts()) {
      $relatedEvents->the_post();

      get_template_part('template-parts/content', 'event');
    }

    wp_reset_postdata();

    endif;
    ?>
  </div>

  <?php
}

get_footer();
