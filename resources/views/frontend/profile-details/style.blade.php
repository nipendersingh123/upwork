<style>
    /* Fix for toggle switch circle positioning */
    .toggle-switch {
        width: 60px;
        height: 34px;
    }

    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }

    .toggle-slider:before {
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
    }

    /* Adjust the slider size if needed */
    .toggle-slider {
        border-radius: 34px;
    }

    /* Color states */
    input:checked + .toggle-slider {
        background-color: #10b981;
    }

    .toggle-switch .toggle-slider {
        background-color: #ccc;
    }
    /* Video Styling for Profile Details */
    .carousel-slide {
        position: relative;
        height: 100%;
        background: #000;
    }

    .carousel-slide video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* Center Play Button Overlay (before hover) */
    .video-play-overlay-center {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.3);
        opacity: 1;
        transition: opacity 0.3s ease;
        z-index: 5;
        pointer-events: none;
    }

    .carousel-slide:hover .video-play-overlay-center,
    .carousel-slide.video-playing .video-play-overlay-center {
        opacity: 0;
        visibility: hidden;
    }

    .play-button-circle-center {
        width: 64px;
        height: 64px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .play-icon-center {
        width: 0;
        height: 0;
        border-left: 16px solid #000;
        border-top: 10px solid transparent;
        border-bottom: 10px solid transparent;
        margin-left: 4px;
    }

    /* Bottom Left Play Button with Progress */
    .video-play-progress {
        position: absolute;
        bottom: 12px;
        left: 12px;
        width: 40px;
        height: 40px;
        z-index: 6;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .carousel-slide:hover .video-play-progress,
    .carousel-slide.video-playing .video-play-progress {
        opacity: 1;
    }

    .progress-ring {
        transform: rotate(-90deg);
    }

    .progress-ring-circle-bg {
        fill: none;
        stroke: rgba(255, 255, 255, 0.3);
        stroke-width: 3;
    }

    .progress-ring-circle {
        fill: none;
        stroke: #fff;
        stroke-width: 3;
        stroke-linecap: round;
        transition: stroke-dashoffset 0.1s linear;
    }

    .play-button-small {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 32px;
        height: 32px;
        background: rgba(0, 0, 0, 0.7);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .play-icon-small {
        width: 0;
        height: 0;
        border-left: 10px solid #fff;
        border-top: 6px solid transparent;
        border-bottom: 6px solid transparent;
        margin-left: 2px;
    }

    /* Video duration badge */
    .video-duration {
        position: absolute;
        bottom: 12px;
        right: 56px;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        z-index: 6;
        letter-spacing: 0.5px;
    }

    /* Volume Control Button */
    .video-volume-control {
        position: absolute;
        bottom: 12px;
        right: 12px;
        width: 36px;
        height: 36px;
        background: rgba(0, 0, 0, 0.7);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 7;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .carousel-slide:hover .video-volume-control,
    .carousel-slide.video-playing .video-volume-control {
        opacity: 1;
    }

    .video-volume-control:hover {
        background: rgba(0, 0, 0, 0.9);
        transform: scale(1.1);
    }

    .video-volume-control svg {
        width: 20px;
        height: 20px;
        fill: #fff;
    }

    /* Hide video controls */
    .carousel-slide video::-webkit-media-controls {
        display: none !important;
    }

    .carousel-slide video::-webkit-media-controls-enclosure {
        display: none !important;
    }

    .carousel-slide video::-webkit-media-controls-panel {
        display: none !important;
    }
    /* Profile Box Layout Protection (scope to profile sidebar only) */
    aside.sticky {
        flex-shrink: 0;
    }

    aside.sticky .border.rounded-2xl.bg-white {
        min-width: 280px;
    }

    /* Prevent text congestion in sidebar cards */
    aside.sticky .border.rounded-2xl.bg-white h3,
    aside.sticky .border.rounded-2xl.bg-white h4 {
        overflow-wrap: break-word;
        word-break: break-word;
        hyphens: auto;
    }

    /* Stretch project cards so image column doesn't leave blank white area */
    .project_wrapper_area .card-animate > .flex {
        align-items: stretch;
    }

    .project_wrapper_area .card-animate figure {
        display: flex;
    }

    /* Force a consistent media area (approx 375x243) so large images don't grow the card */
    .project_wrapper_area .card-animate figure .carousel-container {
        width: 100%;
        height: 243px;
        min-height: 243px;
    }

    .project_wrapper_area .card-animate figure img,
    .project_wrapper_area .card-animate figure video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
