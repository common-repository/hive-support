<div id="hive_lite_support_thread">



    <div class="hive_lite_support_thread_header">
        <img class="hive_lite_support_thread_center_controll" onclick="hive_lite_support_thread_center_controll(this)" />
        <h3 onclick="hive_lite_support_tickets_init()">All Tickets</h3>
        <img class="hive_lite_support_thread_reload" onclick="hive_lite_support_thread_reload_page()" />
    </div>


    <div class="hive_lite_support_thread_sections_container">

        <div class="hive_lite_support_thread_section_left leftrighthidden">

            <div class="hive_lite_support_thread_section_stats">
                <div class="hive_lite_support_thread_section_left_item">
                    <label>Status</label>
                    <select class="status_icon hive_lite_support_thread_status" onchange="hive_lite_support_thread_property_update()">
                        <option value="open">Open</option>
                        <option value="waiting">Waiting</option>
                        <option value="close">Close</option>
                    </select>
                </div>
                <div class="hive_lite_support_thread_section_left_item">
                    <label>Assign</label>
                    <select class="user_icon hive_lite_support_thread_agents" onchange="hive_lite_support_thread_property_update()"></select>
                </div>
                <div class="hive_lite_support_thread_section_left_item">
                    <label>Last Update</label>
                    <input disabled type="text" class="ticket_updated hive_lite_support_thread_last_update" value="3d Ago">
                </div>
                <div class="hive_lite_support_thread_section_left_item">
                    <label>Creation Date</label>
                    <input disabled type="text" class="ticket_created hive_lite_support_thread_created_at" value="3d Ago">
                </div>
            </div>

        </div>


        <div class="hive_lite_support_thread_section_center">
            <div class="hive_lite_support_thread_section_center_header">
                <h4 class="hive_lite_support_thread_subject">Why it’s important to install wpplugin?<span>#233</span></h4>
            </div>

            <div class="hive_lite_support_thread_section_center_thread_form">
                <label>Reply with</label>
                <div class="hive_lite_support_thread_section_center_thread_form_actions">
                    <button type="button" class="button add_media" onclick="hive_lite_support_thread_add_wp_media(this)"><span class="wp-media-button-icon"></span> Add media</button>
                </div>
                <textarea rows="7" onkeyup="hive_lite_support_thread_reply_remove_errors(this)" id="hive_lite_support_thread_ticket_reply_field"></textarea>
                <div class="hive_lite_support_thread_section_center_thread_form_actions">
                    <button class="reply" onclick="hive_lite_support_thread_reply_submit(this, `reply`)">Reply</button>
                    <button class="reply_close" onclick="hive_lite_support_thread_reply_submit(this, `reply_close`)">Reply & Close</button>
                    <button class="ticket_close" onclick="hive_lite_support_thread_reply_submit(this, `close`)">Close</button>
                </div>
                <div class="hive_lite_support_thread_section_center_thread_form_media">
                    <button class="file_upload" onclick="hive_lite_support_thread_doc_upload(this)">Click to Upload</button>
                </div>
                <div class="hive_lite_support_thread_section_center_thread_form_media_upload_area">
                    <ul class="image-list" id="hive_lite_support_thread_section_center_thread_form_media_upload_area_container">

                    </ul>
                </div>
            </div>


            <div class="hive_lite_support_thread_section_center_thread_items">


            </div>
        </div>



        <div class="hive_lite_support_thread_section_right leftrighthidden">
            <div class="hive_lite_support_thread_section_user_profile">
                <img class="hive_lite_support_thread_customer_img" src="">
                <h5 class="hive_lite_support_thread_customer_name"></h5>
                <span class="email hive_lite_support_thread_customer_email"></span>
            </div>
            <div class="hive_lite_support_thread_section_user_profile hive_lite_support_thread_section_user_order_container">
                <h5>WooCommerce Purchases</h5>
                <div class="hive_lite_support_thread_section_user_profile_order" style="display: block;">
                    <div class="hive_lite_support_thread_section_user_profile_order_item">
                        <a target="_blank" href="https://localhost/wordpress/wp-admin/post.php?post=1356&amp;action=edit">#1356</a> on <span class="hive_lite_support_thread_order_date">18 Feb 2023</span>
                        <span class="hive_lite_support_thread_order_status">[on-hold]</span>
                    </div>
                </div>
            </div>

            <div class="hive_lite_support_thread_section_user_profile hive_lite_support_thread_section_user_tickets_container">
                <h5>Previous Conversations</h5>
                <div class="hive_lite_support_thread_section_user_previous_ticket" style="display: block;">
                    <div class="hive_lite_support_thread_section_user_previous_ticket_item">
                        <a target="_blank" href="https://localhost/wordpress/wp-admin/post.php?post=1356&amp;action=edit">#1356</a> on <span class="hive_lite_support_thread_order_date">18 Feb 2023</span>
                        <span class="hive_lite_support_thread_order_status">[on-hold]</span>
                    </div>
                </div>
            </div>

            <div class="hive_lite_support_thread_right_tab">
                <div class="hive_lite_support_thread_right_tab_menu_container">
                    <div class="hive_lite_support_thread_right_tab_menu active" data-slug="templates" onclick="hive_lite_support_thread_right_tab_menu_click(this)"> Templates </div>
                    <div class="hive_lite_support_thread_right_tab_menu" data-slug="activities" onclick="hive_lite_support_thread_right_tab_menu_click(this)"> Activities </div>
                    <div class="hive_lite_support_thread_right_tab_menu" data-slug="todo" onclick="hive_lite_support_thread_right_tab_menu_click(this)"> To Do </div>
                </div>
                <div class="hive_lite_support_thread_right_tab_body" data-slug="templates">
                    <div class="hive_lite_support_thread_right_search hive_lite_support_custom_form">
                        <div class="hive_lite_support_single_input">
                            <input type="search" class="hive_lite_support_form_control" placeholder="Search Template" id="hive-support-thread-search-input-field" onkeyup="hive_lite_support_thread_response_search()">
                            <button onclick="hive_lite_support_thread_response_search()"> <img src="<?php echo esc_url(HIVE_LITE_SUPPORT_IMG_DIR . "/thread/search.svg") ?>"> </button>
                        </div>
                    </div>
                    <div class="hive_lite_support_thread_right_template_body">
                        <div class="hive_lite_support_thread_right_template_contents">

                        </div>
                    </div>
                </div>
                <div class="hive_lite_support_thread_right_tab_body" data-slug="activities">
                    <div class="hive_lite_support_thread_right_template_body">
                        <div class="hive_lite_support_thread_right_template_contents">

                        </div>
                    </div>
                </div>
                <div class="hive_lite_support_thread_right_tab_body" data-slug="todo">
                    <div class="hive_lite_support_thread_right_search hive_lite_support_custom_form">
                        <div class="hive_lite_support_todo_input">
                            <input type="text" class="hive_lite_support_form_control" id="hive-support-thread-todo-input-field" placeholder="Write...">
                            <button onclick="hive_lite_support_thread_add_todo()"> <img src="<?php echo esc_url(HIVE_LITE_SUPPORT_IMG_DIR . "/thread/plus.svg") ?>"> </button>
                        </div>
                    </div>
                    <div class="hive_lite_support_thread_right_template_body">
                        <div class="hive_lite_support_thread_right_template_contents">

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


</div>



<!-- Popup Edit Delete -->
<div class="hive_lite_support_thread_action_popup_overlay"></div>
<div class="hive_lite_support_thread_action_popup_edit">
    <div class="hive_lite_support_thread_action_popup_close" onclick="hive_lite_support_thread_action_popup_close(this)"> <span>x</span> </div>
    <ul class="hive_lite_support_thread_action_popup_edit_contents_list">
        <li class="hive_lite_support_thread_action_popup_edit_contents_list_item" onclick="hive_lite_support_thread_action_delete(this)"> <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR ?>/responses/black-delete.svg"> Delete This Message </li>
    </ul>
</div>