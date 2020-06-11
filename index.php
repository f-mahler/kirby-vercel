<?php
@include_once __DIR__ . '/lib/functions.php';

Kirby::plugin('f-mahler/kirby-vercel', [
  'options' => [
    'deployurl' => 'defaultValue',
    'token' => 'default',
    'projectid' => 'default',
    'hooks' => null,
    'cache' => true,
  ],
  'fields' => [
      'vercel' => [
        'props' => [
          'label' => function ($value = "Vercel") {
            return $value;
          },
          'deploy' => function ($value = "Deploy") {
            return $value;
          },
          'loading' => function ($value = "Deploying...") {
            return $value;
          },
          'complete' => function ($value = "Complete") {
            return $value;
          },
          'error' => function ($value = "Failed to deploy") {
            return $value;
          },
          'button' => function ($value = true) {
            return $value;
          },
          'help' => function ($value = false) {
            return $value;
          },
        ],
        'computed' => [
          'siteModified' => function () {
            $cache = kirby()->cache('f-mahler.kirby-vercel');
            $modified = [
              'timestamp' => $cache->get('timestamp'),
              'count' => $cache->get('count')
            ];
            return $modified;
          }
        ]
      ]
  ],
  'hooks' => @include_once __DIR__ . '/lib/hooks.php',
  'api' => [
    'routes' => [
      [
        'pattern' => 'vercel',
        'action'  => function() {
          return \KirbyVercel\App::deploy();
        }
      ],
      [
        'pattern' => 'vercel/latest',
        'action'  => function() {
          return \KirbyVercel\App::latest();
        }
      ],
    ]
  ]
]);
