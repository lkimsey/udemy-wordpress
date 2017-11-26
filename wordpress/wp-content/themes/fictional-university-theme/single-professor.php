<?php

get_header();

while(have_posts()) {
  $relatedPrograms = get_field('related_programs');

  $likeCount = new WP_Query(array(
    'post_type' => 'like',
    'meta_query' => array(
      array(
        'key' => 'liked_professor_id',
        'compare' => '=',
        'value' => get_the_ID()
      )
    )
  ));

  $iLike = new WP_Query(array(
    'post_type' => 'like',
    'author' => get_current_user_id(),
    'meta_query' => array(
      array(
        'key' => 'liked_professor_id',
        'compare' => '=',
        'value' => get_the_ID()
      )
    )
  ));

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
        <span class="like-box" data-exists="<?php echo $iLike->found_posts ? 'yes' : 'no'; ?>">
            <i class="fa fa-heart-o" aria-hidden="true"></i>
            <i class="fa fa-heart" aria-hidden="true"></i>
            <span class="like-count"><?php echo $likeCount->found_posts; ?></span>
          </span>
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
