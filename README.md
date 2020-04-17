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
        "url": "https://github.com/danielmlozano/Zoomel"
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

7. Now, simply make a link to redirect any user to authorize a connection with Zoom and your app, using the Zoomel route naed "zoomel.access"

```
<a href="{{ route('zoomel.access') }}">Connect your Zoom account"</a> 

```

8. If the user authorizes the connection, the you will be redirected to the OAuth redirect url previously defined in your .env. The URL will receive two query strings: status and token_id. 

The status will carry the Zoom code status from the connection, that in a authorized connection will be 200.

The token_id carries the safe ID for the OAuth token from Zoom API that is stored in your database table "zoom_user_tokens".

From here, you can attach this token to the user in your app:

```
$safe_token_id = $request->input('token_id');

//The $user instace could come from auth, request or directly from the database.
$user->attachZoomToken($safe_token_id);

//You can also pass a ZoomUserToken instance to the attachZoomToken method.
$token = ZoomUserToken::findSafeId($safe_token_id)->first();
$user->attachZoomToken($token);

```

9. Now, the user can perfom a series of actions in its Zoom Account

```
$user->getZoomAccount(); //Gets the Zoom User data

$user->getMeetings(); //Gets the list of the user's meetings

$user->getMeeting($meeting_id); //Gets a meeting from a integer meeting id from Zoom

$user->createMeeting([
    'topic' => 'Monday's morning meeting',
    'type' => 2,
    'start_time' => '2020-20-08T09:00:00Z',
]); //Creates a new meeting

$user->updateMeeting($meeting_id, [
    'topic' => 'Monday's morning meeting',
    'type' => 2,
    'start_time' => '2020-20-08T09:00:00Z',
]); //Updates a existing meeting, passing the integer meeting ID from Zoom

$user->deleteMeeting(76198546628); //Permanently deletes a meeting, passing the integer meeting ID from Zoom

```

These methods, except for the updateMeeting, deleteMeeting and getMeetings, return a instace of ZoomMeeting, an object with all the data returrned from the API. The getMeetings returns a ZoomMeetingsList instance, an obecjt with the paginator data from the API and a property named "meetings", a Laravel Collection of ZoomMeeting instances. Both the updateMeeting and deleteMeeting return a simple string with the action perfomed.

## ToDo:

- CRUD for Zoom webinars
- Make automated tests
