!!if you prefer to go up with docker, it is already configured in the project.

## HOW TO CONFIGURE

PHP >= 7.2.5

Laravel Installed

copy .env.exemple to .env

php artisan key:generate

npm install

npm run dev -> to build components Vue

## HOW TO RUN

php artisan migrate

php artisan serve

## HOW TO RUN NOTIFICATION SCHEDULING

php artisan queue:work

Make sure you open a different terminal=>

RUN NOW> php artisan notify:guests

OR

php artisan schedule:run
