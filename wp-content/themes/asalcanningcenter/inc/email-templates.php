<?php
/**
 * Asal Canning Center - Responsive HTML Email Templates
 *
 * Professional, cross-client responsive HTML templates:
 * 1. Thank You for Reaching Out (Inquiry Confirmation + Upcoming Season & Image Offers)
 * 2. Upcoming Season is Coming (Seasonal Fruit Harvest Pre-Booking)
 * 3. Special Promotional Offers & Bulk Processing Discounts (Image Offers)
 * 4. Asal Canning Bulletin (News, Technology & Farmer Workshops)
 * 5. Admin New Inquiry Alert
 *
 * @package asalcanningcenter
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Helper: Resolve image URL for email templates
 * Handles localhost vs production public image URLs.
 */
function asal_get_email_image_url( $filename ) {
    $smtp_settings = get_option( 'asal_smtp_settings', [] );
    $custom_base   = ! empty( $smtp_settings['public_image_base'] ) ? untrailingslashit( trim( $smtp_settings['public_image_base'] ) ) : '';

    if ( ! empty( $custom_base ) ) {
        return $custom_base . '/' . ltrim( $filename, '/' );
    }

    $upload_dir = wp_upload_dir();
    $default_path = $upload_dir['baseurl'] . '/2026/09/' . ltrim( $filename, '/' );

    return $default_path;
}

/**
 * Common Email Brand Styles
 */
function asal_get_email_styles() {
    return [
        'primary'      => '#14181F', // Midnight Charcoal
        'accent'       => '#E0A238', // Warm Amber Gold
        'accent_hover' => '#C98D28',
        'bg_canvas'    => '#F3F4F6', // Neutral background for client window
        'bg_card'      => '#FFFFFF', // Card white
        'bg_cream'     => '#FAF8F3', // Warm biscuit / cream
        'text'         => '#1F2937', // Deep slate body text
        'text_muted'   => '#6B7280', // Muted slate text
        'border'       => '#E5E7EB', // Subtle border
        'border_gold'  => '#E0A238', // Gold border accent
        'font_serif'   => "Georgia, 'Times New Roman', serif",
        'font_sans'    => "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif",
    ];
}

/**
 * Common Email Header
 */
function asal_get_email_header( $preheader = '', $header_badge = 'Established 2000 • Pure Taste & Traditional Preservation' ) {
    $s        = asal_get_email_styles();
    $site_url = esc_url( home_url( '/' ) );
    $site_name = esc_html( get_bloginfo( 'name' ) ?: 'ASAL CANNING CENTER' );

    $html = '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>' . $site_name . '</title>
<style type="text/css">
  body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
  table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
  img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
  table { border-collapse: collapse !important; }
  body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: ' . $s['bg_canvas'] . '; }
  @media screen and (max-width: 620px) {
    .email-container { width: 100% !important; }
    .fluid-col { display: block !important; width: 100% !important; box-sizing: border-box !important; }
    .mobile-padding { padding-left: 18px !important; padding-right: 18px !important; }
    .mobile-hide { display: none !important; }
  }
</style>
</head>
<body style="margin: 0; padding: 0; background-color: ' . $s['bg_canvas'] . '; font-family: ' . $s['font_sans'] . ';">
<!-- Preheader / Preview Text -->
<div style="display: none; font-size: 1px; color: ' . $s['bg_canvas'] . '; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
  ' . esc_html( $preheader ) . ' &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
</div>

<!-- Main Wrapper -->
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: ' . $s['bg_canvas'] . ';">
  <tr>
    <td align="center" style="padding: 24px 12px 36px 12px;">
      <!-- Email Container (600px Max) -->
      <table border="0" cellpadding="0" cellspacing="0" width="600" class="email-container" style="max-width: 600px; width: 100%; background-color: ' . $s['bg_card'] . '; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid ' . $s['border'] . ';">
        
        <!-- Top Amber Brand Bar -->
        <tr>
          <td height="4" style="background: linear-gradient(90deg, #E0A238, #C98D28, #B85C38); font-size: 0; line-height: 0;">&nbsp;</td>
        </tr>

        <!-- Branded Header -->
        <tr>
          <td align="center" style="background-color: ' . $s['primary'] . '; padding: 28px 24px 24px 24px; text-align: center;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td align="center">
                  <!-- Gold Seal / Badge -->
                  <div style="display: inline-block; background-color: rgba(224, 162, 56, 0.15); border: 1px solid ' . $s['accent'] . '; border-radius: 30px; padding: 4px 14px; margin-bottom: 10px;">
                    <span style="font-family: ' . $s['font_sans'] . '; font-size: 11px; font-weight: 700; color: ' . $s['accent'] . '; text-transform: uppercase; letter-spacing: 1.5px;">
                      ' . esc_html( $header_badge ) . '
                    </span>
                  </div>
                  <!-- Main Brand Title -->
                  <h1 style="margin: 0; font-family: ' . $s['font_serif'] . '; font-size: 26px; font-weight: 700; color: #FFFFFF; letter-spacing: 0.5px; line-height: 1.2;">
                    <a href="' . $site_url . '" style="color: #FFFFFF; text-decoration: none;">' . $site_name . '</a>
                  </h1>
                  <!-- Subtitle -->
                  <p style="margin: 4px 0 0 0; font-family: ' . $s['font_sans'] . '; font-size: 12px; color: #9CA3AF; letter-spacing: 1px; text-transform: uppercase;">
                    Cottage Industry &amp; Food Processing • Pure Pulping • Commercial Canning
                  </p>
                </td>
              </tr>
            </table>
          </td>
        </tr>';

    return $html;
}

/**
 * Common Email Footer
 */
function asal_get_email_footer( $footnote = '' ) {
    $s          = asal_get_email_styles();
    $phone      = get_field( 'footer_phone', 'option' ) ?: '+91 98250 62293';
    $email      = get_field( 'footer_email', 'option' ) ?: 'asalcanning@gmail.com';
    $address    = get_field( 'footer_address', 'option' ) ?: 'Paldi, Ahmedabad, Gujarat, India';
    $site_url   = esc_url( home_url( '/' ) );
    $clean_phone = preg_replace( '/[^0-9+]/', '', $phone );
    $wa_phone    = preg_replace( '/[^0-9]/', '', $phone );

    $html = '
        <!-- Contact & Quick Connect Bar -->
        <tr>
          <td style="background-color: ' . $s['bg_cream'] . '; padding: 22px 28px; border-top: 1px solid ' . $s['border'] . '; border-bottom: 1px solid ' . $s['border'] . ';">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td class="fluid-col" style="padding-bottom: 10px; vertical-align: top;">
                  <strong style="font-family: ' . $s['font_sans'] . '; font-size: 13px; color: ' . $s['primary'] . '; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">Need Urgent Assistance or Custom Batch Quote?</strong>
                  <p style="margin: 0; font-size: 13px; color: ' . $s['text_muted'] . '; line-height: 1.5;">
                    Call us directly at <a href="tel:' . esc_attr( $clean_phone ) . '" style="color: ' . $s['accent_hover'] . '; font-weight: 700; text-decoration: none;">' . esc_html( $phone ) . '</a> or write to <a href="mailto:' . esc_attr( $email ) . '" style="color: ' . $s['accent_hover'] . '; text-decoration: none;">' . esc_html( $email ) . '</a>.
                  </p>
                </td>
              </tr>
              <tr>
                <td style="padding-top: 10px;">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="border-radius: 6px; background-color: #25D366; text-align: center; padding: 7px 16px; margin-right: 8px;">
                        <a href="https://wa.me/' . esc_attr( $wa_phone ) . '?text=' . rawurlencode( 'Hello Asal Canning Center, I would like to inquire about your fruit processing and canning services.' ) . '" style="color: #FFFFFF; font-family: ' . $s['font_sans'] . '; font-size: 12px; font-weight: 700; text-decoration: none; display: inline-block;">
                          💬 Chat on WhatsApp
                        </a>
                      </td>
                      <td style="width: 8px;"></td>
                      <td style="border-radius: 6px; background-color: ' . $s['primary'] . '; text-align: center; padding: 7px 16px;">
                        <a href="' . $site_url . '" style="color: #FFFFFF; font-family: ' . $s['font_sans'] . '; font-size: 12px; font-weight: 700; text-decoration: none; display: inline-block;">
                          🌐 Visit Website
                        </a>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Branded Footer -->
        <tr>
          <td style="background-color: ' . $s['primary'] . '; padding: 26px 28px; text-align: center; color: #9CA3AF;">
            <p style="margin: 0 0 6px 0; font-family: ' . $s['font_sans'] . '; font-size: 12px; color: #D1D5DB;">
              <strong>ASAL CANNING CENTER</strong> • ' . esc_html( $address ) . '
            </p>
            <p style="margin: 0 0 10px 0; font-size: 11px; color: #9CA3AF; line-height: 1.4;">
              FSSAI Certified Commercial Canning &amp; Fruit Preservation Center • Est. 2000
            </p>';

    if ( ! empty( $footnote ) ) {
        $html .= '<p style="margin: 8px 0 0 0; font-size: 10px; color: #6B7280; line-height: 1.4; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 8px;">' . esc_html( $footnote ) . '</p>';
    }

    $html .= '
            <p style="margin: 12px 0 0 0; font-size: 10px; color: #6B7280;">
              &copy; ' . gmdate( 'Y' ) . ' Asal Canning Center. All rights reserved.
            </p>
          </td>
        </tr>

      </table>
      <!-- End Email Container -->
    </td>
  </tr>
</table>
</body>
</html>';

    return $html;
}

/**
 * =========================================================================
 * TEMPLATE 1: "Thank You for Reaching Out" (Inquiry Response + Image Offers)
 * =========================================================================
 */
function asal_get_email_template_thank_you( $data = [] ) {
    $s = asal_get_email_styles();

    $name    = ! empty( $data['name'] ) ? sanitize_text_field( $data['name'] ) : 'Valued Client';
    $subject = ! empty( $data['subject'] ) ? sanitize_text_field( $data['subject'] ) : 'Fruit Processing & Canning';
    $phone   = ! empty( $data['phone'] ) ? sanitize_text_field( $data['phone'] ) : '';
    $message = ! empty( $data['message'] ) ? nl2br( esc_html( $data['message'] ) ) : 'General inquiry regarding batch processing and canning services.';
    $date    = ! empty( $data['date'] ) ? sanitize_text_field( $data['date'] ) : current_time( 'F j, Y - g:i A' );

    $img_mango   = esc_url( asal_get_email_image_url( 'mango-pulp.jpg' ) );
    $img_chyawan = esc_url( asal_get_email_image_url( 'amla-chyawanprash-500g.jpg' ) );
    $img_machine = esc_url( asal_get_email_image_url( 'pulp-journey-machine-extraction.jpg' ) );

    $preheader = "Thank you {$name}! We received your inquiry regarding {$subject} at Asal Canning Center.";
    $header    = asal_get_email_header( $preheader, 'Thank You for Reaching Out' );

    $body = '
        <!-- Greeting & Thank You Section -->
        <tr>
          <td class="mobile-padding" style="padding: 32px 32px 20px 32px;">
            <span style="font-family: ' . $s['font_sans'] . '; font-size: 12px; font-weight: 700; color: ' . $s['accent_hover'] . '; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 6px;">
              Inquiry Confirmation
            </span>
            <h2 style="margin: 0 0 12px 0; font-family: ' . $s['font_serif'] . '; font-size: 24px; font-weight: 700; color: ' . $s['primary'] . '; line-height: 1.25;">
              Dear ' . esc_html( $name ) . ', Thank You for Reaching Out!
            </h2>
            <p style="margin: 0 0 16px 0; font-size: 14px; line-height: 1.6; color: ' . $s['text'] . ';">
              We have successfully received your inquiry at <strong>Asal Canning Center</strong>. Whether you are an orchard farmer looking to pulp seasonal fruit, a food brand seeking private-label canning, or a customer ordering pure fruit preserves, our processing team is thrilled to assist you.
            </p>

            <!-- Inquiry Summary Box -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: ' . $s['bg_cream'] . '; border-left: 4px solid ' . $s['accent'] . '; border-radius: 0 8px 8px 0; margin-bottom: 22px;">
              <tr>
                <td style="padding: 16px 18px;">
                  <div style="font-family: ' . $s['font_sans'] . '; font-size: 11px; font-weight: 700; color: ' . $s['primary'] . '; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">
                    📋 Your Submitted Inquiry Details:
                  </div>
                  <table border="0" cellpadding="2" cellspacing="0" width="100%" style="font-size: 13px; color: ' . $s['text'] . ';">
                    <tr>
                      <td width="90" style="color: ' . $s['text_muted'] . '; font-weight: 600;">Subject:</td>
                      <td><strong>' . esc_html( $subject ) . '</strong></td>
                    </tr>
                    ' . ( $phone ? '<tr>
                      <td style="color: ' . $s['text_muted'] . '; font-weight: 600;">Phone:</td>
                      <td>' . esc_html( $phone ) . '</td>
                    </tr>' : '' ) . '
                    <tr>
                      <td style="color: ' . $s['text_muted'] . '; font-weight: 600;">Received:</td>
                      <td>' . esc_html( $date ) . '</td>
                    </tr>
                    <tr>
                      <td style="color: ' . $s['text_muted'] . '; font-weight: 600; vertical-align: top; padding-top: 4px;">Message:</td>
                      <td style="padding-top: 4px; font-style: italic; color: #4B5563;">&ldquo;' . $message . '&rdquo;</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            <p style="margin: 0 0 8px 0; font-size: 13px; line-height: 1.5; color: ' . $s['text_muted'] . ';">
              ⏱ <strong>What happens next?</strong> One of our technical processing supervisors will review your batch specifications and contact you within <strong>24 business hours</strong> with pricing and timeline estimates.
            </p>
          </td>
        </tr>

        <!-- Seasonal Highlights & Image Offers Banner -->
        <tr>
          <td class="mobile-padding" style="padding: 0 32px 28px 32px;">
            <div style="background: linear-gradient(135deg, #FAF8F3 0%, #FFF8E7 100%); border: 1px solid #E8D9B5; border-radius: 10px; padding: 20px;">
              
              <div style="text-align: center; margin-bottom: 16px;">
                <span style="background-color: ' . $s['accent'] . '; color: #FFFFFF; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; padding: 3px 10px; border-radius: 20px; display: inline-block;">
                  🥭 SEASON ANNOUNCEMENT &amp; SPECIAL OFFERS
                </span>
                <h3 style="margin: 8px 0 4px 0; font-family: ' . $s['font_serif'] . '; font-size: 20px; color: ' . $s['primary'] . ';">
                  The Fruit Harvest Season is Approaching!
                </h3>
                <p style="margin: 0; font-size: 12px; color: ' . $s['text_muted'] . ';">
                  Reserve your batch pulping and canning slots early to avoid peak season rush.
                </p>
              </div>

              <!-- Product Offer 1: Mango Pulp -->
              <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #FFFFFF; border-radius: 8px; border: 1px solid ' . $s['border'] . '; margin-bottom: 12px; overflow: hidden;">
                <tr>
                  <td width="100" style="vertical-align: middle; background-color: #FAF8F3; text-align: center; padding: 8px;">
                    <img src="' . $img_mango . '" alt="Pure Mango Pulp" width="85" style="border-radius: 6px; display: block; max-width: 85px; height: auto; margin: 0 auto;">
                  </td>
                  <td style="padding: 12px 14px; vertical-align: middle;">
                    <div style="font-size: 10px; font-weight: 700; color: ' . $s['accent_hover'] . '; text-transform: uppercase; letter-spacing: 0.5px;">Seasonal Booking</div>
                    <h4 style="margin: 2px 0 4px 0; font-size: 14px; color: ' . $s['primary'] . ';">100% Pure Kesar &amp; Alphonso Mango Pulp</h4>
                    <p style="margin: 0; font-size: 12px; color: ' . $s['text_muted'] . '; line-height: 1.4;">
                      Hermetically sealed retort cans &amp; pouches. Zero artificial colors or chemical preservatives.
                    </p>
                  </td>
                </tr>
              </table>

              <!-- Product Offer 2: Amla Chyawanprash -->
              <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #FFFFFF; border-radius: 8px; border: 1px solid ' . $s['border'] . '; margin-bottom: 12px; overflow: hidden;">
                <tr>
                  <td width="100" style="vertical-align: middle; background-color: #FAF8F3; text-align: center; padding: 8px;">
                    <img src="' . $img_chyawan . '" alt="Artisanal Amla Chyawanprash" width="85" style="border-radius: 6px; display: block; max-width: 85px; height: auto; margin: 0 auto;">
                  </td>
                  <td style="padding: 12px 14px; vertical-align: middle;">
                    <div style="font-size: 10px; font-weight: 700; color: #16A34A; text-transform: uppercase; letter-spacing: 0.5px;">Special Offer</div>
                    <h4 style="margin: 2px 0 4px 0; font-size: 14px; color: ' . $s['primary'] . ';">Artisanal Amla Chyawanprash (500g Jar)</h4>
                    <p style="margin: 0; font-size: 12px; color: ' . $s['text_muted'] . '; line-height: 1.4;">
                      Traditional slow-cooking in pure copper vats with 18 rare Ayurvedic herbs.
                    </p>
                  </td>
                </tr>
              </table>

              <!-- Service Offer 3: Contract Processing Line -->
              <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #FFFFFF; border-radius: 8px; border: 1px solid ' . $s['border'] . '; overflow: hidden;">
                <tr>
                  <td width="100" style="vertical-align: middle; background-color: #FAF8F3; text-align: center; padding: 8px;">
                    <img src="' . $img_machine . '" alt="Continuous Extraction Line" width="85" style="border-radius: 6px; display: block; max-width: 85px; height: auto; margin: 0 auto;">
                  </td>
                  <td style="padding: 12px 14px; vertical-align: middle;">
                    <div style="font-size: 10px; font-weight: 700; color: #2563EB; text-transform: uppercase; letter-spacing: 0.5px;">Commercial Contract</div>
                    <h4 style="margin: 2px 0 4px 0; font-size: 14px; color: ' . $s['primary'] . ';">Custom Batch Canning for Orchard Owners</h4>
                    <p style="margin: 0; font-size: 12px; color: ' . $s['text_muted'] . '; line-height: 1.4;">
                      Automated SS-304 pulping line. Up to 24 months shelf-life guarantee under FSSAI standards.
                    </p>
                  </td>
                </tr>
              </table>

            </div>
          </td>
        </tr>';

    $footer = asal_get_email_footer( 'You are receiving this confirmation because an inquiry was submitted with your email address on the Asal Canning Center website.' );

    return $header . $body . $footer;
}

/**
 * =========================================================================
 * TEMPLATE 2: "Upcoming Season is Coming!" (Seasonal Harvest Pre-Booking)
 * =========================================================================
 */
function asal_get_email_template_season_coming( $data = [] ) {
    $s = asal_get_email_styles();

    $name          = ! empty( $data['name'] ) ? sanitize_text_field( $data['name'] ) : 'Partner / Grower';
    $custom_notice = ! empty( $data['custom_notice'] ) ? esc_html( $data['custom_notice'] ) : 'The summer mango and upcoming seasonal harvest processing bookings are officially open.';

    $img_crates  = esc_url( asal_get_email_image_url( 'pulp-journey-sourcing-crates.jpg' ) );
    $img_machine = esc_url( asal_get_email_image_url( 'pulp-journey-machine-extraction.jpg' ) );

    $phone       = get_field( 'footer_phone', 'option' ) ?: '+91 98250 62293';
    $clean_phone = preg_replace( '/[^0-9+]/', '', $phone );

    $preheader = "The New Fruit Harvest Season is Approaching! Book Your Processing & Canning Slots Early with Asal Canning Center.";
    $header    = asal_get_email_header( $preheader, 'Seasonal Harvest Booking Announcement' );

    $body = '
        <!-- Hero Seasonal Banner -->
        <tr>
          <td style="background-color: ' . $s['primary'] . '; padding: 0; text-align: center;">
            <div style="background: linear-gradient(rgba(20, 24, 31, 0.75), rgba(20, 24, 31, 0.95)), url(\'' . $img_crates . '\') center/cover; padding: 40px 24px 34px 24px;">
              <span style="display: inline-block; background-color: ' . $s['accent'] . '; color: #FFFFFF; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; padding: 4px 14px; border-radius: 20px; margin-bottom: 12px;">
                🥭 HARVEST SEASON IS COMING
              </span>
              <h2 style="margin: 0 0 10px 0; font-family: ' . $s['font_serif'] . '; font-size: 28px; font-weight: 700; color: #FFFFFF; line-height: 1.25;">
                Reserve Your Fruit Pulping &amp; Canning Slots in Advance!
              </h2>
              <p style="margin: 0 auto; max-width: 480px; font-size: 14px; color: #E5E7EB; line-height: 1.5;">
                ' . esc_html( $custom_notice ) . ' Avoid post-harvest wastage and ensure your fruits are processed at peak freshness.
              </p>
            </div>
          </td>
        </tr>

        <!-- Main Content -->
        <tr>
          <td class="mobile-padding" style="padding: 30px 32px 18px 32px;">
            <p style="margin: 0 0 14px 0; font-size: 14px; line-height: 1.6; color: ' . $s['text'] . ';">
              Dear <strong>' . esc_html( $name ) . '</strong>,
            </p>
            <p style="margin: 0 0 18px 0; font-size: 14px; line-height: 1.6; color: ' . $s['text'] . ';">
              Every year, during peak fruit harvesting, canning facilities across Gujarat face overwhelming demand. Orchard owners, farmers, and sweet manufacturers who pre-book their processing slots enjoy <strong>guaranteed processing turnaround</strong>, priority cold staging, and discounted batch rates.
            </p>

            <!-- 3 Key Advantages -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 24px;">
              <tr>
                <td style="padding: 12px; background-color: ' . $s['bg_cream'] . '; border-radius: 8px; margin-bottom: 8px;">
                  <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                      <td width="36" style="vertical-align: top; font-size: 20px;">⚙️</td>
                      <td style="padding-left: 10px;">
                        <strong style="font-size: 13px; color: ' . $s['primary'] . ';">Continuous SS-304 De-stoning &amp; Extraction:</strong>
                        <div style="font-size: 12px; color: ' . $s['text_muted'] . '; margin-top: 2px;">
                          High-yield pulping that separates peels, stones, and coarse fiber cleanly while retaining maximum fruit pulp richness.
                        </div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr><td height="8"></td></tr>
              <tr>
                <td style="padding: 12px; background-color: ' . $s['bg_cream'] . '; border-radius: 8px; margin-bottom: 8px;">
                  <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                      <td width="36" style="vertical-align: top; font-size: 20px;">🛡️</td>
                      <td style="padding-left: 10px;">
                        <strong style="font-size: 13px; color: ' . $s['primary'] . ';">Retort Sterilization (Up to 24 Months Shelf Life):</strong>
                        <div style="font-size: 12px; color: ' . $s['text_muted'] . '; margin-top: 2px;">
                          Hermetically sealed cans &amp; multi-layer pouches processed under high-pressure thermal steam. Zero artificial preservatives needed.
                        </div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr><td height="8"></td></tr>
              <tr>
                <td style="padding: 12px; background-color: ' . $s['bg_cream'] . '; border-radius: 8px;">
                  <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                      <td width="36" style="vertical-align: top; font-size: 20px;">🏷️</td>
                      <td style="padding-left: 10px;">
                        <strong style="font-size: 13px; color: ' . $s['primary'] . ';">Custom Batch Sizes &amp; Private Label Packaging:</strong>
                        <div style="font-size: 12px; color: ' . $s['text_muted'] . '; margin-top: 2px;">
                          From 100 kg experimental farm trials up to multi-ton commercial manufacturing with your own branded labels.
                        </div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            <!-- Early Bird Offer Callout -->
            <div style="border: 2px dashed ' . $s['accent'] . '; background-color: #FFFDF9; border-radius: 8px; padding: 18px; text-align: center; margin-bottom: 24px;">
              <span style="font-size: 12px; font-weight: 700; color: ' . $s['accent_hover'] . '; text-transform: uppercase; letter-spacing: 1px;">
                🎉 EARLY BIRD SEASON SPECIAL OFFER
              </span>
              <h3 style="margin: 6px 0; font-family: ' . $s['font_serif'] . '; font-size: 20px; color: ' . $s['primary'] . ';">
                10% Off Batch Processing Charges + Free Brix &amp; Acidity Lab Testing
              </h3>
              <p style="margin: 0; font-size: 12px; color: ' . $s['text_muted'] . ';">
                Valid for processing slots reserved prior to the seasonal harvest rush.
              </p>
            </div>

            <!-- Big Call to Action Button -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td align="center" style="padding-bottom: 12px;">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="border-radius: 6px; background-color: ' . $s['accent'] . '; text-align: center; padding: 14px 28px;">
                        <a href="tel:' . esc_attr( $clean_phone ) . '" style="color: #FFFFFF; font-family: ' . $s['font_sans'] . '; font-size: 14px; font-weight: 700; text-decoration: none; display: inline-block;">
                          📞 Call Now to Book: ' . esc_html( $phone ) . '
                        </a>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

          </td>
        </tr>';

    $footer = asal_get_email_footer( 'You received this notification because you are a registered farmer, commercial partner, or client of Asal Canning Center.' );

    return $header . $body . $footer;
}

/**
 * =========================================================================
 * TEMPLATE 3: "Special Promotional Offers & Bulk Discounts" (Image Offers)
 * =========================================================================
 */
function asal_get_email_template_offers( $data = [] ) {
    $s = asal_get_email_styles();

    $name        = ! empty( $data['name'] ) ? sanitize_text_field( $data['name'] ) : 'Valued Customer';
    $promo_code  = ! empty( $data['promo_code'] ) ? sanitize_text_field( $data['promo_code'] ) : 'ASALHARVEST20';
    $discount    = ! empty( $data['discount'] ) ? sanitize_text_field( $data['discount'] ) : '20% OFF';
    $offer_title = ! empty( $data['offer_title'] ) ? sanitize_text_field( $data['offer_title'] ) : 'Bulk Pulping & Commercial Packaging Special';

    $img_mango   = esc_url( asal_get_email_image_url( 'mango-pulp.jpg' ) );
    $img_chyawan = esc_url( asal_get_email_image_url( 'amla-chyawanprash-500g.jpg' ) );
    $img_squash  = esc_url( asal_get_email_image_url( 'fruit-squash.jpg' ) );
    $img_pickle  = esc_url( asal_get_email_image_url( 'mango-pickle.jpg' ) );

    $phone       = get_field( 'footer_phone', 'option' ) ?: '+91 98250 62293';
    $wa_phone    = preg_replace( '/[^0-9]/', '', $phone );

    $preheader = "Exclusive Offer: {$discount} on {$offer_title} at Asal Canning Center! Use code {$promo_code}.";
    $header    = asal_get_email_header( $preheader, 'Exclusive Promotional Offers' );

    $body = '
        <!-- Hero Promo Header -->
        <tr>
          <td style="background: linear-gradient(135deg, #14181F 0%, #1F2937 100%); padding: 34px 28px; text-align: center; color: #FFFFFF;">
            <div style="display: inline-block; background-color: rgba(224, 162, 56, 0.2); border: 1px solid ' . $s['accent'] . '; border-radius: 20px; padding: 4px 14px; margin-bottom: 10px;">
              <span style="font-size: 11px; font-weight: 800; color: ' . $s['accent'] . '; text-transform: uppercase; letter-spacing: 1.5px;">
                🏷️ LIMITED TIME PROMOTIONAL OFFER
              </span>
            </div>
            <h2 style="margin: 0 0 8px 0; font-family: ' . $s['font_serif'] . '; font-size: 28px; font-weight: 700; color: #FFFFFF; line-height: 1.25;">
              ' . esc_html( $offer_title ) . '
            </h2>
            <p style="margin: 0 auto; max-width: 460px; font-size: 14px; color: #D1D5DB; line-height: 1.5;">
              Save up to <strong style="color: ' . $s['accent'] . '; font-size: 16px;">' . esc_html( $discount ) . '</strong> on your next contract canning run or commercial bulk order.
            </p>

            <!-- Voucher Code Box -->
            <div style="margin-top: 18px; display: inline-block; background-color: #FFFFFF; border: 2px dashed ' . $s['accent'] . '; border-radius: 8px; padding: 10px 22px;">
              <span style="display: block; font-size: 10px; color: ' . $s['text_muted'] . '; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Quote This Promo Code:</span>
              <strong style="font-size: 20px; letter-spacing: 2px; color: ' . $s['primary'] . '; font-family: monospace;">' . esc_html( $promo_code ) . '</strong>
            </div>
          </td>
        </tr>

        <!-- Image Offers Grid -->
        <tr>
          <td class="mobile-padding" style="padding: 28px 30px 10px 30px;">
            <h3 style="margin: 0 0 16px 0; font-family: ' . $s['font_serif'] . '; font-size: 20px; color: ' . $s['primary'] . '; text-align: center;">
              Featured Products &amp; Packaging Tiers
            </h3>

            <!-- Row 1: Two Column Product Cards -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <!-- Card 1: Mango Pulp -->
                <td class="fluid-col" width="48%" style="vertical-align: top; padding-bottom: 16px;">
                  <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #FAF8F3; border: 1px solid ' . $s['border'] . '; border-radius: 8px; overflow: hidden; text-align: center;">
                    <tr>
                      <td style="padding: 12px; background-color: #FFFFFF;">
                        <img src="' . $img_mango . '" alt="Pure Mango Pulp" width="180" style="border-radius: 6px; display: block; max-width: 100%; height: auto; margin: 0 auto;">
                      </td>
                    </tr>
                    <tr>
                      <td style="padding: 12px 14px;">
                        <div style="font-size: 10px; font-weight: 800; color: ' . $s['accent_hover'] . '; text-transform: uppercase;">Retort Cans &amp; Pouches</div>
                        <h4 style="margin: 4px 0 6px 0; font-size: 14px; color: ' . $s['primary'] . ';">Pure Kesar &amp; Alphonso Pulp</h4>
                        <p style="margin: 0; font-size: 11px; color: ' . $s['text_muted'] . '; line-height: 1.4;">
                          100% pure fruit flesh. Zero added sugar or water. 2-year shelf life.
                        </p>
                      </td>
                    </tr>
                  </table>
                </td>

                <td width="4%" class="mobile-hide"></td>

                <!-- Card 2: Amla Chyawanprash -->
                <td class="fluid-col" width="48%" style="vertical-align: top; padding-bottom: 16px;">
                  <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #FAF8F3; border: 1px solid ' . $s['border'] . '; border-radius: 8px; overflow: hidden; text-align: center;">
                    <tr>
                      <td style="padding: 12px; background-color: #FFFFFF;">
                        <img src="' . $img_chyawan . '" alt="Artisanal Amla Chyawanprash" width="180" style="border-radius: 6px; display: block; max-width: 100%; height: auto; margin: 0 auto;">
                      </td>
                    </tr>
                    <tr>
                      <td style="padding: 12px 14px;">
                        <div style="font-size: 10px; font-weight: 800; color: #16A34A; text-transform: uppercase;">Copper Vat Formulated</div>
                        <h4 style="margin: 4px 0 6px 0; font-size: 14px; color: ' . $s['primary'] . ';">Artisanal Amla Chyawanprash</h4>
                        <p style="margin: 0; font-size: 11px; color: ' . $s['text_muted'] . '; line-height: 1.4;">
                          Slow-simmered in copper vats with 18 authentic Ayurvedic herbs.
                        </p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <!-- Row 2: Two Column Product Cards -->
              <tr>
                <!-- Card 3: Fruit Squashes -->
                <td class="fluid-col" width="48%" style="vertical-align: top; padding-bottom: 16px;">
                  <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #FAF8F3; border: 1px solid ' . $s['border'] . '; border-radius: 8px; overflow: hidden; text-align: center;">
                    <tr>
                      <td style="padding: 12px; background-color: #FFFFFF;">
                        <img src="' . $img_squash . '" alt="Fruit Squash &amp; Drinks" width="180" style="border-radius: 6px; display: block; max-width: 100%; height: auto; margin: 0 auto;">
                      </td>
                    </tr>
                    <tr>
                      <td style="padding: 12px 14px;">
                        <div style="font-size: 10px; font-weight: 800; color: #B85C38; text-transform: uppercase;">Ready to Serve</div>
                        <h4 style="margin: 4px 0 6px 0; font-size: 14px; color: ' . $s['primary'] . ';">Herbal Squashes &amp; Drinks</h4>
                        <p style="margin: 0; font-size: 11px; color: ' . $s['text_muted'] . '; line-height: 1.4;">
                          Refreshing formulations for institutional catering and retail shelves.
                        </p>
                      </td>
                    </tr>
                  </table>
                </td>

                <td width="4%" class="mobile-hide"></td>

                <!-- Card 4: Mango Pickles & Jams -->
                <td class="fluid-col" width="48%" style="vertical-align: top; padding-bottom: 16px;">
                  <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #FAF8F3; border: 1px solid ' . $s['border'] . '; border-radius: 8px; overflow: hidden; text-align: center;">
                    <tr>
                      <td style="padding: 12px; background-color: #FFFFFF;">
                        <img src="' . $img_pickle . '" alt="Artisanal Pickles &amp; Murabba" width="180" style="border-radius: 6px; display: block; max-width: 100%; height: auto; margin: 0 auto;">
                      </td>
                    </tr>
                    <tr>
                      <td style="padding: 12px 14px;">
                        <div style="font-size: 10px; font-weight: 800; color: #9333EA; text-transform: uppercase;">Heritage Recipes</div>
                        <h4 style="margin: 4px 0 6px 0; font-size: 14px; color: ' . $s['primary'] . ';">Traditional Pickles &amp; Murabba</h4>
                        <p style="margin: 0; font-size: 11px; color: ' . $s['text_muted'] . '; line-height: 1.4;">
                          Spiced with stone-ground aromatics and aged in ceramic vats.
                        </p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            <!-- Claim Button -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 8px; margin-bottom: 14px;">
              <tr>
                <td align="center">
                  <a href="https://wa.me/' . esc_attr( $wa_phone ) . '?text=' . rawurlencode( 'Hello, I would like to claim the ' . $discount . ' offer using promo code ' . $promo_code . '.' ) . '" style="display: inline-block; background-color: ' . $s['accent'] . '; color: #FFFFFF; font-family: ' . $s['font_sans'] . '; font-size: 14px; font-weight: 700; text-decoration: none; padding: 13px 26px; border-radius: 6px;">
                    Claim Special Offer On WhatsApp 👉
                  </a>
                </td>
              </tr>
            </table>

          </td>
        </tr>';

    $footer = asal_get_email_footer( 'Terms: Promo code applies to qualifying commercial batch runs or bulk orders confirmed before offer expiry.' );

    return $header . $body . $footer;
}

/**
 * =========================================================================
 * TEMPLATE 4: "Asal Canning Bulletin" (News, Technology & Workshops)
 * =========================================================================
 */
function asal_get_email_template_news( $data = [] ) {
    $s = asal_get_email_styles();

    $issue_date = ! empty( $data['issue_date'] ) ? sanitize_text_field( $data['issue_date'] ) : current_time( 'F Y' );
    $headline   = ! empty( $data['headline'] ) ? sanitize_text_field( $data['headline'] ) : 'Modern Processing Upgrades & Farmer Training Workshops';

    $img_workshop = esc_url( asal_get_email_image_url( 'gallery-women-workshop.jpg' ) );
    $img_machine  = esc_url( asal_get_email_image_url( 'pulp-journey-machine-extraction.jpg' ) );

    $preheader = "Asal Canning Bulletin {$issue_date}: {$headline}";
    $header    = asal_get_email_header( $preheader, "The Asal Bulletin • {$issue_date}" );

    $body = '
        <!-- Newsletter Masthead -->
        <tr>
          <td class="mobile-padding" style="padding: 24px 30px 14px 30px; border-bottom: 2px solid ' . $s['border'] . ';">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td>
                  <span style="font-size: 11px; font-weight: 800; color: ' . $s['accent_hover'] . '; text-transform: uppercase; letter-spacing: 1px;">
                    NEWS &amp; GROWER INSIGHTS
                  </span>
                  <h2 style="margin: 4px 0 0 0; font-family: ' . $s['font_serif'] . '; font-size: 24px; font-weight: 700; color: ' . $s['primary'] . ';">
                    ' . esc_html( $headline ) . '
                  </h2>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Lead Article with Image -->
        <tr>
          <td class="mobile-padding" style="padding: 24px 30px 20px 30px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td>
                  <img src="' . $img_workshop . '" alt="Fruit Processing Workshop" width="540" style="border-radius: 8px; display: block; width: 100%; height: auto; margin-bottom: 14px;">
                  <span style="font-size: 11px; font-weight: 700; color: #16A34A; text-transform: uppercase; letter-spacing: 0.5px;">COMMUNITY &amp; WORKSHOPS</span>
                  <h3 style="margin: 4px 0 8px 0; font-family: ' . $s['font_serif'] . '; font-size: 20px; color: ' . $s['primary'] . ';">
                    Empowering Local Farmers &amp; Women Entrepreneurs in Modern Food Preservation
                  </h3>
                  <p style="margin: 0 0 12px 0; font-size: 13px; line-height: 1.6; color: ' . $s['text'] . ';">
                    Under the visionary leadership of founder <strong>Jalpa Patel</strong>, Asal Canning Center recently hosted an intensive 3-day practical training seminar on scientific fruit pulping, thermal vacuum canning, and hygienic standardization. Over 45 women entrepreneurs and orchard managers attended the sessions to learn how value addition can transform seasonal fruit surplus into sustainable revenue.
                  </p>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Article 2: Technology Spotlight -->
        <tr>
          <td class="mobile-padding" style="padding: 0 30px 24px 30px;">
            <div style="background-color: ' . $s['bg_cream'] . '; border-radius: 8px; padding: 18px; border: 1px solid ' . $s['border'] . ';">
              <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <td width="120" class="fluid-col" style="vertical-align: middle; padding-right: 14px; padding-bottom: 10px;">
                    <img src="' . $img_machine . '" alt="Continuous Pulper" width="120" style="border-radius: 6px; display: block; max-width: 100%; height: auto;">
                  </td>
                  <td class="fluid-col" style="vertical-align: middle;">
                    <span style="font-size: 10px; font-weight: 800; color: ' . $s['accent_hover'] . '; text-transform: uppercase;">TECH SPOTLIGHT</span>
                    <h4 style="margin: 2px 0 4px 0; font-size: 15px; color: ' . $s['primary'] . ';">
                      Retort Canning: How We Retain 95% Natural Nutrients Without Preservatives
                    </h4>
                    <p style="margin: 0; font-size: 12px; color: ' . $s['text_muted'] . '; line-height: 1.5;">
                      By employing precision multi-stage counter-pressure retort cycles, Asal Canning Center inactivates spoilage microorganisms while completely safeguarding the delicate aromatics and vitamins of pure fruit.
                    </p>
                  </td>
                </tr>
              </table>
            </div>
          </td>
        </tr>

        <!-- Harvest Calendar Table -->
        <tr>
          <td class="mobile-padding" style="padding: 0 30px 24px 30px;">
            <h4 style="margin: 0 0 10px 0; font-family: ' . $s['font_serif'] . '; font-size: 18px; color: ' . $s['primary'] . ';">
              📅 Western India Fruit Harvest Calendar
            </h4>
            <table border="0" cellpadding="8" cellspacing="0" width="100%" style="font-size: 12px; border-collapse: collapse; border: 1px solid ' . $s['border'] . ';">
              <tr style="background-color: ' . $s['primary'] . '; color: #FFFFFF;">
                <th align="left" style="padding: 8px 10px;">Produce</th>
                <th align="left" style="padding: 8px 10px;">Harvest Window</th>
                <th align="left" style="padding: 8px 10px;">Recommended Preservation</th>
              </tr>
              <tr style="background-color: #FFFFFF; border-bottom: 1px solid ' . $s['border'] . ';">
                <td style="font-weight: 700; color: ' . $s['primary'] . ';">🥭 Mango (Kesar/Alphonso)</td>
                <td>March &ndash; June</td>
                <td>Aseptic Pulp &amp; Hermetic Cans</td>
              </tr>
              <tr style="background-color: #FAF8F3; border-bottom: 1px solid ' . $s['border'] . ';">
                <td style="font-weight: 700; color: ' . $s['primary'] . ';">🫐 Jamun &amp; Falsa</td>
                <td>June &ndash; July</td>
                <td>Pure Pulper Extraction &amp; Squashes</td>
              </tr>
              <tr style="background-color: #FFFFFF; border-bottom: 1px solid ' . $s['border'] . ';">
                <td style="font-weight: 700; color: ' . $s['primary'] . ';">🍈 Guava &amp; Sitafal</td>
                <td>October &ndash; January</td>
                <td>De-seeded Puree &amp; Ready Pulp</td>
              </tr>
              <tr style="background-color: #FAF8F3;">
                <td style="font-weight: 700; color: ' . $s['primary'] . ';">🌿 Amla (Indian Gooseberry)</td>
                <td>December &ndash; February</td>
                <td>Chyawanprash, Murabba &amp; Juices</td>
              </tr>
            </table>
          </td>
        </tr>';

    $footer = asal_get_email_footer( 'You are receiving the Asal Canning Center quarterly news and processing bulletin.' );

    return $header . $body . $footer;
}

/**
 * =========================================================================
 * TEMPLATE 5: Admin Notification Email (Instant Alert on New Inquiry)
 * =========================================================================
 */
function asal_get_email_template_admin_notification( $data = [] ) {
    $s = asal_get_email_styles();

    $name    = ! empty( $data['name'] ) ? sanitize_text_field( $data['name'] ) : 'Unknown Inquirer';
    $email   = ! empty( $data['email'] ) ? sanitize_email( $data['email'] ) : 'N/A';
    $phone   = ! empty( $data['phone'] ) ? sanitize_text_field( $data['phone'] ) : 'N/A';
    $subject = ! empty( $data['subject'] ) ? sanitize_text_field( $data['subject'] ) : 'General Inquiry';
    $message = ! empty( $data['message'] ) ? nl2br( esc_html( $data['message'] ) ) : 'No message body provided.';
    $post_id = ! empty( $data['post_id'] ) ? absint( $data['post_id'] ) : 0;
    $date    = current_time( 'F j, Y - g:i A' );

    $admin_link  = $post_id ? admin_url( 'post.php?post=' . $post_id . '&action=edit' ) : admin_url( 'edit.php?post_type=asal_inquiry' );
    $clean_phone = preg_replace( '/[^0-9+]/', '', $phone );

    $preheader = "New Website Lead: {$name} inquired about {$subject} ({$phone})";
    $header    = asal_get_email_header( $preheader, 'Admin Lead Notification' );

    $body = '
        <tr>
          <td class="mobile-padding" style="padding: 28px 30px 20px 30px;">
            <div style="display: inline-block; background-color: #FEF3C7; border: 1px solid #F59E0B; border-radius: 20px; padding: 4px 12px; margin-bottom: 12px;">
              <span style="font-size: 11px; font-weight: 800; color: #B45309; text-transform: uppercase;">
                🔔 NEW WEBSITE INQUIRY RECEIVED
              </span>
            </div>
            <h2 style="margin: 0 0 14px 0; font-family: ' . $s['font_serif'] . '; font-size: 22px; color: ' . $s['primary'] . ';">
              Lead Alert: ' . esc_html( $name ) . '
            </h2>

            <table border="0" cellpadding="8" cellspacing="0" width="100%" style="font-size: 13px; background-color: ' . $s['bg_cream'] . '; border-radius: 8px; border: 1px solid ' . $s['border'] . '; margin-bottom: 20px;">
              <tr style="border-bottom: 1px solid ' . $s['border'] . ';">
                <td width="110" style="color: ' . $s['text_muted'] . '; font-weight: 700;">Client Name:</td>
                <td><strong style="color: ' . $s['primary'] . '; font-size: 14px;">' . esc_html( $name ) . '</strong></td>
              </tr>
              <tr style="border-bottom: 1px solid ' . $s['border'] . ';">
                <td style="color: ' . $s['text_muted'] . '; font-weight: 700;">Phone Number:</td>
                <td>
                  ' . ( $phone !== 'N/A' ? '<a href="tel:' . esc_attr( $clean_phone ) . '" style="color: ' . $s['accent_hover'] . '; font-weight: 700; text-decoration: none; font-size: 14px;">' . esc_html( $phone ) . '</a>' : 'N/A' ) . '
                </td>
              </tr>
              <tr style="border-bottom: 1px solid ' . $s['border'] . ';">
                <td style="color: ' . $s['text_muted'] . '; font-weight: 700;">Email Address:</td>
                <td>
                  ' . ( $email !== 'N/A' ? '<a href="mailto:' . esc_attr( $email ) . '" style="color: ' . $s['accent_hover'] . '; text-decoration: none;">' . esc_html( $email ) . '</a>' : 'N/A' ) . '
                </td>
              </tr>
              <tr style="border-bottom: 1px solid ' . $s['border'] . ';">
                <td style="color: ' . $s['text_muted'] . '; font-weight: 700;">Subject / Need:</td>
                <td><strong>' . esc_html( $subject ) . '</strong></td>
              </tr>
              <tr style="border-bottom: 1px solid ' . $s['border'] . ';">
                <td style="color: ' . $s['text_muted'] . '; font-weight: 700;">Submission Date:</td>
                <td>' . esc_html( $date ) . '</td>
              </tr>
              <tr>
                <td style="color: ' . $s['text_muted'] . '; font-weight: 700; vertical-align: top;">Client Message:</td>
                <td style="color: ' . $s['text'] . '; line-height: 1.5;">' . $message . '</td>
              </tr>
            </table>

            <!-- Quick Action Buttons -->
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                ' . ( $phone !== 'N/A' ? '
                <td style="border-radius: 6px; background-color: #16A34A; text-align: center; padding: 10px 18px;">
                  <a href="tel:' . esc_attr( $clean_phone ) . '" style="color: #FFFFFF; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-block;">
                    📞 Call Client
                  </a>
                </td>
                <td width="10"></td>' : '' ) . '
                ' . ( $email !== 'N/A' ? '
                <td style="border-radius: 6px; background-color: #2563EB; text-align: center; padding: 10px 18px;">
                  <a href="mailto:' . esc_attr( $email ) . '?subject=' . rawurlencode( 'Re: ' . $subject . ' - Asal Canning Center' ) . '" style="color: #FFFFFF; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-block;">
                    ✉️ Reply via Email
                  </a>
                </td>
                <td width="10"></td>' : '' ) . '
                <td style="border-radius: 6px; background-color: ' . $s['primary'] . '; text-align: center; padding: 10px 18px;">
                  <a href="' . esc_url( $admin_link ) . '" style="color: #FFFFFF; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-block;">
                    ⚙️ View in Admin
                  </a>
                </td>
              </tr>
            </table>

          </td>
        </tr>';

    $footer = asal_get_email_footer( 'This is an internal administrator notification sent from your Asal Canning Center WordPress system.' );

    return $header . $body . $footer;
}
