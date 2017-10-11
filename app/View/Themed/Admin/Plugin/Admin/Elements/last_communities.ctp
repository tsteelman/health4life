

<?php
foreach ($communities as $community) {
    $community_url = "/community/details/index/" . $community['Community']['id'];
    ?>
    <table width="600" border="0" cellpadding="0" cellspacing="0" style="border-bottom: 1px solid #e6e6e6;padding: 20px 0px; background-color: #fff; border-collapse: separate; margin: 0 auto;">
        <tr>
            <td style="width: 240px;padding-right: 20px; vertical-align: top;">
                <?php echo $this->Html->image(Common::getCommunityThumb($community['Community']['id'])); ?> 
            </td>
            <td style="width: 340px;padding: 0px;">                                                             
                <table>
                    <tr>
                        <td style="font-size: 24px; color: #252525;margin-bottom: 10px; line-height: 28px;"> <?php echo h($community['Community']['name']); ?></td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #2c589e;padding: 0px 0px 5px 0px;"><?php echo h($community['User']['username']); ?></td>
                    </tr>
                    <tr>
                        <td style="font-size: 14px; color: #444444;padding: 8px 0px;"><?php echo h($community['Community']['description']); ?></td>
                    </tr>
                    <tr>
                        <td style="font-size: 14px; color: #2c589e;padding-top: 8px;">
                            <a href="<?php echo $community_url; ?>" style="font-size: 14px; color: #2c589e;text-decoration: none;">
                                <div>View in <?php echo Configure::read('App.name'); ?>
                                    <img style="vertical-align: middle; padding-left: 10px;" src="http://patients4life.qburst.com/theme/App/img/newsletter_arrow.png"> </div>
                            </a> 
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

<?php } ?>