# Simple-PHP-Shrinker

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

Features:
----------------------------------------
[1] Simple PHP Shrinker was created as a service to make posting long URLs easier.
    This is allows you to convert quite instantly an endless internet adress 
    into a new shorter one, that points to the exact same page but that is only
    20 characters long.

    When someone click on the tiny link short cut, before be redirect on the 
    web, script shows the page with static text for several seconds. (Admin can
    change the text, time of showing and also have possibility to switch on/off
    the intermediate 'static' page.)

    In admin area you have:
    - report part. There you will see statistic reports of site usage. For example,
      what is the most popular URL on you site, IPs list etc.
    - database record manager. Web interface for database management. You will have
      ability to delete users URL from you site database.
    - site settings. Web interface to change site settings and configs.

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

[5] Create the mysql database (usually tinylink) with mysqladmin.
    mysqladmin  -u<your_mysql_user> -p<your_mysql_pass> create tinylink

[6] Create the mysql tables. You have 2 options :
      1) mysql -u<your_mysql_user> -p<your_mysql_pass> tinylink < db_structure.sql
      2) use phpMyAdmin to run the SQL file 'sql/db_structure.sql'


[7] All should be ok at this point.

Administration:
----------------------------------------
Enter the link 

e.g.: http://www.example.com/administration/admin.php

and type your login and password there. At the first time they are login=admin and password=admin

Then go to "Settings" menu and change them.

Also you should update URL to index file.
You can use such URLs:
  - if you are using original script:
      http://www.example.com/index.php 
      http://www.example.com/
  - if you have renamed index.php file to other name (e.g link.php)
      http://www.example.com/link.php 

At the page "Edit Ad" you can change you message for the page.

