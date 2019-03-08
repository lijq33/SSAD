##SETUP
#COMPOSER AND NPM
Composer Update
npm install
Open mysql, create a schema name 'ssad'

#starting the services
run mysql service using XAMPP 
npm run watch
php artisan serve

#Webpages
127.0.0.1:8000

#Creating a User
php artisan tinker

replace X and N accordingly - try to use legitimate information as we will 
the email and phone number will be required for some functionality.

$user = App\User::create([ 'name' => 'XXXXX', 'nric'=>'XNNNNNNNX', 'email' => 'X@X.com', 'password' => \Illuminate\Support\Facades\Hash::make('xxxxxx'), 'telephone_number' => 'xxxxxxxx' , 'roles' => 'SuperAdmin' ]);


##THINGS TO DO



##Completed Functionalities

