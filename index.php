<?php
@include_once __DIR__ . '/lib/functions.php';

Kirby::plugin('f-mahler/kirby-vercel', [
  'options' => [
    'deployurl' => 'defaultValue',
    'token' => 'default',
    'projectid' => 'default',
    'hooks' => null,
  ],
  'fields' => [
      'vercel' => [
        'props' => [
          'label' => function ($value = "Vercel") {
            return $value;
          },
          'button' => function ($value = true) {
            return $value;
          },
          'help' => function ($value = false) {
            return $value;
          },
        ],
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
