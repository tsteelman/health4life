<div class="navbar">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a href="#" class="brand">
                <small>
                    <i class="icon-leaf"></i>
                    <?php echo Configure::read('App.name'); echo __(' Admin'); ?>
                </small>
            </a><!--/.brand-->

            <ul class="nav ace-nav pull-right">
                <li class="light-blue">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <?php echo Common::getUserThumb($userId, $userType, 'x_small', 'user_pic'); ?>
                        <span class="user-info">
                            <small><?php echo __('Welcome') . ' ' . $username; ?></small>
                        </span>

                        <i class="icon-caret-down"></i>
                    </a>

                    <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">

                        <!--                                            <li>
                                                                                <a href="#">
                                                                                        <i class="icon-cog"></i>
                        <?php // echo __('Settings'); ?>
                                                                                </a>
                                                                        </li>-->

                        <li>
                            <a href="/admin/users/profile">
                                <i class="icon-user"></i>
                                <?php echo __('Profile'); ?>
                            </a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a href="/admin/users/logout">
                                <i class="icon-off"></i>
                                <?php echo __('Logout'); ?>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul><!--/.ace-nav-->
        </div><!--/.container-fluid-->
    </div><!--/.navbar-inner-->
</div>