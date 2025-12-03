#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	function getJoltage($line) {
		$len = strlen($line);
		$first = -1;
		$second = -1;
		for ($i = 0; $i < $len; $i++) {
			if ($i < ($len - 1)) {
				if ($line[$i] > $first) {
					$first = $line[$i];
					$second = $line[$i + 1];
					continue;
				}
			}

			if ($line[$i] > $second) {
				$second = $line[$i];
			}
		}

		return ($first * 10) + $second;
	}

	$part1 = 0;
	foreach ($input as $line) {
		$part1 += getJoltage($line);
	}

	echo 'Part 1: ', $part1, "\n";

	// $part2 = 0;
	// echo 'Part 2: ', $part2, "\n";
