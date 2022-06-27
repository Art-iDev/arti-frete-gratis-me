<?php
/**
 * Abstract free shipping check for every marketplace supported.
 */
add_filter( 'arti_fgme_cart_has_free_shipping', function( $cart_has_free_shipping, $free_shipping_methods, $methods ) {

    if( !$cart_has_free_shipping ){

        $free_shipping_methods = arti_fgme_get_accepted_methods();
        $method_ids = array_keys( $methods );
        $method_ids = preg_replace( '/\:[0-9]+/', '', $method_ids );

        return !empty( array_intersect( $free_shipping_methods, $method_ids ) );

    }

    return $cart_has_free_shipping;

}, 10, 3 );
