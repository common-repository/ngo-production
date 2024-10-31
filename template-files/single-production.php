<?php get_header(); ?>
<div id="main-content" class="content-area">
	<?php if ( have_posts()) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?>>
			<header class="entry-header">
				<?php the_title(sprintf('<h1 class="entry-title"><a href="%s">', esc_url( get_permalink() ) ), '</a></h1>'); ?>
			</header>
			<?php if (comments_open()) { ?>
				<div class="entry-content-container">
					<div class="entry-metadata">
						<div class="pull-left" style="padding-right: 15px;">
							<?php echo the_post_thumbnail('thumbnail'); ?>
						</div>
							<?php beyond_expectations_entry_meta(); ?>
					</div>
					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages(); ?>
						<?php beyond_expectations_entry_taxonomies(); ?>
					</div>
				</div>
			<?php } else { ?>
				<div class="entry-content-container">
					<div class="entry-metadata">
						<div class="pull-left" style="padding-right: 15px;">
							<?php echo the_post_thumbnail('thumbnail'); ?>
						</div>
					</div>
					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages(); ?>
						<?php beyond_expectations_entry_taxonomies(); ?>
					</div>
				</div>
					<?php } ?>
			</article>
					<?php comments_template(); ?>

				<?php endwhile; ?>
				<?php else : ?>
								<?php get_template_part('template-parts/content', 'none'); ?>
				<?php endif; ?>
</div>
<div class="entry-info">
	<div style="margin-left:3%;">
		<p><strong><?php _e( 'Information', 'beyond-expectations-child-ngo' ); ?>: </strong></p><br/>
	</div>
	<small>
	<?php $firstplaybexps = esc_html( get_post_meta( get_the_ID(), 'ngop_first_performance', true ) );?>
	<?php if( ! empty($firstplaybexps)){?>
		<div style="margin-left:3%;padding-bottom: 5px";>
			<b><?php _e( 'Premiere', 'beyond-expectations-child-ngo' ); ?>: </b><?php echo wpautop( $firstplaybexps ); ?>
		</div>
	<?php } ?>
	<?php $lastplaybexps = esc_html( get_post_meta( get_the_ID(), 'ngop_last_performance', true ) ); ?>
	 <?php if(! empty($lastplaybexps)){?>
		<div style="margin-left:3%;">
			<b><?php _e( 'Last performance', 'beyond-expectations-child-ngo' ); ?>: </b><?php echo wpautop( $lastplaybexps ); ?>
		</div>
	<?php } ?>
	<?php $prodperformbex = esc_html( get_post_meta( get_the_ID(), 'ngop_performances', true ) );?>
	<?php if(! empty($prodperformbex)){ ?>
		<div style="margin-left: 3%;">
			<br/><b><?php _e( 'Number of performances', 'beyond-expectations-child-ngo' ); ?>: </b><?php echo wpautop( $prodperformbex ); ?>
		</div>
	<?php } ?>
	<div style="margin-left:3%;">
		<?php the_terms($post->ID, 'production_scenes', '<b>' . __('Venues', 'beyond-expectations-child-ngo') . ':</b><br/>', '<br/>'); ?>
	</div><br/>
	<div style="margin-left: 3%;">
		<?php the_terms($post->ID, 'production_category', '<b>' . __('Category', 'beyond-expectations-child-ngo') . ':</b><br/>', '<br/>'); ?>
	</div>
	<br/>
	<div style="margin-left:3%;">
		<?php the_terms($post->ID, 'production_actors', '<b>' . __('Actors', 'beyond-expectations-child-ngo') . ':</b> <br/>', '<br/>'); ?>
	</div>
	<br/>
	<?php $prodmetainfobex = esc_html( get_post_meta( get_the_ID(), 'ngop_meta_info', true ) ); ?>
	<?php if(! empty($prodmetainfobex)){?>
		<div style="margin-left:3%;">
			<b><?php _e( 'Other coworkers', 'beyond-expectations-child-ngo' ); ?>:</b><br/><?php echo wpautop( $prodmetainfobex ); ?>
		</div>
	<?php } ?>
	<?php $prodticketbex = esc_html( get_post_meta( get_the_ID(), 'ngop_ticket_url', true ) );?>
	<?php if(! empty($prodticketbex)){ ?>
		<br/><div style="margin-left:3%;background:lightgrey;">
			<b><?php _e( 'Buy tickets here', 'beyond-expectations-child-ngo' ); ?>: </b><br/><a href="<?php echo $prodticketbex; ?>" target="_blank"><?php echo $prodticketbex;?></a>
		</div>
	<?php	} ?>
	</small>
<?php if( function_exists( 'adrotate_group' ) ) { echo "&nbsp;" . adrotate_group(3); } ?>
</div>

<?php get_footer(); ?>
