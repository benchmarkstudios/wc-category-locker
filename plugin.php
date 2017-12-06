<?php
/**
 * Plugin Name: WooCommerce Category Locker
 * Plugin URI: https://github.com/lukasjuhas/wc-category-locker
 * Description: Adds ability to password lock each category.
 * Version: 1.0.3
 * Author: Benchmark
 * Author URI: http://benchmark.co.uk/
 * Text Domain: wc-category-locker
 * License: GPL2
 */

/*  Copyright 2016 Benchmark Studios  (email : hello@benchmark.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

define('WCL_VERSION', '1.0.3');
define('WCL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WCL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WCL_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('WCL_PLUGIN_DOMAIN', 'wc-category-locker');
define('WCL_MIN_WC_REQ', 2.2);

/**
 * Dependency check
 */
if (!class_exists('WCL_Dependencies')) {
    require_once WCL_PLUGIN_DIR . 'classes/class-wc-dependencies.php';
}

if (!function_exists('wcl_is_wc_active')) {
    /**
     * WC detection
     *
     * @return void
     */
    function wcl_is_wc_active()
    {
        return WCL_Dependencies::woocommerce_active_check();
    }
}

/**
 * If WooCommerce is active, let the magic happen.
 */
if (wcl_is_wc_active()) {
    include WCL_PLUGIN_DIR . 'includes/functions.php';
    include WCL_PLUGIN_DIR . 'admin.php';
    include WCL_PLUGIN_DIR . 'frontend.php';
    include WCL_PLUGIN_DIR . 'wc-category-locker.php';
}
