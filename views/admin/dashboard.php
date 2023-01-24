<?php
$this->title = 'Dashboard';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <?= \hail812\adminlte\widgets\Alert::widget([
                'type' => 'success',
                'body' => '<h5>Congratulations!</h5>',
            ]) ?>
        </div>
    </div>
</div>