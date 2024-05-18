<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="payment.css"> 
    <style>
        .warning {
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

    <div class="payment-container">
        <h2>Payment</h2>
        <form id="payment-form" action="payment_process.php" method="post">

            <div class="payment-option">
                <label for="amount">Amount:</label>
                <input type="text" id="amount" name="amount" placeholder="Amount" value="<?php echo isset($_GET['amount']) ? $_GET['amount'] : ''; ?>">
            </div>
            <!-- Hidden input fields to hold selected tables' details -->
            <?php
            if(isset($_GET['tables'])) {
                $tables = explode(',', $_GET['tables']);
                foreach($tables as $table) {
                    echo '<input type="hidden" name="selected_tables[]" value="'.$table.'">';
                }
            }
            ?>
            <!-- Hidden input fields to hold date, time, user_name, and user_email -->
            <input type="hidden" name="date" value="<?php echo isset($_GET['date']) ? $_GET['date'] : ''; ?>">
            <input type="hidden" name="time" value="<?php echo isset($_GET['time']) ? $_GET['time'] : ''; ?>">
            <input type="hidden" name="user_name" value="<?php echo isset($_GET['user_name']) ? $_GET['user_name'] : ''; ?>">
            <input type="hidden" name="user_email" value="<?php echo isset($_GET['user_email']) ? $_GET['user_email'] : ''; ?>">

            <div class="payment-option">
                <input type="radio" id="esewa" name="payment_method" value="eSewa">
                <label for="esewa">Pay via eSewa</label>
            </div>
            <div class="payment-option">
                <input type="radio" id="bank" name="payment_method" value="Bank">
                <label for="bank">Pay via Bank</label>
            </div>
            <div class="payment-option">
                <input type="radio" id="khalti" name="payment_method" value="Khalti">
                <label for="khalti">Pay via Khalti</label>
            </div>
            <div class="remarks">
                <textarea id="remarks" name="remarks" placeholder="Remarks"></textarea>
                <span id="remarks-warning" class="warning" style="display: none;">This field must be filled.</span>
            </div>
            <div class="payment-option">
                <button type="button" id="buy-button" onclick="proceedToPayment()">Proceed Payment</button>
            </div>
        </form>
    </div>

    <div id="payment-success-message">Payment Successful.</div>

    <script>
        function proceedToPayment() {
            var remarks = document.getElementById('remarks').value;

            if (remarks.trim() === '') {    
                document.getElementById('remarks-warning').style.display = 'inline';
                setTimeout(function() {
                    document.getElementById('remarks-warning').style.display = 'none';
                }, 1000); // Hide the warning message after 1 second (1000 milliseconds)
                return;
            }

            document.getElementById('payment-form').submit();
        }

        function showSuccessMessage() {
            var successMessage = document.getElementById('payment-success-message');
            successMessage.style.display = 'block';
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 2000); // Hide the success message after 2 seconds (2000 milliseconds)
        }
    </script>
</body>
</html>
