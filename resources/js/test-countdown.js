/**
 * Test Countdown Timer
 * This file is for testing the countdown timer functionality
 */

console.log('Test countdown file loaded');

// Test function to check if countdown elements exist
function testCountdownElements() {
    console.log('Testing countdown elements...');
    
    const vehicleCards = document.querySelectorAll('[data-vehicle-id]');
    console.log(`Found ${vehicleCards.length} vehicle cards`);
    
    vehicleCards.forEach(card => {
        const vehicleId = card.dataset.vehicleId;
        const status = card.dataset.status;
        const endTime = card.dataset.endTime;
        
        console.log(`Vehicle ${vehicleId}:`);
        console.log(`  - Status: ${status}`);
        console.log(`  - End Time: ${endTime}`);
        console.log(`  - End Time (formatted): ${endTime ? new Date(parseInt(endTime)).toLocaleString() : 'N/A'}`);
        
        const countdownElement = document.getElementById(`countdown-${vehicleId}`);
        if (countdownElement) {
            console.log(`  - Countdown element: ${countdownElement.innerHTML}`);
        } else {
            console.log(`  - Countdown element: NOT FOUND`);
        }
        
        const statusTextElement = document.getElementById(`status-text-${vehicleId}`);
        if (statusTextElement) {
            console.log(`  - Status text: ${statusTextElement.textContent}`);
        } else {
            console.log(`  - Status text: NOT FOUND`);
        }
        
        console.log('---');
    });
}

// Test function to check if timers are running
function testTimers() {
    console.log('Testing timers...');
    
    if (window.vehicleOperations && window.vehicleOperations.vehicleTimers) {
        const timers = window.vehicleOperations.vehicleTimers;
        console.log(`Found ${Object.keys(timers).length} active timers:`, timers);
        
        Object.keys(timers).forEach(vehicleId => {
            console.log(`Timer for vehicle ${vehicleId} is active`);
        });
    } else {
        console.log('No vehicleOperations or timers found');
    }
}

// Export test functions
window.testCountdownElements = testCountdownElements;
window.testTimers = testTimers;

// Auto-run tests after a delay
setTimeout(() => {
    console.log('Running countdown tests...');
    testCountdownElements();
    testTimers();
}, 2000);
