<?php
namespace api\modules\v1\models;
use Yii;
use yii\helpers\ArrayHelper;
use api\modules\v1\models\Gift;


class GiftCategory extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE=10;
    const STATUS_INACTIVE=9;
    const STATUS_DELETED=0;

    const LEVEL_MAIN = 1;
    const LEVEL_SUB = 2;

    public $imageFile;
   

    
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gift_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'status'], 'required'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['status', 'id','parent_id','priority'], 'integer'],
            [['name'], 'string', 'max' => 100],
           // [['name', 'status'], 'save'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'parent_id' => Yii::t('app', 'Main Category'),
            
        ];
    }
    
    public function fields()
    {
        
        $fields = parent::fields();
        unset($fields['parent_id'],$fields['status'],$fields['priority'],$fields['leval']);
        $fields[] = 'imageUrl';
        return $fields;
    }


    public function extraFields()
    {
        return ['subCategory','gift'];
    }
   
    public function getMainCategory(){
        return $this->find()->select(['id','name','image'])->where(['status'=>$this::STATUS_ACTIVE,'level'=>$this::LEVEL_MAIN])->all();
        
    }
    /*public function getSubCategory($parentId){
        return $this->find()->select(['id','name'])->where(['status'=>$this::STATUS_ACTIVE,'level'=>$this::LEVEL_SUB,'parent_id'=>$parentId])->all();
        
    }*/
    public function getParent(){

        return $this->hasOne(GiftCategory::className(), ['id' => 'parent_id']);

    }

    public function getSubCategory(){

        return $this->hasMany(GiftCategory::className(), ['parent_id' => 'id'])->from(['subCategory' => GiftCategory::tableName()])->select(['id','name','parent_id']);

    }

    public function getImageUrl()
    {
        if($this->image){
            
            return Yii::$app->fileUpload->getFileUrl(Yii::$app->fileUpload::TYPE_GIFT,$this->image);

           
        }else{
            return '';
        }
        
    }

    public function getGift()
    {
        return $this->hasMany(Gift::className(), ['category_id' => 'id'])->andOnCondition(['gift.status' => Gift::STATUS_ACTIVE])->limit(10);

    }


    

    

}
