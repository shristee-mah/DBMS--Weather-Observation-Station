<!DOCTYPE html>
<html>
<head>
    <title>Weather Station Registration</title>
    <style>
        /* Styling for the page layout */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        /* Styling for the form container */
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            margin: 20px auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        /* Styling for input fields */
        input[type="number"], input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        /* Styling for submit button and search button */
        input[type="submit"], button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        /* Hover effect for submit and search buttons */
        input[type="submit"]:hover, button:hover {
            background-color: #45a049;
        }
        /* Styling for the container around the buttons */
        .container {
            text-align: center;
            margin-top: 20px;
        }
        /* Styling for horizontal rule */
        hr {
            border: 1px solid #ddd;
            margin: 20px 0;
        }
        /* Styling for error message */
        p {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Form for weather station registration -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <center>
            <b>
                <h1>Weather Station Registration</h1>
            </b>
        </center>
        <hr>
        <!-- Input fields for station details -->
        ID: <input type="number" name="ID" size="20"><br /><br />
        City: <input type="text" name="CITY" size="21"><br /><br />
        State: <input type="text" name="STATE" size="21" maxlength="21"><br /><br />
        Latitude (LAT_N): <input type="number" step="0.000001" name="LAT_N"><br /><br />
        Longitude (LONG_W): <input type="number" step="0.000001" name="LONG_W"><br /><br />
        <!-- Container for buttons -->
        <div class="container">
            <input type="submit" name="submit" value="Register">
            <a href="search.php"><button type="button">Search Stations</button></a>
        </div>
    </form>

    <?php
     // Database connection settings for local development
     $servername = "localhost";  // Use 'localhost' for local MySQL
     $username = "root";         // Default XAMPP username
     $password = "";             // Default XAMPP password (empty)
     $dbname = "weather_db";  // Replace with your actual database name
     
    // Create connection to MySQL database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepopulated station data if the database table is empty
    $prepopulated_data = [
        [1, 'Kathmandu', 'Sunny', 27.7172, 85.3240],
        [2, 'Pokhara', 'Rainy', 28.2096, 83.9856],
        [3, 'Lalitpur', 'Cloudy', 27.6644, 85.3188],
        [4, 'Bhaktapur', 'Sunny', 27.6725, 85.4298],
        [5, 'Biratnagar', 'Rainy', 26.4525, 87.2718],
        [6, 'Chitwan', 'Windy', 27.5291, 84.3542],
        [7, 'Dharan', 'Cloudy', 26.8121, 87.2836],
        [8, 'Janakpur', 'Stormy', 26.7288, 85.9235],
        [9, 'Hetauda', 'Foggy', 27.4215, 85.0322],
        [10, 'Butwal', 'Sunny', 27.6959, 83.4485]
    ];

    // Check if the STATION table is empty and insert prepopulated data if true
    $check_sql = "SELECT COUNT(*) AS count FROM STATION";
    $result_check = $conn->query($check_sql);
    $row = $result_check->fetch_assoc();

    if ($row['count'] == 0) {
        // Insert or update each station in prepopulated data
        foreach ($prepopulated_data as $station) {
            // Check if the record already exists based on ID
            $check_sql = "SELECT * FROM STATION WHERE ID = $station[0]";
            $check_result = $conn->query($check_sql);
    
            if ($check_result->num_rows > 0) {
                // If the record exists, update it
                $update_sql = "UPDATE STATION 
                               SET CITY = '$station[1]', STATE = '$station[2]', LAT_N = $station[3], LONG_W = $station[4]
                               WHERE ID = $station[0]";
                if (!$conn->query($update_sql)) {
                    echo "<p>Error updating data for ID {$station[0]}: " . $conn->error . "</p>";
                }
            } else {
                // If the record does not exist, insert it
                $insert_sql = "INSERT INTO STATION (ID, CITY, STATE, LAT_N, LONG_W) 
                               VALUES ($station[0], '$station[1]', '$station[2]', $station[3], $station[4])";
                if (!$conn->query($insert_sql)) {
                    echo "<p>Error inserting data for ID {$station[0]}: " . $conn->error . "</p>";
                }
            }
        }
    }
    

    // Handle form submission for registering a new station
    if (isset($_POST['submit'])) {
        $id = $_POST['ID'];
        $city = $_POST['CITY'];
        $state = $_POST['STATE'];
        $lat_n = $_POST['LAT_N'];
        $long_w = $_POST['LONG_W'];

        // Check if any required field is empty
        if (empty($id) || empty($city) || empty($state) || empty($lat_n) || empty($long_w)) {
            echo "<p>Please fill in all the fields.</p>";
        } else {
            // Check for duplicate ID
            $sql_check = "SELECT * FROM STATION WHERE ID = '$id'";
            $result_check = $conn->query($sql_check);

            if ($result_check->num_rows > 0) {
                echo "<p>Station with this ID already exists.</p>";
            } else {
                // Insert new station record
                $sql = "INSERT INTO STATION (ID, CITY, STATE, LAT_N, LONG_W) VALUES ('$id', '$city', '$state', '$lat_n', '$long_w')";
                if ($conn->query($sql) === TRUE) {
                    echo "<p>New weather station registered successfully.</p>";
                } else {
                    echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
                }
            }
        }
    }

    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
