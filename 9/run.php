#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$tiles = [];
	$lines = [];
	$prev = explode(',', $input[count($input) - 1], 2);
	foreach ($input as $line) {
		$tiles[] = [$x, $y] = explode(',', $line, 2);
		[$pX, $pY] = $prev;

		if ($pX == $x) {
			$lines[] = ['direction' => 'vertical', 'x' => $x, 'y1' => min($y, $pY), 'y2' => max($y, $pY)];
		} else if ($pY == $y) {
			$lines[] = ['direction' => 'horizontal', 'y' => $y, 'x1' => min($x, $pX), 'x2' => max($x, $pX)];
		} else {
			die('Bad Input' . "\n");
		}

		$prev = [$x, $y];
	}

	function area($x1, $y1, $x2, $y2) {
		return (abs($x1 - $x2) + 1) * (abs($y1 - $y2) + 1);
	}

	function isValidArea($x1, $y1, $x2, $y2) {
		global $lines;

		$minX = min($x1, $x2);
		$maxX = max($x1, $x2);
		$minY = min($y1, $y2);
		$maxY = max($y1, $y2);

		foreach ($lines as $line) {
			if ($line['direction'] == 'vertical') {
				if ($line['x'] > $minX && $line['x'] < $maxX && $line['y1'] < $maxY && $line['y2'] > $minY) {
					return false;
				}
			} else if ($line['direction'] == 'horizontal') {
				if ($line['y'] > $minY && $line['y'] < $maxY && $line['x1'] < $maxX && $line['x2'] > $minX) {
					return false;
				}
			}
		}

        return true;
	}

	$part1 = PHP_INT_MIN;
	$part2 = PHP_INT_MIN;

	for ($i = 0; $i < count($tiles); $i++) {
		for ($j = $i + 1; $j < count($tiles); $j++) {
			[$iX, $iY] = $tiles[$i];
			[$jX, $jY] = $tiles[$j];

			$area = area($iX, $iY,$jX, $jY);
			$part1 = max($part1, $area);

			if ($area > $part2 && isValidArea($iX, $iY,$jX, $jY)) {
				$part2 = max($part2, $area);
			}
		}
	}

	echo 'Part 1: ', $part1, "\n";
	echo 'Part 2: ', $part2, "\n";
