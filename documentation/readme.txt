  Copyright (C) 2006 Simple PHP Shrinker <ossama.benbouidda@gmail.com>

COPYRIGHT NOTICE:
----------------------------------------------------
This script is being offered as freeware.

  1. Requirements
  2. Features
  3. Quick Install
  4. Administration
  5. Support
  6. Other Products

Requirements:
----------------------------------------
* OS: Unix, Linux, Windows 9x/Me, Windows NT/2000
* WWW Server: Apache 1.3x+ or other
* PHP: 4.0+
* MySQL: 3.23.xx+


Quick Install:
----------------------------------------
[1] Unzip the distribution file into your http documents directory.

[2] Edit ./inc/config.ini.php file. Set
      root=directory_of_the_script (e.g. )
      dbhost=your_DB_host 
      dbname=your_DB_name
      dbuser=your_DB_user
      dbpass=your_DB_user_password
    Be careful during editing this file!
    All other options including administrator's login and password you can change after login.

[3] CHMOD ./inc/config.ini.php file to 666

[4] CHMOD ./themes_c folder to 777


Administration:
----------------------------------------
Enter the link 

e.g.: http://www.example.com/administration/admin.php

and type your login and password there. At the first time they are login=admin and password=admin

Then go to "Settings" menu and change them.
