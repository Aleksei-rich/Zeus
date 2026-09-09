<?php
/**
 * Targeted visual/UX refinements requested after production review.
 *
 * Kept separate from the main templates so the changes are easy to audit,
 * deploy and roll back. No business claims or project facts are introduced
 * here; this file only adjusts presentation and swaps known media assets.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Make the floating-shelf captions unambiguous on the homepage and tighten
 * the Portfolio archive copy without changing the underlying page model.
 */
function zeus_visual_polish_gettext( $translation, $text, $domain ) {
	if ( 'zeus' !== $domain ) {
		return $translation;
	}

	if ( is_front_page() ) {
		$homepage_replacements = array(
			'Custom floating shelves — another example of the built-in work we do beyond cabinetry:' => 'Floating shelf examples — the labels below refer to the shelf material and finish, not the cabinet color.',
			'White Oak' => 'Floating Shelf — White Oak',
			'Maple'     => 'Floating Shelf — Maple',
			'Walnut'    => 'Floating Shelf — Walnut',
		);

		if ( isset( $homepage_replacements[ $text ] ) ) {
			return $homepage_replacements[ $text ];
		}
	}

	if ( is_post_type_archive( 'project' ) ) {
		$portfolio_replacements = array(
			'This portfolio is being built from completed ZEUS projects so visitors can evaluate real layouts, finishes and installation details rather than generic stock imagery. Our work includes kitchens, bathroom vanities, closets, laundry and pantry cabinetry, home offices and stone countertops.' => 'Browse completed ZEUS cabinetry and countertop projects documented with real project photography.',
			'ZEUS coordinates cabinet selection, measurements, design, delivery, assembly and installation, with countertop fabrication and installation available as part of the same project. Cabinet options include Shaker, Slim Shaker, Brooklyn and Euro flat-panel styles, while countertop materials include quartz, granite, porcelain and marble.' => 'Our work includes kitchens, bathroom vanities, closets, laundry and pantry cabinetry, home offices, custom built-ins and stone countertops.',
			'We serve homeowners, investors, flippers and renovation companies throughout Orlando, Windermere, Winter Garden, Horizon West, Clermont and surrounding Central Florida communities. As more completed-project photography is organized, this page will expand with individual project details and images.' => 'Projects are added only when completed-work photography and the available project details can be presented accurately.',
		);

		if ( isset( $portfolio_replacements[ $text ] ) ) {
			return $portfolio_replacements[ $text ];
		}
	}

	return $translation;
}
add_filter( 'gettext', 'zeus_visual_polish_gettext', 20, 3 );

/**
 * Swap a small set of known media assets in hard-coded theme sections.
 *
 * - Homepage real-work strip: replace the owner-rejected gray-kitchen image
 *   with the already verified real bathroom-vanity installation.
 * - Cabinets / Custom Cabinetry card: show the dedicated Euro flat-panel kitchen.
 * - Kitchen Cabinets / In-Stock: use a richer Brooklyn Slate kitchen image.
 */
function zeus_visual_polish_attachment_image( $html, $attachment_id, $size, $icon, $attr ) {
	static $replacing = false;

	if ( $replacing || is_admin() ) {
		return $html;
	}

	$replacement_id = 0;

	if ( is_front_page() && 77 === (int) $attachment_id ) {
		$replacement_id = 76;
	} elseif ( is_page( 'cabinets' ) && 139 === (int) $attachment_id ) {
		$replacement_id = 153;
		$attr['alt']     = __( 'Modern Euro flat-panel kitchen cabinetry', 'zeus' );
	} elseif ( is_page( 'kitchen-cabinets' ) && 123 === (int) $attachment_id ) {
		$replacement_id = 121;
		$attr['alt']     = __( 'Brooklyn Slate kitchen cabinetry', 'zeus' );
	}

	if ( ! $replacement_id ) {
		return $html;
	}

	$replacing = true;
	$replacement_html = wp_get_attachment_image( $replacement_id, $size, $icon, $attr );
	$replacing = false;

	return $replacement_html ?: $html;
}
add_filter( 'wp_get_attachment_image', 'zeus_visual_polish_attachment_image', 20, 5 );

/**
 * Expand the homepage Real ZEUS Work strip with featured images from two
 * verified completed Project CPT records. The images are intentionally used
 * generically: no cabinet style, material or room type is asserted here.
 */
function zeus_visual_polish_expand_real_work() {
	if ( is_admin() || ! is_front_page() ) {
		return;
	}

	$extra_ids = array( 354, 357 );
	$cards     = array();

	foreach ( $extra_ids as $attachment_id ) {
		if ( ! wp_attachment_is_image( $attachment_id ) ) {
			continue;
		}

		$image = wp_get_attachment_image(
			$attachment_id,
			'zeus-card',
			false,
			array(
				'loading' => 'lazy',
				'alt'     => __( 'Completed ZEUS cabinetry project', 'zeus' ),
			)
		);

		if ( $image ) {
			$cards[] = '<div class="zeus-real-photo zeus-real-photo--verified-project">' . $image . '<span class="zeus-real-photo__label">' . esc_html__( 'Real ZEUS Installation', 'zeus' ) . '</span></div>';
		}
	}

	if ( ! $cards ) {
		return;
	}
	?>
	<template id="zeus-extra-real-work-template"><?php echo wp_kses_post( implode( '', $cards ) ); ?></template>
	<script id="zeus-extra-real-work-js">
	(function () {
		var template = document.getElementById('zeus-extra-real-work-template');
		if (!template) return;
		var headings = document.querySelectorAll('h2');
		var target = null;
		for (var i = 0; i < headings.length; i++) {
			if (headings[i].textContent.trim() === 'From Real ZEUS Installations') {
				target = headings[i];
				break;
			}
		}
		if (!target) return;
		var section = target.closest('.zeus-section');
		if (!section) return;
		var grid = section.querySelector('.zeus-grid');
		if (!grid || grid.querySelector('.zeus-real-photo--verified-project')) return;
		grid.appendChild(template.content.cloneNode(true));
	})();
	</script>
	<?php
}
add_action( 'wp_footer', 'zeus_visual_polish_expand_real_work', 18 );

/**
 * Small presentation layer for the reviewed sections plus a desktop-only
 * floating consultation CTA. Mobile already has the persistent Call /
 * Consultation conversion bar, so the floating control is intentionally
 * desktop-only.
 */
function zeus_visual_polish_css() {
	if ( is_admin() ) {
		return;
	}
	?>
	<style id="zeus-visual-polish">
		/* Floating-shelf copy must read as shelf information, not cabinet color. */
		.home .zeus-floating-shelf__label {
			font-size: 0.9rem;
			font-weight: 700;
			color: var(--wp--preset--color--navy);
			text-align: center;
			line-height: 1.35;
		}
		.home .zeus-card__meta + .zeus-grid .zeus-floating-shelf {
			background: var(--wp--preset--color--base);
			border: 1px solid var(--wp--preset--color--stone-200);
			border-radius: var(--wp--custom--radius--medium);
			padding: 0.55rem;
		}

		/* Expanded real-work strip: allow five verified images to wrap cleanly. */
		.home .zeus-real-photo {
			min-width: 0;
		}
		.home .zeus-real-photo img {
			width: 100%;
			height: 100%;
			aspect-ratio: 4 / 3;
			object-fit: cover;
		}

		/* CTA text was visually anchored left because the paragraph had a
		   max-width but no auto margins. Keep all CTA copy genuinely centered. */
		.zeus-cta {
			position: relative;
			overflow: hidden;
			isolation: isolate;
			max-width: 100%;
		}
		.zeus-cta > p { margin-inline: auto; }
		.zeus-cta::before,
		.zeus-cta::after { content: none !important; display: none !important; }

		/* Portfolio: a centered editorial intro followed by an aligned grid. */
		.post-type-archive-project .zeus-section__header {
			max-width: 820px;
			margin-inline: auto;
			text-align: center;
		}
		.post-type-archive-project .zeus-section__header p { margin-inline: auto; }
		.post-type-archive-project .zeus-prose {
			max-width: 900px;
			margin: 0 auto var(--wp--preset--spacing--5);
			display: grid;
			gap: var(--wp--preset--spacing--2);
			text-align: center;
		}
		.post-type-archive-project .zeus-prose p {
			max-width: none;
			margin: 0;
		}
		.post-type-archive-project .zeus-prose + .zeus-grid {
			margin-top: var(--wp--preset--spacing--4);
		}
		.post-type-archive-project > .zeus-cta {
			width: calc(100% - 2rem);
			max-width: var(--wp--custom--container--wide);
			margin: 0 auto var(--wp--preset--spacing--4);
		}

		/* Desktop floating consultation CTA. */
		.zeus-desktop-consultation-fab {
			display: none;
		}
		@media (min-width: 1280px) {
			.zeus-desktop-consultation-fab {
				position: fixed;
				right: 1.5rem;
				bottom: 1.5rem;
				z-index: 140;
				display: inline-flex;
				align-items: center;
				justify-content: center;
				min-height: 52px;
				padding: 0.8rem 1.15rem;
				border-radius: 999px;
				background: var(--wp--preset--color--gold);
				color: var(--wp--preset--color--navy);
				font-weight: 800;
				box-shadow: 0 8px 28px rgba(7,31,52,0.24);
				border: 2px solid rgba(7,31,52,0.12);
				transition: opacity .18s ease, transform .18s ease, box-shadow .18s ease;
			}
			.zeus-desktop-consultation-fab:hover,
			.zeus-desktop-consultation-fab:focus-visible {
				background: var(--wp--preset--color--navy);
				color: var(--wp--preset--color--base);
				text-decoration: none;
				box-shadow: 0 10px 32px rgba(7,31,52,0.34);
			}
			.zeus-desktop-consultation-fab[data-hidden="true"] {
				opacity: 0;
				transform: translateY(12px);
				pointer-events: none;
			}
		}
	</style>
	<?php
}
add_action( 'wp_head', 'zeus_visual_polish_css', 30 );

function zeus_render_desktop_consultation_fab() {
	if ( is_admin() || is_page( 'consultation' ) || is_page( 'thank-you' ) ) {
		return;
	}
	?>
	<a class="zeus-desktop-consultation-fab" href="<?php echo esc_url( zeus_consultation_url() ); ?>" aria-label="<?php esc_attr_e( 'Request Free Consultation', 'zeus' ); ?>">
		<?php esc_html_e( 'Request Free Consultation', 'zeus' ); ?>
	</a>
	<script id="zeus-desktop-consultation-fab-js">
	(function () {
		var fab = document.querySelector('.zeus-desktop-consultation-fab');
		if (!fab || !('IntersectionObserver' in window)) return;
		var targets = document.querySelectorAll('.zeus-form, .zeus-footer, .zeus-cta');
		if (!targets.length) return;
		var visible = new Set();
		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) visible.add(entry.target);
				else visible.delete(entry.target);
			});
			fab.dataset.hidden = visible.size ? 'true' : 'false';
		}, { threshold: 0.08 });
		targets.forEach(function (target) { observer.observe(target); });
	})();
	</script>
	<?php
}
add_action( 'wp_footer', 'zeus_render_desktop_consultation_fab', 20 );
