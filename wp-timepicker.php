<?php
/*
Plugin Name: WP-Timepicker
Version: 0.9-beta
Description: An easier way to modify post dates
Author: scribu
Author URI: http://scribu.net/
Plugin URI: http://scribu.net/wordpress/wp-timepicker
Text Domain: wp-timepicker
Domain Path: /lang

Copyright (C) 2011 scribu.net (scribu@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

class WP_Timepicker {

	function init() {
		self::register();
		add_action('admin_print_styles', array(__CLASS__, '_init'));
	}

	function _init() {
		global $pagenow;

		if ( ! in_array($pagenow, array('post.php', 'post-new.php', 'page.php', 'page-new.php')) )
			return;

		self::enqueue();

//		self::add_selector('#datetime');

		add_action('admin_print_footer_scripts', array(__CLASS__, 'start'), 100);
	}

	private static $selectors = array();

	function remove_selector($selector) {
		unset(self::$selectors[$selector]);
	}

	function add_selector($selector) {
		self::$selectors[$selector] = true;
	}

	function register() {
		$url = plugins_url('', __FILE__);

		$js_dev = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.dev' : '';

		wp_register_style('jquery-ui-smoothness', "$url/css/smoothness/smoothness.css", array(), '1.8.6');

		wp_register_script('jquery-ui-datepicker', "$url/js/ui.datepicker.js", array('jquery-ui-core'), '1.8.6', true);

		wp_register_script('jquery-ui-widget', "$url/js/ui.datepicker.js", array('jquery-ui-core'), '1.8.6', true);
		wp_register_script('jquery-ui-mouse', "$url/js/ui.datepicker.js", array('jquery-ui-widget'), '1.8.6', true);
		wp_register_script('jquery-ui-slider', "$url/js/ui.slider.js", array('jquery-ui-mouse'), '1.8.6', true);

		wp_register_script('timepicker', "$url/js/timepicker$js_dev.js", array('jquery-ui-datepicker', 'jquery-ui-slider'), '0.9', true);
	}

	function enqueue() {
		wp_enqueue_style('jquery-ui-smoothness');
		wp_enqueue_script('timepicker');
?>
<style type="text/css">
.ui-timepicker-div .ui-widget-header{ margin-bottom: 8px; }
.ui-timepicker-div dl{ text-align: left; }
.ui-timepicker-div dl dt{ height: 25px; }
.ui-timepicker-div dl dd{ margin: -25px 0 10px 65px; }
.ui-timepicker-div td { font-size: 90%; }
</style>
<?php
	}

	function start() {
		if ( empty(self::$selectors) )
			return;

		$selectors = implode(', ', array_keys(self::$selectors));

?>
<script type="text/javascript">  
jQuery(document).ready(function($) {
	$('<?php echo $selectors; ?>').datepicker({
		constrainInput: true,
		duration: '',
		showTime: true,
	});
});
</script>  
<?php
	}
}

WP_Timepicker::init();

