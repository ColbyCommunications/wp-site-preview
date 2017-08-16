<?php
/**
 * Plugin Name: Colby Site Preview
 * Description: A site preview component and WordPress shortcode
 * Author: John Watkins, Colby Communications
 */

add_action( 'wp_enqueue_scripts', function() {
	global $post;

	if ( has_shortcode( $post->post_content, 'site-preview' ) ) {
		$min = PROD === true ? '.min' : '';
		$dist = plugin_dir_url( __FILE__ ) . 'dist';

		$package_json = json_decode( file_get_contents( __DIR__ . '/package.json' ) )
		?: (object) [ 'version' => '1.0.1' ];

		wp_enqueue_script(
			'site-preview', "$dist/colby-wp-react-site-preview$min.js",
			['react', 'react-dom', 'prop-types', 'date-fns'],
			$package_json->version,
			true
		);

		wp_enqueue_style(
			'site-preview',
			"$dist/colby-wp-react-site-preview$min.css",
			['colby-bootstrap'],
			$package_json->version
		);
	}
 }, 10, 1 );



function render_site_preview_shortcode( $atts ) {
	if ( ! $atts['site-id'] || ! $atts['updates-endpoint'] || ! $atts['updates-more-link'] ) {
		return '';
	}

	return "
<div
  data-site-preview
  data-site-id=\"{$atts['site-id']}\"
  data-updates-endpoint=\"{$atts['updates-endpoint']}\"
  data-updates-more-link=\"{$atts['updates-more-link']}\">
</div>
";
}

function add_site_preview_shortcode() {
	add_shortcode( 'site-preview', 'render_site_preview_shortcode' );
}
add_action( 'init', 'add_site_preview_shortcode' );
