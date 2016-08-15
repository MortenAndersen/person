<?php

/*
Plugin Name: Person
Plugin URI: <a href="http://www.hjemmesider.dk"<br />
Description:</a> Opret kontaktpersoner på hjemmesiden.
Version: 1.0
Author: Hjemmesider.dk
Author URI: http://www.hjemmesider.dk.dk
*/

// person Posttype

function hjemmesider_person_create_posttype() {
    register_post_type('person', array('labels' => array('name' => __('Personer', 'personsdomain'), 'singular_name' => __('person', 'persondomain')), 'public' => true, 'menu_icon' => 'dashicons-businessman', 'exclude_from_search' => true, 'publicly_queryable'  => false, 'query_var'  => false, 'taxonomies' => array('category'), 'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'), 'rewrite' => array('slug' => 'contact'),));
}
add_action('init', 'hjemmesider_person_create_posttype');

// Images

if (function_exists('add_theme_support')) {
    add_theme_support('post-thumbnails');
    add_image_size('person_plugin', 200, 220, true);
}

// Personer Shortcode

add_shortcode('personer', 'hjemmesider_personer');
function hjemmesider_personer($atts) {
    global $post;
    ob_start();

    // define attributes and their defaults
    extract(shortcode_atts(array('cat' => 'cat'), $atts));

    // define query parameters based on attributes
    $options = array('post_type' => 'person', 'orderby' => 'menu_order', 'order' => 'ASC', 'posts_per_page' => - 1, 'cat' => array($cat) );
    $query = new WP_Query($options);

    // run the loop based on the query
    if ($query->have_posts()) { ?>

<ul class="personer nul">

<?php
    while ($query->have_posts()):
            $query->the_post(); ?>
<li class="person <?php
$title = get_the_title();
$loweredTitle = strtolower($title);
echo str_replace(array(" ", 'æ', 'Æ', 'ø', 'Ø', 'å', 'Å', 'ü', 'Ü', 'ö', 'Ö'), array('__', 'ae', 'ae', 'oe', 'oe', 'aa', 'aa', 'u', 'u', 'o', 'o'), $loweredTitle);
?>">
<?php
the_post_thumbnail('person_plugin'); echo "\n"; ?>
<h4><?php
    the_title(); ?></h4>
<?php
    the_content(); ?>
    <?php edit_post_link('<span>Rediger</span>Person', '<p class="edit__content">', '</p>'); ?>
</li>

<?php
    endwhile;
        wp_reset_postdata(); ?>
</ul>
    <?php
        $myvariable = ob_get_clean();
        return $myvariable;
    }
}
