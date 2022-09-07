<?php
 function block_core_home_link_build_css_colors( $context ) { $colors = array( 'css_classes' => array(), 'inline_styles' => '', ); $has_named_text_color = array_key_exists( 'textColor', $context ); $has_custom_text_color = isset( $context['style']['color']['text'] ); if ( $has_custom_text_color || $has_named_text_color ) { $colors['css_classes'][] = 'has-text-color'; } if ( $has_named_text_color ) { $colors['css_classes'][] = sprintf( 'has-%s-color', $context['textColor'] ); } elseif ( $has_custom_text_color ) { $colors['inline_styles'] .= sprintf( 'color: %s;', $context['style']['color']['text'] ); } $has_named_background_color = array_key_exists( 'backgroundColor', $context ); $has_custom_background_color = isset( $context['style']['color']['background'] ); if ( $has_custom_background_color || $has_named_background_color ) { $colors['css_classes'][] = 'has-background'; } if ( $has_named_background_color ) { $colors['css_classes'][] = sprintf( 'has-%s-background-color', $context['backgroundColor'] ); } elseif ( $has_custom_background_color ) { $colors['inline_styles'] .= sprintf( 'background-color: %s;', $context['style']['color']['background'] ); } return $colors; } function block_core_home_link_build_css_font_sizes( $context ) { $font_sizes = array( 'css_classes' => array(), 'inline_styles' => '', ); $has_named_font_size = array_key_exists( 'fontSize', $context ); $has_custom_font_size = isset( $context['style']['typography']['fontSize'] ); if ( $has_named_font_size ) { $font_sizes['css_classes'][] = sprintf( 'has-%s-font-size', $context['fontSize'] ); } elseif ( $has_custom_font_size ) { $font_sizes['inline_styles'] = sprintf( 'font-size: %s;', $context['style']['typography']['fontSize'] ); } return $font_sizes; } function block_core_home_link_build_li_wrapper_attributes( $context ) { $colors = block_core_home_link_build_css_colors( $context ); $font_sizes = block_core_home_link_build_css_font_sizes( $context ); $classes = array_merge( $colors['css_classes'], $font_sizes['css_classes'] ); $style_attribute = ( $colors['inline_styles'] . $font_sizes['inline_styles'] ); $css_classes = trim( implode( ' ', $classes ) ) . ' wp-block-navigation-item'; $wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $css_classes, 'style' => $style_attribute, ) ); return $wrapper_attributes; } function render_block_core_home_link( $attributes, $content, $block ) { if ( empty( $attributes['label'] ) ) { return ''; } $wrapper_attributes = block_core_home_link_build_li_wrapper_attributes( $block->context ); $aria_current = is_home() || ( is_front_page() && 'page' === get_option( 'show_on_front' ) ) ? ' aria-current="page"' : ''; $html = '<li ' . $wrapper_attributes . '><a class="wp-block-home-link__content wp-block-navigation-item__content" rel="home"' . $aria_current; $html .= ' href="' . esc_url( home_url() ) . '"'; $html .= '>'; if ( isset( $attributes['label'] ) ) { $html .= wp_kses_post( $attributes['label'] ); } $html .= '</a></li>'; return $html; } function register_block_core_home_link() { register_block_type_from_metadata( __DIR__ . '/home-link', array( 'render_callback' => 'render_block_core_home_link', ) ); } add_action( 'init', 'register_block_core_home_link' ); 