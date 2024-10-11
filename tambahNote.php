<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Catatan</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dist/output.css">

    <style>
        .input-field {
            transition: border-color 0.3s;
        }

        .color-option {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 10px;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .color-option.selected {
            border: 2px solid black;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container my-5">
        <h2 class="text-center mb-4">Tambah Catatan</h2>

        <?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "koneksi.php";

// Proses penambahan kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newCategory']) && isset($_POST['categoryColor'])) {
    $newCategory = $conn->real_escape_string($_POST['newCategory']);
    $categoryColor = $conn->real_escape_string($_POST['categoryColor']);
    $conn->query("INSERT INTO Labels (nama_label, bg_color) VALUES ('$newCategory', '$categoryColor')");
    echo "<div class='alert alert-success'>Kategori berhasil ditambahkan!</div>";
    // Redirect untuk mencegah pengiriman ulang data
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Proses penambahan catatan
if (isset($_GET['title']) && isset($_GET['content'])) {
    $title = $conn->real_escape_string($_GET['title']);
    $content = $conn->real_escape_string($_GET['content']);
    $category = isset($_GET['category']) && $_GET['category'] !== '' ? $conn->real_escape_string($_GET['category']) : null;
    $color = isset($_GET['color']) && $_GET['color'] !== '' ? $conn->real_escape_string($_GET['color']) : null;

    // Siapkan query berdasarkan keberadaan kategori dan warna
    $query = "INSERT INTO Notes (judul, isi, tanggal_buat, tanggal_ubah" .
             ($category ? ", id_label" : "") .
             ($color ? ", bg_color" : "") .
             ") VALUES ('$title', '$content', NOW(), NOW()" .
             ($category ? ", (SELECT id_label FROM Labels WHERE nama_label='$category')" : "") .
             ($color ? ", '$color'" : "") .
             ")";

    $conn->query($query);
    echo "<div class='alert alert-success'>Catatan berhasil ditambahkan!</div>";

    // Hapus nilai dari localStorage
    echo "<script>localStorage.clear();</script>";
    header("Location:data.php");
}
?>


        <form action="" method="GET" class="bg-white shadow-lg rounded-lg p-4">
            <div class="form-group">
                <label for="title">Judul</label>
                <input type="text" id="title" name="title" class="form-control input-field" placeholder="Masukkan judul..." required oninput="saveInputValues()">
                <small class="form-text text-muted">Tempat untuk menuliskan judul singkat dan jelas tentang isi catatan.</small>
            </div>

            <div class="form-group">
                <label for="content">Teks</label>
                <textarea  id="content" name="content" class="form-control input-field" placeholder="Tulis catatan Anda di sini..." required oninput="saveInputValues()"></textarea>
                <small class="form-text text-muted">Area utama untuk menuliskan isi catatanmu.</small>
            </div>

            <div class="form-group">
                <label for="category">Kategori</label>
                <select id="category" name="category" class="form-control input-field" onchange="checkCategory()">
                    <option value="">Pilih kategori</option>
                    <?php
                    
                    $sql = "SELECT * FROM Labels";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['nama_label'] . "'>" . $row['nama_label'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Belum Ada Kategori</option>";
                    }
                    ?>
                    <option value="tambah">Tambah</option>
                </select>
                <small class="form-text text-muted">Pilih label atau kategori untuk catatan.</small>
            </div>

            <div class="form-group">
                <label>Pilih Warna:</label>
                <div id="colorPicker">
                    <div class="color-option" style="background-color: red;" onclick="selectColor(this)"></div>
                    <div class="color-option" style="background-color: green;" onclick="selectColor(this)"></div>
                    <div class="color-option" style="background-color: blue;" onclick="selectColor(this)"></div>
                    <div class="color-option" style="background-color: yellow;" onclick="selectColor(this)"></div>
                    <div class="color-option" style="background-color: orange;" onclick="selectColor(this)"></div>
                    <div class="color-option" style="background-color: purple;" onclick="selectColor(this)"></div>
                </div>
                <input type="hidden" name="color" id="selectedColor">
                <small class="form-text text-muted">Klik pada warna untuk memilih.</small>
            </div>

            <div class="d-flex justify-content-end">
                <a href="data.php" class="btn btn-secondary mr-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>

    <!-- Modal untuk Menambahkan Kategori -->
    <div id="addCategoryModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Buat Kategori Baru</h5>
                    <button type="button" class="close" onclick="closeModal()" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="newCategory">Nama Kategori</label>
                            <input type="text" id="newCategory" name="newCategory" class="form-control input-field" placeholder="Masukkan nama kategori..." required>
                        </div>
                        <div>
                            <label>Pilih Warna:</label>
                            <div id="colorPickerModal">
                                <div class="color-option" style="background-color: red;" onclick="selectColorModal(this)"></div>
                                <div class="color-option" style="background-color: green;" onclick="selectColorModal(this)"></div>
                                <div class="color-option" style="background-color: blue;" onclick="selectColorModal(this)"></div>
                                <div class="color-option" style="background-color: yellow;" onclick="selectColorModal(this)"></div>
                                <div class="color-option" style="background-color: orange;" onclick="selectColorModal(this)"></div>
                                <div class="color-option" style="background-color: purple;" onclick="selectColorModal(this)"></div>
                            </div>
                            <input type="hidden" name="categoryColor" id="selectedCategoryColor">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function checkCategory() {
            const selectElement = document.getElementById('category');
            const selectedValue = selectElement.value;

            if (selectedValue === 'tambah') {
                openModal();
            }
        }

        function openModal() {
            $('#addCategoryModal').modal('show');
        }

        function closeModal() {
            $('#addCategoryModal').modal('hide');
            document.getElementById('category').selectedIndex = 0;
        }

        function selectColor(element) {
            const colorOptions = document.querySelectorAll('.color-option');
            colorOptions.forEach(option => option.classList.remove('selected'));
            element.classList.add('selected');
            document.getElementById('selectedColor').value = element.style.backgroundColor;
        }

        function selectColorModal(element) {
            const colorOptionsModal = document.querySelectorAll('#colorPickerModal .color-option');
            colorOptionsModal.forEach(option => option.classList.remove('selected'));
            element.classList.add('selected');
            document.getElementById('selectedCategoryColor').value = element.style.backgroundColor;
        }

        // Fungsi untuk menyimpan nilai input ke localStorage
        function saveInputValues() {
            const title = document.getElementById('title').value;
            const content = document.getElementById('content').value;
            const category = document.getElementById('category').value;
            const color = document.getElementById('selectedColor').value;

            localStorage.setItem('noteTitle', title);
            localStorage.setItem('noteContent', content);
            localStorage.setItem('noteCategory', category);
            localStorage.setItem('noteColor', color);
        }

        // Fungsi untuk memuat nilai input dari localStorage
        function loadInputValues() {
            const title = localStorage.getItem('noteTitle');
            const content = localStorage.getItem('noteContent');
            const category = localStorage.getItem('noteCategory');
            const color = localStorage.getItem('noteColor');

            if (title) document.getElementById('title').value = title;
            if (content) document.getElementById('content').value = content;
            if (category) document.getElementById('category').value = category;
            if (color) {
                document.getElementById('selectedColor').value = color;
                const colorOptions = document.querySelectorAll('.color-option');
                colorOptions.forEach(option => {
                    if (option.style.backgroundColor === color) {
                        option.classList.add('selected');
                    }
                });
            }
        }

        // Panggil fungsi loadInputValues saat halaman dimuat
        loadInputValues();
    </script>
</body>

</html>
