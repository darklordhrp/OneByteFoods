<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Process</title>
    <link rel="stylesheet" href="payment_process.css">
    <style>
        .error-message {
            color: red;
            font-size: 14px;
        }
        #payment-success-message {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #4CAF50;
            color: white;
            padding: 15px 30px;
            border-radius: 5px;
            z-index: 9999;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <?php
        // Check if payment method, selected tables, date, time, user_name, and user_email are received
        if(isset($_POST['payment_method']) && isset($_POST['selected_tables']) && isset($_POST['date']) && isset($_POST['time']) && isset($_POST['user_name']) && isset($_POST['user_email'])) {
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

            $payment_method = $_POST['payment_method'];
            $selected_tables = $_POST['selected_tables'];
            $date = $_POST['date'];
            $time = $_POST['time'];
            $user_name = $_POST['user_name'];
            $user_email = $_POST['user_email'];

            // Insert booking details into the database for each selected table
            foreach ($selected_tables as $table_id) {
                $sql_booking = "INSERT INTO booked_tables_two (table_number, booking_date, booking_time, user_name, user_email) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql_booking);
                $stmt->bind_param("issss", $table_id, $date, $time, $user_name, $user_email);
                $stmt->execute();

                // Update availability of the booked table to 'sold-out' in the tables table
                $sql_update_availability = "UPDATE tables SET Availability = 'sold-out' WHERE TableID = ?";
                $stmt = $conn->prepare($sql_update_availability);
                $stmt->bind_param("i", $table_id);
                $stmt->execute();
            }

            // Close the database connection
            $conn->close();

            // Display the form based on the selected payment method
            switch ($payment_method) {
                case 'eSewa':
                    echo '<h2 class="payment-heading">eSewa Payment</h2>';
                    echo '<form class="payment-form">'; // removed action and method attributes
                    echo '<div class="form-group">';
                    echo '<label for="eSewa_id">eSewa ID:</label>';
                    echo '<input type="text" id="eSewa_id" name="eSewa_id" required>';
                    echo '<span id="eSewa_id_error" class="error-message" style="display:none;">This field is required.</span>'; // added error message
                    echo '</div>';
                    echo '<div class="form-group">';
                    echo '<label for="eSewa_name">eSewa Name:</label>';
                    echo '<input type="text" id="eSewa_name" name="eSewa_name" required>';
                    echo '<span id="eSewa_name_error" class="error-message" style="display:none;">This field is required.</span>'; // added error message
                    echo '</div>';
                    echo '<button type="button" class="submit-button" onclick="showSuccessMessage()">Submit eSewa Payment</button>'; // changed type to button and added onclick event
                    echo '</form>';
                    break;
                case 'Bank':
                    echo '<h2 class="payment-heading">Bank Payment</h2>';
                    echo '<form class="payment-form">'; // removed action and method attributes
                    echo '<div class="form-group">';
                    echo '<label for="account_number">Account Number:</label>';
                    echo '<input type="text" id="account_number" name="account_number" required>';
                    echo '<span id="account_number_error" class="error-message" style="display:none;">This field is required.</span>'; // added error message
                    echo '</div>';
                    echo '<div class="form-group">';
                    echo '<label for="account_name">Account Name:</label>';
                    echo '<input type="text" id="account_name" name="account_name" required>';
                    echo '<span id="account_name_error" class="error-message" style="display:none;">This field is required.</span>'; // added error message
                    echo '</div>';
                    echo '<div class="form-group">';
                    echo '<label for="bank_name">Bank Name:</label>';
                    echo '<input type="text" id="bank_name" name="bank_name" required>';
                    echo '<span id="bank_name_error" class="error-message" style="display:none;">This field is required.</span>'; // added error message
                    echo '</div>';
                    echo '<button type="button" class="submit-button" onclick="showSuccessMessage()">Submit Bank Payment</button>'; // changed type to button and added onclick event
                    echo '</form>';
                    break;
                case 'Khalti':
                    echo '<h2 class="payment-heading">Khalti Payment</h2>';
                    echo '<form class="payment-form">'; // removed action and method attributes
                    echo '<div class="form-group">';
                    echo '<label for="khalti_id">Khalti ID:</label>';
                    echo '<input type="text" id="khalti_id" name="khalti_id" required>';
                    echo '<span id="khalti_id_error" class="error-message" style="display:none;">This field is required.</span>'; // added error message
                    echo '</div>';
                    echo '<div class="form-group">';
                    echo '<label for="khalti_name">Khalti Name:</label>';
                    echo '<input type="text" id="khalti_name" name="khalti_name" required>';
                    echo '<span id="khalti_name_error" class="error-message" style="display:none;">This field is required.</span>'; // added error message
                    echo '</div>';
                    echo '<button type="button" class="submit-button" onclick="showSuccessMessage()">Submit Khalti Payment</button>'; // changed type to button and added onclick event
                    echo '</form>';
                    break;
                default:
                    echo '<p class="error-message">No payment method selected.</p>';
                    break;
            }
        } else {
            echo '<p class="error-message">Error: Payment method, selected tables, date, time, user name, or user email not received.</p>';
        }
        ?>
    </div>

    <div id="payment-success-message">Payment Successful.</div>

    <script>    
        function showSuccessMessage() {
            var successMessage = document.getElementById('payment-success-message');
            successMessage.style.display = 'block';
            setTimeout(function() {
                successMessage.style.display = 'none';
                window.location.href = "2table.php"; // Redirect to 2table.php after 2 seconds
            }, 2000); // Hide the success message after 2 seconds (2000 milliseconds)
        }
    </script>
</body>
</html>
