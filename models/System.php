<?php

namespace app\models;

use Yii;
use yii\debug\models\timeline\Search;

class System extends \yii\db\ActiveRecord
{

    public static function mesprint($t){
        echo "<pre>";
        print_r($t);
        echo "</pre>";
    }
    public static function sendSms($phone, $message){
        // Загрузка данных;
        $c = curl_init(Yii::$app->params['smsUrl']);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_TIMEOUT, 30);
        curl_setopt ($c, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($c, CURLOPT_POSTFIELDS, 'operation=send&login='.Yii::$app->params['smsLogin'].'&onum='.Yii::$app->params['smsOnum'].'&unum='.str_replace('+', '', $phone).'&msg='.urlencode($message).'&sign='.sha1(str_replace('+', '', $phone).urlencode($message).Yii::$app->params['smsPass']));
        $data = curl_exec($c);
        curl_close($c);
        // Вывод данных;
        return $data;

    }

    public static function sendTelegrammPayments($text =''){
        if(!empty($text)){
            $tel = new Telegramm();
            $tel->sendMessage(106705570, $text);//YA
            $tel->sendMessage(232291795, $text);//миша
        }
        return false;

    }

    public static function sendTelegrammDev($text =''){
        if(!empty($text)){
            $tel = new Telegramm();
            $tel->sendMessage(106705570, $text);//YA
            $tel->sendMessage(232291795, $text);//миша
            //$tel->sendMessage(158631054, $text);//таня
            //$tel->sendMessage(129466204, $text);//Дима
        }
        return false;

    }

    public static function sendTelegrammPerconal($text =''){
        if(!empty($text)){
            $tel = new Telegramm();
            $tel->sendMessage(106705570, $text);//YA
        }
        return false;

    }

    public static function addMessageToQueue($client_id, $message){
        if(is_numeric($client_id) && is_string($message)){
            $queue = new Sentsms();
            $queue->client_id = $client_id;
            $queue->text = $message;
            $queue->user_id = (!empty(Yii::$app->user->id)?Yii::$app->user->id:0);
            $queue->date = date('Y-m-d H:i:s');
            $queue->status = 1;
            $queue->save(true);
        }
        return false;
    }

    public static function txtLogs($obj, $model){
        $file = "----------------------------------------------------\n------------------------START-----------------------\n";
        $fileName =  $model.'_'.time().'_'.rand(0, 1000).'.txt';
        $file.=  time(). '--'.Date('Y.m.d H:i:s'."\n", time()). "\n";
        $file.= var_export($obj, true);
        $dirName =$_SERVER['DOCUMENT_ROOT'] . '/logs/errors/'.Date('Y-m-d', time());
        if(!file_exists($dirName)){
            mkdir($dirName);
        }
        file_put_contents($dirName.'/'.$fileName, $file."\n");
    }

    public static function txtLogsConsole($obj, $model){
        $file = "----------------------------------------------------\n------------------------START-----------------------\n";
        $fileName =  $model.'_'.Date('Y-m-d',time()).'_'.rand(0, 1000).'.txt';
        $file.=  time(). '--'.Date('Y.m.d H:i:s'."\n", time()). "\n";
        $file.= var_export($obj, true);
        $dirName ='/home/ef/logs/console_'.Date('Y-m-d', time());
        if(!file_exists($dirName)){
            mkdir($dirName);
        }
        file_put_contents($dirName.'/'.$fileName, $file."\n");
    }
    // Обработка срезаем запятую цены;
    public static function money($value, $decimal = 0)
    {
        return number_format($value, $decimal, '.', ' ');
    }

    public static function watermark()
    {
        $path = false;
        $watermark = ScannerWatermark::find()->where(['today'=>Date('Y-m-d 00:00:00', time()), 'status'=>1])->one();
        if(empty($watermark)){
            $maxId = ScannerWatermark::find()->where(['status'=>1])->max('id');
            $minId = ScannerWatermark::find()->where(['status'=>1])->min('id');
            $watermark =  ScannerWatermark::find()->where(['id'=>rand($minId,$maxId), 'status'=>1])->one();
            $watermark->today = Date('Y-m-d 00:00:00', time());
            if($watermark->save(true)){
                if(!empty($watermark->path)) {
                    $path = $watermark->path;
                }
            }
        }
        else{
            if(!empty($watermark->path)) {
                $path = $watermark->path;
            }
        }

        return $path;

    }

    public static function user($string,$type=false)
    {
        // Обработка данные;
        $string = trim($string);
        $string = rtrim($string, "!,.-");
        if($type) {
            // Выводить только имя;
            $string = preg_replace('#(.*)\s+(.*).*\s+(.*).*#usi', '$2', $string);
        }else{
            $string = preg_replace('#(.*)\s+(.).*\s+(.).*#usi', '$1 $2.$3.', $string);
        }
        return $string;
    }

    // Обработка телефон;
    public static function phone($phone)
    {
        return str_replace('+7', '', $phone);
    }
    public static function phone_is($phone)
    {
        $phone = '+7'.substr($phone, -10);
        return $phone;
    }

    // Окончание для числительных;
    public static function numToStr($num, $end1, $end2, $end3) {
        $num100 = $num % 100;
        $num10 = $num % 10;
        if ($num100 >= 5 && $num100 <= 20) $end = $end3;
        else if ($num10 == 0) $end = $end3;
        else if ($num10 == 1) $end = $end1;
        else if ($num10 >= 2 && $num10 <= 4) $end = $end2;
        else if ($num10 >= 5 && $num10 <= 9) $end = $end3;
        else $end = $end3;
        return number_format($num, 0, '.', ' ').' '.$end;
    }

    // Сокращеный чисел;
    public static function numberSize($size)
    {
        $name = array("", "К", "М", "Г", "Т", "П", "Э", "З", "И");
        return $size ? round($size / pow(1000, ($i = floor(log($size, 1000)))), 2) .' '. $name[$i] : '0';
    }

    public static function calcCountPeriod($start=false, $end=false, $period=false){
        if(!empty($start) && !empty($period)){
            if(!empty($end)){
                $delta = strtotime($start)-strtotime($end);
            }
            else{
                $delta = strtotime($start)-time();
            }

            if($delta>0){
                return ceil($delta/abs($period));
            }
        }
        return 0;
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            foreach ($this->activeAttributes() as $attr){
                if(isset($this->attributes[$attr], $this->oldAttributes[$attr])){
                    if($this->attributes[$attr] != $this->oldAttributes[$attr]){
                        $log = new Logs();
                        $log->user_id = isset(Yii::$app->user->id)?Yii::$app->user->id:0;
                        $log->action = $insert ? 'create':'update';
                        $log->table_edit = $this::tableName();
                        $log->colum_edit = $attr;
                        $log->row_edit_id = $this->attributes['id'];
                        $log->new_val = strlen($this->attributes[$attr])>500?'over_size': strval($this->attributes[$attr]);
                        $log->old_val = strlen($this->oldAttributes[$attr])>500?'over_size':strval($this->oldAttributes[$attr]);
                        $log->save(true);
                    }
                }
            }
            return true;
        }
        return false;

    }

    // Обработка Timestamp;
    public static function makeTimestamp($string)
    {
        if(empty($string)) {
            $time = time();

        } elseif (preg_match('/^\d{14}$/', $string)) {
            $time = mktime(substr($string, 8, 2),substr($string, 10, 2),substr($string, 12, 2),
                substr($string, 4, 2),substr($string, 6, 2),substr($string, 0, 4));

        } elseif (is_numeric($string)) {
            $time = (int)$string;

        } else {
            $time = strtotime($string);
            if ($time == -1 || $time === false) {
                $time = time();
            }
        }
        return $time;
    }

    // Обработка дата и время; %d.%m.%Y %A, %e %B
    public static function dateFormat($string, $format = '%d.%m.%Y', $default_date = '')
    {
        if ($string != '') {
            $timestamp =  self::makeTimestamp($string);
        } elseif ($default_date != '') {
            $timestamp =  self::makeTimestamp($default_date);
        } else {
            return;
        }
        $_win_from = array('%D',       '%h', '%n', '%r',          '%R',    '%t', '%T');
        $_win_to   = array('%m/%d/%y', '%b', "\n", '%I:%M:%S %p', '%H:%M', "\t", '%H:%M:%S');
        if (strpos($format, '%e') !== false) {
            $_win_from[] = '%e';
            $_win_to[]   = sprintf('%\' 2d', date('j', $timestamp));
        }
        if (strpos($format, '%l') !== false) {
            $_win_from[] = '%l';
            $_win_to[]   = sprintf('%\' 2d', date('h', $timestamp));
        }

        // Замена дня недели;
        if (strpos($format, '%A') !== false) {
            $days = array('Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');
            $to = $days[date("w",$timestamp)];
            $format = str_replace('%A', $to, $format);
        }
        // Замена месяца;
        if (strpos($format, '%B') !== false) {
            $months = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
            $to = $months[date("n",$timestamp)-1];
            $format = str_replace('%B', $to, $format);
        }
        $format = str_replace($_win_from, $_win_to, $format);
        return strftime($format, $timestamp);
    }

    // Генерация sms-код
    public static function codeSms($phone) {
        if(!empty($phone)) {
            $num = '';
            // Цикл цифры разбиваем на число в виде массивов;
            for ($i = 0; $i < strlen($phone); $i++) {
                $out[$i] = $phone[$i];
                $num = array_slice($out, 1);
            }
            $code = array_sum($num) * 147;
            return substr($code, 0, 4);
        }else{
            return false;
        }
    }
}