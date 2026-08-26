<?php
/**
 * Laundry & Pantry service page (/custom-spaces/laundry-pantry/) --
 * RC4C. Auto-selected by WordPress's page-{slug}.php template hierarchy
 * for the "laundry-pantry" page (ID 19); no post-meta template
 * assignment needed. Purely custom cabinetry positioning -- no in-stock
 * messaging. No independently-verified real ZEUS laundry/pantry photo
 * exists in the approved media library, so this page has no "Real ZEUS
 * Work" section -- attachment 155 is a generated category/lifestyle
 * visual and must never be presented as a completed ZEUS project (see
 * docs/ASSET-PROVENANCE.csv).
 */
get_header();
zeus_render_breadcrumbs();

$zeus_page_custom_spaces = zeus_get_post_by_slug( 'custom-spaces', 'page' );
$zeus_page_countertops   = zeus_get_post_by_slug( 'countertops', 'page' );

$zeus_hero_image_id = 155;
?>

<!-- 1. Hero -->
<section class="zeus-hero" aria-label="<?php esc_attr_e( 'Laundry & Pantry Cabinetry in Orlando', 'zeus' ); ?>">
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
					'alt'           => __( 'Custom laundry room cabinetry with an island, open shelving, and built-in appliance storage', 'zeus' ),
				)
			);
			?>
		<?php endif; ?>
		<div class="zeus-hero__scrim" aria-hidden="true"></div>
	</div>
	<div class="zeus-container">
		<div class="zeus-hero__content">
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Laundry & Pantry', 'zeus' ); ?></p>
			<h1><?php esc_html_e( 'Laundry & Pantry Cabinetry in Orlando', 'zeus' ); ?></h1>
			<p class="zeus-hero__lede">
				<?php esc_html_e( 'Custom storage designed around appliances, supplies, food storage, and the way you use the room every day.', 'zeus' ); ?>
			</p>
			<div class="zeus-cta__actions" style="justify-content:flex-start;">
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Request Free Consultation', 'zeus' ), 'url' => zeus_consultation_url(), 'variant' => 'primary' ) ); ?>
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Explore Custom Spaces', 'zeus' ), 'url' => $zeus_page_custom_spaces ? get_permalink( $zeus_page_custom_spaces ) : home_url( '/custom-spaces/' ), 'variant' => 'secondary', 'on_dark' => true ) ); ?>
			</div>
		</div>
	</div>
</section>

<!-- 2. Laundry -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Laundry Rooms', 'zeus' ),
		'heading' => __( 'Make the Laundry Room Work Harder', 'zeus' ),
		'intro'   => __( 'Cabinetry planned around your washer and dryer, with storage for detergent and supplies above or beside the appliances, tall utility storage, and a folding surface where the room allows.', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--3">
		<div><h3><?php esc_html_e( 'Appliance-Ready Cabinetry', 'zeus' ); ?></h3><p><?php esc_html_e( 'Cabinets planned around your washer and dryer, not just placed nearby.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Tall Utility Storage', 'zeus' ); ?></h3><p><?php esc_html_e( 'Vertical storage for cleaning supplies and household items.', 'zeus' ); ?></p></div>
		<div><h3><?php esc_html_e( 'Work Surface', 'zeus' ); ?></h3><p><?php esc_html_e( 'A folding or sorting surface where the room has space for one.', 'zeus' ); ?></p></div>
	</div>
<?php zeus_section_end(); ?>

<!-- 3. Pantry -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Pantries', 'zeus' ),
		'heading' => __( 'Pantry Storage That Uses the Space Better', 'zeus' ),
		'intro'   => __( 'A mix of shelving, tall cabinetry, and drawers built around how you actually shop, cook, and store food — including small-appliance storage and a blend of open and closed storage where it makes sense.', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--2" style="align-items:center;">
		<div>
			<?php echo wp_get_attachment_image( 155, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto;', 'alt' => 'Built-in cabinetry and shelving used for laundry and pantry storage' ) ); ?>
		</div>
		<div>
			<p><?php esc_html_e( 'Whether it\'s a dedicated pantry room or cabinetry built into a laundry or kitchen-adjacent space, the goal is the same: shelving and drawers sized to what you actually store, so nothing gets lost at the back of a deep shelf.', 'zeus' ); ?></p>
		</div>
	</div>
<?php zeus_section_end(); ?>

<!-- 4. Process -->
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
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Consultation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Discuss your appliances, storage needs, and the room.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Measure', 'zeus' ); ?></h3><p><?php esc_html_e( 'Exact room dimensions and appliance placement.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Design', 'zeus' ); ?></h3><p><?php esc_html_e( 'A layout planned around your appliances, supplies, and routine.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Installation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Professional delivery and installation coordinated by ZEUS.', 'zeus' ); ?></p></div>
	</div>
	<?php if ( $zeus_page_countertops ) : ?>
	<p style="margin-top: var(--wp--preset--spacing--3);">
		<?php
		printf(
			/* translators: %s: link to the countertops page */
			esc_html__( 'A folding or work-surface top can also be coordinated as part of the project — see our %s.', 'zeus' ),
			'<a href="' . esc_url( get_permalink( $zeus_page_countertops ) ) . '">' . esc_html__( 'countertop materials', 'zeus' ) . '</a>'
		);
		?>
	</p>
	<?php endif; ?>
<?php zeus_section_end(); ?>

<!-- 5. Service area -->
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
			'ZEUS builds custom laundry and pantry cabinetry for homeowners throughout Orlando, and in nearby communities including Windermere, Winter Garden, Horizon West, Dr. Phillips, Clermont, and Lake Nona.',
			'zeus'
		);
		?>
	</p>
	<p class="zeus-service-area__note">
		<?php esc_html_e( "Not sure if you're in our service area?", 'zeus' ); ?>
		<a href="<?php echo esc_url( zeus_phone_number_href() ); ?>"><?php esc_html_e( 'Call or text us.', 'zeus' ); ?></a>
	</p>
<?php zeus_section_end(); ?>

<!-- 6. FAQ -->
<?php
zeus_section_start(
	array(
		'variant' => 'compact',
		'eyebrow' => __( 'FAQ', 'zeus' ),
		'heading' => __( 'Laundry & Pantry Questions', 'zeus' ),
	)
);
$zeus_faqs = array(
	array(
		'q' => __( 'Can you design cabinetry around my washer and dryer?', 'zeus' ),
		'a' => __( 'Yes — cabinetry is planned around your specific appliances rather than a generic layout.', 'zeus' ),
	),
	array(
		'q' => __( 'Can you add tall storage to a laundry room?', 'zeus' ),
		'a' => __( 'Yes, vertical utility storage is a common part of laundry cabinetry.', 'zeus' ),
	),
	array(
		'q' => __( 'Do you build custom pantry cabinetry?', 'zeus' ),
		'a' => __( 'Yes, for dedicated pantry rooms and cabinetry built into adjacent spaces.', 'zeus' ),
	),
	array(
		'q' => __( 'Can pantry cabinetry include drawers and shelves?', 'zeus' ),
		'a' => __( 'Yes, a mix of shelving, drawers, and tall cabinetry is planned around what you store.', 'zeus' ),
	),
	array(
		'q' => __( 'Can you work with small utility rooms?', 'zeus' ),
		'a' => __( 'Yes — custom cabinetry is often the better fit for a small or awkward-shaped utility room.', 'zeus' ),
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

<!-- 7. Final CTA / consultation form -->
<?php
zeus_section_start(
	array(
		'variant'   => 'compact',
		'eyebrow'   => __( 'Get Started', 'zeus' ),
		'heading'   => __( 'Need More Storage From Your Laundry or Pantry?', 'zeus' ),
		'intro'     => __( 'Send us your project details, approximate dimensions, or photos, and request a free consultation.', 'zeus' ),
		'container' => 'narrow',
	)
);
get_template_part( 'template-parts/consultation-form' );
zeus_section_end();
?>

<?php get_footer(); ?>
