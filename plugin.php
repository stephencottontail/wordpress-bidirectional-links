<?php
/**
 * Plugin Name: Bidirectional Links
 * Description: Automatically create links between posts
 * Author: Stephen Dickinson <stephencottontail@me.com>
 * Version: 0.0.1
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

add_action( 'save_post', function( $post_id ) {
    if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
        return;
    }

    $post_data = get_post( $post_id );
    $url = get_site_url();
    $content = get_post_field( 'post_content', $post_id );
    $regex = '&(\w+[\s\p{P}]*)<a[^<>]+?href="(' . $url . '[^<>]+?\/)".*?>((?:.(?!\<\/a\>w))*.)\<\/a>([\s\p{P}]*\w+)&';
    preg_match_all( $regex, $content, $matches );

    for ( $i = 0; $i < count( $matches[1] ); $i++ ) {
        $object = [];
        $object[$post_id]['title'] = get_the_title( $post_id );
        $object[$post_id]['permalink'] = get_the_permalink( $post_id );
        $object[$post_id]['context'] = wp_kses( preg_replace( '&<a[^<>]+?href=".+?".*?>((?:.(?!\<\/a\>w))*.)\<\/a>&', '<strong>$1</strong>', $matches[0][$i] ), array( 'strong' => array() ) );

        $id = url_to_postid( $matches[2][$i] );
        $previous = get_post_meta( $id, 'sc_bidirectional_links', true );

        if ( $previous ) {
            update_post_meta( $id, 'sc_bidirectional_links', $object + $previous );
        } else {
            add_post_meta( $id, 'sc_bidirectional_links', $object );
        }
    }
} );

