<?php $flag = (count($menuItems) == 1)? 1 : 0;?>

<?php if ($flag == 0): ?>
<?php if (isset($menuItems) && !empty($menuItems)) : ?>
    <div class="group_options more_nav">
        <nav class="navbar navbar-default" role="navigation">
            <div class="navbar-header">
                                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                                            <span class="sr-only">Toggle navigation</span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                        </button>
                                    </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                <div class="group_catagories">
                    <ul class="subtabs_list">
                        <?php
                        $i = 0;
                        $menu = array();
                        foreach ($menuItems as $menuItem):
                            //show some menu only in login user's profile.
                            if ((!$is_same) && (in_array($menuItem['name'], $loggedInUserMenuItems))) {
                                continue;
                            } 
                            $i++;
//                            if($i < 10)
//                            { ?>   
                            
                                <li <?php if ($menuItem['active'] === true) { ?> class="current" <?php } ?> >
                                    <?php
                                   ($is_same && $menuItem['my']) ? $label = "My " . $menuItem['label'] : $label = $menuItem['label'];

                                    if ( isset( $menuItem['target'] ) ) {
                                        echo $this->Html->link( $label , $menuItem['url'], array('target' => $menuItem['target'] ));
                                    } else {
                                        echo $this->Html->link( $label , $menuItem['url']);
                                    }
                                    ?>
                                </li>
                            <?php // } else { 
//                                            $menu[] = $menuItem;
                                ?>
                       <?php /*}*/ endforeach; 
                                
                        if(!empty($menu)){ ?>
                           <li class="dropdown">
                               <a href="#" class="dropdown-toggle" data-toggle="dropdown">More <b class="caret"></b></a>
                                   <ul class="dropdown-menu">
                                      <?php  foreach ($menu as $item):
                                          if ((!$is_same) && (in_array($item['name'], $loggedInUserMenuItems))) {
                                                continue;
                                            }
                                           ?>
                                        <li <?php if ($item['active'] === true) { ?> class="current" <?php } ?> >
                                            <?php
                                                 ($is_same) ? $label = "My " . $item['label'] : $label = $item['label'];

                                                 echo $this->Html->link($label, $item['url']);
                                            ?>
                                         </li>
                                      <?php endforeach; ?>    
                                    </ul>
                            </li>
                        <?php } ?>  
                    </ul>
                </div>                                            
            </div>
        </nav>
    </div>
<?php endif; ?>
<?php endif; ?>