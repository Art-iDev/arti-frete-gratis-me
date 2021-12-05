<?php
/**
 * Plugin Name: Frete grátis para Art-i Melhor Envio/Marketplace
 * Description:Converter frete grátis do vendedor em cotação do Melhor Envio
 * Plugin URI: https://art-idesenvolvimento.com.br
 * Author: Luis Eduardo Braschi
 * Author URI: https://art-idesenvolvimento.com.br
 * Version: 0.1.1
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: Text Domain
 * Domain Path: Domain Path
 * Network: false
 */

defined( 'ABSPATH' ) || exit;

add_action( 'arti_mpme_init', function(){
    include_once 'fields.php';
} );

add_filter('arti_me_is_melhorenvio_method', function( $is_me_method, $method ){

    $free_sipping_methods = arti_fgme_get_accepted_methods();

    if( in_array( $method, $free_sipping_methods ) ){
        return true;
    }

    return $is_me_method;

}, 10, 2 );

add_filter( 'arti_me_shipping_service_id', function( $service_id, $package ){

    $vendor_id = $package['shipping_item']->get_meta( 'vendor_id', true ) ?? 0;

    if( $vendor_service_id = get_user_meta( $vendor_id, '_me_vendor_free_service', true ) ){
        $service_id = $vendor_service_id;
    }

    return $service_id;

}, 10, 2 );

add_filter( 'arti_mpme_vendor_fields_to_save', function( $fields ){
    $fields[] = '_me_vendor_free_service';
    return $fields;
} );

add_filter( 'woocommerce_package_rates', function( $rates, $package ){

    $methods = wp_list_pluck( $rates, 'method_id' );

    $free_sipping_methods = arti_fgme_get_accepted_methods();

    $service_id = 0;

    if( $vendor_service_id = get_user_meta( $package['vendor_id'] ?? 0, '_me_vendor_free_service', true ) ){
        $service_id = $vendor_service_id;
    }

    $cart_has_free_shipping = (bool)count( array_intersect( $free_sipping_methods, $methods ) );

    if( !$cart_has_free_shipping || !$service_id ){
        return $rates;
    }

    $vendor_rate = null;
    $meta_data_to_free_shipping_package = [];

    foreach( $rates as $key => &$rate ){

        $rate_meta_data = $rate->get_meta_data();

        if( in_array( $rate->get_method_id(), $free_sipping_methods ) ){
            $vendor_rate = &$rate;
        } else {

            $hide_other_methods = apply_filters( 'arti_frete_gratis_me_esconder_outros_metodos', false );

            if( $hide_other_methods ){
                unset( $rates[$key] );
            }

        }

        $rate_meta_service_id = $rate_meta_data['_service_id'] ?? -1;

        if( (int) $rate_meta_service_id === (int) $service_id ){
            $meta_data_to_free_shipping_package = $rate_meta_data;
            unset( $rates[$key] );
        }

    }

    foreach( $meta_data_to_free_shipping_package as $key => $meta_data ){
        $vendor_rate->add_meta_data( $key, $meta_data );
    }

    return $rates;

}, 15, 2 );

function arti_fgme_get_accepted_methods(){
    return apply_filters( 'arti_frete_gratis_me_metodos', [ 'free_shipping' ] );
}
