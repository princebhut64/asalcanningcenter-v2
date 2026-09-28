<?php
/**
 * Asal Canning Center - SMTP Service & Email Manager
 *
 * 1. SMTP Service integration with WordPress (phpmailer_init).
 * 2. Automated Customer Autoresponder ("Thank You for Reaching Out" with Image Offers).
 * 3. Automated Admin Notification on Every Inquiry.
 * 4. Rich HTML Email Templates Previewer (Desktop & Mobile).
 * 5. Campaign / Broadcast Sender for Inquiries & Customers.
 * 6. 1-Click Follow-Up Mailer inside Inquiry Admin Screen.
 *
 * @package asalcanningcenter
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/email-templates.php';

/**
 * 1. Get or Default SMTP Settings
 */
function asal_get_smtp_settings() {
    $defaults = [
        'enabled'                   => 1,
        'host'                      => 'smtp.gmail.com',
        'port'                      => 587,
        'encryption'                => 'tls', // 'tls', 'ssl', 'none'
        'auth'                      => 1,
        'username'                  => 'xlcreater000@gmail.com',
        'password'                  => 'agakseqzssarohhz',
        'from_email'                => 'xlcreater000@gmail.com',
        'from_name'                 => 'Asal Canning Center',
        'admin_email'               => 'xlcreater000@gmail.com',
        'disable_ssl_verify'        => 1, // Crucial for Windows/XAMPP localhost compatibility
        'customer_autoresponder'    => 1,
        'admin_notification'        => 1,
        'public_image_base'         => '',
    ];

    $saved = get_option( 'asal_smtp_settings', [] );
    return wp_parse_args( $saved, $defaults );
}

/**
 * Helper: Check if SMTP is actively enabled
 */
function asal_is_smtp_active() {
    $s = asal_get_smtp_settings();
    return ! empty( $s['enabled'] ) && ! empty( $s['host'] ) && ! empty( $s['username'] );
}

/**
 * 2. Configure PHPMailer via phpmailer_init hook
 */
add_action( 'phpmailer_init', 'asal_phpmailer_configure_smtp', 999 );
function asal_phpmailer_configure_smtp( $phpmailer ) {
    $s = asal_get_smtp_settings();

    // If SMTP service is enabled in settings
    if ( ! empty( $s['enabled'] ) && ! empty( $s['host'] ) ) {
        $phpmailer->isSMTP();
        $phpmailer->Host       = sanitize_text_field( $s['host'] );
        $phpmailer->Port       = absint( $s['port'] );
        $phpmailer->SMTPAuth   = ! empty( $s['auth'] );

        if ( $phpmailer->SMTPAuth ) {
            $phpmailer->Username = sanitize_text_field( $s['username'] );
            $phpmailer->Password = $s['password'];
        }

        // Encryption
        if ( 'ssl' === $s['encryption'] ) {
            $phpmailer->SMTPSecure = 'ssl';
        } elseif ( 'tls' === $s['encryption'] ) {
            $phpmailer->SMTPSecure = 'tls';
        } else {
            $phpmailer->SMTPSecure = '';
            $phpmailer->SMTPAutoTLS = false;
        }

        // Disable SSL certificate verification if configured (needed on local XAMPP/Windows)
        if ( ! empty( $s['disable_ssl_verify'] ) ) {
            $phpmailer->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ],
            ];
        }

        // From email & name
        if ( ! empty( $s['from_email'] ) ) {
            $phpmailer->From     = sanitize_email( $s['from_email'] );
            $phpmailer->FromName = ! empty( $s['from_name'] ) ? sanitize_text_field( $s['from_name'] ) : 'Asal Canning Center';
        }
    }
}

/**
 * Filter outgoing mail sender headers
 */
add_filter( 'wp_mail_from', 'asal_smtp_mail_from', 20 );
function asal_smtp_mail_from( $from ) {
    $s = asal_get_smtp_settings();
    if ( ! empty( $s['from_email'] ) ) {
        return sanitize_email( $s['from_email'] );
    }
    return $from;
}

add_filter( 'wp_mail_from_name', 'asal_smtp_mail_from_name', 20 );
function asal_smtp_mail_from_name( $name ) {
    $s = asal_get_smtp_settings();
    if ( ! empty( $s['from_name'] ) ) {
        return sanitize_text_field( $s['from_name'] );
    }
    return $name;
}

/**
 * 3. Automated Inquiry Dispatcher
 * Triggered when a new inquiry is recorded
 */
function asal_dispatch_inquiry_emails( $post_id, $data = [] ) {
    if ( ! $post_id ) {
        return;
    }

    $s = asal_get_smtp_settings();

    $name    = $data['name'] ?? get_post_meta( $post_id, '_inquiry_name', true );
    $phone   = $data['phone'] ?? get_post_meta( $post_id, '_inquiry_phone', true );
    $email   = $data['email'] ?? get_post_meta( $post_id, '_inquiry_email', true );
    $subject = $data['subject'] ?? get_post_meta( $post_id, '_inquiry_subject', true );
    $message = $data['message'] ?? get_post_field( 'post_content', $post_id );
    $date    = current_time( 'F j, Y - g:i A' );

    $headers = [ 'Content-Type: text/html; charset=UTF-8' ];

    // A. Customer Autoresponder ("Thank You for Reaching Out" + Image Offers)
    if ( ! empty( $s['customer_autoresponder'] ) && ! empty( $email ) && is_email( $email ) ) {
        $customer_subject = 'Thank you for reaching out to Asal Canning Center – We have received your inquiry!';
        $customer_html    = asal_get_email_template_thank_you( [
            'name'    => $name ?: 'Valued Client',
            'subject' => $subject ?: 'Fruit Processing & Canning',
            'phone'   => $phone,
            'message' => $message,
            'date'    => $date,
        ] );

        $sent_customer = wp_mail( $email, $customer_subject, $customer_html, $headers );
        if ( $sent_customer ) {
            update_post_meta( $post_id, '_inquiry_autoresponder_sent', current_time( 'mysql' ) );
        } else {
            update_post_meta( $post_id, '_inquiry_autoresponder_status', 'failed' );
        }
    }

    // B. Admin Notification Email
    if ( ! empty( $s['admin_notification'] ) ) {
        $admin_to = ! empty( $s['admin_email'] ) ? $s['admin_email'] : get_option( 'admin_email' );
        if ( ! empty( $admin_to ) && is_email( $admin_to ) ) {
            $admin_subject = '🔔 [New Inquiry] ' . ( $name ? $name : 'New Lead' ) . ( $subject ? ' – ' . $subject : '' );
            $admin_html    = asal_get_email_template_admin_notification( [
                'post_id' => $post_id,
                'name'    => $name,
                'phone'   => $phone,
                'email'   => $email,
                'subject' => $subject,
                'message' => $message,
            ] );

            $sent_admin = wp_mail( $admin_to, $admin_subject, $admin_html, $headers );
            if ( $sent_admin ) {
                update_post_meta( $post_id, '_inquiry_admin_notified', current_time( 'mysql' ) );
            } else {
                update_post_meta( $post_id, '_inquiry_admin_status', 'failed' );
            }
        }
    }
}

/**
 * 4. Register Admin Menu for SMTP & Email Hub
 */
add_action( 'admin_menu', 'asal_register_smtp_admin_menu' );
function asal_register_smtp_admin_menu() {
    // Top level menu
    add_menu_page(
        __( 'Asal Email & SMTP', 'asalcanningcenter' ),
        __( 'Email & SMTP', 'asalcanningcenter' ),
        'manage_options',
        'asal-email-smtp',
        'asal_render_smtp_admin_page',
        'dashicons-email-alt2',
        27
    );

    // Submenu: Settings & SMTP
    add_submenu_page(
        'asal-email-smtp',
        __( 'SMTP Settings & Tester', 'asalcanningcenter' ),
        __( 'SMTP Settings', 'asalcanningcenter' ),
        'manage_options',
        'asal-email-smtp',
        'asal_render_smtp_admin_page'
    );

    // Submenu: Email Templates & Live Preview
    add_submenu_page(
        'asal-email-smtp',
        __( 'Email Templates & Preview', 'asalcanningcenter' ),
        __( 'Email Templates', 'asalcanningcenter' ),
        'manage_options',
        'asal-email-templates',
        'asal_render_templates_admin_page'
    );

    // Submenu: Send Campaign / Offers
    add_submenu_page(
        'asal-email-smtp',
        __( 'Send Offers & Newsletters', 'asalcanningcenter' ),
        __( 'Send Campaign', 'asalcanningcenter' ),
        'manage_options',
        'asal-email-campaigns',
        'asal_render_campaigns_admin_page'
    );
}

/**
 * Admin Styles for Email & SMTP Suite
 */
add_action( 'admin_head', 'asal_smtp_admin_custom_styles' );
function asal_smtp_admin_custom_styles() {
    ?>
    <style>
        /* Highlight Email & SMTP menu icon in Admin Sidebar */
        #toplevel_page_asal-email-smtp .dashicons-email-alt2 {
            color: #E0A238 !important;
        }
        #toplevel_page_asal-email-smtp.current .dashicons-email-alt2,
        #toplevel_page_asal-email-smtp:hover .dashicons-email-alt2 {
            color: #ffffff !important;
        }
        /* Email Hub Tabs */
        .asal-email-wrap .nav-tab-wrapper {
            border-bottom: 2px solid #E0A238;
            margin-bottom: 20px;
        }
        .asal-email-wrap .nav-tab-active {
            background-color: #E0A238 !important;
            border-color: #E0A238 !important;
            color: #14181F !important;
            font-weight: 700 !important;
        }
        /* Inquiry direct mail box styling */
        #asal_inquiry_mail_actions .inside {
            padding: 12px;
            background: #fffdfa;
        }
    </style>
    <?php
}

/**
 * 5. Render Admin Page: SMTP Settings & Test Tool
 */
function asal_render_smtp_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $notice = '';

    // Handle settings submission
    if ( isset( $_POST['asal_save_smtp_nonce'] ) && wp_verify_nonce( $_POST['asal_save_smtp_nonce'], 'asal_save_smtp_action' ) ) {
        $settings = [
            'enabled'                => isset( $_POST['enabled'] ) ? 1 : 0,
            'host'                   => sanitize_text_field( $_POST['host'] ?? '' ),
            'port'                   => absint( $_POST['port'] ?? 587 ),
            'encryption'             => sanitize_key( $_POST['encryption'] ?? 'tls' ),
            'auth'                   => isset( $_POST['auth'] ) ? 1 : 0,
            'username'               => sanitize_text_field( $_POST['username'] ?? '' ),
            'password'               => $_POST['password'] ?? '',
            'from_email'             => sanitize_email( $_POST['from_email'] ?? '' ),
            'from_name'              => sanitize_text_field( $_POST['from_name'] ?? '' ),
            'admin_email'            => sanitize_email( $_POST['admin_email'] ?? '' ),
            'disable_ssl_verify'     => isset( $_POST['disable_ssl_verify'] ) ? 1 : 0,
            'customer_autoresponder' => isset( $_POST['customer_autoresponder'] ) ? 1 : 0,
            'admin_notification'     => isset( $_POST['admin_notification'] ) ? 1 : 0,
            'public_image_base'      => esc_url_raw( trim( $_POST['public_image_base'] ?? '' ) ),
        ];

        update_option( 'asal_smtp_settings', $settings );
        $notice = '<div class="notice notice-success is-dismissible"><p><strong>Success!</strong> SMTP and email automation settings saved successfully.</p></div>';
    }

    $s = asal_get_smtp_settings();
    ?>
    <div class="wrap asal-email-wrap" style="max-width: 1050px;">
        <h1 style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
            <span class="dashicons dashicons-email-alt2" style="font-size: 32px; width: 32px; height: 32px; color: #E0A238;"></span>
            Asal Canning Center &mdash; SMTP Service &amp; Automation
        </h1>
        <p class="description" style="margin-bottom: 20px; font-size: 14px;">
            Configure your SMTP server to deliver instant autoresponder emails, image offers, seasonal booking alerts, and new inquiry notifications.
        </p>

        <?php echo $notice; ?>

        <!-- Status Summary Bar -->
        <div style="background: #ffffff; border: 1px solid #dcdcde; border-left: 4px solid <?php echo ! empty( $s['enabled'] ) ? '#16A34A' : '#E0A238'; ?>; border-radius: 6px; padding: 14px 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
            <div>
                <strong style="font-size: 14px; color: #14181F;">SMTP Service Status:</strong>
                <?php if ( ! empty( $s['enabled'] ) ) : ?>
                    <span style="display: inline-block; background: #DCFCE7; color: #166534; font-weight: 700; font-size: 12px; padding: 3px 10px; border-radius: 12px; margin-left: 6px;">
                        ● ACTIVE (Routing via <?php echo esc_html( $s['host'] ?: 'SMTP' ); ?>)
                    </span>
                <?php else : ?>
                    <span style="display: inline-block; background: #FEF3C7; color: #92400E; font-weight: 700; font-size: 12px; padding: 3px 10px; border-radius: 12px; margin-left: 6px;">
                        ○ INACTIVE / LOCAL FALLBACK
                    </span>
                <?php endif; ?>
                <div style="font-size: 12px; color: #64748B; margin-top: 4px;">
                    From: <strong><?php echo esc_html( $s['from_name'] ); ?></strong> &lt;<?php echo esc_html( $s['from_email'] ); ?>&gt; &bull; Admin Alerts To: <strong><?php echo esc_html( $s['admin_email'] ); ?></strong>
                </div>
            </div>
            <div>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=asal-email-templates' ) ); ?>" class="button button-secondary">
                    View 4 Email Templates &rarr;
                </a>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=asal-email-campaigns' ) ); ?>" class="button button-secondary">
                    Send Campaign / Offers &rarr;
                </a>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 24px;">
            
            <!-- Column 1: SMTP Settings Form -->
            <div style="background: #ffffff; border: 1px solid #dcdcde; border-radius: 8px; padding: 22px 26px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                <form method="post" action="">
                    <?php wp_nonce_field( 'asal_save_smtp_action', 'asal_save_smtp_nonce' ); ?>

                    <h2 style="margin-top: 0; padding-bottom: 10px; border-bottom: 1px solid #f1f1f1; font-size: 17px;">
                        ⚙️ SMTP Server Credentials
                    </h2>

                    <!-- Enable Switch -->
                    <div style="margin-bottom: 18px; background: #fafafa; padding: 12px 14px; border-radius: 6px; border: 1px solid #eee;">
                        <label style="font-weight: 600; cursor: pointer; font-size: 14px;">
                            <input type="checkbox" name="enabled" value="1" <?php checked( $s['enabled'], 1 ); ?>>
                            <strong>Enable SMTP Service</strong>
                        </label>
                        <p class="description" style="margin: 4px 0 0 24px; font-size: 12px;">
                            Route all outgoing WordPress emails and inquiry notifications through this SMTP server.
                        </p>
                    </div>

                    <!-- Quick Presets -->
                    <div style="margin-bottom: 18px;">
                        <label style="font-weight: 600; display: block; margin-bottom: 6px; font-size: 12px; color: #4B5563; text-transform: uppercase;">
                            Quick Presets (Click to autofill):
                        </label>
                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                            <button type="button" class="button button-small" onclick="asalApplyPreset('gmail')">Gmail / Google Workspace</button>
                            <button type="button" class="button button-small" onclick="asalApplyPreset('hostinger')">Hostinger</button>
                            <button type="button" class="button button-small" onclick="asalApplyPreset('outlook')">Outlook / Office 365</button>
                            <button type="button" class="button button-small" onclick="asalApplyPreset('zoho')">Zoho Mail</button>
                        </div>
                    </div>

                    <table class="form-table" style="margin-top: 0;">
                        <tr>
                            <th scope="row" style="width: 140px;"><label for="host">SMTP Host</label></th>
                            <td>
                                <input type="text" id="host" name="host" value="<?php echo esc_attr( $s['host'] ); ?>" class="regular-text" placeholder="e.g. smtp.gmail.com" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="port">SMTP Port</label></th>
                            <td>
                                <input type="number" id="port" name="port" value="<?php echo esc_attr( $s['port'] ); ?>" style="width: 100px;" required>
                                <span class="description" style="margin-left: 8px;">Typical: <strong>587</strong> (TLS) or <strong>465</strong> (SSL)</span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="encryption">Encryption</label></th>
                            <td>
                                <select id="encryption" name="encryption">
                                    <option value="tls" <?php selected( $s['encryption'], 'tls' ); ?>>TLS (Recommended - Port 587)</option>
                                    <option value="ssl" <?php selected( $s['encryption'], 'ssl' ); ?>>SSL (Port 465)</option>
                                    <option value="none" <?php selected( $s['encryption'], 'none' ); ?>>None (Insecure / Port 25)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="auth">Authentication</label></th>
                            <td>
                                <label>
                                    <input type="checkbox" id="auth" name="auth" value="1" <?php checked( $s['auth'], 1 ); ?>>
                                    Yes (Requires Username and Password)
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="username">SMTP Username</label></th>
                            <td>
                                <input type="text" id="username" name="username" value="<?php echo esc_attr( $s['username'] ); ?>" class="regular-text" placeholder="e.g. xlcreater000@gmail.com">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="password">SMTP Password</label></th>
                            <td>
                                <div style="display: flex; gap: 6px; align-items: center;">
                                    <input type="password" id="password" name="password" value="<?php echo esc_attr( $s['password'] ); ?>" class="regular-text" placeholder="App Password / Account Password">
                                    <button type="button" class="button" onclick="asalTogglePassword()">Show</button>
                                </div>
                                <p class="description" style="font-size: 11px; margin-top: 4px; color: #64748B;">
                                    For Gmail: Use a 16-character <strong>Google App Password</strong> (e.g. <code>agak seqz ssar ohhz</code>).
                                </p>
                            </td>
                        </tr>
                    </table>

                    <h2 style="margin-top: 20px; padding-bottom: 10px; border-bottom: 1px solid #f1f1f1; font-size: 17px;">
                        📨 Sender &amp; Notification Identities
                    </h2>

                    <table class="form-table" style="margin-top: 0;">
                        <tr>
                            <th scope="row" style="width: 140px;"><label for="from_email">From Email</label></th>
                            <td>
                                <input type="email" id="from_email" name="from_email" value="<?php echo esc_attr( $s['from_email'] ); ?>" class="regular-text" required>
                                <p class="description" style="font-size: 11px;">Must match or be an authorized alias of your SMTP Username.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="from_name">From Name</label></th>
                            <td>
                                <input type="text" id="from_name" name="from_name" value="<?php echo esc_attr( $s['from_name'] ); ?>" class="regular-text" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="admin_email">Admin Alert Email</label></th>
                            <td>
                                <input type="email" id="admin_email" name="admin_email" value="<?php echo esc_attr( $s['admin_email'] ); ?>" class="regular-text" required>
                                <p class="description" style="font-size: 11px;">Receives immediate lead notifications whenever a customer submits an inquiry.</p>
                            </td>
                        </tr>
                    </table>

                    <h2 style="margin-top: 20px; padding-bottom: 10px; border-bottom: 1px solid #f1f1f1; font-size: 17px;">
                        🤖 Inquiry Automations &amp; Compatibility
                    </h2>

                    <div style="margin-bottom: 14px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                            <input type="checkbox" name="customer_autoresponder" value="1" <?php checked( $s['customer_autoresponder'], 1 ); ?>>
                            Send Instant "Thank You for Reaching Out" Email to Inquirer (with Seasonal Image Offers)
                        </label>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                            <input type="checkbox" name="admin_notification" value="1" <?php checked( $s['admin_notification'], 1 ); ?>>
                            Send Instant Lead Alert to Administrator Email
                        </label>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                            <input type="checkbox" name="disable_ssl_verify" value="1" <?php checked( $s['disable_ssl_verify'], 1 ); ?>>
                            Bypass Strict SSL Peer Verification (Recommended for Windows / XAMPP localhost development)
                        </label>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label for="public_image_base" style="font-weight: 600; display: block; margin-bottom: 4px;">Public Image Base URL (Optional)</label>
                        <input type="url" id="public_image_base" name="public_image_base" value="<?php echo esc_attr( $s['public_image_base'] ); ?>" class="regular-text" placeholder="https://yourlivesite.com/wp-content/uploads/2026/09">
                        <p class="description" style="font-size: 11px;">
                            On local development, external email clients like Gmail cannot fetch images from <code>http://localhost/</code>. If you have uploaded images to your live server or CDN, enter the public base folder URL here.
                        </p>
                    </div>

                    <p class="submit" style="margin-top: 24px;">
                        <button type="submit" class="button button-primary button-large" style="background: #E0A238; border-color: #C98D28; color: #14181F; font-weight: 700; padding: 6px 20px;">
                            Save SMTP Settings
                        </button>
                    </p>
                </form>
            </div>

            <!-- Column 2: Live Test Tool & Help -->
            <div>
                <!-- Live Test Email Card -->
                <div style="background: #ffffff; border: 1px solid #dcdcde; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); margin-bottom: 20px;">
                    <h3 style="margin-top: 0; font-size: 16px; display: flex; align-items: center; gap: 6px;">
                        <span class="dashicons dashicons-airplane" style="color: #2563EB;"></span>
                        Send Live Test Email
                    </h3>
                    <p style="font-size: 13px; color: #64748B; margin-bottom: 14px;">
                        Verify your SMTP credentials by sending a live test email directly to your inbox.
                    </p>

                    <div style="margin-bottom: 12px;">
                        <label for="test_email_recipient" style="display: block; font-weight: 600; font-size: 12px; margin-bottom: 4px;">Recipient Email Address:</label>
                        <input type="email" id="test_email_recipient" class="widefat" value="<?php echo esc_attr( $s['admin_email'] ); ?>" placeholder="e.g. xlcreater000@gmail.com">
                    </div>

                    <div style="margin-bottom: 14px;">
                        <label for="test_template_select" style="display: block; font-weight: 600; font-size: 12px; margin-bottom: 4px;">Choose Template to Test:</label>
                        <select id="test_template_select" class="widefat">
                            <option value="test_ping">Standard SMTP Ping / Diagnostic</option>
                            <option value="thank_you">Template 1: Thank You for Reaching Out (Offers &amp; Images)</option>
                            <option value="season_coming">Template 2: Upcoming Harvest Season is Coming</option>
                            <option value="special_offers">Template 3: Special Promotional Offers (20% Off)</option>
                            <option value="news_updates">Template 4: Asal Canning Bulletin (News &amp; Workshops)</option>
                        </select>
                    </div>

                    <button type="button" id="btn-send-test" class="button button-primary widefat" style="text-align: center; justify-content: center; height: 38px; line-height: 36px; font-weight: 600;" onclick="asalSendTestEmail()">
                        <span class="dashicons dashicons-email" style="margin-top: 8px; margin-right: 4px;"></span> Send Test Email Now
                    </button>

                    <!-- Test Output Console -->
                    <div id="test-result-box" style="display: none; margin-top: 14px; padding: 12px; border-radius: 6px; font-size: 12px; font-family: monospace; white-space: pre-wrap; max-height: 250px; overflow-y: auto;"></div>
                </div>

                <!-- Guidance Box -->
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 18px;">
                    <h4 style="margin: 0 0 10px 0; font-size: 14px; color: #1E293B;">💡 Gmail Setup Instructions</h4>
                    <ol style="margin: 0; padding-left: 18px; font-size: 12px; color: #475569; line-height: 1.6;">
                        <li>Log into your Google account and go to <strong>Security</strong>.</li>
                        <li>Turn on <strong>2-Step Verification</strong>.</li>
                        <li>Go to <strong>App Passwords</strong> (<a href="https://myaccount.google.com/apppasswords" target="_blank" rel="noopener">myaccount.google.com/apppasswords</a>).</li>
                        <li>Create a new app named <strong>Asal Canning Center</strong>.</li>
                        <li>Copy the generated <strong>16-character code</strong> and paste it into the <em>SMTP Password</em> field above.</li>
                    </ol>
                </div>
            </div>

        </div>
    </div>

    <script>
    function asalTogglePassword() {
        var input = document.getElementById('password');
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    function asalApplyPreset(preset) {
        var host = document.getElementById('host');
        var port = document.getElementById('port');
        var enc  = document.getElementById('encryption');
        var auth = document.getElementById('auth');

        auth.checked = true;

        if (preset === 'gmail') {
            host.value = 'smtp.gmail.com';
            port.value = '587';
            enc.value  = 'tls';
        } else if (preset === 'hostinger') {
            host.value = 'smtp.hostinger.com';
            port.value = '465';
            enc.value  = 'ssl';
        } else if (preset === 'outlook') {
            host.value = 'smtp.office365.com';
            port.value = '587';
            enc.value  = 'tls';
        } else if (preset === 'zoho') {
            host.value = 'smtppro.zoho.com';
            port.value = '465';
            enc.value  = 'ssl';
        }
    }

    function asalSendTestEmail() {
        var btn = document.getElementById('btn-send-test');
        var box = document.getElementById('test-result-box');
        var to  = document.getElementById('test_email_recipient').value;
        var tpl = document.getElementById('test_template_select').value;

        if (!to) {
            alert('Please enter a recipient email address.');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = 'Sending test... Please wait...';
        box.style.display = 'block';
        box.style.background = '#F1F5F9';
        box.style.color = '#334155';
        box.style.border = '1px solid #CBD5E1';
        box.innerHTML = 'Connecting to SMTP server and dispatching email...';

        var formData = new FormData();
        formData.append('action', 'asal_smtp_test_email');
        formData.append('nonce', '<?php echo wp_create_nonce( 'asal_smtp_test_nonce' ); ?>');
        formData.append('to_email', to);
        formData.append('template', tpl);

        fetch(ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            btn.disabled = false;
            btn.innerHTML = '<span class="dashicons dashicons-email" style="margin-top: 8px; margin-right: 4px;"></span> Send Test Email Now';
            if (data.success) {
                box.style.background = '#DCFCE7';
                box.style.color = '#14532D';
                box.style.border = '1px solid #86EFAC';
                box.innerHTML = '✅ SUCCESS!\n' + data.data.message + (data.data.log ? '\n\n[Debug Log]:\n' + data.data.log : '');
            } else {
                box.style.background = '#FEE2E2';
                box.style.color = '#7F1D1D';
                box.style.border = '1px solid #FCA5A5';
                box.innerHTML = '❌ ERROR: ' + data.data.message + (data.data.log ? '\n\n[Debug Log]:\n' + data.data.log : '');
            }
        })
        .catch(function(err) {
            btn.disabled = false;
            btn.innerHTML = '<span class="dashicons dashicons-email" style="margin-top: 8px; margin-right: 4px;"></span> Send Test Email Now';
            box.style.background = '#FEE2E2';
            box.style.color = '#7F1D1D';
            box.innerHTML = 'Network request error: ' + err;
        });
    }
    </script>
    <?php
}

/**
 * 6. Render Admin Page: Email Templates & Live Preview
 */
function asal_render_templates_admin_page() {
    $active_tpl = isset( $_GET['tpl'] ) ? sanitize_key( $_GET['tpl'] ) : 'thank_you';
    $templates = [
        'thank_you'     => [
            'name'        => '1. Thank You for Reaching Out',
            'badge'       => 'Inquiry Autoresponder',
            'desc'        => 'Sent automatically when a customer submits an inquiry. Features personalized greeting, inquiry summary, upcoming harvest season announcement, and product image offers (Mango Pulp, Amla Chyawanprash, Contract Line).',
        ],
        'season_coming' => [
            'name'        => '2. Harvest Season is Coming',
            'badge'       => 'Seasonal Pre-Booking',
            'desc'        => 'Announces the approaching fruit season (Mango, Jamun, Amla) and encourages farmers & commercial food businesses to reserve their commercial pulping, extraction, and retort canning slots in advance.',
        ],
        'special_offers' => [
            'name'        => '3. Special Offers & Bulk Deals',
            'badge'       => 'Promo Voucher & Discounts',
            'desc'        => 'Features coupon code ASALHARVEST20, discount tiers (up to 20% off), and a multi-column visual showcase of pure pulp cans, artisanal chyawanprash jars, squashes, and traditional pickles.',
        ],
        'news_updates'  => [
            'name'        => '4. Asal Canning Bulletin',
            'badge'       => 'News, Tech & Workshops',
            'desc'        => 'Editorial newsletter format highlighting women farmer workshops led by founder Jalpa Patel, advanced SS-304 continuous extraction tech, zero-preservative canning, and the regional fruit calendar.',
        ],
    ];

    $s = asal_get_smtp_settings();
    ?>
    <div class="wrap asal-email-wrap" style="max-width: 1200px;">
        <h1 style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
            <span class="dashicons dashicons-layout" style="font-size: 30px; width: 30px; height: 30px; color: #E0A238;"></span>
            Email Templates Library &amp; Live Previewer
        </h1>
        <p class="description" style="margin-bottom: 20px; font-size: 14px;">
            Inspect, test-send, and preview the 4 branded HTML email templates in desktop and mobile responsive views.
        </p>

        <!-- Template Navigation Tabs -->
        <h2 class="nav-tab-wrapper" style="margin-bottom: 20px;">
            <?php foreach ( $templates as $key => $info ) : ?>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=asal-email-templates&tpl=' . $key ) ); ?>" class="nav-tab <?php echo $active_tpl === $key ? 'nav-tab-active' : ''; ?>">
                    <?php echo esc_html( $info['name'] ); ?>
                </a>
            <?php endforeach; ?>
        </h2>

        <!-- Active Template Header & Controls -->
        <div style="background: #ffffff; border: 1px solid #dcdcde; border-radius: 8px; padding: 18px 24px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div style="max-width: 650px;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                    <h2 style="margin: 0; font-size: 18px; color: #14181F;"><?php echo esc_html( $templates[ $active_tpl ]['name'] ); ?></h2>
                    <span style="background: #FEF3C7; color: #B45309; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 10px;">
                        <?php echo esc_html( $templates[ $active_tpl ]['badge'] ); ?>
                    </span>
                </div>
                <p style="margin: 0; font-size: 13px; color: #64748B;">
                    <?php echo esc_html( $templates[ $active_tpl ]['desc'] ); ?>
                </p>
            </div>

            <!-- Preview Toggle & Quick Test Send -->
            <div style="display: flex; align-items: center; gap: 10px;">
                <!-- Desktop / Mobile Toggle -->
                <div style="display: flex; border: 1px solid #CBD5E1; border-radius: 6px; overflow: hidden;">
                    <button type="button" class="button" id="btn-view-desktop" style="border: none; border-radius: 0; background: #14181F; color: #FFFFFF;" onclick="asalSetViewport(650, this)">
                        <span class="dashicons dashicons-desktop" style="margin-top: 4px;"></span> Desktop
                    </button>
                    <button type="button" class="button" id="btn-view-mobile" style="border: none; border-radius: 0; background: #FFFFFF; color: #475569;" onclick="asalSetViewport(390, this)">
                        <span class="dashicons dashicons-smartphone" style="margin-top: 4px;"></span> Mobile
                    </button>
                </div>

                <!-- Test Send Button -->
                <button type="button" class="button button-primary" style="background: #E0A238; border-color: #C98D28; color: #14181F; font-weight: 700;" onclick="asalPromptTestSend('<?php echo esc_js( $active_tpl ); ?>')">
                    <span class="dashicons dashicons-email" style="margin-top: 4px;"></span> Send Test to My Email
                </button>
            </div>
        </div>

        <!-- Live Preview Frame Container -->
        <div style="background: #E5E7EB; padding: 30px 15px; border-radius: 8px; text-align: center; border: 1px solid #D1D5DB; box-shadow: inset 0 2px 6px rgba(0,0,0,0.06);">
            <div id="preview-wrapper" style="width: 650px; margin: 0 auto; transition: width 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border-radius: 12px; overflow: hidden; background: #FFFFFF;">
                <iframe id="email-preview-frame" src="<?php echo esc_url( add_query_arg( [ 'asal_email_preview' => '1', 'tpl' => $active_tpl, '_nonce' => wp_create_nonce( 'asal_preview_nonce' ) ], home_url( '/' ) ) ); ?>" style="width: 100%; height: 800px; border: none; display: block;" onload="asalAutoResizeFrame(this)"></iframe>
            </div>
        </div>
    </div>

    <script>
    function asalSetViewport(width, btn) {
        document.getElementById('preview-wrapper').style.width = width + 'px';
        document.getElementById('btn-view-desktop').style.background = '#FFFFFF';
        document.getElementById('btn-view-desktop').style.color = '#475569';
        document.getElementById('btn-view-mobile').style.background = '#FFFFFF';
        document.getElementById('btn-view-mobile').style.color = '#475569';
        btn.style.background = '#14181F';
        btn.style.color = '#FFFFFF';
    }

    function asalAutoResizeFrame(iframe) {
        try {
            if (iframe.contentWindow && iframe.contentWindow.document.body) {
                var h = iframe.contentWindow.document.body.scrollHeight;
                if (h > 400) {
                    iframe.style.height = (h + 40) + 'px';
                }
            }
        } catch(e) {}
    }

    function asalPromptTestSend(templateKey) {
        var email = prompt('Enter the email address to receive this test preview:', '<?php echo esc_js( $s['admin_email'] ); ?>');
        if (!email) return;

        var formData = new FormData();
        formData.append('action', 'asal_smtp_test_email');
        formData.append('nonce', '<?php echo wp_create_nonce( 'asal_smtp_test_nonce' ); ?>');
        formData.append('to_email', email);
        formData.append('template', templateKey);

        fetch(ajaxurl, { method: 'POST', body: formData })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                alert('Success! ' + data.data.message);
            } else {
                alert('Send failed: ' + data.data.message);
            }
        });
    }
    </script>
    <?php
}

/**
 * 7. Render Admin Page: Send Campaign / Offers to Inquiries
 */
function asal_render_campaigns_admin_page() {
    $inquiries = get_posts( [
        'post_type'      => 'asal_inquiry',
        'posts_per_page' => 100,
        'post_status'    => 'publish',
    ] );

    // Extract valid emails
    $client_emails = [];
    foreach ( $inquiries as $inq ) {
        $em = get_post_meta( $inq->ID, '_inquiry_email', true );
        if ( ! empty( $em ) && is_email( $em ) ) {
            $nm = get_post_meta( $inq->ID, '_inquiry_name', true ) ?: 'Client';
            $client_emails[ $em ] = $nm;
        }
    }

    $s = asal_get_smtp_settings();
    ?>
    <div class="wrap asal-email-wrap" style="max-width: 960px;">
        <h1 style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
            <span class="dashicons dashicons-megaphone" style="font-size: 30px; width: 30px; height: 30px; color: #E0A238;"></span>
            Send Offers, Seasonal Alerts &amp; Newsletters
        </h1>
        <p class="description" style="margin-bottom: 20px; font-size: 14px;">
            Broadcast seasonal harvest announcements, promotional coupon discounts, or newsletter bulletins to your website leads and client lists.
        </p>

        <div style="background: #ffffff; border: 1px solid #dcdcde; border-radius: 8px; padding: 24px 28px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <form id="campaign-form" onsubmit="asalSubmitCampaign(event)">
                <table class="form-table">
                    <tr>
                        <th scope="row" style="width: 170px;"><label for="campaign_template"><strong>Select Template</strong></label></th>
                        <td>
                            <select id="campaign_template" class="regular-text" style="max-width: 450px;" onchange="asalUpdateCampaignDefaults(this.value)">
                                <option value="season_coming">Template 2: Harvest Season is Coming (Pre-Booking)</option>
                                <option value="special_offers">Template 3: Special Promotional Offers (20% Off)</option>
                                <option value="news_updates">Template 4: Asal Canning Bulletin (News &amp; Workshops)</option>
                                <option value="thank_you">Template 1: General Follow-Up &amp; Thank You</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="campaign_subject"><strong>Email Subject Line</strong></label></th>
                        <td>
                            <input type="text" id="campaign_subject" class="large-text" value="🥭 The New Fruit Harvest Season is Approaching! Book Your Processing Slots Early" required>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="campaign_target"><strong>Audience / Recipients</strong></label></th>
                        <td>
                            <fieldset>
                                <label style="display: block; margin-bottom: 8px;">
                                    <input type="radio" name="audience" value="single" checked onclick="document.getElementById('single-email-wrap').style.display='block'">
                                    Send to Single / Test Recipient
                                </label>
                                <div id="single-email-wrap" style="margin-left: 20px; margin-bottom: 12px;">
                                    <input type="email" id="single_recipient" class="regular-text" value="<?php echo esc_attr( $s['admin_email'] ); ?>" placeholder="name@example.com">
                                </div>

                                <label style="display: block; margin-bottom: 6px;">
                                    <input type="radio" name="audience" value="inquiries" onclick="document.getElementById('single-email-wrap').style.display='none'">
                                    Broadcast to All Saved Inquiries <strong>(<?php echo count( $client_emails ); ?> registered email addresses found)</strong>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="campaign_custom_note"><strong>Custom Note / Message</strong> (Optional)</label></th>
                        <td>
                            <textarea id="campaign_custom_note" rows="3" class="large-text" placeholder="Add any special announcement text or custom greeting for this batch..."></textarea>
                        </td>
                    </tr>
                </table>

                <div style="border-top: 1px solid #eee; padding-top: 18px; margin-top: 10px; display: flex; align-items: center; gap: 14px;">
                    <button type="submit" id="btn-send-campaign" class="button button-primary button-large" style="background: #E0A238; border-color: #C98D28; color: #14181F; font-weight: 700; padding: 6px 24px;">
                        🚀 Dispatch Campaign Now
                    </button>
                    <span id="campaign-spinner" class="spinner" style="float: none; margin: 0;"></span>
                </div>
            </form>

            <div id="campaign-output-box" style="display: none; margin-top: 20px; padding: 14px; border-radius: 6px; font-size: 13px;"></div>
        </div>
    </div>

    <script>
    function asalUpdateCampaignDefaults(val) {
        var sub = document.getElementById('campaign_subject');
        if (val === 'season_coming') {
            sub.value = '🥭 The New Fruit Harvest Season is Approaching! Book Your Processing Slots Early';
        } else if (val === 'special_offers') {
            sub.value = 'Exclusive Seasonal Offer: Save Up to 20% on Bulk Pulping & Commercial Packaging!';
        } else if (val === 'news_updates') {
            sub.value = 'The Asal Canning Bulletin: Modern Processing Tech, Farmer Workshops & Season News';
        } else if (val === 'thank_you') {
            sub.value = 'Greetings from Asal Canning Center – Fruit Processing & Canning Services';
        }
    }

    function asalSubmitCampaign(e) {
        e.preventDefault();
        var btn = document.getElementById('btn-send-campaign');
        var spinner = document.getElementById('campaign-spinner');
        var box = document.getElementById('campaign-output-box');

        var tpl = document.getElementById('campaign_template').value;
        var sub = document.getElementById('campaign_subject').value;
        var note = document.getElementById('campaign_custom_note').value;
        var aud = document.querySelector('input[name="audience"]:checked').value;
        var single = document.getElementById('single_recipient').value;

        if (aud === 'single' && !single) {
            alert('Please enter a recipient email address.');
            return;
        }

        if (aud === 'inquiries' && !confirm('Are you sure you want to broadcast this template to all <?php echo count( $client_emails ); ?> inquiries in the database?')) {
            return;
        }

        btn.disabled = true;
        spinner.classList.add('is-active');
        box.style.display = 'block';
        box.style.background = '#F8FAFC';
        box.style.color = '#334155';
        box.style.border = '1px solid #CBD5E1';
        box.innerHTML = 'Sending campaign emails... Please do not close this window.';

        var formData = new FormData();
        formData.append('action', 'asal_send_campaign_broadcast');
        formData.append('nonce', '<?php echo wp_create_nonce( 'asal_campaign_nonce' ); ?>');
        formData.append('template', tpl);
        formData.append('subject', sub);
        formData.append('custom_note', note);
        formData.append('audience', aud);
        formData.append('single_recipient', single);

        fetch(ajaxurl, { method: 'POST', body: formData })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            btn.disabled = false;
            spinner.classList.remove('is-active');
            if (data.success) {
                box.style.background = '#DCFCE7';
                box.style.color = '#14532D';
                box.style.border = '1px solid #86EFAC';
                box.innerHTML = '✅ ' + data.data.message;
            } else {
                box.style.background = '#FEE2E2';
                box.style.color = '#7F1D1D';
                box.style.border = '1px solid #FCA5A5';
                box.innerHTML = '❌ ' + data.data.message;
            }
        })
        .catch(function(err) {
            btn.disabled = false;
            spinner.classList.remove('is-active');
            box.style.background = '#FEE2E2';
            box.style.color = '#7F1D1D';
            box.innerHTML = 'Error: ' + err;
        });
    }
    </script>
    <?php
}

/**
 * 8. Render Template in Iframe (Preview Endpoint)
 */
add_action( 'template_redirect', 'asal_handle_template_iframe_preview' );
function asal_handle_template_iframe_preview() {
    if ( isset( $_GET['asal_email_preview'] ) && current_user_can( 'manage_options' ) ) {
        if ( ! isset( $_GET['_nonce'] ) || ! wp_verify_nonce( $_GET['_nonce'], 'asal_preview_nonce' ) ) {
            wp_die( 'Security check failed. Please refresh the preview page.' );
        }

        $tpl = sanitize_key( $_GET['tpl'] ?? 'thank_you' );

        $sample_data = [
            'name'          => 'Rajeshbhai Patel',
            'email'         => 'rajesh@example.com',
            'phone'         => '+91 98250 12345',
            'subject'       => 'Mango Pulping & 500kg Retort Batch Canning',
            'message'       => 'We have an upcoming harvest of Kesar mangoes from our Talala Gir farm. We need 500 kg converted into hermetic canned pulp with zero chemical preservatives.',
            'date'          => current_time( 'F j, Y - g:i A' ),
            'promo_code'    => 'ASALHARVEST20',
            'discount'      => '20% OFF',
            'offer_title'   => 'Bulk Pulping & Commercial Packaging Special',
            'custom_notice' => 'Summer mango and upcoming fruit season bookings are officially open.',
            'headline'      => 'Modern Processing Upgrades & Farmer Training Workshops',
        ];

        switch ( $tpl ) {
            case 'season_coming':
                echo asal_get_email_template_season_coming( $sample_data );
                break;
            case 'special_offers':
                echo asal_get_email_template_offers( $sample_data );
                break;
            case 'news_updates':
                echo asal_get_email_template_news( $sample_data );
                break;
            case 'thank_you':
            default:
                echo asal_get_email_template_thank_you( $sample_data );
                break;
        }
        exit;
    }
}

/**
 * 9. AJAX: Test SMTP Connection & Send Diagnostic Email
 */
add_action( 'wp_ajax_asal_smtp_test_email', 'asal_ajax_smtp_test_email' );
function asal_ajax_smtp_test_email() {
    check_ajax_referer( 'asal_smtp_test_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( [ 'message' => 'Unauthorized' ] );
    }

    $to_email = sanitize_email( $_POST['to_email'] ?? '' );
    $template = sanitize_key( $_POST['template'] ?? 'test_ping' );

    if ( ! is_email( $to_email ) ) {
        wp_send_json_error( [ 'message' => 'Please provide a valid recipient email address.' ] );
    }

    $s = asal_get_smtp_settings();

    // Prepare email content based on selected template
    $subject = 'Asal Canning Center – SMTP Test & Diagnostic';
    $headers = [ 'Content-Type: text/html; charset=UTF-8' ];

    $data = [
        'name'          => 'Administrator',
        'email'         => $to_email,
        'phone'         => '+91 98250 62293',
        'subject'       => 'Test Connection & Email Template Diagnostic',
        'message'       => 'This is a test submission dispatched from the Asal Canning Center Email & SMTP suite.',
        'date'          => current_time( 'F j, Y - g:i A' ),
        'promo_code'    => 'ASALHARVEST20',
        'discount'      => '20% OFF',
        'offer_title'   => 'Special Seasonal Batch Processing',
        'custom_notice' => 'Seasonal harvest processing slots are open for reservations.',
        'headline'      => 'Modern Processing Upgrades & Farmer Training Workshops',
    ];

    if ( 'thank_you' === $template ) {
        $subject = 'Thank You for Reaching Out to Asal Canning Center (Test Preview)';
        $body    = asal_get_email_template_thank_you( $data );
    } elseif ( 'season_coming' === $template ) {
        $subject = '🥭 The New Fruit Harvest Season is Approaching! (Test Preview)';
        $body    = asal_get_email_template_season_coming( $data );
    } elseif ( 'special_offers' === $template ) {
        $subject = 'Exclusive Offer: Save Up to 20% on Bulk Pulping (Test Preview)';
        $body    = asal_get_email_template_offers( $data );
    } elseif ( 'news_updates' === $template ) {
        $subject = 'The Asal Canning Bulletin: Modern Tech & Workshops (Test Preview)';
        $body    = asal_get_email_template_news( $data );
    } else {
        // Standard diagnostic ping
        $body = asal_get_email_header( 'SMTP connection test from Asal Canning Center', 'SMTP Diagnostics' ) . '
        <tr>
          <td style="padding: 30px;">
            <h2 style="color: #14181F; margin: 0 0 10px 0;">🎉 SMTP Connection Successful!</h2>
            <p style="font-size: 14px; line-height: 1.5; color: #374151;">
              Congratulations! Your WordPress installation successfully connected to your SMTP server (<strong>' . esc_html( $s['host'] ) . '</strong>) and delivered this test email to <strong>' . esc_html( $to_email ) . '</strong>.
            </p>
            <p style="font-size: 13px; color: #6B7280;">
              All future website inquiries and customer autoresponders will now be routed reliably with rich HTML templates.
            </p>
          </td>
        </tr>' . asal_get_email_footer();
    }

    // Capture PHPMailer debug log if needed
    add_action( 'phpmailer_init', function( $phpmailer ) {
        $phpmailer->SMTPDebug = 2;
        $phpmailer->Debugoutput = function( $str, $level ) {
            $GLOBALS['asal_smtp_debug_log'] = ( $GLOBALS['asal_smtp_debug_log'] ?? '' ) . $str . "\n";
        };
    }, 1000 );

    $GLOBALS['asal_smtp_debug_log'] = '';
    $sent = wp_mail( $to_email, $subject, $body, $headers );

    if ( $sent ) {
        wp_send_json_success( [
            'message' => "Test email dispatched successfully to {$to_email}!",
            'log'     => substr( $GLOBALS['asal_smtp_debug_log'] ?? '', 0, 1000 ),
        ] );
    } else {
        global $phpmailer;
        $error_msg = 'wp_mail() returned false.';
        if ( isset( $phpmailer ) && ! empty( $phpmailer->ErrorInfo ) ) {
            $error_msg = $phpmailer->ErrorInfo;
        }

        wp_send_json_error( [
            'message' => "Email delivery failed: {$error_msg}",
            'log'     => $GLOBALS['asal_smtp_debug_log'] ?? '',
        ] );
    }
}

/**
 * 10. AJAX: Send Campaign Broadcast
 */
add_action( 'wp_ajax_asal_send_campaign_broadcast', 'asal_ajax_send_campaign_broadcast' );
function asal_ajax_send_campaign_broadcast() {
    check_ajax_referer( 'asal_campaign_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( [ 'message' => 'Unauthorized' ] );
    }

    $template    = sanitize_key( $_POST['template'] ?? 'season_coming' );
    $subject     = sanitize_text_field( $_POST['subject'] ?? 'Announcement from Asal Canning Center' );
    $custom_note = sanitize_textarea_field( $_POST['custom_note'] ?? '' );
    $audience    = sanitize_key( $_POST['audience'] ?? 'single' );

    $recipients = [];

    if ( 'single' === $audience ) {
        $single = sanitize_email( $_POST['single_recipient'] ?? '' );
        if ( is_email( $single ) ) {
            $recipients[ $single ] = 'Valued Partner';
        }
    } else {
        // All inquiries
        $inquiries = get_posts( [
            'post_type'      => 'asal_inquiry',
            'posts_per_page' => 200,
            'post_status'    => 'publish',
        ] );

        foreach ( $inquiries as $inq ) {
            $em = get_post_meta( $inq->ID, '_inquiry_email', true );
            if ( ! empty( $em ) && is_email( $em ) ) {
                $nm = get_post_meta( $inq->ID, '_inquiry_name', true ) ?: 'Client';
                $recipients[ $em ] = $nm;
            }
        }
    }

    if ( empty( $recipients ) ) {
        wp_send_json_error( [ 'message' => 'No valid recipient email addresses found.' ] );
    }

    $headers = [ 'Content-Type: text/html; charset=UTF-8' ];
    $sent_count = 0;
    $failed_count = 0;

    foreach ( $recipients as $email => $name ) {
        $data = [
            'name'          => $name,
            'email'         => $email,
            'custom_notice' => $custom_note,
            'promo_code'    => 'ASALHARVEST20',
            'discount'      => '20% OFF',
        ];

        switch ( $template ) {
            case 'season_coming':
                $html = asal_get_email_template_season_coming( $data );
                break;
            case 'special_offers':
                $html = asal_get_email_template_offers( $data );
                break;
            case 'news_updates':
                $html = asal_get_email_template_news( $data );
                break;
            case 'thank_you':
            default:
                $html = asal_get_email_template_thank_you( $data );
                break;
        }

        if ( wp_mail( $email, $subject, $html, $headers ) ) {
            $sent_count++;
        } else {
            $failed_count++;
        }
    }

    wp_send_json_success( [
        'message' => "Campaign sent to {$sent_count} recipient(s)." . ( $failed_count > 0 ? " ({$failed_count} deliveries failed)" : '' ),
    ] );
}

/**
 * 11. Inquiries Post Type Enhancements (Meta Box & Columns)
 */

// Add "Email Status" column to Inquiries admin list
add_filter( 'manage_asal_inquiry_posts_columns', 'asal_add_inquiry_email_column', 15 );
function asal_add_inquiry_email_column( $columns ) {
    $columns['email_status'] = __( 'Email Status', 'asalcanningcenter' );
    return $columns;
}

add_action( 'manage_asal_inquiry_posts_custom_column', 'asal_inquiry_email_column_content', 15, 2 );
function asal_inquiry_email_column_content( $column, $post_id ) {
    if ( 'email_status' === $column ) {
        $auto_sent  = get_post_meta( $post_id, '_inquiry_autoresponder_sent', true );
        $admin_sent = get_post_meta( $post_id, '_inquiry_admin_notified', true );

        echo '<div style="font-size: 11px; line-height: 1.4;">';
        if ( $auto_sent ) {
            echo '<span style="color: #16A34A; font-weight: 600;">✅ Client Auto-replied</span><br>';
        } else {
            echo '<span style="color: #9CA3AF;">○ Client Notified: Pending</span><br>';
        }

        if ( $admin_sent ) {
            echo '<span style="color: #2563EB; font-weight: 600;">🔔 Admin Notified</span>';
        } else {
            echo '<span style="color: #9CA3AF;">○ Admin Notified: Pending</span>';
        }
        echo '</div>';
    }
}

// Meta Box: Send Direct Follow-up or Offers to Inquirer
add_action( 'add_meta_boxes', 'asal_add_inquiry_actions_meta_box' );
function asal_add_inquiry_actions_meta_box() {
    add_meta_box(
        'asal_inquiry_mail_actions',
        __( 'Send Follow-up / Offer Email', 'asalcanningcenter' ),
        'asal_render_inquiry_mail_actions_box',
        'asal_inquiry',
        'side',
        'high'
    );
}

function asal_render_inquiry_mail_actions_box( $post ) {
    $email = get_post_meta( $post->ID, '_inquiry_email', true );
    $name  = get_post_meta( $post->ID, '_inquiry_name', true );
    ?>
    <div style="font-size: 13px;">
        <?php if ( ! empty( $email ) ) : ?>
            <p style="margin-top: 0;">
                Recipient: <strong><?php echo esc_html( $name ?: 'Client' ); ?></strong><br>
                <code><?php echo esc_html( $email ); ?></code>
            </p>
            <p>
                <label for="direct_template_select" style="font-weight: 600; display: block; margin-bottom: 4px;">Choose Template:</label>
                <select id="direct_template_select" style="width: 100%;">
                    <option value="season_coming">Harvest Season Pre-Booking</option>
                    <option value="special_offers">Special Offers &amp; 20% Off</option>
                    <option value="news_updates">Asal Canning Bulletin / News</option>
                    <option value="thank_you">Thank You &amp; Inquiry Receipt</option>
                </select>
            </p>
            <button type="button" class="button button-primary button-large" style="width: 100%; text-align: center; justify-content: center; background: #E0A238; border-color: #C98D28; color: #14181F; font-weight: 700;" onclick="asalSendDirectInquiryMail(<?php echo $post->ID; ?>, '<?php echo esc_js( $email ); ?>')">
                ✉️ Send Template Now
            </button>
            <div id="direct-mail-result" style="margin-top: 8px; font-size: 12px; display: none;"></div>
        <?php else : ?>
            <p style="color: #64748B; margin: 0;">No email address was provided for this inquiry.</p>
        <?php endif; ?>
    </div>

    <script>
    function asalSendDirectInquiryMail(postId, email) {
        var tpl = document.getElementById('direct_template_select').value;
        var res = document.getElementById('direct-mail-result');

        res.style.display = 'block';
        res.style.color = '#334155';
        res.innerHTML = 'Sending email...';

        var formData = new FormData();
        formData.append('action', 'asal_smtp_test_email');
        formData.append('nonce', '<?php echo wp_create_nonce( 'asal_smtp_test_nonce' ); ?>');
        formData.append('to_email', email);
        formData.append('template', tpl);

        fetch(ajaxurl, { method: 'POST', body: formData })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (d.success) {
                res.style.color = '#16A34A';
                res.innerHTML = '✅ Email sent successfully!';
            } else {
                res.style.color = '#DC2626';
                res.innerHTML = '❌ Failed: ' + d.data.message;
            }
        });
    }
    </script>
    <?php
}
