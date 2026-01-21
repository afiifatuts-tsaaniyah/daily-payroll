<?php
header("Content-type: application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=AGRDYL202202_1.xls");
?>
<!DOCTYPE html>
<html>
<head>
  <title>LIST BERITA</title>
</head>
<body>
  <table border="1">
                   <th>Biodata Id</th>
                   <th>Badge No</th>
                   <th>Full Name</th>
                   <th>Dept</th>
                   <th>Day</th>
                   <th>D01</th>
                   <th>D02</th>
                   <th>D03</th>
                   <th>D04</th>
                   <th>D05</th>
                   <th>D06</th>
                   <th>D07</th>
                   <th>D08</th>
                   <th>D09</th>
                   <th>D10</th>
                   <th>D11</th>
                   <th>D12</th>
                   <th>D13</th>
                   <th>D14</th>
                   <th>D15</th>
                   <th>D16</th>
                   <th>D17</th>
                   <th>D18</th>
                   <th>D19</th>
                   <th>D20</th>
                   <th>D21</th>
                   <th>D22</th>
                   <th>D23</th>
                   <th>D24</th>
                   <th>D25</th>
                   <th>D26</th>
                   <th>D27</th>
                   <th>D28</th>
                   <th>D29</th>
                   <th>D30</th>
                   <th>D31</th>
  <?php 
  foreach ($mtBiodata as $row)
   { 
  ?>
  <tr>
    <td><?php echo $row['biodata_id'] ?></td>
    <td><?php echo $row['full_name'] ?></td>
    <td><?php echo $row['dept'] ?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>

    
  </tr>
   <?php
    }
  ?>
</table>

  
</body>
</html>

 