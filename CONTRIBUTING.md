# Contributing to Article Manager

First off, thank you for considering contributing to Article Manager! 🎉

## How Can I Contribute?

### 🐛 Reporting Bugs

Before creating bug reports, please check existing issues. When creating a bug report, include:

- **Clear title and description**
- **Steps to reproduce** the behavior
- **Expected behavior**
- **Actual behavior**
- **Screenshots** (if applicable)
- **Environment details** (PHP version, server type, OS)

**Example Bug Report:**
```
Title: Navigation dropdown not updating after article deletion

Description:
When I delete an article, the navigation dropdown in other articles 
doesn't update automatically.

Steps to Reproduce:
1. Create two articles
2. Delete one article
3. Check the other article's navigation

Expected: Deleted article should be removed from dropdown
Actual: Deleted article still appears in dropdown

Environment:
- PHP 7.4
- Apache 2.4
- Ubuntu 20.04
```

### 💡 Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. Include:

- **Clear title**
- **Detailed description** of the enhancement
- **Why this enhancement would be useful**
- **Examples** of how it would work

### 🔧 Pull Requests

1. **Fork** the repository
2. **Create a branch** (`git checkout -b feature/AmazingFeature`)
3. **Make your changes**
4. **Test thoroughly**
5. **Commit** (`git commit -m 'Add some AmazingFeature'`)
6. **Push** (`git push origin feature/AmazingFeature`)
7. **Open a Pull Request**

#### Pull Request Guidelines:

- Follow existing code style
- Update documentation if needed
- Add comments for complex logic
- Test on PHP 7.4+ and 8.0+
- Ensure responsive design works
- Check security implications

### 📝 Code Style

**PHP:**
```php
// Use meaningful variable names
$articleTitle = 'My Article';

// Add comments for complex logic
// Generate unique slug from title
$slug = generateSlug($articleTitle);

// Use proper indentation (4 spaces)
function createArticle($title, $content) {
    if (empty($title)) {
        return false;
    }
    // ... rest of code
}
```

**HTML/CSS:**
```css
/* Use clear class names */
.article-card {
    /* Group related properties */
    /* Layout */
    display: flex;
    
    /* Appearance */
    background: var(--bg-card);
    border-radius: 12px;
    
    /* Spacing */
    padding: 2rem;
}
```

### 🧪 Testing

Before submitting PR, test:

- Article creation with various markdown
- Article deletion and navigation updates
- Mobile responsive design
- Different PHP versions
- Login system functionality
- Permission handling

### 📚 Documentation

Update documentation for:

- New features
- Changed behavior
- New configuration options
- API changes

## Development Setup

```bash
# Clone your fork
git clone https://github.com/YOUR_USERNAME/article-manager.git

# Create development branch
git checkout -b feature/my-feature

# Make changes and test locally
php -S localhost:8000

# Commit with clear message
git commit -m "Add: Image upload functionality"
```

## Commit Message Guidelines

Use clear, descriptive commit messages:

```
Add: New feature description
Fix: Bug fix description
Update: Documentation or dependency updates
Refactor: Code improvements
Remove: Removed feature or file
```

## Priority Areas for Contribution

### High Priority:
- 🖼️ Image upload and management
- 📝 Rich text editor (WYSIWYG)
- 🔍 Search functionality
- 📊 Analytics integration

### Medium Priority:
- 🏷️ Advanced tagging system
- 📅 Article scheduling
- 👥 Multi-user support
- 🌐 Internationalization (i18n)

### Nice to Have:
- 📤 Social media auto-posting
- 💬 Comments system
- 📧 Email notifications
- 🎨 Theme customization

## Questions?

Feel free to open an issue with the "question" label.

## Code of Conduct

Be respectful and constructive. We're all here to make Article Manager better!

---

Thank you for contributing! 🙏
