<?php
/*
  Plugin Name: Project Custom Post Type
  Plugin URI: http://wordpress.org/plugins/project-custom-post-type/
  Description: A project custom post type
  Version: 1.0
  Author: Oscar Ciutat
  Author URI: http://oscarciutat.com/code
  Text Domain: project-custom-post-type
  License: GPLv2 or later

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Project_Custom_Post_Type {

	/**
	 * Plugin instance.
	 *
	 * @since 1.0
	 *
	 */
	protected static $instance = null;


	/**
	 * Access this plugin’s working instance
	 *
	 * @since 1.0
	 *
	 */
	public static function get_instance() {
		
		if ( !self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	
	/**
	 * Used for regular plugin work.
	 *
	 * @since 1.0
	 *
	 */
	public function plugin_setup() {

  		$this->includes();

		add_action( 'init', array( $this, 'load_language' ) );
		add_action( 'init', array( $this, 'register_custom_type' ) );
		add_action( 'manage_project_posts_custom_column', array( $this, 'project_posts_custom_column' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'project_thumbnail', array( $this, 'project_thumbnail' ) );
		add_action( 'project_attachments', array( $this, 'project_attachments' ), 10, 2 );

		add_filter( 'manage_project_posts_columns', array( $this, 'project_posts_columns' ) );
		add_filter( 'template_include', array( $this, 'template_include' ) );
	
	}

	
	/**
	 * Constructor. Intentionally left empty and public.
	 *
	 * @since 1.0
	 *
	 */
	public function __construct() {}
	
	
 	/**
	 * Includes required core files used in admin and on the frontend.
	 *
	 * @since 1.0
	 *
	 */
	protected function includes() {}


	/**
	 * Loads language
	 *
	 * @since 1.0
	 *
	 */
	function load_language() {
		load_plugin_textdomain( 'project-custom-post-type', '', dirname(plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	
	/*
	* register_custom_type  
	*/
	function register_custom_type() {

		$labels = array(
			'name' => __( 'Projects', 'project-custom-post-type' ),
			'singular_name' => __( 'Project', 'project-custom-post-type' ),
			'add_new' => __( 'Add New Project', 'project-custom-post-type' ),
			'add_new_item' => __( 'Add New Project', 'project-custom-post-type' ),
			'edit_item' => __( 'Edit Project', 'project-custom-post-type' ),
			'new_item' => __( 'New Project', 'project-custom-post-type' ),
			'view_item' => __( 'View Project', 'project-custom-post-type' ),
			'search_items' => __( 'Search Projects', 'project-custom-post-type' ),
			'not_found' => __( 'No Projects found', 'project-custom-post-type' ),
			'not_found_in_trash' => __( 'No Projects found in Trash', 'project-custom-post-type' )
		);
      
		$args = array(
			'show_ui' => true,
			'public' => true,
			'labels' => $labels,
			'menu_position' => 5,
			'menu_icon' => 'dashicons-format-gallery',
			'supports' => array( 'title', 'excerpt', 'editor', 'thumbnail' ), 
			'rewrite' => true,
			'has_archive' => 'projects'
		);

		register_post_type( 'project', $args);

		// Categories

		$labels = array(
			'name' => __( 'Categories' ),
			'singular_name' => __( 'Category' ),
			'add_new_item' => __( 'Add New Category' ),
			'edit_item' => __( 'Edit Category' ),
			'new_item_name' => __( 'New Category' ),
			'search_items' => __( 'Search Categories' ),
			'all_items' => __( 'All Categories' ),
			'popular_items' => __( 'Popular Categories' )
		);
		  
		$args = array(
			'show_ui' => true,
			'public' => true,
			'labels' => $labels,
			'hierarchical' => true,
			'show_admin_column' => true
		);    
	  
		register_taxonomy( 'project_category', 'project', $args);

		// Tags

		$labels = array(
			'name' => __( 'Tags' ),
			'singular_name' => __( 'Tag' ),
			'add_new_item' => __( 'Add New Tag' ),
			'edit_item' => __( 'Edit Tag' ),
			'new_item_name' => __( 'New Tag' ),
			'search_items' => __( 'Search Tags' ),
			'all_items' => __( 'All Tags' ),
			'popular_items' => __( 'Popular Tags' )
		);
		  
		$args = array(
			'show_ui' => true,
			'public' => true,
			'labels' => $labels,
			'hierarchical' => false,
			'show_admin_column' => true
		);    
	  
		register_taxonomy( 'project_tag', 'project', $args);

	} 


	/*
	 * enqueue_scripts 
	 */
	function enqueue_scripts() {
		// Load lightGallery stylesheet.
		wp_enqueue_style( 'lightgallery', plugins_url( '/css/lightgallery.css', __FILE__ ), array(), false );

		// Load plugin stylesheet.
		wp_enqueue_style( 'pcpt-style', plugins_url( '/style.css', __FILE__ ) );
		
		// Load scripts.
		wp_enqueue_script( 'lightgallery-script', plugins_url( '/js/lightgallery.min.js', __FILE__ ), array( 'jquery' ), false, true );

		wp_enqueue_script( 'pcpt-script', plugins_url( '/js/frontend.js', __FILE__ ), array( 'jquery', 'masonry', 'lightgallery-script' ), false, true );

	}


	/**
	 * project_posts_columns
	 */
	function project_posts_columns( $columns ) {
		$new = array();
		foreach ( $columns as $key => $value ) {
			if ( $key == 'title' ) {
				// Put the columns before the Date column
				$new['thumbnail'] = __( 'Cover', 'project-custom-post-type' );
			}
			$new[$key] = $value;
		}
		return $new;
	}


	/**
	 * project_posts_custom_column
	 */
	function project_posts_custom_column( $column ) {
		global $post;
		switch ( $column ) {
			case 'thumbnail':
				// image from gallery
				$args = array(
					'post_parent' => $post->ID, 
					'post_type' => 'attachment',
					'post_mime_type' => 'image'
				);
				$attachments = get_children( $args );
				if ( has_post_thumbnail( $post->ID ) ) {
					$thumb = get_the_post_thumbnail( $post->ID, array( 100, 100 ) );
				} elseif ( $attachments ) {
					foreach ( $attachments as $attachment_id => $attachment ) {
						$thumb = wp_get_attachment_image( $attachment_id, array( 100, 100 ), true );
					}
				}
				if ( isset( $thumb ) && $thumb ) {
					echo $thumb;
				} else {
					echo __( 'None', 'project-custom-post-type' );
				}
				break;	
		}
	}


	/**
	 * project_thumbnail
	 */
	function project_thumbnail( $size ) {
		global $_wp_additional_image_sizes;

		if ( has_post_thumbnail( get_the_ID() ) ) {
			$image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $size );
			$html = '<a href="' . get_permalink() . '">';                
			$html .= '<img src="' . $image_attributes[0] . '" width="' . $image_attributes[1] . '" height="' . $image_attributes[2] . '" alt="' . get_the_title() . '" />';
			$html .= '</a>';
		} else {
			$args = array(
				'post_type' => 'attachment',
				'numberposts' => null,    
				'post_status' => null,
				'post_parent' => get_the_ID()
			);
			$attachments = get_posts( $args );
			if ( $attachments) {
				foreach ( $attachments as $attachment ) {
					$image_attributes = wp_get_attachment_image_src( $attachment->ID, $size );
					$html = '<a href="' . get_permalink() . '">';                
					$html = '<img src="' . $image_attributes[0] . '" width="' . $image_attributes[1] . '" alt="" />';
					$html .= '</a>';
				}
			} else {
				if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) && in_array( $size, array_keys( $_wp_additional_image_sizes ) ) ) {
					$width = $_wp_additional_image_sizes[$size]['width'];
					$height = $_wp_additional_image_sizes[$size]['height'];
				} else {
					$width = get_option( $size. '_size_w' );
					$height = get_option( $size. '_size_h' );
				} 
				$html = '<a href="' . get_permalink() . '">';                
				$html .= '<img src="' . plugins_url( 'assets/images/placeholder.png', __FILE__ ) . '" width="' . $width . 
						 '" height="' . $height . '" alt="' . get_the_title() . '" />';
				$html .= '</a>';
			}
		}    
		echo $html;

	}

	
	/**
	 * project_attachments
	 */
	function project_attachments( $id, $size ) {
	 
		$url_thumb = get_post_thumbnail_id( $id );
				
		$args = array(
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
			'post_type'      => 'attachment',
			'post_parent'    => $id,
			'post_mime_type' => 'image',
			'post_status'    => null,
			'numberposts'    => -1,
		);
		$attachments = get_posts($args);
		if ( $attachments ) {
			foreach ( $attachments as $attachment ) {

				if ( $attachment->ID <> $url_thumb ) {
					$image_attributes = wp_get_attachment_image_src( $attachment->ID, $size );
					$image_attributes_full = wp_get_attachment_image_src( $attachment->ID, 'full' );
					echo '<div class="project-image">';
					echo '<a href="' . $image_attributes_full[0] . '">';                
					echo '<img src="' . $image_attributes[0] . '" width="' . $image_attributes[1] . '" height="' . $image_attributes[2] . '" alt="" />';
					echo '</a>';
					echo '</div>';
				}

			}
		}
		
	}
	

	/**
	 * template_include
	 */
	function template_include( $template ) {
		global $post;

		if ( is_post_type_archive( 'project' ) ) {
			if ( $file = locate_template( array( 'archive-project.php' ) ) ) {
				$template = $file;
			} else {
				$template = plugin_dir_path( __FILE__ ) . '/templates/archive-project.php';
			}
		}
		if ( is_singular( 'project' ) ) {
			if ( $file = locate_template( array( 'single-project.php' ) ) ) {
				$template = $file;
			} else {
				$template = plugin_dir_path( __FILE__ ) . '/templates/single-project.php';
			}
		}

		return $template;
	}


	/**
	 * get_template_part
	 */
	function get_template_part( $slug, $name = null ) {
		$templates = array();
		$name = (string) $name;
		if ( '' !== $name ) {
			$templates[] = "{$slug}-{$name}.php";
		}
		
		$templates[] = "{$slug}.php";
		
		$this->locate_template( $templates, true, false );
	}


	/**
	 * locate_template
	 */
	function locate_template( $template_names, $load = false, $require_once = true ) {
		if ( !is_array( $template_names ) ) {
			return '';
		}
	   
		$located = '';
	   
		$ep_plugin_templates_dir = plugin_dir_path( __FILE__ ) . 'templates';
	   
		foreach ( $template_names as $template_name ) {
			if ( !$template_name )
				continue;
			if ( file_exists( STYLESHEETPATH . '/' . $template_name ) ) {
				$located = STYLESHEETPATH . '/' . $template_name;
				break;
			} else if ( file_exists( TEMPLATEPATH . '/' . $template_name ) ) {
				$located = TEMPLATEPATH . '/' . $template_name;
				break;
			} else if ( file_exists( $ep_plugin_templates_dir . '/' . $template_name ) ) {
				$located = $ep_plugin_templates_dir . '/' . $template_name;
				break;
			}
		}
	   
		if ( $load && '' != $located ) {
			load_template( $located, $require_once );
		}
	   
		return $located;
	}

}

add_action( 'plugins_loaded', array ( Project_Custom_Post_Type::get_instance(), 'plugin_setup' ) );

?>