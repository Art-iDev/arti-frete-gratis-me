<?php
/**
 * Abstract free shipping check for every marketplace supported.
 */

add_action( 'arti_mpme_provider_dokan_loaded', function(){
    add_filter( 'arti_fgme_get_vendor_shipping_key', function(){
        return 'dokan_vendor_shipping';
    } );
} );

add_action( 'arti_mpme_provider_wcfm_loaded', function(){
    add_filter( 'arti_fgme_get_vendor_shipping_key', function(){
        return 'wcfmmp_product_shipping_by_zone';
    } );
} );

add_action( 'arti_mpme_provider_wc-marketplace_loaded', function(){
    add_filter( 'arti_fgme_get_vendor_shipping_key', function(){
        return 'wcmp_vendor_shipping';
    } );
} );

add_filter( 'arti_fgme_cart_has_free_shipping', function( $cart_has_free_shipping, $free_shipping_methods, $methods ) {

    if( !$cart_has_free_shipping ){

        $free_shipping_methods = arti_fgme_get_accepted_methods();
        $method_ids = array_flip( $methods );
        $method_ids = preg_replace( '/\:[0-9]+/', '', $method_ids );

        $free_shipping_key = arti_fgme_get_vendor_shipping_key();

        return isset( $method_ids[$free_shipping_key] ) && arti_fgme_cart_has_free_shipping( $free_shipping_methods, $method_ids );

    }

    return $cart_has_free_shipping;

}, 10, 3 );

function arti_fgme_get_vendor_shipping_key(){
    return apply_filters( 'arti_fgme_get_vendor_shipping_key', '' );
}
