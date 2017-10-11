<div class="container">
    <div class="thumbnail">
        <div class="row outer_pages">
            <div class="col-lg-12">
        <div class="page-header text-center"><h1>Conditions at <?php echo Configure::read ( 'App.name' ); ?></h1></div>
        </div>
        <div class="col-lg-12 term_of_services">
            <?php foreach($diseases_list['parents'] as $key => $disease) { 
                             if($key%3 == 0) { ?>
                               <div class = "row"> <?php } ?>
                                    <div class = "condition-list-row">
                                        <h4> <?php echo h($disease['parent']['Disease']['name']); ?></h4>
                                        <?php 
                                                 $result = "";
                                                 foreach($disease['child'] as $child) {
                                                        $result = $result."<a href='/condition/index/".$child['Disease']['id']."'>".h($child['Disease']['name'])."</a>".", ";
                                                 } 
                                                 $result = substr_replace($result, "", -2);
                                                 echo $result;
                                        ?>
                                    </div>
                             <?php if($key%3 == 2) { ?> </div> <?php } ?>
            <?php  } ?>
           
            <div class="other-conditions-row">
                 <h4> Other conditions</h4>
                    <?php           
                                    $others = "";
                                    foreach($diseases_list['others'] as $disease) { 
                                        $others = $others."<a href='/condition/index/".$disease['Disease']['id']."'>".h($disease['Disease']['name'])."</a>".", ";
                                    }
                                    $others = substr_replace($others, "", -2);
                                    echo $others;
                    ?>
            </div>

        </div>
        </div>
    </div>

</div>
