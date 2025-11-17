class Timer {
    constructor() {
        this.startTime = 0;
        this.elapsedTime = 0;
        this.timerInterval = null;
        this.isPaused = false;
        this.direction = 'down';
        this.targetTime = 0;
        this.speedMultiplier = 1;
        this.onUpdate = null;
        this.onComplete = null;
    }

    setTime(hours, minutes, seconds) {
        this.targetTime = (hours * 3600 + minutes * 60 + seconds) * 1000;
        this.elapsedTime = this.direction === 'down' ? this.targetTime : 0;
        this.updateDisplay();
    }

    setDirection(direction) {
        this.direction = direction;
        if (direction === 'down') {
            this.elapsedTime = this.targetTime;
        } else {
            this.elapsedTime = 0;
        }
        this.updateDisplay();
    }

    setSpeed(multiplier) {
        this.speedMultiplier = multiplier;
    }

    start() {
        if (!this.timerInterval) {
            this.startTime = Date.now() - (this.direction === 'up' ? this.elapsedTime : this.targetTime - this.elapsedTime) / this.speedMultiplier;
            this.timerInterval = setInterval(() => this.update(), 10);
            this.isPaused = false;
        }
    }

    pause() {
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
            this.timerInterval = null;
            this.isPaused = true;
        }
    }

    toggle() {
        if (this.timerInterval) {
            this.pause();
        } else {
            this.start();
        }
        return this.isPaused;
    }

    restart() {
        this.pause();
        this.elapsedTime = this.direction === 'down' ? this.targetTime : 0;
        this.updateDisplay();
        this.start();
    }

    reset() {
        this.pause();
        this.elapsedTime = this.direction === 'down' ? this.targetTime : 0;
        this.updateDisplay();
    }

    update() {
        const now = Date.now();
        const delta = (now - this.startTime) * this.speedMultiplier;
        
        if (this.direction === 'up') {
            this.elapsedTime = delta;
        } else {
            this.elapsedTime = Math.max(0, this.targetTime - delta);
            
            if (this.elapsedTime === 0) {
                this.pause();
                if (this.onComplete) {
                    this.onComplete();
                }
            }
        }
        
        this.updateDisplay();
    }

    updateDisplay() {
        if (this.onUpdate) {
            this.onUpdate(this.getFormattedTime());
        }
    }

    getFormattedTime() {
        const totalSeconds = Math.floor(this.elapsedTime / 1000);
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;
        
        // Hide hours if they are 00 and target time is less than 1 hour
        const hideHours = hours === 0 && this.targetTime < 3600000;
        
        let formatted;
        if (hideHours) {
            formatted = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        } else {
            formatted = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }
        
        return {
            hours: hours,
            minutes: minutes,
            seconds: seconds,
            formatted: formatted,
            hideHours: hideHours
        };
    }

    destroy() {
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
            this.timerInterval = null;
        }
    }
}