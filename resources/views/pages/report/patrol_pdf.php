<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Patroli</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
            text-align: center;
        }
        .no-data {
            text-align: center;
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Report Patroli (<?php echo $tanggal; ?>)</h2>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Task</th>
                <th>Point</th>
                
                <th>Status</th>
                <th>Description</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tasks)): ?>
                <?php $no=1; ?>
                <?php foreach ($tasks as $task): ?>
                        <tr style="background-color:#05a34a">
                            <td><?php echo $no ?></td>
                            <td><?php echo $task['tanggal']; ?></td>
                            <td><?php echo $task['task']; ?></td>
                            <td><?php echo $task['point']; ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                    <?php $no++ ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="no-data">No Data Available</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
