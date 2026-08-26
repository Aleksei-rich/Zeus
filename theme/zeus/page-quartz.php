<?php
/**
 * Quartz Countertops page (/countertops/quartz/) -- RC4D.
 * Auto-selected by WordPress's page-{slug}.php template hierarchy for
 * the "quartz" page (ID 13); no post-meta template assignment needed.
 * No exaggerated claims (heat-proof, stain-proof, scratch-proof) --
 * see docs/DECISIONS.md ("RC4D"). Attachment 156 is a generated
 * category/lifestyle visual (see docs/ASSET-PROVENANCE.csv) and must
 * never be presented as a completed ZEUS project. No independently-
 * verified real ZEUS quartz-specific installation photo exists, so
 * this page has no "Real ZEUS Work" section.
 */
get_header();
zeus_render_breadcrumbs();

$zeus_page_kitchen = zeus_get_post_by_slug( 'kitchen-cabinets', 'page' );
$zeus_page_bath    = zeus_get_post_by_slug( 'bathroom-cabinets-vanities', 'page' );
$zeus_page_ct      = zeus_get_post_by_slug( 'countertops', 'page' );

$zeus_hero_image_id = 156;
?>

<!-- 1. Hero -->
<section class="zeus-hero" aria-label="<?php esc_attr_e( 'Quartz Countertops in Orlando', 'zeus' ); ?>">
	<div class="zeus-hero__media">
		<?php if ( $zeus_hero_image_id ) : ?>
			<?php
			echo wp_get_attachment_image(
				$zeus_hero_image_id,
				'zeus-hero',
				false,
				array(
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'class'         => 'zeus-hero__img',
					'alt'           => __( 'Modern kitchen with light quartz countertop and waterfall island', 'zeus' ),
				)
			);
			?>
		<?php endif; ?>
		<div class="zeus-hero__scrim" aria-hidden="true"></div>
	</div>
	<div class="zeus-container">
		<div class="zeus-hero__content">
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Quartz Countertops', 'zeus' ); ?></p>
			<h1><?php esc_html_e( 'Quartz Countertops in Orlando', 'zeus' ); ?></h1>
			<p class="zeus-hero__lede">
				<?php esc_html_e( 'An engineered surface with a wide range of colors and patterns, popular for homeowners who want a more consistent look and straightforward routine care.', 'zeus' ); ?>
			</p>
			<div class="zeus-cta__actions" style="justify-content:flex-start;">
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Request Free Consultation', 'zeus' ), 'url' => zeus_consultation_url(), 'variant' => 'primary' ) ); ?>
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Compare Materials', 'zeus' ), 'url' => $zeus_page_ct ? get_permalink( $zeus_page_ct ) . '#zeus-compare' : home_url( '/countertops/#zeus-compare' ), 'variant' => 'secondary', 'on_dark' => true ) ); ?>
			</div>
		</div>
	</div>
</section>

<!-- 2. Why quartz -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Why Quartz', 'zeus' ),
		'heading' => __( 'Why Homeowners Choose Quartz', 'zeus' ),
		'intro'   => __( 'Quartz should still be protected from extreme direct heat, and normal care recommendations should be followed — but day to day, it\'s one of the more straightforward surfaces to live with.', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4">
		<div><h3><?php esc_html_e( 'Engineered Consistency', 'zeus' ); ?></h3><p><?php esc_html_e( 'A manufactured surface, so pattern and color are more predictable slab to slab.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Wide Design Range', 'zeus' ); ?></h3><p><?php esc_html_e( 'From clean solid colors to patterns inspired by natural stone.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Low Routine Maintenance', 'zeus' ); ?></h3><p><?php esc_html_e( 'No periodic sealing needed for most quartz surfaces.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Kitchens & Bathrooms', 'zeus' ); ?></h3><p><?php esc_html_e( 'A practical fit for both high-use kitchen surfaces and vanities.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 3. Design / appearance -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Appearance', 'zeus' ),
		'heading' => __( 'From Subtle Patterns to Statement Veining', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--2" style="align-items:center;">
		<div>
			<?php echo wp_get_attachment_image( 156, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto;', 'alt' => 'Quartz countertop with soft, consistent veining' ) ); ?>
		</div>
		<div>
			<p><?php esc_html_e( 'Because quartz is manufactured, it spans a wide design range — clean whites, warm neutrals, soft subtle movement, and bolder patterns inspired by marble veining. That manufactured consistency is an advantage when a project needs multiple matching pieces, like a large island or a long run of countertop.', 'zeus' ); ?></p>
		</div>
	</div>
<?php zeus_section_end(); ?>

<!-- 4. Where quartz works -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Applications', 'zeus' ),
		'heading' => __( 'Where Quartz Works', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4">
		<div><h3><?php esc_html_e( 'Kitchen Countertops', 'zeus' ); ?></h3></div>
		<div><h3><?php esc_html_e( 'Bathroom Vanities', 'zeus' ); ?></h3></div>
		<div><h3><?php esc_html_e( 'Laundry / Utility Worktops', 'zeus' ); ?></h3></div>
		<div><h3><?php esc_html_e( 'Custom Built-In Surfaces', 'zeus' ); ?></h3></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 5. Quartz + cabinetry -->
<?php zeus_section_start( array( 'variant' => 'stone', 'eyebrow' => __( 'Coordinated Design', 'zeus' ), 'heading' => __( 'Quartz + Cabinetry', 'zeus' ) ) ); ?>
	<p>
		<?php
		printf(
			/* translators: 1: link to Kitchen Cabinets, 2: link to Bathroom Cabinets & Vanities */
			wp_kses_post( __( 'Surface tone and pattern are paired with your cabinet color and style, not chosen in isolation — we\'ll go over options together for %1$s or %2$s during your consultation.', 'zeus' ) ),
			'<a href="' . esc_url( $zeus_page_kitchen ? get_permalink( $zeus_page_kitchen ) : home_url( '/cabinets/kitchen-cabinets/' ) ) . '">' . esc_html__( 'kitchen cabinets', 'zeus' ) . '</a>',
			'<a href="' . esc_url( $zeus_page_bath ? get_permalink( $zeus_page_bath ) : home_url( '/cabinets/bathroom-cabinets-vanities/' ) ) . '">' . esc_html__( 'bathroom vanities', 'zeus' ) . '</a>'
		);
		?>
	</p>
<?php zeus_section_end(); ?>

<!-- 6. Process -->
<?php
zeus_section_start(
	array(
		'variant' => 'compact',
		'eyebrow' => __( 'Process', 'zeus' ),
		'heading' => __( 'From Selection to Installation', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4 zeus-process">
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Consultation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Discuss your project and cabinetry pairing.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Color & Pattern Selection', 'zeus' ); ?></h3><p><?php esc_html_e( 'Choose a quartz color and pattern, and plan seam placement.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Final Measurement / Template', 'zeus' ); ?></h3><p><?php esc_html_e( 'Precise measurements taken once cabinetry is in place.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Fabrication & Installation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Professional fabrication and installation coordinated by ZEUS.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 7. Service area -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Service Area', 'zeus' ),
		'heading' => __( 'Serving Orlando & Central Florida', 'zeus' ),
	)
);
?>
	<p>
		<?php
		esc_html_e(
			'ZEUS installs quartz countertops for homeowners throughout Orlando, and in nearby communities including Windermere, Winter Garden, Horizon West, Dr. Phillips, Clermont, and Lake Nona.',
			'zeus'
		);
		?>
	</p>
	<p class="zeus-service-area__note">
		<?php esc_html_e( "Not sure if you're in our service area?", 'zeus' ); ?>
		<a href="<?php echo esc_url( zeus_phone_number_href() ); ?>"><?php esc_html_e( 'Call or text us.', 'zeus' ); ?></a>
	</p>
<?php zeus_section_end(); ?>

<!-- 8. FAQ -->
<?php
zeus_section_start(
	array(
		'variant' => 'compact',
		'eyebrow' => __( 'FAQ', 'zeus' ),
		'heading' => __( 'Quartz Questions', 'zeus' ),
	)
);
$zeus_faqs = array(
	array(
		'q' => __( 'Is quartz natural stone?', 'zeus' ),
		'a' => __( 'No — quartz countertops are engineered, made from quartz mineral bound with resin, which is why the pattern and color are more consistent than natural stone.', 'zeus' ),
	),
	array(
		'q' => __( 'Does quartz need sealing?', 'zeus' ),
		'a' => __( 'Quartz is engineered and generally does not require the periodic sealing associated with many natural stones, but care requirements can vary by product.', 'zeus' ),
	),
	array(
		'q' => __( 'Can hot pans go directly on quartz?', 'zeus' ),
		'a' => __( 'We recommend using a trivet. The resin binder in quartz makes it more sensitive to sustained direct heat than natural stone.', 'zeus' ),
	),
	array(
		'q' => __( 'Is quartz suitable for bathrooms?', 'zeus' ),
		'a' => __( 'Yes — quartz is a common choice for bathroom vanities where low routine maintenance is a priority.', 'zeus' ),
	),
	array(
		'q' => __( 'Can ZEUS coordinate quartz with new cabinets?', 'zeus' ),
		'a' => __( 'Yes — cabinetry and countertop material are planned together, not chosen separately.', 'zeus' ),
	),
	array(
		'q' => __( 'What areas do you serve?', 'zeus' ),
		'a' => __( 'Orlando and nearby Central Florida communities, including Windermere, Winter Garden, Horizon West, Dr. Phillips, Clermont, and Lake Nona.', 'zeus' ),
	),
);
?>
	<div class="zeus-faq zeus-container--narrow" style="padding:0;">
		<?php foreach ( $zeus_faqs as $zeus_faq ) : ?>
			<details class="zeus-faq__item">
				<summary><?php echo esc_html( $zeus_faq['q'] ); ?></summary>
				<p><?php echo esc_html( $zeus_faq['a'] ); ?></p>
			</details>
		<?php endforeach; ?>
	</div>
<?php zeus_section_end(); ?>

<!-- 9. Final CTA / consultation form -->
<?php
zeus_section_start(
	array(
		'variant'   => 'compact',
		'eyebrow'   => __( 'Get Started', 'zeus' ),
		'heading'   => __( 'Considering Quartz for Your Project?', 'zeus' ),
		'intro'     => __( 'Send us your project details, approximate dimensions, or photos, and request a free consultation.', 'zeus' ),
		'container' => 'narrow',
	)
);
get_template_part( 'template-parts/consultation-form' );
zeus_section_end();
?>

<?php get_footer(); ?>
