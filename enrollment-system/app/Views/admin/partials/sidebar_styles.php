<style>
    /* Modern Admin Layout - SB Admin 2 Inspired */
    
    /* Container and Layout */
    .container {
        width: 100%;
        margin: 0 auto;
        padding: 0;
    }
    
    .layout {
        display: flex;
        gap: 0;
        align-items: flex-start;
        min-height: 100vh;
    }
    
    /* Enhanced Sidebar */
    .sidebar {
        width: 224px;
        background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
        padding: 0;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        height: calc(100vh - 65px);
        position: fixed;
        top: 65px;
        left: 0;
        overflow-y: hidden;
        z-index: 50;
    }
    
    .sidebar .nav {
        display: flex;
        flex-direction: column;
        gap: 0;
        padding: 1rem 0;
    }
    
    .sidebar .btn {
        width: 100%;
        text-align: left;
        margin: 0;
        display: flex;
        align-items: center;
        padding: 0.875rem 1rem;
        font-size: 0.85rem;
        font-weight: 400;
        color: rgba(255, 255, 255, 0.8);
        background: transparent;
        border: none;
        border-left: 3px solid transparent;
        border-radius: 0;
        transition: all 0.15s ease;
        text-decoration: none;
        box-sizing: border-box;
    }
    
    .sidebar .btn:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.1);
        border-left-color: rgba(255, 255, 255, 0.5);
        padding-left: 1.25rem;
    }
    
    .sidebar .btn.active {
        color: #fff;
        background: rgba(255, 255, 255, 0.15);
        border-left-color: #fff;
        font-weight: 700;
    }
    
    .main-content {
        flex: 1;
        min-width: 0;
        background-color: #f8f9fc;
        min-height: 100vh;
        padding: 0;
        margin-left: 224px;
    }
    
    /* Enhanced Buttons */
    .btn {
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.35rem;
        transition: all 0.15s ease-in-out;
        cursor: pointer;
        text-decoration: none;
    }
    
    .btn:not(.sidebar .btn) {
        background-color: #4e73df;
        color: #ffffff;
        border-color: #4e73df;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .btn:not(.sidebar .btn):hover {
        background-color: #2e59d9;
        border-color: #2653d4;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }
    
    .btn:not(.sidebar .btn):active {
        background-color: #2653d4;
        border-color: #244ec9;
        transform: translateY(0);
    }
    
    /* Button Variants */
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
    }
    
    .btn-success {
        background-color: #1cc88a;
        border-color: #1cc88a;
    }
    
    .btn-success:hover {
        background-color: #17a673;
        border-color: #169b6b;
    }
    
    .btn-info {
        background-color: #36b9cc;
        border-color: #36b9cc;
    }
    
    .btn-info:hover {
        background-color: #2c9faf;
        border-color: #2a96a5;
    }
    
    .btn-warning {
        background-color: #f6c23e;
        border-color: #f6c23e;
        color: #3a3b45;
    }
    
    .btn-warning:hover {
        background-color: #f4b619;
        border-color: #f4b30d;
        color: #3a3b45;
    }
    
    .btn-danger {
        background-color: #e74a3b;
        border-color: #e74a3b;
    }
    
    .btn-danger:hover {
        background-color: #e02d1b;
        border-color: #d52a1a;
    }
    
    .btn-secondary {
        background-color: #858796;
        border-color: #858796;
    }
    
    .btn-secondary:hover {
        background-color: #717384;
        border-color: #6b6d7d;
    }
    
    /* Sidebar Logout Button */
    .sidebar .btn[href*="logout"] {
        background-color: rgba(231, 74, 59, 0.9);
        margin-top: 1rem;
        border-radius: 0.35rem;
        margin-left: 1rem;
        margin-right: 1rem;
        width: calc(100% - 2rem);
        justify-content: center;
        font-weight: 600;
        border-left: 3px solid transparent;
    }
    
    .sidebar .btn[href*="logout"]:hover {
        background-color: #e74a3b;
        padding-left: 1rem;
    }
</style>

