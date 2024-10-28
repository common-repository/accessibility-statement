<?php
/*
	Plugin Name: Accessibility Statement
	Description: Create page with a11y statement
	Author: Turn
	Version: 1.0.0
    Text Domain: a11yStatement
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class AccessibilityStatementPlugin {

    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'a11yStatement_include_local_acf' ), 10 );
        add_action( 'init', array( $this, 'a11yStatement_load_textdomain' ) );
        add_action( 'init', array( $this, 'a11yStatement_settings_page' ) );
        add_action( 'init', array( $this, 'a11yStatement_remove_editor' ) );
        add_action( 'init', array( $this, 'a11yStatement_setup_acf_fields' ),99 );
        add_action( 'init', array( $this, 'a11yStatement_include_shortcodes' ) );
        add_action( 'update_option_a11y_statement_options', array($this, 'a11yStatement_on_settings_save') );

        //action priority >10 to update post content with just saved ACF values
        add_action('acf/save_post', array( $this, 'a11yStatement_save_post'), 11 );

        register_activation_hook( __FILE__, array(  $this, 'a11yStatement_activate' ) );
        register_deactivation_hook( __FILE__, array(  $this, 'a11yStatement_deactivate' ) );
    }

    public function a11yStatement_load_textdomain() {
        load_plugin_textdomain( 'a11yStatement', false, basename( dirname( __FILE__ ) ) . '/languages/' );
    }

    public function a11yStatement_settings_page() {
        require_once plugin_dir_path( __FILE__ ) . 'AccessibilityStatementSettingsPage.php';
        $my_settings_page = new AccessibilityStatementSettingsPage();
    }
    
    public function a11yStatement_on_settings_save() {
        $a11y_page_id = get_option( 'a11yStatement_post_id' );
        $post_content = $this->a11yStatement_generate_post_content();
        $my_post = array(
            'ID'           => $a11y_page_id,
            'post_content' =>  $post_content
        );
        wp_update_post( $my_post );

    }

    public function a11yStatement_include_shortcodes() {
        require_once plugin_dir_path( __FILE__ ) . 'AccessibilityStatementShortcodes.php';
        $my_shortocdes = new AccessibilityStatementShortcodes();
    }

    public function a11yStatement_activate() {
        $created_post_id = $this->a11yStatement_create_post();
        if ($created_post_id) {
            add_option( 'a11yStatement_post_id', $created_post_id );
            $this->a11yStatement_set_post_default_values($created_post_id);
            $this->a11yStatement_set_settings_default_values();
            $post_content = $this->a11yStatement_generate_post_content();

            $my_post = array(
                'ID'           => $created_post_id,
                'post_content' =>  $post_content
            );
            wp_update_post( $my_post );
        }
    }
    
    public function a11yStatement_deactivate() {
        $a11y_page_id = get_option( 'a11yStatement_post_id' );
        wp_delete_post( $a11y_page_id, true );
        delete_option( 'a11yStatement_post_id' );
        delete_option( 'a11y_statement_options' );
    }

    public function update_acf_settings_path( $path ) {
        $path = plugin_dir_path( __FILE__ ) . 'includes/acf/';
        return $path;
    }

    public function update_acf_settings_dir( $dir ) {
        $dir = plugin_dir_url( __FILE__ ) . 'includes/acf/';
        return $dir;
    }

    public function a11yStatement_include_local_acf() {
        if (! class_exists('ACF') ) {
            add_filter( 'acf/settings/path', array( $this, 'update_acf_settings_path' ) );
            add_filter( 'acf/settings/dir', array( $this, 'update_acf_settings_dir' ) );
            add_filter( 'acf/settings/show_admin', '__return_false' );
            include_once( plugin_dir_path( __FILE__ ) . 'includes/acf/acf.php' );
        }
    }
    
    public function a11yStatement_setup_acf_fields() {
        $a11y_page_id = get_option( 'a11yStatement_post_id' );
        $this->a11yStatement_register_acf($a11y_page_id);
    }

    public function a11yStatement_register_acf($post_id) {
        if( function_exists('acf_add_local_field_group') ):
            acf_add_local_field_group(array(
                'key' => 'group_611d155fba545',
                'title' => 'Piekļūstamības paziņojums',
                'fields' => array(
                    array(
                        'key' => 'field_611d158c5cbbd',
                        'label' => 'Piekļūstamības paziņojums',
                        'name' => 'accessibilityStatement',
                        'type' => 'group',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'layout' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_611d18545cbc1',
                                'label' => 'Par izvērtējumu',
                                'name' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'top',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_6193cf343f571',
                                'label' => 'Pirmie soļi',
                                'name' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => 'Lai izveidotu piekļūstamības paziņojumu, Jums vispirms 
            nepieciešams veikt lapas izvērtējumu pēc <a href="http://pieklustamiba.varam.gov.lv/" target="_blank">VARAM vadlīnijām</a>.',
                                'new_lines' => 'wpautop',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_611d16665cbbe',
                                'label' => 'Izvērtējuma veicējs',
                                'name' => 'a11yAuthor',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),
                            array(
                                'key' => 'field_611d17fb5cbbf',
                                'label' => 'Izvērtējuma ziņojuma fails',
                                'name' => 'a11yAcceptDocument',
                                'type' => 'file',
                                'instructions' => 'Rediģējams <a href="http://pieklustamiba.varam.gov.lv/assets/images/docs/izvertesanas-protokola-piemers.docx">izvērtēšanas protokola piemērs</a> lejupielādei.
                                Rediģējams <a href="http://pieklustamiba.varam.gov.lv/assets/images/docs/izvertesanas-protokols.docx">izvērtēšanas protokola sagatave</a> lejupielādei.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'return_format' => 'array',
                                'library' => 'all',
                                'min_size' => '',
                                'max_size' => '',
                                'mime_types' => '',
                            ),
                            array(
                                'key' => 'field_611d182b5cbc0',
                                'label' => 'Pēdējais izvērtējuma datums',
                                'name' => 'a11yEvaluationDate',
                                'type' => 'date_picker',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'display_format' => 'd.m.Y',
                                'return_format' => 'd/m/Y',
                                'first_day' => 1,
                            ),
                            array(
                                'key' => 'field_611d200a5b9bf',
                                'label' => 'Izvērtējuma rezultāti',
                                'name' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'top',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_611d2c7d9252b',
                                'label' => 'Izvērtējuma rezultātā secinātais',
                                'name' => 'a11yEvaluationFits',
                                'type' => 'radio',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'choices' => array(
                                    'tīmekļvietne pilnīgi atbilst MK noteikumiem Nr. 445' => 'tīmekļvietne pilnīgi atbilst MK noteikumiem Nr. 445',
                                    'tīmekļvietne daļēji atbilst MK noteikumiem Nr. 445' => 'tīmekļvietne daļēji atbilst MK noteikumiem Nr. 445',
                                ),
                                'allow_null' => 0,
                                'other_choice' => 0,
                                'default_value' => '',
                                'layout' => 'vertical',
                                'return_format' => 'value',
                                'save_other_choice' => 0,
                            ),
                            array(
                                'key' => 'field_611d1f1fa2100',
                                'label' => 'Konstatētās neatbilstības',
                                'name' => 'a11yNonComplianceList',
                                'type' => 'wysiwyg',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_611d2c7d9252b',
                                            'operator' => '==',
                                            'value' => 'tīmekļvietne daļēji atbilst MK noteikumiem Nr. 445',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'tabs' => 'all',
                                'toolbar' => 'basic',
                                'media_upload' => 1,
                                'delay' => 0,
                            ),
                            array(
                                'key' => 'field_611d1f64a2102',
                                'label' => 'Kādas ir nepiekļūstamā satura alternatīvas?',
                                'name' => 'whatAreAlternativesToNonAccessableContent',
                                'type' => 'wysiwyg',
                                'instructions' => 'Kā citādi tiks nodrošināts, ka lietotājs tiek pie nepiekļūstamā satura?',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_611d2c7d9252b',
                                            'operator' => '==',
                                            'value' => 'tīmekļvietne daļēji atbilst MK noteikumiem Nr. 445',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'tabs' => 'all',
                                'toolbar' => 'basic',
                                'media_upload' => 0,
                                'delay' => 0,
                            ),
                            array(
                                'key' => 'field_611d189c5cbc2',
                                'label' => 'Kontakti',
                                'name' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'top',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_611e1c9760c2d',
                                'label' => 'Lūdzu ievadiet',
                                'name' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => 'Ar ko sazināties par piekļūstamības problēmām?',
                                'new_lines' => 'wpautop',
                                'esc_html' => 0,
                            ),
                            array(
                                'key' => 'field_611d18d15cbc3',
                                'label' => 'E-pasts',
                                'name' => 'a11yMail',
                                'type' => 'email',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                            ),
                            array(
                                'key' => 'field_611d18e45cbc4',
                                'label' => 'Tālrunis',
                                'name' => 'a11yPhone',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),
                            array(
                                'key' => 'field_611d19065cbc5',
                                'label' => 'Cits saziņas veids',
                                'name' => 'a11yOtherContacts',
                                'type' => 'textarea',
                                'instructions' => 'Piem., Twitter, Linkedin u.tml.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'maxlength' => '',
                                'rows' => '',
                                'new_lines' => '',
                            ),
                            array(
                                'key' => 'field_611d191b5cbc6',
                                'label' => 'Cik ilgā laikā tiks sniegta atbilde?',
                                'name' => 'a11yTime',
                                'type' => 'text',
                                'instructions' => 'Piem., 5 darba dienu laikā',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),
                            array(
                                'key' => 'field_611d19305cbc7',
                                'label' => 'Sūdzības',
                                'name' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'top',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_611d1d418ccc2',
                                'label' => 'Kam iestādes iekšienē var iesniegt sūdzību? (vārds, uzvārds, amats, kontakti)',
                                'name' => 'a11yComplaints',
                                'type' => 'textarea',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'maxlength' => '',
                                'rows' => '',
                                'new_lines' => 'br',
                            ),
                            array(
                                'key' => 'field_611d1d5c8ccc3',
                                'label' => 'Kam Jūsu pārraugošajā iestādē var iesniegt sūdzību? (ja attiecināms)',
                                'name' => 'a11ySupervisoryAuthorityComplaints',
                                'type' => 'textarea',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'maxlength' => '',
                                'rows' => '',
                                'new_lines' => 'br',
                            ),
                            array(
                                'key' => 'field_611d1d808ccc4',
                                'label' => 'Par ziņojumu',
                                'name' => '',
                                'type' => 'tab',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'placement' => 'top',
                                'endpoint' => 0,
                            ),
                            array(
                                'key' => 'field_611d1d9c8ccc5',
                                'label' => 'Paziņojuma pirmreizējās sagatavošanas datums',
                                'name' => 'a11yFirstNotify',
                                'type' => 'date_picker',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'display_format' => 'd.m.Y',
                                'return_format' => 'm/d/Y',
                                'first_day' => 1,
                            ),
                            array(
                                'key' => 'field_611d1dd08ccc6',
                                'label' => 'Vai paziņojums pārskatīts atkārtoti?',
                                'name' => 'HasStatementBeenReReviewed',
                                'type' => 'true_false',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'default_value' => 0,
                                'ui' => 1,
                                'ui_on_text' => 'Jā',
                                'ui_off_text' => 'Nē',
                            ),
                            array(
                                'key' => 'field_611d1e068ccc7',
                                'label' => 'Paziņojuma atkārtotas pārskatīšanas datums',
                                'name' => 'a11yRepeatedNotify',
                                'type' => 'date_picker',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => array(
                                    array(
                                        array(
                                            'field' => 'field_611d1dd08ccc6',
                                            'operator' => '==',
                                            'value' => '1',
                                        ),
                                    ),
                                ),
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'display_format' => 'd.m.Y',
                                'return_format' => 'm/d/Y',
                                'first_day' => 1,
                            ),
                            array(
                                'key' => 'field_611d1e2d8ccc8',
                                'label' => 'Paziņojuma apstiprinājs (vārds uzvārds)',
                                'name' => 'a11yApprovedBy',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),
                            array(
                                'key' => 'field_611d1e508ccc9',
                                'label' => 'Apstiprinātāja amats',
                                'name' => 'a11yApprovedByJobtitle',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                            ),
                        ),
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'page',
                            'operator' => '==',
                            'value' => $post_id,
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => array(
                    0 => 'the_content',
                    1 => 'excerpt',
                    2 => 'discussion',
                    3 => 'comments',
                    4 => 'revisions',
                    5 => 'author',
                    6 => 'format',
                    7 => 'page_attributes',
                    8 => 'featured_image',
                    9 => 'categories',
                    10 => 'tags',
                    11 => 'send-trackbacks',
                ),
                'active' => true,
                'description' => '',
                'show_in_rest' => 0,
            ));
        
        endif;		
    }

    public function a11yStatement_create_post() {
        $post = array(     
            'post_content'   =>   '',
            'post_title'     =>  'Piekļūstamības paziņojums', 
            'post_status'    =>  'publish',
            'post_type'      =>  'page'
        );
        $inserted_post_id = wp_insert_post( $post );
        
        return $inserted_post_id;
    }

    public function a11yStatement_set_post_default_values($post_id) {
        update_post_meta( $post_id, 'accessibilityStatement_a11yEvaluationFits', 'tīmekļvietne daļēji atbilst MK noteikumiem Nr. 445' );
        update_post_meta( $post_id, 'accessibilityStatement_a11yNonComplianceList', '<ul>
        <li>Nav korekti pievienoti subtitri, ekrāna lasītājs tos nespēj nolasīt.</li>
        </ul>' );
        update_post_meta( $post_id, 'accessibilityStatement_whatAreAlternativesToNonAccessableContent', 'Subtitru alterntīva - saturā pievienot audio aprakstu (audio description) teksta formātā zem video (biežāk  satura veidos pasākumā vai aktualitātē).' );
        update_post_meta( $post_id, 'accessibilityStatement_a11yAuthor', 'Vārds, uzvārds ' );
        update_post_meta( $post_id, 'accessibilityStatement_a11yEvaluationDate', '29.12.2020' );
        update_post_meta( $post_id, 'accessibilityStatement_a11yMail', 'vards.uzvards@demoskola.lv' );
        update_post_meta( $post_id, 'accessibilityStatement_a11yPhone', '+371 20000004' );
        update_post_meta( $post_id, 'accessibilityStatement_a11yOtherContacts', '' );
        update_post_meta( $post_id, 'accessibilityStatement_a11yTime', '5 (piecu) darba dienu laikā' );
        update_post_meta( $post_id, 'accessibilityStatement_a11yComplaints', 
        'Vārds, uzvārds
        Demo skolas atbildīgais par tīmekļvietni
        E-pasts: vards.uzvards@demoskola.lv
        Tālrunis: 67000004; +371 2000009' );
        update_post_meta( $post_id, 'accessibilityStatement_a11ySupervisoryAuthorityComplaints', 'Vārds, uzvārds ' );
        update_post_meta( $post_id, 'accessibilityStatement_a11yFirstNotify', '29.12.2020' );
        update_post_meta( $post_id, 'accessibilityStatement_HasStatementBeenReReviewed', '1' );
        update_post_meta( $post_id, 'accessibilityStatement_a11yRepeatedNotify', 'Vārds, uzvārds ' );
        update_post_meta( $post_id, 'accessibilityStatement_a11yApprovedBy', 'Pēteris Demo' );
        update_post_meta( $post_id, 'accessibilityStatement_a11yApprovedByJobtitle', 'Demo skolas direktors vai vietnieks' );
    }

    public function a11yStatement_set_settings_default_values() {
        $content_config = 
        '<div class="text-editor">
            <p>
            [blog_name] apņemas  veidot savu <span class="a11y-type">tīmekļvietni</span> piekļūstamu saskaņā ar Ministru kabineta 2020. gada 14. jūlija noteikumiem Nr. 445 "Kārtība, kādā iestādes ievieto informāciju internetā" (turpmāk – noteikumi Nr. 445).
            </p>
            <p>
            Šis piekļūstamības paziņojums attiecas uz [blog_name_and_link]
            </p>
            <p>
            Tīmekļvietnei veikts <span id="a11y-evaluation-type">Vienkāršotais izvērtējums</span>. Izmantotā metode - <span id="a11y-method">VARAM sagatavotās “Vadlīnijas iestāžu tīmekļvietnēm noteikto piekļūstamības prasību ievērošanas ietekmes izvērtēšanai un nesamērīgā sloga pamatošanai”</span>.
            </p>
            <h2>Cik piekļūstama ir šī tīmekļvietne?</h2>
            <p>[blog_link] [a11y_Evaluation_Fits]</p>
            
                [a11y_Non_Compliance_List]

            <p>Šī tīmekļvietne pēdējo reizi tika izvērtēta [evaluation_date]. Izvērtēšanu veica [a11y_author].</p>  
                
            <p>Izvērtējumu apliecinošs dokuments: [accept_document].</p>
        
            [what_Are_Alternatives_To_Non_Accessable_Content]
                
            <h2>Atsauksmēm un saziņai</h2>
            <p>Mēs nepārtraukti cenšamies uzlabot šīs tīmekļvietnes piekļūstamību.</p>
            <p>Ja Jūs konstatējat kādas problēmas vai nepilnības, kas nav minētas šajā paziņojumā vai vēlaties saņemt nepiekļūstamo saturu citā formātā, sazinieties ar mums:</p>
            <p>
                E-pastā: [contact_mail]
            </p>
            <p>
                Zvaniet: [contact_phone]
            </p>
            
            <p>
                [other_contacts]
            </p>
            <p>
                Mēs izskatīsim Jūsu pieprasījumu un sniegsim atbildi [response_time].
            </p>
        
            <h2>Sūdzību iesniegšana</h2>
            <p>[complaints]</p>
            <p>Ja neesam atbilstoši reaģējuši uz Jūsu iesniegumu vai sūdzību par tīmekļvietnes satura piekļūstamību, Jums ir iespēja iesniegt sūdzību Latvijas Republikas Tiesībsargam:</p>
            <p>Tiesībsarga birojs: Baznīcas iela 25 Rīgā, LV-1010</p>
            <p>Tālrunis: <a href="tel:+37167686768">+37167686768</a></p>
            <p>E-pasts: <a href="mailto:tiesibsargs@tiesibsargs.lv">tiesibsargs@tiesibsargs.lv</a></p>
            <p><a href="https://www.tiesibsargs.lv/lv/pages/kontaktinformacija" target="_blank">https://www.tiesibsargs.lv/lv/pages/kontaktinformacija</a></p>
        
            <h2>Ziņas par paziņojuma sagatavošanu</h2>
        
            <p>Šis paziņojums pirmo reizi tika sagatavots [first_notify].</p>
            [has_statement_been_rereviewed]
            <p>Šo paziņojumu apstiprināja [approved_by_name] [approved_by_job_title].</p>
        </div>';


        $deafult_values = array( 'content_config' => $content_config );
        add_option( 'a11y_statement_options', $deafult_values);

    }

    public function a11yStatement_save_post($post_id) {
        $a11y_page_id = get_option( 'a11yStatement_post_id' );
        if ($post_id === $a11y_page_id) {
            $post_content = $this->a11yStatement_generate_post_content();
            $my_post = array(
                'ID'           => $post_id,
                'post_content' =>  $post_content
            );
            wp_update_post( $my_post );
        }
    }
    
    public function a11yStatement_generate_post_content() {
        $settingsOptions = get_option( 'a11y_statement_options' );
        $post_content = $settingsOptions['content_config'];

        return $post_content;
    }

    public function a11yStatement_remove_editor() {
        if (isset($_GET['post'])) {
            $id = (int) $_GET['post'];
            if (is_int($id)) {
                $a11y_page_id = (int) get_option( 'a11yStatement_post_id' );
                if($id === $a11y_page_id){ 
                    remove_post_type_support( 'page', 'editor' );
                }
            }
        }
    }
      
}
new AccessibilityStatementPlugin();