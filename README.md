# Attendance

A website for maintaing college attendance

Built on LAMP. Uses PHP7

Database Schema in Structure.sql and diagram in Schema.pdf
Fake test db in Entire_db.sql

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

⋅⋅* Install LAMP or XAMPP Server
⋅⋅* Copy entire folder to apache root directory
⋅⋅* Create db in phpmyadmin and import Structure.sql
⋅⋅* Set database and email(gmail only) fields in php/constants.php (Enable less secure apps in Gmail)
⋅⋅* Setup cron jobs for **php/cron_job.php** and **backups.sh**
⋅⋅* Register through webpage
⋅⋅* To access admin page, change Role field in Staff table to 1
⋅⋅* Done!