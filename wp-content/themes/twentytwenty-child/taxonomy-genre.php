<?php
get_header();

$term = get_queried_object(); // Get the current taxonomy term
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; // Get the current page

$args = array(
    'post_type' => 'books',
    'tax_query' => array(
        array(
            'taxonomy' => 'genre',
            'field' => 'slug',
            'terms' => $term->slug,
        ),
    ),
    'posts_per_page' => 5,
    'paged' => $paged,
);

$query = new WP_Query($args);

if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();
        // Display Book information
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header has-text-align-center">
                <div class="entry-header-inner section-inner medium">
            <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php if (has_post_thumbnail()) : ?>
                <div class="entry-thumbnail">
                    <?php the_post_thumbnail(); ?>
                </div>
            <?php endif; ?>
                    <div class="entry-meta">
                        <span class="entry-genre"><?php the_terms(get_the_ID(), 'genre', 'Genre: ', ', '); ?></span>
                        <span class="entry-date"><?php the_date(); ?></span>
                    </div>
                </div>
            </header>

            <div class="entry-content">
                <?php the_excerpt(); ?>
            </div>
        </article>
    <?php
    endwhile;
?>
<div class="entry-content">
<?php
    // Pagination

    the_posts_pagination();
?>
</div>
    <?php
else :
    // No Books found
    echo '<p>No books found.</p>';
endif;

wp_reset_postdata();

get_footer();
?>
