<?php
$custom_fields = [];

$mailboxid = (int) $_POST['mailboxid'];
$ticketfields = [];

// $data = get_option($this->ticket_fields_key);

$data = $this->ticket_fields_data;


wp_send_json_success($data);
