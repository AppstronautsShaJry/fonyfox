<?php
namespace api\modules\v1\controllers;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;


use yii\web\UploadedFile;
class FileUploadController extends ActiveController
{
    
     public $modelClass = '';   
    
    public function actions()
	{
		$actions = parent::actions();

		// disable default actions
		unset($actions['create'], $actions['update'], $actions['index'], $actions['delete'], $actions['view']);                    

		return $actions;
	}    

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'except'=>['upload-file'],
            'authMethods' => [
                HttpBearerAuth::className()
            ],
        ];
        return $behaviors;
    }

    
    public function actionUploadFile()
    {
        
       

        $model = new \yii\base\DynamicModel([
            'mediaFile','type'
        ]);
        $model->addRule(['mediaFile','type'], 'required')
            //->addRule(['mediaFile'], 'file');
            ->addRule(['mediaFile'], 'file');

           
        

        if (Yii::$app->request->isPost) {         
            //$model->mediaFile = UploadedFile::getInstances($model, 'mediaFile');  
            $model->mediaFile = UploadedFile::getInstanceByName('mediaFile'); 
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');
            $model->mediaFile = UploadedFile::getInstanceByName('mediaFile'); 
            if(!$model->validate()) {
                $response['statusCode']=422;
                $response['errors']=$model->errors;
                return $response;
            }
            $fileUrl;


            $type = ($model->type)?$model->type:1;

            //$files = $modelFileUpload->uploadFile($model->mediaFile,$type,false);
            $files = Yii::$app->fileUpload->uploadFile($model->mediaFile,$type,false);
            

            $response['message']='File uploaded successfully';
            $response['files']=$files;
            return $response; 


         
        }
    }   




private function saveRawDataAsImage($rawData)
{
    // Find the boundary that separates parts in the multipart data
    $boundary = '----------------------------' . substr($rawData, 2, 16);
    $parts = explode($boundary, $rawData);
    
        // Find the part containing the image binary data
        foreach ($parts as $part) {
            if (strpos($part, 'Content-Disposition: form-data; name="mediaFile"') !== false) {
                // Extract the image binary data from the part
                $imageData = substr($part, strpos($part, "\r\n\r\n") + 4);
                break;
                
            }
            
        }
        if (empty($imageData)) {
            return false; // Unable to extract image data
        }
    $filename = 'image_' . time();
    // Set the path where you want to save the file
    $imageDirectory = '/Applications/XAMPP/xamppfiles/htdocs/social_media_plus/frontend/web/uploads/image/';
    $filePath = $imageDirectory . $filename;

    // Save the image binary data as an image file
    if (file_put_contents($filePath, $imageData) !== false) {
        // Use getimagesize to determine the image format
        $imageInfo = getimagesize($filePath);
        $imageFormat = image_type_to_extension($imageInfo[2], false);
        
        // Rename the file with the correct extension
        $newFilePath = $filePath . '.' . $imageFormat;
        rename($filePath, $newFilePath);
        return $newFilePath;
    } else {
        // Handle file saving error
        return false;
    }
}




}


