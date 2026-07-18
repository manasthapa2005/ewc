<?php
include("../db.php");
include("head.php");
?>
<body class="interior-body">
<?php
include("menu.php");
?>
<script>
function create_record(aid)
{
    var album = document.getElementById("album-name").value;
    var pname = document.getElementById("image-caption").value;
    
    if (album.trim() === "") {
        alert("Please give album name");
        return false;
    }
    
    if (pname.trim() === "") {
        alert("Please give image caption");
        return false;
    }
    
    var property = document.getElementById("cover-image").files[0];
    if (!property) {
        alert("Please select a cover image");
        return false;
    }
    
    var image_name = property.name;
    var image_extension = image_name.split('.').pop().toLowerCase();
    if (jQuery.inArray(image_extension, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
        alert("Invalid File Format. Please choose a GIF, PNG, JPG or JPEG image.");
        document.getElementById("cover-image").value = null;
        return false;
    }
    
    var image_size = property.size;
    if (image_size > 2000000) {
        alert("Image File size too big (Max 2MB).");
        return false;
    }
    
    var form_data = new FormData();
    form_data.append("main_file", property);
    
    var other_files = document.getElementById("other-images").files;
    for (var i = 0; i < other_files.length; i++) {
        form_data.append("other_files[]", other_files[i]);
    }
    
    $.ajax({
        method: "POST",
        url: "data_set_album.php?typ=add_album&md=0&album=" + encodeURIComponent(album) + "&pname=" + encodeURIComponent(pname) + "&aid=" + aid,
        enctype: 'multipart/form-data',
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
            $('#item_addi').html("<label class='text-success'>Please Wait...</label>");
        },
        success: function(text) {
            console.log('Updated', text);
            $('#item_addi').html(text);
            
            // Reset form fields
            document.getElementById("myform").reset();
            document.getElementById('file_name').style.display = 'block';
            document.getElementById('file_img').style.display = 'none';
        }
    });
}

function delete_record(aid)
{
    if (confirm("Are you sure you want to delete this album?")) {
        $.ajax({
            method: "POST",
            url: "data_set_album.php?typ=add_album&md=1&aid=" + aid,
            beforeSend: function() {
                $('#item_addi').html("<label class='text-success'>Please Wait...</label>");
            },
            success: function(text) {
                console.log('Deleted', text);
                $('#item_addi').html(text);
            }
        });
    }
}
</script>
    <div class="gallery-page-wrapper">

        <!-- COMPACT ADD ALBUM FORM -->
        <div class="gallery-form-container">
            <h2>Update Photo Gallery</h2>
            <div class="form-card">
                <h3>Add New Album</h3>
                <form action="" name="myform" id="myform" method="POST" enctype="multipart/form-data">
                    <div class="form-row" style="flex-wrap: wrap; gap: 15px;">
                        <div class="form-group flex-2" style="min-width: 200px;">
                            <label for="album-name">*Album Name:</label>
                            <input type="text" id="album-name" name="album_name" placeholder="e.g., Athletic Meet 2026"
                                required>
                        </div>

                        <div class="form-group flex-2" id="file_name" style="display:block; min-width: 200px;">
                            <label for="cover-image">*Select Cover Image:</label>
                            <input type="file" id="cover-image" name="cover_image" onchange="readURL(this);">
                        </div>
						<div class="form-group flex-2" id="file_img" style="display:none; min-width: 200px;">
                             <img id="img_prev" src="../assets/images/album/na.png" alt="your image" width="50px" height="50px" />
                        </div>
                        <div class="form-group flex-2" style="min-width: 200px;">
                            <label for="other-images">Select Additional Images:</label>
                            <input type="file" id="other-images" name="other_images[]" multiple accept="image/*">
                        </div>
                        <div class="form-group flex-2" style="min-width: 200px;">
                            <label for="image-caption">Image Caption:</label>
                            <input type="text" id="image-caption" name="image_caption"
                                placeholder="Brief description...">
                        </div>

                        <div class="form-group btn-group flex-1" style="min-width: 120px; align-self: flex-end;">
                            <button type="button" class="btn-create" onclick="create_record('0')">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- SCROLLABLE ALBUM CONTAINER -->
        <div class="album-section-container">
            <h3>Existing Albums</h3>
            <div class="album-scroll-box">
                <div class="album-grid" id="item_addi">
                    <?php
                    if ($connect) {
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
                    } else {
                        echo "<p style='text-align: center; grid-column: 1/-1; color: #dc2626;'>Database connection error.</p>";
                    }
                    ?>

                </div>
            </div>
        </div>

    </div>


    <footer class="main-footer">
        <div class="container footer-content">
            <p>&copy; 2026 ONGC EWC-DEHRADUN - Employee Welfare Committee. All Rights Reserved.</p>
        </div>
    </footer>
	<script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
</body>

<script>

function readURL(input) {
document.getElementById('file_name').style.display='none';
document.getElementById('file_img').style.display='block';
	if (input.files && input.files[0]) {
	  var reader = new FileReader();

	  reader.onload = function (e) {
		$('#img_prev')
		  .attr('src', e.target.result)
		  .width(250)
		  .height(200);
	  };

	  reader.readAsDataURL(input.files[0]);
	}
}

   
</script>







</html>