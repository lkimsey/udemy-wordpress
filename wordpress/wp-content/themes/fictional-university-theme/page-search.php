<?php

get_header();

while(have_posts()) {
  the_post();

  pageBanner();
  ?>
  <div class="container container--narrow page-section">

    <div class="generic-content">
      <form class="search-form" method="get" action="<?php echo esc_url(site_url('/')); ?>">
        <label class="headline headline--medium"for="s">Perform a New Search:</label>
        <div class="search-form-row">
          <input type="search" class="s" name="s" id="s" placeholder="What are you looking for?" />
          <input type="submit" class="search-submit" value="Search" />
        </div>
      </form>
    </div>

  </div>
  <?php
}

get_footer();
