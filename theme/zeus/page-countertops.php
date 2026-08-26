<?php
/**
 * Countertops hub (/countertops/) -- RC4D.
 * Auto-selected by WordPress's page-{slug}.php template hierarchy for
 * the "countertops" page (ID 12); no post-meta template assignment
 * needed. Routes to the four material pages (Quartz, Granite,
 * Porcelain, Marble).
 *
 * Positioning: commercial service page, not an encyclopedia article --
 * helps a homeowner compare materials and understand practical
 * tradeoffs, and makes clear ZEUS coordinates selection through
 * fabrication/installation. No exaggerated claims (indestructible,
 * maintenance-free, scratch/stain/heat-proof, "best material",
 * guaranteed lead times) anywhere on this page or its four children --
 * see docs/PROJECT-SPEC.md and docs/DECISIONS.md ("RC4D"). All four
 * material images (156-159) are generated category/lifestyle visuals
 * (see docs/ASSET-PROVENANCE.csv) and must never be presented as a
 * completed ZEUS project. No independently-verified real ZEUS
 * countertop-specific installation photo exists, so this page (and its
 * four children) deliberately has no "Real ZEUS Work" section --
 * inferring a material from a real kitchen photo (e.g. calling a real
 * white countertop "quartz") is exactly the kind of unverified claim
 * this project avoids.
 */
get_header();
zeus_render_breadcrumbs();

$zeus_page_quartz      = zeus_get_post_by_slug( 'quartz', 'page' );
$zeus_page_granite     = zeus_get_post_by_slug( 'granite', 'page' );
$zeus_page_porcelain   = zeus_get_post_by_slug( 'porcelain', 'page' );
$zeus_page_marble      = zeus_get_post_by_slug( 'marble', 'page' );
$zeus_page_cabinets    = zeus_get_post_by_slug( 'cabinets', 'page' );
$zeus_page_kitchen     = zeus_get_post_by_slug( 'kitchen-cabinets', 'page' );
$zeus_page_bath        = zeus_get_post_by_slug( 'bathroom-cabinets-vanities', 'page' );

// Hero: Marble Countertop Kitchen -- generated category/lifestyle
// visual (RC3B), chosen for the strongest broad opening statement for
// the hub. Never presented as a completed ZEUS project.
$zeus_hero_image_id = 159;
?>

<!-- 1. Hero -->
<section class="zeus-hero" aria-label="<?php esc_attr_e( 'Countertops in Orlando', 'zeus' ); ?>">
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
					'alt'           => __( 'Luxury kitchen with natural marble waterfall island and expressive veining', 'zeus' ),
				)
			);
			?>
		<?php endif; ?>
		<div class="zeus-hero__scrim" aria-hidden="true"></div>
	</div>
	<div class="zeus-container">
		<div class="zeus-hero__content">
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Countertops', 'zeus' ); ?></p>
			<h1><?php esc_html_e( 'Countertops in Orlando', 'zeus' ); ?></h1>
			<p class="zeus-hero__lede">
				<?php esc_html_e( 'Compare quartz, granite, porcelain and marble surfaces for your kitchen, bathroom or custom cabinetry project — with selection and installation coordinated through ZEUS.', 'zeus' ); ?>
			</p>
			<div class="zeus-cta__actions" style="justify-content:flex-start;">
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Request Free Consultation', 'zeus' ), 'url' => zeus_consultation_url(), 'variant' => 'primary' ) ); ?>
				<?php get_template_part( 'components/button', null, array( 'label' => __( 'Compare Materials', 'zeus' ), 'url' => '#zeus-compare', 'variant' => 'secondary', 'on_dark' => true ) ); ?>
			</div>
		</div>
	</div>
</section>

<!-- 2. Four material paths -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Materials', 'zeus' ),
		'heading' => __( 'Four Countertop Materials', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4">
		<?php
		get_template_part( 'components/card-service', null, array(
			'title'    => __( 'Quartz', 'zeus' ),
			'desc'     => __( 'Consistent color and pattern options with low routine maintenance.', 'zeus' ),
			'url'      => $zeus_page_quartz ? get_permalink( $zeus_page_quartz ) : home_url( '/countertops/quartz/' ),
			'image_id' => 156,
		) );
		get_template_part( 'components/card-service', null, array(
			'title'    => __( 'Granite', 'zeus' ),
			'desc'     => __( 'Natural stone with one-of-a-kind mineral movement and slab variation.', 'zeus' ),
			'url'      => $zeus_page_granite ? get_permalink( $zeus_page_granite ) : home_url( '/countertops/granite/' ),
			'image_id' => 157,
		) );
		get_template_part( 'components/card-service', null, array(
			'title'    => __( 'Porcelain', 'zeus' ),
			'desc'     => __( 'A modern slab option with a clean architectural look and large-format design possibilities.', 'zeus' ),
			'url'      => $zeus_page_porcelain ? get_permalink( $zeus_page_porcelain ) : home_url( '/countertops/porcelain/' ),
			'image_id' => 158,
		) );
		get_template_part( 'components/card-service', null, array(
			'title'    => __( 'Marble', 'zeus' ),
			'desc'     => __( 'Natural stone known for distinctive veining, depth and classic luxury.', 'zeus' ),
			'url'      => $zeus_page_marble ? get_permalink( $zeus_page_marble ) : home_url( '/countertops/marble/' ),
			'image_id' => 159,
		) );
		?>
	</div>
<?php zeus_section_end(); ?>

<!-- 3. Material comparison -->
<?php
zeus_section_start(
	array(
		'id'      => 'zeus-compare',
		'variant' => 'stone',
		'eyebrow' => __( 'Compare', 'zeus' ),
		'heading' => __( 'Which Countertop Material Fits Your Project?', 'zeus' ),
		'intro'   => __( 'A quick, practical comparison — not a technical specification chart. We\'ll go deeper on the tradeoffs that matter for your project during your consultation.', 'zeus' ),
	)
);
?>
	<div class="zeus-compare-wrap">
		<table class="zeus-compare-table">
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Category', 'zeus' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Quartz', 'zeus' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Granite', 'zeus' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Porcelain', 'zeus' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Marble', 'zeus' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'Natural vs. engineered', 'zeus' ); ?></th>
					<td><?php esc_html_e( 'Engineered', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Natural stone', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Manufactured slab', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Natural stone', 'zeus' ); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Pattern / variation', 'zeus' ); ?></th>
					<td><?php esc_html_e( 'More predictable pattern and color', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Every slab varies', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Consistent, contemporary appearance', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Distinctive natural veining', 'zeus' ); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Routine maintenance', 'zeus' ); ?></th>
					<td><?php esc_html_e( 'Low; avoid extreme direct heat', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'May benefit from periodic sealing depending on stone, fabrication and sealer', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Fabrication and edge details matter', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'More sensitive to acids and staining than many alternatives', 'zeus' ); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Design character', 'zeus' ); ?></th>
					<td><?php esc_html_e( 'Consistent, wide color range', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Strong natural-stone character', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Thin-profile and large-format possibilities', 'zeus' ); ?></td>
					<td><?php esc_html_e( 'Classic, expressive, and best for clients comfortable with care', 'zeus' ); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
<?php zeus_section_end(); ?>

<!-- 4. Coordinated with cabinetry -->
<?php zeus_section_start( array( 'variant' => 'compact' ) ); ?>
	<div class="zeus-grid zeus-grid--2" style="align-items:center;">
		<div>
			<?php echo wp_get_attachment_image( 161, 'zeus-card', false, array( 'style' => 'border-radius:var(--wp--custom--radius--medium); width:100%; height:auto;', 'alt' => 'Bathroom vanity with light stone countertop and undermount sink' ) ); ?>
		</div>
		<div>
			<p class="zeus-section__eyebrow"><?php esc_html_e( 'Coordinated Design', 'zeus' ); ?></p>
			<h2><?php esc_html_e( 'Cabinetry and Countertops, Planned Together', 'zeus' ); ?></h2>
			<p><?php esc_html_e( 'Your countertop material affects the overall palette of the room, and the edge profile, backsplash, and surface choice should coordinate with your cabinetry rather than being decided in isolation. ZEUS coordinates the cabinet and countertop portions of your project together.', 'zeus' ); ?></p>
			<p>
				<?php
				printf(
					/* translators: 1: link to Cabinets hub, 2: link to Kitchen Cabinets, 3: link to Bathroom Cabinets & Vanities */
					wp_kses_post( __( 'See our %1$s, including %2$s and %3$s.', 'zeus' ) ),
					'<a href="' . esc_url( $zeus_page_cabinets ? get_permalink( $zeus_page_cabinets ) : home_url( '/cabinets/' ) ) . '">' . esc_html__( 'cabinetry options', 'zeus' ) . '</a>',
					'<a href="' . esc_url( $zeus_page_kitchen ? get_permalink( $zeus_page_kitchen ) : home_url( '/cabinets/kitchen-cabinets/' ) ) . '">' . esc_html__( 'kitchen cabinets', 'zeus' ) . '</a>',
					'<a href="' . esc_url( $zeus_page_bath ? get_permalink( $zeus_page_bath ) : home_url( '/cabinets/bathroom-cabinets-vanities/' ) ) . '">' . esc_html__( 'bathroom vanities', 'zeus' ) . '</a>'
				);
				?>
			</p>
		</div>
	</div>
<?php zeus_section_end(); ?>

<!-- 5. Process -->
<?php
zeus_section_start(
	array(
		'variant' => 'stone',
		'eyebrow' => __( 'Process', 'zeus' ),
		'heading' => __( 'How the Countertop Process Works', 'zeus' ),
	)
);
?>
	<div class="zeus-grid zeus-grid--4 zeus-process">
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Consultation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Discuss your project, cabinetry, and the materials you\'re considering.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Material & Slab Selection', 'zeus' ); ?></h3><p><?php esc_html_e( 'Choose a material and, for natural stone, review the actual slab.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Final Measurement / Template', 'zeus' ); ?></h3><p><?php esc_html_e( 'Precise measurements are taken once cabinetry is in place.', 'zeus' ); ?></p></div>
		<div class="zeus-process__step"><h3><?php esc_html_e( 'Fabrication & Installation', 'zeus' ); ?></h3><p><?php esc_html_e( 'Professional fabrication and installation coordinated by ZEUS.', 'zeus' ); ?></p></div>
	</div>
	<p style="margin-top: var(--wp--preset--spacing--3);"><?php esc_html_e( 'Final measurement/template and fabrication are coordinated based on the selected material and project — not every material follows an identical technical process.', 'zeus' ); ?></p>
<?php zeus_section_end(); ?>

<!-- 6. Service area -->
<?php
zeus_section_start(
	array(
		'eyebrow' => __( 'Service Area', 'zeus' ),
		'heading' => __( 'Serving Orlando & Central Florida', 'zeus' ),
	)
);
?>
	<p>
		<?php
		esc_html_e(
			'ZEUS installs quartz, granite, porcelain, and marble countertops for homeowners throughout Orlando, and in nearby communities including Windermere, Winter Garden, Horizon West, Dr. Phillips, Clermont, and Lake Nona.',
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
		'heading' => __( 'Countertop Questions', 'zeus' ),
	)
);
$zeus_faqs = array(
	array(
		'q' => __( 'Which countertop material is easiest to maintain?', 'zeus' ),
		'a' => __( 'Quartz and porcelain generally require the least routine maintenance since neither needs periodic sealing. Granite and marble are natural stones that may benefit from sealing depending on the specific stone, and marble needs more care around acidic foods.', 'zeus' ),
	),
	array(
		'q' => __( 'What is the difference between quartz and granite?', 'zeus' ),
		'a' => __( 'Quartz is engineered, giving it more predictable pattern and color from slab to slab. Granite is natural stone, so every slab has genuinely unique mineral movement and variation.', 'zeus' ),
	),
	array(
		'q' => __( 'Is porcelain a good option for kitchen countertops?', 'zeus' ),
		'a' => __( 'Yes — porcelain slabs offer a modern, contemporary appearance and large-format design possibilities. Edge construction and fabrication details matter more with porcelain, so professional installation is important.', 'zeus' ),
	),
	array(
		'q' => __( 'Does marble require more care?', 'zeus' ),
		'a' => __( 'Yes. Marble is more sensitive to acids and staining than many alternatives, and it develops a natural patina over time. Many homeowners who choose marble are comfortable with that character and care.', 'zeus' ),
	),
	array(
		'q' => __( 'Can ZEUS coordinate cabinets and countertops together?', 'zeus' ),
		'a' => __( 'Yes — cabinetry and countertops are planned as one project, not two, so the finish, edge, and backsplash choices come together.', 'zeus' ),
	),
	array(
		'q' => __( 'What areas around Orlando do you serve?', 'zeus' ),
		'a' => __( 'Orlando and nearby Central Florida communities, including Windermere, Winter Garden, Horizon West, Dr. Phillips, Clermont, and Lake Nona. Call or text us if you\'re not sure your area is covered.', 'zeus' ),
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
		'heading'   => __( 'Need Help Choosing the Right Countertop?', 'zeus' ),
		'intro'     => __( 'Send us your project details, approximate dimensions, or photos, and request a free consultation.', 'zeus' ),
		'container' => 'narrow',
	)
);
get_template_part( 'template-parts/consultation-form' );
zeus_section_end();
?>

<?php get_footer(); ?>
