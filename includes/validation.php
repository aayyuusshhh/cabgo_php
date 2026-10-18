<?php

function normalize_phone_number(string $phone): string
{
    return preg_replace('/\D+/', '', trim($phone));
}

function is_valid_phone_number(string $phone): bool
{
    $normalized = normalize_phone_number($phone);

    return preg_match('/^\d{10}$/', $normalized) === 1;
}

function is_valid_email_address(string $email): bool
{
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false;
}

function email_exists(mysqli $conn, string $email, ?int $excludeUserId = null): bool
{
    $query = "SELECT id FROM users WHERE email = ?";

    if ($excludeUserId !== null) {
        $query .= " AND id != ?";
    }

    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        return true;
    }

    if ($excludeUserId !== null) {
        $stmt->bind_param("si", $email, $excludeUserId);
    } else {
        $stmt->bind_param("s", $email);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
}
?>
