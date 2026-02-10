<?php
/**
 * Plugin Name: H&H Editor Control
 * Description: Lightweight editor control for Heirloom & Hops to toggle between Gutenberg and Classic Editor.
 * Version: 1.0.0
 * Author: Francisco Garay
 * Author URI: https://franciscogaray.me
 * Text Domain: hh-editor-control
 */

if (!defined('ABSPATH')) exit;

class HH_Editor_Control {

    public function __construct() {
        // Add settings page to the dashboard
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);

        // Logic to intercept and disable Gutenberg
        add_filter('use_block_editor_for_post', [$this, 'handle_editor_switching'], 10, 2);

        // UI Cleanup: Hide Gutenberg nags and switch links
        add_action('admin_print_styles', [$this, 'hide_gutenberg_ui']);
    }

    /**
     * Step 1: Create the Settings Menu
     */
    public function add_settings_page() {
        add_options_page(
            'H&H Editor Control',
            'Editor Control',
            'manage_options',
            'hh-editor-control',
            [$this, 'render_settings_page']
        );
    }

    /**
     * Step 2: Register Settings with Sanitization
     */
    public function register_settings() {
        register_setting('hh_editor_settings_group', 'hh_disable_gutenberg_global');
        register_setting('hh_editor_settings_group', 'hh_classic_post_types', [
            'type' => 'array',
            'sanitize_callback' => [$this, 'sanitize_array']
        ]);
    }

    public function sanitize_array($input) {
        return is_array($input) ? array_map('sanitize_text_field', $input) : [];
    }

    /**
     * Step 3: Core Logic - Disable Gutenberg based on settings
     */
    public function handle_editor_switching($use_block_editor, $post) {
        $global_disable = get_option('hh_disable_gutenberg_global');
        $specific_types = get_option('hh_classic_post_types', []);

        // If global toggle is ON, return false (disable Gutenberg)
        if ($global_disable) {
            return false;
        }

        // If specific post type is checked, return false
        if (in_array($post->post_type, $specific_types)) {
            return false;
        }

        return $use_block_editor;
    }

    /**
     * Step 4: UI Cleanup
     */
    public function hide_gutenberg_ui() {
        echo '<style>
            .edit-post-header__settings .editor-post-switch-to-gutenberg,
            .edit-post-sidebar .components-panel__body .editor-post-switch-to-gutenberg,
            .tw-try-gutenberg,
            #try-gutenberg-panel { display: none !important; }
        </style>';
    }

    /**
     * Step 5: Render the Admin Dashboard
     */
    public function render_settings_page() {
        $post_types = get_post_types(['public' => true], 'objects');
        $selected_types = get_option('hh_classic_post_types', []);
        $global_checked = get_option('hh_disable_gutenberg_global');
        ?>
        <div class="wrap">
            <h1>Heirloom & Hops: Editor Control</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('hh_editor_settings_group');
                do_settings_sections('hh_editor_settings_group');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">Global Disable</th>
                        <td>
                            <label>
                                <input type="checkbox" name="hh_disable_gutenberg_global" value="1" <?php checked(1, $global_checked); ?> />
                                Force Classic Editor for <strong>all</strong> post types.
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Granular Control</th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span>Select Post Types</span></legend>
                                <?php foreach ($post_types as $type) : ?>
                                    <label style="display: block; margin-bottom: 5px;">
                                        <input type="checkbox" name="hh_classic_post_types[]" value="<?php echo esc_attr($type->name); ?>" <?php checked(in_array($type->name, $selected_types)); ?> />
                                        Use Classic Editor for <strong><?php echo esc_html($type->label); ?></strong>
                                    </label>
                                <?php endforeach; ?>
                            </fieldset>
                            <p class="description">Select specific types to use the Classic Editor if the global toggle is off.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}

new HH_Editor_Control();