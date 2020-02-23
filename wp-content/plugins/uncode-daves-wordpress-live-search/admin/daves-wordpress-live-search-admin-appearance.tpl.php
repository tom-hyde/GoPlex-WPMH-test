<?php if ( !defined( 'ABSPATH' ) ) die( "Cannot access files directly." ); ?>

<?php include dirname( __FILE__ ) . "/admin_header.tpl.php";

?>
<tr valign="top">
<th scope="row"><?php _e( "Display Category", 'daves-wordpress-live-search' ); ?></th>

<td>
    <input type="hidden" name="daves-wordpress-live-search_display_post_category" value="" />
    <input type="checkbox" name="daves-wordpress-live-search_display_post_category" id="daves-wordpress-live-search_display_post_category" value="true" <?php checked( $displayPostCategory ); ?> /> <label for="daves-wordpress-live-search_display_post_category"><?php _e( "Display post category for every search result", 'daves-wordpress-live-search' ); ?></label>
</td>
</tr>

<tr valign="top">
<th scope="row"><?php _e( "Display Metadata", 'daves-wordpress-live-search' ); ?></th>

<td>
    <input type="hidden" name="daves-wordpress-live-search_display_post_meta" value="" />
    <input type="checkbox" name="daves-wordpress-live-search_display_post_meta" id="daves-wordpress-live-search_display_post_meta" value="true" <?php checked( $displayPostMeta ); ?> /> <label for="daves-wordpress-live-search_display_post_meta"><?php _e( "Display author & date for every search result", 'daves-wordpress-live-search' ); ?></label>
</td>
</tr>

<!-- Display post excerpt -->
<tr valign="top">
<th scope="row"><?php _e( "Display Post Excerpt", 'daves-wordpress-live-search' ); ?></th>

<td>
    <input type="hidden" name="daves-wordpress-live-search_excerpt" value="" />
    <input type="checkbox" name="daves-wordpress-live-search_excerpt" id="daves-wordpress-live-search_excerpt" value="true" <?php checked( $showExcerpt ); ?> /> <label for="daves-wordpress-live-search_excerpt"><?php printf( __( "Display an excerpt for every search result. If the post doesn't have one, use the first %s characters.", 'daves-wordpress-live-search' ), "<input type=\"text\" name=\"daves-wordpress-live-search_excerpt_length\" id=\"daves-wordpress-live-search_excerpt_length\" value=\"$excerptLength\" size=\"3\" />"); ?></label>
</td>
</tr>

<!-- Display 'more results' -->
<tr valign="top">
<th scope="row"><?php _e( "Display &quot;View more results&quot; link", 'daves-wordpress-live-search' ); ?></th>

<td>
    <input type="hidden" name="daves-wordpress-live-search_more_results" value="" />
    <input type="checkbox" name="daves-wordpress-live-search_more_results" id="daves-wordpress-live-search_more_results" value="true" <?php checked( $showMoreResultsLink ); ?> /> <label for="daves-wordpress-live-search_more_results"><?php _e( "Display the &quot;View more results&quot; link after the search results.", 'daves-wordpress-live-search' ); ?></label>
</td>
</tr>

<!-- Uncode row to activate/deactivate on widgets -->
<tr valign="top">
<th scope="row"><?php _e( "Activate on search widgets", 'daves-wordpress-live-search' ); ?></th>

<td>
    <input type="hidden" name="daves-wordpress-live-search_uncode_activate_widget" value="" />
    <input type="checkbox" name="daves-wordpress-live-search_uncode_activate_widget" id="daves-wordpress-live-search_uncode_activate_widget" value="true" <?php checked( $activateWidget ); ?> /> <label for="daves-wordpress-live-search_uncode_activate_widget"><?php _e( "Activate Live Search on Wordpress default and WooCommerce search widgets", 'daves-wordpress-live-search' ); ?></label>
</td>
</tr>

<!-- Submit buttons -->
<tr valign="top">
<td colspan="2">
	<?php submit_button( NULL, 'option-tree-ui-button button button-secondary left', 'daves-wordpress-live-search_submit', false, array('id' => 'daves-wordpress-live-search_submit') ); ?>
</td>
</tr>

</tbody></table>

</form>

</div>
