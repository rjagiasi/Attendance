# Attendance

A website for maintaing college attendance

Built on LAMP. Uses PHP7

**Docs in Documentation.md**

Database Schema in **Structure.sql** and diagram in **Schema.pdf**

Fake test db in **Entire_db.sql**

**php/constants.php** 	- consists of database values & email id used by the app

**php/functions.php** 	- contains database connectivity function 'query', 'render' function which merges html/header.php, html/required_page.php and html/footer.php at runtime, 'sendmail' function for sending email using PHPMailer.

**php/cron_job.php** 	- Cron Job runs everyday for updating teacher notifications

**backups.sh** 			- Cron Job for everyday backups

**js/js2pdf.js** 		- code for exporting data to pdf

**js/functions.js** 	- functions for success failure frontend alert messages

**html/admin_login.php**- Admin page

**html/home.php** 		- Login and notification page

**html/staff.php** 		- Entire staff page with all functions

### Setup

1. Install LAMP or XAMPP Server
2. Copy entire folder to apache root directory
3. Create db in phpmyadmin and import Structure.sql
4. Set database and email(gmail only) fields in php/constants.php (Enable less secure apps in Gmail)
5. Setup cron jobs for **php/cron_job.php** and **backups.sh** (Change folder name in backups.sh)
6. Register through webpage
7. To access admin page, change Role field in Staff table to 1
8. Done!

## Screenshots

![ScreenShot](https://raw.githubusercontent.com/rjagiasi/Attendance/master/Screenshots/Screenshot%20from%202017-09-25%2023-12-23.png)

![ScreenShot](https://raw.githubusercontent.com/rjagiasi/Attendance/master/Screenshots/Screenshot%20from%202017-09-25%2023-12-36.png)

![ScreenShot](https://raw.githubusercontent.com/rjagiasi/Attendance/master/Screenshots/Screenshot%20from%202017-09-25%2023-17-34.png)

![ScreenShot](https://raw.githubusercontent.com/rjagiasi/Attendance/master/Screenshots/Screenshot%20from%202017-09-25%2023-13-19.png)

![ScreenShot](https://raw.githubusercontent.com/rjagiasi/Attendance/master/Screenshots/Screenshot%20from%202017-09-25%2023-13-46.png)

![ScreenShot](https://raw.githubusercontent.com/rjagiasi/Attendance/master/Screenshots/Screenshot%20from%202017-09-25%2023-20-47.png)
