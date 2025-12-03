#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	function getJoltage($line, $batteries) {
		$len = strlen($line);

		$values = array_fill(1, $batteries, -1);

		for ($i = 0; $i < $len; $i++) {
			$val = $line[$i];
			for ($j = 1; $j <= $batteries; $j++) {
				if ($i < $len - ($batteries - $j)) {
					if ($val > $values[$j]) {
						$values[$j] = $val;

						for ($k = $j + 1; $k <= $batteries; $k++) {
							$values[$k] = -1;
						}

						continue 2;
					}
				}
			}
		}

		return (int)implode("", $values);
	}

	$part1 = 0;
	$part2 = 0;
	foreach ($input as $line) {
		$part1 += getJoltage($line, 2);
		$part2 += getJoltage($line, 12);
	}

	echo 'Part 1: ', $part1, "\n";
	echo 'Part 2: ', $part2, "\n";
