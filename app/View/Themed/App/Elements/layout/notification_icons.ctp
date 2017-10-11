<ul class="notification_ul">
    <li class="notification_li">

        <div class="btn-group clearfix frnd_invtatn notifications_container"  id="message_notification_container">
            <a href="#" class="notification_icons btn-group icon_message dropdown-toggle message_icon_dashboard" data-toggle="dropdown" id="message_notification_icon">
                <div class="no_of_notofication hidden" id="unread_message_count"></div>
            </a>

            <ul class="dropdown-menu keep_open" id="message_notification_list_container">
                <img src="/theme/App/img/notifictn_arrow.png" class="notfctn_arrow">
                <center><img class="message_notification_loader" width="30" height="30" src="/img/loader.gif" alt="Loading..."></center>
            </ul>
        </div>
        <!-- new        
        <a href="#" class="notification_icons btn-group icon_message dropdown-toggle" data-toggle="dropdown" id="message_notification_icon">
                        <div class="no_of_notofication" id="unread_message_count">10</div>
                    </a>
        /new-->
    </li>
    <li class="notification_li">
        <div class="btn-group clearfix frnd_invtatn notifications_container" id="notification_container">
            <a href="#" class="notification_icons btn-group icon_notfctns dropdown-toggle" data-toggle="dropdown" id="alarm_notification_icon">
                <div class="no_of_notofication hidden" id="unread_notification_count"></div>
            </a>
            <ul class="dropdown-menu keep_open" id="notification_list_container">
                <img src="/theme/App/img/notifictn_arrow.png" class="notfctn_arrow">
                <center><img class="notification_loader" width="30" height="30" src="/img/loader.gif" alt="Loading..."></center>
            </ul>
        </div>
    </li>
    <li class="notification_li">

        <!--new        
        <a href="#" class="notification_icons btn-group icon_friendst dropdown-toggle" data-toggle="dropdown" id="frineds_notification_icon">
                        <div class="no_of_notofication visible" id="pending_friend_requests_count">10</div>
                    </a>
        /new-->
        <div class="btn-group clearfix frnd_invtatn notifications_container" id="frineds_notification_container">
            <a title="Friends request" href="#" class="notification_icons icon_friendst dropdown-toggle" data-toggle="dropdown" id="frineds_notification_icon">
                <div class="no_of_notofication hidden" id="pending_friend_requests_count"></div>
            </a>

            <ul class="dropdown-menu keep_open" id="frineds_notification_list_container">                
                <center><img class="friends_notification_loader" style="margin-top: 10px;"width="30" height="30" src="/img/loader.gif" alt="Loading..."></center>
            </ul>
        </div>
    </li>
</ul>