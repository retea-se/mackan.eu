// dashboard-main.js - Main dashboard functionality

(function() {
    'use strict';

    let statsData = null;
    let charts = {};

    // Load statistics from API
    async function loadStats() {
        try {
            const response = await fetch('api/consolidated-stats.php');
            if (!response.ok) throw new Error('Failed to load stats');

            statsData = await response.json();
            updateOverview();
            updateVisitsSection();
            updateKortlankSection();
            updateSkyddadSection();
        } catch (error) {
            console.error('Error loading stats:', error);
            showError('Kunde inte ladda statistik. Försök igen senare.');
        }
    }

    // Update overview cards
    function updateOverview() {
        if (!statsData) return;

        // Live visitors
        const live = statsData.visits?.live || 0;
        document.getElementById('live-count').textContent = live;

        // Total visits
        const totalVisits = statsData.visits?.total || 0;
        const todayVisits = statsData.visits?.today || 0;
        const yesterdayVisits = statsData.visits?.yesterday || 0;
        document.getElementById('stat-visits-total').textContent = totalVisits.toLocaleString('sv-SE');

        let visitsChange = '';
        if (yesterdayVisits > 0) {
            const change = ((todayVisits - yesterdayVisits) / yesterdayVisits * 100).toFixed(1);
            visitsChange = change > 0 ? `+${change}% idag` : `${change}% idag`;
        }
        document.getElementById('stat-visits-subtitle').textContent = visitsChange || `${todayVisits} idag`;

        // Kortlank
        const kortlankTotal = statsData.kortlank?.total || 0;
        const kortlankClicks = statsData.kortlank?.total_clicks || 0;
        document.getElementById('stat-kortlank-total').textContent = kortlankTotal.toLocaleString('sv-SE');
        document.getElementById('stat-kortlank-subtitle').textContent = `${kortlankClicks.toLocaleString('sv-SE')} klick`;

        // Skyddad
        const skyddadTotal = statsData.skyddad?.total || 0;
        const skyddadCreated = statsData.skyddad?.created || 0;
        document.getElementById('stat-skyddad-total').textContent = skyddadTotal.toLocaleString('sv-SE');
        document.getElementById('stat-skyddad-subtitle').textContent = `${skyddadCreated} skapade`;
    }

    // Update visits section
    function updateVisitsSection() {
        if (!statsData?.visits) return;

        const visits = statsData.visits;

        document.getElementById('visits-unique').textContent = (visits.unique_ips || 0).toLocaleString('sv-SE');
        document.getElementById('visits-humans').textContent = (visits.humans || 0).toLocaleString('sv-SE');
        document.getElementById('visits-bots').textContent = (visits.bots || 0).toLocaleString('sv-SE');
        document.getElementById('visits-today').textContent = (visits.today || 0).toLocaleString('sv-SE');
    }

    // Update kortlank section
    function updateKortlankSection() {
        if (!statsData?.kortlank) return;

        const kortlank = statsData.kortlank;

        document.getElementById('kortlank-active').textContent = (kortlank.active || 0).toLocaleString('sv-SE');
        document.getElementById('kortlank-clicks').textContent = (kortlank.total_clicks || 0).toLocaleString('sv-SE');
        document.getElementById('kortlank-password').textContent = (kortlank.with_password || 0).toLocaleString('sv-SE');
        document.getElementById('kortlank-expired').textContent = (kortlank.expired || 0).toLocaleString('sv-SE');

        // Top links table
        const tbody = document.getElementById('kortlank-top-tbody');
        if (kortlank.top_links && kortlank.top_links.length > 0) {
            tbody.innerHTML = kortlank.top_links.slice(0, 10).map(link => {
                const linkId = link.custom_alias || link.id;
                const shortUrl = `/m/${linkId}`;
                return `
                    <tr>
                        <td><a href="${shortUrl}" target="_blank">${shortUrl}</a></td>
                        <td>${(link.hits || 0).toLocaleString('sv-SE')}</td>
                        <td>${new Date(link.created_at).toLocaleDateString('sv-SE')}</td>
                    </tr>
                `;
            }).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="3">Inga länkar hittades</td></tr>';
        }
    }

    // Update skyddad section
    function updateSkyddadSection() {
        if (!statsData?.skyddad) return;

        const skyddad = statsData.skyddad;

        document.getElementById('skyddad-created').textContent = (skyddad.created || 0).toLocaleString('sv-SE');
        document.getElementById('skyddad-viewed').textContent = (skyddad.viewed || 0).toLocaleString('sv-SE');
        document.getElementById('skyddad-cron').textContent = (skyddad.cron || 0).toLocaleString('sv-SE');

        // Recent events table
        const tbody = document.getElementById('skyddad-events-tbody');
        if (skyddad.recent_events && skyddad.recent_events.length > 0) {
            tbody.innerHTML = skyddad.recent_events.slice(0, 20).map(event => {
                const date = new Date(event.created_at);
                return `
                    <tr>
                        <td>${date.toLocaleString('sv-SE')}</td>
                        <td>${escapeHtml(event.event_type)}</td>
                        <td><code>${escapeHtml(event.secret_id || '').substring(0, 16)}...</code></td>
                        <td>${escapeHtml(event.ip || '')}</td>
                    </tr>
                `;
            }).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="4">Inga events hittades</td></tr>';
        }
    }

    // Render charts
    function renderCharts() {
        if (!statsData) return;

        // Hourly visits chart
        if (statsData.visits?.hourly && document.getElementById('chart-hourly-visits')) {
            renderHourlyChart();
        }

        // Top pages chart
        if (statsData.visits?.top_pages && document.getElementById('chart-top-pages')) {
            renderTopPagesChart();
        }
    }

    function renderHourlyChart() {
        if (!statsData?.visits?.hourly) return;

        const hourly = statsData.visits.hourly;
        const chartElement = document.getElementById('chart-hourly-visits');
        if (!chartElement) return;

        const hours = Array.from({ length: 24 }, (_, i) => i);
        const counts = hours.map(h => {
            const found = hourly.find(item => item.hour === h);
            return found ? found.count : 0;
        });

        // Dispose existing chart if any
        if (charts.hourly) {
            charts.hourly.dispose();
        }

        const chart = echarts.init(chartElement);
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const textColor = isDark ? '#c9d1d9' : '#666';

        chart.setOption({
            tooltip: {
                trigger: 'axis',
                textStyle: { color: textColor }
            },
            xAxis: {
                type: 'category',
                data: hours.map(h => `${h.toString().padStart(2, '0')}:00`),
                axisLabel: { color: textColor, fontSize: 10 }
            },
            yAxis: {
                type: 'value',
                axisLabel: { color: textColor }
            },
            series: [{
                data: counts,
                type: 'line',
                smooth: true,
                areaStyle: { opacity: 0.3 }
            }],
            grid: { left: 40, right: 20, bottom: 40, top: 20 }
        });

        charts.hourly = chart;

        // Resize on window resize
        window.addEventListener('resize', () => chart.resize());
    }

    function renderTopPagesChart() {
        if (!statsData?.visits?.top_pages || !statsData.visits.top_pages.length) return;

        const topPages = statsData.visits.top_pages.slice(0, 10);
        const chartElement = document.getElementById('chart-top-pages');
        if (!chartElement) return;

        const pages = topPages.map(p => p.page || 'Okänd').map(p => p.length > 30 ? p.substring(0, 30) + '...' : p);
        const counts = topPages.map(p => p.count);

        // Dispose existing chart if any
        if (charts.topPages) {
            charts.topPages.dispose();
        }

        const chart = echarts.init(chartElement);
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const textColor = isDark ? '#c9d1d9' : '#666';

        chart.setOption({
            tooltip: {
                trigger: 'axis',
                textStyle: { color: textColor }
            },
            xAxis: {
                type: 'value',
                axisLabel: { color: textColor }
            },
            yAxis: {
                type: 'category',
                data: pages,
                axisLabel: { color: textColor, fontSize: 10 }
            },
            series: [{
                data: counts,
                type: 'bar'
            }],
            grid: { left: 120, right: 20, top: 20, bottom: 20 }
        });

        charts.topPages = chart;

        // Resize on window resize
        window.addEventListener('resize', () => chart.resize());
    }

    // Lazy load charts when section is expanded
    document.addEventListener('loadChart', (e) => {
        const { chartId } = e.detail;
        if (chartId === 'hourly-visits' && !charts.hourly) {
            renderHourlyChart();
        } else if (chartId === 'top-pages' && !charts.topPages) {
            renderTopPagesChart();
        }
    });

    // Update charts when theme changes
    document.addEventListener('themeChanged', () => {
        // Dispose all existing charts
        Object.values(charts).forEach(chart => {
            if (chart && typeof chart.dispose === 'function') {
                chart.dispose();
            }
        });
        charts = {};

        // Re-render charts after theme transition
        setTimeout(() => {
            if (statsData) {
                renderCharts();
            }
        }, 300);
    });

    // Helper function
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        errorDiv.style.cssText = 'position:fixed;top:20px;left:50%;transform:translateX(-50%);background:#f8d7da;color:#721c24;padding:1rem;border-radius:8px;z-index:10000;';
        document.body.appendChild(errorDiv);
        setTimeout(() => errorDiv.remove(), 5000);
    }

    // Auto-refresh stats every 30 seconds
    setInterval(loadStats, 30000);

    // Initial load
    document.addEventListener('DOMContentLoaded', () => {
        loadStats();

        // Render charts after a short delay to ensure DOM is ready
        setTimeout(() => {
            // Check if sections are expanded (on desktop they might be)
            const visitsContent = document.getElementById('visits-content');
            if (visitsContent && (window.innerWidth > 768 || visitsContent.classList.contains('active'))) {
                renderCharts();
            }
        }, 500);
    });

    // Listen for accordion opens to load charts
    document.addEventListener('click', (e) => {
        if (e.target.closest('.accordion-header')) {
            setTimeout(() => {
                const content = e.target.closest('.accordion-section')?.querySelector('.accordion-content');
                if (content && content.classList.contains('active')) {
                    renderCharts();
                }
            }, 300);
        }
    });
})();

