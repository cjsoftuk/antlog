<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Robot */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Robots', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="robot-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
		if ((User::isCurrentUser($model->teamId)) || User::isUserAdmin())
		{
			if ($model->isOKToDelete($model->id) || $model->isOKToEdit($model->id) || $model->isOKToRetire($model->id))
			{
				echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
			}
			if ($model->isOKToDelete($model->id))
			{
				echo Html::a('Delete', ['delete', 'id' => $model->id],
				[
            		'class' => 'btn btn-danger',
            		'data' =>
					[
                		'confirm' => 'Are you sure you want to delete this item?',
                		'method' => 'post',
            		],
        		]);
			}
		}
		?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'name',
        	'type.name',
            [
				'attribute' => 'team.team_name',
            	'label' => 'Team',
		    ],
            'class.name',
        	[
				'attribute' => 'active',
        		'format' => 'boolean',
 			]
        ],
    ]) ?>

</div>
