<?php
/**
 * Page for displaying taxonomy production musicians
 *
 * @since Twenty Fifteen Child NGO 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php if( have_posts() ) {
				if(is_tax()){
					global $wp_query;
					$term = $wp_query->get_queried_object();
					$title = $term->name;  // Title, IE name of musicians
					$desc = $term->description;	// Description of musicians
				}
			}
			if(isset($post)):
				$term_slug = get_query_var( 'term' );
				$taxonomyName = get_query_var( 'taxonomy' );
				$current_term = get_term_by( 'slug', $term_slug, $taxonomyName );
				$term_id = $current_term->term_id;

				if(function_exists('get_wp_term_image')){
					$meta_image = get_wp_term_image($term_id);
				} ?>

				<!-- content.php included in this file for easy maintenance -->
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<h1 class="entry-title"style="padding-top: 20px;">
						<?php echo $title; ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<!--Inserted for production-->
						<!-- Inline block and other css to make image and text to line up won't work with twenty fifteen, so let's make a table-->
						<table style="width: 100%; border: none">
							<tr style="border: none;">
								<td style="width: 33%; border: none;">
									<!-- Show taxonomy image -->
									<?php if($meta_image) { ?>
										<img src="<?php echo $meta_image; ?>" alt="<?php echo $title; ?>" style="width:150px;height:150px;">
									<?php } ?>
								</td>
								<td style="vertical-align: top; border: none;">
									<strong><?php _e( 'Performs in', 'twenty-fifteen-child-ngo' ); ?>:</strong><br />
									<?php	while ( have_posts() ) : the_post(); ?>
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><br />
									<?php endwhile;
									echo "<br />" . wpautop($desc); ?>
								</td>
							</tr>
						</table><?php
							wp_link_pages( array(
								'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfifteen' ) . '</span>',
								'after'       => '</div>',
								'link_before' => '<span>',
								'link_after'  => '</span>',
								'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>%',
								'separator'   => '<span class="screen-reader-text">, </span>',
							) );
						?>
					</div><!-- .entry-content -->

					<?php
						// Author bio.
						if ( is_single() && get_the_author_meta( 'description' ) ) :
							get_template_part( 'author-bio' );
						endif;
					?>

					<footer class="entry-footer">
						<?php twentyfifteen_entry_meta(); ?>
						<?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<span class="edit-link">', '</span>' ); ?>
					</footer><!-- .entry-footer -->
				</article><!-- #post-## -->

				<?php
				// Previous/next page navigation.
				the_posts_pagination( array(
					'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
					'next_text'          => __( 'Next page', 'twentyfifteen' ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
				) );

			// If no content, include the "No posts found" template.
			else :
				get_template_part( 'content', 'none' );
			endif; ?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
