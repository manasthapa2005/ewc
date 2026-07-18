<?php
include("../db.php");

if (!$connect) {
    echo "<p style='text-align: center; color: #dc2626;'>Database connection failed.</p>";
    exit;
}

$type = $_GET['typ'] ?? '';
$md = intval($_GET['md'] ?? 0);

if ($type === "add_album") {
    $aid = intval($_GET['aid'] ?? 0);
    $album_name = trim($_GET['album'] ?? '');
    $image_caption = trim($_GET['pname'] ?? '');
    $current_year = date('Y');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // Mode 0: Add/Insert Album
        if ($md === 0) {
            if (!empty($album_name)) {
                $query = "INSERT INTO albums (album_name, album_year, image_caption, status, album_type) VALUES (?, ?, ?, 'ACTIVE', 'Celebrations')";
                $stmt = mysqli_prepare($connect, $query);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sss", $album_name, $current_year, $image_caption);
                    if (mysqli_stmt_execute($stmt)) {
                        $aid = mysqli_insert_id($connect);
                    }
                    mysqli_stmt_close($stmt);
                }
                
                // Handle file upload
                if ($aid > 0 && isset($_FILES["main_file"]) && $_FILES["main_file"]["name"] !== "") {
                    $temp_name = $_FILES["main_file"]["name"];
                    $extension = strtolower(pathinfo($temp_name, PATHINFO_EXTENSION));
                    
                    // Validate extension
                    if (in_array($extension, ['gif', 'png', 'jpg', 'jpeg'])) {
                        $imgname = $aid . '.' . $extension;
                        $location = '../assets/images/album/';
                        
                        // Ensure directory exists
                        if (!is_dir($location)) {
                            mkdir($location, 0755, true);
                        }
                        
                        $target_path = $location . $imgname;
                        
                        // Delete old file if exists
                        if (file_exists($target_path)) {
                            @unlink($target_path);
                        }
                        
                        if (move_uploaded_file($_FILES["main_file"]["tmp_name"], $target_path)) {
                            // Update database record with filename
                            $update_query = "UPDATE albums SET cover_image = ? WHERE album_id = ?";
                            $up_stmt = mysqli_prepare($connect, $update_query);
                            if ($up_stmt) {
                                mysqli_stmt_bind_param($up_stmt, "si", $imgname, $aid);
                                mysqli_stmt_execute($up_stmt);
                                mysqli_stmt_close($up_stmt);
                            }
                        }
                    }
                }
                
                // Handle multiple files upload
                if ($aid > 0 && isset($_FILES["other_files"])) {
                    $other_files = $_FILES["other_files"];
                    $location_other = '../assets/images/album/other/';
                    if (!is_dir($location_other)) {
                        mkdir($location_other, 0755, true);
                    }
                    
                    for ($i = 0; $i < count($other_files['name']); $i++) {
                        if ($other_files['name'][$i] !== "") {
                            $temp_name = $other_files['name'][$i];
                            $extension = strtolower(pathinfo($temp_name, PATHINFO_EXTENSION));
                            
                            if (in_array($extension, ['gif', 'png', 'jpg', 'jpeg'])) {
                                $new_filename = $aid . '_' . $i . '_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
                                $target_path = $location_other . $new_filename;
                                
                                if (move_uploaded_file($other_files['tmp_name'][$i], $target_path)) {
                                    $img_query = "INSERT INTO album_images (album_id, image_path) VALUES (?, ?)";
                                    $img_stmt = mysqli_prepare($connect, $img_query);
                                    if ($img_stmt) {
                                        mysqli_stmt_bind_param($img_stmt, "is", $aid, $new_filename);
                                        mysqli_stmt_execute($img_stmt);
                                        mysqli_stmt_close($img_stmt);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } 
        // Mode 1: Delete Album
        elseif ($md === 1) {
            if ($aid > 0) {
                // First get the cover image filename to delete it from disk
                $select_query = "SELECT cover_image FROM albums WHERE album_id = ?";
                $sel_stmt = mysqli_prepare($connect, $select_query);
                if ($sel_stmt) {
                    mysqli_stmt_bind_param($sel_stmt, "i", $aid);
                    mysqli_stmt_execute($sel_stmt);
                    $result = mysqli_stmt_get_result($sel_stmt);
                    if ($row = mysqli_fetch_assoc($result)) {
                        $cover_image = $row['cover_image'];
                        if (!empty($cover_image) && $cover_image !== 'na.png') {
                            $file_path = '../assets/images/album/' . $cover_image;
                            if (file_exists($file_path)) {
                                @unlink($file_path);
                            }
                        }
                    }
                    mysqli_stmt_close($sel_stmt);
                }
                
                // Get additional images to delete them from disk
                $select_imgs_query = "SELECT image_path FROM album_images WHERE album_id = ?";
                $sel_imgs_stmt = mysqli_prepare($connect, $select_imgs_query);
                if ($sel_imgs_stmt) {
                    mysqli_stmt_bind_param($sel_imgs_stmt, "i", $aid);
                    mysqli_stmt_execute($sel_imgs_stmt);
                    $imgs_result = mysqli_stmt_get_result($sel_imgs_stmt);
                    while ($img_row = mysqli_fetch_assoc($imgs_result)) {
                        $img_path = $img_row['image_path'];
                        if (!empty($img_path)) {
                            $file_path = '../assets/images/album/other/' . $img_path;
                            if (file_exists($file_path)) {
                                @unlink($file_path);
                            }
                        }
                    }
                    mysqli_stmt_close($sel_imgs_stmt);
                }
                
                // Delete from database
                $delete_query = "DELETE FROM albums WHERE album_id = ?";
                $del_stmt = mysqli_prepare($connect, $delete_query);
                if ($del_stmt) {
                    mysqli_stmt_bind_param($del_stmt, "i", $aid);
                    mysqli_stmt_execute($del_stmt);
                    mysqli_stmt_close($del_stmt);
                }
            }
        }
    }
    
    // Query all albums to return the updated list
    $sql_check = "SELECT * FROM albums ORDER BY created_at DESC";
    $s_check = mysqli_query($connect, $sql_check);
    if ($s_check && mysqli_num_rows($s_check) > 0) {
        while ($rows = mysqli_fetch_assoc($s_check)) {
            $img = !empty($rows['cover_image']) ? $rows['cover_image'] : 'na.png';
            $img_src = "../assets/images/album/" . $img;
            ?>
            <div class="album-card">
                <div class="image-wrapper">
                    <img src="<?php echo htmlspecialchars($img_src); ?>" alt="<?php echo htmlspecialchars($rows['album_name']); ?>" onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22350%22%20height%3D%22200%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000/svg%22%3E%3Crect%20width%3D%22100%25%22%20height%3D%22100%25%22%20fill%3D%22%23f1f5f9%22%2F%3E%3Ctext%20x%3D%2250%25%22%20y%3D%2250%25%22%20font-family%3D%22sans-serif%22%20font-size%3D%2216%22%20fill%3D%22%2394a3b8%22%20text-anchor%3D%22middle%22%20dominant-baseline%3D%22middle%22%3ENo%20Image%20Available%3C%2Ftext%3E%3C%2Fsvg%3E';">
                </div>
                <div class="album-info">
                    <h4><?php echo htmlspecialchars($rows['album_name']); ?></h4>
                    <button type="button" class="btn-delete" onclick="delete_record('<?php echo $rows['album_id']; ?>')">Delete</button>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p style='text-align: center; grid-column: 1/-1; color: #64748b;'>No albums found.</p>";
    }
}
?>
