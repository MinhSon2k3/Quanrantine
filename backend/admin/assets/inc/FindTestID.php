<?php
// your_ajax_script.php

include('config.php');

if (isset($_GET['Patient_ID'])) {
    $patient_id = $_GET['Patient_ID'];

    // Thực hiện truy vấn để lấy dữ liệu từ bảng test dựa trên Patient_ID
    $query = "SELECT t.Test_ID FROM test t WHERE t.Patient_ID = ?";
    $stmt = $mysqli->prepare($query);

    if ($stmt === false) {
        die('Error in query preparation: ' . $mysqli->error);
    }

    $stmt->bind_param('i', $patient_id);
    $stmt->execute();

    $result = $stmt->get_result();

    // Build an array of Test IDs
    $testIds = array();
    while ($row = $result->fetch_assoc()) {
        $testIds[] = $row['Test_ID'];
    }

    // Return the Test IDs as a JSON-encoded response
    echo json_encode($testIds);
}
?>