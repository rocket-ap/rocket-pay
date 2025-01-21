<?php

//configuration
$config = [
    'telegram_token'    => '', // telgram bot token
    'telegram_chat_id'  => '',  // telegram chat id
];

// Read POST data
$api_key = isset($_POST['api_key']) ? $_POST['api_key'] : '';
$message = isset($_POST['message']) ? $_POST['message'] : '';

// Validate API Key
if ($api_key !== "03dd0ebd-ef0d-4086-82ab-48f3c98fc6f7") {
    echo json_encode(['status' => 'error', 'message' => 'Invalid API key']);
    exit;
}


if (empty($message)) {
    echo json_encode(['status' => 'error', 'message' => 'Message cannot be empty']);
    exit;
}

// Function to send message to Telegram
function sendTelegramMessage($message, $token, $chat_id)
{
    $url = "https://api.telegram.org/bot$token/sendMessage";
    $data = [
        'chat_id'   => $chat_id,
        'text'      => $message
    ];

    // cURL setup
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    // Check for cURL error
    if (curl_errno($ch)) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send message to Telegram']);
        curl_close($ch);
        exit;
    }

    curl_close($ch);

    return $response;
}

// Send the message to Telegram
$response = sendTelegramMessage($message, $config['telegram_token'], $config['telegram_chat_id']);

echo json_encode(['status' => 'success', 'message' => 'Message sent to Telegram successfully']);
