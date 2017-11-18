<?php

get_header();

while(have_posts()) {
  $relatedPrograms = get_field('related_programs');

  the_post();

  pageBanner();
  ?>
  <div class="container container--narrow page-section">
    <div class="generic-content">
      <div class="row group">
        <div class="one-third">
          <?php the_post_thumbnail('professorPotrait'); ?>
        </div>
        <div class="two-thirds">
          <?php the_content(); ?>
        </div>
      </div>
    </div>

    <?php if($relatedPrograms): ?>
      <hr class="section-break" />
      <h2 class="headline headline--medium">Subject(s) Taught</h2>
      <ul class="link-list min-list">
      <?php foreach($relatedPrograms as $program): ?>
        <li><a href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></li>
      <?php endforeach; ?>
    <?php endif; ?>
    </ul>
  </div>

  <?php
}

get_footer();
