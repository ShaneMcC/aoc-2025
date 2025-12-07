#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputMap();

	$beams = [];
	$start = findCells($input, 'S')[0];

	$beams[] = $start;

	$map = $input;
	$part1 = 0;
	while (!empty($beams)) {
		[$bX, $bY] = array_shift($beams);

		if (!isset($map[$bY][$bX])) { continue; }

		$map[$bY][$bX] = '|';

		$next = $map[$bY + 1][$bX] ?? '';
		if ($next == '.') {
			$beams[] = [$bX, $bY + 1];
		} else if ($next == '^') {
			$part1++;
			$beams[] = [$bX + 1, $bY + 1];
			$beams[] = [$bX - 1, $bY + 1];
			$map[$bY + 1][$bX] = 'x';
		}
	}

	echo 'Part 1: ', $part1, "\n";

	// $part2 = 0;
	// echo 'Part 2: ', $part2, "\n";
