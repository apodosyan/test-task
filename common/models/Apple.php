<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%apples}}".
 *
 * @property int $id
 * @property string $color
 * @property int $appearance_date
 * @property int $fall_date
 * @property int $status
 * @property int $size
 */
class Apple extends \yii\db\ActiveRecord
{
    const STATUS_ON_TREE = 1; // висит на дереве
    const STATUS_ON_GROUND = 2; // упало/лежит на земле
    const STATUS_ROTTEN = 3; // гнилое яблоко

    const TIME_BEFORE_ROTTED = 5 * 60 * 60; //После лежания 5 часов - портится.

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%apples}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['color'], 'required'],
            [['appearance_date', 'fall_date', 'status', 'size'], 'integer'],
            [['color'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => 'цвет',
            'appearance_date' => 'дата появления',
            'fall_date' => 'дата падения',
            'status' => 'статус',
            'size' => 'сколько съели (%)',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function statusLabels()
    {
        return [
            self::STATUS_ON_TREE => 'висит на дереве',
            self::STATUS_ON_GROUND => 'упало/лежит на земле',
            self::STATUS_ROTTEN => 'гнилое яблоко',
        ];
    }

    public function getGradientAndColor()
    {
        if ($this->isRotted) {
            $gradient = 'black 100%';
            $color = 'black';
        } else {
            $white = 'white ' . (100 - $this->size) . '%';
            $color = $this->color;
            $currentColor = $this->color . ' ' . $this->size . '%';
            $gradient = ($this->size < 50) ? "$white, $currentColor" : "$currentColor, $white";
        }
        return [$gradient, $color];
    }

    public function getIsRotted()
    {
        return $this->fall_date && ($this->fall_date + self::TIME_BEFORE_ROTTED) < time();
    }

    public function getAlertMessage()
    {
        if ($this->isRotted) {
            return 'Когда испорчено - съесть не получится.';
        }
        if ($this->status == self::STATUS_ON_TREE) {
            return 'Когда висит на дереве - съесть не получится.';
        }
        return '';
    }
}
