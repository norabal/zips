<?php

class ZipsController extends AppController {

    public function index() {
        $prefList = $this->Zip->find('all', array(
            'fields' => 'pref',
            'group' => 'pref',
            'order' => 'pref',
        ));
        $this->set('prefList', $prefList);
    }

    public function drctImport() {

        //読み込み時間計測開始
        $time_start = microtime(true);

            $zip_dl = fopen('http://www.post.japanpost.jp/zipcode/dl/kogaki/zip/ken_all.zip', 'rb');
            $fp = fopen('/Applications/MAMP/htdocs/zips/app/tmp/zips_dl/ken_all.zip', 'w');

            flock($fp, LOCK_EX);

            while ($r = fgets($zip_dl, 1024)) {
                fwrite($fp, $r);
            }
            fclose($zip_dl);
            fclose($fp);

            //zipファイルの解凍
            $zip = new ZipArchive();
            $res = $zip->open('/Applications/MAMP/htdocs/zips/app/tmp/zips_dl/ken_all.zip');
            if ($res === true) {
                $zip->extractTo('/Applications/MAMP/htdocs/zips/app/tmp/zips_dl/');
                $zip->close();
            }

            $fileName = '/Applications/MAMP/htdocs/zips/app/tmp/zips_dl/KEN_ALL.CSV';
            $this->Zip->loadByQuery($fileName);
            unlink('/Applications/MAMP/htdocs/zips/app/tmp/zips_dl/ken_all.zip');
            unlink('/Applications/MAMP/htdocs/zips/app/tmp/zips_dl/KEN_ALL.CSV');

            //読み込み時間計測終了
            $time_end = microtime(true);

            //読み込み時間出力
            $exeTime = $time_end - $time_start;
            $this->set('exeTime', $exeTime);
//                $this->Session->setFlash('Uploaded');
//                $this->redirect(array('action' => 'index'));
    }

    public function slctZipImport() {
        $time_start = microtime(true);      //読み込み時間計測開始
        if ($this->request->is('post')) {
            $up_file = $this->data['Zips']['CsvFile']['tmp_name'];
            $fileName = '/Applications/MAMP/htdocs/zips/app/tmp/zips_dl/KEN_ALL.CSV';
            $zip = new ZipArchive();

            //zipファイルの解凍
            $res = $zip->open($up_file);
            if ($res === true) {
                $zip->extractTo('/Applications/MAMP/htdocs/zips/app/tmp/zips_dl/');
                $zip->close();
            }

            $this->Zip->loadByQuery($fileName);
            $time_end = microtime(true);       //読み込み時間計測終了
            $exeTime = $time_end - $time_start;
            $this->set('exeTime', $exeTime);
//                $this->Session->setFlash($exeTime . '秒かかりました。');
//                $this->redirect(array('action' => 'index'));
        }
    }

    public function slctCsvImport() {

        $time_start = microtime(true);      //読み込み時間計測開始

        if ($this->request->is('post')) {
            $up_file = $this->data['Zips']['CsvFile']['tmp_name'];
            $fileName = 'KEN_ALL.CSV';

            if (is_uploaded_file($up_file)) {
                move_uploaded_file($up_file, $fileName);
                $this->Zip->loadByQuery($fileName);
                $time_end = microtime(true);       //読み込み時間計測終了
                $exeTime = $time_end - $time_start;
                $this->set('exeTime', $exeTime);
//                $this->Session->setFlash('Uploaded');
//                $this->redirect(array('action' => 'index'));
//            }
            }
//$this->redirect(array('action'=>'index'));        
        }
    }

    public function dltDB() {
        $time_start = microtime(true);      //読み込み時間計測開始
        $this->Zip->query("TRUNCATE zips;");
        $time_end = microtime(true);       //読み込み時間計測終了
        $exeTime = $time_end - $time_start;
        $this->set('exeTime', $exeTime);
//        $this->Session->setFlash($exeTime . '秒かかりました。');
//        $this->redirect(array('action' => 'index'));
    }

    public function view() {
        if ($this->request->pass[0]) {
            $options = array(
                'conditions' => array('Zip.pref' => $this->request->pass[0])
            );
            $this->set('sltPref', $this->Zip->find('all', $options));
        }
    }

}
