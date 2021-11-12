<?php

use function Arti\ME\functions\get_companies_list;

add_filter( 'arti_mpme_wcfm_vendor_shipping_fields', function( $fields, $vendor_id ){

	$vendor_service_id = get_user_meta( $vendor_id ?? 0, '_me_vendor_free_service', true );
	$services = [ 0 => 'Nenhum' ] + get_companies_list();

	$fields['vendor_custom_services'] = [
		'id' => '_me_vendor_free_service',
		'name' => '_me_vendor_free_service',
		'label' => 'Serviço a ser usado em caso de frete grátis',
		'type' => 'select',
		'value' => $vendor_service_id,
		'class' => 'wcfm-select wcfm_ele',
		'label_class' => 'wcfm_title wcfm_ele',
		'options' => $services,
		'desc_class' => 'wcfm_page_options_desc',
		'desc' => 'Esse serviço será usado no momento de gerar etiquetas.',
	];

	return $fields;

}, 10, 2 );

add_filter( 'arti_mpme_after_wcmp_shipping_fields', function( $vendor_id ){

	$vendor_service_id = get_user_meta( $vendor_id ?? 0, '_me_vendor_free_service', true );
	$services = [ 0 => 'Nenhum' ] + get_companies_list();

	$fields->select_input(
		[
			'id' => '_me_vendor_free_service',
			'name' => '_me_vendor_free_service',
			'label' => 'Serviço a ser usado em caso de frete grátis',
			'value' => $vendor_service_id,
			'options' => $services,
			'desc' => 'Esse serviço será usado no momento de gerar etiquetas.',
		]
	);

} );

add_filter( 'arti_mpme_after_dokan_shipping_fields', function( $vendor_id ){

	$vendor_service_id = get_user_meta( $vendor_id ?? 0, '_me_vendor_free_service', true );
	$services = [ 0 => 'Nenhum' ] + get_companies_list();

   ?>
	<div class="dokan-form-group">
		<label class="dokan-w3 dokan-control-label" for="_me_vendor_free_service">
			Serviço a ser usado em caso de frete grátis
		</label>
		<div class="dokan-w5 dokan-text-left">
			<select class="dokan-form-control" id="_me_vendor_free_service" name="_me_vendor_free_service">
				<?php foreach( $services as $service_id => $service ):?>
					<option <?php selected( $service_id, $vendor_service_id ); ?> value=<?php echo esc_attr( $service_id );?>>
						<?php echo esc_html( $service ); ?>
					</option>
				<?php endforeach;?>
			</select>
			<p class="dokan-page-help">
				Esse serviço será usado no momento de gerar etiquetas.
			</p>
		</div>
	</div>
	<script>
		jQuery(function( $ ){
			$( '#_me_vendor_free_service' ).select2();
		});
	</script>
	<?php

} );
