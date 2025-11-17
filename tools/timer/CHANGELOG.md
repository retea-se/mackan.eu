# Changelog

All notable changes to the Professional Timer & Clock Application will be documented in this file.

## [1.0.0] - 2025-01-28

### Added
- **Multiple Visual Themes**
  - Digital Standard theme
  - Analog Station clock (Swiss railway style)
  - 7-Segment retro display
  - Tactical theme
  - Nixie tubes theme
  - LCD display theme
  - Split-flap mechanical display
  - LED 7-segment display

- **Core Functionality**
  - Timer mode with countdown and count-up
  - Clock mode with real-time and custom time
  - Fullscreen support
  - Bilingual interface (Swedish/English)
  - Light and dark theme switching

- **Customization Options**
  - Exercise details with custom titles
  - Preparation phase with custom messages
  - Pause messages
  - Visual time signals
  - Progress display (bar/circle)
  - Display size scaling (25%, 50%, 75%, 100%)

- **Advanced Features**
  - Time manipulation (0.5x to 60x speed)
  - Speed affects timer option
  - Date and weekday display
  - Time format selection (12/24 hour)
  - Settings presets
  - Local storage for all preferences

- **Technical Improvements**
  - Keyboard shortcuts (Space, R, Esc/F11)
  - Responsive design
  - Offline support
  - Modern ES6+ JavaScript
  - CSS Grid and Flexbox layouts
  - Smooth animations and transitions

### Enhanced
- **Split-flap Display**: Doubled size (120px × 160px digits)
- **LED 7-Segment**: Doubled size (160px × 240px digits) with enhanced glow effects
- **Exercise Title Display**: Improved styling to look less like a button
- **Pause Indicator**: Full-screen width with blur effects
- **Theme Selector**: Fixed dark mode contrast issues

### Fixed
- Custom time not displaying correctly in clock mode
- Date/weekday not showing in all themes
- Exercise names not preserved when changing themes
- Space key not working to pause clock mode
- Dark theme contrast issues in theme selector
- Exercise title not showing in fullscreen clock mode

## Development Notes

- Built with vanilla JavaScript for maximum compatibility
- No external dependencies required
- Fully self-contained HTML5 application
- Optimized for both desktop and mobile devices