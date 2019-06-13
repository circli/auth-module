<?php

$base = dirname(__DIR__);

$provides = include __DIR__ . '/provides.php';
$ns = $provides['templates']['ns'];

return [
    'actus_templates' => [
        $ns => $base . '/templates',
    ]
];
