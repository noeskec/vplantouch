# vplan_updatedb
> Getting data from untis interface

This software is part of [VPlanTouch](https://softatomos.com/kane/).
This part is for data retrieval using the untis interface.

## Installation

###### Requirements

- Webserver (Apache/Nginx)
- Databse (Mysql/MariaDB)

###### Step by Step

- Clone Repository to vplan_updatedb folder (Web Directory)
- Go to etc/
- Edit **logins_example.json** and rename file to **logins.json** (Databse connection, Untis connection) 
- Create Database 'webscheduler' and import structur from **webscheduler.sql**
- Finished

## Run Script

Open link: [yourdomain]/vplan_updatedb/cron_untis.php

*Script runs by none parameters every 10 min*

###### Get-Paramter
- [force] => Runs script ignores time setup

## Status Codes
Comment on top in this file [classes/UntisFetch.php](https://github.com/auerth/vplan_updatedb/blob/master/classes/UntisFetch.php)

## Usage example

[VPlanTouch (Kane)](https://softatomos.com/kane/) is an solution for schools which are using untis for there timetable management. 

## Release History

**See [changelog.txt](https://github.com/auerth/vplan_updatedb/blob/master/changelog.txt)**

## Meta

ThorbenAuer – [@borbofski](https://www.instagram.com/borbofski/) – thorrogramm@gmail.com
