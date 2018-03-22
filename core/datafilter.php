<?php
		include_once '/var/www/html/log.php';
		global $temperature, $humidity, $debugMode;
		$debugMode = true;

	function kalmanFilter($z=0, $u=0) {
		global $debugMode;
		$R = 0.01;
		$Q = 20;
		$A = 1.1;
		$B = 0;
		$C = 1;
		if ($debugMode==true) {
			logToFile("TEST1: ", $z, '>>>>>>>>');
		}

		$R = $R; 	// noise power desirable
		$Q = $Q;   // noise power estimated
		$B = $B;
		$cov = $cov;
		$x = $x; 	 // estimated signal without noise

		if ($x == null) {
			$x = (1 / $C) * $z;
     		$cov = (1 / $C) * $Q * (1 / $C);
		} else {
			if ($debugMode==true) {
				logToFile("TEST3: ", $z, $u);
			}
		    // Compute prediction
		    $predX = predict($u);
		    $predCov = uncertainty();
			if ($debugMode==true) {
				logToFile("TEST PREDICTIONS: ", $predX, $predCov);
			}

		    // Kalman gain
		    $K = $predCov * $C * (1 / (($C * $predCov * $C) + $Q));
			if ($debugMode==true) {
				logToFile("TEST GAIN: ", $K, '');
			}

		     // Correction
		     $x = $predX + $K * ($z - ($C * $predX));
		     $cov = $predCov - ($K * $C * $predCov);
			if ($debugMode==true) {
		     	logToFile("TEST CORRECTION: ", $x, $cov);
			}
		}
		logToFile("RETURN FILTERED: ", $x, '<<<<<<<<');
	    return $x;

		//predict next value
		function  predict($u) {
   			return ($A * $x) + ($B * $u);
		}

		//  Return uncertainty of filter
		function uncertainty() {
			return (($A * $cov) * $A) + $R;
		 }
	}
