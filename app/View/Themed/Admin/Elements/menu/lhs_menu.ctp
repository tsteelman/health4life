<?php

  $menu = array();
  $currentUrl = $this->here;

  $menu[0]['name']  = 'Dashboard';
  $menu[0]['url']   = '/admin/dashboard';
  $menu[0]['class'] = 'icon-dashboard';
  $menu[0]['id'] = 'dashboard-templates-li';

  $menu[1]['name']  = 'E-mail Templates';
  $menu[1]['url']   = '/admin/emailTemplates';
  $menu[1]['class'] = 'icon-envelope';
  $menu[1]['id'] = 'email-templates-li';

  $menu[2]['name']  = 'Manage Diseases';
  $menu[2]['url']   = '/admin/Diseases';
  $menu[2]['class'] = 'icon-certificate';
  $menu[2]['id'] = 'disease-list-li';

  $menu[3]['name']  = 'Manage Surveys';
  $menu[3]['url']   = '/admin/Surveys';
  $menu[3]['class'] = 'icon-edit';
  $menu[3]['id'] = 'survey-list-li';

  $menu[4]['name']  = 'Manage Symptoms';
  $menu[4]['url']   = '/admin/Symptoms';
  $menu[4]['class'] = 'icon-book';
  $menu[4]['id'] = 'symptom-list-li';

  $menu[5]['name']  = 'User Management';
  $menu[5]['url']   = '#';
  $menu[5]['class'] = 'icon-user';
  $menu[5]['id'] = 'users-li';
  $menu[5]['submenu'][0]['name']= 'Manage Users';
  $menu[5]['submenu'][0]['url'] = '/admin/Users';
  $menu[5]['submenu'][0]['id'] = 'user-list-li';
  $menu[5]['submenu'][1]['name']= 'Manage Admins';
  $menu[5]['submenu'][1]['url']= '/admin/users/admins';
  $menu[5]['submenu'][1]['id'] = 'admin-list-li';
  $menu[5]['submenu'][1]['superAdminOnly'] = 'true';

  $menu[6]['name']  = 'Analytics';
  $menu[6]['url']   = '/admin/Analytics';
  $menu[6]['class'] = 'icon-bar-chart';
  $menu[6]['id'] = 'analytic-list-li';

  $menu[7]['name']  = 'Newsletter';
  $menu[7]['url']   = '#';
  $menu[7]['class'] = 'icon-envelope-alt';
  $menu[7]['id'] = 'newsletters-li';
  $menu[7]['submenu'][0]['name']= 'Manage Subscriber';
  $menu[7]['submenu'][0]['url']= '/admin/subscribers';
  $menu[7]['submenu'][0]['id']= 'newsletters-subscribers-li';
  $menu[7]['submenu'][1]['name']= 'Manage Template';
  $menu[7]['submenu'][1]['url']= '/admin/NewsletterTemplates';
  $menu[7]['submenu'][1]['id']= 'newsletters-templates-li';
  $menu[7]['submenu'][2]['name']= 'Manage Newsletter';
  $menu[7]['submenu'][2]['url']= '/admin/Newsletters';
  $menu[7]['submenu'][2]['id']= 'newsletters-manage-li';

  $menu[8]['name']  = 'Events';
  $menu[8]['url']   = '/admin/Events';
  $menu[8]['class'] = 'icon-calendar';
  $menu[8]['id'] = 'event-list-li';

  $menu[9]['name']  = 'Communities';
  $menu[9]['url']   = '/admin/communities';
  $menu[9]['class'] = 'icon-group';
  $menu[9]['id'] = 'community-list-li';

  $menu[10]['name']  = 'Settings';
  $menu[10]['url']   = '/admin/Settings';
  $menu[10]['class'] = 'icon-cogs';
  $menu[10]['id'] = 'settings-li';
  $menu[10]['superAdminOnly'] = 'true';

  $menu[11]['name']  = 'Manage Abuse Reports';
  $menu[11]['url']   = '/admin/abuseReports';
  $menu[11]['class'] = 'icon-group';
  $menu[11]['id'] = 'abuse-reports-list-li';

?>

<div class="sidebar" id="sidebar">
    <div class="sidebar-shortcuts" id="sidebar-shortcuts">
        <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">

            <a href='/admin/dashboard'><button class="btn btn-small btn-info">
                <i class="icon-dashboard"></i>
            </button></a>

            <a href='/admin/analytics'><button class="btn btn-small btn-success">
                <i class="icon-signal"></i>
            </button></a>

            <a href="/admin/users"><button class="btn btn-small btn-warning">
                    <i class="icon-user"></i>
                </button></a>

            <a href='/admin/users/profile'><button class="btn btn-small btn-danger">
                <i class="icon-cogs"></i>
            </button></a>
        </div>

        <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
            <span class="btn btn-success"></span>

            <span class="btn btn-info"></span>

            <span class="btn btn-warning"></span>

            <span class="btn btn-danger"></span>
        </div>
    </div><!--#sidebar-shortcuts-->

    <ul class="nav nav-list">

        <?php foreach ($menu as $item) { 
            if(isset($item['superAdminOnly']) && ($isSuperAdmin != true)) continue; ?>

            <li class="<?php echo ($item['url'] == $currentUrl )? 'active':''; ?>" id ="<?php echo $item['id']; ?>" >

                <a href= "<?php echo $item['url']; ?>" <?php echo (isset($item['submenu'])) ? 'class="dropdown-toggle"' : ''; ?>>
                    <i class="<?php echo $item['class']; ?>"></i>
                    <span class="menu-text"> <?php echo $item['name']; ?> </span>
                    <?php if(isset($item['submenu'])) { ?>
                        <b class="arrow icon-angle-down"></b>
                    <?php } ?>
                </a>

                <?php if(!empty($item['submenu'])) { ?>
                          <ul class="submenu">
                                    <?php foreach ($item['submenu'] as $submenu)  { 
                                           if(isset($submenu['superAdminOnly']) && ($isSuperAdmin != true)) continue; ?>
                                               <li class= "<?php echo ($submenu['url'] == $currentUrl)? 'active':''; ?>" id ="<?php echo $submenu['id']; ?>" >
                                                   <a href= "<?php echo $submenu['url']; ?>" >
                                                       <i class="icon-double-angle-right"></i>
                                                           <?php echo $submenu['name'] ?>
                                                    </a>
                                               </li>
                                     <?php } ?>  
                           </ul>
                 <?php } ?>  
           </li>    
        
       <?php } ?>

        <span style="display:none">
            <li>
                <a href="#">
                    <i class="icon-file"></i>
                    <span class="menu-text"><?php echo __('Content'); ?></span>
                </a>
            </li>	
            <li>
                <a href="#">
                    <i class="icon-folder-open"></i>
                    <span class="menu-text"><?php echo __('Manage Resources'); ?></span>
                </a>
            </li>	
            <li>
                <a href="#">
                    <i class="icon-food"></i>
                    <span class="menu-text"><?php echo __('Manage Recipe'); ?></span>
                </a>
            </li>	
            <li>
                <a href="#">
                    <i class="icon-key"></i>
                    <span class="menu-text"><?php echo __('Manage Key Opinion Leader'); ?></span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="icon-group"></i>
                    <span class="menu-text"><?php echo __('Manage Volunteer'); ?></span>
                </a>
            </li>
        </span>
    </ul><!--/.nav-list-->

    <div class="sidebar-collapse" id="sidebar-collapse">
        <i class="icon-double-angle-left"></i>
    </div>
</div>