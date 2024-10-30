<div class="hive_lite_support_settings_add_new_business_inbox_container">
    <div class="hive_lite_support_settings_add_new_business_inbox_header">
        <div class="hive_lite_support_settings_add_new_business_inbox_header_flex">
            <div class="hive_lite_support_settings_add_new_business_inbox_section_title">
                <h3 class="title" onclick="back_to_business_inbox()"> <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR; ?>settings/arrow-left.svg"> Add New Inbox </h3>
            </div>
        </div>
    </div>

    <div class="hive_lite_support_settings_new_business_inbox_add">
        <div class="hive_lite_support_settings_new_business_inbox_trigger_inner">
            <h4 class="hive_lite_support_settings_new_business_inbox_trigger_title"> Imap Settings </h4>
            <div class="hive_lite_support_settings_new_business_inbox_trigger_contents">
                <!-- <div class="custom-radio custom-radio-inline">
                    <div class="custom-radio-single active">
                        <input class="radio-input" type="radio" id="radio1" name="size" checked="checked">
                        <label for="radio1"> Match any Conditions</label>
                    </div>
                    <div class="custom-radio-single">
                        <input class="radio-input" type="radio" name="size" id="radio2">
                        <label for="radio2"> Match all Conditions</label>
                    </div>
                </div> -->
                <div class="custom-form">
                    <form action="#">
                        <div class="single-input margin-top-25">
                            <label class="label-title"> Mail Box Title</label>
                            <input type="text" class="form--control mailbox_title" placeholder="Mailbox Title">
                        </div>
                        <div class="single-input margin-top-25">
                            <label class="label-title"> Imap Server Ssl Url </label>
                            <input type="text" class="form--control email_url" placeholder="Imap Server Ssl Url">
                        </div>
                        <div class="single-input margin-top-25">
                            <label class="label-title"> Imap Server Port </label>
                            <input type="text" class="form--control email_port" placeholder="Imap Server Ssl Port">
                        </div>
                        <div class="single-input margin-top-25">
                            <label class="label-title"> Email Id </label>
                            <input type="email" class="form--control email_id" placeholder="Email Id ">
                        </div>
                        <div class="single-input margin-top-25">
                            <label class="label-title"> Email Inbox Path </label>
                            <input type="text" class="form--control email_inbox_path" placeholder="Inbox Path ">
                        </div>
                        <div class="single-input margin-top-25">
                            <label class="label-title"> Password </label>
                            <input type="password" class="form--control email_password" placeholder="Email or app password ">
                        </div>
                    </form>
                </div>
                <div class="btn-wrapper">
                    <p class="cmn-btn " onclick="hive_lite_support_add_mailbox(this)"> Save Settings </a>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="hive_lite_support_settings_new_business_inbox_add">
        <div class="hive_lite_support_settings_new_business_inbox_trigger_inner">
            <div class="hive_lite_support_settings_new_business_inbox_single_flex">
                <h4 class="hive_lite_support_settings_new_business_inbox_single_title"> Footer Customization </h4>
                <div class="hive_lite_support_settings_new_business_inbox_single_right">
                    <div class="get_short_code">
                        <div class="btn-wrapper">
                            <a class="cmn-btn" onclick="hive_lite_support_settings_new_business_inbox_short_code_click()"> Get Short Code </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hive_lite_support_settings_new_business_inbox_trigger_contents">
                <div class="hive_lite_support_settings_new_business_inbox_footer_customization_thumb">
                    <textarea rows="7"  id="hive_lite_support_settings_new_business_inbox_footer_customization_field"></textarea>
                </div>
                <div class="btn-wrapper margin-top-25">
                    <a href="" class="cmn-btn"> Save Settings </a>
                </div>
            </div>
        </div>
    </div> -->
    <!-- <div class="hive_lite_support_settings_new_business_inbox_add">
        <div class="hive_lite_support_settings_new_business_inbox_trigger_inner">
            <h4 class="hive_lite_support_settings_new_business_inbox_single_title"> Notify by Email Settings </h4>
            <div class="hive_lite_support_settings_new_business_inbox_trigger_contents">
                <div class="hive_lite_support_settings_new_business_inbox_trigger_contents_flex">
                    <ul class="hive_lite_support_settings_new_business_inbox_contents_list list-style-none">
                        <li class="hive_lite_support_settings_new_business_inbox_contents_list_item">
                            <span class="hive_lite_support_settings_new_business_inbox_contents_list_item_title"> Notify Customer via email when they submits a ticket </span>
                            <div class="hive_lite_support_settings_new_business_inbox_contents_list_switch">
                                <div class="hive_lite_support_settings_new_business_inbox_contents_list_switch_flex">
                                    <a href="" class="hive_lite_support_settings_new_business_inbox_contents_list_switch_icon">
                                        <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR ?>settings/black-edit.svg">
                                    </a>
                                    <div class="hive_lite_support_settings_new_business_inbox_contents_list_switch_single">
                                        <div class="custom-switch">
                                            <label class="switch round-switch">
                                                <input type="checkbox" checked>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="hive_lite_support_settings_new_business_inbox_contents_list_item">
                            <span class="hive_lite_support_settings_new_business_inbox_contents_list_item_title"> Notify Admin/Agent via email when there is a reply from Customer </span>
                            <div class="hive_lite_support_settings_new_business_inbox_contents_list_switch">
                                <div class="hive_lite_support_settings_new_business_inbox_contents_list_switch_flex">
                                    <a href="" class="hive_lite_support_settings_new_business_inbox_contents_list_switch_icon">
                                        <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR; ?>settings/black-edit.svg">
                                    </a>
                                    <div class="hive_lite_support_settings_new_business_inbox_contents_list_switch_single">
                                        <div class="custom-switch">
                                            <label class="switch round-switch">
                                                <input type="checkbox" checked>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="hive_lite_support_settings_new_business_inbox_contents_list_item">
                            <span class="hive_lite_support_settings_new_business_inbox_contents_list_item_title"> Notify Customer via email when they submits a ticket </span>
                            <div class="hive_lite_support_settings_new_business_inbox_contents_list_switch">
                                <div class="hive_lite_support_settings_new_business_inbox_contents_list_switch_flex">
                                    <a href="" class="hive_lite_support_settings_new_business_inbox_contents_list_switch_icon">
                                        <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR; ?>settings/black-edit.svg">
                                    </a>
                                    <div class="hive_lite_support_settings_new_business_inbox_contents_list_switch_single">
                                        <div class="custom-switch">
                                            <label class="switch round-switch">
                                                <input type="checkbox" checked>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="hive_lite_support_settings_new_business_inbox_contents_list_item">
                            <span class="hive_lite_support_settings_new_business_inbox_contents_list_item_title"> Notify Admin/Agent via email when there is a reply from Customer </span>
                            <div class="hive_lite_support_settings_new_business_inbox_contents_list_switch">
                                <div class="hive_lite_support_settings_new_business_inbox_contents_list_switch_flex">
                                    <a href="" class="hive_lite_support_settings_new_business_inbox_contents_list_switch_icon">
                                        <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR; ?>settings/black-edit.svg">
                                    </a>
                                    <div class="hive_lite_support_settings_new_business_inbox_contents_list_switch_single">
                                        <div class="custom-switch">
                                            <label class="switch round-switch">
                                                <input type="checkbox" checked>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="hive_lite_support_settings_new_business_inbox_contents_list_item">
                            <span class="hive_lite_support_settings_new_business_inbox_contents_list_item_title"> Notify Customer via email when they submits a ticket </span>
                            <div class="hive_lite_support_settings_new_business_inbox_contents_list_switch">
                                <div class="hive_lite_support_settings_new_business_inbox_contents_list_switch_flex">
                                    <a href="" class="hive_lite_support_settings_new_business_inbox_contents_list_switch_icon">
                                        <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR; ?>settings/black-edit.svg">
                                    </a>
                                    <div class="hive_lite_support_settings_new_business_inbox_contents_list_switch_single">
                                        <div class="custom-switch">
                                            <label class="switch round-switch">
                                                <input type="checkbox" checked>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="btn-wrapper">
                    <a href="" class="cmn-btn"> Save Settings </a>
                </div>
            </div>
        </div>
    </div> -->
</div>


<!-- Short Code popup -->
<div class="hive_lite_support_settings_new_business_inbox_short_code_popup">
    <div class="hive_lite_support_settings_new_business_inbox_short_code_popup_inner">
        <div class="hive_lite_support_settings_new_business_inbox_popup_close" onclick="hive_lite_support_settings_new_business_inbox_popup_close()"> <span> x </span> </div>
        <h4 class="hive_lite_support_settings_new_business_inbox_short_code_popup_title"> Short Codes </h4>
        <div class="hive_lite_support_settings_new_business_inbox_short_code_popup_contents">
            <div class="hive_lite_support_settings_new_business_inbox_short_code_popup_single">
                <h6 class="hive_lite_support_settings_new_business_inbox_short_code_popup_single_title"> First Codes</h6>
                <div class="hive_lite_support_settings_new_business_inbox_short_code_popup_single_input">
                    <input type="text" class="myInput" value="{{customer.first_name}}">
                    <div class="hive_lite_support_settings_new_business_inbox_short_code_popup_single_input_icon" onclick="hive_lite_support_settings_short_code_popup_click_copy(this)">
                        <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR; ?>settings/copy.svg">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Popup Edit Delete -->
<div class="hive_lite_support_settings_new_business_inbox_popup_overlay"></div>
<div class="hive_lite_support_settings_new_business_inbox_popup_edit">
    <div class="hive_lite_support_settings_new_business_inbox_popup_close"> <span>x</span> </div>
    <div class="hive_lite_support_settings_new_business_inbox_popup_edit_contents">
        <ul class="hive_lite_support_settings_new_business_inbox_popup_edit_contents_list">
            <li class="hive_lite_support_settings_new_business_inbox_popup_edit_contents_list_item"> <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR; ?>settings/black-edit.svg"> Edit This Template </li>
            <li class="hexp-popup-edit-contents-list-item"> <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR; ?>settings/black-delete.svg"> Delete This Template </li>
        </ul>
    </div>
</div>