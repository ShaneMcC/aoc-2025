#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLineGroups();
	$sizes = array_pop($input);

	$part1 = 0;

	foreach ($sizes as $size) {
		preg_match('#(.*)x(.*): (.*)#ADi', $size, $m);
		[$all, $w, $h, $wanted] = $m;
		$area = ($w * $h);

		$needed = array_sum(explode(' ', $wanted)) * 9;
		if ($needed > $area) { continue; }
		$part1++;
	}

	echo 'Part 1: ', $part1, "\n";
