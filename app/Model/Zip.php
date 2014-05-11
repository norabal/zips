<?php

class zip extends AppModel {

    public function loadByCake($fileName) {
        set_time_limit(0);
        $this->begin();
        try {
            $this->query("TRUNCATE zips;");
            $csvData = file($fileName, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
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

    public function loadByQuery($fileName) {
        set_time_limit(0);
        try {
            $this->query("TRUNCATE zips;");
            $csvData = file($fileName, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
            $tmpRecord = '';
            $skipFlg = 0;
            $sql = "INSERT INTO zips (zips, pref, city, town) VALUES ";
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
                        $sql .= "(" . $record[2] . ",'" . $record[6] . "','" . $record[7] . "','" . $record[8] . "'),";
                    }
                }
            }
            $sql = substr_replace($sql, ';', -1, 1);
            $this->query($sql);
        } catch (Exception $e) {
            echo '捕捉した例外: ', $e->getMessage(), "\n";
            $this->rollback();
        }
    }

}
