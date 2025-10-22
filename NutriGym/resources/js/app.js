// Alpine.js
import Alpine from 'alpinejs'

window.Alpine = Alpine

// Definir la función setup() 
window.setup = function() {
    return {
        activeTab: 0,
        tabs: ['dietas', 'objetivos'], 
        
        // Atras -- Adelante
        nextTab() {
            if (this.activeTab < this.tabs.length - 1) {
                this.activeTab++;
            }
        },
        
        prevTab() {
            if (this.activeTab > 0) {
                this.activeTab--;
            }
        },
        
        init() {
            console.log('Alpine inicializado');
        }
    }
}

Alpine.start()