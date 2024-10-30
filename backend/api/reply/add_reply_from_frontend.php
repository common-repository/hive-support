<?php

global $wpdb;
$files_url = [];
$replaymessage = isset($_POST['replaymessage']) ? $_POST['replaymessage'] : '';
$personid = $_POST['person_id'];
$ticketid = $_POST['ticketid'];
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
            $files_url[] = array(
                "filepath" => $file_url,
                "filename" => $trimfilename
            );
        }
    }
}


$timestamps = current_time('Y-m-d H:i:s');

//$stringurls = implode("__-", $files_url);
$contenthash = md5("Reply Hive Support");
$conversation_table = $wpdb->prefix . 'hs_conversations';
$wpdb->query(
    $wpdb->prepare(
        "INSERT INTO $conversation_table (ticket_id,person_id,content,media_urls,content_hash,created_at) VALUES(
                %d,%d,%s,%s,%s,%s
            )",
        array(
            $ticketid,
            $personid,
            $replaymessage,
            json_encode($files_url),
            $contenthash,
            $timestamps
        )
    )
);

// Check if successfully added
$ticket_data = [];
$ticket_data['ticket_id'] = $ticketid;
$ticket_data['customer_id'] = $personid;
$ticket_data['response_text'] = $replaymessage;


do_action('hs_ticket_cresponse_added', $ticket_data);

// update ticket update time and ticket seen status
$table_name = $wpdb->prefix . 'hs_tickets';
$wpdb->update(
    $table_name,
    array(
        'updated_at' => $timestamps,
        'seen_status' => 'new_reply'
    ),
    array( 'id' => absint( $ticketid ) )
);


// Add Activities
$ac_return = HiveSupportUtils::add_activities("New Reply added", $personid, "Customer", $ticketid);

$mesage_id = '';
$thread_ts = '';

//
wp_send_json_success(
    array(
        "urls" => $files_url,
        "replaymessage" => $thread_ts,
        "msg" => $mesage_id
    )
);
wp_die();
