<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $woocommerce;

?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

	<div id="comment-<?php comment_ID(); ?>" class="comment_container comment-content post-content">

		<?php
		/**
		 * The woocommerce_review_before hook
		 *
		 * @hooked woocommerce_review_display_gravatar - 10
		 */
		do_action( 'woocommerce_review_before', $comment );
		?>

		<figure class="gravatar"><?php echo get_avatar( $comment, apply_filters( 'woocommerce_review_gravatar_size', '256' ), '', get_comment_author_email( $comment->comment_ID ) ); ?></figure>

		<div class="comment-text comment-meta post-meta">

			<?php do_action( 'woocommerce_review_before_comment_meta', $comment ); ?>

			<?php
			/**
			 * The woocommerce_review_meta hook.
			 *
			 * @hooked woocommerce_review_display_meta - 10
			 * @hooked WC_Structured_Data::generate_review_data() - 20
			 */
			do_action( 'woocommerce_review_meta', $comment );
			?>

			<?php if ( $comment->comment_approved == '0' ) : ?>

				<p class="meta"><em><?php esc_html_e( 'Your comment is awaiting approval', 'woocommerce' ); ?></em></p>

			<?php else : ?>

				<div class="comment-author h5 text-bold">
					<span class="comment-author-link"><?php comment_author(); ?></span>
				</div>
				<?php

						if ( get_option( 'woocommerce_review_rating_verification_label' ) === 'yes' ) {
							if ( wc_review_is_from_verified_owner( $comment->comment_ID ) ) {
								echo '<em class="verified">(' . esc_html__( 'verified owner', 'woocommerce' ) . ')</em> ';
							}
						}

					?>
				<time class="comment-meta-item" datetime="<?php comment_date('Y-m-d') ?>T<?php comment_time('H:iP') ?>"><span><?php comment_date() ?></span>, <a href="#comment-<?php comment_ID() ?>"><span><?php comment_time() ?></span></a></time>

			<?php endif; ?>

			<?php do_action( 'woocommerce_review_before_comment_text', $comment ); ?>

			<?php
			/**
			 * The woocommerce_review_comment_text hook
			 *
			 * @hooked woocommerce_review_display_comment_text - 10
			 */
			do_action( 'woocommerce_review_comment_text', $comment );
			?>

			<div class="description"><?php comment_text(); ?></div>

			<?php do_action( 'woocommerce_review_after_comment_text', $comment ); ?>

		</div>
	</div>
