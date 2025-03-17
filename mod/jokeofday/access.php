<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(

    // Capacidad para añadir una instancia del módulo
    'mod/jokeofday:addinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'student' => CAP_PROHIBIT,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),
        'clonepermissionsfrom' => 'moodle/course:addmodule', // Copiar permisos desde el módulo estándar
    ),

    // Puedes agregar más capacidades según sea necesario. Por ejemplo:
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
