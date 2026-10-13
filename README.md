# Smart Student & Faculty Attendance Management System
KLE Society's Degree College Ankali - Developed & Designed by Vishwa
Requirements: Windows, XAMPP with PHP 8.1+ (fileinfo and pdo_mysql enabled, both default), MySQL/MariaDB. Python 3.9-3.12 only for face recognition.

## 1. Install the web application (new installation)
1. Copy this folder to `C:\xampp\htdocs\attendance-system`.
2. Start Apache and MySQL in XAMPP.
3. phpMyAdmin > Import > `database/attendance_system_complete.sql`. (Upgrading an older install? Run only the update files you have not yet run, in order: phase2_update, phase3_update, final_update.)
4. Check `config/database.php` (default root, no password, BASE_URL `/attendance-system`).
5. Open http://localhost/attendance-system/ and sign in with KLE / 1241. Then open Settings and change the password immediately.

## 2. First-time data entry order
Courses > Subjects (same page) > Faculty > Classes > Students (or CSV import) > Face Registration.

## 3. Local face recognition service (needed for photo attendance)
1. Install Python 3.11 from python.org (tick "Add python.exe to PATH"). Python 3.9-3.12 also work.
2. Double-click `face_service\start_face_service.bat`. The first run installs everything automatically (internet needed once, a few minutes). After that it just starts.
3. Keep that black window open while taking attendance. It listens only on 127.0.0.1:5001, and no image leaves this computer.
The Face Registration and Attendance pages show whether the service is running. Without it, attendance can still be marked manually.

## 3b. Live camera
Face Registration and the attendance photo page have a **Use live camera** button. Click it, allow camera access in the browser, press **Capture photo** (repeat for more), then upload. Use **Switch camera** to change between front and back cameras.
The browser only allows cameras on `http://localhost/...` or HTTPS. If you open the site from another computer using an IP address (http://192.168...), the camera button will show a message; use the normal file upload there, or set up HTTPS.

## 4. Daily workflow
Attendance > pick date and class > upload classroom photos > Recognise faces > review every row (yellow rows need verification; changing a recognised result needs a reason) > Verify & finalize. Reports use finalized sessions only.

## 4b. Timetable (new)
Timetable > Upload timetable (CSV, template provided) or add lectures one by one. Each lecture is **Running** until it is changed. Use **Edit** to change the day, time, room or substitute faculty (a reason is required): the lecture becomes **Adjusted**, keeps the original details and a change history, and can be restored with the reset button. Today's lectures also appear on the Attendance page with a **Take attendance** button. Existing installs: run `database/timetable_update.sql` once.

## 5. Attendance rules implemented
Expected students come from the class (course, year, semester, section). **Face registration** accepts a photo only if it contains exactly one face and is not already registered to another student (the face service must be running). **Attendance** is strict: a face in the classroom photos must match a registered photo within the *Face match tolerance* (Settings, default 0.50; lower = stricter) and clearly beat the next-closest student (look-alike margin). Matched = **Present (Face verified)**; everyone else = **Absent**. Present = unique matched students (a student in several photos counts once). Manual corrections need a reason and are stored in attendance_audit_log.

## 6. Security checklist before real use
- Change the default password; set a MySQL root password and update config/database.php.
- Use the system on a trusted college network only. Serve over HTTPS if it is reachable beyond localhost.
- Keep Windows, XAMPP, PHP and Python updated; back up the database and uploads/ folder regularly.
- Student photos and face references are private (uploads/ is blocked from the web and served only to logged-in admins). Delete a student's face data from Face Registration when it is no longer needed.
- Tell students and parents that photos and face data are collected for attendance, and keep to your college's and local data-protection policy.
- Set `display_errors = Off` in php.ini on a production machine.

## 7. Known limits
PDF output uses the browser (Print > Save as PDF). Excel output is CSV, which Excel opens. Face recognition is probabilistic, so human verification is always required.
