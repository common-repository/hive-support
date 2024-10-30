<?php
global $wpdb;
$table_name = $wpdb->prefix . 'hs_mailbox';
$allmailbox = $wpdb->get_results(
    "SELECT * FROM $table_name"
);
?>
<div class="hive_lite_support_settings_business_inbox">
    <div class="hive_lite_support_settings_business_inbox_container">
        <div class="hive_lite_support_settings_row_item">

            <div class="hive_lite_support_settings_item">
                <div class="hive_lite_support_settings_single">
                    <div class="hive_lite_support_settings_single_flex">
                        <h4 class="hive_lite_support_settings_single_title"> Rylies&amp;CVo. </h4>
                        <div class="hive_lite_support_settings_single_right">
                            <div class="hive_lite_support_settings_single_right_flex">
                                <div class="hive_lite_support_settings_single_right_btn">
                                    <a href="#" class="hive_lite_support_settings_item_web_btn"> Web Based </a>
                                </div>
                                <div class="hive_lite_support_settings_elipsis_icon">
                                    <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR; ?>settings/dot-elipsis.svg">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hive_lite_support_settings_single_contents">
                        <ul class="hive_lite_support_settings_single_contents_list">
                            <li class="hive_lite_support_settings_single_contents_list_item">
                                <span class="hive_lite_support_settings_single_contents_list_span"> Email: </span>
                                <a href="#" class="hive_lite_support_settings_single_contents_list_link"> youremail@mail.com
                                </a>
                            </li>
                            <li class="hive_lite_support_settings_single_contents_list_item">
                                <span class="hive_lite_support_settings_single_contents_list"> Tickets: </span>
                                <a href="#" class="hive_lite_support_settings_single_contents_list_link">
                                    <?php echo 1; ?>
                                </a>
                            </li>
                        </ul>
                        <a href="#" class="edit_inbox"> <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR; ?>settings/edit-action.svg"> Edit Inbox </a>
                    </div>
                </div>
            </div>

            <?php
            foreach ($allmailbox as $singlemail) { ?>
                <div class="hive_lite_support_settings_item">
                    <div class="hive_lite_support_settings_single">
                        <div class="hive_lite_support_settings_single_flex">
                            <h4 class="hive_lite_support_settings_single_title">
                                <?php echo print_r($singlemail->mailbox_title); ?>
                            </h4>
                            <div class="hive_lite_support_settings_single_right">
                                <div class="hive_lite_support_settings_single_right_flex">
                                    <div class="hive_lite_support_settings_single_right_btn">
                                        <a href="#" class="hive_lite_support_settings_item_web_btn"> Email Based </a>
                                    </div>
                                    <div class="hive_lite_support_settings_elipsis_icon">
                                        <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR; ?>settings/dot-elipsis.svg">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hive_lite_support_settings_single_contents">
                            <ul class="hive_lite_support_settings_single_contents_list">
                                <li class="hive_lite_support_settings_single_contents_list_item">
                                    <span class="hive_lite_support_settings_single_contents_list_span"> Email: </span>
                                    <a href="#" class="hive_lite_support_settings_single_contents_list_link">
                                        <?php echo $singlemail->email_id; ?>
                                    </a>
                                </li>
                                <li class="hive_lite_support_settings_single_contents_list_item">
                                    <span class="hive_lite_support_settings_single_contents_list"> Tickets: </span>
                                    <a href="#" class="hive_lite_support_settings_single_contents_list_link"> 256 </a>
                                </li>
                            </ul>
                            <a href="#" class="edit_inbox"> <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR; ?>settings/edit-action.svg"> Edit Inbox </a>
                        </div>
                    </div>
                </div>
            <?php }
            ?>
            <div class="hive_lite_support_settings_item">
                <div class="hive_lite_support_settings_add_business_inbox">
                    <div class="hive_lite_support_settings_add_business_inbox_flex">
                        <div class="hive_lite_support_settings_add_business_inbox_contents">
                            <h4 class="hive_lite_support_settings_add_business_inbox_contents_title"> Add Another Business
                                Inbox </h4>
                            <span class="hive_lite_support_settings_add_business_inbox_contents_para"> Add Another Business
                                Inbox </span>
                        </div>
                        <div class="hive_lite_support_settings_add_business_inbox_icon">
                            <a class="add_append_business" onclick="hive_lite_support_add_new_businexx_inbox()"> <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR; ?>settings/plus.svg"> </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once HIVE_LITE_SUPPORT_PATH . "backend/templates/views/settings/add_new_inbox.php"; ?>
</div>