<?php
get_header();

while (have_posts()) : the_post();
    // Display Book title, image, genre, and date
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header has-text-align-center">
            <div class="entry-header-inner section-inner medium">
                <h1 class="entry-title"><?php the_title(); ?></h1>
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
            <?php the_content(); ?>
        </div>
    </article>
<?php
endwhile;

get_footer();
?>
