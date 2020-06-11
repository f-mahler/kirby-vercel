<?php

$names = option('f-mahler.kirby-vercel.hooks');
$timestamphooks = [
	'site.update:after', 'site.changeTitle:after',
	'page.update:after', 'page.create:after', 'page.delete:after', 'page.changeTitle:after',
	'page.changeNum:after', 'page.changeStatus:after', 'page:changeSlug:after', 'page.changeTemplate:after', 'page.duplicate:after',
	'file.changeName:after', 'file.create:after', 'file.delete:after', 'file.replace:after', 'file.changeSort:after', 'file.update:after',
	'user.create:after', 'user.update:after', 'user.delete:after', 'user:changeName:after', 'user.changeRole:after'
];
$hooks = [];

function deploy(string $name)
{
	return function ($input) use ($name) {
    \KirbyVercel\App::deploy();
	};
}

function setTimestamp() {
	return function ($input) {
		$cache = kirby()->cache('f-mahler.kirby-vercel');
		$timestamp  = $cache->get('timestamp');
		$count = $cache->get('count') + 1;
		$timestamp = time();
		$cache->set('timestamp', $timestamp);
		$cache->set('count', $count);
	};
}

if (is_array($names)) {
	$allhooks = array_unique(array_merge($names, $timestamphooks));
} else {
	$allhooks = $timestamphooks;
}

foreach ($allhooks as $hook) {
	if(in_array($hook, $names)) {
		$hooks[$hook] = [deploy($hook), setTimestamp()];
	} else {
		$hooks[$hook] = [setTimestamp()];
	}
}

return $hooks;
