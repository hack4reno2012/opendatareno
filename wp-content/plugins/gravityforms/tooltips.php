<?php

//Prints required tooltip scripts
add_action("admin_print_scripts", 'print_tooltip_scripts');
function print_tooltip_scripts(){
    wp_register_script('qtip-lib' , GFCommon::get_base_url() ."/js/jquery.qtip-1.0.0-rc2.min.js");
    wp_enqueue_script('qtip-init' , GFCommon::get_base_url() ."/js/qtip_init.js", array('qtip-lib'));
    wp_enqueue_style("gf_tooltip", GFCommon::get_base_url() ."/css/tooltip.css");

    wp_print_scripts();
    wp_print_styles();
}

function gform_tooltip($name, $css_class="tooltip"){
    $gf_tooltips = array(
        "notification_send_to_email" => "<h6>" . __("Send To Email Address", "gravityforms") . "</h6>" . __("Enter the email address you would like the administrator notification email sent to.", "gravityforms"),
        "notification_autoformat" => "<h6>" . __("Disable Auto-Formatting", "gravityforms") . "</h6>" . __("When enabled, auto-formatting will insert paragraph breaks automatically. Disable auto-formatting when using HTML to create email notification content.", "gravityforms"),
        "notification_send_to_routing" => "<h6>" . __("Routing", "gravityforms") . "</h6>" . __("Allows notification to be sent do different email addresses depending on values selected in the form.", "gravityforms"),
        "notification_from_email" => "<h6>" . __("From Email Address", "gravityforms") . "</h6>" . __("Enter the email address you would like the administrator notification email sent from, or select the email from available email form fields.", "gravityforms"),
        "notification_reply_to" => "<h6>" . __("Reply To", "gravityforms") . "</h6>" . __("Enter the email address you would like to be used as the reply to address for the administrator notification email.", "gravityforms"),
        "notification_bcc" => "<h6>" . __("Blind Carbon Copy Addresses", "gravityforms") . "</h6>" . __("Enter a comma separated list of email addresses you would like to receive a BCC of the administrator notification email.", "gravityforms"),
        "autoresponder_send_to_email" => "<h6>" . __("Blind Carbon Copy Addresses", "gravityforms") . "</h6>" . __("Select the email form field that the user notification email should be sent to.", "gravityforms"),
        "autoresponder_bcc" => "<h6>" . __("Blind Carbon Copy Addresses", "gravityforms") . "</h6>" . __("Enter a comma separated list of email addresses you would like to receive a BCC of the user notification email.", "gravityforms"),
        "autoresponder_from" => "<h6>" . __("From Email Address", "gravityforms") . "</h6>" . __("Enter the email address you would like the user notification email sent from.", "gravityforms"),
        "autoresponder_reply_to" => "<h6>" . __("Reply To Address", "gravityforms") . "</h6>" . __("Enter the email address you would like to be used as the reply to address for the user notification email.", "gravityforms"),
        "form_activity" => "<h6>" . __("Limit Form Activity", "gravityforms") . "</h6>" . __("Limit the number of entries a form can generate and/or schedule a time period the form is active.", "gravityforms"),
        "form_limit_entries" => "<h6>" . __("Limit Number of Entries", "gravityforms") . "</h6>" . __("Enter a number in the input box below to limit the number of entries allowed for this form. The form will become inactive when that number is reached.", "gravityforms"),
        "form_schedule_form" => "<h6>" . __("Schedule Form", "gravityforms") . "</h6>" . __("Schedule a time period the form is active.", "gravityforms"),
        "form_tile" => "<h6>" . __("Form Title", "gravityforms") . "</h6>" . __("Enter the title of your form.", "gravityforms"),
        "form_description" => "<h6>" . __("Form Description", "gravityforms") . "</h6>" . __("Enter a description for your form. This may be used for user instructions.", "gravityforms"),
        "form_label_placement" => "<h6>" . __("Form Label Placement", "gravityforms") . "</h6>" . __("Select the label placement.  Labels can be top aligned above a field, left aligned to the left of a field, or right aligned to the left of a field.", "gravityforms"),
        "form_button_text" => "<h6>" . __("Form Button Text", "gravityforms") . "</h6>" . __("Enter the text you would like to appear on the form submit button.", "gravityforms"),
        "form_button_image" => "<h6>" . __("Form Button Image", "gravityforms") . "</h6>" . __("Enter the path to an image you would like to use as the form submit button.", "gravityforms"),
        "form_css_class" => "<h6>" . __("Form CSS Class Name", "gravityforms") . "</h6>" . __("Enter the CSS class name you would like to use in order to override the default styles for this form.", "gravityforms"),
        "form_confirmation_message" => "<h6>" . __("Confirmation Message Text", "gravityforms") . "</h6>" . __("Enter the text you would like the user to see on the confirmation page of this form.", "gravityforms"),
        "form_redirect_to_webpage" => "<h6>" . __("Redirect Form to Page", "gravityforms") . "</h6>" . __("Select the page you would like the user to be redirected to after they have submitted the form.", "gravityforms"),
        "form_redirect_to_url" => "<h6>" . __("Redirect Form to URL", "gravityforms") . "</h6>" . __("Enter the URL of the webpage you would like the user to be redirected to after they have submitted the form.", "gravityforms"),
        "form_redirect_querystring" => "<h6>" . __("Pass Data Via Query String", "gravityforms") . "</h6>" . __("To pass field data to the confirmation page, build a Query String using the 'Insert form field' drop down. <a href='http://en.wikipedia.org/wiki/Query_string' target='_blank'>..more info on querystrings &raquo;</a>", "gravityforms"),
        "form_field_label" => "<h6>" . __("Field Label", "gravityforms") . "</h6>" . __("Enter the label of the form field.  This is the field title the user will see when filling out the form.", "gravityforms"),
        "form_field_label_html" => "<h6>" . __("Field Label", "gravityforms") . "</h6>" . __("Enter the label for this HTML block. It will help you identify your HTML blocks in the form editor, but it will not be displayed on the form.", "gravityforms"),
        "form_field_disable_margins" => "<h6>" . __("Disable Default Margins", "gravityforms") . "</h6>" . __("When enabled, margins are added to properly align the HTML content with other form fields.", "gravityforms"),
        "form_field_recaptcha_theme" => "<h6>" . __("Recaptcha Theme", "gravityforms") . "</h6>" . __("Select the visual theme for the reCAPTCHA field the available options to better match your site design.", "gravityforms"),
        "form_field_custom_field_name" => "<h6>" . __("Custom Field Name", "gravityforms") . "</h6>" . __("Select the custom field name from available existing custom fields, or enter a new custom field name.", "gravityforms"),
        "form_field_type" => "<h6>" . __("Field type", "gravityforms") . "</h6>" . __("Select the type of field from the available form fields.", "gravityforms"),
        "form_field_date_input_type" => "<h6>" . __("Date Input Type", "gravityforms") . "</h6>" . __("Select the type of inputs you would like to use for the date field. Date Picker will let users select a date from a calendar. Date Field will let users free type the date.", "gravityforms"),
        "form_field_address_type" => "<h6>" . __("Address Type", "gravityforms") . "</h6>" . __("Select the type of address you would like to use.", "gravityforms"),
        "form_field_address_default_state" => "<h6>" . __("Default State", "gravityforms") . "</h6>" . __("Select the state you would like to be selected by default when the form gets displayed.", "gravityforms"),
        "form_field_address_default_province" => "<h6>" . __("Default Province", "gravityforms") . "</h6>" . __("Select the province you would like to be selected by default when the form gets displayed.", "gravityforms"),
        "form_field_address_default_country" => "<h6>" . __("Default Country", "gravityforms") . "</h6>" . __("Select the country you would like to be selected by default when the form gets displayed.", "gravityforms"),
        "form_field_address_hide_country" => "<h6>" . __("Hide Country", "gravityforms") . "</h6>" . __("For addresses that only apply to one country, you can choose to not display the country drop down. Entries will still be recorded with the selected country.", "gravityforms"),
        "form_field_address_hide_address2" => "<h6>" . __("Hide Address Line 2", "gravityforms") . "</h6>" . __("Check this box to prevent the extra address input (Address Line 2) from being displayed in the form.", "gravityforms"),
        "form_field_name_format" => "<h6>" . __("Field Name Format", "gravityforms") . "</h6>" . __("Select the format you would like to use for the Name field.  There are 3 options, Normal which includes First and Last Name, Extended which adds Prefix and Suffix, or Simple which is a single input field.", "gravityforms"),
        "form_field_date_format" => "<h6>" . __("Field Date Format", "gravityforms") . "</h6>" . __("Select the format you would like to use for the date input.  Available options are MM/DD/YYYY and DD/MM/YYYY.", "gravityforms"),
        "form_field_fileupload_allowed_extensions" => "<h6>" . __("Allowed File Extensions", "gravityforms") . "</h6>" . __("Enter that allowed file extensions for file uploads.  This will limit what type of files a user may upload.", "gravityforms"),
        "form_field_phone_format" => "<h6>" . __("Phone Number Format", "gravityforms") . "</h6>" . __("Select the format you would like to use for the phone input.  Available options are domestic US/CANADA style phone number and international long format phone number.", "gravityforms"),
        "form_field_description" => "<h6>" . __("Field Description", "gravityforms") . "</h6>" . __("Enter the description for the form field.  This will be displayed to the user and provide some direction on how the field should be filled out or selected.", "gravityforms"),
        "form_field_required" => "<h6>" . __("Required Field", "gravityforms") . "</h6>" . __("Select this option to make the form field required.  A required field will prevent the form from being submitted if it is not filled out or selected.", "gravityforms"),
        "form_field_no_duplicate" => "<h6>" . __("No Duplicates", "gravityforms") . "</h6>" . __("Select this option to limit user input to unique values only.  This will require that a value entered in a field does not currently exist in the entry database for that field.", "gravityforms"),
        "form_field_number_range" => "<h6>" . __("Number Range", "gravityforms") . "</h6>" . __("Enter the minimum and maximum values for this form field.  This will require that the value entered by the user must fall within this range.", "gravityforms"),
        "form_field_admin_label" => "<h6>" . __("Admin Label", "gravityforms") . "</h6>" . __("Enter the admin label of the form field.  Entering a value in this field will override the Field Label when displayed in the Gravity Forms administration tool.", "gravityforms"),
        "form_field_size" => "<h6>" . __("Field Size", "gravityforms") . "</h6>" . __("Select a form field size from the available options.  This will set the width of the field.", "gravityforms"),
        "form_field_default_value" => "<h6>" . __("Default Value", "gravityforms") . "</h6>" . __("If you would like to pre-populate the value of a field, enter it here.", "gravityforms"),
        "form_field_validation_message" => "<h6>" . __("Validation Message", "gravityforms") . "</h6>" . __("If you would like to override the default error validation for a field, enter it here.  This message will be displayed if there is an error with this field when the user submits the form.", "gravityforms"),
        "form_field_recaptcha_language" => "<h6>" . __("reCaptcha Language", "gravityforms") . "</h6>" . __("Select the language you would like to use for the reCAPTCHA display from the available options.", "gravityforms"),
        "form_field_css_class" => "<h6>" . __("CSS Class Name", "gravityforms") . "</h6>" . __("Enter the CSS class name you would like to use in order to override the default styles for this field.", "gravityforms"),
        "form_field_visibility" => "<h6>" . __("Visibility", "gravityforms") . "</h6>" . __("Select the visibility for this field. Field visibility set to Everyone will be visible by the user submitting the form. Form field visibility set to Admin Only will only be visible within the Gravity Forms administration tool.<br /><br />Setting a field to Admin Only is useful for creating fields that can be used to set a status or priority level on submitted entries.", "gravityforms"),
        "form_field_choices" => "<h6>" . __("Field Choices", "gravityforms") . "</h6>" . __("Add Choices to this field. You can mark each choice as checked by default by using the radio/checkbox fields on the left.", "gravityforms"),
        "form_field_conditional_logic" => "<h6>" . __("Conditional Logic", "gravityforms") . "</h6>" . __("Create rules to dynamically display or hide this field based on values from another field", "gravityforms"),
        "form_button_conditional_logic" => "<h6>" . __("Conditional Logic", "gravityforms") . "</h6>" . __("Create rules to dynamically display or hide the submit button based on values from another field", "gravityforms"),
        "form_field_post_category_selection" => "<h6>" . __("Post Category", "gravityforms") . "</h6>" . __("Select which categories are displayed. You can choose to display all of them or select individual ones.", "gravityforms"),
        "form_field_post_status" => "<h6>" . __("Post Status", "gravityforms") . "</h6>" . __("Select the post status that will be used for the post that is created by the form entry.", "gravityforms"),
        "form_field_post_author" => "<h6>" . __("Post Author", "gravityforms") . "</h6>" . __("Select the author that will be used for the post that is created by the form entry.", "gravityforms"),
        "form_field_post_category" => "<h6>" . __("Post Category", "gravityforms") . "</h6>" . __("Select the category that will be used for the post that is created by the form entry.", "gravityforms"),
        "form_field_current_user_as_author" => "<h6>" . __("Use Current User as Author", "gravityforms") . "</h6>" . __("Selecting this option will set the post author to the WordPress user that submitted the form.", "gravityforms"),
        "form_field_image_meta" => "<h6>" . __("Image Meta", "gravityforms") . "</h6>" . __("Select one or more image metadata field to be displayed along with the image upload field. They enable users to enter additional information about the uploaded image.", "gravityforms"),
        "form_field_prepopulate" => "<h6>" . __("Incoming Field Data", "gravityforms") . "</h6>" . __("Check this option to enable data to be passed to the form and pre-populate this field dynamically. Data can be passed via Query Strings, Shortcode and/or Hooks", "gravityforms"),
        "form_field_content" => "<h6>" . __("Content", "gravityforms") . "</h6>" . __("Enter the content (Text or HTML) to be displayed on the form.", "gravityforms"),
        "form_standard_fields" => "<h6>" . __("Standard Fields", "gravityforms") . "</h6>" . __("Standard Fields provide basic form functionality. Use them to create a wide variety of flexible fields.", "gravityforms"),
        "form_advanced_fields" => "<h6>" . __("Advanced Fields", "gravityforms") . "</h6>" . __("Advanced Fields are for specific uses.  They enable advanced formatting of regularly used fields such as Name, Email, Address, etc.", "gravityforms"),
        "form_post_fields" => "<h6>" . __("Post Fields", "gravityforms") . "</h6>" . __("Post Fields allow you to add fields to your form that create Post Drafts in WordPress from the submitted data.", "gravityforms"),
        "export_select_form" => "<h6>" . __("Export Selected Form", "gravityforms") . "</h6>" . __("Select the form you would like to export entry data from. You may only export data from one form at a time.", "gravityforms"),
        "export_select_forms" => "<h6>" . __("Export Selected Forms", "gravityforms") . "</h6>" . __("Select the forms you would like to export.", "gravityforms"),
        "export_select_fields" => "<h6>" . __("Export Selected Fields", "gravityforms") . "</h6>" . __("Select the fields from the select form you would like to export data from.", "gravityforms"),
        "export_date_range" => "<h6>" . __("Export Date Range", "gravityforms") . "</h6>" . __("Select a date range. Setting a range will only export entries submitted during that date range. If no range is set, all entries will be exported.", "gravityforms"),
        "settings_license_key" => "<h6>" . __("Settings License Key", "gravityforms") . "</h6>" . __("Your Gravity Forms support license key is used to verify your support package, enable automatic updates and receive support.", "gravityforms"),
        "settings_output_css" => "<h6>" . __("Output CSS", "gravityforms") . "</h6>" . __("Select yes or no to enable or disable CSS output.  Setting this to no will disable the standard Gravity Forms CSS from being included in your theme.", "gravityforms"),
        "settings_html5" => "<h6>" . __("Output HTML5", "gravityforms") . "</h6>" . __("Select yes or no to enable or disable HTML5 output. Setting this to no will disable the standard Gravity Forms HTML5 form field output.", "gravityforms"),
        "settings_recaptcha_public" => "<h6>" . __("reCaptcha Public Key", "gravityforms") . "</h6>" . __("Enter your reCAPTCHA Public Key, if you do not have a key you can register for one at the provided link.  reCAPTCHA is a free service.", "gravityforms"),
        "settings_recaptcha_private" => "<h6>" . __("reCaptcha Private Key", "gravityforms") . "</h6>" . __("Enter your reCAPTCHA Private Key, if you do not have a key you can register for one at the provided link.  reCAPTCHA is a free service.", "gravityforms"),
        "entries_conversion" => "<h6>" . __("Entries Conversion", "gravityforms") . "</h6>" . __("Conversion is the percentage of form views that generated an entry. If a form was viewed twice, and one entry was generated, the conversion will be 50%.", "gravityforms")
    );

    $gf_tooltips = apply_filters("gform_tooltips", $gf_tooltips);

    ?>
    <a href="javascript:void(0);" class="<?php echo esc_attr($css_class) . " tooltip_" . $name?> " tooltip="<?php echo esc_attr($gf_tooltips[$name]) ?>">(?)</a>
    <?php
}
?>
