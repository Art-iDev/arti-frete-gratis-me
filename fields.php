<?php

use function Arti\ME\functions\get_companies_list;

function get_services_list(){
	return [ 0 => 'Nenhum' ] + get_companies_list();
}

add_filter( 'arti_mpme_after_wcfm_shipping_fields', function( $vendor_id ){

	global $WCFM;

	$vendor_service_id = get_user_meta( $vendor_id ?? 0, '_me_vendor_free_service', true );

	$fields['vendor_custom_services'] = [
		'id' => '_me_vendor_free_service',
		'name' => '_me_vendor_free_service',
		'label' => 'Serviço a ser usado em caso de frete grátis',
		'type' => 'select',
		'value' => $vendor_service_id,
		'class' => 'wcfm-select wcfm_ele',
		'label_class' => 'wcfm_title wcfm_ele',
		'options' => get_services_list(),
		'desc_class' => 'wcfm_page_options_desc',
		'desc' => 'Esse serviço será usado no momento de gerar etiquetas.',
	];

	?>
	<div class="store_address">
		<?php $WCFM->wcfm_fields->wcfm_generate_form_field( $fields ); ?>
	</div>
	<?php

}, 10, 2 );


add_filter( 'arti_mpme_after_mvx_shipping_fields', function( $vendor_id ){

	$vendor_service_id = get_user_meta( $vendor_id ?? 0, '_me_vendor_free_service', true );

	?>
	<div class="form-group">
	    <label class="w3 control-label col-sm-3 col-md-3" for="_me_vendor_free_service">
	    	Serviço a ser usado em caso de frete grátis
	    </label>
	    <div class="col-md-6 col-sm-9">
	        <select class="form-control" id="_me_vendor_free_service" name="_me_vendor_free_service">
	            <?php foreach( get_services_list() as $service_id => $service ):?>
	                <option <?php selected( $service_id, $vendor_service_id ); ?> value=<?php echo esc_attr( $service_id );?>>
	                    <?php echo esc_html( $service ); ?>
	                </option>
	            <?php endforeach;?>
	        </select>
	        <p class="page-help">
	        	<?php esc_html_e( 'Select the services you want to use for shipping.', 'arti-marketplace-melhorenvio' );?>
	        </p>
	    </div>
	</div>
	<?php

});
add_filter( 'arti_mpme_after_wcmp_shipping_fields', function( $vendor_id ){

	$vendor_service_id = get_user_meta( $vendor_id ?? 0, '_me_vendor_free_service', true );

	$fields = new WCMp_Frontend_WP_Fields;

	$fields->select_input(
		[
			'id' => '_me_vendor_free_service',
			'name' => '_me_vendor_free_service',
			'label' => 'Serviço a ser usado em caso de frete grátis',
			'value' => $vendor_service_id,
			'options' => get_services_list(),
			'desc' => 'Esse serviço será usado no momento de gerar etiquetas.',
		]
	);

} );

add_filter( 'arti_mpme_after_dokan_shipping_fields', function( $vendor_id ){

	$vendor_service_id = get_user_meta( $vendor_id ?? 0, '_me_vendor_free_service', true );

   ?>
	<div class="dokan-form-group">
		<label class="dokan-w3 dokan-control-label" for="_me_vendor_free_service">
			Serviço a ser usado em caso de frete grátis
		</label>
		<div class="dokan-w5 dokan-text-left">
			<select class="dokan-form-control" id="_me_vendor_free_service" name="_me_vendor_free_service">
				<?php foreach( get_services_list() as $service_id => $service ):?>
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
