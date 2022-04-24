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

add_action( 'save_post', 'sc_create_bidirectional_links' );
function sc_create_bidirectional_links( $post_id ) {
    $post_data = get_post( $post_id );

    if ( ! $post_data->post_type == 'post' || ! $post_data->post_status == 'publish' ) {
        return;
    }

	$url = get_site_url();
	$content = get_post_field( 'post_content', $post_id );
	$regex = '&<a[^<>]+?href="(' . $url . '[^<>]+?\/)".*?>((?:.(?!\<\/a\>))*.)\<\/a>&';
	preg_match_all( $regex, $content, $matches );

    for ( $i = 0; $i < count( $matches[1] ); $i++ ) {
        $object = [];
        $object['id'] = $post_id;
        $object['title'] = get_the_title( $post_id );
        $object['permalink'] = get_the_permalink( $post_id );

        $id = url_to_postid( $matches[1][$i] );
        $previous = get_post_meta( $id, 'sc_bidirectionali_links' );
        update_post_meta( $id, 'sc_bidirectional_links', array_merge( $object, $previous ) );
    }
}
