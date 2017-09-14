# bulb
bulb is a scalable MVC framework, written in object-oriented PHP 7.0. It is designed to be used for the majority of websites that we build—whether it be a static site, a blog site, or a large, complex PHP application. It uses Twig for templating, and utilises APCu caching to speed things up. It is designed to be very configurable, on a per-site basis.

## Installation
To use bulb in a new project, you can require it with Composer just as you would any other package. The only difference is that you have to define the repository within the `composer.json` file, as it isn't available on Packagist (the default site that packages are install from):

```
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/wearethegarden/bulb"
    }
]
```

Once you have added that, you can just run `composer require wearethegarden/bulb` to install it.

## Front controller
All requests are sent to the main app file (`app.php`) which sits in the project root. From here, all requests are dealt with accordingly. The routing of all requests to that file can be done in either Apache, or NGINX;

##### Apache
```
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ app.php/$1 [L]
```

##### NGINX
```
try_files $uri $uri/ /app.php;
```

## Configuration
The main app configuration sits within the file located at `{$app->path}/config/config.php`. This is where the name, version, and any other relevant configuration is set. This is also where the app is initialised. i.e. `$app = new \Bulb\App($path)`.

## Routing
The app routes are defined within `{$app->path}/config/routes.php`. This is where you define the URL, namespace, controller, and action i.e. `$router->add('/', ['namespace' => 'namespace\Controllers', 'controller' => 'Account', 'action' => 'signup'])`.

Once you have defined all the desired routes in the `{$app->path}/config/routes.php` file, you need to call the `dispatch` function. This will check that the current route is valid, and it will also initialise the relevant controller and call the action defined for that route e.g. if a user lands on `/about`, but there isn't a define route, an error page will be returned, along with a 404 HTTP status. If `/about` is defined, and the controller is set to `About`, and the action is set to `index`, then a new instance of `About` class will be created, and the `index` method will be called.

## View
The views sit within the `{$app->path}/Views` folder. For small projects, pages can be stored in the top-level of this folder i.e. `{$app->path}/Views/Home.php`, `{$app->path}/Views/About.php` etc. For bigger projects, these can be separated into sub-folders i.e. `{$app->path}/Views/Account/Login.php`, `{$app->path}/Views/Admin/Settings.php` etc. Master templates can be stored in the templates folder (`{$app->path}/Views/templates`), and partials in the partials folder (`{$app->path}/Views/partials`).

## Controller
Controllers are stored in the `{$app->path}/Controllers` folder, and follow the naming convention [Page Name + Controller] i.e. `AccountController`. For smaller projects that only have a handful of top-level pages, a master 'pages' controller can be used i.e. `PagesController` where the actions for all the pages reside.

## Caching
Caching can be enabled to speed up the PHP application. This can be enabled in the `{$app->path}/config/config.php` file, by setting `$app->cache->enabled` to `true` (the `$app->cache` object needs to be initialised before hand i.e. `$app->cache = (object) []`). Doing so will enable APCu caching, and also the in-built Twig caching. To utilise Twig caching, you will need to create a new folder (setting the permissions to 777 so that it is writable), and then define the path within the aforementioned config file, using `$app->cache->path` i.e. `$app->cache->path = "{$app->path}/cache"`.

## Miscellaneous
### Environment-specific configuration
Environment-specific details are defined in the file located at `app/config/environment.php`. There is a sample file located at `app/config/environment-sample.php`.

### JSON files
JSON files can be stored in the `{$app->path}/inc/data/` folder, and accessed via the `Data` class. To do this, initialise the `Data` class `$data = new \Bulb\Data($this->app)`, and then use the `get` function and pass through the filename like so `$data->get('someFile.json')`. You can optionally pass through a schema as the second parameter.

## Todo
- Add in the ability to define placeholders in app routes i.e. `/account/{username}`, so that the information (username, in this case) can be used in the controller, and later on in the app.
- Set-up the Model class so that we can interact with the database (via PDO) using the CRUD methods.
- Set-up class for sending email notifications.
- Set-up class for building forms.
- Set-up class for custom error reporting.
