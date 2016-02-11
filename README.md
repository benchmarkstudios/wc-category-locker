# WooCommerce Category Locker
Adds ability to password lock each category.

### Info
* Current Stage of development - Testing, adding extra small features
* WooCommerce versions support - latest (older not tested yet)

### TODO (for 1.0 Release)
* ~~Better crypt-php exception handling - redirects / messages~~
* Test compatibility with twentyx themes
* Test compatibility with some of the free WooCommerce themes
* Add more hooks and filters
* Version testing for WooCommerce and check for version when enabling plugin
* Version testing for WordPress

## Hooks
List of available actions and filters:

### Actions
* **wcl_before_passform** - runs before Password Form
* **wcl_after_passform** - runs after Password Form

### Filters
* **wcl_passform_classes** - Password <form> classes
* **wcl_passform_submit_label** - Password form submit button label
* **wcl_passform_submit_classes** - Password form submit button classes
* **wcl_passform_input_classes** - Password form input classes
* **wcl_passform_input_placeholder** - Password form input placeholder
* **wcl_passform_description** - Password form description shown above input and submit button
* **wcl_password_form** - Entire form markup
* **wcl_password_expires** - Password expiry when entered (default 30min)
