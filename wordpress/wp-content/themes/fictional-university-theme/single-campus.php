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

  </div>

  <?php
}

get_footer();
