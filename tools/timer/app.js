class App {
    constructor() {
        this.timer = new Timer();
        this.clock = new Clock();
        this.mode = 'timer';
        this.isFullscreen = false;
        this.selectedPreset = null;
        this.speedMultiplier = 1;
        this.speedAffectsTimer = false;
        this.displaySize = 75;
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadSettings();
        this.initializeDefaults();
        i18n.updateUI();
    }

    setupEventListeners() {
        // Settings tabs
        document.querySelectorAll('.settings-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                this.switchTab(tab.dataset.tab);
            });
        });

        document.getElementById('lang-toggle').addEventListener('click', () => {
            const newLang = i18n.currentLang === 'sv' ? 'en' : 'sv';
            i18n.setLanguage(newLang);
            document.getElementById('lang-toggle').textContent = newLang === 'sv' ? 'EN' : 'SV';
            // Update flip clock labels if split-flap theme is active
            if (themeManager.currentTheme === 'split-flap') {
                themeManager.updateFlipLabels();
            }
        });

        document.getElementById('theme-toggle').addEventListener('click', () => {
            const currentTheme = document.body.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.body.setAttribute('data-theme', newTheme);
            localStorage.setItem('app-theme', newTheme);
            document.getElementById('theme-toggle').textContent = newTheme === 'dark' ? '☀️' : '🌙';
        });

        document.getElementById('timer-mode').addEventListener('click', () => {
            this.setMode('timer');
        });

        document.getElementById('clock-mode').addEventListener('click', () => {
            this.setMode('clock');
        });

        document.getElementById('timer-direction').addEventListener('change', (e) => {
            this.timer.setDirection(e.target.value);
            this.saveSettings();
        });

        ['hours', 'minutes', 'seconds'].forEach(id => {
            document.getElementById(id).addEventListener('change', () => {
                this.updateTimerTime();
            });
        });

        document.getElementById('clock-type').addEventListener('change', (e) => {
            const isCustom = e.target.value === 'custom';
            document.getElementById('custom-time-inputs').classList.toggle('hidden', !isCustom);
            this.saveSettings();
        });

        document.getElementById('custom-time').addEventListener('change', () => {
            this.updateClockTime();
            this.updatePreview();
        });

        document.getElementById('custom-date').addEventListener('change', () => {
            this.updateClockTime();
            this.updatePreview();
        });

        document.getElementById('time-format').addEventListener('change', (e) => {
            this.clock.setFormat(e.target.value);
            this.saveSettings();
        });

        document.getElementById('show-date').addEventListener('change', (e) => {
            this.clock.setShowDate(e.target.checked);
            this.saveSettings();
        });

        document.getElementById('show-weekday').addEventListener('change', (e) => {
            this.clock.setShowWeekday(e.target.checked);
            this.saveSettings();
        });

        document.querySelectorAll('.speed-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.speed-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const speed = parseFloat(btn.dataset.speed);
                this.setSpeed(speed);
                this.updateSpeedExplanation(speed);
            });
        });

        document.getElementById('speed-affects-timer').addEventListener('change', (e) => {
            this.speedAffectsTimer = e.target.checked;
            if (this.speedAffectsTimer) {
                this.timer.setSpeed(this.speedMultiplier);
            } else {
                this.timer.setSpeed(1);
            }
            this.saveSettings();
        });

        document.querySelectorAll('.theme-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.theme-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                themeManager.setTheme(btn.dataset.theme);
                this.updatePreview();
                this.saveSettings();
            });
        });

        // Download button
        document.getElementById('download-offline').addEventListener('click', () => {
            this.downloadOfflineVersion();
        });

        document.querySelectorAll('.preset-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('selected'));
                btn.classList.add('selected');
                this.selectedPreset = btn.dataset.preset;
            });
        });

        document.getElementById('save-preset').addEventListener('click', () => {
            if (this.selectedPreset) {
                this.savePreset(this.selectedPreset);
            }
        });

        document.getElementById('load-preset').addEventListener('click', () => {
            if (this.selectedPreset) {
                this.loadPreset(this.selectedPreset);
            }
        });

        document.getElementById('start-btn').addEventListener('click', () => {
            this.startFullscreen();
        });

        document.addEventListener('keydown', (e) => {
            if (!this.isFullscreen) return;
            
            if (e.key === ' ') {
                e.preventDefault();
                this.togglePause();
            } else if (e.key === 'r' || e.key === 'R') {
                e.preventDefault();
                this.restart();
            } else if (e.key === 'Escape') {
                e.preventDefault();
                this.exitFullscreen();
            }
        });

        const helpIndicator = document.getElementById('help-indicator');
        const helpTooltip = document.getElementById('help-tooltip');
        
        helpIndicator.addEventListener('mouseenter', () => {
            helpTooltip.classList.remove('hidden');
        });
        
        helpIndicator.addEventListener('mouseleave', () => {
            helpTooltip.classList.add('hidden');
        });

        this.timer.onUpdate = (time) => {
            const timerElement = document.getElementById('timer-time');
            const timerDisplay = document.getElementById('timer-display');
            
            timerElement.textContent = time.formatted;
            
            // Set data attribute for LCD background pattern
            if (timerDisplay) {
                timerDisplay.setAttribute('data-hide-hours', time.hideHours ? 'true' : 'false');
            }
            
            if (themeManager.currentTheme === 'analog-station') {
                themeManager.updateAnalogClock(time);
            } else if (themeManager.currentTheme === 'split-flap') {
                themeManager.updateFlipClock(time);
            } else if (themeManager.currentTheme === 'led-segment') {
                themeManager.update7SegmentDisplay(time);
            }
            
            // Update progress display if enabled
            if (this.isFullscreen) {
                const totalTime = this.timer.targetTime;
                const elapsedTime = this.timer.elapsedTime;
                let progress = 0;
                
                if (this.timer.direction === 'down') {
                    progress = 1 - (elapsedTime / totalTime);
                } else {
                    progress = elapsedTime / totalTime;
                }
                
                this.updateProgressDisplay(Math.max(0, Math.min(1, progress)));
            }
        };

        this.timer.onComplete = () => {
            document.getElementById('timer-display').classList.add('blinking');
        };

        this.clock.onUpdate = (time) => {
            if (themeManager.currentTheme === 'analog-station') {
                themeManager.updateAnalogClock(time);
            } else if (themeManager.currentTheme === 'split-flap') {
                themeManager.updateFlipClock(time);
            } else if (themeManager.currentTheme === 'led-segment') {
                // Parse time string for 7-segment display
                const timeParts = time.time.split(':');
                const timeObj = {
                    hours: parseInt(timeParts[0]) || 0,
                    minutes: parseInt(timeParts[1]) || 0,
                    seconds: parseInt(timeParts[2]) || 0
                };
                themeManager.update7SegmentDisplay(timeObj);
            } else {
                document.getElementById('clock-time').textContent = time.time;
                if (time.date) {
                    document.getElementById('clock-date').textContent = time.date;
                    document.getElementById('clock-date').classList.remove('hidden');
                } else {
                    document.getElementById('clock-date').classList.add('hidden');
                }
            }
        };

        // New feature event listeners
        document.getElementById('enable-prep-phase').addEventListener('change', (e) => {
            document.getElementById('prep-phase-settings').classList.toggle('hidden', !e.target.checked);
            this.saveSettings();
        });

        document.getElementById('show-exercise-title').addEventListener('change', () => {
            this.updatePreview();
            this.saveSettings();
        });

        ['exercise-title', 'prep-minutes', 'prep-seconds', 'prep-message', 'pause-message'].forEach(id => {
            document.getElementById(id).addEventListener('input', () => {
                this.updatePreview();
                this.saveSettings();
            });
        });

        ['hours', 'minutes', 'seconds'].forEach(id => {
            document.getElementById(id).addEventListener('input', () => {
                this.updatePreview();
            });
        });

        document.getElementById('enable-visual-signals').addEventListener('change', () => {
            this.saveSettings();
        });

        document.getElementById('progress-display').addEventListener('change', () => {
            this.saveSettings();
        });

        // Display size setting
        document.getElementById('display-size').addEventListener('change', (e) => {
            this.displaySize = parseInt(e.target.value);
            this.applyDisplaySize();
            this.saveSettings();
        });
    }

    switchTab(tabName) {
        // Remove active class from all tabs and panels
        document.querySelectorAll('.settings-tab').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));

        // Add active class to selected tab and panel
        document.querySelector(`.settings-tab[data-tab="${tabName}"]`).classList.add('active');
        document.querySelector(`.tab-panel[data-panel="${tabName}"]`).classList.add('active');
        
        // Handle clock settings visibility based on mode
        if (tabName === 'clock') {
            this.setMode('clock');
        } else if (tabName === 'basic') {
            this.setMode('timer');
        }
    }

    setMode(mode) {
        this.mode = mode;
        
        document.querySelectorAll('.mode-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        if (mode === 'timer') {
            document.getElementById('timer-mode').classList.add('active');
            this.timer.reset();
        } else {
            document.getElementById('clock-mode').classList.add('active');
            this.clock.stop();
        }
        
        this.updatePreview();
        this.saveSettings();
    }

    setSpeed(multiplier) {
        this.speedMultiplier = multiplier;
        this.clock.setSpeed(multiplier);
        
        if (this.speedAffectsTimer) {
            this.timer.setSpeed(multiplier);
        }
        
        this.saveSettings();
    }

    updateSpeedExplanation(speed) {
        const explanationEl = document.querySelector('#speed-explanation .explanation-text');
        let explanation = '';
        
        if (speed === 0.5) {
            explanation = 'Tiden går i halv hastighet - 1 minut tar 2 minuter att visa';
        } else if (speed === 1) {
            explanation = 'Normal hastighet - tiden visas i realtid';
        } else if (speed === 2) {
            explanation = 'Dubbel hastighet - 1 minut visas på 30 sekunder';
        } else if (speed === 5) {
            explanation = '5 gånger snabbare - 5 minuter visas på 1 minut';
        } else if (speed === 10) {
            explanation = '10 gånger snabbare - 10 minuter visas på 1 minut';
        } else if (speed === 60) {
            explanation = 'Mycket snabb - 1 timme visas på 1 minut';
        }
        
        explanationEl.textContent = explanation;
    }

    updateTimerTime() {
        const hours = parseInt(document.getElementById('hours').value) || 0;
        const minutes = parseInt(document.getElementById('minutes').value) || 0;
        const seconds = parseInt(document.getElementById('seconds').value) || 0;
        
        this.timer.setTime(hours, minutes, seconds);
        this.saveSettings();
    }

    updateClockTime() {
        const timeValue = document.getElementById('custom-time').value;
        const dateValue = document.getElementById('custom-date').value;
        
        if (timeValue) {
            this.clock.setCustomTime(timeValue, dateValue);
        }
    }

    togglePause() {
        console.log('=== TOGGLE PAUSE DEBUG ===');
        let isPaused = false;
        
        if (this.mode === 'timer') {
            isPaused = this.timer.toggle();
        } else if (this.mode === 'clock') {
            isPaused = this.clock.toggle();
        }
        
        const pauseIndicator = document.getElementById('pause-indicator');
        const pauseMessage = document.getElementById('pause-message');
        const customPauseMessageInput = document.querySelector('input#pause-message');
        const display = document.querySelector('.display');
        
        console.log('Pause debug:', {
            mode: this.mode,
            isPaused,
            pauseIndicator: !!pauseIndicator,
            pauseMessage: !!pauseMessage,
            customPauseMessageInput: !!customPauseMessageInput,
            customMessage: customPauseMessageInput?.value,
            display: !!display
        });
        
        if (isPaused) {
            // Use custom pause message if provided, otherwise use appropriate default
            if (customPauseMessageInput && customPauseMessageInput.value) {
                pauseMessage.textContent = customPauseMessageInput.value;
                console.log('✓ Using custom pause message:', customPauseMessageInput.value);
            } else {
                // Set appropriate default message based on mode
                const defaultMessage = this.mode === 'timer' ? 
                    'PAUS - Övningen återupptas snart' : 
                    'PAUS - Klockan är pausad';
                pauseMessage.textContent = defaultMessage;
                console.log('✓ Using default pause message for', this.mode);
            }
            pauseIndicator.classList.remove('hidden');
            if (display) {
                display.classList.add('paused');
                console.log('✓ Blur effect applied to display');
            }
            console.log('✓ Pause indicator shown');
            console.log('Pause indicator classes:', pauseIndicator.classList.toString());
        } else {
            pauseIndicator.classList.add('hidden');
            if (display) {
                display.classList.remove('paused');
                console.log('✓ Blur effect removed from display');
            }
            console.log('✗ Pause indicator hidden');
        }
    }

    restart() {
        if (this.mode === 'timer') {
            this.timer.restart();
            document.getElementById('timer-display').classList.remove('blinking');
        }
    }

    startFullscreen() {
        console.log('=== FULLSCREEN START DEBUG ===');
        document.getElementById('settings-panel').classList.add('hidden');
        document.getElementById('display-panel').classList.remove('hidden');
        
        // Handle exercise title display (only for timer mode)
        const showTitle = document.getElementById('show-exercise-title').checked;
        const title = document.getElementById('exercise-title').value;
        const exerciseTitleDisplay = document.getElementById('exercise-title-display');
        
        console.log('Fullscreen title debug:', {
            showTitle,
            title,
            exerciseTitleDisplay: !!exerciseTitleDisplay,
            displayPanel: !!document.getElementById('display-panel'),
            mode: this.mode
        });
        
        if (showTitle && title) {
            exerciseTitleDisplay.textContent = title;
            exerciseTitleDisplay.classList.remove('hidden');
            console.log('✓ Fullscreen title shown:', title);
            console.log('Exercise title element classes:', exerciseTitleDisplay.classList.toString());
            console.log('Exercise title element style:', exerciseTitleDisplay.style.cssText);
        } else {
            exerciseTitleDisplay.classList.add('hidden');
            console.log('✗ Fullscreen title hidden (mode: ' + this.mode + ')');
        }
        
        if (this.mode === 'timer') {
            if (themeManager.currentTheme === 'split-flap') {
                document.getElementById('timer-display').classList.add('hidden');
                document.getElementById('clock-display').classList.add('hidden');
                document.getElementById('analog-clock').classList.add('hidden');
                document.getElementById('flip-clock').classList.remove('hidden');
            } else {
                document.getElementById('timer-display').classList.remove('hidden');
                document.getElementById('clock-display').classList.add('hidden');
                document.getElementById('flip-clock').classList.add('hidden');
                
                if (themeManager.currentTheme !== 'analog-station') {
                    document.getElementById('analog-clock').classList.add('hidden');
                }
            }
            
            // Setup progress display
            console.log('Setting up progress display...');
            this.setupProgressDisplay();
            this.timer.start();
        } else {
            document.getElementById('timer-display').classList.add('hidden');
            
            if (themeManager.currentTheme === 'analog-station') {
                document.getElementById('clock-display').classList.add('hidden');
                document.getElementById('analog-clock').classList.remove('hidden');
                document.getElementById('flip-clock').classList.add('hidden');
            } else if (themeManager.currentTheme === 'split-flap') {
                document.getElementById('clock-display').classList.add('hidden');
                document.getElementById('analog-clock').classList.add('hidden');
                document.getElementById('flip-clock').classList.remove('hidden');
            } else {
                document.getElementById('clock-display').classList.remove('hidden');
                document.getElementById('analog-clock').classList.add('hidden');
                document.getElementById('flip-clock').classList.add('hidden');
            }
            
            const isCustom = document.getElementById('clock-type').value === 'custom';
            this.clock.start(isCustom);
        }
        
        this.isFullscreen = true;
        
        if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen();
        }
    }

    exitFullscreen() {
        document.getElementById('settings-panel').classList.remove('hidden');
        document.getElementById('display-panel').classList.add('hidden');
        document.getElementById('pause-indicator').classList.add('hidden');
        document.getElementById('timer-display').classList.remove('blinking');
        
        this.timer.pause();
        this.timer.reset();
        this.clock.stop();
        
        this.isFullscreen = false;
        
        if (document.fullscreenElement && document.exitFullscreen) {
            document.exitFullscreen().catch(err => {
                console.log('Error attempting to exit fullscreen:', err);
            });
        }
    }

    saveSettings() {
        const settings = {
            mode: this.mode,
            timerDirection: document.getElementById('timer-direction').value,
            hours: document.getElementById('hours').value,
            minutes: document.getElementById('minutes').value,
            seconds: document.getElementById('seconds').value,
            clockType: document.getElementById('clock-type').value,
            customTime: document.getElementById('custom-time').value,
            customDate: document.getElementById('custom-date').value,
            timeFormat: document.getElementById('time-format').value,
            showDate: document.getElementById('show-date').checked,
            showWeekday: document.getElementById('show-weekday').checked,
            speed: this.speedMultiplier,
            speedAffectsTimer: this.speedAffectsTimer,
            theme: themeManager.currentTheme,
            appTheme: document.body.getAttribute('data-theme'),
            language: i18n.currentLang,
            // New settings
            showExerciseTitle: document.getElementById('show-exercise-title').checked,
            exerciseTitle: document.getElementById('exercise-title').value,
            enablePrepPhase: document.getElementById('enable-prep-phase').checked,
            prepMinutes: document.getElementById('prep-minutes').value,
            prepSeconds: document.getElementById('prep-seconds').value,
            prepMessage: document.getElementById('prep-message').value,
            pauseMessage: document.getElementById('pause-message').value,
            enableVisualSignals: document.getElementById('enable-visual-signals').checked,
            progressDisplay: document.getElementById('progress-display').value,
            displaySize: this.displaySize
        };
        
        localStorage.setItem('timerSettings', JSON.stringify(settings));
    }

    loadSettings() {
        const saved = localStorage.getItem('timerSettings');
        if (!saved) return;
        
        const settings = JSON.parse(saved);
        
        if (settings.mode) this.setMode(settings.mode);
        if (settings.timerDirection) document.getElementById('timer-direction').value = settings.timerDirection;
        if (settings.hours !== undefined) document.getElementById('hours').value = settings.hours;
        if (settings.minutes !== undefined) document.getElementById('minutes').value = settings.minutes;
        if (settings.seconds !== undefined) document.getElementById('seconds').value = settings.seconds;
        if (settings.clockType) document.getElementById('clock-type').value = settings.clockType;
        if (settings.customTime) document.getElementById('custom-time').value = settings.customTime;
        if (settings.customDate) document.getElementById('custom-date').value = settings.customDate;
        if (settings.timeFormat) document.getElementById('time-format').value = settings.timeFormat;
        if (settings.showDate !== undefined) document.getElementById('show-date').checked = settings.showDate;
        if (settings.showWeekday !== undefined) document.getElementById('show-weekday').checked = settings.showWeekday;
        if (settings.speedAffectsTimer !== undefined) {
            document.getElementById('speed-affects-timer').checked = settings.speedAffectsTimer;
            this.speedAffectsTimer = settings.speedAffectsTimer;
        }
        
        if (settings.speed) {
            document.querySelectorAll('.speed-btn').forEach(btn => {
                btn.classList.remove('active');
                if (parseFloat(btn.dataset.speed) === settings.speed) {
                    btn.classList.add('active');
                }
            });
            this.setSpeed(settings.speed);
            this.updateSpeedExplanation(settings.speed);
        }
        
        if (settings.theme) {
            document.querySelectorAll('.theme-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.theme === settings.theme) {
                    btn.classList.add('active');
                }
            });
            themeManager.setTheme(settings.theme);
        }
        
        if (settings.appTheme) {
            document.body.setAttribute('data-theme', settings.appTheme);
            document.getElementById('theme-toggle').textContent = settings.appTheme === 'dark' ? '☀️' : '🌙';
        }
        
        if (settings.language) {
            i18n.setLanguage(settings.language);
            document.getElementById('lang-toggle').textContent = settings.language === 'sv' ? 'EN' : 'SV';
        }

        // Load new settings
        if (settings.showExerciseTitle !== undefined) {
            document.getElementById('show-exercise-title').checked = settings.showExerciseTitle;
        }
        if (settings.exerciseTitle) {
            document.getElementById('exercise-title').value = settings.exerciseTitle;
        }
        if (settings.enablePrepPhase !== undefined) {
            document.getElementById('enable-prep-phase').checked = settings.enablePrepPhase;
            document.getElementById('prep-phase-settings').classList.toggle('hidden', !settings.enablePrepPhase);
        }
        if (settings.prepMinutes !== undefined) {
            document.getElementById('prep-minutes').value = settings.prepMinutes;
        }
        if (settings.prepSeconds !== undefined) {
            document.getElementById('prep-seconds').value = settings.prepSeconds;
        }
        if (settings.prepMessage) {
            document.getElementById('prep-message').value = settings.prepMessage;
        }
        if (settings.pauseMessage) {
            document.getElementById('pause-message').value = settings.pauseMessage;
        }
        if (settings.enableVisualSignals !== undefined) {
            document.getElementById('enable-visual-signals').checked = settings.enableVisualSignals;
        }
        if (settings.progressDisplay) {
            document.getElementById('progress-display').value = settings.progressDisplay;
        }
        if (settings.displaySize !== undefined) {
            this.displaySize = settings.displaySize;
            document.getElementById('display-size').value = settings.displaySize;
            this.applyDisplaySize();
        }
    }

    savePreset(number) {
        const settings = {
            mode: this.mode,
            timerDirection: document.getElementById('timer-direction').value,
            hours: document.getElementById('hours').value,
            minutes: document.getElementById('minutes').value,
            seconds: document.getElementById('seconds').value,
            clockType: document.getElementById('clock-type').value,
            customTime: document.getElementById('custom-time').value,
            customDate: document.getElementById('custom-date').value,
            timeFormat: document.getElementById('time-format').value,
            showDate: document.getElementById('show-date').checked,
            showWeekday: document.getElementById('show-weekday').checked,
            speed: this.speedMultiplier,
            speedAffectsTimer: this.speedAffectsTimer,
            theme: themeManager.currentTheme
        };
        
        localStorage.setItem(`preset_${number}`, JSON.stringify(settings));
    }

    loadPreset(number) {
        const saved = localStorage.getItem(`preset_${number}`);
        if (!saved) return;
        
        const settings = JSON.parse(saved);
        
        if (settings.mode) this.setMode(settings.mode);
        if (settings.timerDirection) {
            document.getElementById('timer-direction').value = settings.timerDirection;
            this.timer.setDirection(settings.timerDirection);
        }
        if (settings.hours !== undefined) document.getElementById('hours').value = settings.hours;
        if (settings.minutes !== undefined) document.getElementById('minutes').value = settings.minutes;
        if (settings.seconds !== undefined) document.getElementById('seconds').value = settings.seconds;
        if (settings.clockType) {
            document.getElementById('clock-type').value = settings.clockType;
            document.getElementById('custom-time-inputs').classList.toggle('hidden', settings.clockType !== 'custom');
        }
        if (settings.customTime) document.getElementById('custom-time').value = settings.customTime;
        if (settings.customDate) document.getElementById('custom-date').value = settings.customDate;
        if (settings.timeFormat) {
            document.getElementById('time-format').value = settings.timeFormat;
            this.clock.setFormat(settings.timeFormat);
        }
        if (settings.showDate !== undefined) {
            document.getElementById('show-date').checked = settings.showDate;
            this.clock.setShowDate(settings.showDate);
        }
        if (settings.showWeekday !== undefined) {
            document.getElementById('show-weekday').checked = settings.showWeekday;
            this.clock.setShowWeekday(settings.showWeekday);
        }
        if (settings.speedAffectsTimer !== undefined) {
            document.getElementById('speed-affects-timer').checked = settings.speedAffectsTimer;
            this.speedAffectsTimer = settings.speedAffectsTimer;
        }
        
        if (settings.speed) {
            document.querySelectorAll('.speed-btn').forEach(btn => {
                btn.classList.remove('active');
                if (parseFloat(btn.dataset.speed) === settings.speed) {
                    btn.classList.add('active');
                }
            });
            this.setSpeed(settings.speed);
            this.updateSpeedExplanation(settings.speed);
        }
        
        if (settings.theme) {
            document.querySelectorAll('.theme-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.theme === settings.theme) {
                    btn.classList.add('active');
                }
            });
            themeManager.setTheme(settings.theme);
        }
        
        this.updateTimerTime();
        this.updateClockTime();
    }

    initializeDefaults() {
        const today = new Date();
        document.getElementById('custom-date').value = today.toISOString().split('T')[0];
        document.getElementById('custom-time').value = '12:00';
        
        // Set exercise title placeholder with current year
        const currentYear = today.getFullYear();
        const exerciseTitleInput = document.getElementById('exercise-title');
        exerciseTitleInput.placeholder = `Stabsövning ${currentYear}`;
        
        this.updateTimerTime();
        this.updateSpeedExplanation(this.speedMultiplier);
        // Start preview update interval
        this.startPreviewUpdates();
        
        const appTheme = localStorage.getItem('app-theme') || 'light';
        document.body.setAttribute('data-theme', appTheme);
        document.getElementById('theme-toggle').textContent = appTheme === 'dark' ? '☀️' : '🌙';
    }

    startPreviewUpdates() {
        // Initial update
        this.updatePreview();
        
        // Update preview periodically for clock mode
        if (this.previewInterval) {
            clearInterval(this.previewInterval);
        }
        
        this.previewInterval = setInterval(() => {
            this.updatePreview();
        }, 1000);
    }

    setupProgressDisplay() {
        const progressType = document.getElementById('progress-display').value;
        const progressBar = document.getElementById('progress-bar');
        const progressCircle = document.getElementById('progress-circle');
        
        console.log('=== PROGRESS DISPLAY DEBUG ===');
        console.log('progressType:', progressType);
        console.log('Elements found:', {
            progressBar: !!progressBar,
            progressCircle: !!progressCircle,
            progressSelect: !!document.getElementById('progress-display')
        });
        
        // Hide all progress displays first
        progressBar.classList.add('hidden');
        progressCircle.classList.add('hidden');
        
        // Show selected progress display
        if (progressType === 'bar') {
            progressBar.classList.remove('hidden');
            console.log('✓ Progress bar shown');
            console.log('Progress bar classes:', progressBar.classList.toString());
        } else if (progressType === 'circle') {
            progressCircle.classList.remove('hidden');
            console.log('✓ Progress circle shown');
            console.log('Progress circle classes:', progressCircle.classList.toString());
        } else {
            console.log('✗ No progress display selected');
        }
    }

    updateProgressDisplay(progress) {
        const progressType = document.getElementById('progress-display').value;
        
        if (progressType === 'bar') {
            const progressFill = document.querySelector('#progress-bar .progress-fill');
            if (progressFill) {
                progressFill.style.width = `${progress * 100}%`;
            }
        } else if (progressType === 'circle') {
            const progressArc = document.getElementById('progress-arc');
            if (progressArc) {
                const circumference = 2 * Math.PI * 45; // radius = 45
                const offset = circumference - (progress * circumference);
                progressArc.style.strokeDashoffset = offset;
            }
        }
    }

    updatePreview() {
        const showTitle = document.getElementById('show-exercise-title').checked;
        const title = document.getElementById('exercise-title').value;
        const hours = parseInt(document.getElementById('hours').value) || 0;
        const minutes = parseInt(document.getElementById('minutes').value) || 0;
        const seconds = parseInt(document.getElementById('seconds').value) || 0;
        
        console.log('=== PREVIEW UPDATE DEBUG ===');
        console.log('showTitle:', showTitle);
        console.log('title:', title);
        
        const titleElement = document.getElementById('exercise-title');
        console.log('Title element:', titleElement);
        console.log('Title element value:', titleElement ? titleElement.value : 'ELEMENT NOT FOUND');
        console.log('Title element type:', titleElement ? titleElement.type : 'ELEMENT NOT FOUND');
        console.log('Title element id:', titleElement ? titleElement.id : 'ELEMENT NOT FOUND');
        
        console.log('Elements found:', {
            showTitleCheckbox: !!document.getElementById('show-exercise-title'),
            titleInput: !!document.getElementById('exercise-title'),
            previewTitle: !!document.getElementById('preview-title')
        });

        // Update preview title (only for timer mode)
        const previewTitle = document.getElementById('preview-title');
        console.log('Preview title element:', previewTitle);
        console.log('Should show title:', showTitle && title && this.mode === 'timer');
        
        if (showTitle && title && this.mode === 'timer') {
            previewTitle.textContent = title;
            previewTitle.classList.remove('hidden');
            console.log('✓ Preview title shown:', title);
            console.log('Preview title classes:', previewTitle.classList.toString());
        } else {
            previewTitle.classList.add('hidden');
            console.log('✗ Preview title hidden');
        }

        // Update preview display based on theme
        const currentTheme = themeManager.currentTheme;
        const previewTime = document.getElementById('preview-time');
        const previewAnalog = document.getElementById('preview-analog');
        const previewDisplay = document.querySelector('.preview-display');

        // Remove all theme classes
        previewDisplay.classList.remove('preview-theme-digital-retro', 'preview-theme-tactical', 'preview-theme-nixie', 'preview-theme-lcd', 'preview-theme-split-flap', 'preview-theme-led-segment');

        const previewFlipClock = document.getElementById('preview-flip-clock');
        const preview7Segment = document.getElementById('preview-7segment');
        
        // Hide all preview displays first
        previewTime.classList.add('hidden');
        previewAnalog.classList.add('hidden');
        previewFlipClock.classList.add('hidden');
        preview7Segment.classList.add('hidden');
        
        if (currentTheme === 'analog-station') {
            // Show analog clock for analog theme
            previewAnalog.classList.remove('hidden');
            this.updatePreviewAnalogClock();
        } else if (currentTheme === 'split-flap') {
            // Show flip clock preview
            previewFlipClock.classList.remove('hidden');
            this.updateSpecializedPreviews(currentTheme, hours, minutes, seconds);
        } else if (currentTheme === 'led-segment') {
            // Show 7-segment preview
            preview7Segment.classList.remove('hidden');
            this.updateSpecializedPreviews(currentTheme, hours, minutes, seconds);
        } else {
            // Show digital time for other themes
            previewTime.classList.remove('hidden');
            
            // Apply theme-specific styling
            if (currentTheme === 'digital-retro') {
                previewDisplay.classList.add('preview-theme-digital-retro');
            } else if (currentTheme === 'tactical') {
                previewDisplay.classList.add('preview-theme-tactical');
            } else if (currentTheme === 'nixie') {
                previewDisplay.classList.add('preview-theme-nixie');
            } else if (currentTheme === 'lcd') {
                previewDisplay.classList.add('preview-theme-lcd');
            } else if (currentTheme === 'split-flap') {
                previewDisplay.classList.add('preview-theme-split-flap');
            } else if (currentTheme === 'led-segment') {
                previewDisplay.classList.add('preview-theme-led-segment');
            }

            // Update time string
            let timeString;
            if (this.mode === 'timer') {
                // Use same logic as timer for consistency
                const totalTime = (hours * 3600 + minutes * 60 + seconds) * 1000;
                const hideHours = hours === 0 && totalTime < 3600000;
                
                if (hideHours) {
                    timeString = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                } else {
                    timeString = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                }
                
                // Hide date in timer mode
                const previewDate = document.getElementById('preview-date');
                previewDate.classList.add('hidden');
            } else {
                // Clock mode
                const isCustom = document.getElementById('clock-type').value === 'custom';
                let now;
                
                if (isCustom) {
                    // Update custom time if needed
                    const timeValue = document.getElementById('custom-time').value;
                    const dateValue = document.getElementById('custom-date').value;
                    if (timeValue) {
                        this.clock.setCustomTime(timeValue, dateValue);
                    }
                    // Use custom time
                    now = new Date(this.clock.getCurrentTime());
                } else {
                    // Use real time
                    now = new Date();
                }
                
                const currentHours = now.getHours();
                const currentMinutes = now.getMinutes();
                const currentSeconds = now.getSeconds();
                
                const format24 = document.getElementById('time-format').value === '24';
                if (format24) {
                    timeString = `${String(currentHours).padStart(2, '0')}:${String(currentMinutes).padStart(2, '0')}:${String(currentSeconds).padStart(2, '0')}`;
                } else {
                    const displayHours = currentHours === 0 ? 12 : currentHours > 12 ? currentHours - 12 : currentHours;
                    const period = currentHours >= 12 ? 'PM' : 'AM';
                    timeString = `${String(displayHours).padStart(2, '0')}:${String(currentMinutes).padStart(2, '0')}:${String(currentSeconds).padStart(2, '0')} ${period}`;
                }
                
                // Handle date display for clock mode
                const previewDate = document.getElementById('preview-date');
                const showDate = document.getElementById('show-date').checked;
                const showWeekday = document.getElementById('show-weekday').checked;
                
                if (showDate || showWeekday) {
                    const parts = [];
                    
                    if (showWeekday) {
                        parts.push(i18n.getWeekday(now.getDay()));
                    }
                    
                    if (showDate) {
                        const day = now.getDate();
                        const month = i18n.getMonth(now.getMonth());
                        const year = now.getFullYear();
                        parts.push(`${day} ${month} ${year}`);
                    }
                    
                    previewDate.textContent = parts.join(', ');
                    previewDate.classList.remove('hidden');
                } else {
                    previewDate.classList.add('hidden');
                }
            }
            
            previewTime.textContent = timeString;
        }
        
        // Update specialized preview displays
        this.updateSpecializedPreviews(currentTheme, hours, minutes, seconds);
    }

    updateSpecializedPreviews(currentTheme, hours, minutes, seconds) {
        if (currentTheme === 'split-flap') {
            const flipDigits = document.querySelectorAll('#preview-flip-clock .preview-flip-digit');
            const totalTime = (hours * 3600 + minutes * 60 + seconds) * 1000;
            const hideHours = this.mode === 'timer' && hours === 0 && totalTime < 3600000;
            
            // Show/hide hours
            const hourDigit = flipDigits[0];
            const hourSeparator = document.querySelector('#preview-flip-clock .preview-flip-separator');
            
            if (hideHours && hourDigit && hourSeparator) {
                hourDigit.style.display = 'none';
                hourSeparator.style.display = 'none';
            } else if (hourDigit && hourSeparator) {
                hourDigit.style.display = 'flex';
                hourSeparator.style.display = 'block';
            }
            
            // Update values
            if (!hideHours && flipDigits[0]) {
                flipDigits[0].textContent = String(hours).padStart(2, '0');
            }
            if (flipDigits[1]) flipDigits[1].textContent = String(minutes).padStart(2, '0');
            if (flipDigits[2]) flipDigits[2].textContent = String(seconds).padStart(2, '0');
            
        } else if (currentTheme === 'led-segment') {
            const segmentDisplay = document.querySelector('#preview-7segment .preview-segment-display');
            const totalTime = (hours * 3600 + minutes * 60 + seconds) * 1000;
            const hideHours = this.mode === 'timer' && hours === 0 && totalTime < 3600000;
            
            let timeString;
            if (hideHours) {
                timeString = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            } else {
                timeString = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }
            
            if (segmentDisplay) {
                segmentDisplay.textContent = timeString;
            }
        }
    }

    updatePreviewAnalogClock() {
        const now = new Date();
        const hours = now.getHours() % 12;
        const minutes = now.getMinutes();
        const seconds = now.getSeconds();
        
        const hourDegrees = (hours * 30) + (minutes * 0.5);
        const minuteDegrees = minutes * 6;
        const secondDegrees = seconds * 6;
        
        const hourHand = document.getElementById('preview-hour-hand');
        const minuteHand = document.getElementById('preview-minute-hand');
        const secondHand = document.getElementById('preview-second-hand');
        
        if (hourHand) hourHand.style.transform = `rotate(${hourDegrees}deg)`;
        if (minuteHand) minuteHand.style.transform = `rotate(${minuteDegrees}deg)`;
        if (secondHand) secondHand.style.transform = `rotate(${secondDegrees}deg)`;
    }

    applyDisplaySize() {
        const displayContent = document.getElementById('display-content');
        if (displayContent) {
            const scale = this.displaySize / 100;
            displayContent.style.transform = `scale(${scale})`;
            displayContent.style.transformOrigin = 'center center';
            
            // Adjust the display panel to handle scaling
            const displayPanel = document.getElementById('display-panel');
            if (displayPanel) {
                if (scale > 1) {
                    displayPanel.style.overflow = 'auto';
                    // Add padding to ensure the scaled content fits nicely
                    displayPanel.style.padding = '20px';
                } else {
                    displayPanel.style.overflow = 'visible';
                    displayPanel.style.padding = '0';
                }
            }
        }
    }

    downloadOfflineVersion() {
        // Create a simple link to trigger download
        const link = document.createElement('a');
        link.href = './timer.zip';
        link.download = 'timer.zip';
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new App();
});