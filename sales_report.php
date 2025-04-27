<?php
// sales_report.php
require_once 'db_connect.php'; // DB connection
session_start();

// Check if user is logged in and has the right role (admin)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Function to fetch the sales data from the database
function generateSalesReport($conn) {
    $sql = "SELECT SUM(amount) as total_revenue, COUNT(*) as total_bookings 
            FROM bookings 
            WHERE booking_date BETWEEN '2025-01-01' AND '2025-12-31'"; // Example for a year
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return [
            'total_revenue' => $row['total_revenue'],
            'total_bookings' => $row['total_bookings']
        ];
    } else {
        return null;
    }
}

// Generate sales report
$sales_data = generateSalesReport($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
</head>
<body>

<h1>Sales Report for 2025</h1>

<?php if ($sales_data): ?>
    <p><strong>Total Revenue:</strong> $<?php echo number_format($sales_data['total_revenue'], 2); ?></p>
    <p><strong>Total Bookings:</strong> <?php echo $sales_data['total_bookings']; ?></p>
<?php else: ?>
    <p>No sales data found for the selected period.</p>
<?php endif; ?>

</body>
</html>
