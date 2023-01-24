<?php

use yii\bootstrap4\LinkPager;
use yii\web\View;

$i = 1;

$this->title = 'Domains';
$this->registerJs(
    '
    ',
    View::POS_READY,
);
$this->registerJsFile(
    "@web/js/del.js",
    ['depends' => [\yii\web\JqueryAsset::class]]
);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-lg-12 mb-2" style="text-align: -webkit-right;">
                        <a type="button" href="/admin/domains/create" class="btn btn-block btn-primary" style="width: 100px;">
                            Add
                        </a>
                    </div>
                    <div class="col-lg-12" style="overflow: auto;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <!-- <th>#</th> -->
                                    <th>Domain</th>
                                    <th>Language</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($domains as $domain){ ?>
                                <tr>
                                    <!-- <td><?= $i++ ?></td> -->
                                    <td><?= $domain->domain ?></td>
                                    <td><?= $domain->language->lang ?></td>
                                    <td>
                                        <div class="row">
                                            <div class="col-6">
                                                <a type="button" href="/admin/domains/edit/<?= $domain->id ?>" class="btn btn-block btn-primary">
                                                    Edit
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a type="button" href="javascript:del('/admin/domains/delete/<?= $domain->id ?>')" class="btn btn-block  btn-danger">
                                                    Delete
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
</div>