# Kirby Vercel

This is a plugin to deploy your static site directly from the Kirby Panel to Vercel.

![](kirby-vercel.gif)


## Installation

### Download

Download and copy this repository to `/site/plugins/kirby-vercel`

### Composer

```
composer require f-mahler/kirby-vercel
```

## Setup

/site/config/config.php

```php
return [
  'f-mahler.kirby-vercel.deployurl' => '<deployUrl>',
  'f-mahler.kirby-vercel.token' => '<token>',
  'f-mahler.kirby-vercel.projectid' => '<projectId>',
  
  // Automatically deploy when triggering one of the following hooks. See Kirby documentation for possible options
  'f-mahler.kirby-vercel.hooks' => [
    'site.update:after',
    'page.update:after'
  ]
];

```

In your blueprint (e.g. /site/blueprints/site.yml)

```
vercel:
  label: Vercel
  type: vercel
  button: true
  help: "Click to deploy the changes to the website"
```

## License

MIT

## Credits

- [Frederik Mahler-Andersen](https://github.com/f-mahler)
