![](http://files.festivalengine.co/apptapp/33341e399d8f15daa4607768.png)

#AppTapp Api
[TOC]

#Routes
- Built around the [Dingo Api](https://github.com/dingo/api) package, the api.php file is grouped by:
 - Api Version 
 - General Auth middleware
 - Personal Auth middleware
- When an app is opened, it creates or updates it's installation object. Properties like device, os and app version number get updated here for in-house analytics.
- The app also checks for a local user object and if dne, sends a request to create an anonymous one and saves the result locally before calling additional auth related api calls.
- If the app requires a logged in user, users/register and users/login are available. 
- JWT tokens are provided as part of the login process and provide a way to secure  admin-only routes via:
```
$api->group(['middleware' => ['jwt.auth', 'roles:admin']], function ($api) {
```

- Within access groups (Admin, Public, Personal), model objects are treated the same way with standard CRUD operations and response transformer logic that provides mobile development with expected response types.
- See app/Http/Middleware/ETagMiddleware for cache logic.

#Admin
- Located in app/public/app, front end html/css/js can be altered quickly to provide a backend for an app.
- Replacing prefix of https://yyy.festeng.com in, app.index.js, app.js, app.login.js should be the first step of alterations.
- When adding an adminable backend object to the api, make the following changes to the public folder:
 - Add to /views yyyyy.html and yyyy-detail.html
 - Add to /controllers yyyyyController.js and yyyyyDetailController.js
 - Add js tags to app/resources/views/admin/dashboard.blade.php
 - Add link to /views/navigation.html
 - Add routing lines to app.js
 - Add app.factory('yyyyyyResource', itemResource); to bottom of public/app/resources/itemResource.js
- To create an Admin user:
 - ssh into server and cd to base directory: ``` php artisan db:seed ```
 - This creates a user with the admin role for the email in the .env and the password printed to the console
 ![](http://files.festivalengine.co/apptapp/admin-1.png)
 
#Auth
- Test in Postman for all routes
- base_url in this example is: https://white.festeng.com
- Use https://jwt.io/ for visualizing token pieces. See how they differ between user types
- User requests same as public but require jwt with access

##Register Anonymous 
- POST {{base_url}}/users/anonymous
- Save the token for additional calls

```json
 /** Header **/
 Content-Type:application/json
 Accept:application/vnd.p3.v1+json
 
 /** Body **/
 {
     "anonymousId": "95091A1F-BA8E-44F8-9E39-C22EEB5CE3A5" (UUID.v4)
 }
 
 /** Response **/
 {
     "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL3doaXRlLmZlc3RlbmcuY29tL3VzZXJzL2Fub255bW91cyIsImlhdCI6MTUwNTkzODk3MiwibmJmIjoxNTA1OTM4OTcyLCJqdGkiOiJYRkplUFhUZW5JQ2dZOXhtIiwic3ViIjoiNTljMmNlMWNmZDg5Nzg0MGI0MTYyZTMzIiwiaWQiOiI1OWMyY2UxY2ZkODk3ODQwYjQxNjJlMzMiLCJ1c2VybmFtZSI6IiIsInJvbGVzIjoiIn0.Ns-yWoRCqlA3BBBM_-fh-uuD4eGgGF3ks-IERhVCrpI"
 }
```

##Fetch Public Collection
- GET {{base_url}}/classes/{{object}}

```json
 /** Header **/
 Content-Type:application/json
 Accept:application/vnd.p3.v1+json
 Authorization:Bearer {{access_token}}
 
 /** Response **/
 {
    "data": [],
    "meta": {
        "cursor": {
            "current": null,
            "prev": null,
            "next": null,
            "count": 0
        }
    }
}
```

#App
##Models
- Models in base app folder are protected from public and typically have custom routes /user, /message, etc
- Models in app/Http/{Version}/Models are standard public accessible objects
- Models in app/Http/{Version}/UserModels are personal such as itinerary objects, challenge objects, etc. Controller logic is different from public
- New projects backed by mongo are more flexible than mysql and do not require migration files for tables (a major reason for the switch). Object properties can be written in the model file and become accessible via the api


##Controllers
- Endpoint logic handled here. Auth, File and Message still have custom logic that can be project dependent such as username generators, additional UA fields, etc
- ApiObject and ApiUserObject use PHP's flexibility to provide common logic across objects with similar backend properties
- Should a new object need it's own logic, create a controller and add the api route to routes.php _above_ the existing route

##Console
- ImportTools is a collection of random functions used in import/cron scripts throughout projects
- Typically app/Console/Commands is where pre event imports or post event export scripts would be saved and run
- In Kernal.php you can schedule commands as if they were crons. You will still need to setup a global cron on a per project basis in Forge if you need this. See https://laravel.com/docs/5.5/scheduling for more details
- Look at other projects for examples of import scripts


