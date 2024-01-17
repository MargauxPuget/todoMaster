<?php

use mpuget\TodoMaster\Theme;

define('TEMPLATE_DIR', get_template_directory());
define('ASSETS_SUB_DIR', '/assets/');
define('ASSETS_DIR', get_stylesheet_directory_uri() . ASSETS_SUB_DIR);


/**
 * @var Theme $theme
 */
global $siteTheme;
$siteTheme = new Theme();





if (function_exists('add_theme_support')) {
    // Allow thumbnails
    add_theme_support('post-thumbnails');
    // Add titles support
    add_theme_support('title-tag');
}

require_once __DIR__ . '/vendor/autoload.php';
Timber\Timber::init();


function project_sewing_register_post_types()
{

    // --------------------------------- //
    // Déclaration de la custom pot type //
    // CPT sewingProjets                       //
    // --------------------------------- //
    $labels = array(
        'name' => 'Projets de couture',
        'all_items' => 'Tous les projets de couture',  // affiché dans le sous menu
        'singular_name' => 'Projet de couture',
        'add_new_item' => 'Ajouter un projet de couture',
        'edit_item' => 'Modifier le projet de couture',
        'menu_name' => 'Projets de couture'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_position' => 5,
        'menu_icon' => 'dashicons-admin-customizer',
    );

    // Attention : le premier paramètre est le slug de votre CPT. si vous le changer en cours de route, les publications ne seront plus accessibles (car elles répondent toujours à l’ancien nom).
    // Donc je vous déconseille de changer son nom. À la place on va pouvoir changer plutôt son URL grâce à rewrite, que l’on va voir un peu plus tard.
    register_post_type('sewing-projets', $args);


    // --------------------------- //
    // Déclaration de la Taxonomie //
    // Taxonomie Tissu             //
    // --------------------------- //

    $labels = array(
        'name' => 'Tissu',
        'new_item_name' => 'Nouveau tissu',
        'parent_item' => 'Tissu parent',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
    );

    register_taxonomy('fabric', 'sewing-projets', $args);

    // --------------------------- //
    // Déclaration de la Taxonomie //
    // Taxonomie Tissu             //
    // --------------------------- //

    $labels = array(
        'name' => 'Patron',
        'new_item_name' => 'Nouveau patron',
        'parent_item' => 'Patron parent',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
    );

    register_taxonomy('pattern', array('projets', 'sewing-projets'), $args);
}
add_action('init', 'project_sewing_register_post_types'); // Le hook init lance la fonction
