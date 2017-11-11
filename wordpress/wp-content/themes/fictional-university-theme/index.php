<?php

while(have_posts()) {
  the_post();
  ?>
    <h2>
      <a href="<?php the_permalink();  ?>"><?php echo the_title(); ?></a>
    </h2>
    <p><?php echo the_content(); ?></p>
    <hr>
  <?php
}
