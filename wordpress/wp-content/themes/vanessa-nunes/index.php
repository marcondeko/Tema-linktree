<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php bloginfo('name'); ?></title>
  <?php wp_head(); ?>
</head>

<?php
$style = get_background_color() ? 'style="background-color:#' . esc_attr(get_background_color()) . ';"' : '';
?>
<body <?php body_class('text-gray-900 min-h-screen flex flex-col items-center justify-center px-4'); ?> <?php echo $style; ?>>

  <div class="text-center max-w-md w-full">
    <?php if (get_theme_mod('profile_image')): ?>
      <?php
      $image_size = get_theme_mod('profile_image_size', 160);
      $ring_color = get_theme_mod('profile_image_ring', '#7c3aed');
      $ring_width = get_theme_mod('profile_image_ring_width', 4);
      ?>
      <div class="profile-image-wrapper rounded-full mx-auto mb-4"
           style="width: <?php echo $image_size; ?>px; height: <?php echo $image_size; ?>px;">
        <img src="<?php echo esc_url(get_theme_mod('profile_image')); ?>"
             class="profile-image rounded-full w-full h-full object-cover"
             style="box-shadow: 0 0 0 <?php echo $ring_width; ?>px <?php echo $ring_color; ?>;">
      </div>
    <?php endif; ?>

    <h1 class="site-title text-2xl font-bold"><?php bloginfo('name'); ?></h1>

    <?php if ($bio = get_theme_mod('bio_text', '')): ?>
      <p class="bio-text text-lg mb-6"><?php echo wp_kses_post($bio); ?></p>
    <?php endif; ?>

    <nav class="linktree-menu space-y-3">
      <?php
        wp_nav_menu([
            'theme_location' => 'primary',
            'container' => false,
            'menu_class' => '',
            'items_wrap' => '%3$s',
        ]);
      ?>
    </nav>
  </div>

  <?php wp_footer(); ?>
</body>
</html>
