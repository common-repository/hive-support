<ul>
    <li class="active tickets" onclick="hive_lite_support_sidebar_menu_click(this, `tickets`)">
        <div class="logo"></div>Tickets
    </li>

    <?php if ($this->settings->agentHasAccess("manage_responses")) { ?>
        <li class="responses" onclick="hive_lite_support_sidebar_menu_click(this, `responses`)">
            <div class="logo"></div>Responses
        </li>
    <?php } ?>
    <?php if ($this->settings->agentHasAccess("manage_agents")) { ?>
        <li class="agents" onclick="hive_lite_support_sidebar_menu_click(this, `agents`)">
            <div class="logo"></div>Agents
        </li>
    <?php } ?>
    <?php if ($this->settings->agentHasAccess("access_activities")) { ?>
        <li class="activities" onclick="hive_lite_support_sidebar_menu_click(this, `activities`)">
            <div class="logo"></div>Activities
        </li>
    <?php } ?>
    <?php if ($this->settings->agentHasAccess("access_reports")) { ?>
        <li class="reports" onclick="hive_lite_support_sidebar_menu_click(this, `reports`)">
            <div class="logo"></div>Reports
        </li>
    <?php } ?>
    <?php if ($this->settings->agentHasAccess("modify_automation")) { ?>
        <li class="automation" onclick="hive_lite_support_sidebar_menu_click(this, `automation`)">
            <div class="logo"></div>Automation
        </li>
    <?php } ?>
    <?php if ($this->settings->agentHasAccess("modify_settings")) { ?>
        <li class="settings" onclick="hive_lite_support_sidebar_menu_click(this, `settings`)">
            <div class="logo"></div>Settings
        </li>
    <?php } ?>

</ul>

<div class="hive_lite_support_sidebar_bottom">
    <div class="hive_lite_support_sidebar_bottom_logo">
        <img src="<?php echo esc_url(HIVE_LITE_SUPPORT_IMG_DIR . "sidebar/sidebar-logo.png") ?>" />
    </div>
    <!--<p>Support Ticket Management</p>
    <h2>Hive Support</h2>-->
</div>