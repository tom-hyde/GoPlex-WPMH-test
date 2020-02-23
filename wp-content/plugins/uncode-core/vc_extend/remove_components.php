<?php
	/**
	 *	Disable frontpage editor
	 */
	vc_disable_frontend();
	vc_remove_element( "vc_tta_accordion" );
	vc_remove_element( "vc_tta_tour" );
	vc_remove_element( "vc_tta_tabs" );
	vc_remove_element( "vc_tta_pageable" );
	vc_remove_element( "vc_round_chart" );
	vc_remove_element( "vc_line_chart" );
	vc_remove_element( "vc_text_separator" );
	vc_remove_element( "vc_facebook" );
	vc_remove_element( "vc_tweetmeme" );
	vc_remove_element( "vc_googleplus" );
	vc_remove_element( "vc_pinterest" );
	vc_remove_element( "vc_toggle" );
	vc_remove_element( "vc_images_carousel" );
	vc_remove_element( "vc_tour" );
	vc_remove_element( "vc_teaser_grid" );
	vc_remove_element( "vc_posts_grid" );
	vc_remove_element( "vc_carousel" );
	vc_remove_element( "vc_posts_slider" );
	vc_remove_element( "vc_button2" );
	vc_remove_element( "vc_cta" );
	vc_remove_element( "vc_btn" );
	vc_remove_element( "vc_cta_button" );
	vc_remove_element( "vc_cta_button2" );
	vc_remove_element( "vc_video" );
	vc_remove_element( "vc_basic_grid" );
	vc_remove_element( "vc_media_grid" );
	vc_remove_element( "vc_masonry_grid" );
	vc_remove_element( "vc_masonry_media_grid" );
	vc_remove_element( "vc_zigzag" );
	vc_remove_element( "vc_hoverbox" );


	function uncode_remove_default_templates() {
		return array();
	}

	add_action( 'vc_load_default_templates', 'uncode_remove_default_templates' );

	add_action( 'admin_head', 'uncode_remove_metabox'  );
	function uncode_remove_metabox() {
		global $post_type,$wp_post_types;
		remove_meta_box('vc_teaser', $post_type, 'side');
	}

	function uncode_custom_vc_template( $data ) {
		foreach( $data as $key => $val ) {
			if ( isset( $val['category'] ) && 'shared_templates' == $val['category'] ) {
				unset( $data[$key] );
			}
		}
		return $data;
	}
	add_filter( 'vc_get_all_templates', 'uncode_custom_vc_template', 99 );