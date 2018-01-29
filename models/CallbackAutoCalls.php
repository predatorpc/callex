<?php
/**
 * Created by PhpStorm.
 * User: rr
 * Date: 15.12.17
 * Time: 9:35
 */

namespace app\models;


use yii\base\Model;

class CallbackAutoCalls extends Model
{
    const PATH_DONE = '';

    public function startCallback(){
        //открываем папку и читаем из нее файлы
        // если файл прочитан и все сохранено то удаляем файл
        // е сли нет то оставляем его там
        $files = $this->getFiles();
        if(!empty($files)){
            // открываем файл и ищем по шаблону номер телефона и дату
            foreach ($files as $file){
                if(file_exists($file)){
                    $content = file_get_contents($file);
                    if(!empty($content)){
                        preg_match('/(?P<name_phone>(#phone))(?P<phone>([\d]{11}))(?P<name_date>(#date))(?P<date>([\d-]{10}))/',  $content, $matches);
                        if(!empty($matches['phone']) && !empty($matches['date'])){
                            //ищем клиента и создаем ему комментарий с типом авто обзвон
                            $phone = preg_replace('/(\+7)|(\()|(\))|(-)|(\s)|(^8)/','',$matches['phone']);
                            $client = Clients::find()->where(['like', 'phone', '%'.$phone])->one();
                            if(!empty($client)){
                                // создаем комментарий и удаляем файл
                                $comment = New Comments();
                                $comment->client_id = $client->id;
                                $comment->action_id = 1;
                                $comment->text = 'Автоматический обзвон '.Date('d.m.Y', strtotime($matches['date'])) ;
                                $comment->status = 1 ;
                                $client->last_call = Date('Y-m-d H:i:s');
                                if($comment->save(true) && $client->save(true)){
                                    //удаляем файл
                                    $this->delFile($file);
                                }
                            }
                        }
                    }
                }
            }

        }
    }

    private function getFiles(){
        $fileList = glob(self::PATH_DONE.'/*.call');
        if(!empty($fileList)){
            return $fileList;
        }
        return false;
    }

    private function delFile($file=false){
        if(!empty($file)){
            if(unlink($file)){
                return true;
            }
        }
        return false;
    }

}