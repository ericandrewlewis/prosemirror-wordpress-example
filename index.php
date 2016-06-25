<?php
/*
Plugin Name: WordPress ProseMirror Example Plugin
Description: A proof-of-concept to show you how to use ProseMirror in a plugin.
Author: Eric Andrew Lewis
Version: 0.1
Author URI: https://ericandrewlewis.com/
*/

define( 'WPEP_VERSION', '0.1' );

function wpep_add_prosemirror_css() {
  echo '<style>
  #prosemirror-editor { border: 1px solid silver;}
  .ProseMirror-content { padding: 0 5px; }</style>';
}
add_action( 'admin_head-post.php', 'wpep_add_prosemirror_css' );
add_action( 'admin_head-post-new.php', 'wpep_add_prosemirror_css' );

function wpep_enqueue_scripts($hook_suffix) {
  // Only enqueue the script on the Edit Post screen.
  if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) ) {
    return;
  }
  // echo 1;die;
  // var_dump( $hook_suffix );die;
  wp_enqueue_script( 'wpep', plugins_url( '/dist/index.js', __FILE__ ), array(), WPEP_VERSION, true );
}

add_action( 'admin_enqueue_scripts', 'wpep_enqueue_scripts' );

add_action( 'add_meta_boxes', 'wpep_register_meta_boxes' );

function wpep_register_meta_boxes() {
  add_meta_box( 'meta-box-id', __( 'A Metabox with a ProseMirror editor', 'textdomain' ), 'wpep_output_metabox_contents', 'post', 'normal' );
}

function wpep_output_metabox_contents( $post ) {
  wp_nonce_field( 'wpep_prosemirror_field', 'wpep_prosemirror_field_nonce' );
  echo '<div id="prosemirror-editor"></div>';
  echo '<input type="hidden" name="prosemirror-editor-content" value="' . esc_attr( get_post_meta( $post->ID, 'prosemirror-editor-content', true ) ) . '"></div>';
}

add_action( 'save_post', 'wpep_save_metabox' );

function wpep_save_metabox( $post_id ) {
  // Check if our nonce is set.
  if ( ! isset( $_POST['wpep_prosemirror_field_nonce'] ) ) {
    return $post_id;
  }

  // Verify that the nonce is valid.
  $nonce = $_POST['wpep_prosemirror_field_nonce'];
  if ( ! wp_verify_nonce( $nonce, 'wpep_prosemirror_field' ) ) {
    return $post_id;
  }

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return $post_id;
  }

  if ( ! current_user_can( 'edit_post', $post_id ) ) {
    return $post_id;
  }

  // var_dump( $_POST['prosemirror-editor-content'] );die;
  update_post_meta( $post_id, 'prosemirror-editor-content', $_POST['prosemirror-editor-content'] );
}