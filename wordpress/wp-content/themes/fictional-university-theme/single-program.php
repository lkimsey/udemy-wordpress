<?php

get_header();

while(have_posts()) {
  the_post();
  ?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>);"></div>
    <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title"><?php the_title(); ?></h1>
      <div class="page-banner__intro">
        <p>DONT FORGET TO REPLACE ME LATER</p>
      </div>
    </div>
  </div>

  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
    <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs</a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>

    <div class="generic-content"><?php the_content(); ?></div>

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
        // In this array, post id's are in double quotes. Therefor inorder to
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

      $eventDate = new DateTime(get_field('event_date'));
    ?>
    <div class="event-summary">
      <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
        <span class="event-summary__month"><?php echo $eventDate->format('M'); ?></span>
        <span class="event-summary__day"><?php echo $eventDate->format('d'); ?></span>
      </a>
      <div class="event-summary__content">
        <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
        <p>
        <?php
        if(has_excerpt()) {
          echo get_the_excerpt();
        }
        else {
          echo wp_trim_words(get_the_content(), 18);
        }
        ?>
        <a href="<?php the_permalink(); ?>" class="nu gray">Read more</a>
        </p>
        </p>
      </div>
    </div>
    <?php
    }

    wp_reset_postdata();

    endif;
    ?>
  </div>

  <?php
}

get_footer();
