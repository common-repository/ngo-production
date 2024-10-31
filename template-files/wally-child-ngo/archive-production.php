<?php
 /*
 Template Name: Visa produktioner
 */
get_header();
$production_post = array( 'post_type' => 'production', );
$loop = new WP_Query( $production_post );
?>
  <div class="container">
    <div class="row">
      <?php
        $sidebar_location = fw_get_db_customizer_option('sidebar_setting');
        if($sidebar_location === 'left') {get_sidebar();}
      ?>
      <section id="site-content" class="site-content" role="region" aria-labelledby="page-title-<?php echo $post->ID ?>" data-paginate>
        <?php the_archive_title('<h1 class="page-title" id="page-title-' . $post->ID .'">', '</h1>') ?>
        <h2><?php _e('Teaterproduktioner', 'wally-child-ngo') ?></h2>
        <?php
        do_action("wally_before_post_loop");
				if ($loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post(); ?>

					<?php do_action("wally_before-post-loop-item"); ?>
					<?php
						$display_size = get_post_meta($post->ID, 'thumbnail_size', true);
						$sticky = is_sticky($post->ID) ? ' sticky' : '';
					?>
					<article <?php post_class('article-box'); ?> role="article">

						<div class="article-box__header--below-figure">
							<a href="<?php the_permalink(); ?>"<?php echo $sticky ? ' data-mh="stickies"' : '' ?>>
								<h3 id="post-<?php echo $post->ID ?>"><span><?php the_title(); ?></span></h3>
							</a>
							<?php //Show Edit in Backend button for logged in Editors and Admins
							if(is_user_logged_in() && current_user_can('edit_posts')):
								$url = get_bloginfo('url').'/wp-admin/post.php?post='.$post->ID.'&action=edit';
								?>
								<a href="<?= $url; ?>" class="edit-btn" title="<?php echo $post->post_type === 'post' ? __('Redigera inlägg', 'wally') : __('Redigera sida', 'wally') ?>">
									<span>
										<?php echo $post->post_type === 'post' ? __('Redigera inlägg', 'wally') : __('Redigera sida', 'wally') ?>
									</span>
								</a>
							<?php endif; ?>
						</div>

						<div class="article-box__content">
							<div class="pull-left" style="margin-right:2%;">
								<?php echo the_post_thumbnail('thumbnail'); ?>
							</div>
							<div class="fw-page-builder-content">
								<div class="fw-main-row ">
									<div class="fw-container">
										<div class="fw-row">
											<small>
											<?php $firstplay = esc_html( get_post_meta( get_the_ID(), 'ngop_first_performance', true ) );
											if( ! empty($firstplay)){?>
												<div><?php _e( 'Premi&auml;r: ', 'wally-child-ngo' ); ?><?php echo $firstplay; ?></div>
											<?php }
											$lastplay = esc_html( get_post_meta( get_the_ID(), 'ngop_last_performance', true ) );
											if(! empty($lastplay)){?>
											<div><?php _e( 'Slutar: ', 'wally-child-ngo' ); ?><?php echo $lastplay; ?></div>
											<?php }
											$prodperformences = esc_html( get_post_meta( get_the_ID(), 'ngop_performances', true ) );
											if(! empty($prodperformences)){?>
												<div><?php _e( 'Antal spelningar: ', 'wally-child-ngo' ); ?><?php echo $prodperformences; ?></div>
											<?php }
											the_terms( $post->ID, 'production_scenes', '<div>' . __('Spelas på', 'wally-child-ngo') . ': ', ', ', '</div>' );
											?>
											</small>
											<div class="fw-divider-line"><hr/></div>
										</div>
										<div class="fw-row">
											<?php the_excerpt() ?>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="article-box__footer">
							<?php if(get_post_type($post) == 'post'): ?>
							<div class="article-box__footer__col article-box__footer__col--meta">
								<time datetime="<?php echo get_the_date('Y-m-d\TH:i:s'); ?>"><?php the_date('j F, Y H:i') ?></time>
							</div>
							<div class="article-box__footer__col article-box__footer__col--meta">
								<?php _e('Författare', 'wally') ?>:
								<?php echo get_the_author_posts_link(); ?>
							</div>
							<div class="article-box__footer__col article-box__footer__col--meta article-box__footer__col--meta--comments">
								<a href="<?php the_permalink() ?>#comments"><i class="material-icons" aria-label="<?php _e('Kommentar', 'wally') ?>" aria-hidden="true">insert_comment</i> <?php $comment_count = get_comment_count($post->ID); echo $comment_count['approved']; ?> <?php _e('kommentarer', 'wally') ?></a>				</div>
							<?php endif ?>
						</div>
					</article>
					<?php do_action("wally_after-post-loop-item");
 				endwhile ?>
        <div class="pagination">
					<?php the_posts_pagination(array(
						'prev_text'          => __( 'Föregående sida', 'wally' ),
						'next_text'          => __( 'Nästa sida', 'wally' ),
						'screen_reader_text' => __( 'Sidnavigation' ),
					)) ?>
				</div>

        <?php endif;
        do_action("wally_after_post_loop");
        ?>
      </section>
      <?php if($sidebar_location === 'right') {get_sidebar();} ?>
    </div>
  </div>
<?php get_footer(); ?>