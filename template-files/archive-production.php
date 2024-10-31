<?php
/*
Template Name: Visa produktioner
*/

get_header();
$production_post = array( 'post_type' => 'production', );
$loop = new WP_Query( $production_post );
?>

<div id="main-content" class="content-area">
	<?php if ($loop->have_posts()) : ?>
		<h1 class="archive-header"><?php echo _e( 'Theater productions', 'beyond-expectations-child-ngo' ); ?>: </h1>
		<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
			<?php //get_template_part('template-parts/content', 'archive'); ?>
			<article id="post-<?php $post->ID; ?>" <?php post_class('cf'); ?>>
				<header class="entry-header">
					<?php the_title(sprintf('<h2 class="entry-title"><a href="%s">', esc_url(get_permalink())), '</a></h2>'); ?><br/>
				</header>

				<div class="entry-content-container">
					<div class="entry-metadata">
						<?php //beyond_expectations_entry_posted_on(); ?>
						<?php //echo the_post_thumbnail('thumbnail'); ?>
					</div>

					<div class="entry-content">
						<div class="fw-page-builder-content">
							<div class="fw-main-row ">
								<div class="fw-container">
									<div class="fw-row">
										<div class="pull-left" style="padding-right: 15px;">
											<?php echo the_post_thumbnail('thumbnail'); ?>
										</div>
										<?php $firstplaybexp = esc_html( get_post_meta( get_the_ID(), 'ngop_first_performance', true ) );
										if( ! empty($firstplaybexp)){?>
											<div style="padding-bottom: 3px"><b><?php  _e( 'Premiere', 'beyond-expectations-child-ngo' ); ?>: </b><?php echo $firstplaybexp; ?></div>
										<?php }?>
										<?php $lastplaybexp = esc_html( get_post_meta( get_the_ID(), 'ngop_last_performance', true ) );
										if(! empty($lastplaybexp)){?>
											<div style="padding-bottom: 3px"><b><?php  _e( 'Ends', 'beyond-expectations-child-ngo' ); ?>: </b><?php echo $lastplaybexp; ?></div>
										<?php }?>
										<?php $prodperformencesbexp = esc_html( get_post_meta( get_the_ID(), 'ngop_performances', true ) );
										if(! empty($prodperformencesbexp)){?>
											<div style="padding-bottom: 3px"><b><?php  _e( 'Number of performances', 'beyond-expectations-child-ngo' ); ?>: </b><?php echo $prodperformencesbexp; ?></div>
										<?php } ?>
										<?php the_terms( $post->ID, 'production_scenes', '<div style="padding-bottom: 3px"><b>' . __('Venues', 'beyond-expectations-child-ngo') . ': </b> ', ', ', '</div>' );?>
										<div><?php the_excerpt() ?></div>
									</div>
								</div>
							</div>
						</div>
						<!-- <button type="buttom" class="btn btn-default">
							<a href="<?php echo get_permalink(); ?>"><?php _e('Read More', 'beyond-expectations'); ?></a>
						</button> -->
						<?php wp_link_pages(); ?>
						<?php beyond_expectations_entry_taxonomies(); ?>
					</div>
				</div> <!--end entry-content-container-->
			</article>
			<hr align="left" size="2"/>

	 <?php endwhile; ?>
	 <?php beyond_expectations_paging_navigation_setup(); ?>
	 <?php else : ?>
		<?php get_template_part('template-parts/content', 'none'); ?>
	<?php endif; ?>
</div>
<div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>