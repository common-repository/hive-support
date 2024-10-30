<div id="hive_lite_support_agents">


    <div class="hive_lite_support_agents_update_popup">
        <div class="hive_lite_support_agents_update_popup_dark_bg"></div>
        <div class="hive_lite_support_agents_update">
            <div class="hive_lite_support_agents_update_header">
                <div>
                    <h3> Update Support Staff </h3>
                    <p> Update basic information and access permissions of a Support Staff </p>
                </div>
                <span class="hive_lite_support_agents_update_close_icon" onclick="hive_lite_support_agents_update_staff_popup_close()"></span>
            </div>

            <div class="hive_lite_support_agents_update_form">
                <div class="hive_lite_support_agents_update_form_single_field hive_lite_support_agents_update_form_title">
                    <input type="text" placeholder="Job Title (Ex. Developer, Support Staff)">
                </div>
                <div class="hive_lite_support_agents_update_form_single_field hive_lite_support_agents_update_form_first_name">
                    <input type="text" placeholder="First Name">
                </div>
                <div class="hive_lite_support_agents_update_form_single_field hive_lite_support_agents_update_form_last_name">
                    <input type="text" placeholder="Last Name">
                </div>

                <div class="hive_lite_support_agents_update_form_single_field hive_lite_support_agents_update_form_permissions" style="margin-top: 22px;">
                    <label class="hive_lite_support_agents_element_label">Access Permissions</label>
                    <div class="checkbox_container horizontal">
                        <label class="checkbox_item permission_manage_all_tickets">Tickets<input type="checkbox" value="manage_all_tickets"><span class="checkmark"></span></label>
                        <label class="checkbox_item permission_manage_responses">Responses<input type="checkbox" value="manage_responses"><span class="checkmark"></span></label>
                        <label class="checkbox_item permission_manage_agents">Agents<input type="checkbox" value="manage_agents"><span class="checkmark"></span></label>
                        <label class="checkbox_item permission_access_activities">Activities<input type="checkbox" value="access_activities"><span class="checkmark"></span></label>
                        <label class="checkbox_item permission_access_reports">Reports<input type="checkbox" value="access_reports"><span class="checkmark"></span></label>
                        <label class="checkbox_item permission_modify_automation">Automation<input type="checkbox" value="modify_automation"><span class="checkmark"></span></label>
                        <label class="checkbox_item permission_modify_settings">Settings<input type="checkbox" value="modify_settings"><span class="checkmark"></span></label>
                    </div>
                </div>

                <div class="hive_lite_support_agents_update_actions">
                    <button>Update Staff</button>
                </div>
            </div>
        </div>
    </div>




    <div class="hive_lite_support_agents_add">
        <h3>X Add on Support Staff </h3>
        <p> To manage support tickets created by customers you can add support staffs </p>
        <div class="hive_lite_support_agents_add_error"></div>
        <div class="hive_lite_support_agents_add_form">
            <div class="hive_lite_support_agents_add_form_single_field hive_lite_support_agents_add_form_title">
                <input type="text" placeholder="Job Title (Ex. Developer, Support Staff)">
            </div>
            <div class="hive_lite_support_agents_add_form_single_field hive_lite_support_agents_add_form_first_name">
                <input type="text" placeholder="First Name">
            </div>
            <div class="hive_lite_support_agents_add_form_single_field hive_lite_support_agents_add_form_last_name">
                <input type="text" placeholder="Last Name">
            </div>
            <div class="hive_lite_support_agents_add_form_single_field hive_lite_support_agents_add_form_email">
                <input type="email" placeholder="Email Address">
            </div>
            <div class="hive_lite_support_agents_add_form_single_field hive_lite_support_agents_add_form_password">
                <input type="password" placeholder="Password (For new users only)">
            </div>

            <div class="hive_lite_support_agents_add_form_single_field hive_lite_support_agents_add_form_permissions" style="margin-top: 22px;">
                <label class="hive_lite_support_agents_element_label">Access Permissions</label>
                <div class="checkbox_container horizontal">
                    <label class="checkbox_item permission_manage_all_tickets">Tickets<input type="checkbox" value="manage_all_tickets"><span class="checkmark"></span></label>
                    <label class="checkbox_item permission_manage_responses">Responses<input type="checkbox" value="manage_responses"><span class="checkmark"></span></label>
                    <label class="checkbox_item permission_manage_agents">Agents<input type="checkbox" value="manage_agents"><span class="checkmark"></span></label>
                    <label class="checkbox_item permission_access_activities">Activities<input type="checkbox" value="access_activities"><span class="checkmark"></span></label>
                    <label class="checkbox_item permission_access_reports">Reports<input type="checkbox" value="access_reports"><span class="checkmark"></span></label>
                    <label class="checkbox_item permission_modify_automation">Automation<input type="checkbox" value="modify_automation"><span class="checkmark"></span></label>
                    <label class="checkbox_item permission_modify_settings">Settings<input type="checkbox" value="modify_settings"><span class="checkmark"></span></label>
                </div>
            </div>

            <div class="hive_lite_support_agents_add_actions">
                <button onclick="hive_lite_support_agents_add_staff(`<?php echo esc_url(HIVE_LITE_SUPPORT_URL); ?>`)">Add Staff</button>
            </div>
        </div>
    </div>


    <div class="hive_lite_support_agents_container">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Assigned</th>
                    <th>Solved</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

            </tbody>
        </table>
    </div>


</div>