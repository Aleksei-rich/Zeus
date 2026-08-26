<?php
/**
 * Home Office service page (/custom-spaces/home-office/) -- RC4C.
 * Auto-selected by WordPress's page-{slug}.php template hierarchy for
 * the "home-office" page (ID 20); no post-meta template assignment
 * needed. Purely custom cabinetry positioning -- no in-stock messaging.
 * No independently-verified real ZEUS home-office installation photo
 * exists in the approved media library, so this page has no "Real ZEUS
 * Work" section -- attachment 120 is dealer-provided lifestyle/product
 * media and must never be presented as completed ZEUS work (see
 * docs/ASSET-PROVENANCE.csv). Attachment 112 ("Brooklyn Gray Home
 * Office") was deliberately NOT used here despite visually showing an
 * office -- its underlying file is named brooklyn-gray-kitchen-01,
 * which risks reading as a kitchen-image leak on this page; a text-only
 * "Style & Function" section was used instead rather than risk that
 * ambiguity.
 */
get_header();
zeus_render_breadcrumbs();

$zeus_page_custom_spaces = zeus_get_post_by_slug( 'custom-spaces', 'page' );

$zeus_hero_image_id = 120;
?>

<!-- 1. Hero -->
<section class="zeus-hero" aria-label="<?php esc_attr_e( 'Custom Home Office Cabinetry in Orlando', 'zeus' ); ?>">
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
					'alt'           => __( 'Built-in home office cabinetry with a wall-to-wall desk and glass-front upper cabinets', 'zeus' ),
				)
			);
			?>
		<?php endif; ?>
		<div class="zeus-hero__scrim" aria-hidden="true"></div>
	</div>
	<div class="zeus-container">
		<div class="zeus-hero__content">
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Home Office', 'zeus' ); ?></p>
			<h1><?php esc_html_e( 'Custom Home Office Cabinetry in Orlando', 'zeus' ); ?></h1>
			<p class="zeus-hero__lede">
				<?php esc_html_e( 'Built-in desks, shelving, and storage designed to create a functional, integrated workspace at home.', 'zeus' ); ?>
			</p>
			<div class="zeus-cta__actions" style="justify-content:flex-start;">
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Request Free Consultation', 'zeus' ), 'url' => zeus_consultation_url(), 'variant' => 'primary' ) ); ?>
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Explore Custom Spaces', 'zeus' ), 'url' => $zeus_page_custom_spaces ? get_permalink( $zeus_page_custom_spaces ) : home_url( '/custom-spaces/' ), 'variant' => 'secondary', 'on_dark' => true ) ); ?>
			</div>
		</div>
	</div>
</section>

<!-- 2. Workspace design -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Workspace Design', 'zeus' ),
		'heading' => __( 'A Workspace Designed Around the Room', 'zeus' ),
		'intro'   => __( 'A built-in desk, cabinetry, and shelving planned together — closed storage for files and equipment, open shelving for what you want visible, and a layout that can run wall-to-wall when the room allows.', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--3">
		<div><h3><?php esc_html_e( 'Built-In Desk', 'zeus' ); ?></h3><p><?php esc_html_e( 'A desk surface integrated with the surrounding cabinetry, not a separate piece of furniture.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Closed & Open Storage', 'zeus' ); ?></h3><p><?php esc_html_e( 'Cabinets for files and equipment, open shelving for books and display.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Vertical Storage', 'zeus' ); ?></h3><p><?php esc_html_e( 'Storage that uses the full wall height instead of stopping at desk level.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 3. Style / function -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Style & Function', 'zeus' ),
		'heading' => __( 'Cabinetry Coordinated With Your Home', 'zeus' ),
		'intro'   => __( 'Home office cabinetry can be finished to match the rest of your home or to stand on its own — glass-front uppers, closed lower cabinets, and an integrated desk surface are all options we plan around your room and how you work.', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--3">
		<div><h3><?php esc_html_e( 'Coordinated Finish', 'zeus' ); ?></h3><p><?php esc_html_e( 'Match your home\'s existing cabinetry, or choose a finish that stands on its own.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Glass-Front or Closed', 'zeus' ); ?></h3><p><?php esc_html_e( 'Display shelving behind glass, fully closed storage, or a mix of both.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Integrated Desk Surface', 'zeus' ); ?></h3><p><?php esc_html_e( 'A desk built into the cabinetry run rather than a separate piece of furniture.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 4. Use cases -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Use Cases', 'zeus' ),
		'heading' => __( 'Home Office Layouts We Build', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4">
		<div><h3><?php esc_html_e( 'Dedicated Home Office', 'zeus' ); ?></h3></div>
		<div><h3><?php esc_html_e( 'Study / Homework Space', 'zeus' ); ?></h3></div>
		<div><h3><?php esc_html_e( 'Wall-to-Wall Built-In', 'zeus' ); ?></h3></div>
		<div><h3><?php esc_html_e( 'Office + Storage Combination', 'zeus' ); ?></h3></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 5. Process -->
<?php
zeus_section_start(
	array(
		'variant' => 'compact',
		'eyebrow' => __( 'Process', 'zeus' ),
		'heading' => __( 'From Consultation to Installation', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4 zeus-process">
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Consultation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Discuss how you work and what the space needs to do.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Measure', 'zeus' ); ?></h3><p><?php esc_html_e( 'Exact room dimensions and available wall space.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Design', 'zeus' ); ?></h3><p><?php esc_html_e( 'A layout planned around your equipment, storage, and workflow.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Installation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Professional delivery and installation coordinated by ZEUS.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 6. Service area -->
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
			'ZEUS builds custom home office cabinetry for homeowners throughout Orlando, and in nearby communities including Windermere, Winter Garden, Horizon West, Dr. Phillips, Clermont, and Lake Nona.',
			'zeus'
		);
		?>
	</p>
	<p class="zeus-service-area__note">
		<?php esc_html_e( "Not sure if you're in our service area?", 'zeus' ); ?>
		<a href="<?php echo esc_url( zeus_phone_number_href() ); ?>"><?php esc_html_e( 'Call or text us.', 'zeus' ); ?></a>
	</p>
<?php zeus_section_end(); ?>

<!-- 7. FAQ -->
<?php
zeus_section_start(
	array(
		'variant' => 'compact',
		'eyebrow' => __( 'FAQ', 'zeus' ),
		'heading' => __( 'Home Office Questions', 'zeus' ),
	)
);
$zeus_faqs = array(
	array(
		'q' => __( 'Can you build a wall-to-wall home office?', 'zeus' ),
		'a' => __( 'Yes, when the room allows for it — cabinetry and desk surfaces can run the full wall.', 'zeus' ),
	),
	array(
		'q' => __( 'Can the design include a built-in desk?', 'zeus' ),
		'a' => __( 'Yes, a built-in desk surface integrated with the surrounding cabinetry is a common part of these projects.', 'zeus' ),
	),
	array(
		'q' => __( 'Can you add both open shelves and closed storage?', 'zeus' ),
		'a' => __( 'Yes, a mix of open shelving and closed cabinetry is planned around what you want visible versus stored away.', 'zeus' ),
	),
	array(
		'q' => __( 'Can you design cabinetry for a small office?', 'zeus' ),
		'a' => __( 'Yes — custom cabinetry is often the better fit for a small or oddly-shaped office nook.', 'zeus' ),
	),
	array(
		'q' => __( 'Can the office cabinetry coordinate with the rest of the home?', 'zeus' ),
		'a' => __( 'Yes, it can be finished to match your home\'s existing cabinetry or stand on its own.', 'zeus' ),
	),
	array(
		'q' => __( 'What areas around Orlando do you serve?', 'zeus' ),
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

<!-- 8. Final CTA / consultation form -->
<?php
zeus_section_start(
	array(
		'variant'   => 'compact',
		'eyebrow'   => __( 'Get Started', 'zeus' ),
		'heading'   => __( 'Ready to Build a Better Home Office?', 'zeus' ),
		'intro'     => __( 'Send us your project details, approximate dimensions, or photos, and request a free consultation.', 'zeus' ),
		'container' => 'narrow',
	)
);
get_template_part( 'template-parts/consultation-form' );
zeus_section_end();
?>

<?php get_footer(); ?>
