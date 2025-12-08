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
		$points["{$x},{$y},{$z}"] = ['x' => $x, 'y' => $y, 'z' => $z, 'circuit' => $circuit, 'distances' => []];
		$circuit++;
	}

	function dist($x1, $y1, $z1, $x2, $y2, $z2): int {
		return sqrt(pow($x1 - $x2, 2) + pow($y1 - $y2, 2) + pow($z1 - $z2, 2));
	}

	$distances = [];
	foreach ($points as $eId => $e) {
		$found = false;
		foreach ($points as $oeId => $o) {
			if ($eId == $oeId) { $found = true; continue; }
			if ($found == false) { continue; }

			$distance = dist($e['x'], $e['y'], $e['z'], $o['x'], $o['y'], $o['z']);
			$points[$eId]['distances'][$oeId] = $distance;
			$distances[] = ['distance' => $distance, 'a' => $eId, 'b' => $oeId];
		}
	}
	usort($distances, fn($a,$b) => $a['distance'] <=> $b['distance']);

	for ($i = 0; $i < (isTest() ? 10 : 1000); $i++) {
		if (!isset($distances[$i])) { continue; }

		$aCircuit = $points[$distances[$i]['a']]['circuit'];
		$bCircuit = $points[$distances[$i]['b']]['circuit'];

		if ($aCircuit == $bCircuit) { continue; }

		$circuits[$aCircuit] = array_merge($circuits[$aCircuit], $circuits[$bCircuit]);
		foreach ($circuits[$bCircuit] as $pId) {
			$points[$pId]['circuit'] = $aCircuit;
		}
		unset($circuits[$bCircuit]);
	}
	uasort($circuits, fn($a, $b) => count($b) <=> count($a));
	$keys = array_keys($circuits);

	$part1 = count($circuits[$keys[0]]) * count($circuits[$keys[1]]) * count($circuits[$keys[2]]);
	echo 'Part 1: ', $part1, "\n";

	// $part2 = 0;
	// echo 'Part 2: ', $part2, "\n";
