<?php
$container_id = '';

$current_template = get_page_template_slug();

if (is_front_page()) {
    $container_id = 'home-full-col';


} elseif (is_home('blog')) {
    $container_id = 'grid-one-col'; // For the blog page

    
} elseif (is_singular('product')) {
        $container_id = 'grid-single-product'; // For single product pages


} elseif (is_single()) {
    $container_id = 'grid-two-col'; // For single post templates


} elseif (is_singular('news') && $current_template === 'single-news.php') {
    $container_id = 'grid-one-col'; // For single 'news' post templates. Use "singular" if is a Custom Post


} elseif (is_page('Advanced Search') && $current_template === 'page-search-advanced.php') {
    $container_id = 'grid-two-col';


} elseif (is_page('Sidebar Page') && $current_template === 'page-sidebar.php') {
    $container_id = 'grid-two-col';


} elseif (is_page('login') && $current_template === 'login.php') {
    $container_id = 'grid-login';


} elseif (is_archive()) {
    $container_id = 'grid-one-col-wide'; // For archive pages


} elseif (is_page() && $current_template === 'page.php') {
    $container_id = 'grid-one-col';


} else { // Fallback default for other pages
    $container_id = 'grid-one-col'; 
}

// Output the opening div with the container ID
echo '<div id="' . esc_attr($container_id) . '">';
// Now you can start your header