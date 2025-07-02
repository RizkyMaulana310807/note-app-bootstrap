<?php
include "koneksi.php";

// Handle AJAX request for sorting
if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
    $orderBy = '';

    switch ($sort) {
        case 'judul':
            $orderBy = 'ORDER BY judul ASC';
            break;
        case 'label':
            $orderBy = 'ORDER BY nama_label ASC';
            break;
        case 'tanggal_ubah':
            $orderBy = 'ORDER BY tanggal_ubah DESC';
            break;
        case 'tanggal':
        default:
            $orderBy = 'ORDER BY tanggal_buat DESC';
            break;
    }

    $sql = "SELECT Notes.*, Labels.nama_label FROM Notes LEFT JOIN Labels ON Notes.id_label = Labels.id_label $orderBy";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $warna = $row['warna'];
            $label = $row['nama_label'] ? $row['nama_label'] : 'Tanpa Kategori';
            $textColor = $row['nama_label'] ? 'text-white' : 'text-gray-700';
            $dateColor = $row['nama_label'] ? 'text-white' : 'text-gray-600';

            $judul = htmlspecialchars($row['judul']);
            $isi = htmlspecialchars($row['isi']);

            echo "<div class='bg-$warna-500 rounded-lg shadow-md p-6 relative'>";
            echo "<span class='absolute top-2 right-2 bg-white text-$warna-500 px-3 py-1 text-sm font-semibold rounded'>$label</span>";
            echo "<h2 class='text-xl font-semibold mb-2 $textColor' style='overflow: hidden; white-space: nowrap; text-overflow: ellipsis;'>$judul</h2>";
            echo "<p class='$textColor' style='overflow: hidden; white-space: nowrap; text-overflow: ellipsis;'>$isi</p>";
            echo "<p class='text-sm $dateColor bg-$warna-700 inline-flex px-4 py-1 rounded-lg mt-2'>Tanggal Buat: " . date('d-m-Y', strtotime($row['tanggal_buat'])) . "</p>";
            echo "<p class='text-sm $dateColor bg-$warna-700 inline-flex px-4 py-1 rounded-lg mt-2'>Tanggal Ubah: " . date('d-m-Y', strtotime($row['tanggal_ubah'])) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<div class='text-center'><p>Tidak ada catatan terdeteksi</p></div>";
    }
    $conn->close();
    exit; // Terminate the script after handling the AJAX request
}

// Handle AJAX request for searching
if (isset($_GET['search'])) {
    $searchTerm = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT Notes.*, Labels.nama_label FROM Notes LEFT JOIN Labels ON Notes.id_label = Labels.id_label WHERE judul LIKE '%$searchTerm%' OR isi LIKE '%$searchTerm%' ORDER BY tanggal_buat DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $warna = $row['warna'];
            $label = $row['nama_label'] ? $row['nama_label'] : 'Tanpa Kategori';
            $textColor = $row['nama_label'] ? 'text-white' : 'text-gray-700';
            $dateColor = $row['nama_label'] ? 'text-white' : 'text-gray-600';

            $judul = htmlspecialchars($row['judul']);
            $isi = htmlspecialchars($row['isi']);

            echo "<div class='bg-$warna-500 rounded-lg shadow-md p-6 relative'>";
            echo "<span class='absolute top-2 right-2 bg-white text-$warna-500 px-3 py-1 text-sm font-semibold rounded'>$label</span>";
            echo "<h2 class='text-xl font-semibold mb-2 $textColor' style='overflow: hidden; white-space: nowrap; text-overflow: ellipsis;'>$judul</h2>";
            echo "<p class='$textColor' style='overflow: hidden; white-space: nowrap; text-overflow: ellipsis;'>$isi</p>";
            echo "<p class='text-sm $dateColor bg-$warna-700 inline-flex px-4 py-1 rounded-lg mt-2'>Tanggal Buat: " . date('d-m-Y', strtotime($row['tanggal_buat'])) . "</p>";
            echo "<p class='text-sm $dateColor bg-$warna-700 inline-flex px-4 py-1 rounded-lg mt-2'>Tanggal Ubah: " . date('d-m-Y', strtotime($row['tanggal_ubah'])) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<div class='text-center'><p>Tidak Ditemukan</p></div>";
    }
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Scribble Notes</title>
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="dist/output.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="font/fontawesome/css/all.min.css">
</head>

<body class="bg-gray-100 font-roboto">

    <header class="fixed top-0 left-0 right-0 bg-white shadow-md z-10">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <a href="index.php" class="flex items-center">
                <h1 class="text-2xl font-bold text-blue-600">Scribble Notes</h1>
                <span class="text-2xl font-bold text-blue-600">.</span>
            </a>
            <nav class="hidden md:flex items-center space-x-4">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search..." class="pl-3 pr-10 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" onkeypress="handleSearch(event)">
                    <button class="absolute right-3 top-1/2 transform -translate-y-1/2" onclick="performSearch()">
                        <i class="fas fa-search text-gray-400"></i>
                    </button>
                </div>
            </nav>
            <button class="md:hidden text-gray-600">
                <i class="bi bi-list text-2xl"></i>
            </button>
        </div>
    </header>

    <main class="mt-24 container mx-auto px-4">
        <div class="flex justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-4 text-gray-800">Scribble Notes</h1>
                <p class="text-lg text-gray-600">Catatan anda baru-baru ini</p>
            </div>

            <div class="flex flex-row items-start">
                <div class="p-5 text-gray-800 rounded-full group border-2 border-gray-800 hover:bg-blue-500 hover:border-white transition-all cursor-pointer" id="sortButton" title="sortir" onclick="toggleSort()">
                    <i id="sortIcon" class="fas fa-calendar-alt fa-2xl group-hover:text-white"></i>
                </div>
                <div class="p-5 text-gray-800 rounded-full group border-2 border-gray-800 hover:bg-blue-500 hover:border-white transition-all cursor-pointer" title="tambah note" onclick="window.location.href = 'tambahNote.php';">
                    <i class="fas fa-plus fa-2xl group-hover:text-white"></i>
                </div>
            </div>
        </div>

        <section class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="notesSection">
            <?php
            // Fetch notes with default sorting by date
            $sql = "SELECT Notes.*, Labels.nama_label FROM Notes LEFT JOIN Labels ON Notes.id_label = Labels.id_label ORDER BY tanggal_buat DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $warna = $row['warna'];
                    $label = $row['nama_label'] ? $row['nama_label'] : 'Tanpa Kategori';
                    $textColor = $row['nama_label'] ? 'text-white' : 'text-gray-700';
                    $dateColor = $row['nama_label'] ? 'text-white' : 'text-gray-600';

                    $judul = htmlspecialchars($row['judul']);
                    $isi = htmlspecialchars($row['isi']);

                    echo "<div class='bg-$warna-500 rounded-lg shadow-md p-6 relative overflow-hidden'>";
                    echo "<span class='absolute top-2 right-2 bg-white text-$warna-500 px-3 py-1 text-sm font-semibold rounded'>$label</span>";
                    echo "<h2 class='text-xl font-semibold mb-2 $textColor' style='overflow: hidden; white-space: nowrap; text-overflow: ellipsis;'>$judul</h2>";
                    echo "<p class='$textColor' style='overflow: hidden; white-space: nowrap; text-overflow: ellipsis;'>$isi</p>";
                    echo "<p class='text-sm $dateColor bg-$warna-700 inline-flex px-4 py-1 rounded-lg mt-2'>Tanggal Buat: " . date('d-m-Y', strtotime($row['tanggal_buat'])) . "</p>";
                    echo "<p class='text-sm $dateColor bg-$warna-700 inline-flex px-4 py-1 rounded-lg mt-2'>Tanggal Ubah: " . date('d-m-Y', strtotime($row['tanggal_ubah'])) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<div class='text-center'><p>Tidak ada catatan terdeteksi</p></div>";
            }
            ?>
        </section>

    </main>

    <!-- Modal -->
    <div id="searchModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg p-6 w-11/12 md:w-1/2">
            <h2 class="text-xl font-bold mb-4">Hasil Pencarian</h2>
            <div id="searchResults"></div>
            <button class="mt-4 px-4 py-2 bg-blue-500 text-white rounded" onclick="closeModal()">Tutup</button>
        </div>
    </div>

    <script>
        let sortOrder = 0; // 0: Tanggal, 1: Judul, 2: Label, 3: Tanggal Ubah

        function toggleSort() {
            sortOrder = (sortOrder + 1) % 4; // Loop through 0, 1, 2, 3

            const sortIcon = document.getElementById('sortIcon');
            switch (sortOrder) {
                case 0:
                    sortIcon.className = 'fas fa-calendar-alt fa-2xl group-hover:text-white'; // Tanggal
                    sortNotes('tanggal');
                    break;
                case 1:
                    sortIcon.className = 'fas fa-sort-alpha-up fa-2xl group-hover:text-white'; // Judul
                    sortNotes('judul');
                    break;
                case 2:
                    sortIcon.className = 'fas fa-tag fa-2xl group-hover:text-white'; // Label
                    sortNotes('label');
                    break;
                case 3:
                    sortIcon.className = 'fas fa-calendar-check fa-2xl group-hover:text-white'; // Tanggal Ubah
                    sortNotes('tanggal_ubah');
                    break;
            }
        }

        function sortNotes(criteria) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", `?sort=${criteria}`, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('notesSection').innerHTML = this.responseText;
                }
            };
            xhr.send();
        }

        function performSearch() {
            const searchInput = document.getElementById('searchInput').value.trim();
            if (searchInput) {
                const xhr = new XMLHttpRequest();
                xhr.open("GET", `?search=${encodeURIComponent(searchInput)}`, true);
                xhr.onload = function() {
                    if (this.status === 200) {
                        document.getElementById('searchResults').innerHTML = this.responseText;
                        document.getElementById('searchModal').classList.remove('hidden');
                    }
                };
                xhr.send();
            }
        }

        function handleSearch(event) {
            if (event.key === 'Enter') {
                performSearch();
            }
        }

        function closeModal() {
            document.getElementById('searchModal').classList.add('hidden');
        }
    </script>
</body>

</html>
