<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ZipsController extends AppController {

    public function index() {
        $data = $this->Zip->find('all');
        $this->set('data', $data);

        if ($data) {
            $prefList = $this->Zip->find('all', array(
                'fields' => 'pref',
                'group' => 'pref',
                'order' => 'pref'
            ));
            $this->set('prefList', $prefList);
        }
    }

    public function import() {

        if ($this->request->is('post')) {
            $up_file = $this->data['Zips']['CsvFile']['tmp_name'];
            $fileName = '/Applications/MAMP/htdocs/zips/vendors/KEN_ALL.CSV';


//            $zip = new ZipArchive();
//
//            // ZIPファイルをオープン˙
//            $res = $zip->open($up_file);
//
//            // zipファイルのオープンに成功した場合
//            if ($res === true) {
//
//                // 圧縮ファイル内の全てのファイルを指定した解凍先に展開する
//                $zip->extractTo('/Applications/MAMP/htdocs/zips/vendors/');
//
//                // ZIPファイルをクローズ
//                $zip->close();
//            }






//            if (is_uploaded_file($up_file)) {
//                move_uploaded_file($up_file, $fileName);
                $time_start = microtime(true);      //読み込み速度計測開始
                $this->Zip->loadCSV($fileName);
                $time_end = microtime(true);       //読み込み速度計測終了
                $exeTime = $time_end - $time_start;
                $this->set('exeTime', $exeTime);
//                $this->Session->setFlash('Uploaded');
//                $this->redirect(array('action' => 'index'));
//            }
        }
        //$this->redirect(array('action'=>'index'));        
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
