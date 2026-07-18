<?php
if (basename($_SERVER['SCRIPT_FILENAME']) === 'gallery.php') {
    header("Location: ../main.php?frmid=4");
    exit;
}
include("include/top-nav.php");
?>
<style>
/* Lightbox Modal CSS */
.lightbox-modal {
    display: none;
    position: fixed;
    z-index: 100000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    overflow: hidden;
}

/* Container for the image slide */
.lightbox-content {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    position: relative;
    padding: 20px;
    box-sizing: border-box;
}

.lightbox-slide-container {
    max-width: 85%;
    max-height: 85%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.lightbox-slide {
    display: none;
    animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.lightbox-slide img {
    max-width: 100%;
    max-height: 80vh;
    border-radius: 8px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
    object-fit: contain;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.96); }
    to { opacity: 1; transform: scale(1); }
}

/* Close button - absolute top right corner */
.lightbox-close {
    position: absolute;
    top: 25px;
    right: 35px;
    color: #94a3b8;
    font-size: 40px;
    font-weight: 300;
    cursor: pointer;
    z-index: 100002;
    transition: color 0.2s, transform 0.2s;
    user-select: none;
}

.lightbox-close:hover {
    color: #f1f5f9;
    transform: scale(1.1);
}

/* Navigation arrows at the corners of the website */
.lightbox-prev, .lightbox-next {
    cursor: pointer;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 60px;
    height: 60px;
    color: #94a3b8;
    font-size: 36px;
    transition: color 0.2s, background-color 0.2s, transform 0.2s;
    border-radius: 50%;
    user-select: none;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.08);
    text-decoration: none;
    z-index: 100001;
}

.lightbox-prev {
    left: 40px;
}

.lightbox-next {
    right: 40px;
}

.lightbox-prev:hover, .lightbox-next:hover {
    color: #f1f5f9;
    background-color: rgba(123, 20, 22, 0.95);
    transform: translateY(-50%) scale(1.08);
}

/* Caption text at the bottom center */
.lightbox-caption {
    position: absolute;
    bottom: 30px;
    left: 0;
    width: 100%;
    text-align: center;
    color: #e2e8f0;
    font-size: 16px;
    font-weight: 500;
    font-family: 'Outfit', sans-serif;
    z-index: 100002;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
    pointer-events: none;
}
</style>
<?php

// Read Filter Variables
$filter_year = $_GET['year'] ?? '';

// Build Query
$sql_check = "SELECT * FROM albums WHERE status = 'ACTIVE'";
if (!empty($filter_year)) {
    $sql_check .= " AND album_year = '" . mysqli_real_escape_string($connect, $filter_year) . "'";
}
$sql_check .= " ORDER BY created_at DESC";
?>
<body class="interior-body">

<?php
    include("include/header.php");
    include("include/slider.php");
    ?>

    <main class="container interior-main-layout">
        <div class="page-title-block image-gallery-header-row">
            <div>
                <h2>Photo Gallery</h2>
                <p>Explore moments from our events, activities and celebrations.</p>
            </div>
            <div class="gallery-filter-toolbar">
                <select id="filter-year">
                    <option value="">All Years</option>
                    <?php
                    $yr_res = mysqli_query($connect, "SELECT DISTINCT album_year FROM albums WHERE status = 'ACTIVE' ORDER BY album_year DESC");
                    if ($yr_res) {
                        while($yr_row = mysqli_fetch_assoc($yr_res)) {
                            $sel = ($filter_year === $yr_row['album_year']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($yr_row['album_year']) . "' $sel>" . htmlspecialchars($yr_row['album_year']) . "</option>";
                        }
                    }
                    ?>
                </select>
                <button class="btn-gallery-filter" onclick="applyFilters()">Apply Filter</button>
            </div>
        </div>

        <div class="gallery-grid-scroll-box">
            <?php
            $s_check = mysqli_query($connect, $sql_check);
            $has_albums = false;
            if ($s_check && mysqli_num_rows($s_check) > 0) {
                while ($rows = mysqli_fetch_assoc($s_check)) {
                    $img = !empty($rows['cover_image']) ? $rows['cover_image'] : '';
                    if (empty($img)) {
                        continue;
                    }
                    $img_src = "assets/images/album/" . $img;
                    if (!file_exists($img_src)) {
                        continue;
                    }
                    $has_albums = true;
                    
                    // Format year/month
                    $date_str = date('F Y', strtotime($rows['created_at']));
                    ?>
                    <div class="album-media-card" style="cursor: pointer;" onclick="openGalleryModal(<?php echo $rows['album_id']; ?>, '<?php echo addslashes($rows['album_name']); ?>')">
                        <div class="album-preview-frame">
                            <img src="<?php echo htmlspecialchars($img_src); ?>" alt="<?php echo htmlspecialchars($rows['album_name']); ?>">
                        </div>
                        <div class="album-footer-meta">
                            <h5><?php echo htmlspecialchars($rows['album_name']); ?></h5>
                            <p><?php echo htmlspecialchars($date_str); ?></p>
                        </div>
                    </div>
                    <?php
                }
            }
            if (!$has_albums) {
                echo "<p style='text-align: center; grid-column: 1/-1; color: #64748b; padding: 40px 0;'>No photo albums found matching the selected filters.</p>";
            }
            ?>
        </div>

        <div class="gallery-action-footer-row">
            <button class="btn-load-more-photos" onclick="window.location.reload();">Refresh Gallery</button>
        </div>
    </main>

    <!-- LIGHTBOX MODAL HTML -->
    <div id="galleryModal" class="lightbox-modal">
        <span class="lightbox-close" onclick="closeGalleryModal()">&times;</span>
        <div class="lightbox-content">
            <div class="lightbox-slide-container" id="lightboxSlideContainer">
                <!-- Slides injected dynamically -->
            </div>
            <a class="lightbox-prev" onclick="changeSlide(-1)">&#10094;</a>
            <a class="lightbox-next" onclick="changeSlide(1)">&#10095;</a>
        </div>
        <div class="lightbox-caption" id="lightboxCaption"></div>
    </div>

    <?php
include("include/footer.php")
?>

<script>
function applyFilters() {
    var year = document.getElementById("filter-year").value;
    var url = "main.php?frmid=4";
    if (year !== "") {
        url += "&year=" + encodeURIComponent(year);
    }
    window.location.href = url;
}

let currentSlideIndex = 0;
let albumImages = [];

function openGalleryModal(albumId, albumName) {
    fetch('pages/get_album_images.php?album_id=' + albumId)
        .then(response => response.json())
        .then(data => {
            albumImages = data;
            currentSlideIndex = 0;
            
            if (albumImages.length === 0) {
                alert('No images found in this album.');
                return;
            }
            
            const container = document.getElementById('lightboxSlideContainer');
            container.innerHTML = '';
            
            albumImages.forEach((img, idx) => {
                const slide = document.createElement('div');
                slide.className = 'lightbox-slide';
                slide.style.display = idx === 0 ? 'block' : 'none';
                
                const imgElement = document.createElement('img');
                imgElement.src = img.src;
                imgElement.alt = img.caption;
                imgElement.onerror = function() {
                    this.onerror = null;
                    this.src = 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22800%22%20height%3D%22500%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000/svg%22%3E%3Crect%20width%3D%22100%25%22%20height%3D%22100%25%22%20fill%3D%22%23f1f5f9%22%2F%3E%3Ctext%20x%3D%2250%25%22%20y%3D%2250%25%22%20font-family%3D%22sans-serif%22%20font-size%3D%2218%22%20fill%3D%22%2394a3b8%22%20text-anchor%3D%22middle%22%20dominant-baseline%3D%22middle%22%3EImage%20Not%20Available%3C%2Ftext%3E%3C%2Fsvg%3E';
                };
                
                slide.appendChild(imgElement);
                container.appendChild(slide);
            });
            
            const prevBtn = document.querySelector('.lightbox-prev');
            const nextBtn = document.querySelector('.lightbox-next');
            if (albumImages.length > 1) {
                prevBtn.style.display = 'block';
                nextBtn.style.display = 'block';
            } else {
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
            }
            
            document.getElementById('lightboxCaption').textContent = albumImages[0].caption;
            document.getElementById('galleryModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        })
        .catch(err => {
            console.error('Error fetching album images:', err);
        });
}

function closeGalleryModal() {
    document.getElementById('galleryModal').style.display = 'none';
    document.body.style.overflow = '';
}

function changeSlide(n) {
    showSlide(currentSlideIndex + n);
}

function showSlide(index) {
    const slides = document.getElementsByClassName('lightbox-slide');
    if (slides.length === 0) return;
    
    slides[currentSlideIndex].style.display = 'none';
    
    currentSlideIndex = index;
    if (currentSlideIndex >= slides.length) {
        currentSlideIndex = 0;
    } else if (currentSlideIndex < 0) {
        currentSlideIndex = slides.length - 1;
    }
    
    slides[currentSlideIndex].style.display = 'block';
    document.getElementById('lightboxCaption').textContent = albumImages[currentSlideIndex].caption;
}

window.addEventListener('click', function(e) {
    const modal = document.getElementById('galleryModal');
    if (e.target === modal) {
        closeGalleryModal();
    }
});

window.addEventListener('keydown', function(e) {
    const modal = document.getElementById('galleryModal');
    if (modal && modal.style.display === 'block') {
        if (e.key === 'ArrowLeft') {
            changeSlide(-1);
        } else if (e.key === 'ArrowRight') {
            changeSlide(1);
        } else if (e.key === 'Escape') {
            closeGalleryModal();
        }
    }
});
</script>
</body>
</html>