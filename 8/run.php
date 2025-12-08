#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$points = [];
	$circuits = [];
	$circuit = 0;
	foreach ($input as $line) {
		preg_match('#(.*),(.*),(.*)#ADi', $line, $m);
		[$all, $x, $y, $z] = $m;
		$circuits[$circuit][] = "{$x},{$y},{$z}";
		$points["{$x},{$y},{$z}"] = ['x' => $x, 'y' => $y, 'z' => $z, 'circuit' => $circuit];
		$circuit++;
	}

	function dist($x1, $y1, $z1, $x2, $y2, $z2): int {
		return pow($x1 - $x2, 2) + pow($y1 - $y2, 2) + pow($z1 - $z2, 2);
	}

	$keys = array_keys($points);
	for ($i = 0; $i < count($keys); $i++) {
		for ($j = $i + 1; $j < count($keys); $j++) {
			$entryId = $keys[$i];
			$otherId = $keys[$j];
			$entry = $points[$entryId];
			$other = $points[$otherId];

			$distance = dist($entry['x'], $entry['y'], $entry['z'], $other['x'], $other['y'], $other['z']);
			$distances[] = ['distance' => $distance, 'a' => $entryId, 'b' => $otherId];
		}
	}
	usort($distances, fn($a,$b) => $a['distance'] <=> $b['distance']);

	for ($i = 0; count($circuits) > 1; $i++) {
		if ($i == (isTest() ? 10 : 1000)) {
			uasort($circuits, fn($a, $b) => count($b) <=> count($a));
			$keys = array_keys($circuits);

			$part1 = count($circuits[$keys[0]]) * count($circuits[$keys[1]]) * count($circuits[$keys[2]]);
			echo 'Part 1: ', $part1, "\n";
		}

		$aCircuit = $points[$distances[$i]['a']]['circuit'];
		$bCircuit = $points[$distances[$i]['b']]['circuit'];

		if ($aCircuit == $bCircuit) { continue; }

		$circuits[$aCircuit] = array_merge($circuits[$aCircuit], $circuits[$bCircuit]);
		foreach ($circuits[$bCircuit] as $pId) {
			$points[$pId]['circuit'] = $aCircuit;
		}
		unset($circuits[$bCircuit]);

		if (count($circuits) == 1) {
			$aX = $points[$distances[$i]['a']]['x'];
			$bX = $points[$distances[$i]['b']]['x'];

			$part2 = $aX * $bX;
			echo 'Part 2: ', $part2, "\n";
		}
	}
