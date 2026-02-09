<style>
    /* Background Aurora */
    @keyframes moveBlob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    body {
        background-color: #f8fafc;
        position: relative;
        min-height: 100vh;
    }
    body::before, body::after {
        content: ''; position: fixed; width: 60vw; height: 60vw; border-radius: 50%;
        filter: blur(80px); opacity: 0.4; z-index: -1; animation: moveBlob 15s infinite alternate;
    }
    body::before { background: #d1fae5; top: -10%; left: -10%; }
    body::after { background: #cffafe; bottom: -10%; right: -10%; animation-delay: 5s; }

    /* Glass Effect Admin */
    .fi-body, .fi-panel, .fi-main { background-color: transparent !important; }
    .fi-sidebar { background-color: rgba(255, 255, 255, 0.6) !important; backdrop-filter: blur(10px); border-right: 1px solid rgba(255,255,255,0.5); }
    .fi-topbar { background-color: rgba(255, 255, 255, 0.7) !important; backdrop-filter: blur(10px); }
    
    /* Kartu Widget Dashboard */
    .fi-wi-stats-overview-stat {
        background-color: rgba(255, 255, 255, 0.6) !important;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.6) !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
        border-radius: 1rem !important;
    }
</style>