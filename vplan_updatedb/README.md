# vplan_updatedb
> Getting data from untis interface

This software is part of [VPlanTouch](https://softatomos.com/kane/).
This part is for data retrieval using the untis interface.

## Installation

###### Requirements

- Webserver (Apache/Nginx)
- Database (Mysql/MariaDB)

###### Step by Step

- Clone repository to vplan_updatedb folder (Web Directory)
- Go to etc/
- Edit **logins_example.json** and rename file to **logins.json** (Databse connection, Untis connection) 
- Create database 'webscheduler' and import structur from **webscheduler.sql**
- Finished

###### Update Script
- Clone repository to vplan_updatedb folder (Web Directory)
- Insert structur of sql file to main database
- Finished

## Run Script

Open link: [yourdomain]/vplan_updatedb/cron_untis.php

*Script runs by none parameters every 10 min*

###### Get-Paramter
- [force] => Runs script ignores time setup

## Status Codes
Comment on top in this file [classes/UntisFetch.php](https://github.com/auerth/VPlanTouch/blob/master/vplan_updatedb/classes/UntisFetch.php)

## Usage example

[VPlanTouch (Kane)](https://softatomos.com/kane/) is an solution for schools which are using untis for there timetable management. 

## Release History

**See [changelog.txt](https://github.com/auerth/VPlanTouch/blob/master/vplan_updatedb/changelog.txt)**

## Meta

Thorben Auer – thorrogramm@gmail.com
