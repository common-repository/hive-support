<?php


class HiveSupportEmails
{

    public $emails = false;
    public $automations;
    public $email_templates_key = 'hs_email_templates';
    public $all_templates;

    function __construct()
    {
        // Get all templates
        $this->all_templates = $this->emailTemplates();
        if (!$this->checkTemplateStatus()) return;
        $this->hasTask();
    }
    function hasTask()
    {

        // Actions for necessary events to send emails
        if (is_array($this->all_templates) and count($this->all_templates) > 0) {
            foreach ($this->all_templates as $template) {
                if ($template['status'] == 'on') {
                    if ($template['name_id'] == 'to_admin_on_assign') { // -- done
                        // To Agent -> Manual assignment, Automation Assignmnet
                        add_action('hs_ticket_assigned', [$this, 'ticketAssigned']);
                    } else if ($template['name_id'] == 'to_admin_on_create') { // --done
                        // Ticket created by cudomer only
                        add_action('hs_ticket_created_by_customer', [$this, 'ticketCreatedByCustomer']);
                    } else if ($template['name_id'] == 'to_admin_on_creply') { // --

                        // To agent(admin if no agent)
                        add_action('hs_ticket_cresponse_added', [$this, 'customerRepliedToTicket']);
                    } else if ($template['name_id'] == 'to_customer_on_create') {
                        // To customer on admin create
                        add_action('hs_ticket_created_by_agent', [$this, 'ticketCreatedByAgent']);
                    } else if ($template['name_id'] == 'to_customer_on_closed') {
                        // To customer on ticket closed by admin/agnet
                        add_action('hs_ticket_closed_by_customer', [$this, 'ticketClosedByCustomer']);
                    } else if ($template['name_id'] == 'to_customer_on_areply') {
                        // To customer on agent/admin reply
                        add_action('hs_ticket_aresponse_added', [$this, 'adminRepliedToTicket']);
                    }
                }
            }
        }
    }

    function adminRepliedToTicket($ticket_data)
    {

        $ticket_id = isset($ticket_data['ticket_id']) ? $ticket_data['ticket_id'] : '';

        $replied_text = isset($ticket_data['replied_text']) ? $ticket_data['replied_text'] : '';

        $this_ticket_data = $this->get_ticket_by_id($ticket_id);

        $subject = $this_ticket_data['title'];

        $agent_id = isset($this_ticket_data['agent_id']) ? $this_ticket_data['agent_id'] : '';
        $customer_id = isset($this_ticket_data['customer_id']) ? $this_ticket_data['customer_id'] : '';

        $agent_id = isset($this_ticket_data['agent_id']) ? $this_ticket_data['agent_id'] : '';
        $agent_email = $this->getUserEmailById($agent_id);

        $customer_email = $this->getUserEmailById($customer_id);
        if (!is_email($customer_email)) return;

        // $message = $this_ticket_data['content'];

        $data_to_replace = [];
        $data_to_replace['ticket_track_id'] = $ticket_id;
        $data_to_replace['ticket_title'] = $subject;
        // $data_to_replace['ticket_body'] = $message;

        $data_to_replace['replied_text'] = $replied_text;

        $template = $this->findTemplate('to_customer_on_areply');
        $mail_subject = $template['subject'];
        $mail_content = $template['content'];
        $mail_subject = $this->updateDynamicCodes($mail_subject, $data_to_replace, $this_ticket_data);
        $mail_content = $this->updateDynamicCodes($mail_content, $data_to_replace, $this_ticket_data);

        $toEmail = $customer_email;
        // $reply_to = get_option('admin_email');
        $reply_to = $agent_email;

        $this->SendEmailTemplates($toEmail, $mail_subject, $mail_content, $reply_to);
    }
    function customerRepliedToTicket($ticket_data)
    {
        
        $ticket_id = isset($ticket_data['ticket_id']) ? $ticket_data['ticket_id'] : '';
        $customer_id = isset($ticket_data['customer_id']) ? $ticket_data['customer_id'] : '';
        $replied_text = isset($ticket_data['response_text']) ? $ticket_data['response_text'] : '';

        $this_ticket_data = $this->get_ticket_by_id($ticket_id);

        $subject = $this_ticket_data['title'];

        $customer_email = $this->getUserEmailById($customer_id);

        $agent_id = isset($this_ticket_data['agent_id']) ? $this_ticket_data['agent_id'] : '';
        
        $agent_email = $this->getUserEmailById($agent_id) ? $this->getUserEmailById($agent_id) : get_bloginfo('admin_email');
        
        if (!is_email($agent_email)) return;

        // $message = $this_ticket_data['content'];

        $data_to_replace = [];
        $data_to_replace['ticket_track_id'] = $ticket_id;
        $data_to_replace['ticket_title'] = $subject;
        // $data_to_replace['ticket_body'] = $message;

        $data_to_replace['replied_text'] = $replied_text;

        $template = $this->findTemplate('to_admin_on_creply');
        $mail_subject = $template['subject'];
        $mail_content = $template['content'];
        $mail_subject = $this->updateDynamicCodes($mail_subject, $data_to_replace, $this_ticket_data);
        $mail_content = $this->updateDynamicCodes($mail_content, $data_to_replace, $this_ticket_data);


        $toEmail = $agent_email;
        // $reply_to = get_option('admin_email');
        $reply_to = $customer_email;

        $this->SendEmailTemplates($toEmail, $mail_subject, $mail_content, $reply_to);
    }

    function ticketAssigned($ticket_data)
    {


        $ticket_id = isset($ticket_data['ticket_id']) ? $ticket_data['ticket_id'] : '';
        $agent_id = isset($ticket_data['agent_id']) ? $ticket_data['agent_id'] : '';

        $template = $this->findTemplate('to_admin_on_assign');
        $mail_subject = $template['subject'];
        $mail_content = $template['content'];


        $this_ticket_data = $this->get_ticket_by_id($ticket_id);

        $data_to_replace = [];
        $data_to_replace['ticket_track_id'] = $ticket_id;
        $subject = $this_ticket_data['title'];
        $message = $this_ticket_data['content'];

        $data_to_replace['ticket_title'] = $subject;
        $data_to_replace['ticket_body'] = $message;

        $mail_subject = $this->updateDynamicCodes($mail_subject, $data_to_replace, $this_ticket_data);
        $mail_content = $this->updateDynamicCodes($mail_content, $data_to_replace, $this_ticket_data);

        $agent_email = $this->getUserEmailById($agent_id);
        $toEmail = $agent_email;
        $reply_to = get_option('admin_email');

        $this->SendEmailTemplates($toEmail, $mail_subject, $mail_content, $reply_to);
    }
    function updateDynamicCodes($content, $data_to_replace = [], $ticket_data = [])
    {

        $title = '';
        $the_content = '';
        $customer_name = '';
        $customer_email = '';
        $ticket_track_id = '';
        $replied_text = '';


        if ($data_to_replace) {
            if (isset($data_to_replace['ticket_title'])) {
                $title = $data_to_replace['ticket_title'];
            }
            if (isset($data_to_replace['ticket_body'])) {
                $the_content = $data_to_replace['ticket_body'];
            }
            if (isset($data_to_replace['customer_full_name'])) {
                $customer_name = $data_to_replace['customer_full_name'];
            }
            if (isset($data_to_replace['customer_email'])) {
                $customer_email = $data_to_replace['customer_email'];
            }
            if (isset($data_to_replace['ticket_track_id'])) {
                $ticket_track_id = $data_to_replace['ticket_track_id'];
            }
            if (isset($data_to_replace['replied_text'])) {
                $replied_text = $data_to_replace['replied_text'];
            }
        }
        
        $current_user = wp_get_current_user();

        $customer_email = !empty( $ticket_data['customer_id'] ) ? $this->getUserEmailById($ticket_data['customer_id']) : '';
        $customer_name = !empty( $ticket_data['customer_id'] ) ? $this->getUserNameById($ticket_data['customer_id']) : '';
        $agent_name = !empty( $ticket_data['agent_id'] ) ? $this->getUserNameById($ticket_data['agent_id']) : $current_user->display_name;

        $codes = [
            '{{site_url}}' => get_bloginfo('url'),
            '{{site_name}}' => get_bloginfo('name'),
            '{{ticket_title}}' => $title,
            '{{ticket_body}}' => $the_content,
            '{{customer_full_name}}' => $customer_name,
            '{{ticket_user}}' => $customer_name,
            '{{customer_email}}' => $customer_email,
            '{{ticket_track_id}}' => $ticket_track_id,
            '{{replied_text}}' => $replied_text,
            '{{ticket_replied_user}}' => $agent_name
        ];


        $search = [];
        $replace = [];
        foreach ($codes as $key => $value) {
            // $search[] = "{{" . $key . "}}";
            if ($value != '') {
                $search[] = $key;
                $replace[] = $value;
            }
        }
        $content = str_replace($search, $replace, $content);
        return $content;
    }

    function get_ticket_by_id($ticekt_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hs_tickets';

        $ticket = $wpdb->get_row("SELECT * FROM $table_name WHERE id = {$ticekt_id}", ARRAY_A);
        return $ticket;
    }

    function ticketCreatedByCustomer($ticket_data)
    {
        $template = $this->findTemplate('to_admin_on_create');
        $mail_subject = $template['subject'];
        $mail_content = $template['content'];

        $ticket_id = isset($ticket_data['ticket_insertion_id']) ? $ticket_data['ticket_insertion_id'] : '';
        $subject = isset($ticket_data['subject']) ? $ticket_data['subject'] : '';
        $message = isset($ticket_data['message']) ? $ticket_data['message'] : '';
        $mailbox_id = isset($ticket_data['mailbox_id']) ? $ticket_data['mailbox_id'] : '';
        $customer_id = isset($ticket_data['customer_id']) ? $ticket_data['customer_id'] : '';

        $customer_email = $this->getUserEmailById($customer_id);

        // $args = ['ticket_title', 'ticket_body', 'customer_full_name', 'customer_email', 'ticket_track_id'];
        $data_to_replace = [];
        $data_to_replace['ticket_track_id'] = $ticket_id;
        $data_to_replace['ticket_title'] = $subject;
        $data_to_replace['ticket_body'] = $message;
        $data_to_replace['customer_full_name'] = $this->getUserNameById($customer_id);
        $data_to_replace['customer_email'] = $customer_email;


        $mail_subject = $this->updateDynamicCodes($mail_subject, $data_to_replace, $ticket_data);
        $mail_content = $this->updateDynamicCodes($mail_content, $data_to_replace, $ticket_data);

        $mailboxData = !empty( $mailbox_id ) && $mailbox_id > 0 ? $this->get_active_email_by_mailbox_id($mailbox_id) : [];

        $toEmail = !empty( $mailboxData['admin_email'] ) ? $mailboxData['admin_email'] : get_bloginfo('admin_email');
        $reply_to = $customer_email;
        
        $this->SendEmailTemplates($toEmail, $mail_subject, $mail_content, $reply_to, $mailboxData );
    }
    
    function get_active_email_by_mailbox_id($mailbox_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hs_mailbox';

        $mailbox = $wpdb->get_row("SELECT * FROM $table_name WHERE id = {$mailbox_id}", ARRAY_A);

        return [
            'mailbox_title' => $mailbox['mailbox_title'] ?? '',
            'admin_email' => !empty($mailbox['admin_email']) && is_email($mailbox['admin_email']) ? $mailbox['admin_email'] : '',
            'support_from_email' => !empty($mailbox['support_from_email']) && is_email($mailbox['support_from_email']) ? $mailbox['support_from_email'] : ''
        ];
    }

    function emailsForticketCreated($ticket_data)
    {

        $template = $this->findTemplate('to_admin_on_create');

        $attachments = [];
        // Mail to Customer

        $toEmail = '';
        $content = '';
        $reply_to = '';
        $subject = '';

        $ticket_id = $ticket_data['id'];
        // Customer email
        if (isset($ticket_data['customer_id'])) {
            $id = $ticket_data['customer_id'];

            $customer_email = $this->getUserEmailById($id);
            $toEmail = $customer_email;
        }

        // Agent email
        $agentEmail = $this->getAgentEmailByTicket($ticket_id);
        if ($agentEmail) {
            $reply_to = $agentEmail;
        }

        $subject = $template['subject'];
        $content = $template['content'];

        // $this->SendEmailTemplates($toEmail,$subject,$content,$reply_to,$attachments);

        // Mail to Agent/Admin
        $this->SendEmailTemplates($toEmail, $subject, $content, $reply_to);
    }
    function getAgentEmailByTicket($ticket_id)
    {
        $email = '';

        global $wpdb;
        $ticket_table = $wpdb->prefix . 'hs_tickets';
        $agent_id = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT agent_id FROM $ticket_table WHERE id=%d",
                array($ticket_id)
            )
        );

        if ($agent_id) {
            $agent_id_int = (int)$agent_id[0]->agent_id;
            $agent_email = $this->getUserEmailById($agent_id_int);
            $email = $agent_email;
        }
        return $email;
    }
    function getUserEmailById($id)
    {
        $user_email = '';
        $user = get_user_by('id', $id);
        if ($user) {
            $user_email = $user->user_email;
        }
        return $user_email;
    }
    function getUserNameById($id)
    {
        $user = get_user_by('id', $id);
        $name = $user->first_name . ' ' . $user->last_name;
        if ($user->first_name = '' || $user->last_name == '' ) {
            $name = $user->user_nicename;
        }
        return $name;
    }
    
    function emailsForticketCreatedPrev($ticket_insertion_id, $subject, $message, $priority, $mailbox_id, $toEmail, $content)
    {

        // Mail to Customer
        $this->SendEmailTemplates($toEmail, $subject = "", $content, $reply_to = '', $mailboxData = [], $attachments = []);

        // Mail to Agent/Admin
        $this->SendEmailTemplates($toEmail, $subject = "", $content, $reply_to = '', $mailboxData = [], $attachments = []);
    }

    function SendEmailTemplates($toEmail, $subject, $content, $reply_to, $mailboxData = [], $attachments = [])
    {

        if (!$toEmail) return;
        $attachments = [];
        $headers = array('Content-Type: text/html; charset=UTF-8');
        if (!empty($reply_to)) {
            $headers[] = 'Reply-To: ' . $reply_to;
        }

        if( !empty( $mailboxData['mailbox_title'] ) && !empty( $mailboxData['support_from_email'] ) ) {
            $headers[] = 'From: '.esc_html( $mailboxData['mailbox_title'] ).' <'.esc_html( $mailboxData['support_from_email'] ).'>';
        }

        $mail_send = wp_mail($toEmail, $subject, $content, $headers, $attachments);

        return $mail_send;
    }
    function findTemplate($nameToFind)
    {

        foreach ($this->all_templates as $array) {
            if (isset($array['name_id']) && $array['name_id'] === $nameToFind) {
                return $array;
            }
        }
        return null; // Return null if the name is not found
    }
    function checkTemplateStatus()
    {
        $any_status = false;

        foreach ($this->all_templates as $array) {
            if (isset($array['status']) && $array['status'] === 'on') {
                $any_status = true;
                break;
            }
        }
        return $any_status;
    }
    function defaultTemplates()
    {
        $templates = [];

        $name_ids = [
            'to_admin_on_assign',
            'to_admin_on_create',
            'to_admin_on_creply',
            'to_customer_on_closed',
            'to_customer_on_create',
            'to_customer_on_areply',
        ];

        $to_admin = [];
        // Ticket Assigned

        $item1 = [
            'id' => 1,
            'name_id' => 'to_admin_on_assign',
            'title' => 'Ticket Assigned (To Agent)',
            'status' => 'on',
            'subject' => 'Ticket Assigned: {{ticket_title}} #{{ticket_track_id}}',
            'content' => 'A ticket {{ticket_title}} has been assigned to you

            Ticket Body:
            
            {{ticket_body}}',
        ];

        // Ticket Created
        $item2 = [
            'id' => 2,
            'name_id' => 'to_admin_on_create',
            'title' => 'Ticket Created (To Admin/Agent)',
            'status' => 'on',
            'subject' => 'New Ticket: {{ticket_title}} #{{ticket_track_id}}',
            'content' => 'A new ticket {{ticket_title}} has been submitted by {{ticket_user}}

            Ticket Body:
            {{ticket_body}}',
        ];
        // Ticket Replied
        $item3 = [
            'id' => 3,
            'name_id' => 'to_admin_on_creply',
            'title' => 'Replied by Customer (To Admin/Agent)',
            'status' => 'on',
            'subject' => 'New Response: {{ticket_title}} #{{ticket_track_id}}',
            'content' => 'A new response has been added to ticket {{ticket_title}} by {{ticket_user}}

            Ticket Reply Text:
            
            {{replied_text}}
            View Ticket
            Thanks
            {{site_name}}',
        ];

        $to_admin = [$item1, $item2, $item3];

        $to_customer = [];
        // Ticket Created
        $item1 = [
            'id' => 4,
            'name_id' => 'to_customer_on_create',
            'title' => 'Ticket Created by Agent (To Customer)',
            'status' => '',
            'subject' => 'Re: {{ticket_title}} #{{ticket_track_id}}',
            'content' => 'Dear {{ticket_user}},

            Your request (#{{ticket_track_id}}) has been received and is being reviewed by our support staff. You will receive a response as soon as possible. To add additional comments, follow the link below:
            
            {{view_ticket_anchor}}
            
            Thanks,
            {{site_name}}',
        ];

        // Ticket CLosed
        $item2 = [
            'id' => 5,
            'name_id' => 'to_customer_on_closed',
            'title' => 'Ticket Closed by Agent (To Customer)',
            'status' => '',
            'subject' => 'Re: {{ticket_title}} #{{ticket_track_id}}',
            'content' => 'Dear {{ticket_user}},

            Your ticket (#{{ticket_track_id}}) has been closed.
            
            We hope that the ticket was resolved to your satisfaction. Please reply to this email if you believe that the ticket should not be closed or if it has not been resolved.
            
            Thanks,
            {{site_name}}',
        ];
        // Ticket Replied
        $item3 = [
            'id' => 6,
            'name_id' => 'to_customer_on_areply',
            'title' => 'Ticket Replied by Agent (To Customer)',
            'status' => 'on',
            'subject' => 'Re: {{ticket_title}} #{{ticket_track_id}}',
            'content' => 'Dear {{ticket_user}},

            One of our team members just replied to your ticket (#{{ticket_track_id}}).
            
            You can follow the link below to add comments.
            
            View Ticket
            
            Thanks,
            {{ticket_replied_user}}
            {{site_name}}',
        ];
        $to_customer = [$item1, $item2, $item3];

        $templates = array_merge($to_admin, $to_customer);
        
        return $templates;
    }
    function emailTemplates()
    {
        $getTemplates = get_option($this->email_templates_key);
        if ($getTemplates) {
            $templates = json_decode(stripslashes($getTemplates), true);
        } else {
            $templates = $this->defaultTemplates();
        }

        return $templates;
    }

    static  function SendEmailTemplatesGenix($keyword, $toEmail, $params = array(), $subject = "", $reply_to = '', $attachments = [])
    {
        if (empty($toEmail)) {
            return true;
        }
        $obj = self::FindBy("k_word", $keyword);
        if (!empty($obj)) {
            if ($obj->status != "A") {
                return true;
            }
        }
        if (!isset($params["site_name"])) {
            $params["site_name"] = get_bloginfo('name');
        }
        if (!isset($params["site_url"])) {
            $params["site_url"] = get_bloginfo('url');
        }
        $search = array();
        $replace = array();
        foreach ($params as $key => $value) {
            $search[] = "{{" . $key . "}}";
            $replace[] = $value;
        }
        $content = str_replace($search, $replace, $obj->content);
        if (in_array($keyword, ['UOT', 'TRO', 'TRR', 'TAC', 'EOT', 'ETR']) && !empty($params["ticket_track_id"]) && !empty($params["ticket_title"])) {
            $content = self::getTicketEmailText($params["ticket_track_id"], $content, $params["ticket_title"]);
        }
        if (empty($subject)) {
            $subject = $obj->subject;
        }
        $subject = str_replace($search, $replace, $subject);
        $headers = array('Content-Type: text/html; charset=UTF-8');
        if (!empty($reply_to)) {
            $headers[] = 'Reply-To: ' . $reply_to;
        }
        if (!wp_mail($toEmail, $subject, $content, $headers, $attachments)) {
            return false;
        } else {
            return true;
        }
    }
}

add_action('init', 'call_my_email_notification');

function call_my_email_notification()
{
    $cutepie = new HiveSupportEmails();
}
