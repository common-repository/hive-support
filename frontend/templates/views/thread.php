<div id="hive_lite_support_client_thread">


    <div class="hive_lite_support_client_thread_header">
        <h4>Support Portal</h4>
        <button class="show_tickets" onclick="hive_lite_support_client_show_tickets()">All Tickets</button>
    </div>


    <div class="hive_lite_support_client_form_body hive_lite_support_thread_reply_form">

        <div class="hive_lite_support_single_form_element hive_lite_support_thread_reply_msg_field">
            <label class="hive_lite_support_element_label">Message</label>
            <div class="hive_lite_support_element_field_main">
                <textarea rows="4" onkeyup="hive_lite_support_field_remove_errors(this)" required></textarea>
            </div>
        </div>


        <div class="hive_lite_support_single_form_element" data-field_slug="submit_btn">
            <div class="custom_btn_container">
                <button class="reply_close" onclick="hive_lite_support_client_thread_reply_submit(this, `reply_close`)">Reply & Close</button>
                <button class="reply" onclick="hive_lite_support_client_thread_reply_submit(this, `reply`)">Reply</button>
            </div>
        </div>

    </div>


    <div class="hive_lite_support_client_thread_items">


    </div>

</div>