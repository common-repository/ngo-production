<?php get_header(); ?>
    <div class="container">
        <div class="row">
            <?php
            $sidebar_location = fw_get_db_customizer_option('sidebar_setting');
            if($sidebar_location === 'left') {get_sidebar();}
            ?>
<!-- Why echo $post->ID here? Echo:s production ID you came from when visiting this taxonomy-->
            <section id="site-content" class="site-content" role="region" aria-labelledby="page-title-<?php echo $post->ID ?>">
				<?php
                do_action("wally_before_post_loop");
                if(have_posts()):
					if( is_tax() ) {
						global $wp_query;
						$term = $wp_query->get_queried_object();
						$title = $term->name;  // Title, IE name of scene
						$desc = $term->description;	// Description of scene	   
					}
					// No loop here, since the only thing we need to loop is custom post types that this taxonomy belongs to.
///                    while(have_posts()): the_post(); 
						do_action("wally_before-post-loop-item"); if(isset($post)):
							$term_slug = get_query_var( 'term' );
							$taxonomyName = get_query_var( 'taxonomy' );
							$current_term = get_term_by( 'slug', $term_slug, $taxonomyName );
							$term_id = $current_term->term_id;
							// Get meta image. Requires Category and Taxonomy Image plugin
							if (function_exists('get_wp_term_image')) {
								$meta_image = get_wp_term_image($term_id); 
								//Gives category/term image url 
							} ?>
							<article <?php post_class('article-box'); ?> aria-labelledby="page-title-<?php echo $term_id; ?>">
							<header class="article-box__header below-figure">
								<h1 id="page-title-<?php echo $term_id; ?>"><?php echo $title; ?></h1>
								<?php // Show Edit in Backend button for logged in Editors and Admins
								if(is_user_logged_in() && current_user_can('edit_posts')):
									$url = get_bloginfo('url').'/wp-admin/term.php?taxonomy=production_scenes&tag_ID='.$term_id;
									?>
									<a href="<?= $url; ?>" class="edit-btn" title="<?php echo $post->post_type === 'production_scenes' ? __('Redigera inlägg', 'wally') : __('Redigera sida', 'wally') ?>">
										<span>
											<?php echo $post->post_type === 'post' ? __('Redigera inlägg', 'wally') : __('Redigera sida', 'wally') ?>
										</span>
									</a>
								<?php endif; ?>
							</header>
							<div class="article-box__content">
								<div class="row">
								<!-- Show taxonomy image -->
									<?php if($meta_image) { ?>
										<div class="pull-left" style="margin-right:2%;">
											<img src="<?php echo $meta_image; ?>" alt="<?php echo $title; ?>" style="width:150px;height:150px;">
										</div>
									<?php } ?>
									<div class="pull-left" style="margin-right:2%;">
										<?php echo wpautop($desc); ?>
									</div>
									<div class="pull-left" style="margin-right:2%;">
									<br /><strong><?php _e( 'Spelas h&auml;r:', 'wally-child-ngo' ); ?></strong><br />
<!-- Loop through all productions that taxonomy is member of.. -->
									<?php while ( have_posts() ) : the_post(); ?>	
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><br />
									<?php endwhile; ?>	
									</div>
								</div>
							</div>
<!-- No Tags for scenes, but we keep for now... 
							<?php if(wp_get_post_tags($post->ID)): ?>
							<div class="article-box__tags">
								<a name="taggar" class="anchor"></a>
								<?php get_template_part('parts/meta/tags'); ?>
							</div>
-->
							<?php endif ?>
<!--	FOOTER, not needed, but we keep.						
							<footer class="article-box__footer">
								<div class="article-box__footer__col article-box__footer__col--meta">
									<time datetime="<?php echo get_the_date('Y-m-d\TH:i:s'); ?>"><?php echo get_the_date('j F, Y H:i') ?></time>
								</div>
								<div class="article-box__footer__col article-box__footer__col--meta">
									<?php _e('Författare', 'wally') ?>:
									<?php echo get_the_author_posts_link(); ?>
								</div>
							</footer>
						</article>
-->
						<?php do_action("wally_after-post-loop-item"); endif;
                    ///endwhile;
                endif;
                do_action("wally_after_post_loop");
                comments_template();
                ?>
            </section>
            <?php if($sidebar_location === 'right') {get_sidebar();} ?>
        </div>
<!-- Removed part "related" since it's of no use here. -->
    </div>
<?php get_footer(); ?>