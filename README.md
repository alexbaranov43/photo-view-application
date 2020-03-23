# INSTALLATION GUIDE

## Initial Steps

* git clone the repository and CD into the repository in the terminal
* Make sure MAMP is running! This is built using MySql for the database

* Make sure to install Composer globally. If it is not installed globally check out the guide at https://getcomposer.org/doc/00-intro.md

* Once the photo-view-application repository is the pwd in the terminal, run the command `composer install` 

* After the initial composer installation, run the command `npm install`

* Next, set up the .env file via the terminal by running the command `touch .env` and copy & pase the .env.example content into the newly created .env file

* Once that is done, in MAMP's PHPMyAdmin, create a new database called 'photo-view-application'

* In the .env file, set the DB_DATABASE=photo-view-application and accordingly change the DB_USERNAME && DB_PASSWORD to personal settings

* In the terminal, run the command `php artisan key:generate` to generate  APP_KEY

* In the terminal, run the command `npm run dev` or `npm run watch` if preffered (this approach will require opening a new separate terminal for the next step)

* In the terminal (or new terminal if `npm run watch` is currently running), run the command `php artisan serve` to launch the site on the local Server

## User Guide
* Register an account with a name, email, and password

* Once logged in, click the button "Parse CSV"

* In the parse csv page, download the csv file, (a csv version of the txt file provided in the instructions) the upload that to the upload input.

* Click the Photo Viewer logo in the top left corner to see all of the photos.

* LEGEND GUIDE: Grayscale Index shows all of photos in grayscale and paginated. Color Index shows all of photos in color and paginated.

* Grayscale Photos shows all of the filtered photos by dimension in gray scale. Color Photos shows all of the filtered photos in color.

* Enjoy!