<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qiosk_web/logics/chat_dbconn.php');

// sentiment analysis library
require_once __DIR__ . '/autoload.php';

$dbconn = paliolite();
$product_code = $_GET['product_code']; // target post

// get comments
$query = $dbconn->prepare("SELECT * FROM PRODUCT_COMMENT WHERE PRODUCT_CODE = '$product_code'");
$query->execute();
$comments  = $query->get_result();
$query->close();

// get total like
$query = $dbconn->prepare("SELECT * FROM PRODUCT WHERE CODE = '$product_code'");
$query->execute();
$product  = $query->get_result()->fetch_assoc();
$total_likes = $product['TOTAL_LIKES'];
$query->close();

function doAnalysis($comments){

    $sentiment = new \PHPInsight\Sentiment();
    
    $i = 1;
    $comment_score = 0;
    foreach ($comments as $comment) {
        
        // calculations:
        $scores = $sentiment->score($comment['COMMENT']);
        
        // classification
        $class = array_keys($scores, max($scores))[0];

        if($class == 'positif'){
            $comment_score++;
        } else if($class == 'negatif'){
            $comment_score--;
        }

        $i++;
    }
    

    return $comment_score;
}

$total_score = doAnalysis($comments) + $total_likes;

$query = $dbconn->prepare("UPDATE PRODUCT SET SCORE = '$total_score' WHERE CODE = '$product_code'");
$status = $query->execute();
$query->close();

if($status){
    http_response_code(200);
} else {
    http_response_code(500);
}