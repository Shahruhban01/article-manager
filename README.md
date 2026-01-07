# 📝 Article Manager

A powerful, automated PHP-based article management system that creates beautiful, responsive HTML articles with automatic navigation updates across all pages.

![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue)
![License](https://img.shields.io/badge/license-MIT-green)
![Status](https://img.shields.io/badge/status-production-brightgreen)

## ✨ Features

### 🚀 Automatic Operations
- **Auto-create HTML files** - Generate fully responsive article pages instantly
- **Auto-update navigation** - Updates dropdown menu across ALL pages when you add/delete articles
- **Auto-update homepage** - Syncs articles grid on homepage automatically
- **Markdown to HTML** - Write in markdown, get beautiful HTML
- **SEO-friendly** - Proper meta tags, semantic HTML, and clean URLs

### 🎨 Design Features
- **Fully responsive** - Works perfectly on desktop, tablet, and mobile
- **Modern UI/UX** - Beautiful gradient backgrounds, smooth animations
- **Mobile hamburger menu** - Touch-friendly navigation drawer
- **Sticky navigation** - Always accessible while scrolling
- **Scroll-to-top button** - Easy navigation for long articles
- **Custom accent colors** - Choose different colors per article
- **Dark theme** - Eye-friendly dark mode design

### 🔒 Security Features
- **Secure login system** - Password-protected admin panel
- **Session management** - Automatic session handling
- **Password hashing** - Bcrypt password encryption
- **Access control** - Protected areas with authentication

### 📱 Responsive Features
- Mobile-first design approach
- Touch-optimized interactive elements
- Adaptive typography using clamp()
- Flexible grid layouts
- Optimized for all screen sizes (320px - 4K)

## 📁 File Structure

```
your-website/
├── login.php              # Secure login page
├── admin.php              # Admin interface (protected)
├── article-system.php     # Core system functions
├── articles.json          # Articles database (auto-created)
└── articles/              # Articles directory (auto-created)
    ├── index.html         # Homepage
    ├── article-1.html     # Generated articles
    ├── article-2.html
    └── ...
```

## 🚀 Installation

### Prerequisites
- PHP 7.4 or higher
- Web server (Apache, Nginx, etc.)
- Write permissions on server

### Step 1: Upload Files

Upload these files to your server:
- `login.php`
- `admin.php`
- `article-system.php`

### Step 2: Set Permissions

```bash
chmod 755 login.php
chmod 755 admin.php
chmod 755 article-system.php
chmod 777 articles/  # Allow PHP to create files
```

### Step 3: Configure Login

The default access code is: `your-secure-code-here`

To change it, generate a new hash:

```php
<?php
echo password_hash('your-new-code', PASSWORD_DEFAULT);
?>
```

Replace the hash in `login.php`:

```php
$storedHash = '$2y$10$YOUR_NEW_HASH_HERE';
```

### Step 4: Access Admin Panel

1. Go to: `https://yoursite.com/login.php`
2. Enter your access code
3. Start creating articles!

## 📖 Usage Guide

### Creating Your First Article

1. **Login** to admin panel
2. Click **"Create Article"** tab
3. Fill in the details:
   - **Title**: Your article headline
   - **Subtitle**: Brief description
   - **URL Slug**: Auto-generated from title (editable)
   - **Tag**: Category/topic (e.g., "Technology")
   - **Accent Colors**: Choose custom colors
4. Write content in **Markdown**
5. Click **"Create Article"**
6. **Done!** File created and all pages updated

### Markdown Syntax Guide

```markdown
# Main Section Heading
This becomes a section in your article (appears in table of contents)

## Subsection Heading
Smaller heading within a section

**Bold text** for emphasis
*Italic text* for subtle emphasis

- Bullet point list
- Another item

> This is a blockquote
> Great for highlighting important information

`inline code` for technical terms

1. Numbered list
2. Second item
```

### Managing Articles

**View Articles:**
- Go to "Manage Articles" tab
- See all your published articles
- View live articles by clicking "View"

**Delete Articles:**
- Click "Delete" button next to article
- Confirm deletion
- File deleted + navigation auto-updated everywhere

**Update Navigation:**
- Go to "Settings" tab
- Click "Update All Articles Navigation"
- Manually refresh navigation if needed

## 🎨 Customization

### Changing Colors

Each article can have custom accent colors:

```php
// In admin panel, use color pickers
Main Accent: #ff1744 (red)
Light Accent: #ff5983 (light red)
```

### Adding to Existing Navigation

Articles automatically appear in the dropdown:

```html
<li class="dropdown">
  <a href="#" class="dropdown-toggle">Articles <span class="arrow">▼</span></a>
  <ul class="dropdown-menu">
    <!-- Auto-populated -->
  </ul>
</li>
```

### Customizing Templates

Edit `article-system.php` function `generateArticleHTML()` to modify:
- HTML structure
- CSS styles
- JavaScript behavior
- Meta tags

## 🔐 Security Best Practices

### 1. Change Default Password

```php
// Generate new hash
php -r "echo password_hash('your-strong-password', PASSWORD_DEFAULT);"

// Update in login.php
$storedHash = 'YOUR_NEW_HASH';
```

### 2. Hide Admin Files

Use `.htaccess` to protect admin area:

```apache
<Files "admin.php">
    Require all denied
    Require ip YOUR_IP_ADDRESS
</Files>
```

### 3. Use HTTPS

Always access admin panel over HTTPS:
```
https://yoursite.com/login.php
```

### 4. Regular Backups

Backup these regularly:
- `articles.json`
- `articles/` folder
- All PHP files

## 📊 System Information

### Database Structure

`articles.json` stores article metadata:

```json
{
    "articles": [
        {
            "title": "Article Title",
            "slug": "article-slug",
            "subtitle": "Brief description",
            "tag": "Category",
            "created": "2026-01-08 01:07:18"
        }
    ]
}
```

### Auto-Update Behavior

When you **CREATE** an article:
1. ✅ HTML file created in `/articles/`
2. ✅ Added to `articles.json`
3. ✅ Navigation dropdown updated in ALL article pages
4. ✅ Navigation dropdown updated in `articles/index.html`
5. ✅ Homepage articles grid updated

When you **DELETE** an article:
1. ✅ HTML file removed
2. ✅ Removed from `articles.json`
3. ✅ Navigation updated everywhere
4. ✅ Homepage grid updated

## 🛠️ Technical Details

### Technologies Used
- **Backend**: PHP 7.4+
- **Frontend**: HTML5, CSS3, JavaScript ES6+
- **Styling**: Custom CSS with CSS Variables
- **Database**: JSON file-based
- **Authentication**: PHP Sessions + Bcrypt

### Browser Support
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

### Performance
- Lightweight (no external dependencies)
- Fast page generation (<1s)
- Optimized CSS (no frameworks)
- Minimal JavaScript
- SEO-optimized output

## 🐛 Troubleshooting

### Problem: Can't create articles

**Solution:**
```bash
# Check permissions
chmod 777 articles/

# Check if directory exists
ls -la articles/
```

### Problem: Navigation not updating

**Solution:**
1. Go to Settings tab
2. Click "Update All Articles Navigation"
3. Check file permissions

### Problem: Can't login

**Solution:**
```php
// Reset password hash in login.php
$storedHash = password_hash('new-password', PASSWORD_DEFAULT);
```

### Problem: Articles not appearing on homepage

**Solution:**
1. Ensure `articles/index.html` exists
2. Check `articles.json` for article entries
3. Manually update navigation from Settings

## 📝 Examples

### Example Article Creation

**Input:**
```markdown
# Introduction
This is my first article about web development.

## Getting Started
Here are the basics you need to know:

- Learn HTML
- Learn CSS
- Learn JavaScript

> "The only way to learn is by doing" - Anonymous
```

**Output:**
Beautiful HTML file with:
- Responsive layout
- Syntax highlighting
- Table of contents
- Mobile menu
- SEO tags
- Social sharing ready

## 🤝 Contributing

Contributions welcome! Areas for improvement:
- Image upload support
- Rich text editor
- Article search
- Categories system
- Tags management
- Article scheduling
- Multi-user support

## 📄 License

MIT License - feel free to use for personal or commercial projects.

## 💡 Tips & Tricks

### Tip 1: Organize by Tags
Use consistent tags for better organization:
- "Technology"
- "Political Analysis"
- "Tutorial"
- "Opinion"

### Tip 2: SEO-Friendly Slugs
- Use hyphens, not underscores
- Keep it short (3-5 words)
- Include main keyword
- Example: `web-development-basics`

### Tip 3: Backup Regularly
```bash
# Backup articles
tar -czf articles-backup-$(date +%Y%m%d).tar.gz articles/

# Backup database
cp articles.json articles-backup-$(date +%Y%m%d).json
```

### Tip 4: Custom CSS Per Article
Edit generated HTML to add custom styles in `<style>` block.

## 📞 Support

For issues or questions:
1. Check troubleshooting section
2. Review code comments in PHP files
3. Test with simple markdown first
4. Check file permissions

## 🎯 Roadmap

Future features planned:
- [ ] Rich text WYSIWYG editor
- [ ] Image management system
- [ ] Article preview before publish
- [ ] Draft/Published status
- [ ] Article revisions/history
- [ ] Bulk operations
- [ ] Export/Import articles
- [ ] Analytics integration

## 🌟 Credits

Created by **Ruhban Abdullah**
- Built for simple, automated article management
- Designed for developers who prefer markdown
- Optimized for modern web standards

---

**Made with ❤️ from Kashmir**

For more projects: [developerruhban.com](https://developerruhban.com)