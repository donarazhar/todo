const fs = require('fs');
const path = require('path');

const emojiMap = {
    '📝': '<i class="bi bi-pencil-square"></i>',
    '📊': '<i class="bi bi-bar-chart-line"></i>',
    '👤': '<i class="bi bi-person"></i>',
    '⚠️': '<i class="bi bi-exclamation-triangle"></i>',
    '✔': '<i class="bi bi-check2"></i>',
    '📄': '<i class="bi bi-file-earmark-text"></i>',
    '✅': '<i class="bi bi-check-circle"></i>',
    '↩️': '<i class="bi bi-arrow-return-left"></i>',
    '💬': '<i class="bi bi-chat-dots"></i>',
    '✏️': '<i class="bi bi-pencil"></i>',
    '🗑️': '<i class="bi bi-trash"></i>',
    '📋': '<i class="bi bi-card-checklist"></i>',
    '💾': '<i class="bi bi-floppy"></i>',
    '👑': '<i class="bi bi-person-badge"></i>',
    '🟡': '<i class="bi bi-circle-fill" style="color: #F59E0B; font-size: 0.8em;"></i>',
    '🔴': '<i class="bi bi-circle-fill" style="color: #EF4444; font-size: 0.8em;"></i>',
    '🟢': '<i class="bi bi-circle-fill" style="color: #10B981; font-size: 0.8em;"></i>',
    '📤': '<i class="bi bi-send"></i>',
    '📅': '<i class="bi bi-calendar-event"></i>',
    '🎯': '<i class="bi bi-bullseye"></i>',
    '🏢': '<i class="bi bi-building"></i>',
    '➕': '<i class="bi bi-plus-lg"></i>',
    '🛡️': '<i class="bi bi-shield-check"></i>',
    '🔍': '<i class="bi bi-search"></i>',
    '📈': '<i class="bi bi-graph-up"></i>',
    '👥': '<i class="bi bi-people"></i>',
    '📱': '<i class="bi bi-phone"></i>',
    '🔐': '<i class="bi bi-lock-fill"></i>',
    '🔀': '<i class="bi bi-shuffle"></i>',
    '🏷️': '<i class="bi bi-tag"></i>',
    '📍': '<i class="bi bi-geo-alt-fill"></i>',
    '🔑': '<i class="bi bi-key-fill"></i>',
    '🔗': '<i class="bi bi-link"></i>',
    '👉': '<i class="bi bi-hand-index-thumb"></i>',
    '🚀': '<i class="bi bi-rocket-takeoff"></i>',
    '🔄': '<i class="bi bi-arrow-repeat"></i>',
    '🧬': '<i class="bi bi-diagram-3"></i>',
    '🏠': '<i class="bi bi-house"></i>',
    '🚪': '<i class="bi bi-box-arrow-right"></i>',
    '⏳': '<i class="bi bi-hourglass-split"></i>',
    '🗓️': '<i class="bi bi-calendar-week"></i>',
    '📂': '<i class="bi bi-folder2-open"></i>'
};

function walkDir(dir) {
    let results = [];
    const list = fs.readdirSync(dir);
    list.forEach(file => {
        const fullPath = path.join(dir, file);
        const stat = fs.statSync(fullPath);
        if (stat && stat.isDirectory()) {
            results = results.concat(walkDir(fullPath));
        } else if (file.endsWith('.blade.php')) {
            results.push(fullPath);
        }
    });
    return results;
}

const files = walkDir('resources/views');
let totalReplaced = 0;

files.forEach(file => {
    let content = fs.readFileSync(file, 'utf8');
    let original = content;
    
    for (const [emoji, icon] of Object.entries(emojiMap)) {
        const regex = new RegExp(emoji, 'g');
        content = content.replace(regex, icon);
    }
    
    if (content !== original) {
        fs.writeFileSync(file, content, 'utf8');
        console.log('Updated:', file);
        totalReplaced++;
    }
});

console.log('Done! Updated', totalReplaced, 'files.');
