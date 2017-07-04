# Project Documentation

## Working

It is a 4 page application

* **Home page** consists of login and notifications.
* **Register page** for new staff members.
* **Staff page** works entirely by sending and processing Ajax requests.
* **Admin Page** again uses Ajax for submitting details to backend.

## Database Structure

> Database Schema in **Structure.sql** and diagram in **Schema.pdf**. Fake test db in **Entire_db.sql**

### Tables

#### Staff 

* StaffId - Auto Incremented
* Name - Faculty Name
* Gender - Bit field, 1 for male, 0 for female
* Email - Faculty email id
* Username - Faculty username
* Salt - Hashed password
* RegisterTime
* Role - Bit field, 0 default, 1 for enabling admin priviliges and admin login

#### Student

* StudentId - Auto Incremented Id 
* Name - Student name
* ClassId - Foreign key from Class Table
* RollNo - Roll no of student

#### Department

* DeptId - Auto Incremented
* Name (IT, CMPN, etc)

#### Class

* ClassId - Auto Incremented
* Name (D10, D15, etc)
* DeptId - Foreign Key from Department

#### Subjects

* SubjectId - Auto Incremented
* Name (DSA, WP, WP_LAB, etc)
* ClassId - Foreign key of Class showing subject belongs to which class
* LectorLab - Bit field, 0 for lect, 1 for labs

> Note - Subject and Lab of a subject are considered as different subjects. For eg, if WP is a subject which has labs as well, 2 entries will be present in this table. WP with LectorLab as 0 & WP\_LAB with LectorLab as 1


#### Record

> Each entry shows whether a student was present or absent on that date for a particular subject for a particular lecture number of that subject for the day. 

* Date - Date of lecture
* StudentId - Foreign key from Student table 
* SubjectId - Lecture was for which Subject, Foreign key from Subject Table.
* Lec\_no - Lec number for the Subject on that day. (Eg: If 2nd lec for DBMS is taken, Lec_no is 2)
* PA - Bit Field, Present 1, Absent 0

#### Labs

* LabId - Auto Incremented ID
* SubjectId - Foreign key to Lab Subject Entry in Subject Table
* BatchId - A, B, C Batch correspond to 1, 2, 3
* StaffId - Foreign key to Staff who teaches that batch
* Days - Six bits indicating days of week when the Lab is there (eg: If lab is on Tuesday, 010000)

#### Lectures

* SubjectId - Subject Id for Lecture entry in Subjects table
* StaffId - Foreign key to faculty member who teaches the subject
* Days - Six bits indicating days of week when the Lecture is there (eg: If lab is on Tuesday & Thurday, 010100)

#### LabStudent

> Batches and Student mapping for each Class

* ClassID - For which class
* BatchId - In which batch (A, B, C... Batch correspond to 1, 2, 3...)
* StudentId - StudentId which belongs to that class and batch

#### Cancelled

> Stores details of lectures that were cancelled

* StaffId - Faculty which cancelled lecture
* SubjectId - Subject which was cancelled 
* Date - Lecture on which date was cancelled
* Reason - Reason for cancelling the lecture

#### Notifs

> Stores notifications of lectures where attendance was not added. These are displayed when user logs in.

* SubjectId - Foreign key of Subject Table
* DateMissed - Date for which attendance was not added
* StaffId - StaffId for whom notification is to be shown (which is the faculty who teaches it)

### Views

#### NOL 
> Shows total number of lectures for each subject

### Procedures

#### GetClasses

> Returns the list of class and department in which a particular staff member teaches. Takes StaffId as an input argument.

#### GetClassReport

> Returns entire class report. Takes start date, end date and ClassId as input Argument. Output includes RollNo of student, SubjectId, No of lectures present, No of lectures Absent in that subject.

#### GetStudentReport

> Returns Report of particular student. Takes ClassId and RollNo as Input. Returns SubjectId, No of lectures present, No of lectures Absent in that subject.

#### GetSubjectReport

> Returns Report for particular subject. Takes start date, end date, ClassId and SubjectId as input Argument. Returns RollNo, No of lectures present, No of lectures Absent in that subject.

#### GetSubjects

> Returns the list of subjects which had lectures between start date and end date. Takes start date, end date and ClassId as input arguments. Returns Name and SubjectId 


