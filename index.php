<?php
// Book ID Router - Check if this is a book request
$request = trim($_SERVER['REQUEST_URI'], '/');
$request = strtok($request, '?');

// Pattern to match book IDs: prefix-alphanumeric
if (preg_match('/^(nur|sec|pri|book)-[\w]+$/', $request)) {
    $book_id = $request;
    $prefix = explode('-', $book_id)[0];
    
    $document_root = $_SERVER['DOCUMENT_ROOT'];
    
    // Route based on prefix
    switch ($prefix) {
        case 'nur':
            include($document_root . '/includes/book-details-nursery.php');
            exit;
        case 'pri':
            include($document_root . '/includes/book-details-primary.php');
            exit;
        case 'book':
            include($document_root . '/includes/book-details-university.php');
            exit;
        case 'sec':
            // Determine O-Level or A-Level from database
            require_once '/home/exoterxi/config/exdata.php';
            $dbname = "exoterxi_secondary";
            
            $conn = new mysqli($servername, $username, $password, $dbname);
            if (!$conn->connect_error) {
                $sql = "(SELECT level FROM notes WHERE id = ?) UNION ALL (SELECT level FROM papers WHERE id = ?)";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param('ss', $book_id, $book_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $level = trim($row['level']);
                        $stmt->close();
                        $conn->close();
                        
                        if (strcasecmp($level, 'O-Level') === 0) {
                            include($document_root . '/includes/book-details-o-level.php');
                        } elseif (strcasecmp($level, 'A-Level') === 0) {
                            include($document_root . '/includes/book-details-a-level.php');
                        }
                        exit;
                    }
                    $stmt->close();
                }
                $conn->close();
            }
            
            header("HTTP/1.0 404 Not Found");
            echo "Book not found.";
            exit;
    }
}

// Not a book ID request - continue with homepage
$pageTitle = "Home";
$description = "Classroom at Hand - Download Lesson notes, schemes of work, work books, Holiday Packages, Tekart Learning notes, Excel Standard SST notes, Paramount notes and new curriculum lower secondary resources ";
$keywords = "exotic notes, excel sst notes, tekart learning, sharebility, sst lesson notes, sure key exams, paramount notes, schemes of work, pure mathematics backhouse, new curriculum, a level notes, biology new curriculum, competence based curriculum";
$canonical = "https://exoticnotes.com";
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<main class="mainbar">
<div class="exotic-notes-section">
    <div class="exotic-notes-container">
        <h1 class="exotic-notes-title">EXOTIC NOTES</h1>
        <p style="color: #cc0000;">
            <i class="fa-solid fa-quote-left fa-sm" style="color: green;"></i>
            <b> Classroom at Hand </b>
            <i class="fa-solid fa-quote-right fa-sm" style="color: green;"></i>
        </p>
        <p><b>Select Level</b></p>
        <div class="exotic-notes-buttons">
            <a class="exotic-notes-button" href="nursery">Nursery</a>
            <a class="exotic-notes-button" href="primary">Primary</a>
            <a class="exotic-notes-button" href="secondary">Secondary</a>
            <a class="exotic-notes-button" href="university?ref=tertiary">Tertiary</a>
            <a class="exotic-notes-button" href="university">University</a>
        </div>
        <?php include 'includes/home-search.php'; ?>
        <div>
          <a href="https://play.google.com/store/apps/details?id=com.exoticnotes.app" class="exotic-notes-app"><i class="fab fa-google-play playstore-icon"></i>Download our App</a>
        </div>
    </div>
</div>

  <div class="mainContainer">
    <div class='who-we-are'>
      <h2>WHO WE ARE</h2>
      <p><a href='/'>exoticnotes.com</a> is a home of Books, Lesson notes, Schemes of Work, Books, Holiday Packages Papers and other Learning materials needed for Quality Education</p>
      <p>Actually with exoticnotes, you have the Classroom at Hand</p>
    </div>
    <div class="choose-wrapper">
    <div class="choose-section">
      <h3 style="text-align: center; color: green;">RESOURCES</h3>
      <p style="text-align: center;">Download all useful work from class notes to papers and textbooks</p>
      <div class="choose-container">
    <!-- Schemes of Work -->
    <a class="choose-box" href="/schemes-of-work">
      <h4>Schemes of Work</h4>
      <button>Start <i class="fa-brands fa-searchengin"></i></button>
    </a>
    <!-- Holiday Packages -->
    <a class="choose-box" href="/category/holiday-packages">
      <h4>Holiday Packages</h4>
      <button>Start <i class="fa-brands fa-searchengin"></i></button>
    </a>

    <!-- Lesson Notes -->
    <a class="choose-box" href="/notes">
      <h4>Lesson Notes</h4>
      <button>Download <i class="fa-solid fa-magnifying-glass-plus"></i></button>
    </a>

    <!-- Papers -->
    <a class="choose-box" href="/papers">
      <h4>Papers</h4>
      <button>Revise <i class="fa-brands fa-readme"></i></button>
    </a>

    <!-- Books -->
    <a class="choose-box" href="/books">
      <h4>Books</h4>
      <button>Read <i class="fa-solid fa-book"></i></button>
    </a>

    <!-- Tutorials -->
    <a class="choose-box" href="/tutorials">
      <h4>Tutorials</h4>
      <button>Watch <i class="fa-solid fa-video"></i></button>
    </a>

    <!-- Research -->
    <a class="choose-box" href="books/research">
      <h4>Research</h4>
      <button>Discover <i class="fa-solid fa-magnifying-glass-dollar"></i></button>
    </a>

       <!-- NCDC -->
       <a class="choose-box" href="/category/ncdc-books">
      <h4>NCDC Books</h4>
      <button>Teach <i class="fa-solid fa-book"></i></button>
    </a>
      </div>
      </div>
    </div>
    <?php include 'includes/popular-subjects.php'; ?>
    </div>
  </div>

<?php include 'includes/footer.php'; ?>