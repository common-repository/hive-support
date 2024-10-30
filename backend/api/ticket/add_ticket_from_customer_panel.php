<?php
global $wpdb;
$ticket_table = $wpdb->prefix . 'hs_tickets';


$subject = $this->get_form_value_by_key($this->title_key);
$message = $this->get_form_value_by_key($this->message_key);
$priority = $this->get_form_value_by_key($this->priority_key);

//

$files_url = [];
if (isset($_FILES['files'])) {

    $allfiles = $_FILES['files'];

    foreach ($allfiles['tmp_name'] as $index => $tmp_name) {
        $filename = sanitize_file_name($allfiles['name'][$index]);
        $filenamexten = str_replace(".jpg", "", $filename); // Remove ".jpg" extension
        $trimfilename = ucwords(preg_replace('/[-_]/', ' ', $filenamexten));
        $uniquename = wp_unique_filename(wp_upload_dir()['path'], $filename);
        $uploads = move_uploaded_file($tmp_name, wp_upload_dir()['path'] . '/' . $uniquename);
        if ($uploads) {
            $file_url = wp_upload_dir()['url'] . '/' . $uniquename;
            $files_url[] = [
                "filepath" => $file_url,
                "filename" => $trimfilename
            ];
        }
    }
}


//Get the mailbox id from request body

$mailbox_id = $_POST['mailbox'];

$adminUtl = admin_url('admin.php');
// Unset the action and mailobox from $_POST 

unset($_POST['action']);
unset($_POST['mailbox']);

$ticket_fields = array();

foreach ($_POST as $key => $data) {

    $data_array = json_decode(stripslashes($data), true);
    $new_array = array(
        "metakey" => $key,
        "value" => $data_array['fieldvalue'],
        "label" => $data_array['fieldlabel']
    );

    array_push($ticket_fields, $new_array);
}

$data = array(
    "subject" => $subject,
    "message" => $message,
    "priority" => $priority,
    "mailbox_id" => $mailbox_id,
    'ticket_fields' => $ticket_fields,
    'ticket_fields_json' => json_encode($ticket_fields),
);

$hash = md5("This is test text for md5 encrypting");
$content_hash = md5($subject);
$slug = sanitize_title( $subject );


$current_user = get_current_user_id();
$author_obj = get_user_by('id', $current_user);
$current_user_name = $author_obj->user_login;
$current_user_email = $author_obj->user_email;
$time = current_time('Y-m-d H:i:s');
$ticket_values = array(
    absint( $current_user ),
    absint( $mailbox_id ),
    sanitize_text_field( $subject ),
    sanitize_textarea_field( $message ),
    sanitize_text_field( $priority ),
    'WEB',
    $hash,
    $slug,
    json_encode($ticket_fields),
    $content_hash,
    stripslashes(wp_json_encode($files_url)),
    sanitize_text_field( $current_user_name ),
    sanitize_email( $current_user_email ),
    sanitize_text_field($time),
    sanitize_text_field($time)
);

$cols = 'customer_id,mailbox_id,title,content,priority,source,hash,slug,ticket_fields,content_hash,media_urls,customer_name,customer_email,created_at,updated_at';

$formats = '%d,%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s';
$sql = "INSERT INTO  $ticket_table ({$cols}) VALUES  ({$formats})";

$ticket_created = $wpdb->query(
    $wpdb->prepare($sql, $ticket_values)
);


$ticket_insertion_id = $wpdb->insert_id;

if ($ticket_insertion_id) {
    $data['notice_type'] = 'success';
} else {
    $data['notice_type'] = 'fail';
}

// Action after ticekt create
do_action('hs_ticket_created', $ticket_insertion_id, $subject, $message, $priority, $mailbox_id);

$ticket_data = [
    'ticket_insertion_id' => $ticket_insertion_id,
    'subject' => $subject,
    'message' => $message,
    //'media_urls' => stripslashes(wp_json_encode($files_url)),
    'priority' => $priority,
    'mailbox_id' => $mailbox_id,
    'customer_id' => $current_user,
    'customer_name' => $current_user_name,
];

do_action('hs_ticket_created_by_customer', $ticket_data);

$ticket_id = [
    'id' => $ticket_insertion_id
];
$ticket_data = $ticket_id + $ticket_id;

// do_action('hs_after_ticket_created', $ticket_data);

$ac_return = HiveSupportUtils::add_activities("New ticket created by admin", $current_user, "Customer", $ticket_insertion_id);

$data['ticket_id'] = $ticket_insertion_id;
$data['tr'] = $ac_return;

wp_send_json_success($data);
