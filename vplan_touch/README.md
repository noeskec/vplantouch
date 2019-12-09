# vplan_touch
> Getting data from untis interface

This software is part of VPlanTouch.
This part is for data viewing using the the database structure from [vplan_updatedb](https://github.com/auerth/VPlanTouch/tree/master/vplan_updatedb).

## Installation

###### Requirements

- Webserver (Apache/Nginx)
- Database (Mysql/MariaDB) filled with data from [vplan_updatedb](https://github.com/auerth/VPlanTouch/tree/master/vplan_updatedb)

###### Step by Step

- Clone Repository to vplan_updatedb folder (Web Directory)
- Edit **config/pin_config_example.sha512** and rename file to **pin_config.sha512** (Insert pin SHA-512 encoded) 
- Edit **bin/dbcon_example.php** and rename file to **dbcon.php** (Databse connection) 
- Set up school configuration in **config/school_config_generate_file.php**
- Navigate your browser to http://[yourdomain]/vplan_touch/
- Enter in Searchbar **" config"**
- Enter your pin
- Click on "Bake Configuration to JSON File"
- Finished (Navigate your browser to http://[yourdomain]/vplan_touch/)

###### Update Script
- Clone Repository to vplan_touch folder (Web Directory)
- Finished

## Use Software

Open link: http://[yourdomain]/vplan_touch/

## Usage example

[VPlanTouch (Kane)](https://softatomos.com/kane/) is an solution for schools which are using untis for there timetable management. 

## Release History

**See [changelog.txt](https://github.com/auerth/VPlanTouch/blob/master/vplan_touch/changlog.txt)**

## Meta

[Dominik Ziegelhagel](https://ziegenhagel.de/) 
