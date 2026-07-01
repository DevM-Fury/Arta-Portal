<?php

require_once __DIR__ . '/../includes/configs.php';

$inputFile = $argv[1] ?? (__DIR__ . '/designations.txt');

if (!is_file($inputFile) || !is_readable($inputFile)) {
    fwrite(STDERR, "Input file not found or not readable: {$inputFile}\n");
    exit(1);
}

$lines = file($inputFile, FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    fwrite(STDERR, "Unable to read input file: {$inputFile}\n");
    exit(1);
}

$names = [];
foreach ($lines as $line) {
    $name = trim($line);

    if ($name === '' || strpos($name, '#') === 0) {
        continue;
    }

    $key = function_exists('mb_strtolower')
        ? mb_strtolower($name, 'UTF-8')
        : strtolower($name);
    $names[$key] = $name;
}

if (count($names) === 0) {
    fwrite(STDERR, "No designation names found in {$inputFile}\n");
    exit(1);
}

$exists = $conn->prepare("
    SELECT id
    FROM public.designations
    WHERE LOWER(designation_name) = LOWER(:name)
    LIMIT 1
");

$insert = $conn->prepare("
    INSERT INTO public.designations (
        designation_name,
        designation_description,
        designation_status,
        created_at,
        updated_at
    )
    VALUES (
        :name,
        :description,
        :status,
        NOW(),
        NOW()
    )
");

$inserted = 0;
$skipped = 0;

try {
    $conn->beginTransaction();

    foreach ($names as $name) {
        $exists->execute([':name' => $name]);

        if ($exists->fetchColumn()) {
            $skipped++;
            continue;
        }

        $insert->execute([
            ':name' => $name,
            ':description' => '',
            ':status' => 'Active',
        ]);

        $inserted++;
    }

    $conn->commit();
} catch (Throwable $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    fwrite(STDERR, "Seed failed: " . $e->getMessage() . "\n");
    exit(1);
}

echo "Designations seed complete. Inserted: {$inserted}. Skipped existing: {$skipped}.\n";
