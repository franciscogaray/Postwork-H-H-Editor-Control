# H&H Editor Control

A custom, lightweight WordPress utility for **Heirloom & Hops** to manage the transition between the legacy Classic Editor and the Gutenberg Block Editor.

## Features
- **Global Toggle:** Disable Gutenberg across the entire site with one click.
- **Granular Control:** Choose specific post types (Posts, Pages, Products) to remain on the Classic Editor.
- **UI Cleanup:** Removes "Try Gutenberg" nags to prevent accidental layout breaks.

## Installation
1. Download the `hh-editor-control` folder.
2. Upload it to your `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Navigate to **Settings > Editor Control** to configure.

## Technical Details
- Uses the `use_block_editor_for_post` filter for reliable interception.
- No third-party dependencies.
- Built to handle custom meta boxes and legacy shortcodes.