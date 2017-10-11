<!-- Logged in header -->
<div class="top_header">
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="container header_section">
            <div class="navbar-header">
                <?php echo $this->element('layout/logo'); ?>
                <div class="header_notification pull-left">
                    <?php echo $this->element('layout/notification_icons'); ?>
                </div>
                
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                        data-target="#navbar-collapse">
                    <span class="icon-bar"></span> <span class="icon-bar"></span> <span
                        class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
<!--                        <li class="header_notification">   removed class notfctn_icons 
                            <?php // echo $this->element('layout/notification_icons'); ?>
                        </li>-->
                    <li class="event_search">

                        <div class="search_area">
                            <div class="btn-group pull-left">
                                <div class="search_downarow pull-left dropdown-toggle" data-toggle="dropdown">
                                    <div id="search_icons" class="search_icons <?php
                                    if (isset($searchClass)) {
                                        echo $searchClass;
                                    } else {
                                        echo "search_select";
                                    }
                                    ?>" ></div>
                                </div>
                                <ul class="dropdown-menu">
                                    <li><a data-classname="all" class="all search_list_active search_type" href="javascript:void(0)">All</a></li>
				    <li><a data-classname="disease_search" class="disease_search search_type" href="javascript:void(0)">Condition</a></li>
                                    <li><a data-classname="people_search" class="people_search search_type" href="javascript:void(0)">People</a></li> 
                                    <li><a data-classname="community_search" class="community_search search_type" href="javascript:void(0)">Community</a></li>
                                    <li><a data-classname="hash_search" class="hash_search search_type" href="javascript:void(0)">Hashtag</a></li>
                                </ul>
                            </div>
                            <div class="form-group pull-left"><input maxlength="50" type="text" placeholder="Conditions, Friends and Communities" id="header_search" value="<?php
                                if (isset($searchStr)) {
                                    echo h(stripslashes($searchStr));
                                }
                                ?>" class="form-control search_icon" >
                            </div>
							<div class="search_submit pull-left"></div>

                        </div></li>
                    <li id ="profile_icon_container" tabindex="0" class="profile_icon" onblur="removeBgColor();" onclick="addBgColor();">
                        <div class="btn-group">
                            <div class="dropdown-toggle" data-toggle="dropdown">
                                <div class="pull-left top_profile_img">
                                    <?php echo Common::getUserThumb($loggedin_userid, $loggedin_user_type, 'x_small'); ?>
                                </div>
                                <p class="pull-left"><?php echo $username; ?></p>
                                <div class="down_arow">
                                    <!--                                    <div class="caret_white"></div>-->
                                </div>
                            </div>
                            <!--                            <ul class="dropdown-menu">
                                                                                            <li><a class="myprofile" href="/profile">
                            <?php echo ('My Profile'); ?>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li><a class="inbox" href="/message">
                            <?php echo ('Inbox'); ?>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li><a class="settings" href="/user/edit">
                            <?php echo ('Settings'); ?>
                                                                                                </a>
                                                                                            </li>
                                                            <li><a class="logout" href="/logout">
                            <?php echo ('Logout'); ?>
                                                                </a>
                                                            </li>
                            
                                                        </ul>-->
                            <ul class="dropdown-menu">
                                <?php
                                if (!empty($dashboard_details['items'])) {
                                    foreach ($dashboard_details['items'] as $dash_items) {
										if(isset($dash_items['disabled']) && $dash_items['disabled']) {
                                            continue;
                                        }
                                        $target_window = "_self";
                                        if(isset($dash_items['new_window']) && $dash_items['new_window']) {
                                            $target_window = "_blank";
                                        }
										
                                        echo '<li><a target="' . $target_window . '"'
                                                . ' href="' . $dash_items['url'] . '"><span 
                                                    class="dashboard_icons
                                                     ' . $dash_items['large_icon'] . '"></span><span 
                                                    class="menu_tittle"> ' . $dash_items['name'] . '</span></a></li>';
                                    }
                                }
                                ?>
                            </ul>
                        </div></li>
                </ul>

            </div>
        </div>
    </div>
    <?php
      if (!$is_dashboard_page):
    ?>
    <div id="selection_bar">
        <div class="container">
            <?php
                echo $this->Html->getCrumbList(array('lastClass' => 'active', 'class' => 'breadcrumb pull-left', 'separator' => ' '));
            ?>
        </div>
    </div>
            
    <?php
    endif;
    ?>
</div>
