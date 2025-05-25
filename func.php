<?php 
function getBook($conn, $title){
    $sql = "SELECT title, authors.name AS author, genres.name AS genre FROM books 
    JOIN authors ON books.author_id = authors.id
    JOIN genres ON books.genre_id = genres.id
    WHERE title LIKE ?";

    return queryExecute($conn, $sql, $title);
}


function getAuthor($conn, $name){
    $sql = "SELECT title, authors.name AS author, genres.name AS genre FROM books 
    JOIN authors ON books.author_id = authors.id
    JOIN genres ON books.genre_id = genres.id
    WHERE authors.name LIKE ?";

    return queryExecute($conn, $sql, $name);
}


function queryExecute($conn, $sql, $trToSearch){
        $search = "%{$trToSearch}%";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $search);
        $stmt->execute();
        $result = $stmt->get_result();


    if (mysqli_num_rows($result) > 0) {
        $lines = [];
            while ($row = mysqli_fetch_assoc($result)) {              
                $lines[] = sprintf("%s: Author - %s, Genre -%s", $row['title'], $row['author'], $row['genre'] );
            } 
            $stmt->close();
            return implode("\n", $lines);
    }else{
            $stmt->close();
            return 'No info';
    }   
}

function botMessage($api_url, $chat_id, $reply){
    file_get_contents(
        $api_url
    . "sendMessage?chat_id={$chat_id}"
    . "&text=" . urlencode($reply)
    );     
}

function insertVisitor(mysqli $conn, int $chat_id, string $username, string $command): bool{
    $sql = "INSERT INTO visits (chat_id, username, command) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
         error_log("Prepare failed: " . $conn->error);
         return false;
    }
    $stmt->bind_param('iss', $chat_id, $username, $command);
    if(!$stmt->execute()){
        error_log("Execute failed: " . $stmt->error);
        return false;
    };
    $stmt->close();
    return true;
}

function compareCoin(array $allCoins, array $selectedCoins): bool{
   return true;
}
?>