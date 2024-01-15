<?php

$context = Timber::get_context();
$post = Timber::get_post();
$context['post'] = $post;


$customContext = [
    'title' => "Des projets pleins la tête : page archive-sewing-projets",
    'posts' => $context['posts'],
];

//$context = array_merge($context['posts'], $customContext);

$templates = ['template/archive' . '.twig'];

// echo ('<pre>');
// // var_dump($customContext);
// var_dump(
//     $customContext['posts']
// );
// echo ('</pre>');

Timber::render($templates, $customContext);
