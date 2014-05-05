<h3>郵便番号マスタCSV Import</h3>
<?php
echo $this->Form->create('Zips', array('action' => 'import', 'type' => 'file'));
echo $this->Form->input('CsvFile', array('label' => '', 'type' => 'file'));
echo $this->Form->end('Upload');
?>

<h3>都道府県一覧</h3>
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
            <td><br></td>
            <td><br></td>
            <td><br></td>
</table>