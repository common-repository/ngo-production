<?php
// Visa produktion, Show production
get_header();
while ( have_posts() ) : the_post(); ?>
	<div class="container">
     <div class="row">
			<section id="site-content" class="main" id="main" role="region" aria-labelledby="page-title-<?php echo $post->ID ?>">
	      <div <?php post_class('article-box') ?> id="post-<?php the_ID(); ?>">
          <header class="article-box__header">
            <?php do_action('page-before-title'); ?>
            <h1 id="page-title-<?php echo $post->ID ?>"><?php the_title(); ?></h1>
            <?php do_action('page-after-title'); ?>
						<?php // Show Edit in Backend button for logged in Editors and Admins
						if(is_user_logged_in() && current_user_can('edit_posts')):
							$url = get_bloginfo('url').'/wp-admin/post.php?post='.$post->ID.'&action=edit';
							?>
							<a href="<?= $url; ?>" class="edit-btn" title="<?php echo $post->post_type === 'production' ? __('Redigera inlägg', 'wally') : __('Redigera sida', 'wally') ?>">
								<span>
									<?php echo $post->post_type === 'post' ? __('Redigera inlägg', 'wally') : __('Redigera sida', 'wally') ?>
								</span>
							</a>
						<?php endif; ?>
					</header>
          <div class="article-box__content">
            <div class="row">
							<?php if(has_post_thumbnail()){?>
							<div class="pull-left">
								<?php echo the_post_thumbnail('thumbnail'); ?>
							</div>
							<?php }
							do_action('page-before-content'); ?>
							<small>
							<?php $firstplay = esc_html( get_post_meta( get_the_ID(), 'ngop_first_performance', true ) );
							if( ! empty($firstplay)){?>
								<div class="pull-left" style="margin-left:3%;" >
									<b><?php _e( 'Premi&auml;r: ', 'wally-child-ngo' ); ?></b><?php echo wpautop($firstplay); ?>
								</div>
							<?php }
							$lastplay = esc_html( get_post_meta( get_the_ID(), 'ngop_last_performance', true ) );
              if(! empty($lastplay)){?>
								<div class="pull-left" style="margin-left:3%">
									<b><?php _e( 'Slutar: ', 'wally-child-ngo' ); ?></b><?php echo wpautop($lastplay); ?>
								</div>
							<?php }
							$prodperformences = esc_html( get_post_meta( get_the_ID(), 'ngop_performances', true ) );
              if(! empty($prodperformences)){?>
								<div class="pull-left" style="margin-left:3%;">
									<b><?php _e( 'Antal spelningar: ', 'wally-child-ngo' ); ?></b><?php echo wpautop($prodperformences); ?>
								</div>
							<?php } ?>
							
							<div class="pull-left" style="margin-left:3%;">
							<?php the_terms( $post->ID, 'production_scenes', '<b>' . __('Spelas på', 'wally-child-ngo') . ': </b><br />', ' <br /> ', '<br />' ); ?>
							</div>

							<div class="pull-left" style="margin-left:3%;">
								<?php the_terms($post->ID, 'production_category', '<b>' . __('Genre', 'wally-child-ngo') . ':</b> <br/>', '<br/>'); ?>
							</div>
							
							<?php $prodmetainfo = esc_html( get_post_meta( get_the_ID(), 'ngop_meta_info', true ) );
              if(! empty($prodmetainfo)){?>
								<div class="pull-left" style="margin-left:3%;">
									<b><?php _e( '&Ouml;vriga medverkande: ', 'wally-child-ngo' ); ?></b>
									<?php echo wpautop($prodmetainfo); ?>
								</div>
							<?php } ?>
							</small>
						</div><!--end class=row-->
					</div>
					<div class="article-box__content" >
						<div class="row">
							<?php 
							echo the_content();
							?>
							<div class="pull-right" style="margin-left:3%;">
								<?php // List actors
								the_terms( $post->ID, 'production_actors', '<b>' . __('Skådespelare', 'wally-child-ngo') . ': </b><br />', ' <br /> ', '<br />' );
								?>
							</div>
						</div>
					</div>
					<?php if(get_post_meta(get_the_ID(),'production_ticket_url', true)) { ?>		
            <div class="article-box__content" style="background:lightgrey;">
							<div class="row">
								<div class="pull-left" style="margin-left:5%">
									<strong><?php _e( 'K&ouml;p biljetter h&auml;r: ', 'wally-child-ngo' ); ?></strong><a href="<?php echo esc_html( get_post_meta( get_the_ID(), 'production_ticket_url', true ) ); ?>" target="_blank"><?php echo esc_html( get_post_meta( get_the_ID(), 'production_ticket_url', true ) ); ?></a>
								</div>
							</div>
            </div>
					<?php } ?>
          <div class="article-box__footer">
						<div class="article-box__footer__col article-box__footer__col--meta">
							<time datetime="<?php echo get_the_date('Y-m-d\TH:i:s'); ?>"><?php the_date('j F, Y H:i') ?></time>
						</div>
						<div class="article-box__footer__col article-box__footer__col--meta">
							<?php _e('Författare', 'wally') ?>:
							<?php echo get_the_author_posts_link(); ?>
						</div>
            <div class="article-box__footer__col article-box__footer__col--meta article-box__footer__col--meta--comments">
							<a href="<?php the_permalink() ?>#comments"><i class="material-icons" aria-label="<?php _e('Kommentar', 'wally') ?>" aria-hidden="true">insert_comment</i> <?php $comment_count = get_comment_count($post->ID); echo $comment_count['approved']; ?> <?php _e('kommentarer', 'wally') ?></a>
						</div>
				  </div>
					<?php do_action('page-after-content');
					?>
				</div><!--end class=article-box-->
				<?php if( function_exists( 'adrotate_group' ) ) { echo "&nbsp;" . adrotate_group(4); } ?>
			</section>
		</div> <!-- end class row -->
	</div><!--end class=container -->
<?php endwhile ?>
<?php get_footer(); ?>