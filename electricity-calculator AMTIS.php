<?php
// Enabling strict typing is a best practice in modern PHP
declare(strict_types=1);

/**
 * Electricity Bill Calculator
 * 
 * @param float $voltage The voltage value
 * @param float $current The current value in Amperes
 * @param float $rate    The rate in sen
 * @param int   $hour    The duration in hours
 * @return array         Calculated power, energy, and total
 */
function calculateElectricity(float $voltage, float $current, float $rate, int $hour): array {
    $power_w = $voltage * $current; // Power in Watts
    
    // Note: To get kWh, we divide Wh by 1000
    $energy_kwh = ($power_w * $hour) / 1000; 
    
    $total_cost = $energy_kwh * ($rate / 100); 

    return [
        'power' => $power_w,
        'energy' => $energy_kwh,
        'total' => $total_cost
    ];
}

// Logic to handle form submission
$voltage = isset($_POST['voltage']) ? (float)$_POST['voltage'] : 0.0;
$current = isset($_POST['current']) ? (float)$_POST['current'] : 0.0;
$rate = isset($_POST['rate']) ? (float)$_POST['rate'] : 0.0;

$results = [];
if ($voltage > 0 && $current > 0 && $rate > 0) {
    for ($h = 1; $h <= 24; $h++) {
        // We cast $h to int to match the function signature
        $results[$h] = calculateElectricity($voltage, $current, $rate, (int)$h);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Calculator</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; padding-top: 50px; }
        .card { box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .result-container { margin-top: 30px; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Electricity Calculator</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="voltage">Voltage (V)</label>
                            <input type="number" step="0.01" name="voltage" id="voltage" class="form-control" value="<?php echo $voltage; ?>" required placeholder="e.g. 230">
                        </div>
                        <div class="form-group">
                            <label for="current">Current (A)</label>
                            <input type="number" step="0.01" name="current" id="current" class="form-control" value="<?php echo $current; ?>" required placeholder="e.g. 0.5">
                        </div>
                        <div class="form-group">
                            <label for="rate">Current Rate (sen/kWh)</label>
                            <input type="number" step="0.01" name="rate" id="rate" class="form-control" value="<?php echo $rate; ?>" required placeholder="e.g. 21.80">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Calculate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($results)): ?>
    <div class="row justify-content-center result-container">
        <div class="col-md-10">
            <div class="alert alert-info text-center">
                <strong>Settings:</strong> <?php echo $voltage; ?>V | <?php echo $current; ?>A | <?php echo ($voltage * $current); ?>W | <?php echo $rate; ?> sen/kWh
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped bg-white">
                    <thead class="thead-dark">
                        <tr>
                            <th>Hour</th>
                            <th>Energy (kWh)</th>
                            <th>Total (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $hour => $data): ?>
                            <tr>
                                <td><?php echo $hour; ?></td>
                                <td><?php echo number_format($data['energy'], 5); ?></td>
                                <td><?php echo number_format($data['total'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

</body>
</html>