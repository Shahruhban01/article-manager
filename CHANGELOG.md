# Changelog

All notable changes to Article Manager will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-01-08

### Added
- ✨ Initial release of Article Manager
- 📝 Markdown to HTML article generation
- 🔄 Automatic navigation updates across all pages
- 🏠 Homepage integration with articles grid
- 🔐 Secure login system with bcrypt
- 🎨 Custom accent colors per article
- 📱 Fully responsive design
- 🔍 Table of contents generation
- 📊 JSON-based article database
- ⚙️ Admin panel with three tabs (Create, Manage, Settings)
- 🗑️ Article deletion with auto-cleanup
- 🔄 Manual navigation update option
- 📋 Article metadata management
- 🎯 SEO-friendly HTML output
- 📱 Mobile hamburger menu
- ⬆️ Scroll-to-top button
- 🎭 Smooth animations and transitions
- 📖 Comprehensive documentation

### Features
- Auto-generate URL slugs from titles
- Markdown syntax support (headings, bold, italic, lists, blockquotes, code)
- Section-based navigation
- Sticky navigation bar
- Dropdown articles menu
- Password visibility toggle
- Loading states for forms
- Error message animations
- Session management
- File permission checks

### Security
- Bcrypt password hashing
- Session-based authentication
- Input sanitization
- HTML escaping
- Secure file operations

### Design
- Modern dark theme
- Gradient backgrounds
- Glassmorphism effects
- Responsive typography
- Touch-friendly mobile UI
- Accessible color contrast

## [Unreleased]

### Planned Features
- 🖼️ Image upload and management
- 📝 Rich text WYSIWYG editor
- 🔍 Article search functionality
- 🏷️ Advanced tagging system
- 📅 Article scheduling
- 👥 Multi-user support
- 📊 Analytics integration
- 💾 Draft/Published status
- 📋 Article revisions history
- 📤 Bulk operations
- 📁 Export/Import functionality
- 🌐 Internationalization (i18n)
- 🎨 Theme customization
- 💬 Comments system
- 📧 Email notifications
- 🔗 Social media integration

### Known Issues
- None reported yet

---

## Version History

- **1.0.0** - Initial Release (January 8, 2026)

## How to Update
```bash
# Backup your data
cp articles.json articles.json.backup
tar -czf articles-backup.tar.gz articles/

# Download latest release
git pull origin main

# Check for breaking changes in CHANGELOG
```

---

For detailed changes, see [GitHub Releases](https://github.com/developerruhban/article-manager/releases)