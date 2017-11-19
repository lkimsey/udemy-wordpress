<?php

get_header();

pageBanner();

while(have_posts()) {
  the_post();

  $mapLocation = get_field('map_location');
  ?>
  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
    <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Campuses</a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>

    <div class="generic-content"><?php the_content(); ?></div>

    <div class="acf-map">
      <div class="marker" data-lat="<?php echo $mapLocation['lat']; ?>" data-lng="<?php echo $mapLocation['lng']; ?>">
        <h3><?php the_title(); ?></h3>
        <?php echo $mapLocation['address']; ?>
      </div>
    </div>

    <?php
    $today = date('Ymd');

    $relatedPrograms = new WP_Query(array(
      'post_type' => 'program',
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
          'key' => 'related_campuses',
          'compare' => 'LIKE',
          'value' => '"' . get_the_ID() . '"',
        )
      )
    ));

    if($relatedPrograms->have_posts()):
    ?>

    <hr class="section-break" />
    <h2 class="headline headline--medium">Programs Available At This Campus</h2>

    <ul class="link-list min-list">
    <?php
    while($relatedPrograms->have_posts()) {
      $relatedPrograms->the_post();
    ?>
    <li>
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </li>
    <?php
    }
    ?>
    </ul>
    <?php

    wp_reset_postdata();

    endif;
    ?>
  </div>

  <?php
}

get_footer();
