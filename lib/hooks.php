<?php

$names = option('f-mahler.kirby-vercel.hooks');
$hooks = [];

function hook(string $name)
{
	return function ($input) use ($name) {
    \KirbyVercel\App::deploy();
	};
}

if (is_array($names)) {
	foreach ($names as $name) {
		$hooks[$name] = hook($name);
	}
}

return $hooks;
