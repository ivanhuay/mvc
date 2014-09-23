<?
if (count($table)!=0)
{
?>
<table class="<?=isset($class)?$class:"";?>">
  <thead>
    
    <tr>
    <?
    $ini=0;
    if(isset($header))
    {
      $order=array();
      foreach ($header as $key=>$value) {
        $order[]=$value;
        echo "<th class='header'>".$key."</th>";
      }
      $ini++;
    }
    ?>
    </tr>
  </thead>
  
    <?
    if(isset($order) && !is_array($table[0]))
    {
      echo "<tbody>";
      foreach ($table as $row) {
        echo "<tr>";
        for($i=0;$i<count($order);$i++)
        {  
          $td_data=(isset($round) && is_numeric($row->$order[$i]) && !is_int($row->$order[$i]))?round($row->$order[$i],$round):$row->$order[$i];
          echo "<td>".$td_data."</td>";
        }
        echo "</tr>";
      }
    }else if(isset($order)){
      echo "<tbody>";
      foreach ($table as $row) {
        echo "<tr>";
        for($i=0;$i<count($order);$i++)
        {  
          $td_data=(isset($round) && is_numeric($row[$order[$i]]) && !is_int($row[$order[$i]]))?round($row[$order[$i]],$round):$row[$order[$i]];
          echo "<td>".$td_data."</td>";
        }
        echo "</tr>";
      }
    }else
    {
      echo "<thead><tr>";
      foreach ($table[0] as $key => $value) {
        echo "<th class='header'>".$key."</td>";
      }
      echo "</tr></thead>

      <tbody>";
      foreach ($table as $row) {
        echo "<tr>";
        foreach($row as $key=>$value)
        {
          $td_data=(isset($round) && is_numeric($value) && !is_int($value))?round($value,$round):$value;
          echo "<td>".$value."</td>";
        }
        echo "</tr>";
      }
    }

    ?>
    
  </tbody>
</table>
<?
}else{
  echo "<p>Sin Resultados</p>";
}
          
