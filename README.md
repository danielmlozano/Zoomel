# Zoomel

## A Zoom OAuth2 API library for Laravel

Zoomel is Laravel package to manage the OAuth2 API version from Zoom.

The package is still in development. Any contributions or comments are welcome.

## Usage

1. Edit yor Laravel project composer.json file to add the following:

```
"require": {
    ...
    "danielmlozano/zoomel": "dev-master"
},
...
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/danielmlozano"
    }
]
```

2. [OPTIONAL] Publish the package config file

```
$ php artisan vendor:publish --tag=zoomel-config
```

3. Add your Zoom Client ID and Zoom Client Secret to your .env or edit the zoomel.php config file

```
ZOOM_CLIENT_ID=x_xxxxxxxxxxxxxxxxxxx
ZOOM_CLIENT_SECRET=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

4. Add the OAuth redirect url of your application to your .env or edit the zoomel.php config file

```
ZOOM_OAUTH_REDIRECT_URI="http://yourapp.dev/path/to/redirect/oauth/"
```

5. Run the package migrations

```
$ php artisan migrate
```

6. Add the ZoomAccount interface and HasZoomAccount trait your User model and the ZoomUserToken model hasOne relationship

```
<?php
...
use Danielmlozano\Zoomel\Interfaces\ZoomAccount;
use Danielmlozano\Zoomel\Traits\HasZoomAccount;
...
class User extends Authenticatable implements ZoomAccount
{
    use HasZoomAccount, Notifiable, ...;
...
public function zoomToken(){
    return $this->hasOne(ZoomUserToken::class);
}

```
