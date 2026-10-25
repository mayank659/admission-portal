<?php
session_start();

function redirectWithMessage($message, $type)
{
    $_SESSION['backup_message'] = $message;
    $_SESSION['backup_message_type'] = $type;
    header('Location: index.php');
    exit;
}

function writeBackup($output, $contents)
{
    $length = strlen($contents);
    $written = 0;
    while ($written < $length) {
        $bytes = fwrite($output, substr($contents, $written));
        if ($bytes === false || $bytes === 0) {
            throw new RuntimeException('Could not write the backup file.');
        }
        $written += $bytes;
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$submittedToken = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
if (
    !isset($_SESSION['backup_csrf_token'])
    || !is_string($submittedToken)
    || !hash_equals($_SESSION['backup_csrf_token'], $submittedToken)
) {
    redirectWithMessage('Backup request was invalid. Please try again.', 'error');
}

require __DIR__ . DIRECTORY_SEPARATOR . 'connect.php';

$backupRoot = __DIR__ . DIRECTORY_SEPARATOR . 'database_backups';
$dateFolder = date('Y-m-d');
$backupDirectory = $backupRoot . DIRECTORY_SEPARATOR . $dateFolder;
$backupName = 'mmit_admission_' . date('Y-m-d_H-i-s') . '_' . bin2hex(random_bytes(3)) . '.sql';
$temporaryPath = null;
$output = null;

try {
    if (!is_dir($backupDirectory) && !mkdir($backupDirectory, 0750, true) && !is_dir($backupDirectory)) {
        throw new RuntimeException('Could not create the backup directory.');
    }

    $temporaryPath = $backupDirectory . DIRECTORY_SEPARATOR . '.' . $backupName . '.tmp';
    $output = fopen($temporaryPath, 'xb');
    if ($output === false) {
        throw new RuntimeException('Could not create the backup file.');
    }

    if (!mysqli_set_charset($connect, 'utf8mb4')) {
        throw new RuntimeException('Could not set the database connection character set.');
    }

    writeBackup($output, "-- Database backup for mmit_admission\n");
    writeBackup($output, "-- Created: " . date('c') . "\n\n");
    writeBackup($output, "CREATE DATABASE IF NOT EXISTS `mmit_admission`;\nUSE `mmit_admission`;\n");
    writeBackup($output, "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n\n");

    $tablesResult = mysqli_query($connect, 'SHOW FULL TABLES');
    if ($tablesResult === false) {
        throw new RuntimeException('Could not list database tables.');
    }

    $tables = array();
    $views = array();
    while ($table = mysqli_fetch_row($tablesResult)) {
        if ($table[1] === 'VIEW') {
            $views[] = $table[0];
        } else {
            $tables[] = $table[0];
        }
    }
    mysqli_free_result($tablesResult);

    foreach ($tables as $tableName) {
        $quotedTable = '`' . str_replace('`', '``', $tableName) . '`';
        $createResult = mysqli_query($connect, 'SHOW CREATE TABLE ' . $quotedTable);
        if ($createResult === false) {
            throw new RuntimeException('Could not read a table definition.');
        }
        $createRow = mysqli_fetch_row($createResult);
        mysqli_free_result($createResult);
        if (!isset($createRow[1])) {
            throw new RuntimeException('A table definition was empty.');
        }

        writeBackup($output, 'DROP TABLE IF EXISTS ' . $quotedTable . ";\n");
        writeBackup($output, $createRow[1] . ";\n\n");

        $dataResult = mysqli_query($connect, 'SELECT * FROM ' . $quotedTable, MYSQLI_USE_RESULT);
        if ($dataResult === false) {
            throw new RuntimeException('Could not read table data.');
        }
        $fields = mysqli_fetch_fields($dataResult);
        while ($row = mysqli_fetch_row($dataResult)) {
            $values = array();
            foreach ($row as $index => $value) {
                if ($value === null) {
                    $values[] = 'NULL';
                    continue;
                }

                $fieldType = $fields[$index]->type;
                $isBinary = ($fields[$index]->flags & MYSQLI_BINARY_FLAG) !== 0
                    || $fieldType === MYSQLI_TYPE_BIT
                    || $fieldType === MYSQLI_TYPE_GEOMETRY;
                if ($isBinary) {
                    $values[] = '0x' . bin2hex($value);
                } else {
                    $values[] = "'" . mysqli_real_escape_string($connect, $value) . "'";
                }
            }
            writeBackup($output, 'INSERT INTO ' . $quotedTable . ' VALUES (' . implode(', ', $values) . ");\n");
        }
        mysqli_free_result($dataResult);
        writeBackup($output, "\n");
    }

    foreach ($views as $viewName) {
        $quotedView = '`' . str_replace('`', '``', $viewName) . '`';
        $createResult = mysqli_query($connect, 'SHOW CREATE TABLE ' . $quotedView);
        if ($createResult === false) {
            throw new RuntimeException('Could not read a view definition.');
        }
        $createRow = mysqli_fetch_row($createResult);
        mysqli_free_result($createResult);
        if (!isset($createRow[1])) {
            throw new RuntimeException('A view definition was empty.');
        }

        writeBackup($output, 'DROP VIEW IF EXISTS ' . $quotedView . ";\n");
        writeBackup($output, $createRow[1] . ";\n\n");
    }

    writeBackup($output, "SET FOREIGN_KEY_CHECKS=1;\n");
    if (!fclose($output)) {
        $output = null;
        throw new RuntimeException('Could not finish writing the backup file.');
    }
    $output = null;

    $finalPath = $backupDirectory . DIRECTORY_SEPARATOR . $backupName;
    if (!rename($temporaryPath, $finalPath)) {
        throw new RuntimeException('Could not finalize the backup file.');
    }
    $temporaryPath = null;
} catch (Throwable $exception) {
    if (is_resource($output)) {
        fclose($output);
    }
    if ($temporaryPath !== null && is_file($temporaryPath)) {
        unlink($temporaryPath);
    }
    error_log('Database backup failed: ' . $exception->getMessage());
    redirectWithMessage('Backup failed. Check the server error log and try again.', 'error');
}

redirectWithMessage('Database backup created: ' . $dateFolder . DIRECTORY_SEPARATOR . $backupName, 'success');
