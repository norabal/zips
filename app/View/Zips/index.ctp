<h3>郵便局HPから直接インポート</h3>
<p><?php echo $this->Html->link('インポート開始', array('controller' => 'zips', 'action' => 'drctImport')); ?></p>

<h3>選択したzipファイルのインポート</h3>
<?php
echo $this->Form->create('Zips', array('action' => 'slctZipImport', 'type' => 'file'));
echo $this->Form->input('CsvFile', array('label' => '', 'type' => 'file'));
echo $this->Form->end('Upload');
?>

<h3>選択したcsvファイルのインポート</h3>
<?php
echo $this->Form->create('Zips', array('action' => 'slctCsvImport', 'type' => 'file'));
echo $this->Form->input('CsvFile', array('label' => '', 'type' => 'file'));
echo $this->Form->end('Upload');
?>

<h3>登録データの削除</h3>
<p><?php echo $this->Html->link('一括削除', array('controller' => 'zips', 'action' => 'dltDB')); ?></p>

<h3>都道府県一覧</h3>
<?php if (isset($prefList)) : ?>
<table>
    <?php for ($i = 0; $i < count($prefList); $i++) : ?>
        <?php
        if ($i % 5 == 0) {
            echo "<tr>" . "\n";
        }
        ?>
        <td><?php echo $this->Html->link($prefList[$i]['Zip']['pref'], array('controller' => 'zips', 'action' => 'view', $prefList[$i]['Zip']['pref'])); ?></td>
        <?php
        if ($i == 4) {
            echo "</tr>\n";
        } elseif ($i % 5 == 0 && !$i == 0 && !$i == 5) {
            echo "</tr>\n";
        }
        ?>
    <?php endfor; ?>
    <?php for ($blankNum = 1; $blankNum <= 5 - count($prefList) % 5; $blankNum++) : ?>
            <td><br></td>
    <?php endfor; ?>
</table>
<?php else : ?>
<p>登録データがありません。</p>
<?php endif; ?>
