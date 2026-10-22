<?php

/**
 * config.php – shared functions and settings for the backend
 */

// Every response from the backend is JSON
header('Content-Type: application/json');

// Path to the data file (backend/data/students.json)
define('DATA_FILE', __DIR__ . '/data/students.json');

/**
 * Read all students from the JSON file
 */
function get_students()
{
    if (!file_exists(DATA_FILE)) {
        return [];                                  // no file yet -> empty list
    }
    $json     = file_get_contents(DATA_FILE);
    $students = json_decode($json, true);
    return is_array($students) ? $students : [];
}

/**
 * Save the full student list back into the JSON file
 */
function save_students($students)
{
    $json = json_encode($students, JSON_PRETTY_PRINT);
    return file_put_contents(DATA_FILE, $json);
}

/**
 * Calculate the grade from marks (0–100)
 */
function calculate_grade($marks)
{
    if ($marks >= 90) return 'A+';
    if ($marks >= 80) return 'A';
    if ($marks >= 70) return 'B';
    if ($marks >= 60) return 'C';
    if ($marks >= 50) return 'D';
    return 'F';
}

/**
 * Send a JSON response and stop the script
 */
function respond($data, $code = 200)
{
    http_response_code($code);
    echo json_encode($data);
    exit;
}
