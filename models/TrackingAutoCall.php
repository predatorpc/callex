<?php
/**
 * Created by PhpStorm.
 * User: rr
 * Date: 14.12.17
 * Time: 8:38
 */

namespace app\models;


use yii\base\Model;

class TrackingAutoCall extends Model
{
    const PATH_FORM = '/Users/rr/autocall';
    const PATH_TO = '/Users/rr/autocallto';

    //*************************
    // структура папки PATH_FORM
    //                         --Data
    //                              -file
    //                              -file
    //                              -file
    //                         --Data
    //                              -file
    //                              -file

    //перенос файлов по мере освобощдение папки назнанчени по значению $maxCountFile
    // c

    private $dateCall;// запустить обзвон за указанную дату
    private $maxCountFile = 2;
    private $waitTime = 60; // время ожидания в секундах
    private $previous = false; // предыдущие папки
    private $allDateCall=[]; // даты с завершением обзвона

    private $currnetFile;

    public function __construct(array $config = []){

        if (isset($config['dateCall'])) {
            $this->dateCall = Date('Y-m-d', strtotime($config['dateCall']));
        }

        if (isset($config['maxCountFile']) && is_numeric($config['maxCountFile'])) {
            $this->maxCountFile = $config['maxCountFile'];
        }
        if (isset($config['previous']) && is_numeric($config['previous'])) {
            $this->previous = $config['previous'];
        }
    }


    //запускаем отслеживание
    public function startTracking(){
        // если дата не указана то запускаем все что есть в папках
        // если указана то запускаем только на эту дату
        // получаем список дат-папок в массиве и перебираем от самых старших
        // проверяем местное время вызова оно должно быть 10:00-20:00

        // получаем список папок-дат в которых остались абоненты
        if(empty($this->dateCall)){
            $this->getListFolders();
        }
        else{
            $this->allDateCall[] = $this->dateCall;
        }
        if(!empty($this->allDateCall)){
            foreach ($this->allDateCall as $selectDate){
                $fileslist = $this->getListFiles(Date('Y-m-d', strtotime($selectDate)));// получили список файлов за указаную дату
                if(!empty($fileslist)){
                    foreach ($fileslist as $file){
                        if($this->checkWorkingHours()){
                            //проверка функции ожидания
                            $this->waitingForNext();
                            $this->moveFile($file);
                        }

                    }
                }
            }
        }
    }

    //поиск списка папок для обхода
    private function getListFolders (){
        $dirList = glob(self::PATH_FORM.'/*', GLOB_ONLYDIR);
        if(!empty($dirList)){
            //проверяем что в директории есть файлы
            //если файлы есть суем дату в массив
            foreach ($dirList as $dir){
                if(!empty(glob($dir.'/*.call'))){
                    $date = strtotime( str_replace(self::PATH_FORM, '', $dir) );
                    if(time()>$date){//добавляем только тех кому уже должны были позвонить будущее не трогаем
                        $this->allDateCall[] = Date('Y-m-d',  $date);
                    }
                }
                else{
                    $this->delDir($dir);
                }
            }
        }
        return false;
    }

    //получение списка файлов
    private function getListFiles($date = false){
        if(!empty($date)){
            $fileList = glob(self::PATH_FORM.'/'.$date.'/*.call');
            if(!empty($fileList)){
                return $fileList;
            }
        }
        return false;
    }

    //перемещение файла копируем и удаляем средствами php
    private function moveFile($file = false){
        if(!empty($file)){
            if(!file_exists(self::PATH_TO)){
                mkdir(self::PATH_TO, 0777, true);
            }
            if (copy($file, str_replace(self::PATH_FORM.'/'.$this->dateCall, self::PATH_TO, $file))) {
                //скопировано удачно надо удалить файл
                if($this->delFileFromWarehouse($file)){
                    return true;
                }
            }
        }
        return false;


    }

    //удаление файла из хранилища
    private function delFileFromWarehouse(){
        if(!empty($file)){
            if(unlink($file)){
                return true;
            }
        }
        return false;
    }

    private function delDir($paht=false){
        if(!empty($paht)){
            if(rmdir($paht)){
                return true;
            }
        }
        return false;
    }

    private function checkWorkingHours(){
        if(Date('G', time())>=10 && Date('G', time())<21){
            return true;
        }
        return false;
    }

    //ожидание следующего перемещения
    private function waitingForNext(){
        //количество файлов в папку mnt/aster /не должно превышать лопустипмого значения
        //время на ожидание не учитывается если не задано
        $timeStart = time();
        if(empty($this->waitTime)){
            $currentTime =0;
        }
        else{
            $currentTime = time();
        }
        echo "\n";
        $tmp='waiting';
        while (count(glob(self::PATH_TO.'/*.call'))>=$this->maxCountFile && $currentTime<($timeStart+$this->waitTime)){
            echo $tmp.'.';
            $tmp='';
            if(!empty($currentTime)){
                $currentTime = time();
            }
            sleep(1);
        }
        echo "\n";
        return false;
    }

    //перемещение файла средствами OS
    private function moveFileCmdLine(){
        $cmd = 'mv "/home/user/me/dir1" "/mnt/shares/nfsmount/dir2"';
        exec($cmd, $output, $return_val);

        if ($return_val == 0) {
            echo "success";
        } else {
            echo "failed";
        }
    }


}