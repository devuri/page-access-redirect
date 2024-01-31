<?php

namespace PageAccessRedirect;

class SelectivePageAccess
{
    public const PAGE_ACCESS_OPTION = 'spr_settings';

    public function __construct()
    {
		add_action( 'admin_init', [ $this, 'settings_init' ] );
        add_action( 'admin_menu', [ $this, 'create_settings_page' ] );
        add_action( 'template_redirect', [ $this, 'restrict_page_access' ] );
    }

	public static function init()
	{
		return new self();
	}

    public function create_settings_page(): void
    {
        add_options_page(
            'Page Access Redirect',
            'Page Access Redirect',
            'manage_options',
            'selective-page-access',
            [ $this, 'settings_page_html' ]
        );
    }

    public function settings_init(): void
    {
        register_setting( 'selectivePageAccess', self::PAGE_ACCESS_OPTION );

        add_settings_section(
            'spr_pages_section',
            __( 'Select Pages', 'selectivePageAccess' ),
            [ $this, 'settings_section_cb' ],
            'selectivePageAccess'
        );

        add_settings_field(
            'spr_select_pages',
            __( 'Pages', 'selectivePageAccess' ),
            [ $this, 'pages_field_cb' ],
            'selectivePageAccess',
            'spr_pages_section'
        );
    }

    public function settings_section_cb(): void
    {
        echo '<p>' . __( 'Select the pages that should be accessible. Others will redirect to the homepage.', 'selectivePageAccess' ) . '</p>';
    }

    public function pages_field_cb(): void
    {
        $options = get_option( self::PAGE_ACCESS_OPTION, [] );
        $pages   = get_pages();
        foreach ( $pages as $page ) {
            $checked = isset( $options['pages'][ $page->ID ] ) ? 'checked' : '';
            echo '<input type="checkbox" id="spr_pages_' . $page->ID . '" name="spr_settings[pages][' . $page->ID . ']" value="' . $page->ID . '" ' . $checked . '>';
            echo '<label for="spr_pages_' . $page->ID . '">' . $page->post_title . ' [' . $page->ID . ']</label><br>';
        }
    }

    public function settings_page_html(): void
    {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="options.php" method="post">
                <?php
				settings_fields( 'selectivePageAccess' );
				do_settings_sections( 'selectivePageAccess' );
				submit_button( 'Save Settings' );
				?>
            </form>
        </div>
        <?php
    }

    public function restrict_page_access(): void
    {
        if ( is_admin() || is_front_page() ) {
            return;
        }

        if ( current_user_can( 'manage_options' ) ) {
            return;
        }

        $options = get_option( self::PAGE_ACCESS_OPTION, [] );

        if ( empty( $options ) ) {
            return;
        }

        $page_ids = $options['pages'];

        if ( ! is_page( $page_ids ) && ! empty( $page_ids ) ) {
            wp_redirect( home_url( '/' ) );
            exit;
        }
    }
}
