<?php

/**
 * Displays a form in the front-end.
 *
 */


class AdminPageFramework_FrontendFormBeta extends AdminPageFramework_FrontendFormBeta_Base {

    /**
     * The action/filter hook that the form output will be inserted.
     */
    public $sHookName = 'the_content';

    /**
     * Set the callback priority passed to the `add_action()` or `add_filter()` function.
     */
    public $iPriority = 11;

    /**
     * Set the hook type either `filter` or `action`.
     */
    public $sHookType = 'filter';   // or action

    /**
     * Gets called when the page loads. Do some necessary set-ups here.
     */
    public function load() {

        // Register field types
        if ( class_exists( 'DateCustomFieldType' ) ) {
            new DateCustomFieldType( get_class( $this ) );
        }

    }


    /**
     * Determines whether the form should be loaded in the current page.
     * @return      boolean
     */
    public function isInThePage() {
        if ( ! is_singular() ) {
            return false;
        }
        if ( ! is_main_query() ) {
            return false;
        }
        return true;
    }

    /**
     * @return      array
     */
    public function getStyles() {
        return array(
            '.form-table {
                border: none;
            }
            .form-table th {
                width: 30%;
            }
            .form-table tr > * {
                vertical-align: top;
                border: none;
            }
            .form-table tr label,
            .form-table tr fieldset {
                vertical-align: middle;
            }
            .form-table .admin-page-framework-input-label-container label {
                vertical-align: top;
            }
            .form-table .admin-page-framework-field-radio input {
                margin-right: 0.4em;
            }
            .form-table .admin-page-framework-field-submit {
                text-align: right;
                width: 100%;
            }
            .form-table .admin-page-framework-field-submit .admin-page-framework-input-button-container {
                padding-right: 0;
            }
            .form-table .select_image.button,
            .form-table .remove_image.button,
            .form-table .remove_value.button {
                text-decoration: none;
                border: none;
            }
            .form-table .admin-page-framework-field {
                margin: 0;
            }
            .form-table .admin-page-framework-checkbox-label {
                margin-top: 0;
            }
            .form-table .admin-page-framework-field .admin-page-framework-input-label-container {
                margin-bottom: 0;
            }
            @media only screen and (max-width: 600px) {
                .form-table > tbody > tr > td,
                .form-table > tbody > tr > th {
                    display: inline-block;
                    width: 100%;
                }
                .form-table > tbody > tr > td {
                    margin-bottom: 0.6em;
                }
                .form-table > tbody > tr {
                }
            }
            /* Notification Containers */
            .submit-notice > p {
                border-radius: 6px;
                padding: 1em 2em;
            }
            .submit-notice.error > p {
                border: solid 1px #8a2323;
                background-color: #ffe0e0;
            }
            .submit-notice.update > p {
                border: solid 1px #418a23;
                background-color: #e9ffe0;
            }
            ',
            // or set a CSS file path
            // dirname( __FILE__ ) . '/css/style.css',
        );
    }

    /**
     * Retrieves form section definition arrays.
     * @return  array
     */
    public function getSections() {
        return array();
    }

    /**
     * Retrieves form field definition arrays.
     */
    public function getFields() {
        return array(
            array(
                'title'             => __( 'Text Input', 'admin-page-framework' ),
                'show_debug_info'   => false,
                'field_id'          => 'text',
                'type'              => 'text',
            ),
            array(
                'title'             => __( 'Text Area', 'admin-page-framework' ),
                'field_id'          => 'textarea',
                'type'              => 'textarea',
                'show_debug_info'   => false,
            ),
            array(
                'title'             => __( 'Radio Buttons', 'admin-page-framework' ),
                'field_id'          => 'radio',
                'type'              => 'radio',
                'show_debug_info'   => false,
                'label'             => array(
                    'on'  => __( 'On', 'admin-page-framework' ),
                    'off' => __( 'Off', 'admin-page-framework' ),
                ),
                'default'           => 'on',
            ),
            array(
                'title'             => __( 'Drop-dorn', 'admin-page-framework' ),
                'field_id'          => 'select',
                'type'              => 'select',
                'show_debug_info'   => false,
                'label'             => array(
                    0 => __( 'Sunday', 'admin-page-framework' ),
                    1 => __( 'Monday', 'admin-page-framework' ),
                    2 => __( 'Tuesday', 'admin-page-framework' ),
                    3 => __( 'Wednesday', 'admin-page-framework' ),
                    4 => __( 'Thursday', 'admin-page-framework' ),
                    5 => __( 'Friday', 'admin-page-framework' ),
                    6 => __( 'Saturday', 'admin-page-framework' ),
                ),
                'default'           => 4,
            ),
            array(
                'title'             => __( 'Check Box', 'admin-page-framework' ),
                'field_id'          => 'checkbox',
                'type'              => 'checkbox',
                'show_debug_info'   => false,
                'label'             => __( 'This is a check box.', 'admin-page-framework' ),
            ),
            array(
                'title'             => __( 'Image', 'admin-page-framework' ),
                'field_id'          => 'image',
                'type'              => 'image',
                'show_debug_info'   => false,
            ),
            array(
                'title'             => __( 'Color', 'admin-page-framework' ),
                'field_id'          => 'color',
                'type'              => 'color',
                'show_debug_info'   => false,
            ),
            array(
                'field_id'          => 'date',
                'type'              => 'date',
                'title'             => __( 'Date', 'admin-page-framework' ),
                'date_format'       => 'mm/dd',
                'show_debug_info'   => false,
            ),
            array(
                'field_id'          => '_submit',
                'type'              => 'submit',
                'value'             => __( 'Submit', 'admin-page-framework' ),
                'show_debug_info'   => false,
            ),
        );
    }

    /**
     * Get the output.
     * Here you can modify the output.
     * @return      string
     */
    public function content( $sFormOutput ) {
        return "<h3>" . __( 'Form', 'admin-page-framework' ) . "</h3>"
            . $sFormOutput;
    }

    /**
     * Do form submission handling.
     * @return      void
     */
    public function validate( $aInputs ) {

        $_bHasError = false;
        $_aErrors   = array();
        if ( ! is_numeric( $aInputs[ 'text' ] ) ) {
            $_aErrors[ 'text' ] = 'Type a numeric value.';
            $_bHasError = true;
        }
        if ( empty( $aInputs[ 'textarea' ] ) ) {
            $_aErrors[ 'textarea' ] = 'Please type something.';
            $_bHasError = true;
        }
        $this->setFieldErrors( $_aErrors );

        if ( $_bHasError ) {
            $this->setSubmitNotice( 'Please correct some information you entered.', 'error' );
            $this->aFormData = $aInputs;
        } else {
            $this->setSubmitNotice( 'Form has been submitted', 'update' );
        }

    }

}

//EXAMPLE
//new AdminPageFramework_FrontendFormBeta;

// add here
require_once(YC_ROOT_DIR . '/include_FrontEnd_Form.php');



