# Store POS Desktop Application

A powerful Point of Sale system built with Laravel and Electron, now available as a desktop application with embedded MySQL database.

## 🚀 Features

- ✅ **Offline Capability** - Works without internet connection
- ✅ **Embedded Database** - MySQL database included in the installer
- ✅ **Auto-Installation** - One-click installer with database setup
- ✅ **Professional UI** - Modern interface with 3D animations
- ✅ **Cross-Platform** - Windows, Mac, and Linux support
- ✅ **Auto-Updates** - Built-in update mechanism
- ✅ **Data Backup** - Automatic and manual backup options

## 📦 Installation

### Windows Installer

1. Download `StorePOS-Setup-x.x.x.exe` from releases
2. Run the installer
3. Choose installation directory
4. Create desktop shortcut (optional)
5. Launch the application

The installer will automatically:
- Install the application
- Set up embedded MySQL database
- Create necessary database tables
- Configure the application

## 🛠️ Development

### Prerequisites

- Node.js 16+
- PHP 8+
- MySQL Server (for development)

### Setup

1. Clone the repository
2. Install dependencies:
   ```bash
   npm install --legacy-peer-deps
   composer install
   ```

3. Copy environment files:
   ```bash
   cp .env.example .env
   cp .env.desktop .env
   ```

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Run database migrations:
   ```bash
   php artisan migrate
   ```

### Development Mode

Start the development server:
```bash
npm run electron-dev
```

### Build for Production

Create the installer:
```bash
npm run electron-pack
```

The installer will be created in the `dist/` directory.

## 🗄️ Database

### Embedded MySQL Configuration

- **Host**: 127.0.0.1
- **Port**: 3307
- **Database**: storepos_desktop
- **Username**: storepos
- **Password**: storepos123

### Database Location

The embedded MySQL database is stored in:
- Windows: `%APPDATA%/storepos-desktop/mysql/data/`
- Mac: `~/Library/Application Support/storepos-desktop/mysql/data/`
- Linux: `~/.config/storepos-desktop/mysql/data/`

## 🔧 Configuration

### Database Settings

You can modify database settings in `.env.desktop`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=storepos_desktop
DB_USERNAME=storepos
DB_PASSWORD=storepos123
```

### Application Settings

Application settings are stored in the `desktop_settings` table:

- `app_version`: Current application version
- `first_run`: Whether this is the first time running the app
- `backup_enabled`: Enable automatic backups
- `backup_interval`: Backup frequency (daily, weekly, monthly)
- `last_backup`: Last backup timestamp

## 🔒 Security

### Database Security

- Database is isolated to the application
- No external network access
- Encrypted credentials storage
- Automatic database initialization

### Application Security

- Code signing (production builds)
- Auto-updates with signature verification
- Sandboxed execution environment
- No external API calls in production

## 📱 Features

### Point of Sale

- Product management with barcode scanning
- Sales transaction processing
- Customer management
- Inventory tracking
- Receipt printing

### Inventory Management

- Stock adjustment tracking
- Product categorization
- Supplier management
- Low stock alerts
- Stock history reporting

### Reporting

- Sales reports
- Inventory reports
- Financial summaries
- Export to PDF/Excel
- Custom date ranges

### User Management

- Role-based access control
- User authentication
- Activity logging
- Session management

## 🚨 Troubleshooting

### Common Issues

**Application won't start:**
1. Check if MySQL Server is installed
2. Verify port 3307 is available
3. Check Windows Event Viewer for errors

**Database connection failed:**
1. Restart the application
2. Check database files in AppData
3. Reinstall the application

**Performance issues:**
1. Check available disk space
2. Close other applications
3. Restart the computer

### Logs

Application logs are stored in:
- Windows: `%APPDATA%/storepos-desktop/logs/`
- Mac: `~/Library/Logs/storepos-desktop/`
- Linux: `~/.local/share/storepos-desktop/logs/`

## 🔄 Updates

The application automatically checks for updates on startup. You can also manually check for updates in the application settings.

### Update Process

1. Application checks for updates
2. Downloads update package
3. Verifies digital signature
4. Installs update
5. Restarts application

## 📞 Support

For support and bug reports:
- Email: support@storepos.com
- Documentation: https://docs.storepos.com
- Issues: https://github.com/storepos/desktop/issues

## 📄 License

This software is licensed under the MIT License. See LICENSE file for details.

## 🤝 Contributing

We welcome contributions! Please see CONTRIBUTING.md for guidelines.

---

**Store POS Desktop** - Modern Point of Sale System for Desktop