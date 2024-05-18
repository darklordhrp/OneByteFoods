<?php
// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "one_byte_foods";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch table availability from the database
$sql_availability = "SELECT TableID, TableName, Availability FROM tables_six"; // Update table name to tables_six
$result_availability = $conn->query($sql_availability);
$table_availability = array();
if ($result_availability->num_rows > 0) {
    while ($row = $result_availability->fetch_assoc()) {
        $table_availability[$row['TableID']] = $row['Availability'];
    }
}

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and store form data
    $date = sanitize_input($_POST["date"]);
    $time = sanitize_input($_POST["time"]);
    $seats = sanitize_input($_POST["seats"]);
    $cost = sanitize_input($_POST["cost"]);
    $selected_tables = json_decode($_POST["selected_tables"]);
    $user_name = sanitize_input($_POST["user_name"]);
    $user_email = sanitize_input($_POST["user_email"]);

    // Check if date and time are filled
    if (empty($date) || empty($time)) {
        die("Date and time are required.");
    }

    // Update table availability to 'reserved' in the tables_six table
    foreach ($selected_tables as $table_id) {
        $sql_update_availability = "UPDATE tables_six SET Availability = 'reserved' WHERE TableID = ?";
        $stmt = $conn->prepare($sql_update_availability);
        $stmt->bind_param("i", $table_id);
        $stmt->execute();
    }

    // Insert reservation details into the booked_tables_six table
    $stmt_insert = $conn->prepare("INSERT INTO booked_tables_six (table_number, booking_date, booking_time, user_name, user_email) VALUES (?, ?, ?, ?, ?)");
    foreach ($selected_tables as $table_id) {
        $stmt_insert->bind_param("issss", $table_id, $date, $time, $user_name, $user_email);
        $stmt_insert->execute();
    }

    // Reservation successful message
    echo "Tables reserved successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Booking</title>
    <!-- Link to your CSS file -->
    <link rel="stylesheet" href="2table.css">
</head>
<body>
    <!-- Header and navigation code -->
    <header>
        <div class="container">
            <a href="Mainpage.php" class="logo-link">
                <h1>One Byte Foods</h1>
            </a>
            <nav>
                <ul>
                    <li><a href="booking.php" class="active">Bookings</a></li>
                    <li><a href="contactUS.php">Contact Us</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="booking-container">
        <h1>Select Your Table for 6</h1>
        <!-- Table selection area -->
        
        <div class="table-selection">
            <!-- First column -->
            <div class="column">
                <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <div class="table-box <?php echo ($table_availability[$i] === 'reserved') ? 'reserved' : (($table_availability[$i] === 'sold-out') ? 'sold_out' : (($table_availability[$i] === 'available') ? 'available' : 'unavailable')); ?>" onclick="<?php echo ($table_availability[$i] === 'available') ? 'toggleTable('.$i.');' : ''; ?>">Table <?php echo $i; ?></div>
                <?php endfor; ?>
            </div>

            <!-- Second column -->
            <div class="column">
                <?php for ($i = 6; $i <= 10; $i++) : ?>
                    <div class="table-box <?php echo ($table_availability[$i] === 'reserved') ? 'reserved' : (($table_availability[$i] === 'sold-out') ? 'sold_out' : (($table_availability[$i] === 'available') ? 'available' : 'unavailable')); ?>" onclick="<?php echo ($table_availability[$i] === 'available') ? 'toggleTable('.$i.');' : ''; ?>">Table <?php echo $i; ?></div>
                <?php endfor; ?>
            </div>
        </div>
        
        <!-- Legend for table status -->
        <div class="legend">
            <div><span class="dot unavailable"></span>Unavailable</div>
            <div><span class="dot sold_out"></span>Sold Out</div>
            <div><span class="dot available"></span>Available</div>
            <div><span class="dot my-seat"></span>My Seat</div>
            <div><span class="dot reserved"></span>Reserved</div>
        </div>

        <form id="booking-form" class="reservation-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <!-- Form fields for reservation details -->
    <label for="date"><b>Select Date:</b></label>
    <input type="date" id="date" name="date" required class="date-input">

    <label for="time"><b>Select Time:</b></label>
    <input type="time" id="time" name="time" required class="time-input">

    <label for="Number-of-seats"><b>Number of Seats:</b></label>
    <input type="text" placeholder="Number of Seats" id="seats" name="seats" required class="seat">

    <label for="Total-cost"><b>Total Cost:</b></label>
    <input type="text" placeholder="Total Cost" id="cost" name="cost" required class="cost">

    <!-- Input fields for user's name and email -->
    <label for="user_name"><b>Your Name:</b></label>
    <input type="text" id="user_name" name="user_name" required>

    <label for="user_email"><b>Your Email:</b></label>
    <input type="email" id="user_email" name="user_email" required>

    <!-- Hidden input field to store selected table numbers -->
    <input type="hidden" name="selected_tables" id="selected_tables" value="">

    <!-- Change type to "button" for reserving a table -->
    <button type="button" class="reserve-button" onclick="reserveTable()">Reserve Table</button>

    <!-- Change type to "button" for buying a table -->
    <button type="button" class="buy-button" onclick="buyTable()">Buy Table</button>

    <!-- Success message for reservation stored successfully -->
    <div id="success-message" style="display: none; margin-left: 20px;">
        Tables reserved successfully.
    </div>
</form>

        <!-- Success message for reservation stored successfully -->
        <div id="success-message" style="display: none;">
            Tables reserved successfully.
        </div>
    </div>
    
    <script>
    // Initialize selectedTables array
    var selectedTables = [];

    console.log("Selected tables on page load:", selectedTables);

    // Function to handle toggling tables
    function toggleTable(tableID) {
        console.log("Table ID clicked:", tableID);
        
        var index = selectedTables.indexOf(tableID);
        if (index === -1) {
            // Table not selected, so select it
            selectedTables.push(tableID);
        } else {
            // Table already selected, so deselect it
            selectedTables.splice(index, 1);
        }

        console.log("Selected tables after click:", selectedTables);

        // Update table colors and form field
        updateTableColors();
        updateFormFields(); // Call to update the form fields
    }

    function updateTableColors() {
        var tables = document.querySelectorAll('.table-box');
        tables.forEach(function(table) {
            var tableID = parseInt(table.textContent.split(' ')[1]);
            if (selectedTables.includes(tableID)) {
                table.classList.add('my-seat'); // Add the 'my-seat' class
            } else {
                table.classList.remove('my-seat'); // Remove the 'my-seat' class
            }
            // Set color class based on availability
            var availability = <?php echo json_encode($table_availability); ?>[tableID];
            if (availability === 'reserved') {
                table.classList.remove('available', 'unavailable', 'sold_out');
                table.classList.add('reserved');
            } else if (availability === 'sold-out') {
                table.classList.remove('available', 'unavailable', 'reserved');
                table.classList.add('sold_out');
            } else if (availability === 'available') {
                table.classList.remove('unavailable', 'reserved', 'sold_out');
                table.classList.add('available');
            } else {
                table.classList.remove('available', 'reserved', 'sold_out');
                table.classList.add('unavailable');
            }
        });
    }

    function updateFormFields() {
        // Update hidden input field value with selected tables
        document.getElementById('selected_tables').value = JSON.stringify(selectedTables);
        
        // Update the number of tables displayed
        var numTables = selectedTables.length;
        document.getElementById('seats').value = numTables * 6; // Assuming each table has 6 seats

        // Update the total amount displayed
        var totalAmount = numTables * 600; // Assuming each table costs $600
        document.getElementById('cost').value = totalAmount;
    }

    function reserveTable() {
        // Gather necessary information
        var date = document.getElementById('date').value;
        var time = document.getElementById('time').value;
        var selectedTables = JSON.parse(document.getElementById('selected_tables').value);
        var userName = document.getElementById('user_name').value;
        var userEmail = document.getElementById('user_email').value;

        // Check if date, time, selected tables, and user details are filled
        if (!date || !time || selectedTables.length === 0 || !userName || !userEmail) {
            alert("Please fill in all the required fields before proceeding to reservation.");
            return;
        }

        // Perform AJAX request to handle reservation
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Reservation successful, update table colors and display success message
                    updateTableColors();
                    showSuccessMessage();
                } else {
                    // Error handling if reservation failed
                    console.error('Reservation failed:', xhr.responseText);
                    alert('Reservation failed. Please try again later.');
                }
            }
        };
        var params = 'date=' + encodeURIComponent(date) + '&time=' + encodeURIComponent(time) + '&seats=' + encodeURIComponent(selectedTables.length * 6) + '&cost=' + encodeURIComponent(selectedTables.length * 600) + '&selected_tables=' + encodeURIComponent(JSON.stringify(selectedTables)) + '&user_name=' + encodeURIComponent(userName) + '&user_email=' + encodeURIComponent(userEmail);
        xhr.send(params);
    }

    function buyTable() {
        // Check if date, time, selected tables, user_name, and user_email are filled
        var date = document.getElementById('date').value;
        var time = document.getElementById('time').value;
        var selectedTables = JSON.parse(document.getElementById('selected_tables').value);
        var userName = document.getElementById('user_name').value;
        var userEmail = document.getElementById('user_email').value;

        if (!date || !time || selectedTables.length === 0 || !userName || !userEmail) {
            alert("Please fill in all the required fields before proceeding to buy.");
            return;
        }

        // Redirect to payment.php
        var totalCost = document.getElementById('cost').value;
        // Pass selected tables, date, time, user_name, and user_email as URL parameters to payment_six.php
        var tablesParam = selectedTables.join(','); // Convert array to comma-separated string
        window.location.href = 'payment_six.php?amount=' + totalCost + '&tables=' + tablesParam + '&date=' + date + '&time=' + time + '&user_name=' + userName + '&user_email=' + userEmail;
    }

    function hideSuccessMessage() {
        var successMessage = document.getElementById('success-message');
        successMessage.style.display = 'none';
    }

    function showSuccessMessage() {
        var successMessage = document.getElementById('success-message');
        successMessage.style.display = 'block';
        setTimeout(hideSuccessMessage, 2000); // Hide after 2 seconds (2000 milliseconds)
    }

    // Clear selected tables on page load
    window.onload = function() {
        clearSelectedTables();
        hideSuccessMessage();
        updateTableColors();
    };

    function clearSelectedTables() {
        selectedTables = []; // Clear the selected tables array
        updateTableColors(); // Update table colors to reflect cleared selection
        updateFormFields(); // Update form fields to reflect cleared selection
    }
    </script>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
