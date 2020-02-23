<?php
/**
 * System Status functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! is_admin() ) {
	return;
}

function uncode_core_system_status_scripts() {
	$screen = get_current_screen();

	if ( isset( $screen->id ) && $screen->id == 'toplevel_page_uncode-system-status' ) {
		wp_enqueue_script( 'uncode_system_status_js', plugins_url( 'assets/js/uncode_system_status.js', __FILE__ ), array( 'jquery' ), UncodeCore_Plugin::VERSION , true );

		$system_status_parameters = array(
			'nonce' => wp_create_nonce( 'uncode-php-test-memory-nonce' )
		);

		wp_localize_script( 'uncode_system_status_js', 'SystemStatusParameters', $system_status_parameters );
	}
}
add_action( 'admin_enqueue_scripts', 'uncode_core_system_status_scripts' );

function uncode_core_system_status_print_memory() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>

	<tr>
		<td data-export-label="Server Memory Limit"><?php esc_html_e( 'Server Memory Limit', 'uncode' ); ?>
		<?php echo '<span class="toggle-description"></span><small class="description">' . esc_attr__( 'This is actually the real memory available for your installation despite the WP memory limit.', 'uncode' ) . '</small>'; ?></td>
		<td class="real-memory">
			<?php
			$php_memory            = uncode_let_to_num( WP_MEMORY_LIMIT );
			$real_php_memory       = get_option( 'uncode_real_php_memory' );
			$using_real_php_memory = false;

			if ( $real_php_memory ) {
				$php_memory            = $real_php_memory;
				$using_real_php_memory = true;
			}

			$memory_is_ok          = $using_real_php_memory && $php_memory < 96 || ! $using_real_php_memory && $php_memory < 100663296 ? false : true;
			$memory_formatted_text = $using_real_php_memory ? esc_html( $php_memory ) . ' MB' : size_format( $php_memory );
			?>

			<mark class="saved-memory <?php echo $memory_is_ok ? 'yes' : 'error'; ?>">
				<?php if ( $memory_is_ok ) : ?>
					<?php echo $memory_formatted_text; ?>
				<?php else : ?>
					<?php echo sprintf( esc_html__( 'You only have %s available and it\'s not enough to run the system. If you have already increased the memory limit please check with your hosting provider for increase it (at least 96MB is required)','uncode' ), $memory_formatted_text ); ?>
				<?php endif; ?>
			</mark>
			<span class="calculating" style="display: none;"><?php esc_html_e( 'Calculating…', 'uncode' ); ?></span>
			<mark class="yes" style="display: none;">%d% MB</mark>
			<mark class="error" style="display: none;"><?php esc_html_e( 'You only have %d% MB available and it\'s not enough to run the system. If you have already increased the memory limit please check with your hosting provider for increase it (at least 96MB is required)','uncode' ); ?></mark>
			&nbsp;<a href="#" id="php-memory-check"><i class="fa fa-refresh"></i></a>
		</td>
	</tr>

	<?php
}
//add_action( 'uncode_server_memory_limit', 'uncode_core_system_status_print_memory' );

/**
 * Memory limit test function.
 */
function uncode_core_php_test_memory() {
	if ( current_user_can( 'manage_options' ) && isset( $_POST[ 'php_test_memory_nonce' ] ) && wp_verify_nonce( $_POST[ 'php_test_memory_nonce' ], 'uncode-php-test-memory-nonce' ) ) {
		$text = '';

		for ( $i = 16; $i < 1000; $i += 4 ) {
			$a = @uncode_core_loadmem( $i-4 );
			update_option( 'uncode_real_php_memory', $i );
			echo '%' . $i . "\n";

			if ($a > 112) {
				unset( $a );
				break;
			}

			unset( $a );
		}
	}

	die();
}

/* AJAX call to test php memory */
add_action( 'wp_ajax_uncode_php_test_memory', 'uncode_core_php_test_memory' );

function uncode_core_loadmem( $howmuchmeg ) {
	$dummy = str_repeat( "-", 1048576 * $howmuchmeg );
	$a     = memory_get_peak_usage( true ) / 1048576 ;

	return $a;
}
