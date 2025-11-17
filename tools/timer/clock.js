class Clock {
    constructor() {
        this.clockInterval = null;
        this.customTime = null;
        this.customDate = null;
        this.speedMultiplier = 1;
        this.startRealTime = null;
        this.startCustomTime = null;
        this.timeFormat = '24';
        this.showDate = false;
        this.showWeekday = false;
        this.isCustom = false;
        this.onUpdate = null;
    }

    setCustomTime(timeString, dateString) {
        const [hours, minutes] = timeString.split(':');
        const date = dateString ? new Date(dateString) : new Date();
        date.setHours(parseInt(hours), parseInt(minutes), 0, 0);
        this.customTime = date.getTime();
        this.customDate = date;
        this.isCustom = true;
    }

    setSpeed(multiplier) {
        if (this.isCustom && this.clockInterval) {
            this.startRealTime = Date.now();
            this.startCustomTime = this.getCurrentTime();
        }
        this.speedMultiplier = multiplier;
    }

    setFormat(format) {
        this.timeFormat = format;
    }

    setShowDate(show) {
        this.showDate = show;
    }

    setShowWeekday(show) {
        this.showWeekday = show;
    }

    start(isCustom = false) {
        this.isCustom = isCustom;
        
        if (isCustom && this.customTime) {
            this.startRealTime = Date.now();
            this.startCustomTime = this.customTime;
        }
        
        if (!this.clockInterval) {
            this.clockInterval = setInterval(() => this.update(), 100);
            this.update();
        }
    }

    stop() {
        if (this.clockInterval) {
            clearInterval(this.clockInterval);
            this.clockInterval = null;
        }
    }

    toggle() {
        if (this.clockInterval) {
            this.stop();
            return true; // paused
        } else {
            this.start(this.isCustom);
            return false; // not paused
        }
    }

    getCurrentTime() {
        if (this.isCustom && this.customTime) {
            const elapsed = (Date.now() - this.startRealTime) * this.speedMultiplier;
            return this.startCustomTime + elapsed;
        }
        return Date.now();
    }

    update() {
        const timestamp = this.getCurrentTime();
        const date = new Date(timestamp);
        
        if (this.onUpdate) {
            this.onUpdate(this.getFormattedTime(date));
        }
    }

    getFormattedTime(date) {
        const hours = date.getHours();
        const minutes = date.getMinutes();
        const seconds = date.getSeconds();
        
        let formattedTime;
        let period = '';
        
        if (this.timeFormat === '12') {
            const displayHours = hours === 0 ? 12 : hours > 12 ? hours - 12 : hours;
            period = hours >= 12 ? 'PM' : 'AM';
            formattedTime = `${String(displayHours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')} ${period}`;
        } else {
            formattedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }
        
        let dateString = '';
        if (this.showDate || this.showWeekday) {
            const parts = [];
            
            if (this.showWeekday) {
                parts.push(i18n.getWeekday(date.getDay()));
            }
            
            if (this.showDate) {
                const day = date.getDate();
                const month = i18n.getMonth(date.getMonth());
                const year = date.getFullYear();
                parts.push(`${day} ${month} ${year}`);
            }
            
            dateString = parts.join(', ');
        }
        
        return {
            time: formattedTime,
            date: dateString,
            hours: hours,
            minutes: minutes,
            seconds: seconds,
            timestamp: date.getTime()
        };
    }

    destroy() {
        this.stop();
    }
}