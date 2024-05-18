<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One Byte Foods</title>
    <link rel="stylesheet" href="tables.css">
</head>
<body>
    
    <header>
        
        <div class="container">
            <a href="adminMainpage.php">
                <h1>One Byte Foods</h1>
            </a>
            <nav>
                <ul>
                    <li><a href="Tables.php" class="active">Tables</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
        
    </header>
    
    <div class="booking-container">
        <div class="table-selection">
            <!-- First column -->
            <div class="column">
                <div class="table-box">Table 1</div>
                <div class="table-box">Table 2</div>
                <div class="table-box">Table 3</div>
                <div class="table-box">Table 4</div>
                <div class="table-box">Table 5</div>
            </div>
            <!-- Second column -->
            <div class="column1">
                <div class="table-box">Table 6</div>
                <div class="table-box">Table 7</div>
                <div class="table-box">Table 8</div>
                <div class="table-box">Table 9</div>
                <div class="table-box">Table 10</div>
            </div>
        </div>

        <!-- Legend for table status -->
        <div class="legend">
            <div><span class="dot unavailable"></span>Unavailable</div>
            <div><span class="dot sold-out"></span>Sold Out</div>
            <div><span class="dot available"></span>Available</div>
            <div><span class="dot my-seat"></span>My Seat</div>
            <div><span class="dot reserved"></span>Reserved</div>
        </div>
        
    </div>
        
    
</body>
</html>
