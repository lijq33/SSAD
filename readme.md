##Brief
emergency situations can arise due to dengue outbreak, haze, bird, flu outbreak, earthquake, aftershocks, tsunami’s, terrorist activities or accidents within crowded, confined areas.

##SETUP
#COMPOSER AND NPM
Composer Update
npm install
Open mysql, create a schema name 'ssad'

##.env
add:
NEXMO_API_KEY=6e2a812f
NEXMO_API_SECRET=bWjtpcyA22k3yjlq
run php artisan jwt:secret

#starting the services
run mysql service using XAMPP 
npm run watch
php artisan serve

#Webpages
127.0.0.1:8000

#Commands to try if there are any issues
php artisan cache:clear
php artisan config:clear
composer dump-autoload
Check for the existance of .env
Start Mysql Service

#Creating all UserType.
php artisan db:seed --class=UsersTableSeeder
php artisan db:seed --class=SubscribersTableSeeder
look at UserTableSeeder for more details

##Type of user
role 1 - Call Center Operator - able to register a new crisis
role 2 - Crisis Manager - able to update and archive crisis
role 3 - Civil Defences Admin - able to keep track which CD shelter is in-charge of the crisis. The status of the CD shelter
role 4 - Account Manager - able to register and remove new account


##THINGS TO DO
1)Real time status update on a map of singapore integrated with weather conditions, dengue hot spot, haze information and so on.
    - PERFORM AJAX/AXIOS request to data.gov.sg. Those information can be then displayed on our maps.

The Prime Minister’s Office shall receive a status report summarizing key indicators and trends over email every 30 minutes.
    - Mailswift & ????

The CMS shall provide useful information such as location of Civil Defence shelters and updates to the public through social media periodically.
    - Every 30min?

The CMS shall serve as a command-and-control platform to respond quickly to needs and dangerous conditions,such as gas leaks, hazardous air condition and fires

The input of the CMS may come from different sources. The main one is from call center. 
The public may call call center hotline for assistance and incident reporting. Call center operators 
will request the following information from members of the public:
    • Name
    • Mobile number
    • Location (postal code and building unit number, if applicable)
    • Type of assistance requested
        o Emergency Ambulance 
        o Rescue and Evacuation 
        o Fire-Fighting
        o Gas Leak Control

Call center operators shall enter the information provided into the CMS via a user interface (either web or desktop application). 
Requests are immediately updated on the map and dispatched using SMS to the relevant agencies:
o Emergency Ambulance – Singapore Civil Defence Force (SCDF)
o Rescue and Evacuation - SCDF
o Fire-Fighting - SCDF
o Gas Leak Control – Singapore Power
Other possible courses could be weather forecast API (http://developer.yahoo.com/weather/), PSI 
reading for air pollution (http://www.nea.gov.sg/psi/). 


##Completed Functionalities


##Tools
SMS - Nexmo
Database - MySQL
MapUI - Google Maps API

Facebook - Facebook API
Twitter - Twitter API

Reporting - JasperReports
Central Processing Unit 
CallCentreUI
Weather - Yahoo! Weather RSS Feed
Email - Apache Commons Email




##Non-Functional Requirement - Security
#CSRF TOKEN
token: '{{csrf_token()}}'
google XSS, CSRF

#Password 
Hash::make($data['password']) -> one way hashing of password 
Example
123123 => hash => $2y$10$ei.Tqu7.zDbDZDi/W06sCOlveVOTTF.LVB/k/lqfs98PP9c/SDoGW

#Database
protected $fillable = [....]; Only variable in fillable can be stored into database. The other variables are ignored.
For instance $fillable = ['user_name'], but client pass in user_name = "abc" password = "123", password will be ignored

#Data Validation
Laravel Validator (use Illuminate\Support\Facades\Validator;)
This is basically a backend validation to ensure that we receive what is legitimate. 
For instance, The rule stats that 

    public static $rules = [
        'name' => 'bail|required',
        'telephoneNumber' => 'bail|required|integer|digits:8',
        'postalCode' => 'bail|required|integer|digits:6',
        'date' => 'bail|required|date_format:d/m/Y|before:tomorrow',
        'time' => 'required',
        'address' => 'required',
        'crisisType' => 'required',
    ];
. This means that if anything or anyone tries to perform some malicious actions such as man-in-the-middle attack, we ensure that the damage is reduce to the minimum. For instance the legitimate request date is 12/3/2019 but some hacker change it to 14/3/2019. We know that this is not possible as we would not know if a crisis happen tomorrow. Hence, this safeguard our backend by adding one additional layer of security.

#JWT Token
Secure method to transmit JSON as it is digitally signed
Read https://jwt.io/introduction/ for more detail

#Permission Level
level = 1 Call Center Operator - able to create and update crisis
level = 2 Admin - able to archive crisis
level = 3 CD Admin - able to keep track which CD shelter is in-charge of the crisis, and the status of the CD shelter. 
        CD Admin must also be able to check which other CD shelter is available to assist in resolving the crisis. 
level = 3 Super Admin - able to do what level 1 & level 2 are able to do, additionally, it may add register new lvl 1-2 acc

#middleware - Together with Permission level (Think of middleware as backend security guard)
Before the request reaches the API, It will 1st go through a layer call middleware. In This layer, middleware, it will check the user's permission before allowing access.

#Minimum user Input.
Rather than trusting 100% what the current user is telling us, we minimize the information that the user pass to us.
Example. 
The current user has an id of 1 - Call center Ops. However when he post the data to the server, he stat that he has an id of 2 - SuperAdmin. Hence, we shouldn't trust user input. Thus, we always fetch the current user at the backend, where user input is not required.         
$user = new User();
$data['id'] = $user->fetchUser()['id'];