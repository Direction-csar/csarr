/**
 * Responsive Charts Configuration for CSAR Platform
 * Optimized for mobile-first design with Chart.js
 */

class ResponsiveCharts {
    constructor() {
        this.defaultOptions = {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 750,
                easing: 'easeInOutQuart'
            },
            plugins: {
                legend: {
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: window.innerWidth < 768 ? 12 : 14
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(255, 255, 255, 0.2)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    titleFont: {
                        size: window.innerWidth < 768 ? 12 : 14
                    },
                    bodyFont: {
                        size: window.innerWidth < 768 ? 11 : 13
                    }
                }
            }
        };
        
        this.colors = {
            primary: 'rgba(34, 197, 94, 0.8)',
            secondary: 'rgba(59, 130, 246, 0.8)',
            success: 'rgba(34, 197, 94, 0.8)',
            warning: 'rgba(251, 191, 36, 0.8)',
            danger: 'rgba(239, 68, 68, 0.8)',
            info: 'rgba(139, 92, 246, 0.8)',
            purple: 'rgba(236, 72, 153, 0.8)',
            orange: 'rgba(249, 115, 22, 0.8)',
            teal: 'rgba(20, 184, 166, 0.8)',
            indigo: 'rgba(99, 102, 241, 0.8)'
        };
        
        this.init();
    }
    
    init() {
        this.setupResizeHandler();
        this.setupMobileOptimizations();
    }
    
    setupResizeHandler() {
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                this.handleResize();
            }, 250);
        });
    }
    
    setupMobileOptimizations() {
        if (window.innerWidth < 768) {
            // Reduce animation duration on mobile for better performance
            this.defaultOptions.animation.duration = 500;
            
            // Adjust font sizes for mobile
            this.defaultOptions.plugins.legend.labels.font.size = 10;
            this.defaultOptions.plugins.tooltip.titleFont.size = 11;
            this.defaultOptions.plugins.tooltip.bodyFont.size = 10;
        }
    }
    
    handleResize() {
        // Resize all charts
        Chart.helpers.each(Chart.instances, (chart) => {
            chart.resize();
        });
        
        // Update font sizes based on screen size
        const isMobile = window.innerWidth < 768;
        const isTablet = window.innerWidth >= 768 && window.innerWidth < 1024;
        
        this.defaultOptions.plugins.legend.labels.font.size = isMobile ? 10 : isTablet ? 12 : 14;
        this.defaultOptions.plugins.tooltip.titleFont.size = isMobile ? 11 : isTablet ? 13 : 14;
        this.defaultOptions.plugins.tooltip.bodyFont.size = isMobile ? 10 : isTablet ? 12 : 13;
    }
    
    // Line Chart Configuration
    createLineChart(canvasId, data, options = {}) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;
        
        const defaultOptions = {
            ...this.defaultOptions,
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: window.innerWidth < 768 ? 10 : 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        font: {
                            size: window.innerWidth < 768 ? 10 : 12
                        }
                    }
                }
            }
        };
        
        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: data.datasets.map((dataset, index) => ({
                    ...dataset,
                    backgroundColor: dataset.backgroundColor || this.getColor(index),
                    borderColor: dataset.borderColor || this.getColor(index, 1),
                    tension: dataset.tension || 0.4,
                    fill: dataset.fill || false,
                    pointRadius: window.innerWidth < 768 ? 3 : 4,
                    pointHoverRadius: window.innerWidth < 768 ? 5 : 6
                }))
            },
            options: { ...defaultOptions, ...options }
        });
    }
    
    // Bar Chart Configuration
    createBarChart(canvasId, data, options = {}) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;
        
        const defaultOptions = {
            ...this.defaultOptions,
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: window.innerWidth < 768 ? 10 : 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        font: {
                            size: window.innerWidth < 768 ? 10 : 12
                        }
                    }
                }
            }
        };
        
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: data.datasets.map((dataset, index) => ({
                    ...dataset,
                    backgroundColor: dataset.backgroundColor || this.getColor(index),
                    borderColor: dataset.borderColor || this.getColor(index, 1),
                    borderWidth: 1,
                    borderRadius: window.innerWidth < 768 ? 2 : 4,
                    borderSkipped: false
                }))
            },
            options: { ...defaultOptions, ...options }
        });
    }
    
    // Doughnut Chart Configuration
    createDoughnutChart(canvasId, data, options = {}) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;
        
        const defaultOptions = {
            ...this.defaultOptions,
            plugins: {
                ...this.defaultOptions.plugins,
                legend: {
                    ...this.defaultOptions.plugins.legend,
                    position: 'bottom',
                    labels: {
                        ...this.defaultOptions.plugins.legend.labels,
                        padding: window.innerWidth < 768 ? 15 : 20,
                        boxWidth: window.innerWidth < 768 ? 12 : 15
                    }
                }
            },
            cutout: window.innerWidth < 768 ? '60%' : '50%'
        };
        
        return new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.values,
                    backgroundColor: data.colors || this.getColorPalette(data.labels.length),
                    borderWidth: 0,
                    hoverOffset: window.innerWidth < 768 ? 4 : 8
                }]
            },
            options: { ...defaultOptions, ...options }
        });
    }
    
    // Pie Chart Configuration
    createPieChart(canvasId, data, options = {}) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;
        
        const defaultOptions = {
            ...this.defaultOptions,
            plugins: {
                ...this.defaultOptions.plugins,
                legend: {
                    ...this.defaultOptions.plugins.legend,
                    position: 'bottom',
                    labels: {
                        ...this.defaultOptions.plugins.legend.labels,
                        padding: window.innerWidth < 768 ? 15 : 20,
                        boxWidth: window.innerWidth < 768 ? 12 : 15
                    }
                }
            }
        };
        
        return new Chart(ctx, {
            type: 'pie',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.values,
                    backgroundColor: data.colors || this.getColorPalette(data.labels.length),
                    borderWidth: 0,
                    hoverOffset: window.innerWidth < 768 ? 4 : 8
                }]
            },
            options: { ...defaultOptions, ...options }
        });
    }
    
    // Area Chart Configuration
    createAreaChart(canvasId, data, options = {}) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;
        
        const defaultOptions = {
            ...this.defaultOptions,
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: window.innerWidth < 768 ? 10 : 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        font: {
                            size: window.innerWidth < 768 ? 10 : 12
                        }
                    }
                }
            }
        };
        
        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: data.datasets.map((dataset, index) => ({
                    ...dataset,
                    backgroundColor: dataset.backgroundColor || this.getColor(index, 0.2),
                    borderColor: dataset.borderColor || this.getColor(index, 1),
                    tension: dataset.tension || 0.4,
                    fill: true,
                    pointRadius: window.innerWidth < 768 ? 3 : 4,
                    pointHoverRadius: window.innerWidth < 768 ? 5 : 6
                }))
            },
            options: { ...defaultOptions, ...options }
        });
    }
    
    // Utility Methods
    getColor(index, alpha = 0.8) {
        const colorKeys = Object.keys(this.colors);
        const colorKey = colorKeys[index % colorKeys.length];
        const color = this.colors[colorKey];
        
        if (alpha !== 0.8) {
            return color.replace('0.8', alpha.toString());
        }
        
        return color;
    }
    
    getColorPalette(count) {
        const colorKeys = Object.keys(this.colors);
        return Array.from({ length: count }, (_, index) => this.getColor(index));
    }
    
    // Mobile-specific chart configurations
    createMobileOptimizedChart(canvasId, type, data, options = {}) {
        const mobileOptions = {
            ...options,
            plugins: {
                ...options.plugins,
                legend: {
                    ...options.plugins?.legend,
                    display: window.innerWidth >= 768,
                    position: 'bottom'
                },
                tooltip: {
                    ...options.plugins?.tooltip,
                    enabled: window.innerWidth >= 768 || window.innerWidth < 480
                }
            }
        };
        
        switch (type) {
            case 'line':
                return this.createLineChart(canvasId, data, mobileOptions);
            case 'bar':
                return this.createBarChart(canvasId, data, mobileOptions);
            case 'doughnut':
                return this.createDoughnutChart(canvasId, data, mobileOptions);
            case 'pie':
                return this.createPieChart(canvasId, data, mobileOptions);
            case 'area':
                return this.createAreaChart(canvasId, data, mobileOptions);
            default:
                console.warn(`Chart type "${type}" not supported`);
                return null;
        }
    }
    
    // Destroy all charts
    destroyAllCharts() {
        Chart.helpers.each(Chart.instances, (chart) => {
            chart.destroy();
        });
    }
    
    // Update chart data
    updateChart(chart, newData) {
        if (chart) {
            chart.data = newData;
            chart.update('active');
        }
    }
}

// Initialize responsive charts when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.responsiveCharts = new ResponsiveCharts();
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ResponsiveCharts;
}

