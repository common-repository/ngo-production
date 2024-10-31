<?php
/**
 * Template for displaying content from custom post type "production"
 *
 * @since Twenty Fifteen Child NGO 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


<br/><br/>
		<header class="entry-header">
			<?php
					the_title( '<h1 class="entry-title">', '</h1>' );
			?>		
		</header><!-- .entry-header -->
	
	<div class="entry-content">

		<!-- Inline block and other css to make image and text to line up doesn't work with twenty fifteen, so let's make a table-->
		<table style="width: 100%; border: none">
  		<tr style="border: none;">
				<td style="width: 30%; border: none;">
					<?php
						echo the_post_thumbnail('thumbnail');
					?>
				</td>
				<td style="width: 35%; vertical-align: top; border: none;">

					<small>
						<?php $firstplaybexp = esc_html( get_post_meta( get_the_ID(), 'ngop_first_performance', true ) );?>
						<?php if( ! empty($firstplaybexp)){?>
							<div>
								<b><?php _e( 'Premiere', 'twenty-fifteen-child-ngo' ); ?>: </b><nobr><?php echo $firstplaybexp; ?></nobr>
							</div>
						<?php } ?>
						
						<?php $lastplaybexp = esc_html( get_post_meta( get_the_ID(), 'ngop_last_performance', true ) ); ?>
						 <?php if(! empty($lastplaybexp)){?>
							<div>
								<b><?php _e( 'Last staging', 'twenty-fifteen-child-ngo' ); ?>: </b><nobr><?php echo $lastplaybexp; ?></nobr>
							</div>
						<?php } ?>
						
						<?php $prodperformbex = esc_html( get_post_meta( get_the_ID(), 'ngop_performances', true ) );?>
						<?php if(! empty($prodperformbex)){ ?>
							<div>
								<b><?php _e( 'Performances', 'twenty-fifteen-child-ngo' ); ?>: </b><?php echo $prodperformbex; ?>
							</div>
						<?php } ?>
						<div>
							<?php the_terms($post->ID, 'production_category', '<b>' . __('Category', 'twenty-fifteen-child-ngo') . ':</b><br/>', '<br/>'); ?>
						</div>
						<div>
							<?php the_terms($post->ID, 'production_scenes', '<b>' . __('Venues', 'twenty-fifteen-child-ngo') . ':</b><br/>', '<br/>'); ?>
						</div><br/>
					</small>
				</td>

				<td style="width: 35%; vertical-align: top; border: none;">
					<small>	
						<div>
							<?php the_terms($post->ID, 'production_actors', '<b>' . __('Actors', 'twenty-fifteen-child-ngo') . ':</b> <br/>', '<br/>'); ?>
						</div>
		
						<?php $prodmetainfobex = esc_html( get_post_meta( get_the_ID(), 'ngop_meta_info', true ) ); ?>
						<?php if(! empty($prodmetainfobex)){?>
							<div>
								<b><?php _e( 'Other coworkers', 'twenty-fifteen-child-ngo' ); ?>:</b><br/><?php echo wpautop( $prodmetainfobex ); ?>
							</div>
						<?php } ?>
		
						<?php $prodticketbex = esc_html( get_post_meta( get_the_ID(), 'ngop_ticket_url', true ) );?>
						<?php if(! empty($prodticketbex)){ ?>
							<div style="background:lightgrey;">
								<b><?php _e( 'Buy tickets here', 'twenty-fifteen-child-ngo' ); ?>: </b><br/><a href="<?php echo $prodticketbex; ?>" target="_blank"><?php echo $prodticketbex;?></a>
							</div>
						<?php	} ?>
					</small>
				</td>
  		</tr>
		</table>
		
		<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
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
