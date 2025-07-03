<?php
    include '../connection.php';

    $message = "";
    $current_image_path = "";

    if (isset($_GET['id'])){
        $id = $_GET['id'];

        $sql = "SELECT * FROM modules WHERE module_id = $id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $title = $row['title'];
            $description = $row['description'];
            $type = $row['type'];
            $category = $row['category'];
            $content = $row['content'];
            $image_path = $row['image_path'];
            $current_image_path = $row['image_path'];
        }
        else{
            echo "No module found";
            exit();
        }
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $type = mysqli_real_escape_string($conn, $_POST['type']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $content = mysqli_real_escape_string($conn, $_POST['content']);
        $id = $_POST['id'];

        // Handle image input (file upload or URL)
        $image_path = $current_image_path; // Keep current image by default
        
        // Check if user provided an image URL
        if(!empty($_POST['image_url']) && filter_var($_POST['image_url'], FILTER_VALIDATE_URL)) {
            $image_path = $_POST['image_url'];
            
            // Delete old uploaded file if it exists and is different
            if(!empty($current_image_path) && $current_image_path != $image_path && !filter_var($current_image_path, FILTER_VALIDATE_URL)) {
                $old_file_path = '../../' . $current_image_path;
                if(file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }
        }
        // Check if user uploaded a file
        elseif(isset($_FILES['module_image']) && $_FILES['module_image']['error'] == 0) {
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/avif', 'image/webp'];
            $file_type = $_FILES['module_image']['type'];
            $file_size = $_FILES['module_image']['size'];
            $file_name = $_FILES['module_image']['name'];
            
            // Validate file type
            if(!in_array($file_type, $allowed_types)) {
                $message = "<div class='alert alert-danger'>Invalid file type. Only JPG, PNG, GIF, AVIF, and WebP images are allowed.</div>";
            }
            // Validate file size (5MB limit)
            elseif($file_size > 5 * 1024 * 1024) {
                $message = "<div class='alert alert-danger'>File size too large. Maximum size is 5MB.</div>";
            }
            else {
                // Generate unique filename
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
                $new_filename = 'module_' . time() . '_' . rand(1000, 9999) . '.' . $file_extension;
                $upload_path = '../../html/moduleimages/' . $new_filename;
                
                // Upload file
                if(move_uploaded_file($_FILES['module_image']['tmp_name'], $upload_path)) {
                    $image_path = '../html/moduleimages/' . $new_filename;
                    
                    // Delete old image if it exists and is different
                    if(!empty($current_image_path) && $current_image_path != $image_path && !filter_var($current_image_path, FILTER_VALIDATE_URL)) {
                        $old_file_path = '../../' . $current_image_path;
                        if(file_exists($old_file_path)) {
                            unlink($old_file_path);
                        }
                    }
                } else {
                    $message = "<div class='alert alert-danger'>Failed to upload image. Please try again.</div>";
                }
            }
        }

        if(!empty($title) && !empty($description) && !empty($content) && !empty($type) && !empty($category) && empty($message)){
            $sql = "UPDATE modules SET title='$title', description='$description', content='$content', type='$type', category='$category', image_path='$image_path', updated_at=CURRENT_TIMESTAMP WHERE module_id='$id'";
            
            if($conn->query($sql) === TRUE){
                $message = "<div class='alert alert-success'>Module updated successfully!</div>";
                // Redirect after a short delay
                echo "<script>setTimeout(function(){ window.location.href='adminpage.php#module-management'; }, 1500);</script>";
            }
            else{
                $message = "<div class='alert alert-danger'>Update Failed: " . mysqli_error($conn) . "</div>";
            }
        }
        elseif(empty($message)){
            $message = "<div class='alert alert-warning'>Please fill all the required fields!</div>";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Module</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            overflow-x: hidden;
        }
        .d-flex {
            min-height: 100vh;
            justify-content: center;
            align-items: center;
            padding: 80px 0 20px 0;
        }
        .form-container {
            background: #343a40;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .image-preview {
            max-width: 200px;
            max-height: 150px;
            border-radius: 8px;
            border: 2px solid #6c757d;
        }
        .file-input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }
        .file-input-label {
            display: block;
            padding: 10px 15px;
            background: #6c757d;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: background 0.3s;
        }
        .file-input-label:hover {
            background: #5a6268;
        }
        .current-image-info {
            background: #495057;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .input-tabs {
            display: flex;
            margin-bottom: 15px;
            border-radius: 5px;
            overflow: hidden;
        }
        .input-tab {
            flex: 1;
            padding: 10px;
            background: #6c757d;
            color: white;
            text-align: center;
            cursor: pointer;
            transition: background 0.3s;
            border: none;
        }
        .input-tab.active {
            background: #28a745;
        }
        .input-tab:hover {
            background: #5a6268;
        }
        .input-tab.active:hover {
            background: #218838;
        }
        .input-content {
            display: none;
        }
        .input-content.active {
            display: block;
        }
        .url-example {
            background: #495057;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 0.9em;
        }
        .url-example code {
            color: #ffc107;
            background: #343a40;
            padding: 2px 4px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-5 fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="adminpage.php"><i class="bi bi-arrow-left"></i> Admin Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    
    <div class="d-flex">
        <div class="container p-5">
            <?php if (!empty($message)) echo $message; ?>
            
            <form action="editmodule.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" class="p-5 form-container">
                <h2 class="fs-3 mb-4 text-white"><i class="bi bi-pencil-square"></i> Edit Module Details</h2>

                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <div class="row">
                    <div class="col-md-8">
                        <label for="title" class="form-label fw-semibold fs-5 text-white">Module Title:</label>
                        <input type="text" class="form-control mb-3" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>

                        <label for="description" class="form-label fw-semibold fs-5 text-white">Description:</label>
                        <textarea class="form-control mb-3" id="description" name="description" rows="4" required><?php echo htmlspecialchars($description); ?></textarea>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="type" class="form-label fw-semibold fs-5 text-white">Type:</label>
                                <input class="form-control mb-3" id="type" name="type" value="<?php echo htmlspecialchars($type); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="category" class="form-label fw-semibold fs-5 text-white">Category:</label>
                                <input class="form-control mb-3" id="category" name="category" value="<?php echo htmlspecialchars($category); ?>" required>
                            </div>
                        </div>

                        <label for="content" class="form-label fw-semibold fs-5 text-white">Content Path/URL:</label>
                        <input type="text" class="form-control mb-3" id="content" name="content" value="<?php echo htmlspecialchars($content); ?>" placeholder="https://example.com/content or ../html/modulefiles/Module1.html" required>
                        <div class="url-example">
                            <i class="bi bi-info-circle"></i> <strong>Examples:</strong><br>
                            • Google Drive: <code>https://drive.google.com/file/d/YOUR_FILE_ID/view</code><br>
                            • Local file: <code>../html/modulefiles/Module1.html</code><br>
                            • External URL: <code>https://example.com/module-content</code>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold fs-5 text-white">Module Image:</label>
                        
                        <?php if(!empty($current_image_path)): ?>
                            <div class="current-image-info">
                                <p class="text-white mb-2"><strong>Current Image:</strong></p>
                                <?php if(filter_var($current_image_path, FILTER_VALIDATE_URL)): ?>
                                    <img src="<?php echo htmlspecialchars($current_image_path); ?>" alt="Current module image" class="image-preview mb-2" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <p class="text-muted small" style="display: none;">External URL: <?php echo htmlspecialchars($current_image_path); ?></p>
                                <?php else: ?>
                                    <img src="../../<?php echo htmlspecialchars($current_image_path); ?>" alt="Current module image" class="image-preview mb-2">
                                    <p class="text-muted small"><?php echo basename($current_image_path); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Image Input Tabs -->
                        <div class="input-tabs">
                            <button type="button" class="input-tab active" onclick="switchImageInput('upload')">Upload File</button>
                            <button type="button" class="input-tab" onclick="switchImageInput('url')">Use URL</button>
                        </div>
                        
                        <!-- Upload File Content -->
                        <div id="upload-content" class="input-content active">
                            <div class="file-input-wrapper">
                                <label for="module_image" class="file-input-label">
                                    <i class="bi bi-upload"></i> Choose Image File
                                </label>
                                <input type="file" id="module_image" name="module_image" accept="image/*">
                            </div>
                            
                            <div id="image-preview-container" class="mt-3" style="display: none;">
                                <p class="text-white mb-2"><strong>New Image Preview:</strong></p>
                                <img id="image-preview" src="" alt="Image preview" class="image-preview">
                                <p id="file-info" class="text-muted small"></p>
                            </div>
                            
                            <p class="text-muted small mt-2">
                                <i class="bi bi-info-circle"></i> 
                                Supported formats: JPG, PNG, GIF, AVIF, WebP<br>
                                Maximum size: 5MB
                            </p>
                        </div>
                        
                        <!-- URL Input Content -->
                        <div id="url-content" class="input-content">
                            <input type="url" class="form-control mb-3" id="image_url" name="image_url" placeholder="https://example.com/image.jpg">
                            
                            <div id="url-preview-container" class="mt-3" style="display: none;">
                                <p class="text-white mb-2"><strong>URL Image Preview:</strong></p>
                                <img id="url-preview" src="" alt="URL image preview" class="image-preview">
                                <p id="url-info" class="text-muted small"></p>
                            </div>
                            
                            <div class="url-example">
                                <i class="bi bi-info-circle"></i> <strong>Examples:</strong><br>
                                • Google Drive: <code>https://drive.google.com/uc?export=view&id=YOUR_FILE_ID</code><br>
                                • Imgur: <code>https://i.imgur.com/example.jpg</code><br>
                                • Any direct image URL
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success me-2">
                        <i class="bi bi-check-circle"></i> Update Module
                    </button>
                    <a href="adminpage.php#module-management" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Admin
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Switch between upload and URL input
        function switchImageInput(type) {
            // Update tab buttons
            document.querySelectorAll('.input-tab').forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');
            
            // Update content visibility
            document.querySelectorAll('.input-content').forEach(content => content.classList.remove('active'));
            if (type === 'upload') {
                document.getElementById('upload-content').classList.add('active');
            } else {
                document.getElementById('url-content').classList.add('active');
            }
        }
        
        // Image preview functionality for file upload
        document.getElementById('module_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewContainer = document.getElementById('image-preview-container');
            const preview = document.getElementById('image-preview');
            const fileInfo = document.getElementById('file-info');
            
            if (file) {
                // Show file info
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                fileInfo.textContent = `${file.name} (${fileSize} MB)`;
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        });
        
        // URL preview functionality
        document.getElementById('image_url').addEventListener('input', function(e) {
            const url = e.target.value;
            const previewContainer = document.getElementById('url-preview-container');
            const preview = document.getElementById('url-preview');
            const urlInfo = document.getElementById('url-info');
            
            if (url && isValidUrl(url)) {
                urlInfo.textContent = `URL: ${url}`;
                preview.src = url;
                previewContainer.style.display = 'block';
                
                // Handle image load error
                preview.onerror = function() {
                    urlInfo.textContent = `URL: ${url} (Image not accessible)`;
                    preview.style.display = 'none';
                };
                preview.onload = function() {
                    preview.style.display = 'block';
                };
            } else {
                previewContainer.style.display = 'none';
            }
        });
        
        // URL validation function
        function isValidUrl(string) {
            try {
                new URL(string);
                return true;
            } catch (_) {
                return false;
            }
        }
    </script>
</body>
</html>
