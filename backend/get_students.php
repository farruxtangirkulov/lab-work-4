<?php

/**
 * get_students.php – reads data/students.json and sends the
 * student list back to table.html as JSON
 */

require_once 'config.php';

$students = get_students();

respond([
    'ok'       => true,
    'count'    => count($students),
    'students' => $students
]);
