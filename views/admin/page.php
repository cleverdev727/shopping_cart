<?php
use yii\helpers\Html;
use yii\bootstrap4\LinkPager;
use yii\web\View;

$i = 1;

$this->title = 'Pages';
$this->registerJs(
    '
    $("#pageform-flag option:eq(1)").attr("selected", "selected");

    $(\'#pageform-lang_code\').change(function(){
        console.log($(this).val());
        if ($(this).val() !== "" && $(this).val() !== "template") {
            $("#pageform-flag").attr("disabled", false);
        } else {
            $("#pageform-flag").attr("disabled", true);
            $(".field-pageform-file_name").removeClass("d-none");
            $(".field-pageform-template").addClass("d-none");
        }
    });

    $("#pageform-flag").change(function(){
        if ($(this).val() === "template") {
            $(".field-pageform-file_name").addClass("d-none");
            $(".field-pageform-template").removeClass("d-none");
        } else if ($(this).val() === "custom") {
            $(".field-pageform-file_name").removeClass("d-none");
            $(".field-pageform-template").addClass("d-none");
        }
    });

    $("#pageform-file_name").change(function(){
        $("#pageform-template").val($(this).val());
    });

    $("#pageform-template").change(function(){
        $("#pageform-file_name").val($(this).val());
    });
    ',
    View::POS_READY,
);
$this->registerJsFile(
    "@web/js/del.js",
    ['depends' => [\yii\web\JqueryAsset::class]]
);
?>
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="col-lg-12">
                    <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'page-create-form']) ?>
                    <div class="row">
                        <div class="col-lg-4">
                        <?= $form->field($model, 'lang_code', [
                                    'options' => ['class' => 'form-group has-feedback'],
                                    'template' => '{beginWrapper}{input}{error}{endWrapper}',
                                    'wrapperOptions' => ['class' => 'input-group mb-3']
                                ])
                            ->label(false)
                            ->dropdownList($language,
                            ['prompt'=>'Select Language']) ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'flag', [
                                    'options' => ['class' => 'form-group has-feedback'],
                                    'template' => '{beginWrapper}{input}{error}{endWrapper}',
                                    'wrapperOptions' => ['class' => 'input-group mb-3']
                                ])
                            ->label(false)
                            ->dropdownList(['custom' => 'custom', 'template' => 'template'],
                            // ->dropdownList([0 => ['custom' => 'custom'], 1 => ['template' => 'template']],
                            ['prompt'=>'Select Flag', 'disabled' => 'disabled']) ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'file_name', [
                                    'options' => ['class' => 'form-group has-feedback'],
                                    'template' => '{beginWrapper}{input}{error}{endWrapper}',
                                    'wrapperOptions' => ['class' => 'input-group mb-3']
                                ])
                            ->label(false)
                            ->textInput(['placeholder' => $model->getAttributeLabel('file_name')]) ?>

                            <?= $form->field($model, 'template', [
                                    'options' => ['class' => 'form-group has-feedback d-none'],
                                    'template' => '{beginWrapper}{input}{error}{endWrapper}',
                                    'wrapperOptions' => ['class' => 'input-group mb-3']
                                ])
                            ->label(false)
                            ->dropdownList($templates,
                            ['prompt'=>'Select Template']) ?>
                        </div>
                        <div class="col-lg-2">
                            <?= Html::submitButton('Create', ['class' => 'btn btn-primary btn-block']) ?>
                        </div>
                    </div>
                    <?php \yii\bootstrap4\ActiveForm::end(); ?>
                </div>
                <div class="col-lg-12" style="overflow: auto;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <!-- <th>#</th> -->
                                <th>File Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($files as $file){ ?>
                            <?php if($file != "." && $file != ".."){ ?>
                                <tr>
                                <!-- <td><?= $i++ ?></td> -->
                                <td><?= $file ?></td>
                                <td>
                                    <div class="row">
                                        <div class="col-6">
                                            <a type="button" href="/admin/pages/edit?file=<?= $file ?>" class="btn btn-block btn-primary">
                                                Edit
                                            </a>
                                        </div>  
                                        <div class="col-6">
                                            <a type="button" href="javascript:del('/admin/pages/delete?file=<?= $file ?>')" class="btn btn-block btn-danger">
                                                Delete
                                            </a>
                                        </div>    
                                    </div>              
                                </td>
                            </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> 
</div>