<?php
 function block_core_comment_template_render_comments( $comments, $block ) { global $comment_depth; if ( empty( $comment_depth ) ) { $comment_depth = 1; } $content = ''; foreach ( $comments as $comment ) { $block_content = ( new WP_Block( $block->parsed_block, array( 'commentId' => $comment->comment_ID, ) ) )->render( array( 'dynamic' => false ) ); $children = $comment->get_children(); $comment_classes = comment_class( '', $comment->comment_ID, $comment->comment_post_ID, false ); if ( ! empty( $children ) ) { $comment_depth += 1; $inner_content = block_core_comment_template_render_comments( $children, $block ); $block_content .= sprintf( '<ol>%1$s</ol>', $inner_content ); $comment_depth -= 1; } $content .= sprintf( '<li id="comment-%1$s" %2$s>%3$s</li>', $comment->comment_ID, $comment_classes, $block_content ); } return $content; } function render_block_core_comment_template( $attributes, $content, $block ) { if ( empty( $block->context['postId'] ) ) { return ''; } if ( post_password_required( $block->context['postId'] ) ) { return; } $comment_query = new WP_Comment_Query( build_comment_query_vars_from_block( $block ) ); $comments = $comment_query->get_comments(); if ( count( $comments ) === 0 ) { return ''; } $comment_order = get_option( 'comment_order' ); if ( 'desc' === $comment_order ) { $comments = array_reverse( $comments ); } $wrapper_attributes = get_block_wrapper_attributes(); return sprintf( '<ol %1$s>%2$s</ol>', $wrapper_attributes, block_core_comment_template_render_comments( $comments, $block ) ); } function register_block_core_comment_template() { register_block_type_from_metadata( __DIR__ . '/comment-template', array( 'render_callback' => 'render_block_core_comment_template', 'skip_inner_blocks' => true, ) ); } add_action( 'init', 'register_block_core_comment_template' ); 