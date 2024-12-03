<?php
/**
 * BD ACF Shortcodes
 *
 * @package     BD_ACF_Shortcodes
 * @author      Brianna Deleasa
 * @copyright   2024 Brianna Deleasa
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: BD ACF Shortcodes
 * Plugin URI:  https://github.com/bdeleasa/bd-acf-shortcodes
 * Description: A small plugin for WordPress to enable the use of ACF [acf field=""] shortcodes in query loops.
 * Version:     2.0.0
 * Author:      Brianna Deleasa
 * Author URI:  https://briannadeleasa.com
 * Text Domain: bd-acf-shortcodes
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */


namespace BD_ACF_Shortcodes;


add_filter( 'render_block_core/shortcode', __NAMESPACE__ . '\shortcode_render', 10, 3);
/**
 * Run our callback function on the shortcode content, which will
 * inject the post ID into the ACF shortcode.
 *
 * Without this, the ID doesn't get inserted properly into the shortcode
 * in query blocks in the block editor. The shortcode values don't return
 * the proper values because they aren't using the proper ID.
 *
 * Hopefully this gets patched and we can remove this in the future.
 * Until then, this is the fix to get [acf] shortcodes
 * working in query blocks.
 *
 * @since 1.0.0
 *
 * @uses shortcode_render()
 * @uses inject_query_loop_post_ID()
 *
 * @param string $content
 * @param array $parsed_block
 * @param \WP_Block $block
 * @return string
 */
function shortcode_render(string $content, array $parsed_block, \WP_Block $block ): string
{
    return preg_replace_callback( '/\[acf\s.*?\]/', __NAMESPACE__ .'\inject_query_loop_post_ID', $content );
}


/**
 * Inject the post ID into the ACF shortcode.
 *
 * @since 1.0.0
 *
 * @param $match
 * @return string
 */
function inject_query_loop_post_ID( $match ): string
{
    return str_replace( '[acf', '[acf post_id="' . get_the_ID() . '"', $match[0] );
}