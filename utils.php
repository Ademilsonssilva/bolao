<?php
	
	function sortArrayByPlayerPoints(&$array)
	{
		$sortedArray = [];
		for($i = 0; $i < sizeof($array); $i++) {

			if ($i < sizeof($array)-1) {

				for ($j = $i+1; $j < sizeof($array); $j++) {

					if ( $array[$i]["points"] < $array[$j]["points"] ) {

						$aux = $array[$i];
						$array[$i] = $array[$j];
						$array[$j] = $aux;

					}

				}

			}

		}

		return $array;
	}