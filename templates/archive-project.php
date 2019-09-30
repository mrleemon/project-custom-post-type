<?php
/**
 * The Template for displaying project archives.
 *
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

        <?php if ( have_posts() ) : ?>

            <header class="page-header">
                <h1 class="page-title"></h1>
            </header><!-- .page-header -->

            <div class="projects thumbnails">
            
                <?php /* Start the Loop */ ?>
                <?php while ( have_posts() ) : the_post(); ?>

                    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <?php 
                        do_action( 'project_thumbnail', 'thumbnail' );
                    ?>
                    <p>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" ><?php the_title(); ?></a>
                    </p>
                    </div><!-- #post -->

                <?php endwhile; ?>
                
            </div><!-- .projects -->

            <?php the_posts_pagination(); ?>

        <?php endif; ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>