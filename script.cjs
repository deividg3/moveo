const fs = require('fs');
const path = require('path');
function processDir(dir) {
  const files = fs.readdirSync(dir);
  for (const file of files) {
    const fullPath = path.join(dir, file);
    if (fs.statSync(fullPath).isDirectory()) {
      processDir(fullPath);
    } else if (fullPath.endsWith('.vue') || fullPath.endsWith('.js') || fullPath.endsWith('.css')) {
      let text = fs.readFileSync(fullPath, 'utf8');
      
      const map = {
        '👋': '<i class="fas fa-hand-paper"></i>',
        '🙋': '<i class="fas fa-user"></i>',
        '✉️': '<i class="fas fa-envelope"></i>',
        '🔒': '<i class="fas fa-lock"></i>',
        '🔔': '<i class="fas fa-bell"></i>',
        '🎯': '<i class="fas fa-bullseye"></i>',
        '🔥': '<i class="fas fa-fire"></i>',
        '🎓': '<i class="fas fa-graduation-cap"></i>',
        '📊': '<i class="fas fa-chart-bar"></i>',
        '📝': '<i class="fas fa-file-alt"></i>',
        '✅': '<i class="fas fa-check-circle"></i>',
        '❌': '<i class="fas fa-times-circle"></i>',
        '📚': '<i class="fas fa-book"></i>',
        '🔁': '<i class="fas fa-redo"></i>',
        '⚠️': '<i class="fas fa-exclamation-triangle"></i>',
        '🏆': '<i class="fas fa-trophy"></i>',
        '🎉': '<i class="fas fa-star"></i>',
        '💪': '<i class="fas fa-dumbbell"></i>',
        '💡': '<i class="fas fa-lightbulb"></i>',
        '✓': '<i class="fas fa-check"></i>',
        '✗': '<i class="fas fa-times"></i>',
        '✕': '<i class="fas fa-times"></i>',
        '👥': '<i class="fas fa-users"></i>',
        '❓': '<i class="fas fa-question"></i>',
        '🙈': '<i class="fas fa-eye-slash"></i>',
        '👁️': '<i class="fas fa-eye"></i>',
        '👤': '<i class="fas fa-user"></i>'
      };
      
      for (const [emoji, fa] of Object.entries(map)) {
        text = text.split(emoji).join(fa);
      }
      
      // Remove HTML comments
      text = text.replace(/<!--[\s\S]*?-->/g, '');
      // Remove multiline JS comments
      text = text.replace(/\/\*[\s\S]*?\*\//g, '');
      // Remove single line JS comments that are not part of URLs
      text = text.replace(/(?<![:"'])\/\/ .*/g, '');
      
      fs.writeFileSync(fullPath, text, 'utf8');
    }
  }
}
processDir('src');
console.log('Done');