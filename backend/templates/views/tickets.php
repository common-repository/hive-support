<div id="hive_lite_support_tickets">


    <div class="hive_lite_support_tickets_summary">
        <div class="hive_lite_support_tickets_summary_item">
            <p>Total Tickets</p>
            <h3 class="total_tickets_count">0</h3>
        </div>
        <div class="hive_lite_support_tickets_summary_item">
            <p>Waiting Tickets</p>
            <h3 class="waiting_tickets_count">0</h3>
        </div>
        <div class="hive_lite_support_tickets_summary_item">
            <p>Open Tickets</p>
            <h3 class="open_tickets_count">0</h3>
        </div>
        <div class="hive_lite_support_tickets_summary_item">
            <p>Resolved Tickets</p>
            <h3 class="close_tickets_count">0</h3>
        </div>
    </div>
    <div class="hive_lite_support_ticket_filter_container">
        <div class="hive_lite_support_ticket_filter_items">
            <label class="hive_lite_support_ticket_filter_items_label"> Status </label>
            <select class="hive_lite_support_ticket_filter_items_dropdown" id="hive_lite_support_ticket_filter_status" onchange="hive_lite_support_tickets_filter_update()">
                <option value="">Select Status</option>
                <option value="Open">Open</option>
                <option value="Waiting">Waiting</option>
                <option value="Close">Close</option>
            </select>

        </div>
        <div class="hive_lite_support_ticket_filter_items">
            <label class="hive_lite_support_ticket_filter_items_label"> Priority </label>
            <select class="hive_lite_support_ticket_filter_items_dropdown" id="hive_lite_support_ticket_filter_priority" onchange="hive_lite_support_tickets_filter_update()">
                <option value="">Select Priority</option>
                <option value="Normal">Normal</option>
                <option value="High">High</option>
                <option value="Urgent">Urgent</option>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="Critical">Critical</option>
            </select>
        </div>
        <div class="hive_lite_support_ticket_filter_items">
            <label class="hive_lite_support_ticket_filter_items_label"> Assign </label>
            <select class="hive_lite_support_ticket_filter_items_dropdown" id="hive_lite_support_ticket_filter_assign" onchange="hive_lite_support_tickets_filter_update()"></select>
        </div>
        <div class="hive_lite_support_ticket_filter_items">
            <label class="hive_lite_support_ticket_filter_items_label"> Customer Name </label>
            <select class="hive_lite_support_ticket_filter_items_dropdown" id="hive_lite_support_ticket_filter_customers" onchange="hive_lite_support_tickets_filter_update()"></select>
        </div>
        <div class="hive_lite_support_ticket_filter_items">
            <div class="hive_lite_support_ticket_filter_btn_wrapper">
                <button class="hive_lite_support_ticket_filter_btn" onclick="hive_lite_support_tickets_filter_reset()"> Reset
                    Filter </button>
            </div>
        </div>

    </div>


    <div class="hive_lite_support_tickets_container">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Assign</th>
                    <th>Update</th>
                    <th>Created</th>
                </tr>
            </thead>

            <tbody>
                <!-- <tr>
                    <td>
                        <input type="checkbox" name="" id="">
                    </td>
                    <td>
                        <div class="user-desc-container">
                            <img src="https://placehold.co/400" alt="">
                            <h3 class="ticket-title">
                                Produic is not Recieved #233
                            </h3>
                            <p>I orderd but not recived</p>
                            <h4>User Name</h4>
                        </div>
                    </td>
                    <td>
                        tgytyyty
                    </td>
                    <td>fsdfdef</td>
                    <td>fsdfdef</td>
                </tr> -->

                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Assign</th>
                    <th>Update</th>
                    <th>Created</th>
                </tr>
            </tbody>
        </table>
    </div>


    <div class="hive_lite_support_tickets_empty">
        You have received no support tickets.
    </div>
    <?php
    global $wpdb;
    $ticket_table = $wpdb->prefix . 'hs_tickets';
    $tickets = $wpdb->get_results(
        "SELECT * FROM $ticket_table"
    );


    foreach ($tickets as $ticket) {

        echo "<pre>";
        print_r($ticket->title);
        echo "</pre>";
    }

    ?>


</div>