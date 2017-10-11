<?php
if (isset($ads)) {
    $i = 0;
    foreach ($ads as $ad) {
        if ($i < sizeof($ads) - 1) {
            ?>
        <div class="add_area">
            <?php
        } else {
            ?>
        <div class="add_area sticky">
            <?php
        }
        ?>
            <h5>
                <a href="<?php echo $ad['link']; ?>" target="_blank"><?php echo __($ad['title']); ?></a>
            </h5>
            <img src="<?php echo $ad['image']; ?>" height="40%" width="100%" />
            <p><?php echo __($ad['description']); ?></p>
        </div>
        <?php
        $i++;
    }
}
?>