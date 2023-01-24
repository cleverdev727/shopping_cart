<?php

use yii\helpers\Html;

$this->title = 'Languages';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">

                        </div>
                        <div class="col-6">
                            <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'lang-create-form']) ?>

                            <?= $form->field($model,'lang_code', [
                                'options' => ['class' => 'form-group has-feedback'],
                                'template' => '{beginWrapper}{input}{error}{endWrapper}',
                                'wrapperOptions' => ['class' => 'input-group mb-3']
                            ])
                                ->label(false)
                                ->textInput(['placeholder' => $model->getAttributeLabel('lang_code')]) ?>

                            <?= $form->field($model, 'lang', [
                                'options' => ['class' => 'form-group has-feedback'],
                                'template' => '{beginWrapper}{input}{error}{endWrapper}',
                                'wrapperOptions' => ['class' => 'input-group mb-3']
                            ])
                                ->label(false)
                                ->textInput(['placeholder' => $model->getAttributeLabel('lang')]) ?>

                            <div class="row">
                                <div class="col-12">
                                    <?= Html::submitButton('Create', ['class' => 'btn btn-primary btn-block']) ?>
                                </div>
                            </div>

                            <?php \yii\bootstrap4\ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>