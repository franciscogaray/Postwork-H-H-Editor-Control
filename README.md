# H&H Editor Control

A lightweight, standalone WordPress plugin developed for **Heirloom & Hops** to manage editor stability. This utility allows administrators to toggle between the Gutenberg Block Editor and the Classic Editor globally or on a per-post-type basis, ensuring that legacy layouts and custom meta boxes do not break.

## Installation

1. **Download/Clone**: Download the plugin ZIP file or clone the repository into your local machine.
2. **Upload**: Upload the `hh-editor-control` folder to the `/wp-content/plugins/` directory of your WordPress installation.
3. **Activate**: Log in to the WordPress Admin dashboard, navigate to **Plugins**, and click **Activate** under "H&H Editor Control".
4. **Configure**: Go to **Settings > Editor Control** to set your preferences.

## Features

- **Global Toggle**: Instantly force the Classic Editor across all post types.
- **Granular Control**: Enable or disable the Block Editor for specific post types (e.g., enable for "Posts" but disable for "Products").
- **UI Cleanup**: Automatically hides "Try Gutenberg" prompts and sidebar switch links to prevent accidental clicks by the content team.
- **Developer Friendly**: Built using standard WordPress hooks with no third-party dependencies.

## Technical Logic & Hooks

The plugin utilizes the following key WordPress hooks to manage the editor state:

```php
/**
 * The 'use_block_editor_for_post' filter is the primary hook used to intercept 
 * the loading of the Gutenberg editor. 
 * * Logic:
 * 1. It checks the 'hh_disable_gutenberg_global' option. If true, it returns false
 * to immediately bail out of the Block Editor for every post.
 * 2. If global is false, it checks the 'hh_classic_post_types' array. If the current
 * $post->post_type exists in that array, it returns false to force the Classic Editor.
 */
add_filter('use_block_editor_for_post', [$this, 'handle_editor_switching'], 10, 2);

/**
 * The 'admin_menu' hook is used to register the custom settings dashboard.
 * * Logic:
 * Uses add_options_page to create a dedicated UI under Settings > Editor Control,
 * ensuring the developer can delegate control to the founder without them touching code.
 */
add_action('admin_menu', [$this, 'add_settings_page']);

/**
 * The 'admin_print_styles' hook is used for interface cleanup.
 * * Logic:
 * Injects CSS into the admin head to hide elements with classes like 
 * '.editor-post-switch-to-gutenberg'. This prevents the content team from 
 * manually switching back to Gutenberg and breaking legacy shortcode layouts.
 */
add_action('admin_print_styles', [$this, 'hide_gutenberg_ui']);
```

## Requirements
- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **WP_DEBUG**: Compatible (No notices or warnings generated)

## License
Custom for Heirloom & Hops.
