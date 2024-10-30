<?php

$result = array();

/* Check if user has admin capabilities */
if(current_user_can('manage_options')){


    if(isset($_REQUEST['enable_email_to_staff_on_customer_reply'])){
        $this->base_admin->settings->updateSettings("enable_email_to_staff_on_customer_reply", sanitize_text_field($_REQUEST['enable_email_to_staff_on_customer_reply']));
    }
    if(isset($_REQUEST['email_to_staff_on_customer_reply_subject'])){
        $this->base_admin->settings->updateSettings("email_to_staff_on_customer_reply_subject", wp_filter_post_kses($_REQUEST['email_to_staff_on_customer_reply_subject']));
    }
    if(isset($_REQUEST['email_to_staff_on_customer_reply_body'])){
        $this->base_admin->settings->updateSettings("email_to_staff_on_customer_reply_body", wp_filter_post_kses($_REQUEST['email_to_staff_on_customer_reply_body']));
    }


    if(isset($_REQUEST['enable_email_to_customer_on_staff_reply'])){
        $this->base_admin->settings->updateSettings("enable_email_to_customer_on_staff_reply", sanitize_text_field($_REQUEST['enable_email_to_customer_on_staff_reply']));
    }
    if(isset($_REQUEST['email_to_customer_on_staff_reply_subject'])){
        $this->base_admin->settings->updateSettings("email_to_customer_on_staff_reply_subject", wp_filter_post_kses($_REQUEST['email_to_customer_on_staff_reply_subject']));
    }
    if(isset($_REQUEST['email_to_customer_on_staff_reply_body'])){
        $this->base_admin->settings->updateSettings("email_to_customer_on_staff_reply_body", wp_filter_post_kses($_REQUEST['email_to_customer_on_staff_reply_body']));
    }



    if(isset($_REQUEST['enable_email_to_admin_on_ticket_created'])){
        $this->base_admin->settings->updateSettings("enable_email_to_admin_on_ticket_created", sanitize_text_field($_REQUEST['enable_email_to_admin_on_ticket_created']));
    }
    if(isset($_REQUEST['email_to_admin_on_ticket_created_subject'])){
        $this->base_admin->settings->updateSettings("email_to_admin_on_ticket_created_subject", wp_filter_post_kses($_REQUEST['email_to_admin_on_ticket_created_subject']));
    }
    if(isset($_REQUEST['email_to_admin_on_ticket_created_body'])){
        $this->base_admin->settings->updateSettings("email_to_admin_on_ticket_created_body", wp_filter_post_kses($_REQUEST['email_to_admin_on_ticket_created_body']));
    }


    $result = array("status" => "true");

}else{
    $result = array("status" => 'false');
}

echo json_encode($result,  JSON_UNESCAPED_UNICODE);