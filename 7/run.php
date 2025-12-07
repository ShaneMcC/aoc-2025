#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputMap();

	$beams = [];
	$start = findCells($input, 'S')[0];

	$beams[] = [$start[0], $start[1], 1];

	$map = $input;
	$part1 = 0;
	$values = [];
	$values[$start[0]] = 1;

	while (!empty($beams)) {
		[$bX, $bY] = array_shift($beams);
		$next = $map[$bY + 1][$bX] ?? '';

		if ($next == '.') {
			$beams[] = [$bX, $bY + 1];
		} else if ($next == '^') {
			$part1++;
			$options = [];
			$options[] = [$bX + 1, $bY + 1];
			$options[] = [$bX - 1, $bY + 1];

			foreach ($options as [$oX, $oY]) {
				if (($map[$oY][$oX] ?? '') != '.') { continue; }
				$beams[] = [$oX, $oY];

				$values[$oX] = ($values[$oX] ?? 0) + $values[$bX];
			}
			$values[$bX] = 0;
			$map[$bY + 1][$bX] = 'x';
		}
	}

	echo 'Part 1: ', $part1, "\n";
	echo 'Part 2: ', array_sum($values), "\n";
