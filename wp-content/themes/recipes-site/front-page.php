<?php
/**
 * Front page — recipes multi-rail homepage (sibling pattern, recipe-adapted).
 *
 *   hero (newest recipe — or the brand hero while the site has no posts)
 *   → Trending recipes rail (editorial `trending` tag, card rail)
 *   → Kitchen Tips rail (Kitchen Tips category)
 *   → topic band (the six pillars) → Latest grid + pagination.
 * Every rail renders ONLY when it has posts, so at launch (zero posts) the page is
 * hero + topic band + Latest heading — graceful, never broken-looking.
 *
 * @package Recipes_Site
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

$rc_q = vr_front_query();

/* The hero is the newest post; it is taken out of the Latest grid below. */
$rc_hero    = null;
$rc_hero_id = 0;
if ( ! is_paged() && $rc_q->have_posts() ) {
	$rc_q->the_post();
	$rc_hero    = get_post();
	$rc_hero_id = (int) $rc_hero->ID;
}

if ( is_paged() ) :
	?>
	<section class="vr-container page-head">
		<p class="eyebrow"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
		<h1 class="page-title"><?php printf( esc_html__( 'Latest recipes — page %d', 'recipes-site' ), (int) get_query_var( 'paged' ) ); ?></h1>
	</section>
	<?php
elseif ( $rc_hero ) :
	vr_hero_media( $rc_hero );
elseif ( function_exists( 'vr_hero_image_url' ) && vr_hero_image_url() ) :
	/* Standing hero while the site has no articles yet (brand launch state). */
	?>
	<section class="home-hero">
		<img class="home-hero__img" src="<?php echo esc_url( vr_hero_image_url() ); ?>" alt="" fetchpriority="high" decoding="async">
		<div class="vr-container home-hero__in">
			<p class="home-hero__eyebrow"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
			<h1 class="home-hero__title"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
			<p class="home-hero__sub"><?php esc_html_e( 'Honest recipes and kitchen know-how for UK home cooks — the first recipes are on their way.', 'recipes-site' ); ?></p>
			<a class="vr-btn" href="#explore"><?php esc_html_e( 'Explore the topics', 'recipes-site' ); ?></a>
		</div>
	</section>
	<?php
endif;

/* ---------- Rails (first page only, and only once the site has posts) ---------- */
if ( ! is_paged() && $rc_hero ) :

	/* A card rail: renders the section only when the query finds posts. */
	$rc_rail = function ( $eyebrow, $title, $more_url, $query ) {
		if ( ! $query->have_posts() ) { wp_reset_postdata(); return; }
		?>
		<section class="vr-container rc-rail">
			<div class="rc-rail__head">
				<span class="rc-rail__eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
				<h2 class="rc-rail__title"><?php echo esc_html( $title ); ?></h2>
				<?php if ( $more_url ) : ?><a class="rc-rail__more" href="<?php echo esc_url( $more_url ); ?>"><?php esc_html_e( 'More', 'recipes-site' ); ?> &rarr;</a><?php endif; ?>
			</div>
			<div class="post-grid">
				<?php
				while ( $query->have_posts() ) {
					$query->the_post();
					get_template_part( 'template-parts/card', '', array( 'heading' => 'h3' ) );
				}
				?>
			</div>
		</section>
		<?php
		wp_reset_postdata();
	};

	/* Term links, guarded (a missing term must never break the homepage). */
	$rc_link = function ( $slug, $taxonomy ) {
		$t = get_term_by( 'slug', $slug, $taxonomy );
		if ( ! $t ) { return ''; }
		$l = get_term_link( $t );
		return is_wp_error( $l ) ? '' : $l;
	};

	/* Trending recipes — the editorial `trending` curation tag. */
	$rc_rail( __( 'Most cooked', 'recipes-site' ), __( 'Trending recipes', 'recipes-site' ), $rc_link( 'trending', 'post_tag' ),
		new WP_Query( array( 'tag' => 'trending', 'posts_per_page' => 4, 'post__not_in' => array( $rc_hero_id ), 'ignore_sticky_posts' => true, 'no_found_rows' => true ) ) );

	/* Kitchen Tips — the tips pillar; the card excerpt reads as the takeaway line. */
	$rc_rail( __( 'Kitchen know-how', 'recipes-site' ), __( 'Tips & tricks', 'recipes-site' ), $rc_link( 'kitchen-tips', 'category' ),
		new WP_Query( array( 'category_name' => 'kitchen-tips', 'posts_per_page' => 3, 'post__not_in' => array( $rc_hero_id ), 'ignore_sticky_posts' => true, 'no_found_rows' => true ) ) );

endif;

/* The six pillars as topic tiles (parent block; covers fall back gracefully pre-content).
   Wrapped in #explore: the brand hero's "Explore the topics" button anchors here. */
if ( function_exists( 'vr_showcase_categories' ) ) {
	echo '<div id="explore">';
	vr_showcase_categories( 6 );
	echo '</div>';
}
?>

<section class="vr-container section">
	<div class="section-head">
		<h2><?php esc_html_e( 'Latest recipes', 'recipes-site' ); ?></h2>
	</div>
	<?php if ( $rc_q->have_posts() ) : ?>
		<div class="post-grid">
			<?php
			$rc_posts = $rc_q->posts;
			if ( $rc_hero_id ) {
				/* The hero post was already consumed above — skip it in the grid. */
				$rc_posts = array_values( array_filter( $rc_posts, function ( $p ) use ( $rc_hero_id ) { return (int) $p->ID !== $rc_hero_id; } ) );
			}
			foreach ( $rc_posts as $post ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- template loop
				setup_postdata( $post );
				get_template_part( 'template-parts/card', '', array( 'heading' => 'h3' ) );
			}
			wp_reset_postdata();
			?>
		</div>
		<?php vr_pagination( $rc_q ); ?>
	<?php else : ?>
		<p class="empty-note"><?php esc_html_e( 'No recipes published yet — the first batch is being cooked. Explore the topics above in the meantime.', 'recipes-site' ); ?></p>
	<?php endif; ?>
</section>

<?php
get_footer();
