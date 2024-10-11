<!DOCTYPE html>
<html lang="en">

<t>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - Impact Tailwind Template</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- Tailwind CSS -->
  <link rel="stylesheet" href="dist/output.css">
  <!-- <script src="https://cdn.tailwindcss.com"></script> -->
  <style>
    html {
      scroll-behavior: smooth;
    }
  </style>
</t>

<body class="bg-gray-100 font-roboto">

  <header class="fixed top-0 left-0 right-0 bg-white shadow-md z-10">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
      <a href="index.php" class="flex items-center">
        <h1 class="text-2xl font-bold text-blue-600">Scribble Notes</h1>
        <span class="text-2xl font-bold text-blue-600">.</span>
      </a>

      <nav class="hidden md:flex space-x-4">
        <a href="#hero" class="text-gray-800 hover:text-blue-600">Home</a>
        <a href="#main" class="text-gray-800 hover:text-blue-600">Tentang Scribble</a>
        <a href="#about" class="text-gray-800 hover:text-blue-600">About</a>
        <a href="#team" class="text-gray-800 hover:text-blue-600">Team</a>
        <a href="data.php" class="text-gray-800 hover:text-blue-600">Mulai</a>
      </nav>

      <button class="md:hidden text-gray-600">
        <i class="fas fa-ellipsis-v text-2xl"></i>
      </button>
    </div>
  </header>

  <main class="mt-0">
    <!-- Hero Section -->
    <section id="hero" class="hero section bg-blue-300 h-screen border-b border-gray-200 flex items-center">
      <div class="container mx-auto">
        <div class="flex flex-wrap justify-center items-center h-full">
          <div class="w-full lg:w-1/2 p-6 text-lg flex flex-col justify-center" id="welcome-text">
            <h2 class="text-7xl font-bold mb-4 text-white text-center drop-shadow-lg" style="font-family: 'Impact', sans-serif;">WELCOME TO SCRIBBLE NOTES</h2>
          </div>


          <!-- <div class="w-full lg:w-1/2 p-6 flex justify-center">
                <img src="assets/img/hero-img.svg" class="w-full" alt="">
            </div> -->
        </div>
      </div>
    </section>


    <!-- Main Section -->
    <section id="main" class="main section bg-white h-screen border-b border-gray-200 flex shadow-lg">
      <div class="container mx-auto">
        <h2 class="text-7xl font-extrabold mb-4 mt-40 text-gray-700" style="font-family: 'Impact', sans-serif;">SCRIBBLE NOTES</h2>
        <p class="text-lg text-gray-600">Catatan Pintar Anda untuk menyimpan <span id="typing-text" class="bg-blue-600 px-3 py-1 rounded-lg shadow-xl font-extrabold text-white"></span></p>
        <div class="border-blue-500 border-2 rounded-full mt-11 inline-flex hover:bg-blue-500 hover:border-white transition-all group">
          <a class="text-xl py-3 px-10 text-center font-extrabold text-blue-500 group-hover:text-white" href="data.php">MULAI</a>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <div id="about" class="about section bg-blue-600 h-screen border-b border-gray-200 flex flex-col items-center justify-between shadow-lg">
      <div class="container mx-auto mt-16"> <!-- Added margin-top to move text down -->
        <h2 class="text-5xl font-bold mb-4 text-white">About Us</h2>
        <p class="text-lg text-white">Website ini dibuat oleh kelompok 2 yang di anggotai, Dika, Rizky, Rafi B, Dan Dearly. Dengan tujuan untuk membantu pengguna agar lebih produktif dengan aplikasi catatan yang sederhana dan efektif</p>
        <div class="border-white-500 border-2 rounded-full mt-11 inline-flex hover:bg-white hover:border-white transition-all group">
        <a class="text-xl py-3 px-10 text-center font-extrabold text-white group-hover:text-blue-500" href="#team">Our Team</a>
      </div>
      </div>
      
      <div class="p-2 rounded-2xl"> <!-- Reduced padding -->
        <h1 class="text-white mb-2">Website ini di buat dengan:</h1> <!-- Reduced margin -->
        <div class="flex justify-center space-x-2"> <!-- Reduced space between images -->
          <img src="assets/logo/tailwind.png" alt="tailwind" class="w-24 h-24 rounded-lg shadow-md" title="TailwindCSS">
          <img src="assets/logo/php.png" alt="PHP" class="w-24 h-24 rounded-lg shadow-md" title="PHP">
          <img src="assets/logo/chatgpt.png" alt="chatGPT" class="w-24 h-24 rounded-lg shadow-md" title="ChatGPT">
        </div>
      </div>
    </div>

    <!-- Team Section -->
    <section id="team" class="team section bg-white h-screen border-b border-gray-200 flex items-center shadow-lg">
      <div class="container mx-auto">
        <h2 class="text-3xl font-bold mb-4 text-gray-800">Our Team</h2>
        <div class="flex flex-wrap justify-center">
          <div class="w-full lg:w-1/4 p-6">
            <div class="bg-white rounded-lg shadow-md p-4"> <!-- Card 1 -->
              <img src="assets/img/team/team-1.jpg" class="w-full rounded-lg mb-2" alt="">
              <h4 class="text-xl font-bold mb-2 text-gray-800">Rizky Maulana</h4>
              <p class="text-lg text-gray-600">BackEnd Dev | Flow Chart</p>
            </div>
          </div>
          <div class="w-full lg:w-1/4 p-6">
            <div class="bg-white rounded-lg shadow-md p-4"> <!-- Card 2 -->
              <img src="assets/img/team/team-2.jpg" class="w-full rounded-lg mb-2" alt="">
              <h4 class="text-xl font-bold mb-2 text-gray-800">Rafi B</h4>
              <p class="text-lg text-gray-600">FrontEnd Dev</p>
            </div>
          </div>
          <div class="w-full lg:w-1/4 p-6">
            <div class="bg-white rounded-lg shadow-md p-4"> <!-- Card 3 -->
              <img src="assets/img/team/team-3.jpg" class="w-full rounded-lg mb-2" alt="">
              <h4 class="text-xl font-bold mb-2 text-gray-800">Dika</h4>
              <p class="text-lg text-gray-600">UI/UX | QA (Test Case)</p>
            </div>
          </div>
          <div class="w-full lg:w-1/4 p-6">
            <div class="bg-white rounded-lg shadow-md p-4"> <!-- Card 4 -->
              <img src="assets/img/team/team-4.jpg" class="w-full rounded-lg mb-2" alt="">
              <h4 class="text-xl font-bold mb-2 text-gray-800">Dearly</h4>
              <p class="text-lg text-gray-600">none</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>







  <footer class="footer bg-blue-600 text-white py-12">
    <div class="container mx-auto px-4">
      <p class="text-lg">2024 Kelompok 2. All Rights Reserved.</p>
      <div class="credits">
        <!-- Designed by <a href="https://bootstrapmade.com/" class="text-blue-200 hover:underline">BootstrapMade</a> -->
        @ SMKN 4 PADALARANG
      </div>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="fas fa-arrow-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="assets/js/jquery-3.7.1.min.js"></script>

  <script>
    const texts = ["Ide", "Tugas Sekolah", "Proyek", "Daftar Belanja", "Kata Kata Penting", "Nomor Telepon", "Impian", "Jadwal", "Lirik Lagu", "Daftar bacaan", "Akun dan Password", "Scribble Notes"];
    let currentTextIndex = 0;
    let currentCharIndex = 0;
    let isDeleting = false;
    const typingSpeed = 150; // Speed of typing
    const deletingSpeed = 50; // Speed of deleting
    const delayBetweenTexts = 2000; // Delay between each text

    function type() {
      const typingTextElement = document.getElementById("typing-text");
      const currentText = texts[currentTextIndex];

      if (isDeleting) {
        typingTextElement.textContent = currentText.substring(0, currentCharIndex - 1);
        currentCharIndex--;

        if (currentCharIndex === 0) {
          isDeleting = false;
          currentTextIndex = (currentTextIndex + 1) % texts.length;
          setTimeout(type, typingSpeed);
        } else {
          setTimeout(type, deletingSpeed);
        }
      } else {
        typingTextElement.textContent = currentText.substring(0, currentCharIndex + 1);
        currentCharIndex++;

        if (currentCharIndex === currentText.length) {
          isDeleting = true;
          setTimeout(type, delayBetweenTexts);
        } else {
          setTimeout(type, typingSpeed);
        }
      }
    }

    document.addEventListener("DOMContentLoaded", () => {
      type();
    });
    $(document).ready(function() {
      $('#toggle-button').click(function() {
        if ($('#welcome-text').is(':visible')) {
          $('#welcome-text').slideUp(300).fadeOut(300);
        } else {
          $('#welcome-text').fadeIn(300).slideDown(300);
        }
      });
    });
  </script>
</body>

</html>