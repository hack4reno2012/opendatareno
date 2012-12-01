function Form(){
    this.id = 0;
    this.title = "Untitled Form";
    this.description = "We would love to hear from you! Please fill out this form and we will get in touch with you shortly.";
    this.labelPlacement = "top_label";
    this.maxEntriesMessage = "";
    this.confirmation = new Confirmation();
    this.button = new Button();
    this.fields = new Array();
}

function Confirmation(){
    this.type = "message";
    this.message = "Thanks for contacting us! We will get in touch with you shortly.";
    this.url = "";
    this.pageId = "";
    this.queryString="";
}

function Button(){
    this.type = "text";
    this.text = "Submit";
    this.imageUrl = "";
}

function Field(id, type){
    this.id = id;
    this.label = "";
    this.adminLabel = "";
    this.type = type;
    this.isRequired = false;
    this.size = "medium";
    this.errorMessage = "";
    //NOTE: other properties will be added dynamically using associative array syntax
}

function Choice(text, value){
    this.text=text;
    this.value = value ? value : text;
    this.isSelected = false;
}

function Input(id, label){
    this.id = id;
    this.label = label;
    this.name = "";
}

function ConditionalLogic(){
    this.actionType = "show"; //show or hide
    this.logicType = "all"; //any or all
    this.rules = [new ConditionalRule()];
}

function ConditionalRule(){
    this.fieldId = 0;
    this.operator = "is"; //is or is not
    this.value = "";
}

var fieldSettings = {
    "html" :        ".disable_margins_setting, .label_setting, .content_setting, .conditional_logic_field_setting, .prepopulate_field_setting",
    "hidden" :      ".prepopulate_field_setting, .label_setting, .default_value_setting",
    "section" :     ".conditional_logic_field_setting, .label_setting, .description_setting, .visibility_setting, .css_class_setting",
    "text" :        ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .size_setting, .rules_setting, .visibility_setting, .duplicate_setting, .default_value_setting, .description_setting, .css_class_setting",
    "website" :     ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .size_setting, .rules_setting, .visibility_setting, .duplicate_setting, .default_value_setting, .description_setting, .css_class_setting",
    "phone" :       ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .rules_setting, .duplicate_setting, .visibility_setting, .default_value_setting, .description_setting, .phone_format_setting, .css_class_setting",
    "number" :      ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .size_setting, .range_setting, .rules_setting, .visibility_setting, .duplicate_setting, .default_value_setting, .description_setting, .css_class_setting",
    "date" :        ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .rules_setting, .date_input_type_setting, .duplicate_setting, .visibility_setting, .date_format_setting, .default_value_setting, .description_setting, .css_class_setting",
    "time" :        ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .rules_setting, .duplicate_setting, .visibility_setting, .description_setting, .css_class_setting",
    "textarea" :    ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .size_setting, .rules_setting, .visibility_setting, .duplicate_setting, .default_value_textarea_setting, .description_setting, .css_class_setting",
    "select" :      ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .size_setting, .choices_setting, .rules_setting,  .duplicate_setting, .visibility_setting, .description_setting, .css_class_setting",
    "checkbox" :    ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .choices_setting, .rules_setting, .visibility_setting, .description_setting, .css_class_setting",
    "radio" :       ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .choices_setting, .rules_setting, .visibility_setting, .duplicate_setting, .description_setting, .css_class_setting",
    "name" :        ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .name_format_setting, .rules_setting, .visibility_setting, .description_setting, .css_class_setting",
    "address" :     ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .address_setting, .rules_setting, .description_setting, .visibility_setting, .css_class_setting",
    "fileupload" :  ".conditional_logic_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .rules_setting, .file_extensions_setting, .visibility_setting, .description_setting, .css_class_setting",
    "email" :       ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .label_setting, .admin_label_setting, .size_setting, .rules_setting, .visibility_setting, .duplicate_setting, .default_value_setting, .description_setting, .css_class_setting",
    "post_title" :  ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .admin_label_setting, .post_status_setting, .post_category_setting, .post_author_setting, .label_setting, .size_setting, .rules_setting, .default_value_setting, .description_setting, .css_class_setting",
    "post_content" :  ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .admin_label_setting, .post_status_setting, .post_category_setting, .post_author_setting, .label_setting, .size_setting, .rules_setting, .default_value_textarea_setting, .description_setting, .css_class_setting",
    "post_excerpt" :  ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .admin_label_setting, .post_status_setting, .post_category_setting, .post_author_setting, .label_setting, .size_setting, .rules_setting, .default_value_textarea_setting, .description_setting, .css_class_setting",
    "post_tags" :     ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .admin_label_setting, .label_setting, .size_setting, .rules_setting, .default_value_setting, .description_setting, .css_class_setting",
    "post_category" : ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .admin_label_setting, .post_category_checkbox_setting, .label_setting, .size_setting, .rules_setting, .duplicate_setting, .description_setting, .css_class_setting",
    "post_image" :    ".conditional_logic_field_setting, .error_message_setting, .admin_label_setting, .post_image_setting, .label_setting, .rules_setting, .description_setting, .css_class_setting",
    "post_custom_field" : ".conditional_logic_field_setting, .prepopulate_field_setting, .error_message_setting, .post_custom_field_setting, .post_custom_field_type_setting, .label_setting, .size_setting, .rules_setting, .visibility_setting, .duplicate_setting, .default_value_setting, .description_setting, .css_class_setting",
    "captcha" :     ".conditional_logic_field_setting, .captcha_language_setting, .captcha_theme_setting, .error_message_setting, .label_setting, .description_setting, .css_class_setting"
}

