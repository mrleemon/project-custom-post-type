<div class="projectindex list">

<?php
	$args = array(
        'numberposts'      => -1,
		'orderby'          => 'date',
        'order'            => 'DESC',
        'post_type'        => 'project',
		'post_status'	   => 'publish',
		'suppress_filters' => '0' );
		
	$posts = get_posts( $args ); 
?>

	<?php if ( $posts ) : ?>

		<div class="projects thumbnails">
		
		<?php foreach ( $posts as $post ) : ?>
			
			<div id="post-<?php echo $post->ID; ?>" <?php echo post_class( $post->ID ); ?>>
			
				<a href="<?php the_permalink( $post->ID ); ?>" title="<?php the_title_attribute( $post->ID ); ?>" >
				<?php 
					do_action( 'project_thumbnail', 'thumbnail' );
				?>
				</a>
				<p>
				<a href="<?php the_permalink( $post->ID ); ?>" title="<?php the_title_attribute( $post->ID ); ?>" ><?php the_title( $post->ID ); ?></a>
				</p>
        
			</div><!-- #post -->
			
		<?php endforeach; ?>

		</div>
	
	<?php endif; ?>

</div>