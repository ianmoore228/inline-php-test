<?php
require "../config/conn_db.php";

$posts = json_decode(file_get_contents('https://jsonplaceholder.typicode.com/posts'));

$postStmt = $conn->prepare("INSERT INTO posts (id, user_id, title, body) VALUES (:id, :user_id, :title, :body)");
$commentsStmt = $conn->prepare("INSERT INTO comments (id, post_id, name, email, body) VALUES (:id, :post_id, :name, :email, :body)");

$postCount = 0;
$commentCount = 0;

foreach ($posts as $post) {
    $postStmt->execute([
        ':id' => $post->id,
        ':user_id' => $post->userId,
        ':title' => $post->title,
        ':body' => $post->body,
    ]);
    $postCount++;

    $comments = json_decode(file_get_contents("https://jsonplaceholder.typicode.com/posts/{$post->id}/comments"));

    foreach ($comments as $comment) {
        $commentsStmt->execute([
            ':id' => $comment->id,
            ':post_id' => $comment->postId,
            ':name' => $comment->name,
            ':email' => $comment->email,
            ':body' => $comment->body,
        ]);
        $commentCount++;
    }
}

echo "Загружено $postCount записей и $commentCount комментариев.";