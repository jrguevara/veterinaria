<?php

use app\models\TblDepartamentos;
use app\models\TblMunicipios;
use kartik\widgets\DepDrop;
use kartik\select2\Select2;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\SwitchInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <?php $form = ActiveForm::begin(['id' => 'modal_datos','type' => ActiveForm::TYPE_HORIZONTAL]); ?>
            <div class="card-body">
                <form role="form">
                    <div class="row">
                        <div class="col-md-6">
                            <?= Html::activeLabel($model, 'nombre', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'nombre', ['showLabels' => false])->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= Html::activeLabel($model, 'apellido', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'apellido', ['showLabels' => false])->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-12">
                            <?= Html::activeLabel($model, 'direcccion', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'direccion', ['showLabels' => false])->textarea(['rows' => 2]) ?>
                        </div>
                        <div class="col-md-4">
                            <?= Html::activeLabel($model, 'dui', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'dui', ['showLabels' => false])->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-4">
                            <?= Html::activeLabel($model, 'telefono', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'telefono', ['showLabels' => false])->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-4">
                            <?= Html::activeLabel($model, 'correo_electronico', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'correo_electronico', ['showLabels' => false])->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= Html::activeLabel($model, 'id_departamento', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'id_departamento', ['showLabels' => false])->widget(Select2::class, [
                                'data' => ArrayHelper::map(TblDepartamentos::find()->all(), 'id_departamento', 'nombre'),
                                'language' => 'es',
                                'options' => ['placeholder' => '- Seleccionar Departamento -'],
                                'pluginOptions' => ['allowClear' => true],
                            ]); ?>
                        </div>
                        <div class="col-md-6">
                            <?= Html::hiddenInput('model_id1', $model->isNewRecord ? '' : $model->id_departamento, ['id' => 'model_id1']); ?>
                            <?= Html::activeLabel($model, 'id_municipio', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'id_municipio', ['showLabels' => false])->widget(DepDrop::class, [
                                'language' => 'es',
                                'type' => DepDrop::TYPE_SELECT2,
                                'pluginOptions' => [
                                    'depends' => ['tblrepresentantes-id_departamento'],
                                    'initialize' => $model->isNewRecord ? false : true,
                                    'url' => Url::to(['/pacientes/representantes/municipios']),
                                    'placeholder' => '- Seleccionar Municipio -',
                                    'loadingText' => 'Cargando datos...',
                                    'params' => ['model_id1'] ///SPECIFYING THE PARAM
                                ]
                            ]); ?>
                        </div>
                        <div class="col-md-6">
                            <?= Html::activeLabel($model, 'activo', ['class' => 'control-label']) ?>
                            <?= $form->field($model, 'activo', ['showLabels' => false])->label('visible')->widget(SwitchInput::class, [
                                'value' => $model->activo, //checked status can change by db value
                                'options' => ['uncheck' => 0, 'value' => 1], //value if not set ,default is 1
                                'pluginOptions' => [
                                    'handleWidth' => 60,
                                    'onColor' => 'success',
                                    'offColor' => 'danger',
                                    'onText' => 'Activo',
                                    'offText' => 'Inactivo'
                                ]
                            ]); ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-save"></i> Guardar' : '<i class="fa fa-save"></i> Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        <?= Html::button('<i class="fa fa-ban"></i> Cancelar', ['class' => 'btn btn-danger', 'data-dismiss' => 'modal']) ?>
                    </div>
                </form>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<<SCRIPT
    $('form#modal_datos').on('beforeSubmit', function(e)
    {
        var \$form = $(this);
        $.post(
            \$form.attr("action"),
            \$form.serialize()
        )
            .done(function(result) {
                if(result == 1)
                {
                    $.pjax.reload({container:'#datosGrid'});
                    $('#modal_datos').trigger('reset');
                    $('#tblrepresentantes-id_departamento').val('').trigger('change');
                    $('#tblrepresentantes-id_municipio').depdrop();
                } else
                {
                    $('#message').html(result);
                }
            }).fail(function()
            {
                console.log("Server Error");
            });
            return false;
        });
    SCRIPT;
$this->registerJs($js);
?>