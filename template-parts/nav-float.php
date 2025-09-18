  <!-- Float Nav BEGIN -->
  <div id="nav-float">
    <figure id="logo-container-float">
      <a href="<?php echo esc_url(get_bloginfo('url')); ?>" title="<?php echo esc_attr(get_bloginfo('name')); ?> | <?php echo esc_attr(get_bloginfo('description')); ?>"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/framework-logo.png" /></a></figure>
    <?php wp_nav_menu(array(
      'menu' => 'floatnav',
      'theme_location' => 'floatnav',
      'menu_class' => 'float-nav',
    )); ?>

<nav><?php get_template_part('/template-parts/mobile-nav'); ?></nav>

  </div>

  
  <!-- Float Nav END -->