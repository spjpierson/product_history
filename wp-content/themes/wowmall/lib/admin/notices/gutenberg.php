<?php

$plugin_slug = 'gutenberg';

$plugin_path = "{$plugin_slug}/{$plugin_slug}.php";

$plugin_activated = is_plugin_active($plugin_path);

$plugin_installed = array_key_exists($plugin_path, get_plugins());

$plugin_installing = isset($_GET['action'], $_GET['plugin']) && $_GET['action'] == 'install-plugin' && $_GET['plugin'] == 'gutenberg';

if (!WOWMALL_THEME_DEVELOPMENT_MODE && get_user_meta(get_current_user_id(), 'wowmall-notice-gutenberg', true) && $plugin_activated && $plugin_installed) {
    return;
}

?>

<div class="notice wowmall-notice">
    <div class="wowmall-notice-inner">
        <button type="button" class="notice-dismiss" data-dismiss-notice="wowmall-notice-gutenberg">
            <span class="screen-reader-text">
                <?php esc_html_e('Dismiss this notice.', 'wowmall'); ?>
            </span>
        </button>
        <span class="wowmall-notice-logo">
            <img src="<?php echo esc_url(WOWMALL_THEME_URL . '/assets/images/isotype-white.svg'); ?>">
        </span>
        <div class="wowmall-notice-content">
            <h2><?php esc_html_e('Thanks for installing Wowmall!', 'wowmall'); ?></h2>
            <p>
                <?php esc_html_e('Wowmall is an experimental full site editing theme.', 'wowmall'); ?>
                <br>
                <?php esc_html_e('This theme requires the latest Gutenberg plugin version. Activate the plugin to view the theme.', 'wowmall'); ?>
                <br>
            </p>

            <div class="wowmall-notice-actions">
                <?php
                if ($plugin_installed) {
                    if (current_user_can('activate_plugins')) : ?>
                        <a href="<?php echo wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin_path . '&amp;plugin_status=all&amp;paged=1', 'activate-plugin_' . $plugin_path); ?>" class="button button-primary <?php echo ($plugin_activated ? 'disabled' : ''); ?>"><?php printf(esc_html__('Activate %s', 'wowmall'), 'Gutenberg'); ?></a>
                    <?php endif;
                } else {
                    if (current_user_can('install_plugins')) : ?>
                        <a href="<?php echo wp_nonce_url(self_admin_url("update.php?action=install-plugin&plugin={$plugin_slug}"), "install-plugin_{$plugin_slug}"); ?>" class='button button-primary <?php echo ($plugin_installing ? 'disabled' : ''); ?>'><?php printf(esc_html__('Install %s', 'wowmall'), 'Gutenberg'); ?></a>
                <?php endif;
                }; ?>
                <a href="<?php echo admin_url('themes.php?page=wowmall'); ?>" class="button button-primary"><?php esc_html_e('About us', 'wowmall'); ?></a>
            </div>
        </div>
    </div>
</div>