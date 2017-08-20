# Promotion Friend

My project consists of a prototype site for a system for exchanging inter-site disclosures. The site user registers and inserts a banner (200x600) that will be automatically announced by my system. This registered user receives an iframe code that when inserted in a site realizes the display of the banners of our many partners, similar to google adsense.
The system currently uses the number of clicks and visions provided to rank who should have your most viewed banner.
My system can be expanded to some advertising-based income-earning system.
Acquiring my prototype you are free to modify it as you wish.
[Click here](http://promotionfriend.yugiohult.com.br) to see an online and working version of my prototype

# Features!

  - Registration system with google recaptcha protection
  - Ranking system based on who posted the most
  - The install.php page provides a quick installation
  - Administrators page that lets users easily ban
  - Real-time feedback to the user on the number of clicks and views received and promoted

The system was fully developed with technologies: Jquery, HTML, PHP, MySQL and Apache



Besides these there are other pages that collaborate in the operation of the system

### Installation
---
Watch the installation [video](https://youtu.be/Y9yjYsDI0_0)

By installing an apache server, php 5.x or higher and the MySQL manager you will be ready to start.
Send all files to the server and open the install.php page
After completing the requestor data click on install and done


### User ranking
---
I developed a ranking system that consists of the following calculation:

>((Clicks generated * 500) + Views generated) - ((Received clicks * 500) + Views received)

Using this calculation the system (libs / db_lib.php) will generate a ranking of the 100 best users.
They will be randomly drawn by get_banner.php when choosing which banner to generate.
Of course, these 100 best fell fast in the ranking because they will be the only ones receiving clicks and views which will naturally make the calculation shown to become smaller.

### Development
---
Developed in object-oriented php the system has several pages with very well designated functions

The page set_banner.php perform an analysis of the submitted banner and only if it is a valid image (.png .jpg) and has resolution 200x600 it will be accepted.
It will be converted to .png format to become compatible.

Do not forget to enable reading the .htaccess
>a2enmod rewrite 
And to have a server with the following properties:
 - Apache server
 - PHP 5.x or better
 - MySQL server
 - Mod_Rewrite enabled
 - Curl enabled
# Do it yourself
Enter the [demo version](http://promotionfriend.yugiohult.com.br) and try to create an account.
