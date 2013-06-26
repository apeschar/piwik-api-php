piwik-api-php
=============

An easy PHP wrapper for the Piwik API.

Please read the [Piwik API reference](http://piwik.org/docs/analytics-api/reference/) first.

The PiwikAPI PHP class exposes the exact same functions as documented at the link above. It is very easy to use.

First, find the API token in the Piwik interface by clicking "API" in the navigation bar. Then, try this example.

    <?php

    $api = new PiwikAPI('http://example.com/piwik', '0123apitokenhere');
    $users = $api->UsersManager->getUser(array('userLogin' => 'anonymous'));
    var_dump($users);
    
    ?>

All API modules and methods are automatically exposed using PHP's magic methods.
