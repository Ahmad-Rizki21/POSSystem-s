# POS System - Point of Sale Application

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.0-FF2D20.svg?style=for-the-badge&logo=laravel" alt="Laravel"/>
  <img src="https://img.shields.io/badge/Vue.js-3.4-4FC08D.svg?style=for-the-badge&logo=vue.js" alt="Vue.js"/>
  <img src="https://img.shields.io/badge/Electron-38.3-47848F.svg?style=for-the-badge&logo=electron" alt="Electron"/>
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1.svg?style=for-the-badge&logo=mysql" alt="MySQL"/>
  <img src="https://img.shields.io/badge/Tailwind%20CSS-3.2-06B6D4.svg?style=for-the-badge&logo=tailwindcss" alt="Tailwind CSS"/>
</p>

<p align="center">
  <strong>A comprehensive Point of Sale (POS) system built with Laravel, Vue.js, and Electron for desktop deployment.</strong>
</p>

## 📋 Table of Contents

- [About](#about)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [API Documentation](#api-documentation)
- [Database Schema](#database-schema)
- [Testing](#testing)
- [Deployment](#deployment)
- [CI/CD](#cicd)
- [Contributing](#contributing)
- [License](#license)
- [Support](#support)

## 🚀 About

POS System is a modern, feature-rich Point of Sale application designed for small to medium-sized retail businesses. It provides a complete solution for managing sales, inventory, customers, and business analytics.

### Key Highlights
- 🖥️ **Desktop Application**: Built with Electron for cross-platform desktop deployment
- 📱 **Responsive Design**: Works seamlessly on desktop and tablet devices
- 🔄 **Real-time Updates**: Live inventory and transaction updates
- 📊 **Advanced Analytics**: Comprehensive reporting and business insights
- 🔒 **Secure**: Role-based access control and user authentication
- 🌐 **Offline Capable**: Core functionality works without internet connection

## ✨ Features

### Core POS Features
- **Sales Management**: Complete transaction processing with multiple payment methods
- **Inventory Management**: Real-time stock tracking with low-stock alerts
- **Product Catalog**: Comprehensive product management with categories and variants
- **Customer Management**: Customer database and purchase history
- **Reporting & Analytics**: Sales reports, inventory reports, and business insights
- **User Management**: Role-based access control with customizable permissions
- **Receipt Printing**: Thermal printer support for receipt printing
- **Barcode Scanner**: Integration with barcode scanners for quick product entry

### Advanced Features
- **Multi-store Support**: Manage multiple stores from a single system
- **Supplier Management**: Track suppliers and purchase orders
- **Discount & Promotion**: Flexible discount and promotion system
- **Tax Management**: Multi-tax support with automatic calculations
- **Offline Mode**: Continue operations without internet connectivity
- **Data Synchronization**: Sync data across multiple devices and locations
- **Backup & Restore**: Automated backup system with easy restoration
- **Mobile Support**: Responsive design for tablet and mobile devices

## 🛠 Tech Stack

### Backend
- **Laravel 12.0** - PHP Framework
- **MySQL 8.0** - Database
- **PHP 8.2** - Programming Language
- **Laravel Sanctum** - API Authentication
- **Laravel Inertia** - SPA Framework

### Frontend
- **Vue.js 3.4** - JavaScript Framework
- **Tailwind CSS 3.2** - CSS Framework
- **Vite 6.0** - Build Tool
- **Axios** - HTTP Client
- **Ziggy** - Laravel Route Helper

### Desktop Application
- **Electron 38.3** - Desktop App Framework
- **Electron Builder** - App Packager

### Development Tools
- **PHPUnit** - Testing Framework
- **Laravel Pint** - Code Style
- **MySQL2** - Node.js MySQL Driver

## 📋 Prerequisites

Before installing, make sure you have the following installed:

### Required Software
- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 20.0
- **MySQL** >= 8.0 or MariaDB >= 10.3
- **Git**

### Optional for Desktop Development
- **Python** 3.x (for node-gyp)
- **Visual Studio Build Tools** (Windows)
- **Xcode Command Line Tools** (macOS)

### System Requirements
- **RAM**: Minimum 4GB, Recommended 8GB+
- **Storage**: Minimum 2GB free space
- **OS**: Windows 10+, macOS 10.15+, or Ubuntu 20.04+

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/Ahmad-Rizki21/POSSystem-s.git
cd POSSystem-s
```

### 2. Install Dependencies

#### Backend Dependencies
```bash
composer install
```

#### Frontend Dependencies
```bash
npm install
```

### 3. Environment Configuration

#### Copy Environment File
```bash
cp .env.example .env
```

#### Generate Application Key
```bash
php artisan key:generate
```

#### Configure Database
Edit your `.env` file and update the database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos_system
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Database Setup

#### Create Database
```sql
CREATE DATABASE pos_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### Run Migrations
```bash
php artisan migrate
```

#### Seed Database (Optional)
```bash
php artisan db:seed
```

### 5. Build Assets
```bash
npm run build
```

### 6. Link Storage
```bash
php artisan storage:link
```

## ⚙️ Configuration

### Environment Variables

Configure the following environment variables in your `.env` file:

```env
# Application
APP_NAME="POS System"
APP_ENV=local
APP_KEY=base64:your_generated_key
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos_system
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME="${APP_NAME}"

# File Uploads
FILESYSTEM_DISK=public

# Queue Configuration
QUEUE_CONNECTION=database

# Cache
CACHE_DRIVER=file
CACHE_PREFIX=pos_system

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Electron (Desktop App)
ELECTRON_IS_DEV=true
```

### Desktop Configuration

For desktop development, create `.env.desktop`:

```env
ELECTRON_IS_DEV=true
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

## 🎯 Usage

### Development Server

#### Web Development
```bash
# Start Laravel development server
php artisan serve

# Start Vite development server (in another terminal)
npm run dev

# Or run both concurrently
npm run dev
```

#### Desktop Development
```bash
# Start Electron in development mode
npm run electron-dev

# Or build and run
npm run build
npm run electron
```

### Production Build

#### Web Production
```bash
# Optimize for production
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Desktop Production
```bash
# Build desktop application
npm run electron-pack
```

### First-Time Setup

1. **Access the Application**: Open `http://localhost:8000` in your browser
2. **Create Admin Account**: Register the first user (automatically becomes admin)
3. **Configure Store Settings**: Set up your store information, tax rates, and payment methods
4. **Add Categories**: Create product categories
5. **Add Products**: Import or manually add products to inventory
6. **Set Up Users**: Create additional user accounts with appropriate roles

### Daily Operations

1. **Start Day**: Open cash register and check inventory levels
2. **Process Sales**: Use the POS interface to process customer transactions
3. **Manage Inventory**: Monitor stock levels and reorder when necessary
4. **Generate Reports**: Review daily, weekly, or monthly sales reports
5. **End Day**: Close cash register and generate daily report

## 📚 API Documentation

Comprehensive API documentation is available in [API_DOCUMENTATION.md](./API_DOCUMENTATION.md).

### Quick API Start

1. **Generate API Token**: Use the authentication endpoint to get your API token
2. **Make API Requests**: Include the token in the Authorization header
3. **Explore Endpoints**: Check the API documentation for available endpoints

Example API call:
```bash
curl -X GET http://localhost:8000/api/products \
     -H "Authorization: Bearer YOUR_API_TOKEN" \
     -H "Accept: application/json"
```

## 🗄️ Database Schema

### Core Tables

- **users** - User accounts and authentication
- **categories** - Product categories
- **products** - Product information and inventory
- **transactions** - Sales transactions
- **transaction_items** - Transaction line items
- **customers** - Customer information
- **roles** - User roles
- **permissions** - System permissions
- **stock_adjustments** - Inventory adjustments
- **reports** - Generated reports

### Relationships

- Users → Transactions (one-to-many)
- Categories → Products (one-to-many)
- Products → Transaction Items (one-to-many)
- Transactions → Transaction Items (one-to-many)

## 🧪 Testing

### Run All Tests
```bash
php artisan test
```

### Run Specific Tests
```bash
# Run PHPUnit tests
php artisan test --testsuite=Feature

# Run JavaScript tests
npm run test

# Run with coverage
php artisan test --coverage
```

### Testing Database

Use separate database for testing:
```env
DB_CONNECTION=mysql
DB_DATABASE=pos_system_testing
```

Then run:
```bash
php artisan migrate --database=testing
```

## 🚀 Deployment

### Web Deployment

#### Using Laravel Forge
1. Connect your repository to Laravel Forge
2. Configure server and database
3. Set environment variables
4. Deploy automatically on push to main branch

#### Manual Deployment
```bash
# Clone repository on server
git clone https://github.com/Ahmad-Rizki21/POSSystem-s.git

# Install dependencies
composer install --optimize-autoloader --no-dev
npm ci --production

# Set permissions
chmod -R 755 storage bootstrap/cache

# Configure environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate --force

# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets
npm run build
```

### Desktop Deployment

#### Automated Build
The CI/CD pipeline automatically builds desktop applications when you push a tag:

```bash
# Create and push a tag
git tag v1.0.0
git push origin v1.0.0
```

#### Manual Build
```bash
# Build for current platform
npm run electron-pack

# Build for specific platforms
npm run electron-pack -- --win
npm run electron-pack -- --mac
npm run electron-pack -- --linux
```

### Environment Configuration

#### Production Environment
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=pos_system_production
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Cache
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis

# File Storage
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket
```

## 🔄 CI/CD

### GitHub Actions Workflow

This project includes comprehensive CI/CD workflows:

#### CI/CD Pipeline (`.github/workflows/ci-cd.yml`)
- **Test**: Runs PHPUnit tests, ESLint, and builds assets
- **Security**: Scans for vulnerabilities in dependencies
- **Deploy**: Deploys to production/staging environments
- **Build**: Creates desktop application packages

#### Electron Release (`.github/workflows/electron-release.yml`)
- **Multi-platform Build**: Builds for Windows, macOS, and Linux
- **Auto-release**: Creates GitHub releases on tag push
- **Artifact Upload**: Uploads build artifacts

### Workflow Triggers

- **Push to main**: Runs tests and deploys to production
- **Push to develop**: Runs tests and deploys to staging
- **Pull requests**: Runs tests and security checks
- **Tags**: Builds and releases desktop applications

### Environment Variables for CI/CD

Configure these in your GitHub repository settings:

```env
# GitHub Token (automatically provided)
GH_TOKEN

# Database credentials (for testing)
DB_USERNAME
DB_PASSWORD

# Deployment secrets
DEPLOY_HOST
DEPLOY_USER
DEPLOY_KEY
```

## 🤝 Contributing

We welcome contributions! Please follow these guidelines:

### Development Workflow

1. **Fork the Repository**
2. **Create Feature Branch**: `git checkout -b feature/amazing-feature`
3. **Make Changes**: Follow coding standards and add tests
4. **Run Tests**: Ensure all tests pass
5. **Commit Changes**: `git commit -m 'Add amazing feature'`
6. **Push to Branch**: `git push origin feature/amazing-feature`
7. **Create Pull Request**: Describe your changes and why they're needed

### Coding Standards

- Follow PSR-12 coding standards for PHP
- Use ESLint configuration for JavaScript
- Write clear, descriptive commit messages
- Add tests for new features
- Update documentation as needed

### Bug Reports

When reporting bugs, please include:
- Description of the issue
- Steps to reproduce
- Expected vs actual behavior
- Environment details (OS, PHP version, etc.)
- Screenshots if applicable

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

### Documentation
- [API Documentation](./API_DOCUMENTATION.md)
- [User Manual](./docs/USER_MANUAL.md)
- [Developer Guide](./docs/DEVELOPER_GUIDE.md)

### Community
- **GitHub Issues**: Report bugs and request features
- **Discussions**: Ask questions and share ideas
- **Wiki**: Community-maintained documentation

### Contact
- **Email**: support@pos-system.com
- **Website**: https://pos-system.com
- **Twitter**: [@possystem](https://twitter.com/possystem)

---

## 🎉 Acknowledgments

- **Laravel Team** - For the amazing PHP framework
- **Vue.js Team** - For the progressive JavaScript framework
- **Electron Team** - For enabling cross-platform desktop apps
- **Contributors** - Everyone who has contributed to this project

---

<div align="center">
  <strong>Made with ❤️ by the POS System Team</strong>
</div>