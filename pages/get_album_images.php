<?php
header('Content-Type: application/json');
include("../db.php");

$album_id = intval($_GET['album_id'] ?? 0);

if ($album_id <= 0 || !$connect) {
    echo json_encode([]);
    exit;
}

$images = [];

// 1. Get the cover image
$query = "SELECT cover_image, album_name FROM albums WHERE album_id = ?";
$stmt = mysqli_prepare($connect, $query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $album_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $cover = $row['cover_image'];
        $album_name = $row['album_name'];
        if (!empty($cover) && $cover !== 'na.png') {
            $images[] = [
                'src' => "assets/images/album/" . $cover,
                'caption' => $album_name
            ];
        } else {
            // Default placeholder if cover image doesn't exist
            $images[] = [
                'src' => "data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22350%22%20height%3D%22200%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000/svg%22%3E%3Crect%20width%3D%22100%25%22%20height%3D%22100%25%22%20fill%3D%22%23f1f5f9%22%2F%3E%3Ctext%20x%3D%2250%25%22%20y%3D%2250%25%22%20font-family%3D%22sans-serif%22%20font-size%3D%2216%22%20fill%3D%22%2394a3b8%22%20text-anchor%3D%22middle%22%20dominant-baseline%3D%22middle%22%3ENo%20Image%20Available%3C%2Ftext%3E%3C%2Fsvg%3E",
                'caption' => $album_name
            ];
        }
    }
    mysqli_stmt_close($stmt);
}

// 2. Get additional images
$query_other = "SELECT image_path FROM album_images WHERE album_id = ? ORDER BY id ASC";
$stmt_other = mysqli_prepare($connect, $query_other);
if ($stmt_other) {
    mysqli_stmt_bind_param($stmt_other, "i", $album_id);
    mysqli_stmt_execute($stmt_other);
    $result_other = mysqli_stmt_get_result($stmt_other);
    while ($row_other = mysqli_fetch_assoc($result_other)) {
        $path = $row_other['image_path'];
        if (!empty($path)) {
            $images[] = [
                'src' => "assets/images/album/other/" . $path,
                'caption' => ($album_name ?? '') . ' - Image'
            ];
        }
    }
    mysqli_stmt_close($stmt_other);
}

echo json_encode($images);
exit;
