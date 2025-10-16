const { spawn } = require('child_process');
const http = require('http');
const path = require('path');

console.log('🚀 Starting Store POS Desktop Development Mode...');

// Function to check if port is ready
function checkPort(port) {
    return new Promise((resolve) => {
        const req = http.request({
            hostname: 'localhost',
            port: port,
            path: '/',
            method: 'GET',
            timeout: 1000
        }, (res) => {
            resolve(true);
        });

        req.on('error', () => {
            resolve(false);
        });

        req.on('timeout', () => {
            req.destroy();
            resolve(false);
        });

        req.end();
    });
}

// Function to wait for Vite
async function waitForVite() {
    console.log('🔍 Waiting for Vite development server...');

    const ports = [5173, 5174, 5175, 5176, 5177];

    for (let i = 0; i < 30; i++) { // Try for 30 seconds
        for (const port of ports) {
            const isReady = await checkPort(port);
            if (isReady) {
                console.log(`✅ Vite server ready on port ${port}!`);
                return port;
            }
        }

        // Wait 1 second before retrying
        await new Promise(resolve => setTimeout(resolve, 1000));
        console.log(`⏳ Still waiting for Vite... (${i + 1}/30)`);
    }

    throw new Error('Vite server not found after 30 seconds');
}

// Function to start Electron
function startElectron(vitePort) {
    console.log(`🚀 Starting Electron with Vite on port ${vitePort}`);

    const electron = spawn('npx', ['electron', 'public/electron.cjs'], {
        stdio: 'inherit',
        cwd: path.join(__dirname, '..'),
        shell: true,
        env: {
            ...process.env,
            VITE_PORT: vitePort
        }
    });

    electron.on('error', (error) => {
        console.error('❌ Failed to start Electron:', error);
        process.exit(1);
    });

    electron.on('exit', (code) => {
        console.log(`📱 Electron exited with code ${code}`);
        process.exit(code);
    });
}

// Main function
async function main() {
    try {
        const vitePort = await waitForVite();
        startElectron(vitePort);
    } catch (error) {
        console.error('❌ Error:', error.message);
        process.exit(1);
    }
}

main();