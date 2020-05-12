# Kirby Vercel

This is a plugin to deploy your static site directly from the Kirby Panel to Vercel.

![](kirby-vercel.gif)


## Installation

### Download

Download and copy this repository to `/site/plugins/kirby-vercel`.

### Composer

```
coming soon
```

## Setup

/site/config/config.php

```php
return [
  'f-mahler.kirby-vercel.deployurl' => '<deployUrl>',
  'f-mahler.kirby-vercel.token' => '<token>',
  'f-mahler.kirby-vercel.projectid' => '<projectId>',
  'f-mahler.kirby-vercel.hooks' => [
    'site.update:after',
    'page.update:after'
  ]
];

```

In your blueprint

```
vercel:
  label: Vercel
  type: vercel
  button: true
  help: "Click to deploy the changes to the website"
  width: 1/3
```

## License

MIT

## Credits

- [Frederik Mahler-Andersen](https://github.com/f-mahler)
