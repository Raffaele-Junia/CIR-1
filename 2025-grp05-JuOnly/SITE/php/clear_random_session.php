<?php
    session_start();
    unset($_SESSION['random_series_start_time']);
    echo json_encode(['status' => 'success', 'message' => 'Random series timer cleared.']);
?>