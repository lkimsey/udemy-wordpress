<?php

while(have_posts()) {
  the_post();
  ?>
    <h1>This is a Page, NOT a Post</h1>
    <h2><?php echo the_title(); ?></h2>
    <p><?php echo the_content(); ?></p>
  <?php
}
