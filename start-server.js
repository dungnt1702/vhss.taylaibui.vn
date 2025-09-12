const { spawn } = require('child_process');
const path = require('path');

console.log('🚀 Starting Laravel development server...');

// Kill any existing PHP processes on port 8000
const killProcess = spawn('pkill', ['-f', 'php artisan serve'], { stdio: 'inherit' });

killProcess.on('close', () => {
    // Start the Laravel server
    const server = spawn('php', ['artisan', 'serve', '--host=0.0.0.0', '--port=8000'], {
        cwd: __dirname,
        stdio: 'inherit',
        shell: true
    });

    server.on('error', (err) => {
        console.error('❌ Failed to start server:', err);
    });

    server.on('close', (code) => {
        console.log(`🛑 Server stopped with code ${code}`);
    });

    // Handle process termination
    process.on('SIGINT', () => {
        console.log('\n🛑 Stopping server...');
        server.kill('SIGINT');
        process.exit(0);
    });
});

