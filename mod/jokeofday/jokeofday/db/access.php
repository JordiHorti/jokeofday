<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(

    'mod/jokeofday:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,  // Opcional, pero recomendable
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),
        'clonepermissionsfrom' => 'moodle/course:addinstance',
    ),

    'mod/jokeofday:view' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),
    ),
);
print_r($capabilities);