<?php
function formatDuration($seconds) {
    if ($seconds < 60) {
        return $seconds . ' s';
    }
    $minutes = floor($seconds / 60);
    $remainingSeconds = $seconds % 60;
    if ($minutes < 60) {
        return $minutes . ' min ' . $remainingSeconds . ' s';
    }
    $hours = floor($minutes / 60);
    $remainingMinutes = $minutes % 60;
    return $hours . ' h ' . $remainingMinutes . ' min ' . $remainingSeconds . ' s';
}


?>