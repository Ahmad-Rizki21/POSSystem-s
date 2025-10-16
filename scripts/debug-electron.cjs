console.log('🔍 Debugging Electron setup...');

const path = require('path');
const fs = require('fs');

// Check if electron.cjs exists
const electronJsPath = path.join(__dirname, '..', 'public', 'electron.cjs');
console.log('📁 Electron.cjs path:', electronJsPath);
console.log('📁 Electron.cjs exists:', fs.existsSync(electronJsPath));

// Check package.json
const packageJsonPath = path.join(__dirname, '..', 'package.json');
const packageJson = JSON.parse(fs.readFileSync(packageJsonPath, 'utf8'));
console.log('📦 Package main field:', packageJson.main);
console.log('📦 Package scripts:', packageJson.scripts);

// Check Node modules
const nodeModulesPath = path.join(__dirname, '..', 'node_modules', 'electron');
console.log('📁 Electron module exists:', fs.existsSync(nodeModulesPath));

// Test simple electron start
console.log('🚀 Attempting to start Electron directly...');

const { spawn } = require('child_process');

const electron = spawn('npx', ['electron', 'public/electron.cjs'], {
    stdio: 'inherit',
    cwd: path.join(__dirname, '..'),
    shell: true
});

electron.on('error', (error) => {
    console.error('❌ Electron error:', error);
});

electron.on('exit', (code) => {
    console.log('📱 Electron exited with code:', code);
});