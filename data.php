<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// header('Content-Type: application/json'); // Set the header for JSON response

include "koneksi.php";

// Function to send JSON response
function sendJsonResponse($data)
{
  echo json_encode($data);
  exit;
}

if (isset($_GET['delete_label_id'])) {
  $deleteLabelId = intval($_GET['delete_label_id']); // Sanitize input
  $sql = "DELETE FROM Labels WHERE id_label = $deleteLabelId";

  // Prepare the response array
  $response = [];

  if ($conn->query($sql) === TRUE) {
    $response['success'] = 'Label deleted successfully';
    $response['deleted_id'] = $deleteLabelId;
  } else {
    $response['error'] = 'Failed to delete label: ' . $conn->error;
  }

  // Add debug information
  $response['debug'] = [
    'attempted_delete_id' => $deleteLabelId,
    'sql_query' => $sql,
  ];

  sendJsonResponse($response);
}

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

  $sql = "SELECT Notes.*, Labels.nama_label, Labels.warna FROM Notes LEFT JOIN Labels ON Notes.id_label = Labels.id_label $orderBy";
  $result = $conn->query($sql);

  $notes = [];
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $notes[] = $row;
    }
  }

  sendJsonResponse(['notes' => $notes]);
}

if (isset($_GET['search'])) {
  $searchTerm = $conn->real_escape_string($_GET['search']);
  $sql = "SELECT Notes.*, Labels.nama_label, Labels.warna FROM Notes LEFT JOIN Labels ON Notes.id_label = Labels.id_label WHERE judul LIKE '%$searchTerm%' OR isi LIKE '%$searchTerm%' ORDER BY tanggal_buat DESC";
  $result = $conn->query($sql);

  $notes = [];
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $notes[] = $row;
    }
  }

  sendJsonResponse(['notes' => $notes]);
}

if (isset($_GET['note_id'])) {
  $note_id = intval($_GET['note_id']);
  $sql = "SELECT * FROM Notes WHERE id_catatan = $note_id";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $note = $result->fetch_assoc();
    sendJsonResponse($note);
  } else {
    sendJsonResponse(['error' => 'Note not found']);
  }
}

if (isset($_GET['delete_id'])) {
  $delete_id = intval($_GET['delete_id']);
  $sql = "DELETE FROM Notes WHERE id_catatan = $delete_id";
  if ($conn->query($sql) === TRUE) {
    sendJsonResponse(['success' => 'Note deleted successfully']);
  } else {
    sendJsonResponse(['error' => 'Failed to delete note']);
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['noteId'])) {
  $noteId = intval($_POST['noteId']);
  $title = $conn->real_escape_string($_POST['title']);
  $content = $conn->real_escape_string($_POST['content']);
  $labelId = intval($_POST['label']);
  $sql = "UPDATE Notes SET judul = '$title', isi = '$content', id_label = '$labelId', tanggal_ubah = NOW() WHERE id_catatan = $noteId";
  if ($conn->query($sql) === TRUE) {
    sendJsonResponse(['success' => 'Note updated successfully']);
  } else {
    sendJsonResponse(['error' => 'Failed to update note']);
  }
}

function generateNoteCard($row)
{
  $bgColor = htmlspecialchars($row['bg_color']) ?: 'gray'; // Default color if no color is set
  $label = htmlspecialchars($row['nama_label']) ?: 'Tanpa Kategori';

  $textColor = $row['id_label'] ? 'text-white' : 'text-gray-700';
  $dateColor = 'text-gray-600';

  $judul = htmlspecialchars($row['judul']);
  $isi = htmlspecialchars($row['isi']);
  $idNote = htmlspecialchars($row['id_catatan']);

  return "<div style='background-color: $bgColor;' class='rounded-lg shadow-md p-6 relative cursor-pointer' onclick='openEditModal($idNote)'>
                 <span class='absolute top-2 right-2 bg-white text-$bgColor px-3 py-1 text-sm font-semibold rounded'>$label</span>
                 <h2 class='text-xl font-semibold mb-2 $textColor' style='overflow: hidden; white-space: nowrap; text-overflow: ellipsis;'>$judul</h2>
                 <p class='$textColor' style='overflow: hidden; white-space: nowrap; text-overflow: ellipsis;'>$isi</p>
                 <p class='text-sm $textColor bg-$bgColor inline-flex px-4 py-1 rounded-lg mt-2'>Tanggal Buat: " . date('d-m-Y', strtotime($row['tanggal_buat'])) . "</p>
                 <p class='text-sm $textColor bg-$bgColor inline-flex px-4 py-1 rounded-lg mt-2'>Tanggal Ubah: " . date('d-m-Y', strtotime($row['tanggal_ubah'])) . "</p>
             </div>";
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
        <div class="px-4 py-5 rounded-full group border-2 border-gray-800 hover:bg-blue-500 hover:border-white transition-all cursor-pointer">
          <a href="#" class="text-gray-800 group-hover:text-white"><i class="fas fa-2xl fa-gear ml-1"></i></a>
          <ul class="absolute hidden group-hover:block bg-white shadow-md mt-2 py-2 w-48 z-10">
            <li><a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-200" onclick="openLabelModal()">Label</a></li>
          </ul>
        </div>
        <div class="p-5 text-gray-800 rounded-full group border-2 border-gray-800 hover:bg-blue-500 hover:border-white transition-all cursor-pointer" id="sortButton" title="sortir" onclick="toggleSort()">
          <i id="sortIcon" class="fas fa-calendar-alt fa-2xl group-hover:text-white"></i>
        </div>
        <div class="p-5 text-gray-800 rounded-full group border-2 border-gray-800 hover:bg-blue-500 hover:border-white transition-all cursor-pointer" title="tambah note" onclick="window.location.href = 'tambahNote.php';">
          <i class="fas fa-plus fa-2xl group group-hover:text-white"></i>
        </div>
      </div>
    </div>

    <section class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="notesSection">
      <?php
      $sql = "SELECT Notes.*, Labels.nama_label, Labels.warna, Notes.bg_color FROM Notes LEFT JOIN Labels ON Notes.id_label = Labels.id_label ORDER BY tanggal_buat DESC";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo generateNoteCard($row);
        }
      } else {
        echo "<div class='text-center'><p>Tidak ada catatan terdeteksi</p></div>";
      }
      ?>
    </section>
  </main>

  <!-- Label Management Modal -->
  <div id="labelModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
      <h2 class="text-xl font-semibold mb-4">Manajemen Label</h2>
      <input type="hidden" id="labelId">
      <div class="mb-4">
        <?php
        $sql = "SELECT * FROM Labels";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          echo "<div class='flex flex-col space-y-2'>";
          while ($row = $result->fetch_assoc()) {
            echo "<div class='flex items-center justify-between border-b-2 border-gray-200 pb-2'>";
            echo "<h1 class='text-lg text-gray-800'>" . htmlspecialchars($row['nama_label']) . "</h1>";
            echo "<button onclick='deleteLabel(" . $row['id_label'] . ")' class='bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700'>Hapus</button>";
            echo "</div>";
          }
          echo "</div>";
        } else {
          echo "<h1 class='text-gray-600'>Belum Ada Label</h1>";
        }
        ?>
      </div>
      <div class="flex justify-between">
        <button onclick="saveLabel()" class="bg-blue-600 text-white px-4 py-2 rounded-md">Simpan</button>
        <button onclick="closeLabelModal()" class="ml-2 bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Batal</button>
      </div>
    </div>
  </div>

  <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
      <h2 class="text-xl font-semibold mb-4">Edit Catatan</h2>
      <input type="hidden" id="noteId">
      <div class="mb-4">
        <label for="noteTitle" class="block text-sm font-medium text-gray-700">Judul</label>
        <input type="text" id="noteTitle" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500" required>
      </div>
      <div class="mb-4">
        <label for="noteContent" class="block text-sm font-medium text-gray-700">Isi</label>
        <textarea id="noteContent" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500" required></textarea>
      </div>
      <div class="mb-4">
        <label for="noteLabel" class="block text-sm font-medium text-gray-700">Label</label>
        <select id="noteLabel" name="label" class="form-control input-field">
          <option value="">Pilih kategori</option>
          <?php
          $sql = "SELECT * FROM Labels";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<option value='" . $row['id_label'] . "'>" . htmlspecialchars($row['nama_label']) . "</option>";
            }
          } else {
            echo "<option value=''>Belum Ada Kategori</option>";
          }
          ?>
          <option value="tambah">Tambah</option>
        </select>
      </div>

      <div class="flex justify-between">
        <button onclick="updateNote()" class="bg-blue-600 text-white px-4 py-2 rounded-md">Simpan</button>
        <button onclick="closeEditModal()" class="ml-2 bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Batal</button>
        <button onclick="deleteNote()" class="ml-2 bg-red-600 text-white px-4 py-2 rounded-md">Hapus</button>
      </div>
    </div>
  </div>

  <script>
  localStorage.clear();

  function deleteLabel(labelId) {
    if (confirm('Apakah Anda yakin ingin menghapus label ini?')) {
      const xhr = new XMLHttpRequest();
      xhr.open("GET", `?delete_label_id=${labelId}`, true);
      xhr.onload = function() {
        const response = JSON.parse(this.responseText);
        alert(response.success || response.error);

        // Log debug information to the console
        console.log('Debug Info:', response.debug);

        if (response.success) {
          location.reload(); // Reload the page to see the changes
        }
      };
      xhr.send();
    }
  }

  function openEditModal(noteId) {
    const xhrNote = new XMLHttpRequest();
    xhrNote.open("GET", `?note_id=${noteId}`, true);
    xhrNote.onload = function() {
      if (this.status === 200) {
        const note = JSON.parse(this.responseText);
        if (!note.error) {
          document.getElementById('noteId').value = note.id_catatan;
          document.getElementById('noteTitle').value = note.judul;
          document.getElementById('noteContent').value = note.isi;
          document.getElementById('noteLabel').value = note.id_label || '';
          document.getElementById('editModal').classList.remove('hidden');
        } else {
          console.error(note.error);
        }
      }
    };
    xhrNote.send();
  }

  function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
  }

  function updateNote() {
    const noteId = document.getElementById('noteId').value;
    const title = document.getElementById('noteTitle').value;
    const content = document.getElementById('noteContent').value;
    const label = document.getElementById('noteLabel').value;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
      const response = JSON.parse(this.responseText);
      alert(response.success || response.error);
      if (response.success) {
        location.reload();
      }
    };
    xhr.send(`noteId=${noteId}&title=${title}&content=${content}&label=${label}`);
  }

  function performSearch() {
  const searchTerm = document.getElementById('searchInput').value;
  const xhr = new XMLHttpRequest();
  xhr.open("GET", `?search=${searchTerm}`, true);
  xhr.onload = function() {
    const response = JSON.parse(this.responseText);
    const notesSection = document.getElementById('notesSection');
    notesSection.innerHTML = ''; // Clear current notes

    // Generate HTML for each note and append to notesSection
    response.notes.forEach(note => {
      const noteCard = generateNoteCard(note);
      notesSection.innerHTML += noteCard;
    });
  };
  xhr.send();
}

  function handleSearch(event) {
    if (event.key === 'Enter') {
      performSearch();
    }
  }

  let currentSort = 'tanggal';

  function generateNoteCard(note) {
    const bgColor = note.bg_color || 'gray'; // Default color if no color is set
    const label = note.nama_label || 'Tanpa Kategori';
    const textColor = note.id_label ? 'text-white' : 'text-gray-700';

    return `
      <div style="background-color: ${bgColor};" class="rounded-lg shadow-md p-6 relative cursor-pointer" onclick="openEditModal(${note.id_catatan})">
        <span class="absolute top-2 right-2 bg-white text-${bgColor} px-3 py-1 text-sm font-semibold rounded">${label}</span>
        <h2 class="text-xl font-semibold mb-2 ${textColor}" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">${note.judul}</h2>
        <p class="${textColor}" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">${note.isi}</p>
        <p class="text-sm ${textColor} bg-${bgColor} inline-flex px-4 py-1 rounded-lg mt-2">Tanggal Buat: ${new Date(note.tanggal_buat).toLocaleDateString()}</p>
        <p class="text-sm ${textColor} bg-${bgColor} inline-flex px-4 py-1 rounded-lg mt-2">Tanggal Ubah: ${new Date(note.tanggal_ubah).toLocaleDateString()}</p>
      </div>
    `;
  }

  function toggleSort() {
  currentSort = currentSort === 'judul' ? 'label' : currentSort === 'label' ? 'tanggal_ubah' : 'judul';
  const xhr = new XMLHttpRequest();
  xhr.open("GET", `?sort=${currentSort}`, true);
  xhr.onload = function() {
    const response = JSON.parse(this.responseText);
    const notesSection = document.getElementById('notesSection');
    notesSection.innerHTML = ''; // Clear current notes

    // Generate HTML for each note and append to notesSection
    response.notes.forEach(note => {
      const bgColor = note.bg_color || 'gray'; // Default color if no color is set
      const label = note.nama_label || 'Tanpa Kategori';
      const textColor = note.id_label ? 'text-white' : 'text-gray-700';

      const noteCard = `
        <div style="background-color: ${bgColor};" class="rounded-lg shadow-md p-6 relative cursor-pointer" onclick="openEditModal(${note.id_catatan})">
          <span class="absolute top-2 right-2 bg-white text-${bgColor} px-3 py-1 text-sm font-semibold rounded">${label}</span>
          <h2 class="text-xl font-semibold mb-2 ${textColor}" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">${note.judul}</h2>
          <p class="${textColor}" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">${note.isi}</p>
          <p class="text-sm ${textColor} bg-${bgColor} inline-flex px-4 py-1 rounded-lg mt-2">Tanggal Buat: ${new Date(note.tanggal_buat).toLocaleDateString()}</p>
          <p class="text-sm ${textColor} bg-${bgColor} inline-flex px-4 py-1 rounded-lg mt-2">Tanggal Ubah: ${new Date(note.tanggal_ubah).toLocaleDateString()}</p>
        </div>
      `;

      notesSection.innerHTML += noteCard;
    });

    // Update sort icon
    document.getElementById('sortIcon').className = currentSort === 'judul' ? 'fas fa-book fa-2xl group-hover:text-white' : currentSort === 'label' ? 'fas fa-tags fa-2xl group-hover:text-white' : 'fas fa-calendar-alt fa-2xl group-hover:text-white';
  };
  xhr.send();
}

  function deleteNote() {
    const noteId = document.getElementById('noteId').value;
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `?delete_id=${noteId}`, true);
    xhr.onload = function() {
      const response = JSON.parse(this.responseText);
      alert(response.success || response.error);
      if (response.success) {
        location.reload();
      }
    };
    xhr.send();
  }

  function openLabelModal() {
    document.getElementById('labelModal').classList.remove('hidden');
  }

  function closeLabelModal() {
    document.getElementById('labelModal').classList.add('hidden');
    document.getElementById('labelId').value = '';
    document.getElementById('labelName').value = '';
  }

  function saveLabel() {
    const labelId = document.getElementById('labelId').value;
    const labelName = document.getElementById('labelName').value;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "save_label.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
      const response = JSON.parse(this.responseText);
      alert(response.success || response.error);
      if (response.success) {
        location.reload();
      }
    };
    xhr.send(`labelId=${labelId}&labelName=${labelName}`);
  }
</script>
</body>

</html>