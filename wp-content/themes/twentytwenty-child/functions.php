<?php
add_action( 'wp_enqueue_scripts', 'twentytwenty_enqueue_styles' );
function twentytwenty_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_template_directory_uri() . '/style.css', 999 );

}

add_action('wp_enqueue_scripts', 'twentytwenty_enqueue_scripts' );
function twentytwenty_enqueue_scripts(){
    wp_enqueue_script('script', get_stylesheet_directory_uri() . '/assets/js/script.js', array('jquery'), '1.0', true );
    wp_localize_script('script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

}

/**
 * Custom Post Type: Books
 */
function create_books_post_type() {
    $labels = array(
        'name' => __( 'Books', 'twentytwentychild' ),
        'singular_name' => __( 'Book', 'twentytwentychild' ),
        'menu_name' => __( 'Books', 'twentytwentychild' ),
        'all_items' => __( 'All Books', 'twentytwentychild' ),
        'add_new' => __( 'Add New', 'twentytwentychild' ),
        'add_new_item' => __( 'Add New Book', 'twentytwentychild' ),
        'edit_item' => __( 'Edit Book', 'twentytwentychild' ),
        'new_item' => __( 'New Book', 'twentytwentychild' ),
        'view_item' => __( 'View Book', 'twentytwentychild' ),
        'search_items' => __( 'Search Books', 'twentytwentychild' ),
        'not_found' => __( 'No books found', 'twentytwentychild' ),
        'not_found_in_trash' => __( 'No books found in trash', 'twentytwentychild' ),
        'parent_item_colon' => __( 'Parent Book:', 'twentytwentychild' ),
        'featured_image' => __( 'Book Cover Image', 'twentytwentychild' ),
        'set_featured_image' => __( 'Set cover image', 'twentytwentychild' ),
        'remove_featured_image' => __( 'Remove cover image', 'twentytwentychild' ),
        'use_featured_image' => __( 'Use as cover image', 'twentytwentychild' ),
        'archives' => __( 'Book archives', 'twentytwentychild' ),
        'insert_into_item' => __( 'Insert into book', 'twentytwentychild' ),
        'uploaded_to_this_item' => __( 'Uploaded to this book', 'twentytwentychild' ),
        'filter_items_list' => __( 'Filter books list', 'twentytwentychild' ),
        'items_list_navigation' => __( 'Books list navigation', 'twentytwentychild' ),
        'items_list' => __( 'Books list', 'twentytwentychild' ),
    );

    $args = array(
        'label' => __( 'Books', 'twentytwentychild' ),
        'description' => __( 'A collection of books', 'twentytwentychild' ),
        'labels' => $labels,
        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
        'public' => true,
        'rewrite' => array( 'slug' => 'library' ),
        'menu_icon' => 'dashicons-book',
    );

    register_post_type( 'books', $args );
}
add_action( 'init', 'create_books_post_type' );

/**
 * Custom Taxonomy: Genre
 */
function create_genre_taxonomy() {
    $labels = array(
        'name' => __( 'Genres', 'twentytwentychild' ),
        'singular_name' => __( 'Genre', 'twentytwentychild' ),
        'menu_name' => __( 'Genres', 'twentytwentychild' ),
        'all_items' => __( 'All Genres', 'twentytwentychild' ),
        'edit_item' => __( 'Edit Genre', 'twentytwentychild' ),
        'view_item' => __( 'View Genre', 'twentytwentychild' ),
        'update_item' => __( 'Update Genre', 'twentytwentychild' ),
        'add_new_item' => __( 'Add New Genre', 'twentytwentychild' ),
        'new_item_name' => __( 'New Genre Name', 'twentytwentychild' ),
        'parent_item' => __( 'Parent Genre', 'twentytwentychild' ),
        'parent_item_colon' => __('Parent Genre:', 'twentytwentychild' ),
        'search_items' => __( 'Search Genres', 'twentytwentychild' ),
        'popular_items' => __( 'Popular Genres', 'twentytwentychild' ),
        'separate_items_with_commas' => __( 'Separate genres with commas', 'twentytwentychild' ),
        'add_or_remove_items' => __( 'Add or remove genres', 'twentytwentychild' ),
        'choose_from_most_used' => __( 'Choose from the most used genres', 'twentytwentychild' ),
        'not_found' => __( 'No genres found', 'twentytwentychild' ),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'book-genre' ),
    );

    register_taxonomy( 'genre', array( 'books' ), $args );
}
add_action( 'init', 'create_genre_taxonomy' );


/**
 * Shortcode: Recent Book Title
 * Usage: [recent_book_title]
 */
function recent_book_title_shortcode() {
    $args = array(
        'post_type' => 'books',
        'posts_per_page' => 1,
        'orderby' => 'date',
        'order' => 'DESC',
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            return get_the_title();
        }
    }

    wp_reset_postdata();
    return 'No recent book found.';
}
add_shortcode('recent_book_title', 'recent_book_title_shortcode');

/**
 * Shortcode: Books by Genre
 * Usage: [books_by_genre genre_id="123"]
 */
function books_by_genre_shortcode($atts) {
    $atts = shortcode_atts(array(
        'genre_id' => '',
    ), $atts);

    $args = array(
        'post_type' => 'books',
        'posts_per_page' => 5,
        'orderby' => 'title',
        'order' => 'ASC',
        'tax_query' => array(
            array(
                'taxonomy' => 'genre',
                'field' => 'term_id',
                'terms' => $atts['genre_id'],
            ),
        ),
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $output = '<ul>';
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<li>' . get_the_title() . '</li>';
        }
        $output .= '</ul>';
        return $output;
    }

    wp_reset_postdata();
    return 'No books found for the given genre.';
}
add_shortcode('books_by_genre', 'books_by_genre_shortcode');

// AJAX callback to retrieve books
function ajax_get_books_callback() {
    $args = array(
        'post_type' => 'books',
        'posts_per_page' => 20,
    );

    $query = new WP_Query($args);

    $books = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $book = array(
                'name' => get_the_title(),
                'date' => get_the_date(),
                'genre' => wp_get_post_terms(get_the_ID(), 'genre', array('fields' => 'names')),
                'excerpt' => get_the_excerpt(),
            );
            $books[] = $book;
        }
    }

    wp_reset_postdata();

    // Return the books in JSON format
    wp_send_json($books);
}
add_action('wp_ajax_get_books', 'ajax_get_books_callback');
add_action('wp_ajax_nopriv_get_books', 'ajax_get_books_callback');



