<?php
error_reporting(0);
/**
 * Article Management System
 * Automatically creates articles and updates navigation across all pages
 */

 session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Add your login logic here
    die('Access denied');
}

// Configuration
define('ARTICLES_DIR', __DIR__ . '/articles/');
define('ARTICLES_JSON', __DIR__ . '/articles.json');
define('TEMPLATES_DIR', __DIR__ . '/templates/');

// Ensure directories exist
if (!file_exists(ARTICLES_DIR)) {
    mkdir(ARTICLES_DIR, 0755, true);
}
if (!file_exists(TEMPLATES_DIR)) {
    mkdir(TEMPLATES_DIR, 0755, true);
}

// Initialize articles JSON if not exists
if (!file_exists(ARTICLES_JSON)) {
    file_put_contents(ARTICLES_JSON, json_encode([
        'articles' => [
            [
                'title' => 'India as an Elected Dictatorship',
                'slug' => 'india-as-an-elected-dictatorship',
                'subtitle' => 'An analysis of institutional capture and democratic erosion',
                'tag' => 'Political Analysis',
                'created' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Technology & VPN Fear in Kashmir',
                'slug' => 'vpn-ban-in-kashmir',
                'subtitle' => 'A student developer\'s perspective on tech restrictions',
                'tag' => 'Technology & Society',
                'created' => date('Y-m-d H:i:s')
            ]
        ]
    ], JSON_PRETTY_PRINT));
}

/**
 * Load all articles from JSON
 */
function loadArticles() {
    $json = file_get_contents(ARTICLES_JSON);
    return json_decode($json, true);
}

/**
 * Save articles to JSON
 */
function saveArticles($data) {
    file_put_contents(ARTICLES_JSON, json_encode($data, JSON_PRETTY_PRINT));
}

/**
 * Generate navigation HTML for articles dropdown
 */
function generateNavDropdown($articles) {
    $html = '';
    foreach ($articles as $article) {
        $html .= sprintf(
            "          <li><a href=\"%s.html\">%s</a></li>\n",
            htmlspecialchars($article['slug']),
            htmlspecialchars($article['title'])
        );
    }
    return $html;
}

/**
 * Parse simple markdown to HTML
 */
function parseMarkdown($markdown) {
    $html = $markdown;
    
    // Headings
    $html = preg_replace('/^### (.*)$/m', '<h3>$1</h3>', $html);
    $html = preg_replace('/^## (.*)$/m', '<h2>$1</h2>', $html);
    $html = preg_replace('/^# (.*)$/m', '<h2>$1</h2>', $html);
    
    // Bold
    $html = preg_replace('/\*\*(.*?)\*\*/s', '<strong>$1</strong>', $html);
    
    // Italic
    $html = preg_replace('/\*(.*?)\*/s', '<em>$1</em>', $html);
    
    // Blockquotes
    $html = preg_replace('/^> (.*)$/m', '<blockquote>$1</blockquote>', $html);
    
    // Inline code
    $html = preg_replace('/`(.*?)`/', '<code>$1</code>', $html);
    
    // Lists
    $html = preg_replace('/^- (.*)$/m', '<li>$1</li>', $html);
    $html = preg_replace('/^\d+\. (.*)$/m', '<li>$1</li>', $html);
    
    // Wrap consecutive list items in ul
    $html = preg_replace('/(<li>.*<\/li>\n?)+/s', '<ul>$0</ul>', $html);
    
    // Paragraphs
    $lines = explode("\n\n", $html);
    $processed = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if (!empty($line) && !preg_match('/^<[a-z]/', $line)) {
            $processed[] = '<p>' . $line . '</p>';
        } else {
            $processed[] = $line;
        }
    }
    $html = implode("\n\n", $processed);
    
    return $html;
}

/**
 * Generate sections with IDs from markdown content
 */
function generateSections($markdown) {
    $content = parseMarkdown($markdown);
    $sections = preg_split('/<h2>/', $content);
    array_shift($sections); // Remove empty first element
    
    $sectionsHTML = '';
    $navItems = '';
    
    foreach ($sections as $section) {
        preg_match('/^(.*?)<\/h2>(.*)$/s', $section, $matches);
        if (count($matches) >= 3) {
            $heading = $matches[1];
            $content = $matches[2];
            $id = strtolower(preg_replace('/[^a-z0-9]+/', '-', $heading));
            $id = trim($id, '-');
            
            $sectionsHTML .= "      <section id=\"{$id}\">\n";
            $sectionsHTML .= "        <h2>{$heading}</h2>\n";
            $sectionsHTML .= $content;
            $sectionsHTML .= "      </section>\n\n";
            
            $navItems .= "        <li><a href=\"#{$id}\">{$heading}</a></li>\n";
        }
    }
    
    return ['sections' => $sectionsHTML, 'nav' => $navItems];
}

/**
 * Generate complete article HTML
 */
function generateArticleHTML($title, $subtitle, $slug, $markdown, $accentColor, $accentLight) {
    $data = loadArticles();
    $articlesDropdown = generateNavDropdown($data['articles']);
    $generated = generateSections($markdown);
    
    $template = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="{$subtitle}" />
  <meta name="robots" content="index, follow">
  <title>{$title} | Developer Ruhban</title>

  <style>
    :root {
      --bg-primary: #0a0e27;
      --bg-secondary: #151937;
      --bg-card: #1a1f3a;
      --text-primary: #e8eaf6;
      --text-secondary: #9fa8da;
      --text-muted: #7986cb;
      --accent: {$accentColor};
      --accent-hover: {$accentColor}dd;
      --accent-light: {$accentLight};
      --border: #283593;
      --border-light: #3949ab;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    html { scroll-behavior: smooth; font-size: 16px; }
    @media (max-width: 768px) { html { font-size: 15px; } }
    @media (max-width: 480px) { html { font-size: 14px; } }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      background: linear-gradient(135deg, var(--bg-primary) 0%, #0d1128 100%);
      color: var(--text-primary);
      line-height: 1.7;
      overflow-x: hidden;
    }

    .menu-toggle {
      display: none;
      position: fixed;
      top: 1rem;
      right: 1rem;
      z-index: 1000;
      background: var(--bg-card);
      border: 2px solid var(--border);
      border-radius: 8px;
      padding: 0.75rem;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .menu-toggle:hover { background: var(--accent); border-color: var(--accent); }
    .menu-toggle span {
      display: block;
      width: 25px;
      height: 2px;
      background: var(--text-primary);
      margin: 5px 0;
      transition: all 0.3s ease;
    }
    .menu-toggle.active span:nth-child(1) { transform: rotate(45deg) translate(7px, 7px); }
    .menu-toggle.active span:nth-child(2) { opacity: 0; }
    .menu-toggle.active span:nth-child(3) { transform: rotate(-45deg) translate(6px, -6px); }
    @media (max-width: 768px) { .menu-toggle { display: block; } }

    nav {
      position: sticky;
      top: 0;
      z-index: 999;
      background: rgba(21, 25, 55, 0.95);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--border);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }
    nav ul {
      display: flex;
      justify-content: center;
      align-items: center;
      list-style: none;
      flex-wrap: wrap;
      max-width: 1400px;
      margin: 0 auto;
      padding: 0;
    }
    nav li a {
      display: block;
      padding: 1.25rem clamp(0.75rem, 3vw, 2rem);
      color: var(--text-secondary);
      font-weight: 600;
      font-size: clamp(0.85rem, 2vw, 1rem);
      text-transform: uppercase;
      letter-spacing: 0.05em;
      transition: all 0.3s ease;
      border-bottom: 3px solid transparent;
      text-decoration: none;
    }
    nav li a:hover {
      color: var(--accent-light);
      background: rgba(255, 23, 68, 0.1);
      border-bottom-color: var(--accent);
    }

    .dropdown { position: relative; }
    .dropdown-toggle { display: flex; align-items: center; gap: 0.5rem; }
    .dropdown-toggle .arrow { font-size: 0.7rem; transition: transform 0.3s ease; }
    .dropdown:hover .arrow { transform: rotate(180deg); }
    .dropdown-menu {
      position: absolute;
      top: 100%;
      left: 0;
      background: var(--bg-card);
      border: 1px solid var(--border-light);
      border-radius: 8px;
      min-width: 250px;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      transition: all 0.3s ease;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
      z-index: 1000;
      list-style: none;
      padding: 0.5rem 0;
    }
    .dropdown:hover .dropdown-menu {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }
    .dropdown-menu li { margin: 0; }
    .dropdown-menu li a {
      padding: 1rem 1.5rem;
      font-size: 0.95rem;
      border-bottom: none;
      border-left: 3px solid transparent;
    }
    .dropdown-menu li a:hover {
      background: rgba(255, 23, 68, 0.1);
      border-left-color: var(--accent);
    }

    @media (max-width: 768px) {
      nav {
        position: fixed;
        top: 0;
        left: -100%;
        width: 280px;
        height: 100vh;
        transition: left 0.3s ease;
        overflow-y: auto;
      }
      nav.active { left: 0; }
      nav ul { flex-direction: column; padding: 5rem 0 2rem 0; }
      nav li { width: 100%; }
      nav li a {
        padding: 1.25rem 2rem;
        border-bottom: 1px solid var(--border);
        border-left: 3px solid transparent;
      }
      nav li a:hover { border-left-color: var(--accent); border-bottom-color: transparent; }
      .dropdown-menu {
        position: static;
        opacity: 1;
        visibility: visible;
        transform: none;
        background: rgba(0, 0, 0, 0.3);
        border: none;
        border-radius: 0;
        box-shadow: none;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
      }
      .dropdown.active .dropdown-menu { max-height: 500px; }
      .dropdown.active .arrow { transform: rotate(180deg); }
      .dropdown-toggle { justify-content: space-between; }
      .dropdown-menu li a { padding: 1rem 2.5rem; font-size: 0.9rem; }
    }

    .overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      z-index: 998;
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    .overlay.active { display: block; opacity: 1; }

    header {
      background: linear-gradient(135deg, rgba(21, 25, 55, 0.95) 0%, rgba(10, 14, 39, 0.98) 100%);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid var(--border);
      padding: clamp(2rem, 8vw, 5rem) 5%;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    header::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255, 23, 68, 0.1) 0%, transparent 70%);
      animation: pulse 15s ease-in-out infinite;
    }
    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 0.5; }
      50% { transform: scale(1.1); opacity: 0.8; }
    }
    .header-content { position: relative; z-index: 2; max-width: 1000px; margin: 0 auto; }
    h1 {
      font-size: clamp(1.75rem, 5vw, 3.5rem);
      font-weight: 800;
      margin-bottom: 1rem;
      background: linear-gradient(135deg, #fff 0%, var(--accent-light) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      letter-spacing: -0.02em;
      line-height: 1.2;
    }
    .subtitle {
      font-size: clamp(1rem, 2.5vw, 1.25rem);
      color: var(--text-secondary);
      line-height: 1.6;
      max-width: 800px;
      margin: 0 auto;
    }

    .container {
      max-width: 1400px;
      margin: 0 auto;
      padding: clamp(2rem, 5vw, 4rem) 5%;
      display: grid;
      grid-template-columns: 1fr;
      gap: 2rem;
    }
    @media (min-width: 1024px) {
      .container { grid-template-columns: minmax(0, 3fr) minmax(280px, 1fr); gap: 3rem; }
    }

    main {
      background: var(--bg-card);
      border: 1px solid var(--border-light);
      border-radius: 16px;
      padding: clamp(1.5rem, 5vw, 3.5rem);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
      width: 100%;
      max-width: 100%;
      overflow-x: hidden;
    }
    @media (max-width: 768px) {
      main { padding: clamp(1.25rem, 4vw, 2rem); border-radius: 12px; }
    }

    section { margin-bottom: 3.5rem; }
    section:last-child { margin-bottom: 0; }

    h2 {
      font-size: clamp(1.5rem, 4vw, 2.25rem);
      font-weight: 700;
      margin-bottom: 1.5rem;
      padding-left: 1.25rem;
      border-left: 5px solid var(--accent);
      color: var(--text-primary);
      letter-spacing: -0.01em;
      margin-top: 0;
    }
    section:not(:first-child) h2 { margin-top: 3rem; }
    h3 {
      font-size: clamp(1.25rem, 3vw, 1.75rem);
      font-weight: 600;
      margin: 2rem 0 1rem 0;
      color: var(--text-primary);
    }

    p {
      margin: 1.25rem 0;
      color: var(--text-secondary);
      font-size: clamp(0.95rem, 2.5vw, 1.125rem);
      line-height: 1.8;
      word-wrap: break-word;
      overflow-wrap: break-word;
    }
    strong { color: var(--accent-light); font-weight: 600; }
    ul, ol { padding-left: clamp(1.5rem, 4vw, 2.25rem); margin: 1.5rem 0; }
    li {
      margin-bottom: 1rem;
      color: var(--text-secondary);
      font-size: clamp(0.95rem, 2.5vw, 1.125rem);
      line-height: 1.7;
      word-wrap: break-word;
      overflow-wrap: break-word;
    }
    li::marker { color: var(--accent); font-weight: bold; }

    blockquote {
      position: relative;
      border-left: 5px solid var(--accent);
      background: linear-gradient(135deg, rgba(255, 23, 68, 0.08) 0%, rgba(255, 23, 68, 0.03) 100%);
      padding: clamp(1rem, 4vw, 2rem);
      margin: 2rem 0;
      border-radius: 0 8px 8px 0;
      font-style: italic;
      font-size: clamp(1rem, 3vw, 1.25rem);
      color: var(--text-primary);
      line-height: 1.7;
      word-wrap: break-word;
      overflow-wrap: break-word;
    }

    code {
      background: rgba(0, 0, 0, 0.5);
      color: #ffd54f;
      padding: 0.25rem 0.5rem;
      border-radius: 4px;
      font-family: 'Courier New', monospace;
      font-size: 0.9em;
      border: 1px solid rgba(255, 213, 79, 0.2);
    }

    aside {
      background: var(--bg-card);
      border: 1px solid var(--border-light);
      border-radius: 16px;
      padding: clamp(1.5rem, 4vw, 2.5rem);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
      height: fit-content;
      position: sticky;
      top: 100px;
    }
    @media (max-width: 1023px) { aside { position: static; } }
    aside h3 {
      margin: 0 0 1.5rem 0;
      font-size: clamp(1.25rem, 3vw, 1.5rem);
      color: var(--text-primary);
      padding-bottom: 1rem;
      border-bottom: 2px solid var(--accent);
    }
    .toc ul { list-style: none; padding-left: 0; }
    .toc li { margin-bottom: 0; }
    .toc li a {
      display: block;
      padding: 0.875rem 1rem;
      margin-bottom: 0.5rem;
      color: var(--text-muted);
      border-radius: 8px;
      transition: all 0.3s ease;
      font-size: clamp(0.95rem, 2vw, 1.05rem);
      border-left: 3px solid transparent;
      text-decoration: none;
    }
    .toc li a:hover {
      background: rgba(255, 23, 68, 0.1);
      color: var(--accent-light);
      border-left-color: var(--accent);
      transform: translateX(5px);
    }

    footer {
      background: var(--bg-secondary);
      border-top: 1px solid var(--border);
      padding: clamp(2rem, 5vw, 3rem) 5%;
      text-align: center;
      margin-top: 4rem;
    }
    footer p {
      color: var(--text-muted);
      font-size: clamp(0.9rem, 2vw, 1rem);
      line-height: 1.7;
      max-width: 900px;
      margin: 0 auto;
    }

    .scroll-top {
      position: fixed;
      bottom: 2rem;
      right: 2rem;
      width: 50px;
      height: 50px;
      background: var(--accent);
      border: 2px solid var(--accent);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      z-index: 100;
      box-shadow: 0 4px 15px rgba(255, 23, 68, 0.4);
    }
    .scroll-top.visible { opacity: 1; visibility: visible; }
    .scroll-top:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(255, 23, 68, 0.6); }
    .scroll-top::before { content: '↑'; color: white; font-size: 1.5rem; font-weight: bold; }
  </style>
</head>

<body>
  <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
    <span></span><span></span><span></span>
  </button>

  <div class="overlay" id="overlay"></div>

  <header>
    <div class="header-content">
      <h1>{$title}</h1>
      <p class="subtitle">{$subtitle}</p>
    </div>
  </header>

  <nav id="nav">
    <ul>
      <li><a href="index.html">Home</a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle">Articles <span class="arrow">▼</span></a>
        <ul class="dropdown-menu">
{$articlesDropdown}
        </ul>
      </li>
    </ul>
  </nav>

  <div class="container">
    <main>
{$generated['sections']}
    </main>

    <aside class="toc">
      <h3>Contents</h3>
      <ul>
{$generated['nav']}
      </ul>
    </aside>
  </div>

  <footer>
    <p>© 2026 Developer Ruhban. All views expressed are personal.</p>
  </footer>

  <div class="scroll-top" id="scrollTop"></div>

  <script>
    const menuToggle = document.getElementById('menuToggle');
    const nav = document.getElementById('nav');
    const overlay = document.getElementById('overlay');

    menuToggle.addEventListener('click', () => {
      menuToggle.classList.toggle('active');
      nav.classList.toggle('active');
      overlay.classList.toggle('active');
    });

    overlay.addEventListener('click', () => {
      menuToggle.classList.remove('active');
      nav.classList.remove('active');
      overlay.classList.remove('active');
    });

    const navLinks = nav.querySelectorAll('a');
    navLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        if (link.classList.contains('dropdown-toggle') && window.innerWidth <= 768) {
          e.preventDefault();
          link.closest('.dropdown').classList.toggle('active');
          return;
        }
        menuToggle.classList.remove('active');
        nav.classList.remove('active');
        overlay.classList.remove('active');
      });
    });

    const scrollTop = document.getElementById('scrollTop');
    window.addEventListener('scroll', () => {
      scrollTop.classList.toggle('visible', window.pageYOffset > 300);
    });
    scrollTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    const sections = document.querySelectorAll('section');
    const navLinksArray = Array.from(navLinks);
    window.addEventListener('scroll', () => {
      let current = '';
      sections.forEach(section => {
        const sectionTop = section.offsetTop;
        if (pageYOffset >= (sectionTop - 200)) {
          current = section.getAttribute('id');
        }
      });
      navLinksArray.forEach(link => {
        link.style.color = '';
        link.style.borderBottomColor = '';
        if (link.getAttribute('href') === `#\${current}`) {
          link.style.color = 'var(--accent-light)';
          link.style.borderBottomColor = 'var(--accent)';
        }
      });
    });
  </script>
</body>
</html>
HTML;
    
    return $template;
}

/**
 * Create a new article
 */
function createArticle($title, $subtitle, $slug, $tag, $markdown, $accentColor = '#ff1744', $accentLight = '#ff5983') {
    // Add article to JSON
    $data = loadArticles();
    $data['articles'][] = [
        'title' => $title,
        'slug' => $slug,
        'subtitle' => $subtitle,
        'tag' => $tag,
        'created' => date('Y-m-d H:i:s')
    ];
    saveArticles($data);
    
    // Generate and save HTML file
    $html = generateArticleHTML($title, $subtitle, $slug, $markdown, $accentColor, $accentLight);
    $filename = ARTICLES_DIR . $slug . '.html';
    file_put_contents($filename, $html);
    
    // Update all existing article files with new navigation
    updateAllArticleNavigation();
    
    return $filename;
}

/**
 * Update navigation in all existing articles AND index.html
 */
function updateAllArticleNavigation() {
    $data = loadArticles();
    $articlesDropdown = generateNavDropdown($data['articles']);
    
    // Update each article file
    foreach ($data['articles'] as $article) {
        $filename = ARTICLES_DIR . $article['slug'] . '.html';
        if (file_exists($filename)) {
            $content = file_get_contents($filename);
            
            // Replace the dropdown menu content
            $pattern = '/(<ul class="dropdown-menu">)(.*?)(<\/ul>)/s';
            $replacement = "$1\n{$articlesDropdown}        $3";
            $content = preg_replace($pattern, $replacement, $content);
            
            file_put_contents($filename, $content);
        }
    }
    
    // Update index.html navigation (in articles folder)
    $indexFile = ARTICLES_DIR . 'index.html';
    if (file_exists($indexFile)) {
        $content = file_get_contents($indexFile);
        
        // Replace the dropdown menu content
        $pattern = '/(<ul class="dropdown-menu">)(.*?)(<\/ul>)/s';
        $replacement = "$1\n{$articlesDropdown}        $3";
        $content = preg_replace($pattern, $replacement, $content);
        
        file_put_contents($indexFile, $content);
    }
    
    // Update homepage articles grid
    updateHomepageArticlesGrid();
}

/**
 * Update the articles grid on homepage (index.html in articles folder)
 */
function updateHomepageArticlesGrid() {
    $data = loadArticles();
    $indexFile = ARTICLES_DIR . 'index.html';
    
    if (!file_exists($indexFile)) {
        return;
    }
    
    $content = file_get_contents($indexFile);
    
    // Generate articles grid HTML
    $articlesGrid = '';
    foreach ($data['articles'] as $article) {
        $articlesGrid .= sprintf(
            '        <a href="%s.html" class="article-card">
          <span class="article-tag">%s</span>
          <h3>%s</h3>
          <p>%s</p>
          <div class="article-meta">
            <span>Read more</span>
          </div>
        </a>

',
            htmlspecialchars($article['slug']),
            htmlspecialchars($article['tag']),
            htmlspecialchars($article['title']),
            htmlspecialchars($article['subtitle'])
        );
    }
    
    // Replace the articles grid section
    $pattern = '/(<div class="articles-grid">)(.*?)(<\/div>\s*<\/section>)/s';
    $replacement = "$1\n{$articlesGrid}      $3";
    $content = preg_replace($pattern, $replacement, $content);
    
    file_put_contents($indexFile, $content);
}

/**
 * Delete an article
 */
function deleteArticle($slug) {
    $data = loadArticles();
    $data['articles'] = array_filter($data['articles'], function($article) use ($slug) {
        return $article['slug'] !== $slug;
    });
    $data['articles'] = array_values($data['articles']); // Re-index array
    saveArticles($data);
    
    // Delete the HTML file
    $filename = ARTICLES_DIR . $slug . '.html';
    if (file_exists($filename)) {
        unlink($filename);
    }
    
    // Update remaining articles
    updateAllArticleNavigation();
}

/**
 * List all articles
 */
function listArticles() {
    $data = loadArticles();
    return $data['articles'];
}

// Example usage:
/*
// Create a new article
$markdown = "# Introduction\n\nThis is my article content.\n\n## Section One\n\nMore content here.";
createArticle(
    'My New Article',
    'A brief description',
    'my-new-article',
    'General',
    $markdown,
    '#00bcd4',
    '#4dd0e1'
);

// List all articles
$articles = listArticles();
print_r($articles);

// Delete an article
deleteArticle('my-new-article');
*/
?>