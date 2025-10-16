const fs = require('fs');
const path = require('path');

console.log('🚀 Building Store POS Desktop App...');

// Create necessary directories
const buildDir = path.join(__dirname, 'public', 'build');
if (!fs.existsSync(buildDir)) {
    fs.mkdirSync(buildDir, { recursive: true });
}

// Create environment file for production
const envContent = fs.readFileSync('.env.desktop', 'utf8');
const prodEnvContent = envContent
    .replace('APP_DEBUG=false', 'APP_DEBUG=false')
    .replace('APP_ENV=production', 'APP_ENV=production');

fs.writeFileSync(path.join(buildDir, '.env'), prodEnvContent);

// Create startup script
const startupScript = `@echo off
echo Starting Store POS Desktop...
cd /d "%~dp0"
echo Initializing database...
node electron.js
pause`;

fs.writeFileSync(path.join(buildDir, 'start.bat'), startupScript);

console.log('✅ Build completed!');
console.log('📦 Run: npm run electron-pack to create installer');