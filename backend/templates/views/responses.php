<div id="hive_lite_support_responses">

    <div class="hive_lite_support_response_add_popup">
        <div class="hive_lite_support_response_add_popup_dark_bg"></div>
        <div class="hive_lite_support_response_add">
            <div class="hive_lite_support_response_add_header">
                <div>
                    <h3> Add Response </h3>

                    <div id="hivedashboard"></div>
                    <p> Add basic response template to answer users question easily. </p>
                </div>
                <span class="hive_lite_support_response_add_close_icon" onclick="hive_lite_support_response_add_popup_close()"></span>
            </div>

            <div class="hive_lite_support_response_add_form">
                <div class="hive_lite_support_response_add_form_single_field hive_lite_support_response_add_question">
                    <input type="text" placeholder="Response Question">
                </div>
                <div class="hive_lite_support_response_add_form_single_field hive_lite_support_response_add_answer">
                    <textarea type="text" rows="4" placeholder="Response Answer"></textarea>
                </div>

                <div class="hive_lite_support_response_add_actions">
                    <button>Add</button>
                </div>
            </div>
        </div>
    </div>


    <div class="hive_lite_support_response_header">
        <div class="hive_lite_support_response_header_form custom_form">
            <div class="single-input">
                <input type="search" placeholder="Search Template" class="form--control" id="hive-support-search-input-field" onkeyup="hive_lite_support_response_search()">
                <button class="button-search" onclick="hive_lite_support_response_search()"> <img src="<?php echo esc_url(HIVE_LITE_SUPPORT_IMG_DIR . "/responses/search-white.svg") ?>"> </button>
            </div>
        </div>
        <div class="hive_lite_support_response_header_btn" onclick="hive_lite_support_response_add_popup_open()">
            <button class="hive_lite_support_add_template_btn"> <img src="<?php echo esc_url(HIVE_LITE_SUPPORT_IMG_DIR . "/responses/plus.svg") ?>"> Add a Template </button>
        </div>
    </div>
    <div class="hive_lite_support_response_container">
        <div class="hive_lite_support_response_item">
            <div class="hive_lite_support_response_single">
                <div class="hive_lite_support_response_single_heading">
                    <div class="hive_lite_support_response_single_heading_flex">
                        <h5 class="title " onclick="hive_lite_support_response_template_details(this)"> Product not working
                            soultion </h5>
                        <span class="hive_lite_support_response_single_elipsis_icon" onclick="hive_lite_support_response_popup_open()"> <img src=""> </span>
                    </div>
                    <div class="hive_lite_support_response_single_content">
                        <p class="hive_lite_support_response_single_content_para"> If your product isn’t working then first
                            you’ll just have to add a simple product license, that is available with the product. </p>
                        <span class="hive_lite_support_response_single_content_span"> Thanks, </span>
                        <span class="hive_lite_support_response_single_content_span"> Support Team </span>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<!-- Popup Edit Delete -->
<div class="hive_lite_support_response_popup_overlay"></div>
<div class="hive_lite_support_response_popup_edit">
    <div class="hive_lite_support_response_popup_close" onclick="hive_lite_support_response_popup_close()"> <span>x</span> </div>
    <ul class="hive_lite_support_response_popup_edit_contents_list">
        <li class="hive_lite_support_response_popup_edit_contents_list_item" onclick="hive_lite_support_responses_delete()"> <img src="<?php echo HIVE_LITE_SUPPORT_IMG_DIR ?>/responses/black-delete.svg"> Delete This Template </li>
    </ul>
</div>