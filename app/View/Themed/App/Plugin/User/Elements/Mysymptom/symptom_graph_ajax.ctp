   <?php        
        if (!empty($symptoms)) {
            $i = 0;
            ?>
            <div class="row symptom_graph_rep">
                <?php
                foreach ($symptoms as $symptom) {
                    if ($i == 3) {
                        echo '</div><div class="row symptom_graph_rep">';
                        $i = 0;
                    }
                    ?>
                   <?php echo $this->element('User.Mysymptom/symptom_graph_block',
						   array('symptom' => $symptom)); ?> 
                    <?php
                    $i++;
                } // foreach 
                ?>
            </div>
            <?php
        } else {
            echo __('No symptoms found');
        }// end if
        ?> 