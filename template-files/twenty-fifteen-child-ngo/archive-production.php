<?php
/**
Template Name: Visa teaterproduktioner

 * Template for displaying archive pages of custom post type "concert"
 *
 * @since Twenty Fifteen Child NGO 1.0
 */

get_header();
// Prepare a loop for concert content type
$production_post = array( 'post_type' => 'production', );
$loop = new WP_Query( $production_post );
?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ($loop->have_posts()) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php echo _e('Theater productions', 'twenty-fifteen-child-ngo' ); ?>: </h1>
			</header><!-- .page-header -->

			<?php
				while ( $loop->have_posts() ) : $loop->the_post();

				/*
				 * Instead of including a content version we have pasted it's content here. Less files to administer.
				 * Start of content-file
				*/
			?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<header class="entry-header">
						<?php
							the_title( sprintf( '<h2 class="entry-title" style="padding-top: 20px;"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
						?>
					</header><!-- .entry-header -->
					<div class="entry-content">

						<!--Inserted for production-->
						<!-- Inline block and other css to make image and text to line up won't work with twenty fifteen, so let's make a table-->
						<table style="width: 100%; border: none">
  						<tr style="border: none;">
								<td style="width: 33%; border: none;">
									<?php echo the_post_thumbnail('thumbnail'); ?>
								</td>
								<td style="vertical-align: top; border: none;">
									<?php $firstplaybexp = esc_html( get_post_meta( get_the_ID(), 'ngop_first_performance', true ) );
										if( ! empty($firstplaybexp)){?>
											<div style="padding-bottom: 3px"><b><?php  _e( 'Premiere', 'twenty-fifteen-child-ngo' ); ?>: </b><nobr><?php echo $firstplaybexp; ?></nobr></div>
											<?php }?>
											
											<?php $lastplaybexp = esc_html( get_post_meta( get_the_ID(), 'ngop_last_performance', true ) );
										if(! empty($lastplaybexp)){?>
											<div><b><?php  _e( 'Ends', 'twenty-fifteen-child-ngo' ); ?>: </b><nobr><?php echo $lastplaybexp; ?></nobr></div>
											<?php }?>
											
											<?php $prodperformencesbexp = esc_html( get_post_meta( get_the_ID(), 'ngop_performances', true ) );
										if(! empty($prodperformencesbexp)){?>
											<div><b><?php  _e( 'Performances', 'twenty-fifteen-child-ngo' ); ?>: </b><?php echo $prodperformencesbexp; ?></div>
											<?php }
											
											the_terms( $post->ID, 'production_scenes', '<div><b>' . __('Venues', 'twenty-fifteen-child-ngo') . ': </b> ', ', ', '</div>' );?>
							  </td>
  						</tr>
						</table>
						<?php
						// End insertion for production

							/* translators: %s: Name of current post */
							the_excerpt( sprintf(
								__( 'Continue reading %s', 'twentyfifteen' ),
								the_title( '<span class="screen-reader-text">', '</span>', false )
							) );

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

				// End the loop.
				endwhile;

				// Previous/next page navigation.
				the_posts_pagination( array(
					'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
					'next_text'          => __( 'Next page', 'twentyfifteen' ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
				) );

			// If no content, include the "No posts found" template.
				else :
					get_template_part( 'content', 'none' );
				endif;
				?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>
