<?php
/**
 * Theme functions file.
 *
 * @package iwpdev/flatsome-child
 */

use Iwpdev\FlatsomeChild\Main;


/**
 * Load parent styles.
 *
 * @return void
 */
function parent_styles(): void {
	wp_enqueue_style(
		'fl-parent-style',
		get_stylesheet_uri()
	);
}

add_action( 'wp_enqueue_scripts', 'parent_styles' );

/**
 * Auto load classes.
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load main class.
 */
new Main();

