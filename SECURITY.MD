# Security Policy

## Supported Versions

| Version | Supported          |
| ------- | ------------------ |
| 1.0.x   | :white_check_mark: |

## Reporting a Vulnerability

**Please do not report security vulnerabilities through public GitHub issues.**

Instead, please report them via email to: **contact@developerruhban.com**

Include:

- Type of vulnerability
- Full paths of source file(s)
- Location of the affected source code
- Step-by-step instructions to reproduce
- Proof-of-concept or exploit code (if possible)
- Impact of the issue

### What to Expect

- **Acknowledgment** within 48 hours
- **Status update** within 7 days
- **Fix timeline** provided if vulnerability confirmed
- **Credit** in security advisory (if desired)

## Security Best Practices

### For Users:

1. **Change Default Password**
```php
   // Generate strong password hash
   php -r "echo password_hash('your-strong-password', PASSWORD_DEFAULT);"
```

2. **Use HTTPS**
   - Always access admin panel over HTTPS
   - Configure SSL certificate

3. **Restrict Admin Access**
```apache
   # .htaccess
   <Files "admin.php">
       Require ip YOUR_IP_ADDRESS
   </Files>
```

4. **Regular Updates**
   - Keep PHP updated
   - Monitor security advisories
   - Backup regularly

5. **File Permissions**
```bash
   chmod 755 *.php
   chmod 777 articles/
   chmod 600 articles.json
```

### For Developers:

1. **Input Validation**
   - Sanitize all user inputs
   - Use `htmlspecialchars()` for output
   - Validate file uploads

2. **SQL Injection Prevention**
   - Use prepared statements (if adding DB)
   - Escape special characters

3. **XSS Prevention**
   - Sanitize markdown input
   - Escape HTML in templates
   - Use Content Security Policy

4. **CSRF Protection**
   - Implement CSRF tokens (future feature)
   - Validate request origins

5. **Session Security**
   - Use secure session configuration
   - Implement session timeout
   - Regenerate session IDs

## Known Security Features

✅ **Password Hashing** - Bcrypt encryption
✅ **Session Management** - PHP sessions
✅ **Input Sanitization** - HTML escaping
✅ **Access Control** - Login required
✅ **File Validation** - Extension checks

## Security Considerations

⚠️ **No CSRF Protection** - Add for production
⚠️ **Rate Limiting** - Implement for login
⚠️ **File Upload** - Not yet implemented
⚠️ **Multi-user** - Single user only

## Disclosure Policy

- Security issues are addressed promptly
- Public disclosure after fix is released
- Credit given to security researchers
- Timeline: 30-90 days for fixes

## Security Updates

Subscribe to security advisories:
- Watch this repository
- Enable email notifications
- Check releases regularly

---

Last Updated: January 2026