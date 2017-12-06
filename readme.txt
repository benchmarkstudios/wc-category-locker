=== WooCommerce Category Locker ===
Contributors: benchmarkstudios, LukasNeptun
Donate Link: http://benchmark.co.uk/contact-us/
Tags: category lock, taxonomy lock, lock, locker, woocommerce category, woocommerce,
Requires at least: 3.5
Tested up to: 4.9.1
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds ability to password lock each category the same way as Wordpress gives you ability to lock each post.

== Description ==

**General Info & Features:**

* WooCommerce versions support - 2.2.0 and later
* Wordpress version support - 3.5 and later
* Products under locked category are excluded from the main shop loop
* Passwords are fully encrypted and saved as a cookie
* Password cookie lasts for 30 minutes by default
* Ability to customise password template by copying it in to woocommerce theme folder
* No Ads
* No Paid Upgrades
* Regular updates and maintenance

Having trouble? Please read FAQ first, if you need any assistance, contact us.

> <strong>Development on GitHub</strong><br>
> The development of WooCommerce Category Locker [takes place on GitHub](https://github.com/benchmarkstudios/wc-category-locker). Bugs and pull requests are welcomed there.

= Hooks =
List of available actions and filters:

= Actions =
* <strong>wcl_before_passform</strong> - runs before Password Form
* <strong>wcl_after_passform</strong> - runs after Password Form

= Filters =
* <strong>wcl_passform_classes</strong> - Password `<form>` classes
* <strong>wcl_passform_submit_label</strong> - Password form submit button label
* <strong>wcl_passform_submit_classes</strong> - Password form submit button classes
* <strong>wcl_passform_input_classes</strong> - Password form input classes
* <strong>wcl_passform_input_placeholder</strong> - Password form input placeholder
* <strong>wcl_passform_description</strong> - Password form description shown above input and submit button
* <strong>wcl_password_form</strong> - Entire form markup
* <strong>wcl_password_expires</strong> - Password expiry when entered (default 30min)

== Installation ==

1. Upload `wc-category-locker` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. To activate password for category, navigate to Products -> Categories then either create or edit existing category, tick "Password Protected" -> Enter password you wish to use and hit "Update"
1. To disable password, simply untick "Password Protected"

== Frequently Asked Questions ==

None yet

== Screenshots ==

1. Category page using TwentySixteen theme.
2. Category page using Storefront theme by WooCommerce.
3. Edit product category page in the admin area.
4. Add product category page in the admin area.

== Changelog ==
= 1.0.3 =
* change `pre_get_posts` action prioriry to default (10) for better theme support. [Thanks @marcs84](https://github.com/marcs84)

= 1.0.2 =
* Tidy up, minor updates and corrections in code

= 1.0.1 =
* Fixed issue where users were not able to remove plugin if they didn't have WooCommerce plugin installed.

= 1.0 =
* First release.

== Upgrade Notice ==
= 1.0.1 =
* Fixed issue where users were not able to remove plugin if they didn't have WooCommerce plugin installed.

= 1.0 =
* First release.
