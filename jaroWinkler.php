<?php
function jaro( $string1, $string2)
{
	$string1_len = strlen($string1);
	$string2_len = strlen($string2);
	
	$distance = (int) floor ((max($string1_len,$string2_len))/2)-1;
	$commons1 = commonCharacters( $string1, $string2, $distance );
	$commons2 = commonCharacters( $string2, $string1, $distance );
	
	if( ($commons1_len = strlen( $commons1 )) == 0) return 0;
	if( ($commons2_len = strlen( $commons2 )) == 0) return 0;

	// calculate transpositions
	$transpositions = 0;
	$upperBound = min( $commons1_len, $commons2_len );

	for( $i = 0; $i < $upperBound; $i++)
	{
		if( $commons1[$i] != $commons2[$i] ) 
		$transpositions++;
	}
	$transpositions /= 2.0;


	// return the Jaro distance
	return (($upperBound/($string1_len) + $upperBound/($string2_len) + ($upperBound - $transpositions)/($commons1_len)) / 3.0);
}

function commonCharacters( $string1, $string2, $distance )
{
	$string1_len = strlen($string1);
	$string2_len = strlen($string2);
	$commonCharacters='';
	$matching=0;
  
	for($i=0;$i<$string1_len;$i++)
	{
		$noMatch = True;
		for( $j= 0; $noMatch && $j < $string2_len ; $j++)
		{
			if(($string2[$j]==$string1[$i]) && (abs($j-$i)<=$distance))
			{
				$noMatch = False;
				$matching++;
				$commonCharacters .= $string1[$i];
			}
		}
	}
	return $commonCharacters;
}

function prefixLength( $string1, $string2, $MINPREFIXLENGTH = 4 )
{
	$n = min( array( $MINPREFIXLENGTH, strlen($string1), strlen($string2) ) );
    for($i = 0; $i < $n; $i++)
	{
		if( $string1[$i] != $string2[$i] )
		{
			return $i;
		}
	}
	return $n;
}

function jaroWinkler($string1, $string2, $PREFIXSCALE = 0.1, $threshold)
{
	$string1 = strtolower($string1);
	$string2 = strtolower($string2);
	$jaroDistance = jaro( $string1, $string2 );
	$prefixLength = prefixLength( $string1, $string2 );
	$score = round(($jaroDistance + ($prefixLength * $PREFIXSCALE * (1.0 - $jaroDistance)))*100,2);
	if ($score >= $threshold)
		echo $score." => High similarity level";
	else
		echo $score." => Low similarity level";
}

$string1 = $_POST['string1'];
$string2 = $_POST['string2'];
$threshold = $_POST['threshold'];
jaroWinkler($string1, $string2, 0.1, $threshold);

?>
