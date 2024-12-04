<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Patroli</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            border: 1px solid #ddd;
            font-size: 9px !important;
        }

        thead tr {
            background-color: #f2f2f2;
            text-align: left;
        }

        td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 9px !important;
        }

        tbody tr {
            page-break-inside: avoid;  /* Prevent page break within a row */
        }

        tbody td {
            page-break-inside: avoid;  /* Prevent page break inside cells */
        }

        hr {
            border: 1px solid #ddd;
        }
        @page {
            margin-top:120px; /* No margin for the first page */
        }
    </style>
</head>
<body>
    <!-- <h4>Report Patroli (<?php echo $tanggal; ?>)</h4> -->
    
    <!-- font-size:9px !important;    -->
    <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; border: 1px solid #ddd; font-size: 9px !important;">
    <thead>
        <tr style="background-color: #f2f2f2; text-align: left;">
            <td style="width: 5%; text-align: center; padding: 8px; border: 1px solid #ddd; font-size: 9px !important;">No</td>
            <td style="padding: 8px; border: 1px solid #ddd; font-size: 9px !important;">Titik</td>
            <td style="padding: 8px; border: 1px solid #ddd; font-size: 9px !important;" colspan="4">Point</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        foreach ($tasks as $task) { 
            if ($no > 61) {
                $no = 1;
            }
        ?>
            <tr>
                <td style="text-align: center; padding: 8px; border: 1px solid #ddd; font-size: 9px !important;" rowspan="2"><?php echo $no; ?></td>
                <td style="padding: 8px; border: 1px solid #ddd; font-size: 9px !important;background-color:#2cc6e7" colspan="5">
                    <?php echo htmlspecialchars($task["judul"]); ?> - <?php echo $task['filter_tanggal']  ?>
                </td>
            </tr>
            <tr>
                <?php 
                $point = 1;
                $max_points = count($task["points"]);  // Get the number of points in the current task
                foreach ($task["points"] as $key => $row) { 
                    $colspan = 1;  // Default colspan value

                    // Set colspan based on the number of points
                    if($max_points ==  2 && $point == $max_points){
                        $colspan = 4;
                    } elseif($max_points ==  3 && $point == $max_points){
                        $colspan = 3;
                    } elseif($max_points ==  4 && $point == $max_points){
                        $colspan = 2;
                    }
                    $background="";
                    $color="black";
                    if(empty($row['list_data'])){
                        $background="red";
                        $color="white";
                    }
                ?>
                    <td style="padding: 8px; border: 1px solid #ddd; font-size: 9px !important;background-color:<?php echo  $background ?>;color:<?php  echo  $color ?>" colspan="<?php echo $colspan; ?>">
                        <?php echo "POINT ".$point; ?>
                        <?php 
                            if(!empty($row['list_data'])){
                                
                                foreach($row['list_data'] as $data){ ?>
                                <hr/>
                                <?php echo karyawan_bynik($data['employee_code'])->nama; ?> - <?php echo schedule($data['employee_code'],$data['created_at'])->shift; ?><br/>
                                <?php echo $data['status']; ?><br/>
                                <?php echo $data['description']; ?><br/>
                                <?php echo date('Y-m-d H:i:s',strtotime($data['created_at'])); ?><br/>
                        <?php 
                                } 
                            }else{
                        ?>
                                <hr/><br/>
                                <hr/><br/>
                                <hr/><br/>
                        <?php 
                            }
                        ?>   
                    </td>
                <?php 
                    $point++;
                } ?>
            </tr>
        <?php 
        $no++;
        } ?>
    </tbody>
</table>



</body>
</html>
