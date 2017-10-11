<?php

/**
 * The Template for displaying all single projects.
 *
 */

get_header(); ?>

	<div id="primary" class="content-area">

		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php echo the_title(); ?></h1>
					<a class="toggle-content" href="#">
					[<span class="toggled-off">+</span><span class="toggled-on">-</span>
					<?php _e( 'info', 'project-custom-post-type' ); ?>]
					</a>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<?php the_content(); ?>
				</div><!-- .entry-content -->

				<div class="entry-gallery">
					<?php do_action( 'project_attachments', get_the_ID(), 'medium' ); ?>
					<div class="loading"></div>
				</div><!-- .entry-gallery -->

				<?php
					// Previous/next post navigation.
					the_post_navigation( array(
						'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next Project >', 'project-custom-post-type' ) . '</span> ' .
							'<span class="screen-reader-text">' . __( 'Next Project:', 'project-custom-post-type' ) . '</span> ' .
							'<span class="post-title">%title</span>',
						'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous Project >', 'project-custom-post-type' ) . '</span> ' .
							'<span class="screen-reader-text">' . __( 'Previous Project:', 'project-custom-post-type' ) . '</span> ' .
							'<span class="post-title">%title</span>',
					) );
				?>

			</article><!-- #post-## -->

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
		
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>