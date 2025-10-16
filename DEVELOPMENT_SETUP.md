# Development Setup Instructions

## Running the Development Server

To run the development server, follow these steps:

1. Make sure no other Node.js or Electron processes are running
2. Install dependencies:
   ```bash
   npm install
   ```
3. Run the development server:
   ```bash
   npm run dev
   ```

## Troubleshooting

### If you get "vite is not recognized" error:
This means the node_modules folder is not properly installed or vite is not in your PATH.
Run `npm install` to install all dependencies.

### If you get file locking errors during npm install:
```
npm error EBUSY: resource busy or locked
```

This occurs when files in node_modules are in use by another process (typically Electron processes).
To resolve:
1. Close any running Electron applications
2. End Node.js processes in Task Manager (Ctrl+Shift+Esc -> Processes tab)
3. Then run `npm install`

### For Windows users:
If you have persistent file locking issues:
1. Restart Windows Explorer or reboot your system
2. Make sure no IDE terminals are running Node processes
3. Close Laravel Valet or any service that might be monitoring files

## Docker Development (Alternative)

If you encounter persistent issues with local development setup, you can use Docker:

1. Build the Docker image:
   ```bash
   docker-compose build
   ```
2. Run the development environment:
   ```bash
   docker-compose up
   ```

This will run the application in containers without requiring local Node.js setup.