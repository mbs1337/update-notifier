# Update Notifier for WordPress

[![Built By](https://img.shields.io/badge/BUILT%20BY-e--studio.dk-555555?style=flat-square)](https://e-studio.dk)
[![Website](https://img.shields.io/badge/e--studio.dk-2e981b?style=flat-square&logo=wordpress&logoColor=white)](https://e-studio.dk)
[![WordPress](https://img.shields.io/badge/WordPress-PHP-blue?style=flat-square&logo=wordpress&logoColor=white)](https://wordpress.org/)
[![Built In PHP](https://img.shields.io/badge/BUILT%20IN-PHP-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v3-blue?style=flat-square)](http://www.gnu.org/licenses/gpl-3.0.html)

A simple WordPress plugin that sends email notifications to the site administrator and a predefined support address whenever WordPress core, plugins, themes, or translations are updated.

## Description

Update Notifier automatically monitors your WordPress site for updates and sends email notifications when:
- WordPress core is updated
- Plugins are updated
- Themes are updated
- Translations are updated

The notification email includes details about what was updated and is sent to both the site administrator (configured in WordPress settings) and a predefined support email address.

## Installation

1. Download or clone this repository
2. Upload the `up2date-notifier` folder to `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

## Configuration

The plugin sends emails to:
- The WordPress administrator email (set in Settings → General)
- A predefined support email address (currently hardcoded in the plugin)

To change the support email address, edit `up2date-notifier.php` and modify line 123:

```php
$support_email = 'your-email@example.com';
```

## Requirements

- WordPress 6.5 or higher
- PHP 8.0 or higher

## License

GPL-3.0 [http://www.gnu.org/licenses/gpl-3.0.html](http://www.gnu.org/licenses/gpl-3.0.html)

## Author

e-studio.dk | Michael Bay Sørensen

