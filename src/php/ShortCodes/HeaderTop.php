<?php
/**
 * Header top class.
 *
 * @package iwpdev/flatsome-child
 */

namespace Iwpdev\FlatsomeChild\ShortCodes;

/**
 * HeaderTop class file.
 */
class HeaderTop {
	/**
	 * HeaderTop construct.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Init actions and filters.
	 *
	 * @return void
	 */
	private function init(): void {
		add_shortcode( 'fl_header_top', [ $this, 'show_header_top' ] );
	}

	public function show_header_top( array $atts ) {
		ob_start();
		?>
		<div class="top-bar-content">
			<strong class="uppercase" style="display: block; text-align: center;">
				<?php echo wp_kses_post( $atts['text'] ); ?>
			</strong>
		</div>
		<?php
		return ob_get_clean();
	}
}

