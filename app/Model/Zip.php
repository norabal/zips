<?php

class zip extends AppModel {

    public function loadCSV($filename) {
        set_time_limit(0);
        $this->begin();
        try {
            $this->deleteAll('1=1', false);
            $csvData = file($filename, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
            $tmpRecord = '';
            $skipFlg = 0;
            if (is_array($csvData)) {
                foreach ($csvData as $line) {
                    $line = mb_convert_encoding($line, 'UTF-8', 'SJIS-win');
                    $split = preg_split('/,/', $line);

                    //不要な文字の削除
                    $patterns = array(
                        '/以下に掲載がない場合/',
                        '/一円/',
                        '/（.*階）/',
                        '/"/'
                    );
                    $replacement = array('');
                    $record = preg_replace($patterns, $replacement, $split);

                    //（　）が次の行にまたがっている場合の対処
                    if (strstr($record[8], '（') && !strstr($record[8], '）')) {
                        $tmpRecord = $record[8];
                        $skipFlg = 1;
                    } elseif ($skipFlg == 1 && !strstr($record[8], '）')) {
                        $tmpRecord .= $record[8];
                    } elseif (!strstr($record[8], '（') && strstr($record[8], '）')) {
                        $record[8] = $tmpRecord . $record[8];
                        $skipFlg = 0;
                    }

                    //データの格納
                    if ($skipFlg == 0) {
                        $data = array(
                            'zips' => $record[2],
                            'pref' => $record[6],
                            'city' => $record[7],
                            'town' => $record[8]
                        );
                        $this->create($data);
                        $this->save();
                    }
                }

                $this->commit();
            }
        } catch (Exception $e) {
            echo '捕捉した例外: ', $e->getMessage(), "\n";
            $this->rollback();
        }
    }

}
