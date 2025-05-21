<?php 
/**
 * Сохраняет состояние для чата
 */
function setState(mysqli $conn, int $chat_id, string $state): void {
    $sql = "INSERT INTO user_states (chat_id, state) 
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE state = VALUES(state)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $chat_id, $state);
    $stmt->execute();
    $stmt->close();
}

/**
 * Возвращает текущее состояние чата или null
 */
function getState(mysqli $conn, int $chat_id): ?string {
    $sql = "SELECT state FROM user_states WHERE chat_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $chat_id);
    $stmt->execute();
    $stmt->bind_result($state);
    $result = $stmt->fetch() ? $state : null;
    $stmt->close();
    return $result;
}

/**
 * Сбрасывает состояние чата (удаляет или ставит NULL)
 */
function clearState(mysqli $conn, int $chat_id): void {
    $sql = "DELETE FROM user_states WHERE chat_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $chat_id);
    $stmt->execute();
    $stmt->close();
}




?>