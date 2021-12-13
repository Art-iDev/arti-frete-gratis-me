<?php
/**
 * Abstract free shipping check for every marketplace supported.
 */

add_action( 'arti_mpme_provider_dokan_loaded', function(){
    add_filter( 'arti_fgme_cart_has_free_shipping', function( $cart_has_free_shipping, $free_shipping_methods, $methods ) {

        if( !$cart_has_free_shipping ){

            $free_shipping_methods = arti_fgme_get_accepted_methods();
            $method_ids = array_flip( $methods );
            $method_ids = preg_replace( '/\:[0-9]+/', '', $method_ids );

            return isset( $method_ids['dokan_vendor_shipping'] ) && arti_fgme_cart_has_free_shipping( $free_shipping_methods, $method_ids );

        }

        return $cart_has_free_shipping;

    }, 10, 3 );
} );

add_action( 'arti_mpme_provider_wcfm_loaded', function(){
    add_filter( 'arti_fgme_cart_has_free_shipping', function( $cart_has_free_shipping, $free_shipping_methods, $methods ) {

        if( !$cart_has_free_shipping ){

            $free_shipping_methods = arti_fgme_get_accepted_methods();
            $method_ids = array_flip( $methods );
            $method_ids = preg_replace( '/\:[0-9]+/', '', $method_ids );
            write_log($method_ids);
            return isset( $method_ids['wcfmmp_product_shipping_by_zone'] ) && arti_fgme_cart_has_free_shipping( $free_shipping_methods, $method_ids );

        }

        return $cart_has_free_shipping;

    }, 10, 3 );
} );
