<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>
	<header id="masthead" class="site-header container" role="banner">
		<div class="site-branding">
			<h1 class="site-title">
				<?php if ( undiscovered_options( 'logotype' ) ) : ?>
					<a class="logotype-img" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo undiscovered_options( 'logotype' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"></a>
				<?php else : ?>
					<a class="logotype-text" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				<?php endif; ?>
			</h1>
		</div>

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'undiscovered' ); ?></a>

			<?php wp_nav_menu(array(
				'theme_location' => 'primary'
			)); ?>

			<?php get_search_form(); ?>
		</nav>

		<div class="site-about">
			<?php if(is_search()): ?>
				<h2 class="site-description"><?php printf( __( 'Search Results for: %s', 'undiscovered' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
			<?php elseif(is_archive()): ?>
				<h2 class="site-description">
					<?php
						if ( is_category() ) :
							single_cat_title();

						elseif ( is_tag() ) :
							single_tag_title();

						elseif ( is_author() ) :
							printf( __( 'Author: %s', 'undiscovered' ), '<span class="vcard">' . get_the_author() . '</span>' );

						elseif ( is_day() ) :
							printf( __( 'Day: %s', 'undiscovered' ), '<span>' . get_the_date() . '</span>' );

						elseif ( is_month() ) :
							printf( __( 'Month: %s', 'undiscovered' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'undiscovered' ) ) . '</span>' );

						elseif ( is_year() ) :
							printf( __( 'Year: %s', 'undiscovered' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'undiscovered' ) ) . '</span>' );

						elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
							_e( 'Asides', 'undiscovered' );

						elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
							_e( 'Galleries', 'undiscovered');

						elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
							_e( 'Images', 'undiscovered');

						elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
							_e( 'Videos', 'undiscovered' );

						elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
							_e( 'Quotes', 'undiscovered' );

						elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
							_e( 'Links', 'undiscovered' );

						elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
							_e( 'Statuses', 'undiscovered' );

						elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
							_e( 'Audios', 'undiscovered' );

						elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
							_e( 'Chats', 'undiscovered' );

						else :
							_e( 'Archives', 'undiscovered' );

						endif;
					?>
				</h2>
			<?php elseif(is_404()): ?>
				<h2 class="site-description"><?php _e('Error 404 - Not Found', 'undiscovered') ?></h2>
			<?php else: ?>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			<?php endif; ?>
		</div>
	</header>

	<div id="content" class="site-content container">
