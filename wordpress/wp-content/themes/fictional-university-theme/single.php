<?php

get_header();

while(have_posts()) {
  the_post();
  ?>
    <h2><?php echo the_title(); ?></h2>
    <p><?php echo the_content(); ?></p>
  <?php
}

get_footer();
