<div class="pagination pagination-small">
    <div class="pull-left pagination_counter">
        <?php
        if ($this->Paginator->numbers()) {
            echo $this->Paginator->counter(
                    'Total {:count} records. Showing page {:page} of {:pages}.'
            );
        }
        ?>
    </div>
    <ul class="pull-right">
        <?php
        if ($this->Paginator->numbers()) {
            echo $this->Paginator->first(__('<<'), array('tag' => 'li', 'disabledTag' => 'a'));
            echo $this->Paginator->prev(__('<'), array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
            echo $this->Paginator->numbers(array('separator' => '', 'currentTag' => 'a', 'currentClass' => 'active', 'tag' => 'li'));
            echo $this->Paginator->next(__('>'), array('tag' => 'li', 'currentClass' => 'disabled'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
            echo $this->Paginator->last(__('>>'), array('tag' => 'li', 'disabledTag' => 'a'));
        }
        ?>
    </ul>
</div>