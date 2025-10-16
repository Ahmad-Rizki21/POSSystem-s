const axios = require('axios');

async function waitForVite() {
    console.log('🔍 Waiting for Vite development server...');

    const ports = [5173, 5174, 5175, 5176, 5177];

    for (const port of ports) {
        try {
            console.log(`📡 Checking port ${port}...`);
            const response = await axios.get(`http://localhost:${port}`, {
                timeout: 1000
            });

            if (response.status === 200) {
                console.log(`✅ Vite server found on port ${port}!`);
                return port;
            }
        } catch (error) {
            // Port not ready, continue to next
            continue;
        }
    }

    throw new Error('Vite server not found on any port');
}

waitForVite().then(port => {
    console.log(`🚀 Starting Electron with Vite on port ${port}`);
    process.exit(0); // Signal that Vite is ready
}).catch(error => {
    console.error('❌ Error:', error.message);
    process.exit(1);
});