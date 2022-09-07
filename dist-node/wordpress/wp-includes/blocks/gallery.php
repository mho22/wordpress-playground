<?php
 function block_core_gallery_data_id_backcompatibility( $parsed_block ) { if ( 'core/gallery' === $parsed_block['blockName'] ) { foreach ( $parsed_block['innerBlocks'] as $key => $inner_block ) { if ( 'core/image' === $inner_block['blockName'] ) { if ( ! isset( $parsed_block['innerBlocks'][ $key ]['attrs']['data-id'] ) && isset( $inner_block['attrs']['id'] ) ) { $parsed_block['innerBlocks'][ $key ]['attrs']['data-id'] = esc_attr( $inner_block['attrs']['id'] ); } } } } return $parsed_block; } add_filter( 'render_block_data', 'block_core_gallery_data_id_backcompatibility' ); function block_core_gallery_render( $attributes, $content ) { $gap = _wp_array_get( $attributes, array( 'style', 'spacing', 'blockGap' ) ); if ( is_array( $gap ) ) { foreach ( $gap as $key => $value ) { $gap[ $key ] = $value && preg_match( '%[\\\(&=}]|/\*%', $value ) ? null : $value; } } else { $gap = $gap && preg_match( '%[\\\(&=}]|/\*%', $gap ) ? null : $gap; } $class = wp_unique_id( 'wp-block-gallery-' ); $content = preg_replace( '/' . preg_quote( 'class="', '/' ) . '/', 'class="' . $class . ' ', $content, 1 ); $fallback_gap = 'var( --wp--style--gallery-gap-default, var( --gallery-block--gutter-size, var( --wp--style--block-gap, 0.5em ) ) )'; $gap_value = $gap ? $gap : $fallback_gap; $gap_column = $gap_value; if ( is_array( $gap_value ) ) { $gap_row = isset( $gap_value['top'] ) ? $gap_value['top'] : $fallback_gap; $gap_column = isset( $gap_value['left'] ) ? $gap_value['left'] : $fallback_gap; $gap_value = $gap_row === $gap_column ? $gap_row : $gap_row . ' ' . $gap_column; } $style = '.' . $class . '{ --wp--style--unstable-gallery-gap: ' . $gap_column . '; gap: ' . $gap_value . '}'; add_action( 'wp_footer', function () use ( $style ) { echo '<style> ' . $style . '</style>'; }, 11 ); return $content; } function register_block_core_gallery() { register_block_type_from_metadata( __DIR__ . '/gallery', array( 'render_callback' => 'block_core_gallery_render', ) ); } add_action( 'init', 'register_block_core_gallery' ); 