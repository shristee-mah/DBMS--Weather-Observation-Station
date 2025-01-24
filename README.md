# DBMS--Weather-Observation-Station
Weather Registration and Observation System

---

### Weather Observation System

#### Overview
The Weather Observation System is a PHP-based web application designed to manage and analyze weather station data on a local host environment. It allows users to add, search, and analyze station details such as city, weather state, latitude, and longitude, with functionality to calculate distances between selected stations for spatial analysis.

---

#### Features
- **Add Stations:** Use `index.php` to add new weather stations with details like city, weather state, latitude, and longitude.
- **Preloaded Data:** Includes 10 sample weather station entries preloaded into the SQLite database (`weather.db`) for testing.
- **Search Stations:** Use `search.php` to filter weather stations by various attributes.
- **Distance Calculations:** Select two stations to calculate Euclidean and Manhattan distances.
- **Local Database Management:** Stores all weather station data in an SQLite database for lightweight performance on local setups.

---

#### How to Set Up and Use
1. **Setup on Local Host:**
   - Install a local server environment (e.g., XAMPP, WAMP, or MAMP) with PHP and SQLite support.
   - Place the project files (`index.php`, `search.php`, and `weather.db`) in your server's root directory (e.g., `htdocs` for XAMPP).

2. **Start the Server:**
   - Start the local server using your chosen software.
   - Open a browser and navigate to `http://localhost/index.php` to access the system.

3. **Manage Data:**
   - Use `index.php` to view, add, or manage weather station data.
   - Open `search.php` to filter stations and calculate distances.

4. **Search and Analyze:**
   - Enter filters (e.g., city or weather state) in `search.php` to find relevant stations.
   - Select two stations from the results to calculate and view distance metrics.

---


#### Technologies Used
- **Local Environment:** PHP with SQLite support on a local host (e.g., XAMPP, WAMP, MAMP).
- **Backend:** PHP
- **Database:** SQLite (`weather.db`)
- **Frontend:** HTML, CSS

---

#### Future Enhancements
- Integration with live weather data APIs.
- User authentication for restricted access and secure data management.
- Enhanced search filters and data visualization features.
  

---

This system is optimized for local development and testing, providing a practical solution for managing weather station data and performing basic spatial analyses.
