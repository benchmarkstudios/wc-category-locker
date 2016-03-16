# WooCommerce Category Locker
Adds ability to password lock each category the same way as Wordpress gives you ability to lock each post.

### General Info & Features
* WooCommerce versions support - 2.2.0 and later
* Wordpress version support - 3.5 and later
* Products under locked category are excluded from the main shop loop
* Passwords are fully encrypted and saved as a cookie
* Password cookie lasts for 30 minutes by default
* Ability to customise password template by copying it in to woocommerce theme folder

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

### Suggestions
Suggestions and requests are very welcome.
