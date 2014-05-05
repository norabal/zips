<table>
    <tr>
        <th>zips</th>
        <th>pref</th>
        <th>city</th>
        <th>town</th>
    </tr>
    <?php for($i = 0; $i < count($sltPref); $i++) : ?>
    <tr>
        <td>
            <?php echo $sltPref[$i]['Zip']['zips']; ?>
        </td>
        <td>
            <?php echo $sltPref[$i]['Zip']['pref']; ?>
        </td>
        <td>
            <?php echo $sltPref[$i]['Zip']['city']; ?>
        </td>
        <td>
            <?php echo $sltPref[$i]['Zip']['town']; ?>
        </td>
    </tr>
    <?php endfor ?>
</table>