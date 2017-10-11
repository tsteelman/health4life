<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <?php
            foreach ($tableHeaders as $tableHeader) {
                ?>
                <th class="center"><?php echo __($tableHeader); ?></th>
                <?php
            }
            ?>
        </tr>
    </thead>

    <tbody>
        <?php
        $i = 0;
        foreach ($topElements as $element) {
            $i++;
            ?>
            <tr>
                <td class="center"><?php echo __($i);?></td>
                <?php
                    if($link) {
                        ?>
                        <td><a href="Diseases/view/<?php echo $element['id'];?>"><?php echo __($element['name']);?></a></td>
                        <?php
                    } else {
                        ?>
                        <td><?php echo __($element['name']);?></td>
                        <?php
                        
                    }
                ?>
                <td class="center"><?php echo __($element['users']);?></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>