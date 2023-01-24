<?php

use yii\bootstrap4\LinkPager;
use yii\web\View;
use yii\jui\DatePicker;

$this->title = 'Settings';
$this->registerJs(
    '
    ',
    View::POS_READY,
);
?>
<div class="container-fluid">
    <div class="row">
        <div class="card col-md-12">
            <div class="card-body">
                <div class="col-lg-12" style="overflow: auto;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>API Key</th>
                                <th>Domain</th>
                                <th>Admin Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($models as $field){ ?>
                                <tr>
                                    <td><?= $field->username ?></td>
                                    <td><?= $field->authKey ?></td>
                                    <td><?= $field->accessToken ?></td>
                                    <td><?= $field->adminname ?></td>
                                    <td>
                                        <div class="row">
                                            <div class="col-12">
                                                <a type="button" href="/admin/settings/edit/<?= $field->id ?>" class="btn btn-block btn-primary">
                                                    Edit
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>