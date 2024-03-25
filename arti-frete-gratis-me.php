<?php
/**
 * Plugin Name: Frete grátis para Art-i Melhor Envio/Marketplace
 * Description:Converter frete grátis do vendedor em cotação do Melhor Envio
 * Plugin URI: https://art-idesenvolvimento.com.br
 * Author: Luis Eduardo Braschi
 * Author URI: https://art-idesenvolvimento.com.br
 * Version: 0.9.0
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: Text Domain
 * Domain Path: Domain Path
 * Network: false
 */

use function Arti\ME\functions\is_melhorenvio_method;

defined( 'ABSPATH' ) || exit;

add_action( 'arti_mpme_provider_loaded', function(){
	include_once 'fields.php';
	include_once 'marketplace-functions.php';
} );

add_filter( 'arti_mpme_vendor_fields_to_save', function( $fields ){
	$fields[] = '_me_vendor_free_service';
	return $fields;
} );

add_filter( 'woocommerce_package_rates', function( $rates, $package ){

	$service_id = 0;

	if( $vendor_service_id = get_user_meta( $package['vendor_id'] ?? 0, '_me_vendor_free_service', true ) ){
		$service_id = $vendor_service_id;
	}

	$free_shipping_methods = arti_fgme_get_accepted_methods();
	$methods = wp_list_pluck( $rates, 'method_id' );

	$cart_has_free_shipping = arti_fgme_cart_has_free_shipping( $free_shipping_methods, $methods );

	$cart_has_me = arti_fgme_cart_has_melhorenvio( $rates );

	if( !$cart_has_free_shipping || empty( $service_id ) || !$cart_has_me ){
		return $rates;
	}

	$free_shipping_rate = null;
	$cheapest_rate = null;
	$cheapest_cost = PHP_INT_MAX;
	$current_cost = 0;

	foreach( $rates as $key => &$rate ){

		$current_cost = $rate->get_cost();

		if(
			$current_cost < $cheapest_cost &&
			$rate->get_cost() > 0 &&
			is_melhorenvio_method( $rate->get_method_id() )
		){
			$cheapest_cost = $current_cost;
			$cheapest_rate = clone $rate;
		}

		$rate_service_id = $rate->get_meta_data()['_service_id'] ?? 0;

		$method_id = explode( ':', $rate->get_id() );
		$method_id = $method_id[0] ?? '';

		if(
			in_array( $rate->get_method_id(), $free_shipping_methods ) ||
			in_array( $method_id, $free_shipping_methods )
		){

			$free_shipping_label = $rate->get_label();
			$original_free_shipping_rate = $rate;
			unset( $rates[$key] );
		}

		if( (int) $rate_service_id === (int) $service_id ){

			$rate->set_cost( 0 );
			$free_shipping_rate = $rate;
			unset( $rates[$key] );

		} elseif( apply_filters( 'arti_frete_gratis_me_esconder_outros_metodos', false ) ){

			if( apply_filters( 'arti_frete_gratis_me_esconder_metodo', true, $method_id, $key ) ){
				unset( $rates[$key] );
			}

		}

	}

	if( !is_null( $free_shipping_rate ) ){
		$free_shipping_rate->set_label( $free_shipping_label );
	} elseif( apply_filters( 'arti_frete_gratis_me_usar_cotacao_mais_barata', false ) && is_null( $free_shipping_rate ) ){
		$cheapest_rate->set_label( $free_shipping_label );
		$cheapest_rate->set_cost( 0 );
		$free_shipping_rate = $cheapest_rate;
	} else {
		$free_shipping_rate = $original_free_shipping_rate;
	}

	$rates[$free_shipping_rate->get_id()] = $free_shipping_rate;

	return $rates;

}, 15, 2 );

function arti_fgme_get_accepted_methods(){
	return apply_filters( 'arti_frete_gratis_me_metodos', [ 'free_shipping' ] );
}

function arti_fgme_cart_has_free_shipping( $free_shipping_methods, $methods ){

	$cart_has_free_shipping = (bool) count( array_intersect( $free_shipping_methods, $methods ) );

	return apply_filters( 'arti_fgme_cart_has_free_shipping', $cart_has_free_shipping, $free_shipping_methods, $methods );

}

/**
 * Check for a method from Melhor Envio.
 * @param  WC_Shipping_Rate $rates
 * @return bool
 */
function arti_fgme_cart_has_melhorenvio( $rates ){

    foreach( $rates as $method ){
        if( is_melhorenvio_method( $method->get_method_id() ) ){
            return true;
        }
    }

    return false;

}
