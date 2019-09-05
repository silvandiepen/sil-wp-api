<?php
	
	function advanced_replace($searchStart, $searchEnd, $replace, $subject, &$assignValue = array(), $addValue = false, $inReplace = false, $valueKey = "") {
    $strlen = strlen( $subject );
    $open = 0;
    $ob = false;
    $ob_message = "";
    $message = "";
    for( $i = 0; $i <= $strlen; $i++ ) {
        $char = substr( $subject, $i, 1 );

        if ($char == $searchStart) {
            $open++;
            $ob = true;
        }
        if ($ob) {
            $ob_message .= $char;
        } else {
            $message .= $char;
        }
        if ($char == $searchEnd) {
            $open--;
            if ($open == 0) {
                $ob = false;
                $message .= ($replace.($addValue!== false && $inReplace?$addValue:""));
                $assignValue[$valueKey.($addValue!== false?$addValue:"")] = $ob_message;
                $ob_message = "";
                if ($addValue !== false) $addValue++;
            }
        }
    }
    return $message; 
}