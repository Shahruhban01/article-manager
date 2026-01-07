<?php
error_reporting(0);
/**
 * Article Manager Web Interface
 */

session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Add your login logic here
    die('Access denied');
}
require_once 'article-system.php';

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                try {
                    $filename = createArticle(
                        $_POST['title'],
                        $_POST['subtitle'],
                        $_POST['slug'],
                        $_POST['tag'],
                        $_POST['markdown'],
                        $_POST['accentColor'],
                        $_POST['accentLight']
                    );
                    $message = "Article created successfully! File: " . basename($filename);
                    $messageType = 'success';
                } catch (Exception $e) {
                    $message = "Error: " . $e->getMessage();
                    $messageType = 'error';
                }
                break;
                
            case 'delete':
                try {
                    deleteArticle($_POST['slug']);
                    $message = "Article deleted successfully!";
                    $messageType = 'success';
                } catch (Exception $e) {
                    $message = "Error: " . $e->getMessage();
                    $messageType = 'error';
                }
                break;
                
            case 'update_all':
                try {
                    updateAllArticleNavigation();
                    $message = "All articles updated with latest navigation!";
                    $messageType = 'success';
                } catch (Exception $e) {
                    $message = "Error: " . $e->getMessage();
                    $messageType = 'error';
                }
                break;
        }
    }
}

$articles = listArticles();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Article Manager | Developer Ruhban</title>

  <style>
    :root {
      --bg-primary: #0a0e27;
      --bg-secondary: #151937;
      --bg-card: #1a1f3a;
      --text-primary: #e8eaf6;
      --text-secondary: #9fa8da;
      --text-muted: #7986cb;
      --accent: #7c4dff;
      --accent-hover: #651fff;
      --border: #283593;
      --border-light: #3949ab;
      --success: #00e676;
      --error: #ff1744;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    html { scroll-behavior: smooth; font-size: 16px; }
    
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      background: linear-gradient(135deg, var(--bg-primary) 0%, #0d1128 100%);
      color: var(--text-primary);
      line-height: 1.7;
      padding: 2rem;
    }

    .container { max-width: 1200px; margin: 0 auto; }

    h1 {
      font-size: clamp(2rem, 5vw, 3rem);
      font-weight: 800;
      margin-bottom: 1rem;
      background: linear-gradient(135deg, #fff 0%, var(--accent) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      text-align: center;
    }

    .subtitle {
      text-align: center;
      color: var(--text-secondary);
      margin-bottom: 3rem;
      font-size: 1.1rem;
    }

    .alert {
      padding: 1rem 1.5rem;
      border-radius: 8px;
      margin-bottom: 2rem;
      animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .alert.success {
      background: rgba(0, 230, 118, 0.1);
      border: 1px solid var(--success);
      color: var(--success);
    }

    .alert.error {
      background: rgba(255, 23, 68, 0.1);
      border: 1px solid var(--error);
      color: var(--error);
    }

    .tabs {
      display: flex;
      gap: 1rem;
      margin-bottom: 2rem;
      border-bottom: 2px solid var(--border);
    }

    .tab {
      padding: 1rem 2rem;
      background: transparent;
      border: none;
      color: var(--text-secondary);
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
      border-bottom: 3px solid transparent;
    }

    .tab.active {
      color: var(--accent);
      border-bottom-color: var(--accent);
    }

    .tab-content { display: none; }
    .tab-content.active { display: block; }

    .panel {
      background: var(--bg-card);
      border: 1px solid var(--border-light);
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
      margin-bottom: 2rem;
    }

    .panel h2 {
      color: var(--accent);
      margin-bottom: 1.5rem;
      font-size: 1.5rem;
    }

    .form-group { margin-bottom: 1.5rem; }

    label {
      display: block;
      color: var(--text-secondary);
      font-weight: 600;
      margin-bottom: 0.5rem;
      font-size: 0.95rem;
    }

    input, textarea, select {
      width: 100%;
      padding: 0.875rem;
      background: rgba(0, 0, 0, 0.3);
      border: 1px solid var(--border);
      border-radius: 8px;
      color: var(--text-primary);
      font-size: 1rem;
      font-family: inherit;
      transition: all 0.3s ease;
    }

    input:focus, textarea:focus, select:focus {
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(124, 77, 255, 0.1);
    }

    textarea {
      resize: vertical;
      min-height: 300px;
      font-family: 'Courier New', monospace;
      line-height: 1.6;
    }

    .color-picker-group {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    input[type="color"] {
      height: 50px;
      cursor: pointer;
      padding: 0.25rem;
    }

    .btn {
      display: inline-block;
      padding: 1rem 2rem;
      background: var(--accent);
      color: #fff;
      border: none;
      border-radius: 12px;
      font-weight: 700;
      font-size: 1rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(124, 77, 255, 0.3);
    }

    .btn:hover {
      background: var(--accent-hover);
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(124, 77, 255, 0.5);
    }

    .btn-secondary {
      background: transparent;
      border: 2px solid var(--accent);
      color: var(--accent);
    }

    .btn-secondary:hover {
      background: rgba(124, 77, 255, 0.1);
    }

    .btn-danger {
      background: var(--error);
      color: #fff;
    }

    .btn-danger:hover {
      background: #d50032;
    }

    .btn-small {
      padding: 0.5rem 1rem;
      font-size: 0.9rem;
    }

    .help-text {
      font-size: 0.85rem;
      color: var(--text-muted);
      margin-top: 0.5rem;
    }

    .articles-list {
      display: grid;
      gap: 1rem;
    }

    .article-item {
      background: rgba(0, 0, 0, 0.3);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 1.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .article-info h3 {
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }

    .article-info p {
      color: var(--text-muted);
      font-size: 0.9rem;
    }

    .article-actions {
      display: flex;
      gap: 0.5rem;
    }

    code {
      background: rgba(124, 77, 255, 0.2);
      padding: 0.2rem 0.5rem;
      border-radius: 4px;
      font-family: 'Courier New', monospace;
      font-size: 0.9em;
      color: var(--accent);
    }

    .markdown-guide {
      background: rgba(124, 77, 255, 0.05);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1rem;
    }

    .markdown-guide h3 {
      color: var(--accent);
      font-size: 1rem;
      margin-bottom: 0.75rem;
    }

    .markdown-guide ul {
      list-style: none;
      padding: 0;
    }

    .markdown-guide li {
      padding: 0.25rem 0;
      color: var(--text-secondary);
      font-size: 0.9rem;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>📝 Article Manager</h1>
    <p class="subtitle">Create and manage your articles with automatic navigation updates</p>

    <?php if ($message): ?>
      <div class="alert <?php echo $messageType; ?>">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <div class="tabs">
      <button class="tab active" onclick="switchTab('create')">Create Article</button>
      <button class="tab" onclick="switchTab('manage')">Manage Articles</button>
      <button class="tab" onclick="switchTab('settings')">Settings</button>
    </div>

    <!-- Create Article Tab -->
    <div id="create" class="tab-content active">
      <div class="panel">
        <h2>Create New Article</h2>

        <form method="POST" action="">
          <input type="hidden" name="action" value="create">

          <div class="form-group">
            <label for="title">Article Title *</label>
            <input type="text" id="title" name="title" required>
            <p class="help-text">This will be the main heading of your article</p>
          </div>

          <div class="form-group">
            <label for="subtitle">Subtitle</label>
            <input type="text" id="subtitle" name="subtitle">
            <p class="help-text">Brief description or tagline</p>
          </div>

          <div class="form-group">
            <label for="slug">URL Slug *</label>
            <input type="text" id="slug" name="slug" required>
            <p class="help-text">This will be your filename: <code>slug.html</code></p>
          </div>

          <div class="form-group">
            <label for="tag">Article Tag</label>
            <input type="text" id="tag" name="tag">
            <p class="help-text">e.g., Political Analysis, Technology</p>
          </div>

          <div class="form-group">
            <label>Accent Colors</label>
            <div class="color-picker-group">
              <div>
                <input type="color" id="accentColor" name="accentColor" value="#ff1744">
                <p class="help-text">Main accent color</p>
              </div>
              <div>
                <input type="color" id="accentLight" name="accentLight" value="#ff5983">
                <p class="help-text">Light accent color</p>
              </div>
            </div>
          </div>

          <div class="markdown-guide">
            <h3>Markdown Syntax Guide</h3>
            <ul>
              <li><code># Heading</code> - Creates a section (will appear in navigation)</li>
              <li><code>## Subheading</code> - Subsection heading</li>
              <li><code>**bold text**</code> - Bold text</li>
              <li><code>*italic text*</code> - Italic text</li>
              <li><code>- List item</code> - Bullet point</li>
              <li><code>> Quote</code> - Blockquote</li>
              <li><code>`code`</code> - Inline code</li>
            </ul>
          </div>

          <div class="form-group">
            <label for="markdown">Article Content (Markdown) *</label>
            <textarea id="markdown" name="markdown" required placeholder="# Introduction

This is your first paragraph.

## Section Title

- List item 1
- List item 2

> This is a blockquote

**Bold text** and *italic text*"></textarea>
            <p class="help-text">Write your content in markdown format</p>
          </div>

          <button type="submit" class="btn">Create Article</button>
        </form>
      </div>
    </div>

    <!-- Manage Articles Tab -->
    <div id="manage" class="tab-content">
      <div class="panel">
        <h2>Your Articles</h2>
        
        <div class="articles-list">
          <?php if (empty($articles)): ?>
            <p style="color: var(--text-muted);">No articles yet. Create your first article!</p>
          <?php else: ?>
            <?php foreach ($articles as $article): ?>
              <div class="article-item">
                <div class="article-info">
                  <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                  <p>
                    <strong>Slug:</strong> <?php echo htmlspecialchars($article['slug']); ?>.html | 
                    <strong>Tag:</strong> <?php echo htmlspecialchars($article['tag']); ?> | 
                    <strong>Created:</strong> <?php echo date('M d, Y', strtotime($article['created'])); ?>
                  </p>
                </div>
                <div class="article-actions">
                  <a href="articles/<?php echo htmlspecialchars($article['slug']); ?>.html" 
                     target="_blank" class="btn btn-secondary btn-small">View</a>
                  <form method="POST" style="display: inline;" 
                        onsubmit="return confirm('Are you sure you want to delete this article?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="slug" value="<?php echo htmlspecialchars($article['slug']); ?>">
                    <button type="submit" class="btn btn-danger btn-small">Delete</button>
                  </form>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Settings Tab -->
    <div id="settings" class="tab-content">
      <div class="panel">
        <h2>System Settings</h2>
        
        <h3 style="color: var(--text-secondary); margin-bottom: 1rem;">Update Navigation</h3>
        <p style="color: var(--text-muted); margin-bottom: 1rem;">
          If you manually edited any articles, use this to update all navigation dropdowns with the latest articles list.
        </p>
        <form method="POST">
          <input type="hidden" name="action" value="update_all">
          <button type="submit" class="btn btn-secondary">Update All Articles Navigation</button>
        </form>

        <hr style="border: none; border-top: 1px solid var(--border); margin: 2rem 0;">

        <h3 style="color: var(--text-secondary); margin-bottom: 1rem;">System Info</h3>
        <p style="color: var(--text-muted);">
          <strong>Articles Directory:</strong> <?php echo ARTICLES_DIR; ?><br>
          <strong>Index File:</strong> <?php echo ARTICLES_DIR . 'index.html'; ?><br>
          <strong>Total Articles:</strong> <?php echo count($articles); ?><br>
          <strong>JSON Database:</strong> <?php echo ARTICLES_JSON; ?>
        </p>

        <hr style="border: none; border-top: 1px solid var(--border); margin: 2rem 0;">

        <h3 style="color: var(--text-secondary); margin-bottom: 1rem;">📝 Important Notes</h3>
        <ul style="color: var(--text-muted); padding-left: 1.5rem; line-height: 2;">
          <li>New articles are automatically added to navigation dropdown</li>
          <li>Homepage (index.html) in articles/ folder is auto-updated</li>
          <li>All article pages get updated navigation when you create/delete</li>
          <li>Article links are relative (same folder as index.html)</li>
        </ul>
      </div>
    </div>
  </div>

  <script>
    function switchTab(tabName) {
      // Hide all tabs
      document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
      });
      document.querySelectorAll('.tab').forEach(tab => {
        tab.classList.remove('active');
      });

      // Show selected tab
      document.getElementById(tabName).classList.add('active');
      event.target.classList.add('active');
    }

    // Auto-generate slug from title
    document.getElementById('title').addEventListener('input', (e) => {
      const slug = e.target.value
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .substring(0, 50);
      document.getElementById('slug').value = slug;
    });
  </script>
</body>
</html>