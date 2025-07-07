<?php
/**
 * Tema Linktree - functions.php
 * @package Linktree_Theme
 */

// ====================================
// 1. CONFIGURAÇÕES BÁSICAS DO TEMA
// ====================================
function linktree_theme_setup() {
    add_theme_support('menus');
    add_theme_support('custom-background', ['default-color' => 'ffffff']);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('title-tag');

    register_nav_menus([
        'primary' => 'Links principais (estilo Linktree)',
    ]);

    add_image_size('linktree-profile', 300, 300, true);
}
add_action('after_setup_theme', 'linktree_theme_setup');

// ====================================
// 2. CARREGAMENTO DE RECURSOS
// ====================================
function linktree_enqueue_assets() {
    wp_enqueue_script('tailwindcss', 'https://cdn.tailwindcss.com', [], null, true);
}
add_action('wp_enqueue_scripts', 'linktree_enqueue_assets');

// Carrega JS do Customizer (do jeito certo)
function linktree_customizer_live_preview_js() {
    wp_enqueue_script(
        'linktree-customizer',
        get_template_directory_uri() . '/js/customizer.js',
        ['jquery', 'customize-preview'],
        '1.0.0',
        true
    );
}
add_action('customize_preview_init', 'linktree_customizer_live_preview_js');

// ====================================
// 3. CUSTOMIZER
// ====================================
function linktree_customizer_settings($wp_customize) {
    $wp_customize->get_section('title_tagline')->title = __('Identidade do Site', 'linktree-theme');

    $wp_customize->add_setting('profile_image', [
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_url_raw'
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize, 'profile_image', [
            'label'       => __('Foto de Perfil', 'linktree-theme'),
            'description' => __('Recomendado: 300x300 pixels', 'linktree-theme'),
            'section'     => 'title_tagline',
            'priority'    => 10
        ]
    ));

    $wp_customize->add_setting('profile_image_size', [
        'default'           => 160,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'linktree_sanitize_number_range'
    ]);
    $wp_customize->add_control('profile_image_size', [
        'label'       => __('Tamanho da Imagem (px)', 'linktree-theme'),
        'section'     => 'title_tagline',
        'type'        => 'number',
        'input_attrs' => ['min' => 80, 'max' => 300, 'step' => 10],
        'priority'    => 11
    ]);

    $wp_customize->add_setting('profile_image_ring', [
        'default'           => '#7c3aed',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color'
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize, 'profile_image_ring', [
            'label'    => __('Cor do Efeito de Anel', 'linktree-theme'),
            'section'  => 'title_tagline',
            'priority' => 12
        ]
    ));

    $wp_customize->add_setting('profile_image_ring_width', [
        'default'           => 4,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'linktree_sanitize_number_range'
    ]);
    $wp_customize->add_control('profile_image_ring_width', [
        'label'       => __('Largura do Anel (px)', 'linktree-theme'),
        'section'     => 'title_tagline',
        'type'        => 'number',
        'input_attrs' => ['min' => 1, 'max' => 20, 'step' => 1],
        'priority'    => 13
    ]);

    $wp_customize->add_setting('bio_text', [
        'default'           => __('Dicas de beleza, maternidade e DIY', 'linktree-theme'),
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_kses_post'
    ]);
    $wp_customize->add_control('bio_text', [
        'label'    => __('Biografia', 'linktree-theme'),
        'section'  => 'title_tagline',
        'type'     => 'textarea',
        'priority' => 20
    ]);

    // Cores do menu
    $wp_customize->add_section('linktree_menu', [
        'title'    => __('Aparência do Menu', 'linktree-theme'),
        'priority' => 35
    ]);

    $menu_colors = [
        'menu_bg_color' => ['default' => '#7c3aed', 'label' => __('Cor de Fundo', 'linktree-theme')],
        'menu_text_color' => ['default' => '#ffffff', 'label' => __('Cor do Texto', 'linktree-theme')],
        'menu_hover_bg_color' => ['default' => '#6d28d9', 'label' => __('Cor de Fundo (Hover)', 'linktree-theme')],
        'menu_hover_text_color' => ['default' => '#ffffff', 'label' => __('Cor do Texto (Hover)', 'linktree-theme')]
    ];

    foreach ($menu_colors as $id => $args) {
        $wp_customize->add_setting($id, [
            'default'           => $args['default'],
            'transport'         => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color'
        ]);
        $wp_customize->add_control(new WP_Customize_Color_Control(
            $wp_customize, $id, [
                'label'   => $args['label'],
                'section' => 'linktree_menu'
            ]
        ));
    }

    // Cores do texto
    $wp_customize->add_section('linktree_text', [
        'title'    => __('Cores do Texto', 'linktree-theme'),
        'priority' => 40
    ]);

    $wp_customize->add_setting('title_color', [
        'default'           => '#2d3748',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color'
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize, 'title_color', [
            'label'   => __('Cor do Título', 'linktree-theme'),
            'section' => 'linktree_text'
        ]
    ));

    $wp_customize->add_setting('bio_color', [
        'default'           => '#4a5568',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color'
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize, 'bio_color', [
            'label'       => __('Cor da Biografia', 'linktree-theme'),
            'description' => __('Cor do texto descritivo abaixo do título', 'linktree-theme'),
            'section'     => 'linktree_text'
        ]
    ));
}
add_action('customize_register', 'linktree_customizer_settings');

// ====================================
// 4. PERSONALIZAÇÃO DO MENU
// ====================================
function linktree_menu_attributes($atts, $item, $args) {
    if ($args->theme_location == 'primary') {
        $atts['class'] = 'block px-6 py-3 rounded-full transition-all duration-300 text-center mb-3';
        $atts['style'] = sprintf(
            'background-color:%s; color:%s;',
            get_theme_mod('menu_bg_color', '#7c3aed'),
            get_theme_mod('menu_text_color', '#ffffff')
        );
        $atts['onmouseover'] = sprintf(
            "this.style.backgroundColor='%s';this.style.color='%s'",
            get_theme_mod('menu_hover_bg_color', '#6d28d9'),
            get_theme_mod('menu_hover_text_color', '#ffffff')
        );
        $atts['onmouseout'] = sprintf(
            "this.style.backgroundColor='%s';this.style.color='%s'",
            get_theme_mod('menu_bg_color', '#7c3aed'),
            get_theme_mod('menu_text_color', '#ffffff')
        );
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'linktree_menu_attributes', 10, 3);

function linktree_menu_output($items) {
    return str_replace(['<li', '</li>'], ['<div', '</div>'], $items);
}
add_filter('wp_nav_menu_items', 'linktree_menu_output');

// ====================================
// 5. CSS DINÂMICO
// ====================================
function linktree_dynamic_styles() {
    ?>
    <style type="text/css">
        .site-title { color: <?php echo get_theme_mod('title_color', '#2d3748'); ?>; }
        .bio-text { color: <?php echo get_theme_mod('bio_color', '#4a5568'); ?>; }

        .linktree-menu a {
            background-color: <?php echo get_theme_mod('menu_bg_color', '#7c3aed'); ?>;
            color: <?php echo get_theme_mod('menu_text_color', '#ffffff'); ?>;
        }
        .linktree-menu a:hover {
            background-color: <?php echo get_theme_mod('menu_hover_bg_color', '#6d28d9'); ?>;
            color: <?php echo get_theme_mod('menu_hover_text_color', '#ffffff'); ?>;
        }

        .profile-image {
            box-shadow: 0 0 0 <?php echo get_theme_mod('profile_image_ring_width', 4); ?>px <?php echo get_theme_mod('profile_image_ring', '#7c3aed'); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'linktree_dynamic_styles');

// ====================================
// 6. FUNÇÃO DE SANITIZAÇÃO
// ====================================
function linktree_sanitize_number_range($number, $setting) {
    $atts = $setting->manager->get_control($setting->id)->input_attrs;
    $min = isset($atts['min']) ? $atts['min'] : 0;
    $max = isset($atts['max']) ? $atts['max'] : $number;
    $step = isset($atts['step']) ? $atts['step'] : 1;

    $number = floor($number / $step) * $step;
    return max($min, min($max, $number));
}
