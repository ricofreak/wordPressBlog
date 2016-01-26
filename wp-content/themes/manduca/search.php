<?php
/**
 * Manduca
 *
 * @since 1.0 */

get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main" itemprop="mainContentOfPage" itemscope="itemscope" itemtype="http://schema.org/Blog">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title" itemprop="headline"><?php echo '<span>' . get_search_query() . '</span>'; ?></h1>
			</header>

			<?php manduca_page_navigation(); ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

			<?php manduca_page_navigation(); ?>

		<?php else : ?>

			<article id="post-0" class="post no-results not-found"  itemscope="itemscope" itemtype="http://schema.org/BlogPosting" itemprop="blogPost" >
				<header class="entry-header">
					<h2 class="entry-title" itemprop="headline" ><?php _e( 'No matching result found.', 'manduca' ) ?></h2>
				</header>

				<div class="entry-content">
					<?php get_search_form(); ?>
				</div><!-- .entry-content -->
			</article><!-- #post-0 -->

		<?php endif; ?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>