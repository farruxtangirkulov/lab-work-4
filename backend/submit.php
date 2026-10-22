<?php

/**
 * submit.php – receives the form data (POST), validates it,
 * and saves it into data/students.json
 */

require_once 'config.php';

// Only accept POST requests (same as the form's method)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(['ok' => false, 'message' => 'Only POST requests are allowed.'], 405);
}

// 1. Read the values sent by the form / JavaScript
$name       = trim($_POST['name']       ?? '');
$enrollment = trim($_POST['enrollment'] ?? '');
$email      = trim($_POST['email']      ?? '');
$course     = trim($_POST['course']     ?? '');
$gender     = trim($_POST['gender']     ?? '');
$marks      = trim($_POST['marks']      ?? '');

// 2. Server-side validation
$errors = [];

if ($name === '')       $errors[] = 'Name is required.';
if ($enrollment === '') $errors[] = 'Enrollment number is required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
if (!in_array($course, ['B.Tech CSE', 'BCA', 'B.Sc IT'])) $errors[] = 'Please choose a valid course.';
if (!in_array($gender, ['Male', 'Female', 'Other']))      $errors[] = 'Please choose a valid gender.';
if (!is_numeric($marks) || $marks < 0 || $marks > 100)    $errors[] = 'Marks must be a number between 0 and 100.';

if ($errors) {
    respond(['ok' => false, 'message' => implode(' ', $errors)], 400);
}

// 3. Build the new student record
$students = get_students();

$student = [
    'id'         => count($students) > 0 ? end($students)['id'] + 1 : 1,
    'name'       => htmlspecialchars($name),
    'enrollment' => htmlspecialchars($enrollment),
    'email'      => htmlspecialchars($email),
    'course'     => $course,
    'gender'     => $gender,
    'marks'      => (int) $marks,
    'grade'      => calculate_grade((int) $marks),
    'created_at' => date('Y-m-d H:i:s'),
];

$students[] = $student;

// 4. Save the updated list
if (save_students($students) === false) {
    respond(['ok' => false, 'message' => 'Could not write to the data file.'], 500);
}

// 5. Success response (read by app.js and shown under the form)
respond(['ok' => true, 'message' => 'Student saved successfully!', 'student' => $student], 201);
