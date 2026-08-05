<?php
session_start();

// ============================================================
// This file used to include php/name_config.php separately.
// Those values are now set directly here as part of the
// codebase consolidation into just index.php and main.php.
// ============================================================
$bot_name = 'E-Bot'; 	// Give the bot a name
$user_name = 'Guest';	// Set the user's name
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Tell search engines not to index the site -->
    <meta name="robots" content="noindex, nofollow">
  
    <meta charset="utf-8">
    <title>E-Bot - English Practice Chatbot</title>
    <meta name="description" content="E-Bot is a free AI-powered chatbot for practicing English conversation. Non-profit, no ads, no signup, and no app download required. Start chatting instantly.">
	
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Image -->
    <link rel="shortcut icon" type="image/png" href="assets/ebot3.png">
	
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        /* ============================================================
           Default Theme Palette: Light Mode
           ============================================================ */
        :root {
            --bg: #f5f7fa;
            --bg2: #ffffff;
            --bg3: #eef1f6;
            --border: rgba(0, 0, 0, 0.08);
            --border2: rgba(0, 0, 0, 0.15);
            --text: #1a1d24;
            --text2: #5c6275;
            --text3: #8a91a5;
            --accent: #2563eb;
            --accent-dim: rgba(37, 99, 235, 0.08);
            --accent-glow: rgba(37, 99, 235, 0.18);
            --pill-bg: rgba(255, 255, 255, 0.92);
            --overlay-bg: rgba(245, 247, 250, 0.97);
        }

        /* ============================================================
           Dark Theme Palette Override: Near-black Navy (adopted from
           the Noise for Sleep app: two lighter "surface" layers for
           cards/panels rather than one flat background color
           everywhere, soft off-white text with two dimmer secondary
           shades, and a blue accent).
           ============================================================ */
        body.dark-mode {
        	--bg: #0d0f14; --bg2: #141720; --bg3: #1c2030;
        	--border: rgba(255,255,255,0.08); --border2: rgba(255,255,255,0.14);
        	--text: #e8eaf0; --text2: #8b90a4; --text3: #555b70;
        	--accent: #5b8cff; --accent-dim: rgba(91,140,255,0.12); --accent-glow: rgba(91,140,255,0.25);
        	--pill-bg: rgba(20, 23, 32, 0.92);
        	--overlay-bg: rgba(13, 15, 20, 0.97);
        }

        /* ============================================================
           Custom replacements for the w3.css utility classes that
           were previously used (w3.css has been dropped entirely).
           ============================================================ */
        .w3-small { font-size: 12px; }
        .w3-padding-left { padding-left: 8px; }
        .w3-padding-right { padding-right: 8px; }
        .w3-padding-bottom { padding-bottom: 8px; }
        .w3-padding { padding: 8px; }
        .w3-text-white { color: var(--text); }
        .w3-text-blue { color: var(--accent); }
        .w3-text-teal { color: var(--accent); }
        .w3-center { text-align: center; }
        .w3-round { border-radius: 4px; }
        .w3-animate-opacity { animation: w3-fade-in 0.5s; }
        @keyframes w3-fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* ============================================================
           App styles (formerly css/e-bot.css)
           ============================================================ */
        body {
        	background-color: var(--bg);
        	font-family: Helvetica, Arial, sans-serif;
        	font-size: 18px;
        	color: var(--text);
        	padding-top: 60px; /* leave room for the fixed language pill */
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        main {
        	margin-bottom: 200px;
        	color: var(--text);
        	padding: 10px;
        }

        h4 {
        	font-size: 20px;
        }

        #main-image h2 {
        	color: var(--text);
        	letter-spacing: -0.02em;
        	font-size: clamp(2.4rem, 9vw, 3.2rem);
        	margin: 0 0 2px 0;
        }

        .hero-subtitle {
        	color: var(--text2);
        	font-weight: normal;
        	font-size: 18px;
        	margin: 0;
        }

        #about-link {
        	display: inline-block;
        	color: var(--text3);
        	font-size: 13px;
        	margin-top: 6px;
        	text-decoration: none;
        }

        #about-link:hover,
        #about-link:focus-visible {
        	color: var(--accent);
        	outline: none;
        }

        a {
        	text-decoration: none;
        }

        .responsive {
        	 width: 100%; /*Makes media scalable as the viewport size changes*/
        	 height: auto;
        	 max-width: 200px; 
        } 

        .hero-image {
        	width: 100%;
        	height: auto;
        	max-width: 280px;
        	margin: 0 auto 4px auto;
        	display: block;
        }

        .container {
        	width: 100%;
        	max-width: 600px;
        	margin: 0 auto;
        	padding: 0 20px;
        }

        .sticky-bar {
        	position: fixed;
        	bottom: 0;
        	left: 0;
        	width: 100%;
            box-sizing: border-box; /* Fix content-box overflow clipping on mobile */
        	background-color: var(--bg);
        	color: var(--text);
        	padding: 10px 0; /* Align top/bottom, horizontal spacing is handled by input-group */
        	text-align: center;
        	border-top: 1px solid transparent;
            transition: background-color 0.3s ease, border-top-color 0.2s ease;
        }

        /* Thin top border on the sticky area while the Settings panel
           is open, so the bar visually separates itself from the chat
           above it. Disappears again once the panel is closed. */
        .sticky-bar.settings-open {
        	border-top-color: var(--border2);
        }

        .input-group {
            display: flex;
            align-items: center;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 0 20px; /* Matches default container margins on desktop */
            box-sizing: border-box;
        }

        .sticky-bar input[type="text"] {
        	box-sizing: border-box;
        	height: 46px;
        	padding: 0 15px;
        	border-radius: 5px;
        	border: 1px solid var(--border2);
        	background-color: var(--bg3);
        	color: var(--text);
        	flex: 1;
        	min-width: 0;
        	font-size: 18px;
        }

        .sticky-bar input[type="submit"] {
        	box-sizing: border-box;
        	height: 46px;
        	background-color: var(--accent);
        	color: #fff;
        	border: 1px solid transparent;
        	padding: 0 20px;
        	border-radius: 5px;
        	cursor: pointer;
        	font-size: 16px;
        	margin-left: 10px;
        	flex-shrink: 0;
        }

        .message-container {
        	margin-bottom: 10px;
        	padding: 5px 20px;
        	background-color: var(--bg2);
        	color: var(--text);
        	border: 1px solid var(--border);
        	border-radius: 5px;
        	line-height: 1.8;
        	letter-spacing: 0.02em;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        /* --- Image attach button, pending-image preview, and
               sent-image thumbnails inside chat bubbles --- */

        #image-picker-label {
        	display: flex;
        	align-items: center;
        	justify-content: center;
        	width: 46px;
        	height: 46px;
        	flex-shrink: 0;
        	margin-right: 10px;
        	border-radius: 5px;
        	border: 1px solid var(--border2);
        	background-color: var(--bg3);
        	color: var(--text);
        	cursor: pointer;
        }

        /* --- Suggested prompt chips, shown above the input until the
               first message is sent --- */

        .suggested-prompts {
        	display: flex;
        	flex-direction: column;
        	gap: 4px;
        	width: 100%;
        	max-width: 600px;
        	margin: 0 auto 10px auto;
        	padding: 0 20px;
        	box-sizing: border-box;
        }

        .suggested-prompt-btn {
        	box-sizing: border-box;
        	background: none;
        	border: none;
        	padding: 2px 0;
        	color: var(--text2);
        	font-size: 13px;
        	text-align: left;
        	white-space: nowrap;
        	overflow: hidden;
        	text-overflow: ellipsis;
        	cursor: pointer;
        }

        .suggested-prompt-btn:hover {
        	color: var(--accent);
        }

        .image-preview-row {
        	display: none;
        	align-items: center;
        	gap: 10px;
        	width: 100%;
        	max-width: 600px;
        	margin: 0 auto 8px auto;
        	padding: 0 20px;
        	box-sizing: border-box;
        }

        .image-preview-row img {
        	height: 56px;
        	width: 56px;
        	object-fit: cover;
        	border-radius: 6px;
        	border: 1px solid var(--border2);
        }

        .image-preview-row button {
        	width: 24px;
        	height: 24px;
        	border-radius: 50%;
        	border: 1px solid var(--border2);
        	background-color: var(--bg3);
        	color: var(--text);
        	line-height: 1;
        	font-size: 14px;
        	cursor: pointer;
        }

        .chat-image-thumb {
        	display: block;
        	max-width: 220px;
        	max-height: 220px;
        	border-radius: 6px;
        	margin: 8px 0;
        	object-fit: cover;
        }

        /* --- Drag-and-drop indicator (no full-page overlay) --- */

        #drag-drop-indicator {
        	position: fixed;
        	inset: 0;
        	z-index: 9999;
        	pointer-events: none;
        	border: 3px dashed var(--accent);
        	box-shadow: inset 0 0 40px var(--accent-glow);
        	opacity: 0;
        	transition: opacity 0.15s ease;
        }

        body.page-drag-active #drag-drop-indicator {
        	opacity: 1;
        }

        body.page-drag-active #image-picker-label {
        	border-color: var(--accent);
        	background-color: var(--accent-dim);
        	box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .set-color1 {
        	color: var(--accent);
        }

        .set-color2 {
        	color: var(--text2);
        }

        #chat-buttons {
          display: flex;
          justify-content: center;
          align-items: center;
          margin-top: 10px;
        }

        #chat-buttons button {
          margin-right: 20px;
          padding: 0px 20px;
          border-radius: 5px;
          cursor: pointer;
          font-size: 15px;
          background-color: var(--bg3);
          color: var(--text);
          border: none;
        }

        #chat-buttons input[type="file"] {
          display: none;
        }

        #chat-buttons label {
          display: inline-block;
          padding: 0px 20px;
          border-radius: 5px;
          cursor: pointer;
          font-size: 15px;
          background-color: var(--bg3);
          color: var(--text);
          border: none;
        }
        	
        #chat-buttons input[type="file"] + label {
        	margin-right: 10px;
        }

        #chat-buttons input[type="file"] + label:before {
          	content: "Load a saved chat";
        }

        .sticky-image {
        	position: fixed;
        	top: 0;
        	left: 0;
        }
        	
        .beta-text {
        	font-size: 15px;
        }


        .lighter-black {
        	color: var(--text2); 
        }

        #language-dropdown {
        	margin-top: 10px;
        	font-size: 15px;
        }

        .space-letters {
        	letter-spacing: .03em;
        }
        	
        .wrapper {
          display: flex;
          justify-content: center;
          width: 100%;
          max-width: 600px;
          margin: 3px auto 0 auto;
          padding: 0 20px;
          box-sizing: border-box;
        }

        .form-elements {
          display: flex;
          flex-direction: row;
          justify-content: center;
          align-items: center;
          gap: 10px; /* Space between the radio buttons and dropdown */
          width: 100%;
        }

        .radio-group {
          display: flex;
          flex-direction: row;
          justify-content: flex-start;
          align-items: center;
          gap: 10px; /* Space between the radio buttons */
        }

        .radio-option {
          display: flex;
          align-items: center;
          gap: 8px;
          padding: 8px;
          /*border: 1px solid #ccc;
          border-radius: 4px;*/
          cursor: pointer;
        }

        .radio-option input[type="radio"] {
          margin-right: 0;
          accent-color: var(--accent);
        }

        .dropdown-option {
          display: flex;
          align-items: center;
          flex: 1 1 auto;
          min-width: 0; /* lets the select actually shrink instead of forcing the row wide - this was the root cause of the dropdown ballooning to full width alone on mobile */
        }

        .styled-dropdown {
          width: 100%;
          padding: 8px 30px 8px 10px;
          border: 1px solid var(--border2);
          border-radius: 4px;
          cursor: pointer;
          background-color: var(--bg3);
          color: var(--text);
          appearance: none;
          -webkit-appearance: none;
          -moz-appearance: none;
          background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 8'%3E%3Cpath fill='%238b90a4' d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
          background-repeat: no-repeat;
          background-position: right 10px center;
          background-size: 10px 7px;
        }

        .styled-dropdown:hover,
        .styled-dropdown:focus,
        .styled-dropdown:focus-visible {
          border-color: var(--accent);
          outline: none;
        }

        /* Settings panel rows (Voice, Speed): label on the left, a
           *single* controls group on the right that holds the
           dropdown/slider plus its companion button/value-label. Using
           one wrapper for "everything after the label" means that if
           the row runs out of space, the whole controls group wraps
           together onto its own line - rather than the label, the
           dropdown, and the button each independently deciding where
           to wrap and landing on three separate lines. */
        .settings-row {
          display: flex;
          flex-wrap: wrap;
          align-items: center;
          gap: 10px;
        }

        .settings-row-label {
          display: flex;
          align-items: center;
          flex: 0 0 auto;
          min-width: 60px;
          cursor: pointer;
        }

        .settings-row-controls {
          display: flex;
          align-items: center;
          gap: 8px;
          flex: 1 1 auto;
          min-width: 0;
        }

        .title-color {
        	color: var(--accent);
        }

        .tag-color {
        	background-color: var(--bg3);
        }

        .hide {
            display: none;
        }

        /* ============================================================
           Voice picker row (Settings panel): dropdown of available
           TTS voices for English, plus a small preview button so the
           user can hear a voice before committing to it.
           ============================================================ */
        #preview-voice-btn {
        	display: flex;
        	align-items: center;
        	justify-content: center;
        	width: 34px;
        	height: 34px;
        	flex-shrink: 0;
        	border-radius: 4px;
        	border: 1px solid var(--border2);
        	background-color: var(--bg3);
        	color: var(--text);
        	cursor: pointer;
        }

        #preview-voice-btn:hover {
        	border-color: var(--accent);
        	color: var(--accent);
        }

        #preview-voice-btn:disabled {
        	opacity: 0.4;
        	cursor: not-allowed;
        }

        /* ============================================================
           Speed picker row (Settings panel): a range slider so users
           can slow speech down for practice or speed it back up.
           ============================================================ */
        #speed-select {
        	flex: 1;
        	min-width: 100px;
        	accent-color: var(--accent);
        	cursor: pointer;
        }

        #speed-value-label {
        	min-width: 34px;
        	text-align: right;
        	font-size: 13px;
        	color: var(--text);
        	font-variant-numeric: tabular-nums;
        }

        /* ============================================================
           Language pair pill + full-screen picker overlay.
           Same interaction pattern as the source picker in the
           faithscrolling app: a small pill is always visible showing
           the current selection, tapping it opens a full-screen
           overlay with large, accessible options to choose from.
           ============================================================ */
        .language-pill-header {
        	position: fixed;
        	top: 12px;
        	left: 0;
        	right: 0;
        	z-index: 500;
        	display: flex;
        	justify-content: center;
        	pointer-events: none;
        }

        .language-pill {
        	pointer-events: auto;
        	background-color: var(--pill-bg); /* var(--bg2), translucent */
        	backdrop-filter: blur(6px);
        	-webkit-backdrop-filter: blur(6px);
        	color: var(--text);
        	padding: 8px 18px;
        	border-radius: 30px;
        	box-shadow: 0 4px 14px rgba(0,0,0,0.25);
        	border: 1px solid var(--accent-glow);
        	font-size: 15px;
        	letter-spacing: 0.03em;
        	cursor: pointer;
        	display: flex;
        	align-items: center;
        	gap: 8px;
        	transition: border-color 0.2s ease, background-color 0.3s ease, color 0.3s ease;
        }

        .language-pill:hover,
        .language-pill:focus-visible {
        	border-color: var(--accent);
        	outline: none;
        }

        .language-pill .pill-caret {
        	color: var(--accent);
        	font-size: 0.75em;
        }

        .language-overlay {
        	position: fixed;
        	top: 0;
        	left: 0;
        	width: 100%;
        	height: 100%;
        	background-color: var(--overlay-bg); /* var(--bg), translucent */
        	backdrop-filter: blur(5px);
        	-webkit-backdrop-filter: blur(5px);
        	z-index: 600;
        	display: flex;
        	flex-direction: column;
        	justify-content: flex-start;
        	align-items: center;
        	opacity: 0;
        	visibility: hidden;
        	transition: opacity 0.3s ease, visibility 0.3s ease, background-color 0.3s ease;
        	padding: 76px 20px 20px; /* top padding clears the fixed close button below */
        	overflow-y: auto; /* safety net so nothing is ever unreachable */
        	box-sizing: border-box;
        }

        .language-overlay.open {
        	opacity: 1;
        	visibility: visible;
        }

        .language-overlay-subtitle {
        	color: var(--text2);
        	font-size: 15px;
        	margin-bottom: 15px;
        	text-align: center;
        }

        .language-search-input {
        	width: 100%;
        	max-width: 320px;
        	padding: 10px 16px;
        	margin-bottom: 15px;
        	border-radius: 24px;
        	border: 1px solid var(--border2);
        	background-color: var(--bg3);
        	color: var(--text);
        	font-size: 16px;
        	text-align: center;
        	box-sizing: border-box;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .language-search-input:focus {
        	outline: none;
        	border-color: var(--accent);
        }

        #language-menu-options {
        	max-height: 60vh;
        	overflow-y: auto;
        	display: flex;
        	flex-direction: column;
        	align-items: center;
        	width: 100%;
        }

        .language-overlay-no-results {
        	color: var(--text2);
        	font-size: 15px;
        	margin-top: 10px;
        }

        .language-overlay-option {
        	font-size: 20px;
        	margin: 8px 0;
        	padding: 10px 24px;
        	border-radius: 6px;
        	cursor: pointer;
        	color: var(--text);
        	text-align: center;
        	transition: color 0.15s ease, background-color 0.15s ease;
        }

        .language-overlay-option:hover,
        .language-overlay-option.active,
        .language-overlay-option:focus-visible {
        	color: var(--accent);
        	background-color: var(--accent-dim);
        	outline: none;
        }

        .close-language-overlay {
        	position: fixed;
        	top: 16px;
        	right: 16px;
        	width: 48px;
        	height: 48px;
        	border-radius: 50%;
        	border: 1px solid var(--border2);
        	background: var(--overlay-bg);
        	color: var(--text);
        	font-size: 22px;
        	cursor: pointer;
        	z-index: 601; /* stays above the scrollable option list */
        	transition: background-color 0.3s ease, color 0.3s ease;
        }

        .close-language-overlay:hover,
        .close-language-overlay:focus-visible {
        	border-color: var(--accent);
        	color: var(--accent);
        	outline: none;
        }

        /* The original <select> stays in the DOM and keeps working
           exactly as before (same id, same form field name) - it's
           just no longer shown, since the pill + overlay now handle
           the visible UI. */
        .visually-hidden-select {
        	position: absolute;
        	width: 1px;
        	height: 1px;
        	overflow: hidden;
        	clip: rect(0 0 0 0);
        	white-space: nowrap;
        }

        /* #line1 used to have its own display:block rule here too, but
           since it also carries the .radio-group class (display:flex),
           the ID selector's higher specificity was silently winning and
           stacking the toggles vertically instead of in a row. Only
           #line2 (the visually-hidden language dropdown's wrapper)
           actually needs to stay block. */
        #line2 {
        	display: block;
        }

        #line1 {
        	flex-wrap: wrap;
        }

        .display-block {
        	display: block;
        }

        /* Initially hide the panel and set up the transition */
        #panel {
          max-height: 0;
          overflow: hidden;
          transition: max-height 0.5s ease-out;
          width: 100%;
        }

        /* Class to show the panel */
        .panel-open {
          max-height: 1000px; /* Set a high value for max-height */
        }

        /* Card styling lives on this inner wrapper rather than on #panel
           itself: #panel is the thing whose max-height animates for the
           open/close transition, and padding placed directly on an
           element with max-height:0 still renders (padding isn't part
           of what max-height constrains), leaving a visible sliver even
           while "closed". Keeping padding/background one level in avoids
           that. */
        #panel-inner {
          display: flex;
          flex-direction: column;
          gap: 10px;
          margin-top: 10px;
          padding: 14px 16px;
          background-color: var(--bg2);
          border: 1px solid var(--border);
          border-radius: 10px;
        }

        #accordion {
          margin-right: 20px;
          padding: 8px 14px;
          border-radius: 8px;
          cursor: pointer;
          font-size: 18px;
          background-color: transparent;
          color: var(--text);
          border: none;
          transition: background-color 0.15s ease, color 0.15s ease;
        }

        #accordion:hover,
        #accordion:focus,
        #accordion:focus-visible {
          background-color: var(--bg3);
          outline: none;
          box-shadow: none;
        }

        /* Settings button stays visibly "active" while its panel is
           open, so the gear affordance actually signals open/closed
           state instead of looking the same either way (no blue
           tint - just a neutral background, matching the button's
           hover state). */
        #accordion.settings-active {
          background-color: var(--bg3);
        }

        #start-voicechat-btn {
          margin-right: 10px;
          padding: 8px 14px;
          border-radius: 8px;
          cursor: pointer;
          font-size: 18px;
          background-color: transparent;
          color: var(--text);
          border: none;
          transition: background-color 0.15s ease;
        }

        #start-voicechat-btn:hover,
        #start-voicechat-btn:focus,
        #start-voicechat-btn:focus-visible {
          background-color: var(--bg3);
          outline: none;
          box-shadow: none;
        }

        #practice-plan-btn {
          margin-right: 10px;
          padding: 8px 14px;
          border-radius: 8px;
          cursor: pointer;
          font-size: 18px;
          background-color: transparent;
          color: var(--text);
          border: none;
          transition: background-color 0.15s ease;
        }

        #practice-plan-btn:hover,
        #practice-plan-btn:focus,
        #practice-plan-btn:focus-visible {
          background-color: var(--bg3);
          outline: none;
          box-shadow: none;
        }

        /* ============================================================
           7-Day Practice Plan overlay.
           Same full-screen dialog pattern as .language-overlay: fixed,
           translucent, blurred backdrop, fades in/out via .open,
           fixed circular close button top-right.
           ============================================================ */
        .plan-overlay {
        	position: fixed;
        	top: 0;
        	left: 0;
        	width: 100%;
        	height: 100%;
        	background-color: var(--overlay-bg);
        	backdrop-filter: blur(5px);
        	-webkit-backdrop-filter: blur(5px);
        	z-index: 600;
        	display: flex;
        	flex-direction: column;
        	align-items: center;
        	opacity: 0;
        	visibility: hidden;
        	transition: opacity 0.3s ease, visibility 0.3s ease, background-color 0.3s ease;
        	padding: 76px 20px 40px;
        	overflow-y: auto;
        	box-sizing: border-box;
        }

        .plan-overlay.open {
        	opacity: 1;
        	visibility: visible;
        }

        .close-plan-overlay {
        	position: fixed;
        	top: 16px;
        	right: 16px;
        	width: 48px;
        	height: 48px;
        	border-radius: 50%;
        	border: 1px solid var(--border2);
        	background: var(--overlay-bg);
        	color: var(--text);
        	font-size: 22px;
        	cursor: pointer;
        	z-index: 601;
        	transition: background-color 0.3s ease, color 0.3s ease;
        }

        .close-plan-overlay:hover,
        .close-plan-overlay:focus-visible {
        	border-color: var(--accent);
        	color: var(--accent);
        	outline: none;
        }

        .plan-overlay-subtitle {
        	color: var(--text2);
        	font-size: 15px;
        	margin: 0 0 18px 0;
        	text-align: center;
        	max-width: 380px;
        }

        .plan-progress-wrap {
        	width: 100%;
        	max-width: 480px;
        	margin-bottom: 20px;
        }

        .plan-progress-label {
        	display: flex;
        	justify-content: space-between;
        	font-size: 13px;
        	color: var(--text2);
        	margin-bottom: 6px;
        }

        .plan-progress-label span:last-child {
        	color: var(--text);
        	font-weight: 600;
        }

        .plan-progress-track {
        	height: 6px;
        	border-radius: 3px;
        	background-color: var(--bg3);
        	border: 1px solid var(--border);
        	overflow: hidden;
        }

        .plan-progress-fill {
        	height: 100%;
        	width: 0%;
        	background-color: var(--accent);
        	border-radius: 3px;
        	transition: width 0.35s ease, background-color 0.35s ease;
        }

        .plan-progress-fill.complete {
        	background-color: #16a34a;
        }

        #plan-list {
        	list-style: none;
        	margin: 0;
        	padding: 0;
        	width: 100%;
        	max-width: 480px;
        	display: flex;
        	flex-direction: column;
        	gap: 10px;
        }

        .plan-item {
        	display: flex;
        	align-items: center;
        	gap: 14px;
        	padding: 12px 14px;
        	background-color: var(--bg2);
        	border: 1px solid var(--border);
        	border-radius: 10px;
        	transition: background-color 0.25s ease, border-color 0.25s ease;
        }

        .plan-item.checked {
        	background-color: rgba(22, 163, 74, 0.08);
        	border-color: rgba(22, 163, 74, 0.25);
        }

        .plan-checkbox {
        	flex-shrink: 0;
        	width: 28px;
        	height: 28px;
        	border-radius: 50%;
        	border: 1.5px solid var(--border2);
        	color: var(--text3);
        	font-size: 13px;
        	font-weight: 600;
        	display: flex;
        	align-items: center;
        	justify-content: center;
        	cursor: pointer;
        	background: none;
        	transition: all 0.25s ease;
        	padding: 0;
        }

        .plan-item.checked .plan-checkbox {
        	background-color: #16a34a;
        	border-color: #16a34a;
        	color: #fff;
        }

        .plan-item.checked .plan-checkbox .plan-num {
        	display: none;
        }

        .plan-item.checked .plan-checkbox::after {
        	content: "\2713";
        }

        .plan-prompt {
        	flex: 1;
        	background: none;
        	border: none;
        	text-align: left;
        	font-family: Helvetica, Arial, sans-serif;
        	font-size: 16px;
        	line-height: 1.5;
        	color: var(--text);
        	cursor: pointer;
        	padding: 4px 0;
        }

        .plan-item.checked .plan-prompt {
        	color: var(--text3);
        	text-decoration: line-through;
        	text-decoration-color: var(--text3);
        	text-decoration-thickness: 1px;
        }

        .plan-prompt:hover {
        	color: var(--accent);
        }

        .plan-item.checked .plan-prompt:hover {
        	color: var(--text3);
        }

        .instruction-text {
        	font-size: 17px;
        }

        #audioIndicator {
          bottom: 20px;
          right: 20px;
        }

        .bar {
          width: 4px;
          height: 15px;
          background-color: var(--accent);
          display: inline-block;
          margin-right: 2px;
          animation: soundWave 1s infinite alternate;
        }

        @keyframes soundWave {
          0% { height: 5px; }
          50% { height: 20px; }
          100% { height: 5px; }
        }

        #audioIndicator1 {
          bottom: 20px;
          right: 20px;
        }

        .bar1 {
          width: 4px;
          height: 15px;
          background-color: var(--text2);
          display: inline-block;
          margin-right: 2px;
          
        }

        .clickable {
          cursor: pointer;
        }

        /* ============================================================
           Per-message speaker icon: pulses while its audio is playing,
           doubling as both the "audio is playing" cue and the
           "click here to mute" affordance (replaces the old separate
           mute button in the sticky bar).

           Opacity-only on purpose: this icon has display:block (see
           .display-block below) so it drops onto its own line under
           the chat text, which makes its box as wide as the paragraph
           while the glyph itself sits left-aligned inside that box.
           transform: scale() scales around the box's center, not the
           glyph, so it visibly drifts sideways as it grows/shrinks.
           Opacity doesn't touch layout/geometry at all, so it can't
           cause that drift regardless of box width.
           ============================================================ */
        .speaker-icon.speaking {
        	animation: speaker-pulse 1s ease-in-out infinite;
        }

        @keyframes speaker-pulse {
        	0%, 100% { opacity: 1; }
        	50% { opacity: 0.35; }
        }


        /* When the page loads
        show audioIndicator1 and
        hide #audioIndicator
        */

        #audioIndicator1 {
            display: inline-block;
            vertical-align: middle;
        }

        #audioIndicator {
        	display: none;
        	vertical-align: middle;
        }

        /* 
        --------------
        MEDIA QUERIES
        --------------
        */

        /*Cellphone Screens in portrait*/	
        @media only screen and (max-width: 480px) and (orientation: portrait){

        	.container {
        	        padding: 0;
        	}
        		
        	.hide-on-phone {
        		display: none;
        	}

            .input-group {
                padding: 0 12px; /* Add a dedicated structural layout margin from phone borders */
            }

            .wrapper {
                padding: 0 12px; /* Match .input-group's phone padding above */
            }

            /* The toggle row (Auto Speak / Correction / Translation)
               fits 2 across on most phones with a 3rd wrapping alone,
               which reads as lopsided. Stack them into a clean single
               column instead - also makes each toggle a taller,
               easier tap target. */
            #line1 {
                flex-direction: column;
                align-items: flex-start;
                gap: 2px;
            }
        } /*Close media query*/
    </style>
	
</head>

<body>
<div id="drag-drop-indicator" aria-hidden="true"></div>


    <!-- Language pair pill: always visible, shows the current
         language pair. Tapping/clicking it opens the full-screen
         language picker overlay below. -->
    <header class="language-pill-header" id="language-pill-header">
        <div class="language-pill" id="language-pill" role="button" tabindex="0" aria-haspopup="true" aria-expanded="false" aria-label="Current language pair: English and Thai. Change language.">
            <span>English &#8646; <span id="current-language-label">Thai</span></span>
            <span class="pill-caret" aria-hidden="true">&#9660;</span>
        </div>
    </header>

    <!-- Language Picker Overlay -->
    <div class="language-overlay" id="language-overlay" role="dialog" aria-modal="true" aria-label="Select your language">
        <button class="close-language-overlay" id="close-language-overlay" aria-label="Close language menu">&#10005;</button>
        <p class="language-overlay-subtitle">Supports 86 languages.<br>Translation can be turned off in Settings.</p>
        <input type="text" id="language-search-input" class="language-search-input" placeholder="Search languages&hellip;" aria-label="Search languages" autocomplete="off">
        <div role="menu" aria-label="Language Options" id="language-menu-options">
            <!-- Populated by JS from the existing #language-select
                 options, so the <select> stays the single source of
                 truth for the language list. Filtered live by the
                 search input above. -->
        </div>
        <p class="language-overlay-no-results hide" id="language-overlay-no-results">No languages found</p>
    </div>

    <div class="plan-overlay" id="plan-overlay" role="dialog" aria-modal="true" aria-label="7-Day Practice Plan">
        <button class="close-plan-overlay" id="close-plan-overlay" aria-label="Close practice plan">&#10005;</button>
        <p class="plan-overlay-subtitle">Click a prompt to send it to E-Bot. Click the circle to mark as complete.</p>
        <div class="plan-progress-wrap">
            <div class="plan-progress-label">
                <span>Progress</span>
                <span id="plan-progress-count">0 / 7</span>
            </div>
            <div class="plan-progress-track">
                <div class="plan-progress-fill" id="plan-progress-fill"></div>
            </div>
        </div>
        <ul id="plan-list">
            <!-- Populated by JS -->
        </ul>
    </div>

    <div class="container w3-animate-opacity">
        <div id="main-image" class="w3-center w3-round w3-padding">

			<img src="assets/ebot2.png" alt="E-Bot" class="hero-image" loading="eager" fetchpriority="high">

			<h2 class="space-letters"><b>E-Bot</b></h2>

            <h4 class="space-letters hero-subtitle">Practice Speaking English</h4>

            <a href="about.php" id="about-link">About</a>

        </div>
        <main id="chat" class="texts">
            <!-- Add more message containers here -->
            <!-- The div for the spinner gets added and deleted here. -->
        </main>
        <div class="sticky-bar">
			
            <div id="image-preview-row" class="image-preview-row">
                <img id="image-preview-thumb" src="" alt="Selected image preview">
                <button type="button" id="remove-image-btn" aria-label="Remove image">&#10005;</button>
            </div>

            <div id="suggested-prompts" class="suggested-prompts">
                <button type="button" class="suggested-prompt-btn" onclick="submit_text_to_php('Hello')">Hello</button>
                <button type="button" class="suggested-prompt-btn" onclick="submit_text_to_php(&quot;Let's talk about my family&quot;)">Let's talk about my family</button>
                <button type="button" class="suggested-prompt-btn" onclick="submit_text_to_php(&quot;Let's roleplay ordering coffee&quot;)">Let's roleplay ordering coffee</button>
       
            </div>

            <form id="myForm" action="main.php" method="post">
                <div class="input-group">
                    <label id="image-picker-label" for="image-input" aria-label="Attach an image" title="Attach an image">
                        <i class="fa fa-camera" style="font-size:20px;"></i>
                    </label>
                    <input id="image-input" type="file" accept="image/*" style="display:none;">
                    <input id="user-input" type="text" name="my_message" placeholder="Type or talk in English" autofocus>
                    <input type="hidden" name="robotblock">
                    <input id="submit-btn" type="submit" value="Send">
                </div>
                <div class="w3-padding space-letters">
                    <button type="button" class="" id="start-voicechat-btn" onclick="toggle_voicechat(lang_code)" aria-label="Start Voicechat" title="Start Voicechat"><i class="fa fa-microphone" style="font-size:25px;"></i></button>
                    <button type="button" class="" id="practice-plan-btn" aria-label="7-Day Practice Plan" title="7-Day Practice Plan"><i class="fa fa-list-ul" style="font-size:22px;"></i></button>
                    <button type="button" class="" id="accordion" aria-label="Settings" aria-expanded="false"><i class="fa fa-gear" style="font-size:25px;"></i></button>
                    <div id="audioIndicator">
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                    </div>
                    <div id="audioIndicator1">
                        <div class="bar1"></div>
                        <div class="bar1"></div>
                        <div class="bar1"></div>
                    </div>
                </div>
                <div class="wrapper">
                    <div class="form-elements">
                        <div id="panel">
                          <div id="panel-inner">
                            <div id="line1" class="radio-group">
                                <label class="radio-option">
                                    <input id="speakid" class="w3-padding" type="radio" name="speak1" value="speak" onclick="toggleRadio(this)">
                                    Auto Speak
                                </label>
                                <label class="radio-option">
                                    <input id="correctid" type="radio" name="correct1" value="correct" onclick="toggleRadio(this)">
                                    Correction
                                </label>
                                <label class="radio-option">
                                    <input id="translateid" type="radio" name="translate1" value="translate" onclick="toggleRadio(this)">
                                    Translation
                                </label>
                            </div>
                            <div id="line3" class="radio-group" style="margin-top: 5px; margin-bottom: 5px;">
                                <label class="radio-option">
                                    <input id="theme-toggle" type="checkbox" onclick="toggleTheme(this)">
                                    Dark Mode
                                </label>
                            </div>
                            <div id="voice-picker-row" class="settings-row">
                                <label class="settings-row-label" for="voice-select">
                                    <span>Voice</span>
                                </label>
                                <div class="settings-row-controls">
                                    <div id="voice-select-wrap" class="dropdown-option">
                                        <select class="styled-dropdown" id="voice-select" aria-label="Choose the bot's voice" onchange="onVoiceSelected(this)">
                                            <option value="">Default</option>
                                        </select>
                                    </div>
                                    <button type="button" id="preview-voice-btn" aria-label="Preview this voice" title="Preview this voice" onclick="previewSelectedVoice()">
                                        <i class="fa fa-play" style="font-size:12px;"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="speed-picker-row" class="settings-row">
                                <label class="settings-row-label" for="speed-select">
                                    <span>Speed</span>
                                </label>
                                <div class="settings-row-controls">
                                    <!-- value/min/max here are an internal 0-100 "position"
                                         scale, NOT the speaking rate itself. See
                                         speedPositionToRate()/speedRateToPosition() in the
                                         script below: a plain linear 0.5x-2x mapping puts
                                         1.0x (the default/"normal" speed) at 33% along the
                                         track instead of visual center, since 1.0 isn't the
                                         midpoint of 0.5-2. This piecewise mapping keeps the
                                         full 0.5x-2x range while guaranteeing 1.0x always
                                         renders at exactly 50%. -->
                                    <input type="range" id="speed-select" min="0" max="100" step="0.1" value="50" aria-label="Speaking speed" aria-valuetext="1.0x" oninput="onSpeedChanged(this)">
                                    <span id="speed-value-label" aria-hidden="true">1.0x</span>
                                </div>
                            </div>
                            <div id="line2">
                                
                                <!-- This dropdown now lives visually in the language
                                     pill + overlay at the top of the page. It's kept
                                     here (visually hidden) since it's still the actual
                                     form field that gets submitted, and the rest of the
                                     app's JS (updateSelectedOption, updateSelectedLanguage)
                                     already targets it by id. -->
                                <div id="dropdown1" class="dropdown-option w3-padding-left w3-padding-bottom visually-hidden-select">
                                    <select class="styled-dropdown" id="language-select" name='user_language' onchange="updateSelectedOption(this)">
                                        <option value="Afrikaans">Afrikaans</option>
                                        <option value="Albanian">Albanian</option>
                                        <option value="Arabic">Arabic</option>
                                        <option value="Armenian">Armenian</option>
                                        <option value="Assamese">Assamese</option>
                                        <option value="Azerbaijani">Azerbaijani</option>
                                        <option value="Bashkir">Bashkir</option>
                                        <option value="Basque">Basque</option>
                                        <option value="Belarusian">Belarusian</option>
                                        <option value="Bengali">Bengali</option>
                                        <option value="Bosnian">Bosnian</option>
                                        <option value="Bulgarian">Bulgarian</option>
                                        <option value="Burmese">Burmese</option>
                                        <option value="Catalan">Catalan</option>
                                        <option value="Chinese">Chinese</option>
                                        <option value="Croatian">Croatian</option>
                                        <option value="Czech">Czech</option>
                                        <option value="Danish">Danish</option>
                                        <option value="Dutch">Dutch</option>
                                        <option value="English">English</option>
                                        <option value="Estonian">Estonian</option>
                                        <option value="Faroese">Faroese</option>
                                        <option value="Finnish">Finnish</option>
                                        <option value="French">French</option>
                                        <option value="Galician">Galician</option>
                                        <option value="Georgian">Georgian</option>
                                        <option value="German">German</option>
                                        <option value="Greek">Greek</option>
                                        <option value="Gujarati">Gujarati</option>
                                        <option value="Haitian Creole">Haitian Creole</option>
                                        <option value="Hebrew">Hebrew</option>
                                        <option value="Hindi">Hindi</option>
                                        <option value="Hungarian">Hungarian</option>
                                        <option value="Icelandic">Icelandic</option>
                                        <option value="Indonesian">Indonesian</option>
                                        <option value="Irish">Irish</option>
                                        <option value="Italian">Italian</option>
                                        <option value="Japanese">Japanese</option>
                                        <option value="Javanese">Javanese</option>
                                        <option value="Kannada">Kannada</option>
                                        <option value="Kazakh">Kazakh</option>
                                        <option value="Khmer">Khmer</option>
                                        <option value="Korean">Korean</option>
                                        <option value="Lao">Lao</option>
                                        <option value="Latvian">Latvian</option>
                                        <option value="Lithuanian">Lithuanian</option>
                                        <option value="Luxembourgish">Luxembourgish</option>
                                        <option value="Macedonian">Macedonian</option>
                                        <option value="Malay">Malay</option>
                                        <option value="Malayalam">Malayalam</option>
                                        <option value="Maltese">Maltese</option>
                                        <option value="Marathi">Marathi</option>
                                        <option value="Nepali">Nepali</option>
                                        <option value="Norwegian Bokmål">Norwegian Bokmål</option>
                                        <option value="Norwegian Nynorsk">Norwegian Nynorsk</option>
                                        <option value="Occitan">Occitan</option>
                                        <option value="Oriya">Oriya</option>
                                        <option value="Persian">Persian</option>
                                        <option value="Polish">Polish</option>
                                        <option value="Portuguese">Portuguese</option>
                                        <option value="Punjabi">Punjabi</option>
                                        <option value="Romanian">Romanian</option>
                                        <option value="Russian">Russian</option>
                                        <option value="Sardinian">Sardinian</option>
                                        <option value="Serbian">Serbian</option>
                                        <option value="Sindhi">Sindhi</option>
                                        <option value="Sinhala">Sinhala</option>
                                        <option value="Slovak">Slovak</option>
                                        <option value="Slovenian">Slovenian</option>
                                        <option value="Spanish">Spanish</option>
                                        <option value="Sundanese">Sundanese</option>
                                        <option value="Swahili">Swahili</option>
                                        <option value="Swedish">Swedish</option>
                                        <option value="Tagalog">Tagalog</option>
                                        <option value="Tajik">Tajik</option>
                                        <option value="Tamil">Tamil</option>
                                        <option value="Tatar">Tatar</option>
                                        <option value="Telugu">Telugu</option>
                                        <option value="Thai" selected>Thai</option>
                                        <option value="Turkish">Turkish</option>
                                        <option value="Ukrainian">Ukrainian</option>
                                        <option value="Urdu">Urdu</option>
                                        <option value="Uzbek">Uzbek</option>
                                        <option value="Vietnamese">Vietnamese</option>
                                        <option value="Welsh">Welsh</option>
                                        <option value="Yiddish">Yiddish</option>
                                    </select>
                                </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- The page gets scrolled up to this id. -->
    <div id="e-bot"></div>
    <!-- Onload a click is simulated on this to scroll the page to id="bottom-bar" -->
    <a href="#e-bot" id="scroll-page-up"></a>
    <a href="#test100" id="scroll-to-last-message"></a>
    <a href="#chatbot" id="scroll-to-bot-message"></a>

    <!--
    Dev-only panel showing per-request token counts and cost, as
    reported by OpenRouter. Hidden by default - flip DEV_MODE to true
    in the script below (or just delete this block) for production.
    -->
    <div id="dev-usage-panel" style="display:none; position:fixed; bottom:0; left:0; z-index:9999; max-width:340px; max-height:40vh; overflow-y:auto; background:rgba(0,0,0,0.85); color:#0f0; font-family:monospace; font-size:12px; padding:8px; border-top-right-radius:6px;"></div>

</body>
</html>


<script>
/* ============================================================
   Utility functions
   (formerly js/utils.js)
   ============================================================ */

// When the form is submitted this function removes
// the 'selected' attribute. This ensures we don't end
// up with two dropdown options that have an attribute called 'selected'.
// The 'selected' attribute gets added later when the ajax response
// displays the output on the page. This ensures that the language
// the user has selected stays selected.
function clearSelectedOptions() {
    var selectElement = document.getElementById('language-select');
    var options = selectElement.getElementsByTagName('option');
    
    for (var i = 0; i < options.length; i++) {
        if (options[i].hasAttribute('selected')) {
            options[i].removeAttribute('selected');
        }
    }
}


//* Replaced this with a better solution *
// This sets the language when the dropdown option is selected.
// This gets called after the ajax response is received when the page gets updated.
function updateSelectedLanguage(user_language) {
    var selectElement = document.getElementById("language-select");
    // translation_language = selectElement.value;
    // console.log("Selected language: " + translation_language);

    // Get the <option> element you want to add the 'selected' attribute to by its value
    var optionToSelect = selectElement.querySelector('option[value="' + user_language + '"]'); 

    // Add the 'selected' attribute to the option
    optionToSelect.setAttribute("selected", "selected");

    // Keep the language pill's visible label in sync too.
    if (typeof window.updateLanguagePillLabel === 'function') {
        window.updateLanguagePillLabel();
    }
}


// This function creates the three dot spinner.
// Calling this function starts the spinner.
function spinner() {
    // Select the element where the spinner will be displayed
    const spinnerElement = document.getElementById("spinner");
    
    // Define an array of dots
    const dots = ["", ".", "..", "..."];
    
    // Initialize the dot counter
    let dotIndex = 0;// Set the color and size of the spinner
	
    spinnerElement.style.color = "var(--text)";
    spinnerElement.style.fontSize = "25px";
	
	
    
    // Start the spinner animation
    setInterval(() => {
        // Update the text content of the spinner element with the current dot
        // This adds the >... symbol
        // spinnerElement.textContent = `>${dots[dotIndex]}`;
        
        // This does not have the >... symbol
        spinnerElement.textContent = `${dots[dotIndex]}`;
    
        // Increment the dot counter
        dotIndex = (dotIndex + 1) % dots.length;
    }, 500);
}


// We create the div containing the spinner.
// We append the div to the chat.
// This displays the spinner.
function create_spinner_div() {
    // Create a new div element
    const spinnerElement = document.createElement("div");
    
    // Set the id attribute of the div element to "spinner"
    spinnerElement.setAttribute("id", "spinner");
    
    var chat = document.getElementById("chat");
    
    // Append the div to the chat
    chat.appendChild(spinnerElement);
    
    // Start the spinner
    spinner();
}


// This function deletes the div containing the spinner.
// This causes the spinner to disappear.
function delete_spinner_div() {
    // Get the div element you want to delete
    const elementToDelete = document.getElementById("spinner");
    
    // Get the parent node of the div element
    const parentElement = elementToDelete.parentNode;
    
    // Remove the div element from its parent node
    parentElement.removeChild(elementToDelete);
}


// This functions takes a list of text (paragraphs).
// If the paragraph does not have p tags then it adds them.
function wrapInPTags(paragraphs) {
    let result = '';

    for (let i = 0; i < paragraphs.length; i++) {
        const paragraph = paragraphs[i];

        if (paragraph.includes('<p>')) {
            result += paragraph;
        } else {
            result += '<p>' + paragraph + '</p>';
        }
    }

    return result;
}


// This function formats the text into paragraphs.
function formatResponse(response) {
    // Split the response into lines
    const lines = response.split("\n");

    // Combine the lines into paragraphs
    const paragraphs = [];
    let currentParagraph = "";

    for (const line of lines) {
        if (line.trim()) {  // Check if the line is non-empty
            currentParagraph += line.trim() + " ";
        } else if (currentParagraph) {  // Check if the current paragraph is non-empty
            paragraphs.push(currentParagraph.trim());
            currentParagraph = "";
        }
    }

    // Append the last paragraph
    if (currentParagraph) {
        paragraphs.push(currentParagraph.trim());
    }

    // Some text thats returned has \n character but no <p> tags.
    // Other text has <p> tags that we can use when displaying the text on the page.
    // Here we check each list item (paragraph). If it doesn't have <p> tags then add them.
    // This is also important when we save and then reload the chat history.
    // If you change this make sure that the saving and reloading also works well.
    formattedResponse = wrapInPTags(paragraphs);
    
    // Add HTML tags to separate paragraphs
    // const formattedResponse = paragraphs.map(p => `<p>${p}</p>`).join("");
    
    return formattedResponse;
}


// Function to create a new message container
function createMessageContainer(message) {
    var messageContainer = document.createElement("div");
    messageContainer.classList.add("message-container");
    messageContainer.classList.add("w3-animate-opacity");
    
    // Add an id attribute. This will help to scroll to
    // the bot message. This gets detelted after the page
    // is scrolled to the bot message.
    messageContainer.setAttribute("id", "chatbot");

    var messageText = document.createElement("span"); // p

    // This if statement sets the coour of the name that gets displayed
    if (message.sender == bot_name) {
        messageText.innerHTML = "<span class='set-color1'><b>&#x2022 " + message.sender + "</b></span>" + message.text;
    } else {
        messageText.innerHTML = "<span class='set-color2'><b>&#x2022 " + message.sender + "</b></span>" + message.text;
    }

    messageContainer.appendChild(messageText);

    return messageContainer;
}


// Function to add a new message to the chat
function addMessageToChat(message) {
    var chat = document.getElementById("chat");
    var messageContainer = createMessageContainer(message);
    
    chat.appendChild(messageContainer);
    
    // Scroll the page up by cicking on a div at the bottom of the page.
    simulateClick('scroll-page-up');
}

// Function to remove html tags from a string
function removeHtmlTags(str) {
    return str.replace(/(<([^>]+)>)/gi, "");
}

// Function to mute the cahtbot
// when it is speaking.
//
// NOTE: speechSynthesis.cancel() does not reliably fire the
// utterance's 'onend' event in every browser. The mic restart
// used to live only inside onend, which meant muting mid-speech
// could permanently leave voicechat mode with the mic off and
// no visual indication. To fix this, muting now explicitly
// restarts the mic itself (if it's supposed to be on), instead
// of depending on onend to do it.
function quiet_please() {
    speechSynthesis.cancel();
    
    // Stop whichever per-message icon is currently pulsing.
    clearSpeakingIcon();
    
    // Hide the moving audio bars and show the three dots bars
    hide('audioIndicator');
    show('audioIndicator1');
    
    // If the mic is supposed to be listening (voicechat mode is on),
    // make sure it actually is - don't rely on onend firing.
    if (micShouldBeOn) {
        console.log('Muted mid-speech - restarting mic directly...');
        restart_recognition_if_needed();
    }
}


// Stops the currently-animating speaker icon (if any) from pulsing,
// and resets its tooltip back to "Click to play". Centralized here
// since speak(), quiet_please(), and the utterance's onend/onerror
// handlers all need to agree on which icon (if any) is "speaking".
function clearSpeakingIcon() {
    if (window.currentSpeakingIcon) {
        window.currentSpeakingIcon.classList.remove('speaking');
        window.currentSpeakingIcon.setAttribute('title', 'Click to play');
        window.currentSpeakingIcon = null;
    }
}


// Function that converts text to speech
function speak(text, speech_lang_code, speech_voice_name, speech_rate = 1, iconElement = null) {

	if (!speechSynthesisSupported) {
		console.log('Speech synthesis is not supported in this browser - skipping TTS.');
		return;
	}

    // Only one utterance plays at a time, so only one icon should ever
    // show the "speaking" animation - clear whichever one was
    // animating before starting this one.
    clearSpeakingIcon();

    // Flush any utterance that's currently speaking or still queued
    // before starting this one. Without this, calling speak() again
    // while a previous utterance hasn't finished (e.g. clicking "play
    // translation" right after the English reply auto-speaks) queues
    // the new utterance instead of replacing it - and some browsers
    // (Chrome in particular) can bleed the voice/lang from one queued
    // utterance into another when that happens. This is what caused
    // the Thai voice to occasionally speak the English text.
    speechSynthesis.cancel();

    // Create a new instance of SpeechSynthesisUtterance
    const utterance = new SpeechSynthesisUtterance();
    
    // Set the text that you want to speak
    utterance.text = text;
	
	// If speech recognition is currently running, stop it while the
	// bot talks (otherwise the mic will hear - and respond to - the
	// bot's own voice). We do NOT touch micShouldBeOn here: that flag
	// tracks the user's intent (voicechat mode on/off), independent of
	// this temporary pause. handleEnd() and quiet_please() both check
	// micShouldBeOn to decide whether to restart the mic.
	  if (window.recognition) {
		  
		  console.log('Stopping recognition while bot speaks...')
	  
		  window.recognition.removeEventListener('end', handleEnd);
		  window.recognition.stop();
	  
	  }
	
	
	/////////////
	
	 // Ensure the language is set
    utterance.lang = speech_lang_code;

    // Get the list of available voices. Prefer the cached list (populated
    // by onvoiceschanged) since getVoices() can return an empty array on
    // first call before the browser has finished loading its voice list.
    const voices = (cachedVoices && cachedVoices.length) ? cachedVoices : window.speechSynthesis.getVoices();

    // If the user has picked a voice from the Settings panel (see
    // populateVoiceSelect()/onVoiceSelected() above), it takes
    // priority over the server-provided default - but only for
    // English speech. Translated text is spoken with whatever
    // browser-default voice fits that language, so a user's English
    // voice preference shouldn't hijack, say, Thai playback.
    const userPreferredVoiceName = (speech_lang_code && speech_lang_code.toLowerCase().startsWith('en'))
        ? localStorage.getItem('ebot_preferred_voice_name')
        : null;
    const requestedVoiceName = userPreferredVoiceName || speech_voice_name;

    // Find the voice with the requested name (e.g. "Serena", "Jorge" -
    // these are hardcoded per-language in main.php, or picked by the
    // user from the Settings dropdown). This is a fragile,
    // OS/browser-specific exact-name match, so it will often come up
    // empty on non-Apple platforms - in that case we work down the
    // fallback chain below rather than failing.
    let selectedVoice = requestedVoiceName ? voices.find(voice => voice.name === requestedVoiceName) : null;

    // Fallback chain, English only: tried in order after the requested
    // voice (e.g. "Serena") comes up empty on this device/browser.
    // Each entry is checked by name AND lang, since voice names aren't
    // guaranteed unique across languages/platforms.
    const FALLBACK_VOICE_CHAIN = [
        { name: 'Arthur', lang: 'en-GB' },
        { name: 'Daniel', lang: 'en-GB' },
        { name: 'Samantha', lang: 'en-US' }
    ];
    if (!selectedVoice && speech_lang_code && speech_lang_code.toLowerCase().startsWith('en')) {
        for (const candidate of FALLBACK_VOICE_CHAIN) {
            const match = voices.find(voice =>
                voice.name === candidate.name &&
                voice.lang && voice.lang.toLowerCase() === candidate.lang.toLowerCase()
            );
            if (match) {
                selectedVoice = match;
                break;
            }
        }
    }

    if (selectedVoice) {
        utterance.voice = selectedVoice;
    } else if (requestedVoiceName) {
        console.log('Requested voice "' + requestedVoiceName + '" and the fallback chain (Arthur/Daniel/Samantha) were not found on this device/browser - using the browser\'s default voice instead.');
    } else {
        console.log('No specific voice requested for lang "' + speech_lang_code + '" - using the browser\'s default voice for it.');
    }
	
	
	/////////////
	
	
	// Set the speaking rate. The Settings panel's Speed slider (see
	// getPreferredSpeechRate() above) always wins here - unlike the
	// voice name override, speed isn't language-specific, so this
	// applies to any text this function is asked to speak.
    utterance.rate = getPreferredSpeechRate();
    

    // When the chatbot starts speaking display the sound bar animation
    hide('audioIndicator1');
    show('audioIndicator');
    
    // Pulse this message's speaker icon (if one was passed in) and
    // remember it so quiet_please()/onend/onerror can find it again.
    window.currentSpeakingIcon = iconElement;
    if (iconElement) {
        iconElement.classList.add('speaking');
        iconElement.setAttribute('title', 'Playing - click to mute');
    }
    
    utterance.onend = function() {
        // When the chatbot stops speaking hide the sound bar animation
        hide('audioIndicator');
        show('audioIndicator1');
        clearSpeakingIcon();
		
		// Only when the speech synthesis ends, start the mic again -
		// but only if voicechat mode is still supposed to be on.
		// If we don't gate this on micShouldBeOn, the mic could start
		// listening again even after the user muted or stopped voicechat.
		restart_recognition_if_needed();
    };
    
    utterance.onerror = function(event) {
        // 'interrupted' / 'canceled' fire whenever speechSynthesis.cancel()
        // preempts this utterance - which happens on every normal replay-
        // a-different-message click, every mute, and every new speak()
        // call (which now flushes the queue first), since speak() and
        // quiet_please() both call cancel(). That's expected, not a
        // failure, so it's logged at a lower level than a real error.
        if (event.error === 'interrupted' || event.error === 'canceled') {
            console.log('Speech synthesis interrupted (expected - a new message started playing or audio was muted).');
        } else {
            console.log('Speech synthesis error:', event.error);
        }
        
        // Don't leave the "speaking" animation stuck on if TTS fails.
        hide('audioIndicator');
        show('audioIndicator1');
        clearSpeakingIcon();
        
        // Make sure the mic doesn't stay stuck off just because TTS failed.
        restart_recognition_if_needed();
    };
    
    // Speak the text. This is deliberately deferred a tick rather than
    // called synchronously: Chrome (and some other browsers) have a
    // known bug where calling speak() immediately after cancel() can
    // silently drop the utterance entirely - no sound, no onerror,
    // nothing - because cancel() hasn't actually finished tearing down
    // the previous utterance yet even though it returns right away.
    // A short delay gives that teardown time to complete first.
    setTimeout(function() {
        speechSynthesis.speak(utterance);
    }, 50);
    
}


// Restarts speech recognition if (and only if) voicechat mode is
// supposed to be on. Centralizing this logic means quiet_please(),
// utterance.onend, and utterance.onerror all agree on whether the
// mic should be listening, instead of each having their own copy
// of this logic (which is how the old mute bug happened).
function restart_recognition_if_needed() {
    if (!micShouldBeOn) {
        return;
    }
    
    if (!window.recognition) {
        // Recognition object was torn down (e.g. by stop_recognition()) -
        // nothing to restart.
        return;
    }
    
    console.log('Restarting recognition...');
    
    window.recognition.removeEventListener('end', handleEnd);
    window.recognition.addEventListener('end', handleEnd);
    
    try {
        window.recognition.start();
    } catch (err) {
        // start() throws if recognition is already running (e.g. it
        // never actually stopped) - safe to ignore.
        console.log('Recognition already running or could not be restarted:', err);
    }
}


// Function to remove items from a json string
// before it gets displayed on the page.
function replaceItemsInString(inputString) {
    const itemsToReplace = ["```", "json", "{", "}", '"correction": "', '"translation": "', "#"];
    
    let modifiedString = inputString;
    itemsToReplace.forEach(item => {
        const regex = new RegExp(item, 'g'); // Create a global regular expression for each item
        modifiedString = modifiedString.replace(regex, "");
    });
    
    modifiedString = modifiedString.trim();
    
    // Only strip a trailing quote character if one is actually left over
    // (see the matching fix in main.php's replaceItemsInString for the
    // full explanation). Previously this unconditionally sliced off the
    // last character of every string, which truncated translated replies
    // that didn't happen to end in a stray '"'.
    if (modifiedString.endsWith('"')) {
        modifiedString = modifiedString.slice(0, -1);
    }
    
    modifiedString = removeEmojis(modifiedString);
    
    return modifiedString;
}


function removeEscapeSlashes(str) {
    // Handles two distinct things that can show up in model output:
    // 1) Genuinely escaped quote/backslash characters, e.g. \" \' \\
    //    -> unescape them to the plain character.
    // 2) A literal two-character "\n" (backslash + n) or "\t" left over
    //    from the model double-escaping newlines/tabs inside its JSON
    //    output (e.g. it emits \\n, which after one layer of JSON
    //    decoding becomes the literal text \n instead of a real
    //    newline). These aren't real control characters, so
    //    removeNewlines() never catches them and they show up as
    //    visible "\n\n" in the chat. Convert them to real control
    //    characters here so removeNewlines() can strip them as normal.
    return str
        .replace(/\\n/g, '\n')
        .replace(/\\t/g, '\t')
        .replace(/\\(["'\\])/g, '$1');
}

function removeNewlines(str) {
  return str.replace(/[\r\n]+/g, '');
}


// Function to remove emojis from text
function removeEmojis(text) {
    return text.replace(/[\u{1F600}-\u{1F64F}]/gu, '')  // Emoticons
               .replace(/[\u{1F300}-\u{1F5FF}]/gu, '')  // Miscellaneous Symbols and Pictographs
               .replace(/[\u{1F680}-\u{1F6FF}]/gu, '')  // Transport and Map Symbols
               .replace(/[\u{1F700}-\u{1F77F}]/gu, '')  // Alchemical Symbols
               .replace(/[\u{1F780}-\u{1F7FF}]/gu, '')  // Geometric Shapes Extended
               .replace(/[\u{1F800}-\u{1F8FF}]/gu, '')  // Supplemental Arrows-C
               .replace(/[\u{1F900}-\u{1F9FF}]/gu, '')  // Supplemental Symbols and Pictographs
               .replace(/[\u{1FA00}-\u{1FA6F}]/gu, '')  // Chess Symbols
               .replace(/[\u{1FA70}-\u{1FAFF}]/gu, '')  // Symbols and Pictographs Extended-A
               .replace(/[\u{2600}-\u{26FF}]/gu, '')    // Miscellaneous Symbols
               .replace(/[\u{2700}-\u{27BF}]/gu, '')    // Dingbats
               .replace(/[\u{FE00}-\u{FE0F}]/gu, '')    // Variation Selectors
               .replace(/[\u{1F1E6}-\u{1F1FF}]/gu, '')  // Flags
               .replace(/[\u{1F900}-\u{1F9FF}]/gu, '')  // Supplemental Symbols and Pictographs
               .replace(/[\u{1FA70}-\u{1FAFF}]/gu, ''); // Symbols and Pictographs Extended-A
}


// Function to get the user's preferred language
function getUserLanguage() {
    // Use navigator.languages if available, otherwise fallback to navigator.language
    const languages = navigator.languages && navigator.languages.length ? navigator.languages : [navigator.language || navigator.userLanguage];
    return languages[0];  // Return the first preferred language
}


function hide(elementId) {
    document.getElementById(elementId).style.display = "none";
}


function show(elementId) {
    document.getElementById(elementId).style.display = "inline-block";
}


function removeITags(html) {
    // Use a regular expression to remove <i> tags and their content
    return html.replace(/<i[^>]*>.*?<\/i>/gi, '');
}


function speakText(pElement) {
    playOrToggleMuteMessageAudio(pElement, speech_lang_code, speech_voice_name);
}

function playOrToggleMuteMessageAudio(pElement, langCode, voiceName) {
    var icon = pElement.querySelector('.speaker-icon');

    // If this exact message is the one currently playing, clicking it
    // again mutes/stops playback rather than restarting it - the
    // pulsing icon is the visual cue that a click here means "mute".
    if (icon && icon.classList.contains('speaking')) {
        quiet_please();
        return;
    }

    // Remove the <i> tags associated with the icon
    var processedText = removeITags(pElement.innerHTML);
    
    speak(processedText, langCode, voiceName, speech_rate, icon);
}




// Simulates a click.
function simulateClick(tabID) {
    // Simulate a click.
    document.getElementById(tabID).click();
}


// Adding and removing the checked attribute ensures
// that the radio button remains checked or unchecked
// after the form is submitted. Otherwise it will return
// to its default status each time a chat message is sent.
function toggleRadio(radio) {
    // Check the previous state stored in a custom property
    if (radio.wasChecked) {
        // If it was previously checked, uncheck it
        radio.checked = false;
        radio.removeAttribute('checked');
        radio.wasChecked = false;  // Update the state to reflect that it's no longer checked
    } else {
        // If it was not checked, check it
        radio.checked = true;
        radio.setAttribute('checked', 'checked');
        radio.wasChecked = true;  // Update the state to reflect that it's now checked
    }

    // Keep the language pill (and its overlay) in sync with the
    // Translation toggle - hide the pill entirely when translation
    // is off, since there's nothing to pick a language for.
    if (radio.id === 'translateid') {
        setLanguagePillVisibility(radio.wasChecked);
    }
}


// Shows/hides the language pill header based on whether translation
// is currently on. Also force-closes the overlay if it happens to be
// open when translation gets turned off.
function setLanguagePillVisibility(visible) {
    const pillHeader = document.getElementById('language-pill-header');
    if (pillHeader) {
        pillHeader.style.display = visible ? '' : 'none';
    }
    if (!visible) {
        const overlay = document.getElementById('language-overlay');
        if (overlay) {
            overlay.classList.remove('open');
        }
    }
}


function checkRadioButton(radioName, radioID) {
    var radio = document.querySelector(`input[name="${radioName}"]`);
    if (radio) {
        radio.checked = true;
    }
    // This makes sure the button does not uncheck
    // when the form is submitted. It stays checked.
    document.getElementById(radioID).setAttribute('checked', 'checked');
}


function uncheckRadioButton(radioName, radioID) {
    var radio = document.querySelector(`input[name="${radioName}"]`);
    if (radio) {
        radio.checked = false;
    }
    document.getElementById(radioID).removeAttribute('checked');
}


// Helper function to update elements correctly
function updateSelectedOption(selectElement) {
    // Remove 'selected' attribute from all options
    for (let option of selectElement.options) {
        option.removeAttribute('selected');
    }

    // Add 'selected' attribute to the currently selected option
    selectElement.options[selectElement.selectedIndex].setAttribute('selected', 'selected');
}


/* ============================================================
   Config
   (formerly js/config.js)
   Set the language that the user is learning
   ============================================================ */
lang_code = "en-GB";
</script>

<script>
/* ============================================================
   Language pill + full-screen picker overlay.
   Same interaction pattern as the source picker in the
   faithscrolling app: build the visible option list from the
   existing #language-select, let the pill/overlay drive that
   select, and let the rest of the app (updateSelectedOption,
   the ajax handler, main.php) keep working exactly as before.
   ============================================================ */
document.addEventListener('DOMContentLoaded', () => {
    const pill = document.getElementById('language-pill');
    const pillLabel = document.getElementById('current-language-label');
    const overlay = document.getElementById('language-overlay');
    const optionsContainer = document.getElementById('language-menu-options');
    const closeBtn = document.getElementById('close-language-overlay');
    const selectElement = document.getElementById('language-select');
    const searchInput = document.getElementById('language-search-input');
    const noResults = document.getElementById('language-overlay-no-results');

    if (!pill || !overlay || !selectElement) {
        return;
    }

    // The <select>'s options are the single source of truth for which
    // languages are offered and what value gets submitted. Cache them
    // once (as plain {value,label} pairs) so filtering doesn't have to
    // keep re-reading the DOM.
    const allLanguages = Array.from(selectElement.options).map((option) => ({
        value: option.value,
        label: option.textContent,
    }));

    // Builds the overlay's option list from allLanguages, optionally
    // narrowed to names starting with `query`. Re-run on every
    // keystroke in the search box.
    function buildLanguageOptions(query) {
        const q = (query || '').trim().toLowerCase();
        optionsContainer.innerHTML = '';
        const matches = q
            ? allLanguages.filter((lang) => lang.label.toLowerCase().startsWith(q))
            : allLanguages;

        matches.forEach((lang) => {
            const item = document.createElement('div');
            item.className = 'language-overlay-option' + (lang.value === selectElement.value ? ' active' : '');
            item.setAttribute('role', 'menuitem');
            item.setAttribute('tabindex', '0');
            item.setAttribute('data-value', lang.value);
            item.textContent = lang.label;
            optionsContainer.appendChild(item);
        });

        attachOptionListeners();
        if (noResults) {
            noResults.classList.toggle('hide', matches.length !== 0);
        }
    }

    function updatePillLabel() {
        const selected = selectElement.options[selectElement.selectedIndex];
        const languageName = selected ? selected.value : '';
        if (pillLabel) {
            pillLabel.textContent = languageName;
        }
        pill.setAttribute('aria-label', 'Current language pair: English and ' + languageName + '. Change language.');
    }

    // Makes everything except the overlay unfocusable/unclickable
    // while the picker is open, same approach as the faithscrolling
    // source picker.
    function setBackgroundInert(isInert) {
        Array.from(document.body.children).forEach((el) => {
            if (el === overlay) return;
            if (isInert) el.setAttribute('inert', '');
            else el.removeAttribute('inert');
        });
    }

    function attachOptionListeners() {
        const options = Array.from(optionsContainer.querySelectorAll('.language-overlay-option'));
        options.forEach((option, idx) => {
            option.addEventListener('click', () => selectLanguage(option.getAttribute('data-value')));
            option.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    selectLanguage(option.getAttribute('data-value'));
                } else if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    const next = e.key === 'ArrowDown'
                        ? options[(idx + 1) % options.length]
                        : options[(idx - 1 + options.length) % options.length];
                    next.focus();
                }
            });
        });
    }

    function openOverlay() {
        if (searchInput) searchInput.value = '';
        buildLanguageOptions('');
        overlay.classList.add('open');
        pill.setAttribute('aria-expanded', 'true');
        setBackgroundInert(true);
        setTimeout(() => {
            // With 86 languages now on offer, typing to search is the
            // fastest path in, so the search box takes focus first
            // instead of the currently active option.
            if (searchInput) {
                searchInput.focus();
            } else {
                const active = optionsContainer.querySelector('.language-overlay-option.active');
                (active || optionsContainer.querySelector('.language-overlay-option'))?.focus();
            }
        }, 300);
    }

    function closeOverlay() {
        overlay.classList.remove('open');
        pill.setAttribute('aria-expanded', 'false');
        setBackgroundInert(false);
        pill.focus();
    }

    function selectLanguage(value) {
        selectElement.value = value;
        // Run the app's existing logic (marks the 'selected' attribute
        // so the choice survives the ajax round-trip) exactly as it
        // would run for a native <select> change.
        updateSelectedOption(selectElement);
        selectElement.dispatchEvent(new Event('change'));
        updatePillLabel();
        closeOverlay();
    }

    function handleEnterSpace(e, callback) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            callback();
        }
    }

    // Keeps Tab cycling within the overlay's options and close button
    // instead of letting focus run out into the (inert) background.
    function trapTabKey(e) {
        if (e.key !== 'Tab') return;
        if (!overlay.classList.contains('open')) return;
        const focusable = [closeBtn, searchInput, ...optionsContainer.querySelectorAll('.language-overlay-option')].filter(Boolean);
        if (focusable.length === 0) return;
        const first = focusable[0];
        const last = focusable[focusable.length - 1];
        const current = document.activeElement;
        if (e.shiftKey) {
            if (current === first || !focusable.includes(current)) {
                e.preventDefault();
                last.focus();
            }
        } else {
            if (current === last || !focusable.includes(current)) {
                e.preventDefault();
                first.focus();
            }
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', () => buildLanguageOptions(searchInput.value));
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const first = optionsContainer.querySelector('.language-overlay-option');
                if (first) selectLanguage(first.getAttribute('data-value'));
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                const first = optionsContainer.querySelector('.language-overlay-option');
                if (first) first.focus();
            }
        });
    }

    pill.addEventListener('click', openOverlay);
    pill.addEventListener('keydown', (e) => handleEnterSpace(e, openOverlay));
    closeBtn.addEventListener('click', closeOverlay);
    overlay.addEventListener('keydown', trapTabKey);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && overlay.classList.contains('open')) closeOverlay();
    });

    // Keep the pill's label in sync any time the select's value
    // changes elsewhere in the app.
    selectElement.addEventListener('change', updatePillLabel);

    // Let updateSelectedLanguage() (declared earlier, called after
    // every ajax response) refresh the pill too.
    window.updateLanguagePillLabel = updatePillLabel;

    // Set the initial pill label on page load.
    updatePillLabel();
});
</script>

<script>
// Cache of available TTS voices. getVoices() frequently returns an
// empty list on first page load until the browser's 'voiceschanged'
// event fires, so speak() should read from this cache rather than
// calling getVoices() fresh every time.
let cachedVoices = [];

// The key voice picking is stored under in localStorage, so the
// user's choice survives page reloads (sessionStorage isn't used
// here on purpose - the chat history is wiped per-tab via
// session_unset()/session_destroy() below, but a voice preference
// is a device/browser setting, not a conversation setting, so it
// should persist).
const VOICE_PREF_KEY = 'ebot_preferred_voice_name';

function cacheVoices() {
    const voices = speechSynthesis.getVoices();
    if (voices && voices.length) {
        cachedVoices = voices;
        console.log('Cached ' + voices.length + ' voices.');
        populateVoiceSelect();
    }
}

// Fills the #voice-select dropdown with the English voices this
// browser/OS actually has available, so the list only ever shows
// voices that will really work here (rather than a fixed list that
// might not exist on the visitor's device/browser - see the
// Chrome-vs-Safari voice mismatch this replaces).
function populateVoiceSelect() {
    const select = document.getElementById('voice-select');
    if (!select) return;

    // Practice language is English, so only offer English voices -
    // matches window.speech_lang_code ("en-GB" by default in main.php).
    const englishVoices = cachedVoices
        .filter(v => v.lang && v.lang.toLowerCase().startsWith('en'))
        .sort((a, b) => a.name.localeCompare(b.name));

    const previouslySelected = select.value || localStorage.getItem(VOICE_PREF_KEY) || '';

    // Rebuild the option list (keep the "Default" option first).
    select.innerHTML = '<option value="">Default</option>';
    englishVoices.forEach(voice => {
        const option = document.createElement('option');
        option.value = voice.name;
        option.textContent = voice.name + ' (' + voice.lang + ')';
        select.appendChild(option);
    });

    // Restore the saved/previous choice if it's still available on
    // this browser; otherwise fall back to "Default" quietly.
    if (previouslySelected && englishVoices.some(v => v.name === previouslySelected)) {
        select.value = previouslySelected;
    } else {
        select.value = '';
    }
}

// Called when the user picks a voice from the dropdown.
function onVoiceSelected(selectElement) {
    const voiceName = selectElement.value;
    if (voiceName) {
        localStorage.setItem(VOICE_PREF_KEY, voiceName);
    } else {
        localStorage.removeItem(VOICE_PREF_KEY);
    }
}

// Lets the user hear a short sample in the currently selected voice
// before committing to it, without having to send a chat message.
function previewSelectedVoice() {
    const select = document.getElementById('voice-select');
    if (!select) return;

    const voiceName = select.value || null; // null = browser default
    const langCode = (window.speech_lang_code) || 'en-GB';

    // Note: the 4th arg (rate) below is intentionally ignored by
    // speak() now - it always sources the rate from the Speed
    // slider itself via getPreferredSpeechRate(), so this preview
    // automatically plays at whatever speed is currently set.
    speak('Hi! This is a preview of my voice.', langCode, voiceName, 1, null);
}

// Same idea as VOICE_PREF_KEY above: speaking speed is a
// device/browser-level preference, so it's saved separately from
// the per-session chat state and persists across reloads.
const SPEED_PREF_KEY = 'ebot_preferred_speech_rate';

// The Speed slider's own value scale is 0-100 ("position" along the
// track) rather than the speaking rate directly. A plain linear
// mapping of a 0.5x-2x rate onto the track puts 1.0x (the default/
// "normal" speed) at 33% instead of visual center, since 1.0 isn't
// the midpoint of 0.5-2. These two conversions keep the full 0.5x-2x
// range while guaranteeing 1.0x always renders at exactly 50%: the
// left half of the track covers 0.5x-1.0x, the right half covers
// 1.0x-2.0x, each with its own (different) slope.
function speedPositionToRate(position) {
    return position <= 50
        ? 0.5 + (position / 50) * 0.5
        : 1.0 + ((position - 50) / 50) * 1.0;
}

function speedRateToPosition(rate) {
    return rate <= 1.0
        ? (rate - 0.5) * 100
        : 50 + (rate - 1.0) * 50;
}

// Restores the saved speed (or the 1.0x default) into the slider +
// its label. Called once on page load - the slider itself doesn't
// depend on voices being cached, so it doesn't need to wait for
// cacheVoices()/onvoiceschanged like the voice dropdown does.
function initSpeedSlider() {
    const slider = document.getElementById('speed-select');
    const label = document.getElementById('speed-value-label');
    if (!slider || !label) return;

    const saved = parseFloat(localStorage.getItem(SPEED_PREF_KEY));
    const rate = (!isNaN(saved) && saved >= 0.5 && saved <= 2) ? saved : 1;

    slider.value = speedRateToPosition(rate);
    slider.setAttribute('aria-valuetext', rate.toFixed(1) + 'x');
    label.textContent = rate.toFixed(1) + 'x';
}

// Called continuously while the user drags the slider (oninput, not
// onchange) so the "1.2x" label updates live as they move it. Rounds
// to the nearest 0.1x (matching the slider's old 0.1 step, before it
// was switched to an internal 0-100 position scale) so the stored/
// applied rate always matches what the label displays.
function onSpeedChanged(sliderElement) {
    const rawRate = speedPositionToRate(parseFloat(sliderElement.value));
    const rate = Math.round(rawRate * 10) / 10;
    sliderElement.setAttribute('aria-valuetext', rate.toFixed(1) + 'x');
    document.getElementById('speed-value-label').textContent = rate.toFixed(1) + 'x';
    localStorage.setItem(SPEED_PREF_KEY, rate);
}

// Returns the user's saved speaking speed, or 1 (normal speed) if
// they haven't set one yet.
function getPreferredSpeechRate() {
    const saved = parseFloat(localStorage.getItem(SPEED_PREF_KEY));
    return (!isNaN(saved) && saved >= 0.5 && saved <= 2) ? saved : 1;
}

initSpeedSlider();

// Some browsers (e.g. Chrome) have voices ready immediately; others
// only populate them once 'voiceschanged' fires. Try both.
cacheVoices();
speechSynthesis.onvoiceschanged = cacheVoices;
</script>

<script>
    // Opens/closes the settings panel with the smooth max-height
    // transition, keeping the gear button's active state and
    // aria-expanded in sync. Pulled out into named functions (rather
    // than only living inside the accordion's click handler) so the
    // click-outside-to-close listener below can call closeSettingsPanel()
    // directly without needing to fake a click on the accordion button.
    function isSettingsPanelOpen() {
        var panel = document.getElementById('panel');
        return !!panel.style.maxHeight;
    }

    function openSettingsPanel() {
        var panel = document.getElementById('panel');
        var accordionBtn = document.getElementById('accordion');
        var stickyBar = document.querySelector('.sticky-bar');
        panel.style.maxHeight = panel.scrollHeight + "px";
        accordionBtn.classList.add('settings-active');
        accordionBtn.setAttribute('aria-expanded', 'true');
        if (stickyBar) {
            stickyBar.classList.add('settings-open');
        }
    }

    function closeSettingsPanel() {
        var panel = document.getElementById('panel');
        var accordionBtn = document.getElementById('accordion');
        var stickyBar = document.querySelector('.sticky-bar');
        panel.style.maxHeight = null;
        accordionBtn.classList.remove('settings-active');
        accordionBtn.setAttribute('aria-expanded', 'false');
        if (stickyBar) {
            stickyBar.classList.remove('settings-open');
        }
    }

    // Event listener that prevents the form from submitting when
    // the "Settings" button is clicked.
    document.getElementById('accordion').addEventListener('click', function(event) {
        event.preventDefault();
        // Add your settings toggle code here
        console.log('Settings button clicked');
    });

    // JavaScript to toggle the visibility of the panel with a smooth transition
    document.getElementById('accordion').addEventListener('click', function() {
        if (isSettingsPanelOpen()) {
            closeSettingsPanel();
        } else {
            openSettingsPanel();
        }
    });

    // Click-outside-to-close: while the settings panel is open, a
    // click/tap anywhere outside the sticky bottom bar (which holds
    // the accordion button and the panel itself) closes it. Scoped to
    // .sticky-bar rather than just #panel so that clicking the message
    // input, the mic button, etc. also closes settings - not just
    // clicks on the chat messages above.
    document.addEventListener('click', function(event) {
        if (!isSettingsPanelOpen()) return;

        var stickyBar = document.querySelector('.sticky-bar');
        if (stickyBar && !stickyBar.contains(event.target)) {
            closeSettingsPanel();
        }
    });
	
	

	
	// *** ON LOAD ***
    // Comment or uncomment these lines to check (select) radio
    // when the page loads.
    // Selects and checks radio buttons when the page loads
    window.onload = function() {
        checkRadioButton('speak1', 'speakid');
        checkRadioButton('correct1', 'correctid');
        checkRadioButton('translate1', 'translateid');

        // Match the language pill's visibility to whatever state
        // the Translation toggle ends up in above.
        const translateRadio = document.getElementById('translateid');
        setLanguagePillVisibility(!!(translateRadio && translateRadio.checked));
    };

    // Sync the Dark Mode checkbox as soon as the DOM is parsed rather
    // than waiting for window.onload (which only fires once every
    // resource on the page - including the hero image - has finished
    // loading). The <body> tag already renders in dark mode by default,
    // so on a slow connection the settings panel could be opened while
    // the checkbox still hadn't been ticked yet.
    document.addEventListener('DOMContentLoaded', function() {
        // Load saved theme preference on page load. Light mode is the
        // default (the <body> tag has no dark-mode class by default),
        // so dark mode is only applied when explicitly saved.
        const savedTheme = localStorage.getItem('theme');
        const themeToggle = document.getElementById('theme-toggle');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
            if (themeToggle) {
                themeToggle.checked = true;
            }
        } else {
            document.body.classList.remove('dark-mode');
            if (themeToggle) {
                themeToggle.checked = false;
            }
        }
    });

    // Toggle theme function (activates/deactivates Dark Mode)
    function toggleTheme(checkbox) {
        if (checkbox.checked) {
            document.body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
        } else {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light');
        }
    }
</script>

<script>
    // These names are set in PHP at the top of this file.
    const bot_name = "<?php echo $bot_name; ?>";
    const user_name = "<?php echo $user_name; ?>";
</script>

<script>
    // ============================================================
    // Image attach: pick, compress, preview
    // ============================================================
    // Selecting a file compresses it client-side (via canvas) into a
    // small JPEG data URI and shows a thumbnail preview immediately.
    // The actual image only gets sent to the server when the message
    // is submitted - see form.onsubmit below.

    var imageInput = document.getElementById('image-input');
    var imagePreviewRow = document.getElementById('image-preview-row');
    var imagePreviewThumb = document.getElementById('image-preview-thumb');
    var removeImageBtn = document.getElementById('remove-image-btn');

    // Holds the compressed data URI of the currently-selected image,
    // once compression has finished, or null if none is selected/ready.
    var pendingImageDataUrl = null;

    // While a file is being compressed, this holds a Promise that
    // resolves to its data URI (or null on failure). form.onsubmit
    // waits on THIS - not on pendingImageDataUrl directly - so that a
    // fast, hands-free voice submission can't race ahead of a still-
    // in-progress compression and get sent without its image.
    var pendingImageCompressionPromise = null;

    // Bumped on every new selection or removal, so a slow compression
    // that finishes after the user has already moved on (picked a
    // different file, or hit remove) doesn't clobber the current state.
    var imageGeneration = 0;

    // Resizes/re-encodes an image file down to a max dimension and
    // JPEG quality, keeping the request payload small. Returns a
    // Promise that resolves with a "data:image/jpeg;base64,..." string.
    function compressImageForUpload(file, maxDim, quality) {
        return new Promise(function(resolve, reject) {
            var objectUrl = URL.createObjectURL(file);
            var img = new Image();

            img.onload = function() {
                URL.revokeObjectURL(objectUrl);

                var scale = Math.min(1, maxDim / Math.max(img.width, img.height));
                var targetWidth = Math.round(img.width * scale);
                var targetHeight = Math.round(img.height * scale);

                var canvas = document.createElement('canvas');
                canvas.width = targetWidth;
                canvas.height = targetHeight;

                var ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, targetWidth, targetHeight);

                resolve(canvas.toDataURL('image/jpeg', quality));
            };

            img.onerror = function(err) {
                URL.revokeObjectURL(objectUrl);
                reject(err);
            };

            img.src = objectUrl;
        });
    }

    // Clears the pending image state, hides the preview row, and
    // resets the file input so the same file can be picked again later.
    function clearImagePreview() {
        imageGeneration++;
        pendingImageDataUrl = null;
        pendingImageCompressionPromise = null;
        imagePreviewThumb.src = '';
        imagePreviewRow.style.display = 'none';
        imageInput.value = '';
    }

    // Takes a File (from either the file picker or a drag-and-drop),
    // validates it, and kicks off compression/preview. Shared by both
    // entry points so they behave identically.
    function handleSelectedImageFile(file) {
        if (!file) {
            return;
        }

        if (!file.type.startsWith('image/')) {
            alert('Please choose an image file.');
            clearImagePreview();
            return;
        }

        var myGeneration = ++imageGeneration;
        pendingImageDataUrl = null;
        imagePreviewRow.style.display = 'none';

        var compressionPromise = compressImageForUpload(file, 1024, 0.7).then(function(dataUrl) {
            // Only touch the visible preview/state if this is still the
            // most recent selection - an older, slower compression
            // finishing after a newer pick (or a removal) shouldn't
            // clobber it. The resolved value is still returned either
            // way, for whichever submit is actually waiting on it.
            if (myGeneration === imageGeneration) {
                pendingImageDataUrl = dataUrl;
                imagePreviewThumb.src = dataUrl;
                imagePreviewRow.style.display = 'flex';
            }
            return dataUrl;
        }).catch(function(err) {
            console.log('Image compression failed:', err);
            if (myGeneration === imageGeneration) {
                alert('Sorry, that image could not be processed.');
                clearImagePreview();
            }
            return null;
        });

        pendingImageCompressionPromise = compressionPromise;
    }

    imageInput.addEventListener('change', function() {
        handleSelectedImageFile(imageInput.files[0]);
    });

    removeImageBtn.addEventListener('click', function() {
        clearImagePreview();
    });

    // ============================================================
    // Drag-and-drop: whole page is a drop zone
    // ============================================================
    // No full-page overlay - instead, a subtle border appears around
    // the page and the attach (camera) icon lights up, so a drop feels
    // available without dimming or blocking the rest of the content.
    //
    // dragenter/dragleave both bubble up from every element the cursor
    // crosses while dragging, so a plain "add on enter, remove on
    // leave" toggle flickers constantly as the cursor moves over child
    // elements. A counter fixes that: only the very first enter (from
    // outside the window) turns the indicator on, and only the count
    // dropping back to zero turns it off.
    var dragCounter = 0;

    // Ignores drags that aren't carrying files (e.g. dragging selected
    // text around the page) - only file drags should arm the indicator.
    function dragEventHasFiles(event) {
        return event.dataTransfer && Array.prototype.indexOf.call(event.dataTransfer.types || [], 'Files') !== -1;
    }

    document.addEventListener('dragenter', function(event) {
        if (!dragEventHasFiles(event)) {
            return;
        }
        event.preventDefault();
        dragCounter++;
        document.body.classList.add('page-drag-active');
    });

    document.addEventListener('dragover', function(event) {
        // Always prevent the default here - this is what allows a
        // 'drop' event to fire at all instead of the browser
        // navigating to the dropped file.
        if (dragEventHasFiles(event)) {
            event.preventDefault();
        }
    });

    document.addEventListener('dragleave', function(event) {
        if (!dragEventHasFiles(event)) {
            return;
        }
        dragCounter = Math.max(0, dragCounter - 1);
        if (dragCounter === 0) {
            document.body.classList.remove('page-drag-active');
        }
    });

    document.addEventListener('drop', function(event) {
        if (!dragEventHasFiles(event)) {
            return;
        }
        event.preventDefault();
        dragCounter = 0;
        document.body.classList.remove('page-drag-active');

        var file = event.dataTransfer.files && event.dataTransfer.files[0];
        handleSelectedImageFile(file);
    });
</script>

<script>
	// *** DEV_MODE ***
    // PHP Ajax Code
    ///////////////////

    // Dev-only: set to true locally to show the token/cost panel
    // (#dev-usage-panel). Leave false in production.
    var DEV_MODE = false; // true/false

    function renderDevUsagePanel(debugUsage) {
        var panel = document.getElementById('dev-usage-panel');
        if (!panel || !DEV_MODE || !debugUsage) {
            return;
        }

        function fmtAgent(label, usage) {
            if (!usage) {
                return label + ': (not called)';
            }
            var cost = (usage.cost !== undefined) ? '$' + Number(usage.cost).toFixed(6) : 'n/a';
            var model = usage.model_id ? ' [' + usage.model_id + ']' : '';
            return label + model + ': ' + (usage.prompt_tokens ?? '?') + ' in / '
                + (usage.completion_tokens ?? '?') + ' out - ' + cost;
        }

        var lines = [
            fmtAgent('Proofreader', debugUsage.agents.proofreader),
            fmtAgent('Chat', debugUsage.agents.chat),
            fmtAgent('Translation', debugUsage.agents.translation),
            '---',
            'History pairs: ' + debugUsage.history_pairs,
            'Total tokens: ' + debugUsage.total_prompt_tokens + ' in / ' + debugUsage.total_completion_tokens + ' out',
            'Total cost this request: $' + Number(debugUsage.total_cost).toFixed(6)
        ];

        panel.innerHTML = lines.join('<br>');
        panel.style.display = 'block';
    }

    var form = document.getElementById('myForm');
	
    form.onsubmit = function(event) {
        // Prevent the default form submission behavior
        event.preventDefault();
		
        // Get the form data. This MUST happen synchronously, right here,
        // in the same tick as the click - not after any await/then below.
        // Voice submissions (submit_text_to_php) set the input's value,
        // simulate a click on the submit button, and then immediately
        // clear that value again on the very next line. If reading the
        // form data were delayed, it would come back empty for voice
        // messages.
        var formData = new FormData(form);
        var $my_message = formData.get("my_message");
		
        // Snapshot whichever image-compression promise is currently in
        // flight (if any), rather than reading pendingImageDataUrl
        // directly - it may still be null right now even though an
        // image WAS attached, simply because compression hasn't
        // finished yet. Waiting on this specific promise is what stops
        // a fast, hands-free voice message from going out before its
        // attached image is ready, which used to split one message
        // into two separate sends (and two separate bot replies).
        var imageWait = pendingImageCompressionPromise || Promise.resolve(pendingImageDataUrl);
		
        // Reset the visible form/preview right away so the UI feels
        // responsive - the actual send below still waits on imageWait.
        form.reset();
        clearImagePreview();
		
        imageWait.then(function(imageToSend) {
			
            // This will prevent the form from submitting if there's
            // neither text nor an attached image.
            if ($my_message == "" && !imageToSend) {
                return; // Exit the function if the condition is not met
            }

            // Prevent a second message being sent before the first
            // response has come back. Without this, the button/input
            // stay enabled the whole time the request is in flight, so
            // clicking Send (or pressing Enter) again - or a fast voice
            // submission - can fire a second request on top of the
            // first. Re-enabled in xhr.onload/xhr.onerror below,
            // whichever fires.
            var submitBtn = document.getElementById('submit-btn');
            var userInput = document.getElementById('user-input');
            if (submitBtn) submitBtn.disabled = true;
            if (userInput) userInput.disabled = true;

            // Hide the suggested-prompt chips once a real message goes
            // out - they're a first-message nudge, not a permanent
            // fixture, so they get out of the way after that.
            var suggestedPrompts = document.getElementById("suggested-prompts");
            if (suggestedPrompts) {
                suggestedPrompts.style.display = "none";
            }
			
            // Format the input into paragraphs. This
            // adds paragraph html to the students chat.
            // It's main use is in Maiya's chat where the long response needs 
            // to be formatted into separate paragraphs.
            $my_message = formatResponse($my_message);
			
            // Prepend a thumbnail of the sent image to the user's own
            // chat bubble, if one was attached.
            var displayText = imageToSend
                ? '<img src="' + imageToSend + '" class="chat-image-thumb" alt="Sent image">' + $my_message
                : $my_message;
			
            var input_message = {
                sender: user_name,
                text: displayText
            };
			
            console.log(input_message.text);
			
            // Add a user message to the chat
            addMessageToChat(input_message);
			
            // Show the spinner while waiting for the response from openai
            create_spinner_div();
			
            // Scroll the page up by clicking on a div at the bottom of the page.
            simulateClick('scroll-page-up');
			
            // Delete the id from the message container.
            // It will get added again when the message container is created.
            var element = document.getElementById("chatbot");
            element.removeAttribute("id");
			
            // Attach the compressed image to the outgoing request, if any.
            if (imageToSend) {
                formData.append('my_image', imageToSend);
            }
			
            // Send an AJAX request to the server to process the form data
            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.onload = function() {
			
                if (xhr.status === 200) {
				
                    var response;
                    try {
                        response = JSON.parse(xhr.responseText);
                    } catch (e) {
                        // Server returned 200 but the body wasn't valid
                        // JSON (e.g. a stray PHP warning/notice printed
                        // before the JSON payload). Without this guard,
                        // the throw would abort the handler right here,
                        // leaving the spinner on screen and the form
                        // disabled forever - the same failure mode as
                        // the non-200 case handled below.
                        console.log("Failed to parse server response:", e);
                        delete_spinner_div();
                        alert('Sorry, something went wrong processing your message. Please try again.');
                        if (submitBtn) submitBtn.disabled = false;
                        if (userInput) userInput.disabled = false;
                        return;
                    }
				
                    console.log("===API Output===");
				
    				var check_text = response.check_array;
				
    				console.log('==Check text==');
    				console.log(check_text);
				
    				// Dev-only token/cost panel (no-op unless DEV_MODE is true).
    				renderDevUsagePanel(response.debug_usage);
				
    				// Make these variables global by attaching
    				// them to the window object.
    				window.speech_lang_code = response.speech_lang_code;
    				window.speech_voice_name = response.speech_voice_name;
    				window.speech_rate = response.speech_rate;
				
				
                    var response_text = response.chat_text;
                    var text_to_speak = response.text_to_speak;
                    var speak_status = response.speak_status;
                    var translation_language = response.translation_language;
                    let correctedUserMessage = response.corrected_text;
				
                    if (correctedUserMessage !== 'none' && correctedUserMessage !== 'api_error') {
                        correctedUserMessage = correctedUserMessage;
                    }
				
                    let translatedChatAgentResponse = response.translated_text;
				
                    if (translatedChatAgentResponse !== 'none' && translatedChatAgentResponse !== 'api_error') {
                        translatedChatAgentResponse = replaceItemsInString(translatedChatAgentResponse);
					
    					// Remove the escape backslashes (\")
    					translatedChatAgentResponse = removeEscapeSlashes(translatedChatAgentResponse);
					
    					// Remove newline chracters (\n\n)
    					translatedChatAgentResponse = removeNewlines(translatedChatAgentResponse);
                    }
				
				
    				let chatAgentResponse = response.chat_text;
				
                    // Remove emojis
                    chatAgentResponse = removeEmojis(chatAgentResponse);
				
                    let correctedText, translatedText, chatText, finalText;
				
                    // Handle corrected user message
                    if (correctedUserMessage !== 'none') {
                        correctedText = `<p class='lighter-black'><i>Correction: ${correctedUserMessage}</i></p>`;
                    } else {
                        correctedText = "";
                    }
				
                    // Handle translated chat agent response
                    if (translatedChatAgentResponse !== 'none') {
                        translatedText = `<p class='lighter-black'>${translatedChatAgentResponse}</p>`;
                    } else {
                        translatedText = "";
                    }
				
                    console.log(speak_status);
				
                    // For Deaf Accessibility.
                    // Deaf people won't know that the audio is on
                    // and the chatbot is speaking.
                    if (speak_status == 'selected') {
                        // Handle chat agent response
                        chatText = `<p class="clickable" onclick="speakText(this)">${chatAgentResponse}<i class="fa fa-volume-up w3-text-teal display-block speaker-icon" style="font-size:18px" title="Click to play"></i></p>`;
                    } else {
                        // Handle chat agent response
                        chatText = `<p class="clickable" onclick="speakText(this)">${chatAgentResponse}<i class="fa fa-volume-off w3-text-teal display-block speaker-icon" style="font-size:18px" title="Click to play"></i></p>`;
                    }
				
                    // Combine all parts into final text
                    finalText = correctedText + chatText + translatedText;
				
                    console.log(finalText);  // Output the final text
				
                    var input_message = {
                        sender: bot_name,
                        text: finalText
                    };
				
                    console.log("--Check--");
                    console.log(response.check_variable);
				
                    // Add the 'selected' attribute to the dropdown menu
                    updateSelectedLanguage(translation_language);
				
                    // *** Remove any html and then speak *** //
                    ////////////////////////////////////////////
                    let cleaned_text = removeHtmlTags(text_to_speak);
				
                    // Remove any emojis
                    cleaned_text = removeEmojis(cleaned_text);
				
                    // Delete the div containing the spinner
                    delete_spinner_div();
				
                    // Add the message from Maiya to the chat
                    addMessageToChat(input_message);

                    if (speak_status == 'selected') {
                        // The message container still has its temporary
                        // #chatbot id at this point (createMessageContainer
                        // sets it, and it gets removed a few lines below),
                        // so this is the newly-added message's own icon -
                        // not some other message's.
                        var chatbotElement = document.getElementById('chatbot');
                        var newIcon = chatbotElement ? chatbotElement.querySelector('.speaker-icon') : null;
                        speak(cleaned_text, speech_lang_code, speech_voice_name, speech_rate, newIcon);
                    }
				
                    // Scroll the page up by clicking on a div at the bottom of the page.
                    // ***** Change this to click on the bot message div, then delete the div id ****
                    simulateClick('scroll-to-bot-message');
				
                    // Delete the id from the message container.
                    // It will get added again when the message container is created.
                    var element = document.getElementById("chatbot");
				
                    element.removeAttribute("id");
				
                    // Only put the cursor into the input field
                    // if the user is not using a cellphone.
                    // If the cursor is in the input field on a phone then the keyboard
                    // gets displayed. This affects the page scrolling to the bot message.
                    var screenWidth = window.screen.width;
                    var screenHeight = window.screen.height;
				
                    // Assuming a threshold of 768 pixels as a cutoff for mobile devices
                    var isMobile = screenWidth <= 768;
                    if (isMobile) {
                        console.log("User is using a cellphone");
                    } else {
                        console.log("User is not using a cellphone");
                        // Put the cursor in the form input field
                        const inputField = document.getElementById("user-input");
                        inputField.focus();
                    }
                } else {
                    // Server responded, but with a non-200 status (e.g. a
                    // PHP fatal error, or a 500/502/504 from a proxy/gateway
                    // timeout while waiting on the AI API). xhr.onerror does
                    // NOT fire for this case - it only fires for network-
                    // level failures - so without this branch the spinner
                    // was left on screen forever with no feedback to the
                    // user.
                    console.log("Request failed with status: " + xhr.status);
                    delete_spinner_div();
                    alert('Sorry, something went wrong processing your message. Please try again.');
                }

                // Re-enable the form now that the response (success or
                // otherwise) has been handled, so the user can send
                // their next message.
                if (submitBtn) submitBtn.disabled = false;
                if (userInput) userInput.disabled = false;
            };
            xhr.onerror = function() {
                // Network-level failure (e.g. connection dropped). Make
                // sure the form doesn't stay stuck disabled forever.
                delete_spinner_div();
                if (submitBtn) submitBtn.disabled = false;
                if (userInput) userInput.disabled = false;
                alert('Sorry, something went wrong sending your message. Please try again.');
            };
        xhr.send(formData);
        }); // end imageWait.then
    };
</script>

<script>

// ============================================================
// Voicechat (Speech-to-Text) state & feature detection
// ============================================================

// Tracks whether the mic is SUPPOSED to be listening right now
// (i.e. whether voicechat mode is turned on). This is tracked
// independently of the SpeechRecognition 'end' event and of TTS
// playback state, so that muting or a stray 'end' event can't
// silently leave the mic stuck on or off - see quiet_please()
// and restart_recognition_if_needed() above.
let micShouldBeOn = false;

// Feature detection, done once up front. Some browsers (Firefox,
// most iOS Safari) don't implement SpeechRecognition at all - the
// old code just silently did nothing when clicked on those browsers.
window.SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
const speechRecognitionSupported = !!window.SpeechRecognition;
const speechSynthesisSupported = !!window.speechSynthesis;

document.addEventListener('DOMContentLoaded', () => {
    const voicechatBtn = document.getElementById('start-voicechat-btn');
    if (voicechatBtn && !speechRecognitionSupported) {
        voicechatBtn.disabled = true;
        voicechatBtn.setAttribute('aria-label', 'Voicechat not supported in this browser');
        voicechatBtn.title = 'Speech recognition is not supported in this browser. Try Google Chrome on desktop or Android.';
        voicechatBtn.style.opacity = '0.5';
        voicechatBtn.style.cursor = 'not-allowed';
    }
});


// Event listener function.
// When the 'end' event fires, this restarts the mic - but only if
// voicechat mode is still supposed to be on. This is how "always
// listening" is simulated, since the API doesn't have a true
// "always on" mode.
function handleEnd() {
    console.log('Recognition ended.');
    if (micShouldBeOn) {
        console.log('Event listener restarting mic...');
        try {
            window.recognition.start();
        } catch (err) {
            console.log('Could not restart recognition:', err);
        }
    }
}


// Handles recognition errors so the user isn't left staring at
// "Listening..." forever with no idea what went wrong.
function handleRecognitionError(event) {
    console.log('Recognition error:', event.error);

    // Not a real problem - the mic just hasn't heard anything yet.
    // Let handleEnd() (which fires right after) restart it as normal.
    if (event.error === 'no-speech') {
        return;
    }

    let message;
    switch (event.error) {
        case 'not-allowed':
        case 'service-not-allowed':
            message = 'Microphone access was denied. Please allow microphone access in your browser and try again.';
            break;
        case 'audio-capture':
            message = 'No microphone was found. Please check your microphone and try again.';
            break;
        case 'network':
            message = 'A network error interrupted voice recognition. Please try again.';
            break;
        default:
            message = 'Voice recognition ran into a problem (' + event.error + '). Please try again.';
    }

    // Stop trying to auto-restart the mic after a real error, and
    // reset the UI so the user can see something went wrong.
    stop_recognition();
    alert(message);
}


function initialize_recognition(lang_code) {

    const recognition = new SpeechRecognition();

    //recognition.continuous = true;

    // *** Comment out this line for better performance on Android. ***
    // When this line is commented out there's no intermediate voice detections,
    // however, the bot works much better on Android.
    //recognition.interimResults = true;

    // Set the language you want
    recognition.lang = lang_code; //'ja-JP'; // or 'th-TH' for Thai // en-US

    console.log('Detection lang:');
    console.log(lang_code);

    // Make the recognition object available globally
    window.recognition = recognition;

    console.log('recognition initialized');

    window.recognition.addEventListener('end', handleEnd);
    window.recognition.addEventListener('error', handleRecognitionError);

    window.recognition.addEventListener("result", (e) => {

        let text = Array.from(e.results)
            .map((result) => result[0])
            .map((result) => result.transcript)
            .join("");

        if (e.results[0].isFinal) {

            // Format the input into paragraphs. This
            // adds paragrah html to the user's chat.
            // It's main use is where the bot's long response needs
            // to be formatted into separate paragraphs.
            text = formatResponse(text);

            // Use the form to submit the text to php for processing
            submit_text_to_php(text);
        }
    });

    window.recognition.start();

    // Select the button by ID
    const button = document.getElementById("start-voicechat-btn");

    // Show the mic as active/listening: swap to the "slash" icon,
    // add an orange border, and update the accessible label.
    if (button) {
        button.style.border = "2px solid orange";
        button.style.borderRadius = "8px";
        button.setAttribute('aria-label', 'Stop Voicechat');
        button.setAttribute('title', 'Stop Voicechat');
        const icon = button.querySelector('i');
        if (icon) {
            icon.classList.remove('fa-microphone');
            icon.classList.add('fa-microphone-slash');
        }
    }

}


// Stops voicechat mode entirely (as opposed to the temporary stop
// that happens while the bot is speaking). Tears down the
// recognition object so a future Start click can't stack a second,
// leaked recognizer on top of a stale one.
function stop_recognition() {
    micShouldBeOn = false;

    if (window.recognition) {
        window.recognition.removeEventListener('end', handleEnd);
        window.recognition.removeEventListener('error', handleRecognitionError);
        try {
            window.recognition.stop();
        } catch (err) {
            console.log('Recognition already stopped:', err);
        }
        window.recognition = null;
    }

    const button = document.getElementById("start-voicechat-btn");
    if (button) {
        button.style.border = "";
        button.setAttribute('aria-label', 'Start Voicechat');
        button.setAttribute('title', 'Start Voicechat');
        const icon = button.querySelector('i');
        if (icon) {
            icon.classList.remove('fa-microphone-slash');
            icon.classList.add('fa-microphone');
        }
    }
}


// Submits generated text
function submit_text_to_php(my_text) {
    // Select the input element by its id
    const inputElement = document.getElementById('user-input');

    // Set the value attribute
    inputElement.setAttribute('value', my_text);

    // Simulate a click on the form submit button
    // This will send the form to the php code for processing.
    simulateClick('submit-btn');

    // Clear the value that was set
    inputElement.setAttribute('value', "");
}


// Source: Speech Recognition App Using Vanilla JavaScript
// https://www.youtube.com/watch?v=-k-PgvbktX4
//
// Toggles voicechat mode on/off. Replaces the old start-only
// button - previously there was no way to turn the mic off short
// of muting (which only affects TTS) or leaving the page. Also
// guards against the old leak where clicking "Start" again while
// already running would spin up a second overlapping recognizer.
function toggle_voicechat(lang_code) {

    if (!speechRecognitionSupported) {
        alert('Sorry, voice recognition is not supported in this browser. Please try Google Chrome.');
        return;
    }

    // Already running - this click means "stop".
    if (window.recognition) {
        stop_recognition();
        return;
    }

    micShouldBeOn = true;
    initialize_recognition(lang_code);
}
</script>

<script>
/* ============================================================
   7-Day Practice Plan overlay.
   Reuses the app's existing submit_text_to_php() - the same
   function the mic input and the suggested-prompt buttons call -
   so a tapped prompt is sent exactly as if the user had typed
   and submitted it themselves. Checkbox state is independent of
   sending and persists across visits via localStorage.
   ============================================================ */
document.addEventListener('DOMContentLoaded', () => {

    const PLAN_PROMPTS = [
        "Let's roleplay \u2014 you're a barista at a caf\u00e9 and I'm a customer.",
        "Let's talk about my family.",
        "I want to tell you about a place I've visited \u2014 please ask me questions about it.",
        "Let's roleplay \u2014 you're a server in McDonald's and I'm a customer.",
        "Let's talk about what a typical day looks like for me.",
        "Let's roleplay \u2014 you're a shop assistant and I'm returning a shirt I bought.",
        "Let's talk about my plans for the future."
    ];

    const STORAGE_KEY = 'ebot_practice_plan_progress';

    const triggerBtn = document.getElementById('practice-plan-btn');
    const overlay = document.getElementById('plan-overlay');
    const closeBtn = document.getElementById('close-plan-overlay');
    const listEl = document.getElementById('plan-list');
    const fillEl = document.getElementById('plan-progress-fill');
    const countEl = document.getElementById('plan-progress-count');

    if (!triggerBtn || !overlay || !listEl) {
        return;
    }

    function loadProgress() {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            return raw ? JSON.parse(raw) : {};
        } catch (e) {
            return {};
        }
    }

    function saveProgress(state) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
        } catch (e) {
            // localStorage unavailable (e.g. private browsing) - checkboxes
            // still work for the current page view, just won't persist.
        }
    }

    let planState = loadProgress();

    function updateProgress() {
        const total = PLAN_PROMPTS.length;
        const done = Object.values(planState).filter(Boolean).length;
        countEl.textContent = done + ' / ' + total;
        const pct = (done / total) * 100;
        fillEl.style.width = pct + '%';
        fillEl.classList.toggle('complete', done === total);
    }

    function renderPlan() {
        listEl.innerHTML = '';
        PLAN_PROMPTS.forEach((text, i) => {
            const checked = !!planState[i];

            const li = document.createElement('li');
            li.className = 'plan-item' + (checked ? ' checked' : '');

            const checkbox = document.createElement('button');
            checkbox.type = 'button';
            checkbox.className = 'plan-checkbox';
            checkbox.setAttribute('aria-label', checked ? 'Mark exercise ' + (i + 1) + ' as not done' : 'Mark exercise ' + (i + 1) + ' as done');
            checkbox.innerHTML = '<span class="plan-num">' + (i + 1) + '</span>';
            checkbox.addEventListener('click', () => {
                planState[i] = !planState[i];
                saveProgress(planState);
                renderPlan();
            });

            const promptBtn = document.createElement('button');
            promptBtn.type = 'button';
            promptBtn.className = 'plan-prompt';
            promptBtn.textContent = text;
            promptBtn.addEventListener('click', () => {
                submit_text_to_php(text);
                closeOverlay();
            });

            li.appendChild(checkbox);
            li.appendChild(promptBtn);
            listEl.appendChild(li);
        });
        updateProgress();
    }

    function openOverlay() {
        overlay.classList.add('open');
        closeBtn.focus();
        document.addEventListener('keydown', handleEscape);
    }

    function closeOverlay() {
        overlay.classList.remove('open');
        triggerBtn.focus();
        document.removeEventListener('keydown', handleEscape);
    }

    function handleEscape(e) {
        if (e.key === 'Escape') {
            closeOverlay();
        }
    }

    triggerBtn.addEventListener('click', openOverlay);
    closeBtn.addEventListener('click', closeOverlay);

    // Clicking the dimmed backdrop itself (not the list or close
    // button) also closes it, matching the language overlay's feel.
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            closeOverlay();
        }
    });

    renderPlan();
});
</script>

<?php
// This is important.
// If this is not done then the session variables will still
// be available even after the tab is closed. By doing this the
// session variables get deleted when the tab is closed.
// You can print out the message history to confirm that the
// session variable has been deleted: print_r($_SESSION['message_history']);

// remove all session variables
session_unset();

// destroy the session
session_destroy();
?>