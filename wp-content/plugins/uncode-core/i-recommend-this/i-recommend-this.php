<?php
/**
 * Custom implementation of "I Recommend This" by Harish Chouhan.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Uncode_DOT_IRecommendThis' ) ) {

	class Uncode_DOT_IRecommendThis {

		public $version = '3.0.0';
		public $db_version = '3.0.0';

		/*--------------------------------------------*
		 * Constructor
		 *--------------------------------------------*/

		function __construct( ) {
			// Run this on activation / deactivation
			register_activation_hook( UNCODE_CORE_FILE, array( $this, 'activate' ) );

			//update_option('test_recommend', 'test');

			add_action( 'wp_enqueue_scripts', array( &$this, 'dot_enqueue_scripts' ) );
			add_action( 'publish_post', array( &$this, 'dot_setup_recommends' ) );
			add_action( 'wp_ajax_uncode-irecommendthis', array( &$this, 'ajax_callback' ) );
			add_action( 'wp_ajax_nopriv_uncode-irecommendthis', array( &$this, 'ajax_callback' ) );
		} // end constructor

		/*--------------------------------------------*
		 * Activate
		 *--------------------------------------------*/

		public function activate( $network_wide ) {
			if (!isset($wpdb)) $wpdb = $GLOBALS['wpdb'];
			global $wpdb;

			$table_name = $wpdb->prefix . "irecommendthis_votes";
			if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {
				$sql = "CREATE TABLE " . $table_name . " (
					id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
					time TIMESTAMP NOT NULL,
					post_id BIGINT(20) NOT NULL,
					ip VARCHAR(45) NOT NULL,
					UNIQUE KEY id (id)
				);";

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

				dbDelta($sql);

				$this->register_plugin_version();

				if ( $this->db_version != '' ) {
					update_option( 'dot_irecommendthis_db_version', $this->db_version );
				}
			}

		} // end activate

		private function register_plugin_version () {
			if ( $this->version != '' ) {
				update_option( 'dot-irecommendthis' . '-version', $this->version );
			}
		} // End register_plugin_version()

		/*--------------------------------------------*
		 * Enqueue Scripts
		 *--------------------------------------------*/

		function dot_enqueue_scripts()
		{
			if ( apply_filters('uncode_dot_irecommendthis', false) ) {
				wp_register_script( 'uncode-irecommendthisd',  plugins_url( 'js/dot_irecommendthis.js', __FILE__ ), 'jquery', '3.0.0', 'in_footer' );

				wp_enqueue_script( 'uncode-irecommendthisd' );

				wp_localize_script( 'uncode-irecommendthisd', 'uncode_irecommendthis', array(
					'i18n'    => esc_html__( 'You already recommended this', 'uncode' ),
					'ajaxurl' => admin_url('admin-ajax.php')
				) );
			}

		}	//dot_enqueue_scripts

		/*--------------------------------------------*
		 * Setup recommends
		 *--------------------------------------------*/

		function dot_setup_recommends( $post_id )
		{
			if(!is_numeric($post_id)) return;

			add_post_meta($post_id, '_recommended', '0', true);

		}	//setup_recommends

		/*--------------------------------------------*
		 * AJAX Callback
		 *--------------------------------------------*/

		function ajax_callback($post_id)
		{
			if( isset($_POST['recommend_id']) ) {
				// Click event. Get and Update Count
				$post_id = str_replace('dot-irecommendthis-', '', $_POST['recommend_id']);
				echo $this->dot_recommend_this($post_id, 'update');
			} else {
				// AJAXing data in. Get Count
				$post_id = str_replace('dot-irecommendthis-', '', $_POST['post_id']);
				echo $this->dot_recommend_this($post_id, 'get');
			}

			exit;

		}	//ajax_callback

		/*--------------------------------------------*
		 * Main Process
		 *--------------------------------------------*/

		function dot_recommend_this($post_id, $action = 'get')
		{
			if (!is_numeric($post_id)) return;

			switch ($action)
			{
				case 'get':
					$recommended = get_post_meta($post_id, '_recommended', true);
					if (!$recommended)
					{
						$recommended = 0;
						add_post_meta($post_id, '_recommended', $recommended, true);
					}

					if ($recommended == 0)
					{
						$output = '<span class="extras-wrap"><i class="fa fa-heart3"></i><span><span class="likes-counter">0</span> ' . esc_html__('Like', 'uncode') . '</span></span>';
						return $output;
					}
					else
					{
						$output = '<span class="extras-wrap"><i class="fa fa-heart3"></i><span><span class="likes-counter">' . esc_html( $recommended ) . '</span> ' . esc_html__('Likes', 'uncode') . '</span></span>';
						return $output;
					}

					break;

				case 'update':

					$recommended = get_post_meta($post_id, '_recommended', true);

					global $wpdb;
					$ip = $_SERVER['REMOTE_ADDR'];
					$voteStatusByIp = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $wpdb->prefix . "irecommendthis_votes WHERE post_id = %d AND ip = %s", $post_id, $ip));

					if (isset($_COOKIE['dot_irecommendthis_' . $post_id]) || $voteStatusByIp != 0)
					{
						return $recommended;
					}

					$recommended++;
					update_post_meta($post_id, '_recommended', $recommended);
					setcookie('dot_irecommendthis_' . $post_id, time() , time() + 3600 * 24 * 365, '/');
					$wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "irecommendthis_votes VALUES ('', NOW(), %d, %s)", $post_id, $ip));


					$output = '<i class="fa fa-heart3"></i><span>' . esc_html( $recommended ) . '</span>';
					$dot_irt_html = apply_filters('dot_irt_before_count', $output);

					return $dot_irt_html;
					break;
			}
		}	//dot_recommend_this

		function dot_recommend($id = null, $wrap = true)
		{

			global $wpdb;
			$ip = $_SERVER['REMOTE_ADDR'];
			$post_ID = $id ? $id : get_the_ID();
			global $post;

			$voteStatusByIp = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $wpdb->prefix . "irecommendthis_votes WHERE post_id = %d AND ip = %s", $post_ID, $ip));

			$output = self::dot_recommend_this($post_ID);

			if (!isset($_COOKIE['dot_irecommendthis_' . $post_ID]) && $voteStatusByIp == 0) {

				$class = 'uncode-dot-irecommendthis';
				$title = esc_html__('Recommend this', 'uncode');

			} else {

				$class = 'uncode-dot-irecommendthis active';
				$title = esc_html__('You already recommended this', 'uncode');
			}

			if ($wrap) {
				$dot_irt_html = '<a href="#" class="' . esc_attr( $class ) . '" id="dot-irecommendthis-' . esc_attr( $post_ID ) . '" title="' . esc_attr( $title ) . '">';
				$dot_irt_html.= apply_filters('dot_irt_before_count', $output);
				$dot_irt_html.= '</a>';
			} else {
				$dot_irt_html = '<span class="' . esc_attr( $class ) . '">';
				$dot_irt_html.= apply_filters('dot_irt_before_count', $output);
				$dot_irt_html.= '</span>';
			}

			return $dot_irt_html;
		}

	} // End Class

	global $uncode_dot_irecommendthis;

	// Initiation call of plugin
	$uncode_dot_irecommendthis = new Uncode_DOT_IRecommendThis();
}

/*--------------------------------------------*
 * Template Tag
 *--------------------------------------------*/

if ( ! function_exists( 'uncode_dot_irecommendthis' ) ) {
	function uncode_dot_irecommendthis( $id = null ) {
		global $uncode_dot_irecommendthis;
		echo $uncode_dot_irecommendthis->dot_recommend( $id );
	}
}

/*--------------------------------------------*
 * Widget
 *--------------------------------------------*/

if ( ! function_exists( 'uncode_most_recommended_posts' ) ) {
	function uncode_most_recommended_posts($numberOf, $before, $after, $show_count, $post_type="post", $raw=false) {
		$args = array(
			'post_type'        => $post_type,
			'post_status'      => 'publish',
			'posts_per_page'   => $numberOf,
			'orderby'          => 'meta_value_num',
			'order'            => 'DESC',
			'meta_query'       => array(
				'relation' => 'OR',
				array(
					'key'     => '_recommended',
					'compare' => 'NOT EXISTS'
				),
				array(
					'key'     => '_recommended',
					'type'    => 'numeric',
					'compare' => 'EXISTS'
				)
			),
			'suppress_filters' => false,
		);

		$wp_query = new WP_Query( $args );
		$posts    = $wp_query->get_posts();

		if ($raw):
			return $posts;
		else:
			foreach ($posts as $item) {
				$post_title = stripslashes($item->post_title);
				$permalink = get_permalink($item->ID);
				$post_count = $item->meta_value;
				echo $before.'<a href="' . $permalink . '" title="' . $post_title.'" rel="nofollow">' . $post_title . '</a>';
				echo $show_count == '1' ? ' ('.$post_count.')' : '';
				echo $after;
			}
		endif;
	}
}

if ( ! class_exists( 'Uncode_Most_recommended_posts' ) ) {
	/**
	 * Adds Uncode_Most_recommended_posts widget.
	 */
	class Uncode_Most_recommended_posts extends WP_Widget
	{

		/**
		 * Register widget with WordPress.
		 */
		function __construct()
		{
			parent::__construct('most-recommended-posts', // Base ID
				esc_html__('Most recommended posts', 'uncode') , // Name
				array('description' => esc_html__('Your site’s most liked posts.', 'uncode') ,) // Args
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget($args, $instance)
		{

			$numberOf = $instance['number'];
			$show_count = $instance['show_count'];
			$title = $instance['title'];
			$before_widget = $args['before_widget'];
			$after_widget = $args['after_widget'];
			$before_title = $args['before_title'];
			$after_title = $args['after_title'];

			$widget_before = $before_widget;
			$widget_before .= $before_title . $title . $after_title;
			echo wp_kses_post($widget_before);
			echo '<ul class="mostrecommendedposts">';

			uncode_most_recommended_posts($numberOf, '<li>', '</li>', $show_count);

			echo '</ul>';
			echo wp_kses_post($after_widget);
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form($instance)
		{
			$title = !empty($instance['title']) ? $instance['title'] : esc_html__('Most recommended posts', 'uncode');
			$number = !empty($instance['number']) ? $instance['number'] : 5;
			$show_count = !empty($instance['show_count']) ? true : false;
			?>
	    <p>
	        <label for="<?php echo  esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'uncode'); ?></label>
	        <input class="widefat" id="<?php echo  esc_attr($this->get_field_id('title')); ?>" name="<?php echo  esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
	    </p>
	    <p>
	        <label for="<?php echo  esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of posts to show:', 'uncode'); ?></label><br />
	        <input id="<?php echo  esc_attr($this->get_field_id('number')); ?>" name="<?php echo  esc_attr($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr($number); ?>" style="width: 35px;"> <small>(max. 15)</small></label></p>
	    </p>
	    <p>
	        <label for="<?php echo  esc_attr($this->get_field_id('show_count')); ?>"><?php esc_html_e('Show post count', 'uncode'); ?></label>
	        <input class="checkbox" type="checkbox" <?php checked($show_count, '1'); ?> value="1" id="<?php echo  esc_attr($this->get_field_id('show_count')); ?>" name="<?php echo  esc_attr($this->get_field_name('show_count')); ?>" />
	    </p>
	  	<?php
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update($new_instance, $old_instance)
		{
			$instance = array();
			$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
			$instance['number'] = (!empty($new_instance['number'])) ? strip_tags($new_instance['number']) : '';
			$instance['show_count'] = (!empty($new_instance['show_count'])) ? $new_instance['show_count'] : false;

			return $instance;
		}
	}

	function uncode_register_most_recommended_posts() {
		register_widget( 'Uncode_Most_recommended_posts' );
	}
	add_action( 'widgets_init', 'uncode_register_most_recommended_posts' );
}

/*--------------------------------------------*
* Add Likes Column In Post Manage Page
*--------------------------------------------*/

if ( ! function_exists( 'uncode_dot_columns_head' ) ) {
	function uncode_dot_columns_head($defaults) {
		// Check if the original I Recommend This plug is active
		if ( class_exists( 'DOT_IRecommendThis' ) ) {
			return $defaults;
		}

		// Don't add the column if the filter is not active
		if ( ! apply_filters( 'uncode_dot_irecommendthis', false ) ) {
			return $defaults;
		}

		$defaults['likes'] = esc_html__('Likes', 'uncode');
		return $defaults;
	}
}

if ( ! function_exists( 'uncode_dot_column_content' ) ) {
	function uncode_dot_column_content($column_name, $post_ID) {
		// Check if the original I Recommend This plug is active
		if ( class_exists( 'DOT_IRecommendThis' ) ) {
			return;
		}

		// Don't add the column if the filter is not active
		if ( ! apply_filters( 'uncode_dot_irecommendthis', false ) ) {
			return;
		}

		if ($column_name == 'likes') {
			echo esc_html( get_post_meta($post_ID, '_recommended', true) . ' ' . __('like', 'uncode') );
		}
	}
}

if ( ! function_exists( 'uncode_dot_column_register_sortable' ) ) {
	function uncode_dot_column_register_sortable( $columns ) {
		// Check if the original I Recommend This plug is active
		if ( class_exists( 'DOT_IRecommendThis' ) ) {
			return $columns;
		}

		// Don't add the column if the filter is not active
		if ( ! apply_filters( 'uncode_dot_irecommendthis', false ) ) {
			return $columns;
		}

		$columns['likes'] = 'likes';
		return $columns;
	}
}

if ( ! function_exists( 'uncode_dot_column_orderby' ) ) {
	function uncode_dot_column_orderby( $vars ) {
		// Check if the original I Recommend This plug is active
		if ( class_exists( 'DOT_IRecommendThis' ) ) {
			return $vars;
		}

		// Don't add the column if the filter is not active
		if ( ! apply_filters( 'uncode_dot_irecommendthis', false ) ) {
			return $vars;
		}

		if ( isset( $vars['orderby'] ) && 'likes' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => '_recommended',
				'orderby' => 'meta_value'
			) );
		}

		return $vars;
	}
}

add_filter('request', 'uncode_dot_column_orderby');
add_filter('manage_edit-post_sortable_columns', 'uncode_dot_column_register_sortable');
add_filter('manage_posts_columns', 'uncode_dot_columns_head');
add_action('manage_posts_custom_column', 'uncode_dot_column_content', 10, 2);

/**
 * Remove hooks from DOT_IRecommendThis
 */
if ( class_exists( 'DOT_IRecommendThis' ) ) {
	function uncode_remove_irecommendthis_scripts() {
	    wp_deregister_script( 'dot-irecommendthis' );
	}
	add_action( 'wp_footer', 'uncode_remove_irecommendthis_scripts', 11 );


	function uncode_remove_irecommendthis_menu() {
		remove_submenu_page('options-general.php', 'dot-irecommendthis');
	}
	add_action('admin_menu', 'uncode_remove_irecommendthis_menu', 9999);

	function uncode_remove_irecommendthis_styles() {
        wp_dequeue_style( 'dot-irecommendthis' );
    }
    add_action('wp_enqueue_scripts', 'uncode_remove_irecommendthis_styles', 20);
}
