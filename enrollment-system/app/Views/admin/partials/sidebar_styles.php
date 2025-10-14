<style>
    /* Normalize container and layout across admin views */
    .container {
        width: 100%;
        margin: 0 auto;
    }
    .layout {
        display: flex;
        gap: 6px;
        align-items: flex-start;
    }
    .sidebar {
        width: 240px;
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        height: fit-content;
        position: sticky;
        top: 20px;
        align-self: flex-start;
    }
    .sidebar .nav {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .sidebar .btn {
        width: 100%;
        text-align: left;
        margin: 0;
        display: block;
        box-sizing: border-box;
    }
    .main-content {
        flex: 1;
        min-width: 0; /* prevent overflow inconsistencies */
    }
    
    /* Global button theming for admin views */
    .btn {
        background-color: #007bff; /* blue */
        color: #ffffff;
        border: none;
        transition: background-color 0.15s ease, color 0.15s ease, border-color 0.15s ease;
    }
    .btn:hover {
        background-color: #0069d9;
        color: #ffffff;
    }
    .btn:active,
    .btn:focus {
        background-color: #ffffff;
        color: #007bff;
        outline: none;
        border: 1px solid #007bff;
    }
    /* Persist active state for current view */
    .btn.active {
        background-color: #ffffff;
        color: #007bff;
        border: 1px solid #007bff;
    }
    
    /* Variants keep maroon base unless otherwise overridden on active */
    .btn-warning,
    .btn-info,
    .btn-success,
    .btn-danger,
    .btn-secondary {
        background-color: #007bff;
        color: #ffffff;
    }
    .btn-warning:hover,
    .btn-info:hover,
    .btn-success:hover,
    .btn-danger:hover,
    .btn-secondary:hover {
        background-color: #0069d9;
        color: #ffffff;
    }
    .btn-warning:active,
    .btn-info:active,
    .btn-success:active,
    .btn-danger:active,
    .btn-secondary:active,
    .btn-warning:focus,
    .btn-info:focus,
    .btn-success:focus,
    .btn-danger:focus,
    .btn-secondary:focus {
        background-color: #ffffff;
        color: #007bff;
        border: 1px solid #007bff;
        outline: none;
    }
    .btn-warning.active,
    .btn-info.active,
    .btn-success.active,
    .btn-danger.active,
    .btn-secondary.active {
        background-color: #ffffff;
        color: #007bff;
        border: 1px solid #007bff;
    }
</style>

