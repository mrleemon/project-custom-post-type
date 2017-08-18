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
         
		<ul class="projects list">
		
		<?php foreach ( $posts as $post ) : ?>
		
			<?php $title = ( $post->post_excerpt ) ? $post->post_excerpt : $post->post_title; ?>
			
			<li class="project"><a href="<?php the_permalink( $post->ID ); ?>" title="<?php echo esc_attr( $title ); ?>"><span class="project-title"><?php echo $title; ?></span></a></li>
			
		<?php endforeach; ?>  

		</ul>

	<?php endif; ?>

</div>