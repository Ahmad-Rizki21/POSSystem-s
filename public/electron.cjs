const { app, BrowserWindow, ipcMain, dialog, shell } = require('electron');
const path = require('path');
const fs = require('fs');
const { spawn, exec } = require('child_process');

// Fallback untuk electron-is-dev
let isDev;
try {
    isDev = require('electron-is-dev');
} catch (error) {
    console.log('electron-is-dev not available, using fallback');
    isDev = !app.isPackaged;
}

// Enable live reload for Electron in development
// Note: electron-reload is optional and will be ignored if not installed
if (isDev) {
    try {
        require('electron-reload')(__dirname, {
            electron: path.join(__dirname, '..', 'node_modules', '.bin', 'electron'),
            hardResetMethod: 'exit'
        });
    } catch (error) {
        console.log('electron-reload not available, continuing without hot reload');
    }
}

// Setup error handling
process.on('uncaughtException', (error) => {
    console.error('Uncaught Exception:', error);
    if (isDev) {
        dialog.showErrorBox('Uncaught Exception', error.message + '\n\n' + error.stack);
    }
});

process.on('unhandledRejection', (reason, promise) => {
    console.error('Unhandled Rejection at:', promise, 'reason:', reason);
    if (isDev) {
        dialog.showErrorBox('Unhandled Rejection', reason);
    }
});

class StorePOSDesktop {
    constructor() {
        this.mainWindow = null;
        this.phpServer = null;
        this.mysqlProcess = null;
        this.dataPath = app.getPath('userData');
        this.mysqlPath = path.join(this.dataPath, 'mysql');
        this.dbPath = path.join(this.dataPath, 'database');

        console.log('StorePOSDesktop initialized');
        console.log('Data path:', this.dataPath);
    }

    async initializeApp() {
        try {
            console.log('Initializing application...');

            // Create necessary directories
            this.createDirectories();

            // For now, skip embedded MySQL to focus on getting the app working
            // await this.initializeMySQL();

            // Start PHP server
            console.log('Starting PHP server...');
            await this.startPHPServer();

            // Create main window
            console.log('Creating main window...');
            this.createMainWindow();

            // Setup IPC handlers
            console.log('Setting up IPC handlers...');
            this.setupIPC();

            console.log('Application initialized successfully!');
        } catch (error) {
            console.error('Error initializing application:', error);
            this.showErrorDialog('Initialization Error', 'Failed to initialize application: ' + error.message);
        }
    }

    createDirectories() {
        const dirs = [this.dataPath, this.mysqlPath, this.dbPath];
        dirs.forEach(dir => {
            if (!fs.existsSync(dir)) {
                fs.mkdirSync(dir, { recursive: true });
            }
        });
    }

    async initializeMySQL() {
        try {
            // Check if MySQL is already initialized
            const mysqlIniPath = path.join(this.mysqlPath, 'my.ini');
            const dataDir = path.join(this.mysqlPath, 'data');

            if (!fs.existsSync(mysqlIniPath)) {
                await this.setupMySQLConfig(mysqlIniPath, dataDir);
            }

            // Start MySQL process
            await this.startMySQL();

            // Wait for MySQL to be ready
            await this.waitForMySQL();

            // Initialize database
            await this.initializeDatabase();

        } catch (error) {
            console.error('MySQL initialization failed:', error);
            this.showErrorDialog('Database Error', 'Failed to initialize database: ' + error.message);
        }
    }

    async setupMySQLConfig(mysqlIniPath, dataDir) {
        const mysqlConfig = `[mysqld]
port=3307
datadir=${dataDir.replace(/\\/g, '/')}
socket=/tmp/mysql.sock
key_buffer_size=16M
max_allowed_packet=1M
table_open_cache=64
sort_buffer_size=512K
net_buffer_length=8K
read_buffer_size=256K
read_rnd_buffer_size=512K
myisam_sort_buffer_size=8M
bind-address=127.0.0.1
skip-grant-tables=0
secure-file-priv=""

[client]
port=3307
socket=/tmp/mysql.sock`;

        fs.writeFileSync(mysqlIniPath, mysqlConfig);

        // Create data directory
        if (!fs.existsSync(dataDir)) {
            fs.mkdirSync(dataDir, { recursive: true });
        }
    }

    async startMySQL() {
        return new Promise((resolve, reject) => {
            // Try to find MySQL executable
            const mysqlPaths = [
                path.join(process.env.ProgramFiles || 'C:\\Program Files', 'MySQL', 'MySQL Server 8.0', 'bin', 'mysqld.exe'),
                path.join(process.env.ProgramFiles || 'C:\\Program Files', 'MySQL', 'MySQL Server 5.7', 'bin', 'mysqld.exe'),
                path.join(this.mysqlPath, 'mysqld.exe'),
                'mysqld'
            ];

            let mysqlExecutable = null;
            for (const mysqlPath of mysqlPaths) {
                if (fs.existsSync(mysqlPath) || mysqlPath === 'mysqld') {
                    mysqlExecutable = mysqlPath;
                    break;
                }
            }

            if (!mysqlExecutable) {
                throw new Error('MySQL executable not found. Please install MySQL Server or use bundled MySQL.');
            }

            const args = [
                '--defaults-file=' + path.join(this.mysqlPath, 'my.ini'),
                '--console',
                '--skip-grant-tables=0'
            ];

            this.mysqlProcess = spawn(mysqlExecutable, args, {
                stdio: ['pipe', 'pipe', 'pipe']
            });

            this.mysqlProcess.stdout.on('data', (data) => {
                console.log('MySQL stdout:', data.toString());
            });

            this.mysqlProcess.stderr.on('data', (data) => {
                console.log('MySQL stderr:', data.toString());
            });

            this.mysqlProcess.on('error', (error) => {
                console.error('MySQL process error:', error);
                reject(error);
            });

            this.mysqlProcess.on('exit', (code) => {
                console.log('MySQL process exited with code:', code);
            });

            // Give MySQL time to start
            setTimeout(resolve, 3000);
        });
    }

    async waitForMySQL() {
        return new Promise((resolve, reject) => {
            const maxAttempts = 30;
            let attempts = 0;

            const checkMySQL = async () => {
                try {
                    const mysql = require('mysql2/promise');
                    const connection = await mysql.createConnection({
                        host: '127.0.0.1',
                        port: 3307,
                        user: 'root',
                        password: ''
                    });

                    console.log('MySQL is ready!');
                    await connection.end();
                    resolve();
                } catch (error) {
                    attempts++;
                    if (attempts >= maxAttempts) {
                        reject(new Error('MySQL failed to start after 30 attempts'));
                        return;
                    }
                    console.log(`Waiting for MySQL... (${attempts}/${maxAttempts})`);
                    setTimeout(checkMySQL, 1000);
                }
            };

            checkMySQL();
        });
    }

    async initializeDatabase() {
        try {
            const mysql = require('mysql2/promise');
            const connection = await mysql.createConnection({
                host: '127.0.0.1',
                port: 3307,
                user: 'root',
                password: ''
            });

            // Create database if not exists
            await connection.query('CREATE DATABASE IF NOT EXISTS storepos_desktop');
            await connection.query('CREATE DATABASE IF NOT EXISTS storepos');

            // Create app user if not exists
            await connection.query(`
                CREATE USER IF NOT EXISTS 'storepos'@'localhost' IDENTIFIED BY 'storepos123'
            `);

            // Grant permissions
            await connection.query(`
                GRANT ALL PRIVILEGES ON storepos_desktop.* TO 'storepos'@'localhost'
            `);
            await connection.query(`
                GRANT ALL PRIVILEGES ON storepos.* TO 'storepos'@'localhost'
            `);

            await connection.query('FLUSH PRIVILEGES');
            await connection.end();

            console.log('Database initialized successfully');
        } catch (error) {
            console.error('Database initialization error:', error);
            throw error;
        }
    }

    async findPHPExecutable() {
        const possiblePaths = [
            // Cek di PATH
            'php',
            // XAMPP paths
            'C:\\xampp\\php\\php.exe',
            'C:\\laragon\\bin\\php\\php-8.2.13-Win32-vs16-x64\\php.exe',
            'C:\\laragon\\bin\\php\\php-8.1.23-Win32-vs16-x64\\php.exe',
            'C:\\laragon\\bin\\php\\php-8.0.30-Win32-vs16-x64\\php.exe',
            'C:\\laragon\\bin\\php\\php-7.4.33-Win32-vs16-x64\\php.exe',
            // WampServer paths
            'C:\\wamp64\\bin\\php\\php8.2.13\\php.exe',
            'C:\\wamp\\bin\\php\\php8.2.13\\php.exe',
            // Default PHP installations
            path.join(process.env.ProgramFiles || 'C:\\Program Files', 'PHP', 'php.exe'),
            path.join(process.env['Program Files (x86)'] || 'C:\\Program Files (x86)', 'PHP', 'php.exe'),
            // Check for different PHP versions in Program Files
            path.join(process.env.ProgramFiles || 'C:\\Program Files', 'PHP', 'php8.2', 'php.exe'),
            path.join(process.env.ProgramFiles || 'C:\\Program Files', 'PHP', 'php8.1', 'php.exe'),
            path.join(process.env.ProgramFiles || 'C:\\Program Files', 'PHP', 'php8.0', 'php.exe'),
            path.join(process.env['Program Files (x86)'] || 'C:\\Program Files (x86)', 'PHP', 'php8.2', 'php.exe'),
            path.join(process.env['Program Files (x86)'] || 'C:\\Program Files (x86)', 'PHP', 'php8.1', 'php.exe'),
            path.join(process.env['Program Files (x86)'] || 'C:\\Program Files (x86)', 'PHP', 'php8.0', 'php.exe'),
        ];

        console.log('Searching for PHP executable...');

        for (const phpPath of possiblePaths) {
            try {
                console.log('Checking:', phpPath);
                if (fs.existsSync(phpPath)) {
                    console.log('Found PHP executable:', phpPath);
                    return phpPath;
                }
            } catch (error) {
                // Continue checking other paths
            }
        }

        // Try to find php in common installation directories using glob-like approach
        const commonInstallDirs = [
            'C:\\laragon\\bin\\php\\',
            'C:\\xampp\\php\\',
            'C:\\wamp64\\bin\\php\\',
            'C:\\wamp\\bin\\php\\',
            path.join(process.env.ProgramFiles || 'C:\\Program Files', 'PHP\\'),
            path.join(process.env['Program Files (x86)'] || 'C:\\Program Files (x86)', 'PHP\\'),
        ];

        for (const baseDir of commonInstallDirs) {
            try {
                if (fs.existsSync(baseDir)) {
                    const phpDirs = fs.readdirSync(baseDir);
                    for (const phpDir of phpDirs) {
                        if (phpDir.toLowerCase().startsWith('php')) {
                            const phpExe = path.join(baseDir, phpDir, 'php.exe');
                            if (fs.existsSync(phpExe)) {
                                console.log('Found PHP executable:', phpExe);
                                return phpExe;
                            }
                        }
                    }
                }
            } catch (error) {
                // Continue checking other directories
            }
        }

        return null;
    }

    async startPHPServer() {
        return new Promise(async (resolve, reject) => {
            try {
                const phpExecutable = await this.findPHPExecutable();

                if (!phpExecutable) {
                    const errorMessage = 'PHP executable not found. Please install PHP and ensure it is in your PATH, or install XAMPP/Laragon/WampServer.';
                    console.error(errorMessage);
                    this.showErrorDialog('PHP Not Found', errorMessage);
                    reject(new Error(errorMessage));
                    return;
                }

                const phpArgs = ['artisan', 'serve', '--host=127.0.0.1', '--port=8001'];
                console.log('Starting PHP server with executable:', phpExecutable);
                console.log('PHP args:', phpArgs);
                console.log('Working directory:', path.join(__dirname, '..'));

                this.phpServer = spawn(phpExecutable, phpArgs, {
                    cwd: path.join(__dirname, '..'),
                    stdio: ['pipe', 'pipe', 'pipe']
                });

                this.phpServer.stdout.on('data', (data) => {
                    const output = data.toString();
                    console.log('PHP server stdout:', output);
                    if (output.includes('Server running on')) {
                        console.log('PHP server is ready!');
                        resolve();
                    }
                });

                this.phpServer.stderr.on('data', (data) => {
                    const output = data.toString();
                    console.log('PHP server stderr:', output);
                });

                this.phpServer.on('error', (error) => {
                    console.error('PHP server process error:', error);
                    if (error.code === 'ENOENT') {
                        const errorMessage = `PHP executable not found at: ${phpExecutable}. Please ensure PHP is properly installed.`;
                        this.showErrorDialog('PHP Error', errorMessage);
                        reject(new Error(errorMessage));
                    } else {
                        reject(error);
                    }
                });

                this.phpServer.on('exit', (code) => {
                    console.log('PHP server exited with code:', code);
                    if (code !== 0) {
                        console.error('PHP server exited with non-zero code:', code);
                    }
                });

                // Wait for server to start
                setTimeout(() => {
                    if (!this.phpServer.killed) {
                        console.log('PHP server timeout - checking if it started');
                        // Check if server is actually running by making a simple HTTP request
                        const http = require('http');
                        const req = http.request({
                            hostname: '127.0.0.1',
                            port: 8001,
                            path: '/',
                            method: 'HEAD',
                            timeout: 1000
                        }, (res) => {
                            console.log('PHP server is responding!');
                            resolve();
                        });

                        req.on('error', () => {
                            console.log('PHP server is not responding yet, but proceeding');
                            resolve();
                        });

                        req.on('timeout', () => {
                            req.destroy();
                            console.log('PHP server request timeout, but proceeding');
                            resolve();
                        });

                        req.end();
                    }
                }, 5000);
            } catch (error) {
                console.error('Error in startPHPServer:', error);
                reject(error);
            }
        });
    }

    createMainWindow() {
        try {
            console.log('Creating BrowserWindow...');
            this.mainWindow = new BrowserWindow({
                width: 1200,
                height: 800,
                minWidth: 800,
                minHeight: 600,
                show: false,
                icon: path.join(__dirname, 'icon.ico'),
                webPreferences: {
                    nodeIntegration: false,
                    contextIsolation: true,
                    enableRemoteModule: false,
                    webSecurity: true,
                    preload: path.join(__dirname, 'preload.js'),
                    // Mengatasi error Autofill.enable
                    autoplayPolicy: 'user-gesture-required',
                    // Disable features yang tidak diperlukan
                    backgroundThrottling: false,
                    offscreen: false
                },
                titleBarStyle: 'default',
                autoHideMenuBar: true
            });

            console.log('BrowserWindow created successfully');

            // Filter console messages untuk menyembunyikan error yang tidak penting
            this.mainWindow.webContents.on('console-message', (event, level, message, line, sourceId) => {
                // Sembunyikan error Autofill.enable yang tidak berbahaya
                if (message.includes('Autofill.enable') || message.includes("wasn't found")) {
                    event.preventDefault();
                    return;
                }
            });

            // Handle web contents errors
            this.mainWindow.webContents.on('did-fail-load', (event, errorCode, errorDescription, validatedURL) => {
                console.error('Failed to load URL:', validatedURL, 'Error:', errorDescription);
                this.showErrorDialog('Load Error', `Failed to load application: ${errorDescription}`);
            });

            // Load the app
            const startUrl = isDev
                ? 'http://localhost:8001'  // Laravel development server
                : `file://${path.join(__dirname, '../build/index.html')}`;

            console.log('Loading URL:', startUrl);
            this.mainWindow.loadURL(startUrl);

            // Show window when ready
            this.mainWindow.once('ready-to-show', () => {
                console.log('Window ready to show');
                this.mainWindow.show();
                this.mainWindow.center();

                // Uncomment to open DevTools automatically in development
                // if (isDev) {
                //     this.mainWindow.webContents.openDevTools();
                // }
            });

            // Handle window close
            this.mainWindow.on('closed', () => {
                console.log('Window closed');
                this.mainWindow = null;
            });

            // Prevent navigation to external sites
            this.mainWindow.webContents.on('will-navigate', (event, navigationUrl) => {
                const parsedUrl = new URL(navigationUrl);
                if (parsedUrl.origin !== 'http://localhost:8001') {
                    event.preventDefault();
                    shell.openExternal(navigationUrl);
                }
            });

        } catch (error) {
            console.error('Error creating main window:', error);
            this.showErrorDialog('Window Creation Error', 'Failed to create main window: ' + error.message);
        }
    }

    setupIPC() {
        // Get app version
        ipcMain.handle('get-app-version', () => {
            return app.getVersion();
        });

        // Get database info
        ipcMain.handle('get-database-info', () => {
            return {
                host: '127.0.0.1',
                port: 3307,
                database: 'storepos_desktop',
                username: 'storepos',
                password: 'storepos123'
            };
        });

        // Restart app
        ipcMain.handle('restart-app', () => {
            app.relaunch();
            app.exit();
        });

        // Show message box
        ipcMain.handle('show-message', async (event, { type, title, message }) => {
            const result = await dialog.showMessageBox(this.mainWindow, {
                type,
                title,
                message,
                buttons: ['OK']
            });
            return result;
        });
    }

    showErrorDialog(title, message) {
        dialog.showErrorBox(title, message);
    }

    async cleanup() {
        // Stop MySQL (if it was started)
        if (this.mysqlProcess) {
            this.mysqlProcess.kill();
        }

        // Stop PHP server
        if (this.phpServer) {
            this.phpServer.kill();
        }
    }
}

// App event handlers
const storePOS = new StorePOSDesktop();

app.whenReady().then(async () => {
    try {
        console.log('App is ready, initializing...');
        await storePOS.initializeApp();
        console.log('App initialization completed');
    } catch (error) {
        console.error('Error during app initialization:', error);
        app.quit();
    }
}).catch(error => {
    console.error('Error in app.whenReady():', error);
    app.quit();
});

app.on('window-all-closed', () => {
    console.log('All windows closed');
    if (process.platform !== 'darwin') {
        app.quit();
    }
});

app.on('activate', () => {
    console.log('App activated');
    if (BrowserWindow.getAllWindows().length === 0) {
        console.log('Creating new window on activate');
        storePOS.createMainWindow();
    }
});

app.on('before-quit', async (event) => {
    console.log('App is about to quit');
    try {
        await storePOS.cleanup();
        console.log('Cleanup completed');
    } catch (error) {
        console.error('Error during cleanup:', error);
    }
});

// Handle certificate errors (for development)
app.on('certificate-error', (event, webContents, url, error, certificate, callback) => {
    console.log('Certificate error for URL:', url, 'Error:', error);
    if (isDev) {
        // Allow self-signed certificates in development
        event.preventDefault();
        callback(true);
    } else {
        callback(false);
    }
});