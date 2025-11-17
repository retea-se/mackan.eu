class ThemeManager {
    constructor() {
        this.currentTheme = 'digital-standard';
        this.themes = {
            'digital-standard': {
                name: 'Digital Standard',
                applyTheme: () => this.applyDigitalStandard()
            },
            'analog-station': {
                name: 'Analog Station',
                applyTheme: () => this.applyAnalogStation()
            },
            'digital-retro': {
                name: '7-segment',
                applyTheme: () => this.applyDigitalRetro()
            },
            'tactical': {
                name: 'Tactical',
                applyTheme: () => this.applyTactical()
            },
            'nixie': {
                name: 'Nixie',
                applyTheme: () => this.applyNixie()
            },
            'lcd': {
                name: 'LCD',
                applyTheme: () => this.applyLCD()
            },
            'split-flap': {
                name: 'Split-flap',
                applyTheme: () => this.applySplitFlap()
            },
            'led-segment': {
                name: 'LED 7-segment',
                applyTheme: () => this.applyLEDSegment()
            }
        };
    }

    setTheme(themeName) {
        this.currentTheme = themeName;
        const theme = this.themes[themeName];
        if (theme) {
            this.resetTheme();
            theme.applyTheme();
            localStorage.setItem('theme', themeName);
        }
    }

    resetTheme() {
        const displayContent = document.getElementById('display-content');
        displayContent.className = 'display-content';
        
        document.getElementById('timer-display').className = 'timer-display';
        document.getElementById('clock-display').className = 'clock-display hidden';
        document.getElementById('analog-clock').className = 'analog-clock hidden';
        document.getElementById('flip-clock').className = 'flip-clock hidden';
        
        // Reset preview displays - show normal preview by default
        document.getElementById('preview-time').classList.remove('hidden');
        document.getElementById('preview-flip-clock').classList.add('hidden');
        document.getElementById('preview-7segment').classList.add('hidden');
        document.getElementById('preview-analog').classList.add('hidden');
        
        // Remove 7-segment display if it exists
        const sevenSegmentDisplay = document.getElementById('seven-segment-display');
        if (sevenSegmentDisplay && sevenSegmentDisplay.parentNode) {
            sevenSegmentDisplay.parentNode.removeChild(sevenSegmentDisplay);
        }
        
        // Remove old theme style if exists
        const oldStyle = document.getElementById('theme-style');
        if (oldStyle) {
            oldStyle.remove();
        }
    }

    applyDigitalStandard() {
        const displayContent = document.getElementById('display-content');
        displayContent.classList.add('theme-digital-standard');
        
        const style = document.createElement('style');
        style.id = 'theme-style';
        style.textContent = `
            .theme-digital-standard .timer-display,
            .theme-digital-standard .clock-display {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                font-weight: 300;
                letter-spacing: 0.05em;
            }
        `;
        document.head.appendChild(style);
    }

    applyAnalogStation() {
        const displayContent = document.getElementById('display-content');
        displayContent.classList.add('theme-analog-station');
        
        document.getElementById('timer-display').classList.add('hidden');
        document.getElementById('clock-display').classList.add('hidden');
        document.getElementById('analog-clock').classList.remove('hidden');
        
        // Update preview display
        document.getElementById('preview-time').classList.add('hidden');
        document.getElementById('preview-flip-clock').classList.add('hidden');
        document.getElementById('preview-7segment').classList.add('hidden');
        document.getElementById('preview-analog').classList.remove('hidden');
        
        const style = document.createElement('style');
        style.id = 'theme-style';
        style.textContent = `
            .theme-analog-station .analog-clock {
                filter: drop-shadow(0 4px 20px rgba(0,0,0,0.3));
                background: white;
                border-radius: 50%;
            }
            .theme-analog-station .clock-face {
                background: white;
                border: 3px solid #333;
                border-radius: 50%;
            }
            .theme-analog-station .clock-face .hour-markers text {
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                font-weight: 600;
                font-size: 16px;
                fill: #333;
            }
            .theme-analog-station .clock-face .hour-markers line {
                stroke: #333;
                stroke-width: 4;
            }
            .theme-analog-station .clock-face .minute-ticks line {
                stroke: #333;
                stroke-width: 2;
            }
            .theme-analog-station #hour-hand {
                stroke: #333;
                stroke-width: 8;
                stroke-linecap: round;
            }
            .theme-analog-station #minute-hand {
                stroke: #333;
                stroke-width: 6;
                stroke-linecap: round;
            }
            .theme-analog-station #second-hand {
                stroke: #ff0000;
                stroke-width: 3;
                stroke-linecap: round;
            }
            .theme-analog-station .clock-face circle[fill="currentColor"] {
                fill: #333;
            }
        `;
        document.head.appendChild(style);
    }

    applyDigitalRetro() {
        const displayContent = document.getElementById('display-content');
        displayContent.classList.add('theme-digital-retro');
        
        const style = document.createElement('style');
        style.id = 'theme-style';
        style.textContent = `
            .display-panel {
                background: #1a1a1a !important;
            }
            .theme-digital-retro .timer-display,
            .theme-digital-retro .clock-display {
                font-family: 'Courier New', monospace;
                color: #00ff00 !important;
                text-shadow: 0 0 10px rgba(0,255,0,0.5);
                background: none;
                padding: 0;
                border-radius: 0;
                box-shadow: none;
            }
            .theme-digital-retro .clock-date {
                color: #00ff00 !important;
            }
        `;
        document.head.appendChild(style);
    }

    applyTactical() {
        const displayContent = document.getElementById('display-content');
        displayContent.classList.add('theme-tactical');
        
        const style = document.createElement('style');
        style.id = 'theme-style';
        style.textContent = `
            .display-panel {
                background: #2a2a2a !important;
            }
            .theme-tactical .timer-display,
            .theme-tactical .clock-display {
                font-family: 'Courier New', monospace;
                font-weight: bold;
                color: #ff6600 !important;
                background: none;
                padding: 0;
                border: none;
                text-transform: uppercase;
                letter-spacing: 0.2em;
            }
            .theme-tactical .clock-date {
                color: #ff6600 !important;
            }
            
            /* Mobile responsive adjustments for Tactical */
            @media (max-width: 768px) {
                .theme-tactical .timer-display,
                .theme-tactical .clock-display {
                    font-size: clamp(2rem, 8vw, 4rem) !important;
                    letter-spacing: 0.1em !important;
                }
            }
        `;
        document.head.appendChild(style);
    }

    applyNixie() {
        const displayContent = document.getElementById('display-content');
        displayContent.classList.add('theme-nixie');
        
        const style = document.createElement('style');
        style.id = 'theme-style';
        style.textContent = `
            .theme-nixie .timer-display,
            .theme-nixie .clock-display {
                font-family: Georgia, serif;
                background: linear-gradient(180deg, #1a0a0a 0%, #2a1515 100%);
                color: #ff9955;
                padding: 40px 60px;
                border-radius: 20px;
                box-shadow: 
                    inset 0 0 60px rgba(255,150,85,0.2),
                    0 0 40px rgba(255,150,85,0.1);
                text-shadow: 
                    0 0 20px rgba(255,150,85,0.8),
                    0 0 40px rgba(255,150,85,0.6),
                    0 0 60px rgba(255,150,85,0.4);
                letter-spacing: 0.3em;
            }
        `;
        document.head.appendChild(style);
    }

    applyLCD() {
        const displayContent = document.getElementById('display-content');
        displayContent.classList.add('theme-lcd');
        
        const style = document.createElement('style');
        style.id = 'theme-style';
        style.textContent = `
            .display-panel {
                background: #9eb89e !important;
            }
            .theme-lcd .timer-display,
            .theme-lcd .clock-display {
                font-family: 'Courier New', monospace;
                color: #1a2a1a !important;
                background: none;
                padding: 0;
                border: none;
                position: relative;
            }
            .theme-lcd .clock-date {
                color: #1a2a1a !important;
            }
            .theme-lcd .timer-display::before {
                content: '88:88:88';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #000 !important;
                opacity: 0.15;
                z-index: 0;
                font-family: inherit;
                font-size: inherit;
                font-weight: inherit;
                letter-spacing: inherit;
            }
            .theme-lcd .clock-display::before {
                content: '88:88:88';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #000 !important;
                opacity: 0.15;
                z-index: 0;
                font-family: inherit;
                font-size: inherit;
                font-weight: inherit;
                letter-spacing: inherit;
            }
            /* Dynamic background pattern for timer without hours */
            .theme-lcd .timer-display[data-hide-hours="true"]::before {
                content: '88:88';
            }
            .theme-lcd .timer-display > *,
            .theme-lcd .clock-display > * {
                position: relative;
                z-index: 1;
            }
            
            /* Mobile responsive adjustments for LCD */
            @media (max-width: 768px) {
                .theme-lcd .timer-display,
                .theme-lcd .clock-display {
                    font-size: clamp(2rem, 8vw, 4rem) !important;
                }
            }
        `;
        document.head.appendChild(style);
    }

    applySplitFlap() {
        const displayContent = document.getElementById('display-content');
        displayContent.classList.add('theme-split-flap');
        
        // Hide other displays and show flip clock
        document.getElementById('timer-display').classList.add('hidden');
        document.getElementById('clock-display').classList.add('hidden');
        document.getElementById('analog-clock').classList.add('hidden');
        document.getElementById('flip-clock').classList.remove('hidden');
        
        // Update preview display
        document.getElementById('preview-time').classList.add('hidden');
        document.getElementById('preview-flip-clock').classList.remove('hidden');
        document.getElementById('preview-7segment').classList.add('hidden');
        document.getElementById('preview-analog').classList.add('hidden');
        
        // Initialize flip components after a short delay to ensure DOM is ready
        setTimeout(() => {
            if (!this.flipComponents && typeof Tick !== 'undefined') {
                this.initializeFlipComponents();
            }
            // Update labels for current language
            this.updateFlipLabels();
        }, 100);
        
        const style = document.createElement('style');
        style.id = 'theme-style';
        style.textContent = `
            .display-panel {
                background: linear-gradient(135deg, #2c2c2c 0%, #1a1a1a 100%) !important;
            }
            
            .theme-split-flap .flip-clock {
                color: var(--text-primary);
            }
            
            /* Customize PQINA flip styling */
            .tick-flip {
                background: linear-gradient(180deg, #333 0%, #222 50%, #111 100%) !important;
                color: #fff !important;
                border: 2px solid #444 !important;
                border-radius: 8px !important;
                box-shadow: 
                    0 3px 6px rgba(0,0,0,0.4),
                    inset 0 1px 0 rgba(255,255,255,0.1) !important;
            }
            
            .tick-flip-panel {
                background: linear-gradient(180deg, #333 0%, #222 50%, #111 100%) !important;
                color: #fff !important;
                border: 2px solid #444 !important;
            }
            
            .tick-flip-panel::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 0;
                right: 0;
                height: 1px;
                background: linear-gradient(90deg, 
                    transparent 0%, 
                    rgba(255,255,255,0.2) 20%, 
                    rgba(255,255,255,0.2) 80%, 
                    transparent 100%) !important;
                transform: translateY(-50%);
            }
            
            /* Dark theme variant */
            [data-theme="dark"] .tick-flip,
            [data-theme="dark"] .tick-flip-panel {
                background: linear-gradient(180deg, #444 0%, #333 50%, #222 100%) !important;
                border-color: #555 !important;
            }
        `;
        document.head.appendChild(style);
    }
    
    applyLEDSegment() {
        const displayContent = document.getElementById('display-content');
        displayContent.classList.add('theme-led-segment');
        
        // Hide regular displays and create SVG-based 7-segment display
        document.getElementById('timer-display').classList.add('hidden');
        document.getElementById('clock-display').classList.add('hidden');
        document.getElementById('analog-clock').classList.add('hidden');
        document.getElementById('flip-clock').classList.add('hidden');
        
        // Update preview display
        document.getElementById('preview-time').classList.add('hidden');
        document.getElementById('preview-flip-clock').classList.add('hidden');
        document.getElementById('preview-7segment').classList.remove('hidden');
        document.getElementById('preview-analog').classList.add('hidden');
        
        // Hide the seven-segment display from other themes if it exists
        const existingDisplay = document.getElementById('seven-segment-display');
        if (existingDisplay && existingDisplay.parentNode) {
            existingDisplay.parentNode.removeChild(existingDisplay);
        }
        
        // Create or show the 7-segment display
        this.create7SegmentDisplay();
        
        const style = document.createElement('style');
        style.id = 'theme-style';
        style.textContent = `
            .display-panel {
                background: #000000 !important;
            }
            
            /* Force hide regular displays when LED theme is active */
            .theme-led-segment #timer-display,
            .theme-led-segment #clock-display {
                display: none !important;
            }
            
            #seven-segment-display {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100%;
                gap: 40px;
                padding: 40px;
            }
            
            .seven-segment-digit {
                width: 160px;
                height: 240px;
            }
            
            .seven-segment-separator {
                width: 40px;
                height: 240px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                gap: 40px;
            }
            
            .separator-dot {
                width: 16px;
                height: 16px;
                background: #ff0000;
                border-radius: 50%;
                box-shadow: 0 0 20px #ff0000, 0 0 40px #ff0000;
                margin: 0 auto;
            }
            
            .segment {
                fill: #330000;
                transition: all 0.2s ease;
            }
            
            .segment.active {
                fill: #ff0000;
                filter: drop-shadow(0 0 5px #ff0000) drop-shadow(0 0 10px #ff0000) drop-shadow(0 0 15px #ff0000);
            }
            
            /* Exercise title styling */
            .theme-led-segment .exercise-title {
                color: #ff3300 !important;
                text-shadow: 
                    0 0 5px #ff3300,
                    0 0 10px #ff3300;
                font-family: 'Orbitron', monospace;
                font-weight: 400;
                text-align: center;
                margin-bottom: 30px;
            }
        `;
        document.head.appendChild(style);
    }
    
    create7SegmentDisplay() {
        let sevenSegmentDisplay = document.getElementById('seven-segment-display');
        if (!sevenSegmentDisplay) {
            sevenSegmentDisplay = document.createElement('div');
            sevenSegmentDisplay.id = 'seven-segment-display';
            document.getElementById('display-content').appendChild(sevenSegmentDisplay);
        }
        
        // Create 6 digits for HH:MM:SS
        sevenSegmentDisplay.innerHTML = `
            <svg class="seven-segment-digit" id="digit-0" viewBox="0 0 80 120">
                ${this.createSegmentPaths()}
            </svg>
            <svg class="seven-segment-digit" id="digit-1" viewBox="0 0 80 120">
                ${this.createSegmentPaths()}
            </svg>
            <div class="seven-segment-separator">
                <div class="separator-dot"></div>
                <div class="separator-dot"></div>
            </div>
            <svg class="seven-segment-digit" id="digit-2" viewBox="0 0 80 120">
                ${this.createSegmentPaths()}
            </svg>
            <svg class="seven-segment-digit" id="digit-3" viewBox="0 0 80 120">
                ${this.createSegmentPaths()}
            </svg>
            <div class="seven-segment-separator">
                <div class="separator-dot"></div>
                <div class="separator-dot"></div>
            </div>
            <svg class="seven-segment-digit" id="digit-4" viewBox="0 0 80 120">
                ${this.createSegmentPaths()}
            </svg>
            <svg class="seven-segment-digit" id="digit-5" viewBox="0 0 80 120">
                ${this.createSegmentPaths()}
            </svg>
        `;
        
        sevenSegmentDisplay.classList.remove('hidden');
    }
    
    createSegmentPaths() {
        return `
            <path id="seg-a" class="segment" d="M10 10 L70 10 L65 15 L15 15 Z"/>
            <path id="seg-b" class="segment" d="M70 10 L75 15 L75 55 L70 60 L65 55 L65 15 Z"/>
            <path id="seg-c" class="segment" d="M70 60 L75 65 L75 105 L70 110 L65 105 L65 65 Z"/>
            <path id="seg-d" class="segment" d="M10 110 L70 110 L65 105 L15 105 Z"/>
            <path id="seg-e" class="segment" d="M5 65 L10 60 L15 65 L15 105 L10 110 L5 105 Z"/>
            <path id="seg-f" class="segment" d="M5 15 L10 10 L15 15 L15 55 L10 60 L5 55 Z"/>
            <path id="seg-g" class="segment" d="M10 60 L70 60 L65 55 L65 65 L15 65 L15 55 Z"/>
        `;
    }
    
    update7SegmentDisplay(time) {
        if (this.currentTheme !== 'led-segment') return;
        
        const hours = time.hours || 0;
        const minutes = time.minutes || 0;
        const seconds = time.seconds || 0;
        const hideHours = time.hideHours || false;
        
        // Show/hide hour digits based on hideHours flag
        const display = document.getElementById('seven-segment-display');
        if (display) {
            const digits = display.querySelectorAll('.seven-segment-digit');
            const separator = display.querySelector('.seven-segment-separator');
            
            if (hideHours && digits.length >= 2 && separator) {
                digits[0].style.display = 'none';
                digits[1].style.display = 'none';
                separator.style.display = 'none';
            } else if (digits.length >= 2 && separator) {
                digits[0].style.display = 'block';
                digits[1].style.display = 'block';
                separator.style.display = 'flex';
            }
        }
        
        const hoursStr = String(hours).padStart(2, '0');
        const minutesStr = String(minutes).padStart(2, '0');
        const secondsStr = String(seconds).padStart(2, '0');
        
        const digits = [
            parseInt(hoursStr[0]),
            parseInt(hoursStr[1]),
            parseInt(minutesStr[0]),
            parseInt(minutesStr[1]),
            parseInt(secondsStr[0]),
            parseInt(secondsStr[1])
        ];
        
        // Segment patterns for each digit (0-9)
        const segmentPatterns = {
            0: ['a', 'b', 'c', 'd', 'e', 'f'],
            1: ['b', 'c'],
            2: ['a', 'b', 'g', 'e', 'd'],
            3: ['a', 'b', 'g', 'c', 'd'],
            4: ['f', 'g', 'b', 'c'],
            5: ['a', 'f', 'g', 'c', 'd'],
            6: ['a', 'f', 'g', 'e', 'd', 'c'],
            7: ['a', 'b', 'c'],
            8: ['a', 'b', 'c', 'd', 'e', 'f', 'g'],
            9: ['a', 'b', 'c', 'd', 'f', 'g']
        };
        
        digits.forEach((digit, index) => {
            const digitElement = document.getElementById(`digit-${index}`);
            if (digitElement) {
                const segments = digitElement.querySelectorAll('.segment');
                const activeSegments = segmentPatterns[digit] || [];
                
                segments.forEach(segment => {
                    const segmentId = segment.id.split('-')[1];
                    if (activeSegments.includes(segmentId)) {
                        segment.classList.add('active');
                    } else {
                        segment.classList.remove('active');
                    }
                });
            }
        });
    }
    
    initializeFlipComponents() {
        try {
            const tickElements = document.querySelectorAll('#flip-clock .tick');
            console.log('Found tick elements:', tickElements.length);
            
            if (tickElements.length === 0) {
                console.warn('No tick elements found');
                return;
            }
            
            if (typeof Tick === 'undefined') {
                console.warn('Tick library not loaded');
                return;
            }
            
            this.flipComponents = [];
            
            tickElements.forEach((element, index) => {
                try {
                    const tick = new Tick(element, {
                        value: 0
                    });
                    this.flipComponents.push(tick);
                    console.log(`Initialized tick ${index}:`, tick);
                } catch (error) {
                    console.error(`Failed to initialize tick ${index}:`, error);
                }
            });
            
            console.log('Total flip components initialized:', this.flipComponents.length);
        } catch (error) {
            console.error('Error initializing flip components:', error);
        }
    }
    
    updateFlipClock(time) {
        if (this.currentTheme !== 'split-flap') return;
        
        const hours = time.hours || 0;
        const minutes = time.minutes || 0;
        const seconds = time.seconds || 0;
        const hideHours = time.hideHours || false;
        
        // Show/hide hours units based on hideHours flag
        const hoursUnits = document.querySelectorAll('#flip-clock .flip-clock-unit')[0];
        const hoursSeparator = document.querySelectorAll('#flip-clock .flip-clock-separator')[0];
        
        if (hoursUnits && hoursSeparator) {
            if (hideHours) {
                hoursUnits.style.display = 'none';
                hoursSeparator.style.display = 'none';
            } else {
                hoursUnits.style.display = 'flex';
                hoursSeparator.style.display = 'flex';
            }
        }
        
        // If PQINA Flip is available and components are initialized
        if (this.flipComponents && this.flipComponents.length === 6) {
            // Hours (2 digits: tens and ones)
            if (this.flipComponents[0]) this.flipComponents[0].value = Math.floor(hours / 10);
            if (this.flipComponents[1]) this.flipComponents[1].value = hours % 10;
            
            // Minutes (2 digits: tens and ones)
            if (this.flipComponents[2]) this.flipComponents[2].value = Math.floor(minutes / 10);
            if (this.flipComponents[3]) this.flipComponents[3].value = minutes % 10;
            
            // Seconds (2 digits: tens and ones)
            if (this.flipComponents[4]) this.flipComponents[4].value = Math.floor(seconds / 10);
            if (this.flipComponents[5]) this.flipComponents[5].value = seconds % 10;
        } else {
            // Fallback: Update text content directly
            this.updateFlipClockFallback(hours, minutes, seconds, hideHours);
        }
    }
    
    updateFlipClockFallback(hours, minutes, seconds, hideHours) {
        const tickElements = document.querySelectorAll('#flip-clock .tick');
        if (tickElements.length === 6) {
            const values = [
                Math.floor(hours / 10),
                hours % 10,
                Math.floor(minutes / 10),
                minutes % 10,
                Math.floor(seconds / 10),
                seconds % 10
            ];
            
            tickElements.forEach((tick, index) => {
                const currentValue = parseInt(tick.dataset.current);
                const newValue = values[index];
                
                if (currentValue !== newValue) {
                    this.performFlip(tick, currentValue, newValue);
                    tick.dataset.current = newValue;
                }
            });
        }
    }
    
    performFlip(tickElement, currentValue, newValue) {
        const card = tickElement.querySelector('.tick-card');
        const frontFace = tickElement.querySelector('.tick-face.front');
        const backFace = tickElement.querySelector('.tick-face.back');
        
        // Set up the flip
        backFace.textContent = newValue;
        card.classList.add('flipping');
        
        // Complete the flip after animation
        setTimeout(() => {
            frontFace.textContent = newValue;
            card.classList.remove('flipping');
        }, 200);
    }
    
    updateFlipLabels() {
        // Update labels when language changes
        if (this.currentTheme === 'split-flap' && typeof i18n !== 'undefined') {
            const labels = document.querySelectorAll('#flip-clock .flip-clock-label[data-i18n]');
            labels.forEach(label => {
                const key = label.getAttribute('data-i18n');
                if (key) {
                    label.textContent = i18n.translate(key);
                }
            });
        }
    }

    updateAnalogClock(time) {
        if (this.currentTheme !== 'analog-station') return;
        
        const hourHand = document.getElementById('hour-hand');
        const minuteHand = document.getElementById('minute-hand');
        const secondHand = document.getElementById('second-hand');
        const secondHandTip = document.getElementById('second-hand-tip');
        
        const hours = time.hours % 12;
        const minutes = time.minutes;
        const seconds = time.seconds;
        
        const hourDegrees = (hours * 30) + (minutes * 0.5);
        const minuteDegrees = minutes * 6;
        const secondDegrees = seconds * 6;
        
        hourHand.style.transform = `rotate(${hourDegrees}deg)`;
        minuteHand.style.transform = `rotate(${minuteDegrees}deg)`;
        secondHand.style.transform = `rotate(${secondDegrees}deg)`;
        
        // Also rotate the second hand tip circle to match
        if (secondHandTip) {
            secondHandTip.style.transform = `rotate(${secondDegrees}deg)`;
        }
        
        // Transform origin is already set in HTML as 100px 100px (center of clock)
    }
}

const themeManager = new ThemeManager();