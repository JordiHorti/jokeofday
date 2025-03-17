<?php

$functions = [
    // The name of your web service function, as discussed above.
    'mod_jokeofday_save_rating' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'mod_jokeofday\external\jokeofday_services',

        // A brief, human-readable, description of the web service function.
        'description' => 'Catch ratings of jokes',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            MOODLE_OFFICIAL_MOBILE_SERVICE,
        ]
    ],
];