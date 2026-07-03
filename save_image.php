<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['image'])) {
    $image_parts = explode(";base64,", $data['image']);
    if (count($image_parts) == 2) {
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        
        $image_base64 = base64_decode($image_parts[1]);
        
        $dir = 'visitor_history';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        
        $file_name = 'visitor_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.jpg';
        $file_path = $dir . '/' . $file_name;
        
        if (file_put_contents($file_path, $image_base64)) {
            echo json_encode(['success' => true, 'file' => $file_name]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to save image']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid image format']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No image data received']);
}
?>
