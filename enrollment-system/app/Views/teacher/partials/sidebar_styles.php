<style>
    /* Modern Teacher Layout - SB Admin 2 Inspired */
    
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
        overflow-y: auto;
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
    
    /* Navigation Sections */
    .nav-section {
        margin: 1rem 0;
    }
    
    .nav-section-title {
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.6);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    
    .nav-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.1);
        margin: 0.5rem 1rem;
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
        background-color: #218838;
        border-color: #224abe;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }
    
    .btn:not(.sidebar .btn):active {
        background-color: #224abe;
        border-color: #1c7430;
        transform: translateY(0);
    }
    
    /* Button Variants */
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }
    
    .btn-success {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    
    .btn-success:hover {
        background-color: #218838;
        border-color: #224abe;
    }
    
    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    
    .btn-info:hover {
        background-color: #138496;
        border-color: #117a8b;
    }
    
    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }
    
    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
        color: #212529;
    }
    
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    
    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }
    
    /* Sidebar Logout Button */
    .sidebar .logout-btn {
        background-color: rgba(220, 53, 69, 0.9);
        margin-top: 1rem;
        border-radius: 0.35rem;
        margin-left: 1rem;
        margin-right: 1rem;
        width: calc(100% - 2rem);
        justify-content: center;
        font-weight: 600;
        border-left: 3px solid transparent;
    }
    
    .sidebar .logout-btn:hover {
        background-color: #dc3545;
        padding-left: 1rem;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
            top: 0;
        }
        
        .main-content {
            margin-left: 0;
        }
        
        .layout {
            flex-direction: column;
        }
    }
</style>
