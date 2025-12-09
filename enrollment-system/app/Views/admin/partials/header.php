<style>
    .top-header {
        background: #fff;
        border-bottom: 1px solid #e3e6f0;
        padding: 0.75rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .header-brand {
        font-size: 1.25rem;
        font-weight: 800;
        color: #4e73df;
        text-decoration: none;
    }

    .header-brand:hover {
        color: #2e59d9;
    }

    .admin-profile {
        position: relative;
    }

    .profile-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0.5rem 1rem;
        border-radius: 0.35rem;
        transition: all 0.15s ease-in-out;
    }

    .profile-btn:hover {
        background: #f8f9fc;
    }

    .profile-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1rem;
    }

    .profile-info {
        text-align: left;
    }

    .profile-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: #5a5c69;
        display: block;
        line-height: 1.2;
    }

    .profile-role {
        font-size: 0.75rem;
        color: #858796;
    }

    .profile-dropdown-icon {
        color: #858796;
        font-size: 0.75rem;
        transition: transform 0.2s;
    }

    .profile-btn.active .profile-dropdown-icon {
        transform: rotate(180deg);
    }

    .profile-dropdown {
        position: absolute;
        top: calc(100% + 0.5rem);
        right: 0;
        background: white;
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        min-width: 200px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.2s ease-in-out;
        z-index: 1000;
        overflow: hidden;
    }

    .profile-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-header {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e3e6f0;
        background: #f8f9fc;
    }

    .dropdown-header-name {
        font-weight: 600;
        color: #5a5c69;
        font-size: 0.875rem;
        margin: 0;
    }

    .dropdown-header-email {
        font-size: 0.75rem;
        color: #858796;
        margin: 0.25rem 0 0 0;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: #5a5c69;
        text-decoration: none;
        transition: all 0.15s ease-in-out;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .dropdown-item:hover {
        background: #f8f9fc;
        color: #4e73df;
    }

    .dropdown-item i,
    .dropdown-item span:first-child {
        width: 1.25rem;
        text-align: center;
    }

    .dropdown-divider {
        height: 0;
        margin: 0.5rem 0;
        overflow: hidden;
        border-top: 1px solid #e3e6f0;
    }

    .dropdown-item.logout {
        color: #e74a3b;
        font-weight: 500;
    }

    .dropdown-item.logout:hover {
        background: linear-gradient(135deg, #e74a3b 0%, #d52a1a 100%);
        color: #fff;
        padding-left: 1.25rem;
    }
    
    .dropdown-item.logout:hover span:first-child {
        transform: scale(1.15);
        display: inline-block;
    }
</style>

<header class="top-header">
    <a href="/admin/dashboard" class="header-brand">
        📚 RNTVS AdminT
    </a>

    <div class="admin-profile">
        <button class="profile-btn" id="profileBtn" onclick="toggleProfileDropdown()">
            <div class="profile-avatar">
                <?php 
                $adminName = session()->get('username') ?? 'Admin';
                $initials = strtoupper(substr($adminName, 0, 1));
                echo $initials;
                ?>
            </div>
            <div class="profile-info">
                <span class="profile-name"><?= esc($adminName) ?></span>
                <span class="profile-role">Administrator</span>
            </div>
            <span class="profile-dropdown-icon">▼</span>
        </button>

        <div class="profile-dropdown" id="profileDropdown">
            <div class="dropdown-header">
                <p class="dropdown-header-name"><?= esc($adminName) ?></p>
                <p class="dropdown-header-email"><?= esc(session()->get('email') ?? 'admin@sis.com') ?></p>
            </div>
            
            <a href="/auth/logout" class="dropdown-item logout" onclick="return confirm('Are you sure you want to logout?')">
                <span>🚪</span>
                <span>Logout</span>
            </a>
        </div>
    </div>
</header>

<script>
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        const btn = document.getElementById('profileBtn');
        dropdown.classList.toggle('show');
        btn.classList.toggle('active');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const profile = document.querySelector('.admin-profile');
        const dropdown = document.getElementById('profileDropdown');
        const btn = document.getElementById('profileBtn');
        
        if (!profile.contains(event.target)) {
            dropdown.classList.remove('show');
            btn.classList.remove('active');
        }
    });

    // Close dropdown on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const dropdown = document.getElementById('profileDropdown');
            const btn = document.getElementById('profileBtn');
            dropdown.classList.remove('show');
            btn.classList.remove('active');
        }
    });
</script>

