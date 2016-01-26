<?php
/**
 * Manduca
 *
 * @since 1.0 */

if ( ! isset( $content_width ) ) {
	$content_width = 625;
}

//-------------------------------------------------------------------------------------------
// Manduca setup
//-------------------------------------------------------------------------------------------
function manduca_setup() {
	
	// Styles the visual editor with editor-style.css
	add_editor_style();
	
	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );
	
	// Switch default core markup to output valid HTML5.
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'caption'
	) );
	//Allows themes to add document title tag
	add_theme_support( 'title-tag' );
	
	//Register navigation menus
	register_nav_menu( 'primary', __( 'Main navigation', 'manduca' ) );
	register_nav_menu( 'footer', __( 'Footer navigation', 'manduca' ) );
	
	//Makes translation-ready
	load_theme_textdomain( 'manduca', get_template_directory() . '/lang' );
	
	 //Supports custom background color and image
	add_theme_support( 'custom-background', array(
		'default-color' => 'f3f3f5',
	) );

	// Uses a custom image size for featured images
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop
}
add_action( 'after_setup_theme', 'manduca_setup' );

require( get_template_directory() . '/inc/custom-header.php' );

//-------------------------------------------------------------------------------------------
// Manduca Scripts
//-------------------------------------------------------------------------------------------
function manduca_scripts_styles() {
	global $wp_styles;


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Loads mobile menu scripts
	wp_enqueue_script( 'manduca-navigation', get_template_directory_uri() . '/js/navigation.js', array( 'jquery' ), '20140711', true );

	// Loads our main stylesheet.
	wp_enqueue_style( 'manduca-style', get_stylesheet_uri() );
	
	//Loads Font Awesome
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css' );

	// Loads the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'manduca-ie', get_template_directory_uri() . '/css/ie.css', array( 'manduca-style' ), '20121010' );
	$wp_styles->add_data( 'manduca-ie', 'conditional', 'lt IE 9' );
	
	//focus-snail (https://github.com/NV/focus-snail)
	wp_enqueue_script( 'focus-snail', get_template_directory_uri() ."/js/focus-snail.js", array(), '1.0', true );
	
}

add_action( 'wp_enqueue_scripts', 'manduca_scripts_styles' );


//-------------------------------------------------------------------------------------------
// Filter the page menu arguments.
//-------------------------------------------------------------------------------------------
 
function manduca_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) ) {
		$args['show_home'] = true;
	}
	return $args;
}

add_filter( 'wp_page_menu_args', 'manduca_page_menu_args' );
		
function manduca_widgets_init() {
	register_sidebar( array(
		'name' =>__( 'Sidebar', 'manduca' ),
		'id' => 'main_sidebar',
		'description' => __( 'Appears all pages except when using full page template', 'manduca' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );

	
}
add_action( 'widgets_init', 'manduca_widgets_init' );

//-------------------------------------------------------------------------------------------
// Template for comments and pingbacks.
//-------------------------------------------------------------------------------------------

if ( ! function_exists( 'manduca_comment' ) ) :
 
function manduca_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'manduca' ), '<span class="edit-link"><i class="fa fa-pencil" aria-hidden="true"></i> ', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					printf( '<cite><b class="fn">%1$s</b> %2$s</cite>',
						get_comment_author_link(),
						( $comment->user_id === $post->post_author ) ? '<span>' .__( 'Author', 'manduca' ) .'</span>' : ''
					);
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						sprintf( __( 'Date: %1$s: %2$s', 'manduca' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) { ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Comment is awaiting for apporval.', 'manduca' ) ?></p>
			<?php } ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'manduca' ), '<p class="edit-link"><i class="fa fa-pencil"></i> ', '</p>' ); ?>
			</section><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'manduca' ), 'after' => ' <span>&rarr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

//-------------------------------------------------------------------------------------------
// Set up Manduca post meta.
//-------------------------------------------------------------------------------------------

if ( ! function_exists( 'manduca_entry_meta' ) ) :

function manduca_entry_meta( $only_date=false )
{	
	$categories_list = get_the_category_list( ', ' );
	$tag_list = get_the_tag_list( '', ', ' );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><abbr class="published" title="%3$s"><time class="entry-date" datetime="%3$s">%4$s</time itemprop="datePublished"></abbr></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
	$date_wo_link = sprintf( '<p class="content-date"><time class="entry-date" itemprop="datePublished" datetime="%1$s"></time><span class="entry-date-month">%3$s</span><span class="entry-date-day">%2$s</span></p>',
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date( 'j' ) ),
		esc_html( get_the_date( 'M' ) )
	);

	$author = sprintf( '<span class="author vcard" itemprop="name"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'All posts by %s', ' manduca' ), get_the_author() ) ),
		get_the_author()
	);

	$modified_date = sprintf( '<time class="updated" datetime="%1$s">%2$s</time></a>',
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date( 'Y. F j.' ) )
	);

	$utility_text ='<p class="screen-reader-text">'. __( 'Post meta', 'manduca' ) .'</p>';
	$utility_text .= "<ul>";
	
	$utility_text .= '<li><i class="fa fa-clock-o" aria-hidden="true"></i><span> ' .__( 'Entry date', 'manduca' ) .':</span> ' . $date .'</li>';
	
	if( get_the_date() !== get_the_modified_date() ) {
		$utility_text .='<li><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span> ' . __( 'Last revision:', 'manduca' ) .':</span> ' .$modified_date .'</li>';
	}
	
	$utility_text .= '<li><i class="fa fa-user" aria-hidden="true"></i><span> ' .__( 'Author', 'manduca' ) .':</span> ' . $author .'</li>';

	if ( $categories_list ) {
		$utility_text .='<li><i class="fa fa-folder-open-o" aria-hidden="true"></i><span> ' .__( 'Category', 'manduca' ) .':</span> ' .$categories_list .'</li>';
	}
	
	if ( $tag_list ) {
		$utility_text .= '<li><i class="fa fa-tags" aria-hidden="true"></i><span> '. __( 'Tags', 'manduca' ) .':</span> ' .$tag_list .'</li>';
	}
	
	
	$utility_text .="</ul>";
	
	if ( $only_date ) {
		$utility_text = $date_wo_link;
	}
	echo $utility_text;
}
endif;

//-------------------------------------------------------------------------------------------
// Customizer setup
//-------------------------------------------------------------------------------------------

function manduca_sanitize_text_input( $input ) {
    $output = strip_tags( stripslashes( $input ) );  
    return $output;
} 

function manduca_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

// Move theme option to the customizer
	$wp_customize->add_setting( 'manduca_copyright_text', array(
		'default' => get_bloginfo(),
		'sanitize_callback' => 'manduca_sanitize_text_input'
		) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'manduca_options', array(
		'label'        =>  __( 'Copyright text', 'manduca' ),
		'section'    => 'title_tagline',
		'settings'   => 'manduca_copyright_text',
	) ) );
}
add_action( 'customize_register', 'manduca_customize_register' );

//-------------------------------------------------------------------------------------------
// Enqueue Javascript postMessage handlers for the Customizer.
//-------------------------------------------------------------------------------------------
 
function manduca_customize_preview_js() {
	wp_enqueue_script( 'manduca-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20141120', true );
}
add_action( 'customize_preview_init', 'manduca_customize_preview_js' );

//-------------------------------------------------------------------------------------------
// Manduca post navigation
//-------------------------------------------------------------------------------------------
if ( ! function_exists( 'manduca_page_navigation' ) ) :
 
function manduca_page_navigation() {
	global $wp_query, $wp_rewrite;

	if ( $wp_query->max_num_pages < 2 ) {
		return;
	}

	$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}

	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

	$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

	$links = paginate_links( array(
		'base'     => $pagenum_link,
		'format'   => $format,
		'total'    => $wp_query->max_num_pages,
		'current'  => $paged,
		'mid_size' => 1,
		'add_args' => array_map( 'urlencode', $query_args ),
		'prev_text' => __( '&larr; Previous', 'manduca' ),
		'next_text' => __( 'Next &rarr;', 'manduca' ),
	) );

	if ( $links ) :

	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h3 class="screen-reader-text"><?php _e( 'Posts navigation', 'manduca' ); ?></h3>
		<div class="pagination loop-pagination">
			<?php echo $links; ?>
		</div><!-- .pagination -->
	</nav><!-- .navigation -->
	<?php
	endif;
}
endif;


//-------------------------------------------------------------------------------------------
// Change default text for comments
//-------------------------------------------------------------------------------------------

add_filter( 'comment_form_defaults', 'set_comment_form_text' );
function set_comment_form_text( $defaults ) {
	$defaults['comment_notes_after'] 	= '';
	$defaults['title_reply']		= __( 'Please give your comments!', 'manduca' );
$commenter = wp_get_current_commenter();
	$fields =  array(

  'author' =>
    '<p class="comment-form-author"><label for="author">' . __( 'Your name', 'manduca' )  .'</label> <input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
    '" size="30" /></p>',

  'email' =>
    '<p class="comment-form-email"><label for="email">' .__( 'Email', 'manduca' ) .'</label> <input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
    '" size="30" /></p>',

);
	$defaults['fields'] =$fields;
	$defaults['comment_field'] ='<p class="comment-form-comment"><label for="comment">' .__( 'Your comment', 'manduca' ) .'</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';
	$defaults['comment_notes_before'] = '<p class="comment-notes">' .__( 'Your email address is confidential.', 'manduca' ) .'</p>';
	return $defaults;

}

//-------------------------------------------------------------------------------------------
//  additional form button to MCE 
//-------------------------------------------------------------------------------------------


function manduca_form_MCE( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
    }
add_filter( 'mce_buttons_3', 'manduca_form_MCE' );


function my_mce_before_init_insert_formats( $init_array ) {  
	// Define the style_formats array
	$style_formats = array(  
		// Each array child is a format with it's own settings
		array(  
			'title' => __( 'Highlight' , 'manduca' ) .'-1',
			'block' => 'div',  
			'classes' => 'highlight-1',
			'wrapper' => true,
			
		),  
		array(  
			'title' => __( 'Highlight' , 'manduca' ) .'-2',  
			'block' => 'div',  
			'classes' => 'highlight-2',
			'wrapper' => true,
		)
		
	);  
	// Insert the array, JSON ENCODED, into 'style_formats'
	$init_array['style_formats'] = json_encode( $style_formats );  
	
	return $init_array;  
  
} 

add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' );


function editor_css() {
	add_editor_style( 'editor.css' );    
}

add_action( 'after_setup_theme', 'editor_css' );

//-------------------------------------------------------------------------------------------
// Get custom header's alt data.
//-------------------------------------------------------------------------------------------
function manduca_get_header_image_alt() {
    $attachment_id = 0;
    
    if ( is_random_header_image() && $header_url = get_header_image() ) {
	    // For a random header, we have to search for a match against all headers.
	    foreach ( get_uploaded_header_images() as $header ) {
		    if ( $header['url'] == $header_url ) {
			    $attachment_id = $header['attachment_id'];
			    break;
		    }
	    }
    
    } elseif ( $data = get_custom_header() ) {
	    // For static headers, less intensive approach.
	    $attachment_id = $data->attachment_id;
    } 
    
    if ( $attachment_id ) {
	    $alt = trim( strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );

	    if ( ! $alt ) {
		    $alt = trim( strip_tags( get_post_field( 'post_excerpt', $attachment_id ) ) );
	    }
	    if ( ! $alt ) {
		    $alt = trim( strip_tags( get_post_field( 'post_title', $attachment_id ) ) );
	    }
    }
	else {
	    $alt = '';
    }
    
    return $alt;
}
  
//-------------------------------------------------------------------------------------------
// Login redirect to homepage except admin
//-------------------------------------------------------------------------------------------
 
function manduca_login_redirect( $redirect_to, $request, $user ) {
	global $user;
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		if ( in_array( 'administrator', $user->roles ) ) {
			return esc_url( $redirect_to );
		} else {
			return esc_url( home_url() );
		}
	}
	else {
		return $redirect_to;
	}
}

add_filter( 'login_redirect', 'manduca_login_redirect', 10, 3 );

//---------------------------------------------------------------------------------------------------------------------------
// Manduca body class
//---------------------------------------------------------------------------------------------------------------------------

function manduca_body_class( $classes ) {
	$background_color = get_background_color();
	$background_image = get_background_image();

	if ( ! is_active_sidebar( 'main_sidebar' ) || is_page_template( 'page-templates/full-width.php' ) ) {
		$classes[] = 'full-width';
	}

	if ( is_page_template( 'page-templates/front-page.php' ) ) {
		$classes[] = 'template-front-page';
		if ( has_post_thumbnail() ) {
			$classes[] = 'has-post-thumbnail';
		}
	}

	if ( empty( $background_image ) ) {
		if ( empty( $background_color ) ) {
			$classes[] = 'custom-background-empty';
		}
		elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) ) {
			$classes[] = 'custom-background-white';
		}
	}

	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	return $classes; 
}
add_filter( 'body_class', 'manduca_body_class' );


 //-------------------------------------------------------------------------------------------
 // Display posts in two columns
 //-------------------------------------------------------------------------------------------
 
function manduca_display_in_two_columns() {
?>				<div class="excerpt-wrapper">

			<?php
			/* Start the Loop */
			$post_counter = 1;
			while ( have_posts() ) : the_post(); ?>
				
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope="itemscope" itemtype="http://schema.org/BlogPosting" itemprop="blogPost" >
				<?php if ( has_post_thumbnail() ) :?>
					<div class="crop-height">
						<?php the_post_thumbnail(); ?>
					</div>
				<?php endif; ?>
		
				<h2 class="entry-title"  itemprop="headline">
					<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h2>
				<?php
					if( strpos( get_the_content(), 'more-link' ) === false ) {
						the_excerpt();
					}
					else {
						the_content();
					}
				?>
				
				</article>
				<?php if ( $post_counter %2 ==0 ) {
					echo '<div class="vonalzo"></div>';
					} ?>

			<?php $post_counter++; endwhile;
			manduca_page_navigation();
			?>
			</div>
<?php
}

//-------------------------------------------------------------------------------------------
// Avatar need alt tag
//-------------------------------------------------------------------------------------------

    
function manduca_add_alt_to_avatar( $text )
{
	$alt = get_the_author_meta( 'display_name' );
	$text = str_replace( 'alt=""', 'alt="'.$alt . __( 'avatar', 'manduca' ) , $text );
	return $text;
}
add_filter( 'get_avatar', 'manduca_add_alt_to_avatar' );

//-------------------------------------------------------------------------------------------
// Change "more" to "continue reading" and make it acccessible.
//-------------------------------------------------------------------------------------------

function manduca_more_tag( $more ) {
       global $post;
	return '&nbsp;&nbsp;<a id="moretag" class="kvazi-button" href="'. get_permalink( $post->ID ) .'" rel="nofollow" >' . __( 'Continue reading', 'manduca' ) .'&nbsp;&rarr;<span class="screen-reader-text"> '.get_the_title() .'</span></a>';
}
add_filter( 'excerpt_more', 'manduca_more_tag' );

function manduca_content_more_link() {
	global $post;
	return '<a class="more-link" rel="nofollow" href="' . get_permalink() .'">' . __( 'Continue reading', 'manduca' ) .'&nbsp;&rarr;<span class="screen-reader-text">  '.get_the_title() .'</span></a>';
}
add_filter( 'the_content_more_link', 'manduca_content_more_link' );

//-------------------------------------------------------------------------------------------
// Add "ext-link" class to external links 
//-------------------------------------------------------------------------------------------

function manduca_get_domain_name_from_uri( $uri ) {
	preg_match( '/^(http:\/\/)?([^\/]+)/i', $uri, $matches );
	$host = $matches[2];
	preg_match( '/[^\.\/]+\.[^\.\/]+$/', $host, $matches );
	return $host;
}


function mandcua_parse_external_links( $matches ) {
	if ( manduca_get_domain_name_from_uri( $matches[3] ) != manduca_get_domain_name_from_uri( $_SERVER["HTTP_HOST"] ) ) {
		return '<a href="' . $matches[2] . '//' . $matches[3] . '"' . $matches[1] . $matches[4] . ' class="ext-link">' . $matches[5] . '</a>';	 
	} else {
		return '<a href="' . $matches[2] . '//' . $matches[3] . '"' . $matches[1] . $matches[4] . '>' . $matches[5] . '</a>';
	}
}
	

function manduca_external_links( $text ) {
	$pattern = '/<a (.*?)href="(.*?)\/\/(.*?)"(.*?)>(.*?)<\/a>/i';
	$text = preg_replace_callback( $pattern, 'mandcua_parse_external_links', $text );

	$pattern2 = '/<a (.*?) class="extlink"(.*?)>(.*?)<img (.*?)<\/a>/i';
	return $text;
}

// filters have high priority to make sure that any markup plugins like Textile or Markdown have already created the HTML links
add_filter( 'the_content', 'manduca_external_links', 999 );
add_filter( 'the_excerpt', 'manduca_external_links', 999 );

// delete this one if you don't want it run on comments
add_filter( 'comment_text', 'manduca_external_links', 999 );


?>