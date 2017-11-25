<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <?php wp_head(); ?>
</head>
  <body <?php body_class(); ?>>
  <header class="site-header">
    <div class="container">
      <h1 class="school-logo-text float-left"><a href="<?php echo esc_url(site_url()); ?>"><strong>Fictional</strong> University</a></h1>
      <a href="<?php echo esc_url(site_url('/search')); ?>" class="js-search-trigger site-header__search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
      <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
      <div class="site-header__menu group">
        <nav class="main-navigation">
          <?php
          /*
          wp_nav_menu(array(
            'theme_location' => 'header_menu'
          ));
          */

          $aboutPage = get_page_by_path('about-us');
          ?>
          <ul>
            <li <?php if(is_page('about-us') || wp_get_post_parent_id(0) == $aboutPage->ID) echo 'class="current-menu-item"'; ?>><a href="<?php echo esc_url(site_url('/about-us')); ?>">About Us</a></li>
            <li <?php if('program' == get_post_type()) echo 'class="current-menu-item"'; ?>><a href="<?php echo get_post_type_archive_link('program'); ?>">Programs</a></li>
            <li <?php if('event' == get_post_type() || is_page('past-events')) echo 'class="current-menu-item"'; ?>><a href="<?php echo get_post_type_archive_link('event'); ?>">Events</a></li>
            <li <?php if('campus' == get_post_type()) echo 'class="current-menu-item"'; ?>><a href="<?php echo get_post_type_archive_link('campus'); ?>">Campuses</a></li>
            <li <?php if('post' == get_post_type()) echo 'class="current-menu-item"'; ?>><a href="<?php echo esc_url(site_url('/blog')); ?>">Blog</a></li>
          </ul>
        </nav>
        <div class="site-header__util">
          <?php if(is_user_logged_in()): ?>
          <a href="<?php echo esc_url(site_url('/my-notes')); ?>" class="btn btn--small btn--orange float-left push-right">My Notes</a>
          <a href="<?php echo wp_logout_url(); ?>" class="btn btn--small  btn--dark-orange float-left btn--with-photo">
            <span class="site-header__avatar"><?php echo get_avatar(get_current_user_id(), 60); ?></span>
            <span class="btn__text">Logout</span>
          </a>
          <?php else: ?>
          <a href="<?php echo wp_login_url(); ?>" class="btn btn--small btn--orange float-left push-right">Login</a>
          <a href="<?php echo wp_registration_url(); ?>" class="btn btn--small  btn--dark-orange float-left">Sign Up</a>
          <?php endif; ?>
          <a href="<?php echo esc_url(site_url('/search')); ?>" class="search-trigger js-search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
        </div>
      </div>
    </div>
  </header>
