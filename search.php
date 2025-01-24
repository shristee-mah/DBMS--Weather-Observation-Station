<html>
    <head>
        <title>Weather Observation Stations</title>
        <style>
            /* Basic body and text styling */
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                margin: 0;
                padding: 0;
            }

            /* Header styling */
            h1 {
                text-align: center;
                color: #2C3E50;
            }

            /* Form styling */
            form {
                background-color: #ffffff;
                padding: 20px;
                margin: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                width: 70%;
                margin-left: auto;
                margin-right: auto;
            }

            /* Input styling */
            input[type="text"], input[type="submit"] {
                padding: 8px;
                margin: 8px 0;
                width: 100%;
                box-sizing: border-box;
                border-radius: 4px;
                border: 1px solid #ccc;
            }

            /* Button styling */
            input[type="submit"] {
                background-color: #3498db;
                color: white;
                border: none;
                cursor: pointer;
            }

            /* Button hover effect */
            input[type="submit"]:hover {
                background-color: #2980b9;
            }

            /* Table styling */
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            th, td {
                padding: 12px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            th {
                background-color: #3498db;
                color: white;
            }

            /* Row hover effect */
            tr:hover {
                background-color: #f1f1f1;
            }

            /* Subheader styling */
            h3 {
                text-align: center;
                color: #2C3E50;
            }

            /* Container styling for central alignment */
            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Weather Observation Stations</h1>
            <!-- Form for searching weather stations -->
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <h3>Search Station</h3>
                <!-- Inputs for filtering the stations based on city, state, latitude, and longitude -->
                <label for="city">City:</label>
                <input type="text" name="city" id="city" size="20">
                <label for="state">State:</label>
                <input type="text" name="state" id="state" size="5">
                <label for="lat_n">Latitude (LAT_N):</label>
                <input type="text" name="lat_n" id="lat_n" size="10">
                <label for="long_w">Longitude (LONG_W):</label>
                <input type="text" name="long_w" id="long_w" size="10">
                <input type="submit" name="search" value="Search">
            </form>

            <?php
            // Database connection settings for local development
            $servername = "localhost";  
            $username = "root";         
            $password = "";             
            $dbname = "weather_db";  

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error); // Error handling for database connection
            }

            // Prepopulated data to insert into the table if it's empty
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
                // Insert prepopulated data into the table
                foreach ($prepopulated_data as $station) {
                    $insert_sql = "INSERT IGNORE INTO STATION (ID, CITY, STATE, LAT_N, LONG_W) 
                                   VALUES ($station[0], '$station[1]', '$station[2]', $station[3], $station[4]);";
                    $conn->query($insert_sql);
                }
            }

            // Handle search functionality based on the form input
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
                $conditions = [];
                if (!empty($_POST['city'])) $conditions[] = "CITY LIKE '%" . $_POST['city'] . "%'";
                if (!empty($_POST['state'])) $conditions[] = "STATE = '" . $_POST['state'] . "'";
                if (!empty($_POST['lat_n'])) $conditions[] = "LAT_N = " . $_POST['lat_n'];
                if (!empty($_POST['long_w'])) $conditions[] = "LONG_W = " . $_POST['long_w'];

                $sql = "SELECT * FROM STATION";
                if (count($conditions) > 0) {
                    $sql .= " WHERE " . implode(" AND ", $conditions); // Apply filters based on input fields
                }

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Display search results if stations are found
                    echo "<h3>Search Results</h3>";
                    echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
                    echo "<table>
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>ID</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>LAT_N</th>
                                    <th>LONG_W</th>
                                </tr>
                            </thead>
                            <tbody>";
                    while ($row = $result->fetch_assoc()) {
                        // Loop through and display each row as a table entry
                        echo "<tr>
                                <td><input type='checkbox' name='station_ids[]' value='" . $row['ID'] . "'></td>
                                <td>" . $row['ID'] . "</td>
                                <td>" . $row['CITY'] . "</td>
                                <td>" . $row['STATE'] . "</td>
                                <td>" . $row['LAT_N'] . "</td>
                                <td>" . $row['LONG_W'] . "</td>
                              </tr>";
                    }
                    echo "</tbody></table>";
                    echo "<input type='submit' name='calculate' value='Calculate Distance'>"; // Button to calculate distance
                    echo "</form>";
                } else {
                    echo "<p>No records found.</p>"; // If no matching stations are found
                }
            }

            // Handle distance calculation between two selected stations
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["calculate"])) {
                if (!isset($_POST['station_ids']) || count($_POST['station_ids']) != 2) {
                    echo "<p>Please select exactly two stations to calculate the distance.</p>";
                } else {
                    $station_ids = $_POST['station_ids'];

                    // Query to retrieve the two selected stations
                    $sql = "SELECT * FROM STATION WHERE ID IN (" . implode(",", $station_ids) . ")";
                    $result = $conn->query($sql);

                    $stations = [];
                    while ($row = $result->fetch_assoc()) {
                        $stations[] = $row; // Store the station details
                    }

                    if (count($stations) == 2) {
                        // Calculate Euclidean and Manhattan distances
                        $lat_diff = abs($stations[0]['LAT_N'] - $stations[1]['LAT_N']);
                        $long_diff = abs($stations[0]['LONG_W'] - $stations[1]['LONG_W']);

                        $euclidean = sqrt(pow($lat_diff, 2) + pow($long_diff, 2)); // Euclidean distance
                        $manhattan = $lat_diff + $long_diff; // Manhattan distance

                        echo "<h3>Distance Results</h3>";
                        echo "<p>Euclidean Distance: " . number_format($euclidean, 4) . "</p>";
                        echo "<p>Manhattan Distance: " . number_format($manhattan, 4) . "</p>";
                    }
                }
            }

            $conn->close(); // Close the database connection
            ?>
        </div>
    </body>
</html>
