// Initialize dark mode
function initDarkMode() {
    const darkModeToggle = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const html = document.documentElement;

    // Check saved preference
    const savedTheme = localStorage.getItem('darkMode');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    // Set initial state
    if (savedTheme === 'true' || (savedTheme === null && systemPrefersDark)) {
        enableDarkMode();
    }

    // Toggle event listener
    darkModeToggle.addEventListener('change', () => {
        if (darkModeToggle.checked) {
            enableDarkMode();
        } else {
            disableDarkMode();
        }
    });

    function enableDarkMode() {
        html.setAttribute('data-bs-theme', 'dark');
        darkModeToggle.checked = true;
        darkModeIcon.innerHTML = 'light_mode';
        localStorage.setItem('darkMode', 'true');
        
        // Update any charts or plugins
        updateChartsTheme('dark');
    }

    function disableDarkMode() {
        html.setAttribute('data-bs-theme', 'light');
        darkModeToggle.checked = false;
        darkModeIcon.innerHTML = 'dark_mode';
        localStorage.setItem('darkMode', 'false');
        
        // Update any charts or plugins
        updateChartsTheme('light');
    }

    // Update charts theme if they exist
    function updateChartsTheme(theme) {
        if (typeof Chart !== 'undefined') {
            Chart.helpers.each(Chart.instances, function(instance) {
                instance.options.plugins.legend.labels.color = theme === 'dark' ? '#fff' : '#344767';
                instance.options.scales.y.grid.color = theme === 'dark' ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)';
                instance.options.scales.x.grid.color = theme === 'dark' ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)';
                instance.options.scales.y.ticks.color = theme === 'dark' ? '#fff' : '#344767';
                instance.options.scales.x.ticks.color = theme === 'dark' ? '#fff' : '#344767';
                instance.update();
            });
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initDarkMode); 