const { contextBridge, ipcRenderer } = require('electron');

// Expose safe APIs to renderer process
contextBridge.exposeInMainWorld('electronAPI', {
    // Get app version
    getAppVersion: () => ipcRenderer.invoke('get-app-version'),

    // Get database info
    getDatabaseInfo: () => ipcRenderer.invoke('get-database-info'),

    // Restart app
    restartApp: () => ipcRenderer.invoke('restart-app'),

    // Show message box
    showMessage: (options) => ipcRenderer.invoke('show-message', options),

    // Check if running in Electron
    isElectron: true,

    // Get platform info
    getPlatform: () => process.platform
});

// Console log for debugging
console.log('Preload script loaded');