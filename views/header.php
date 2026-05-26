<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= APP_NAME ?> - <?= $pageTitle ?? 'Dashboard' ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6366f1',
                        primaryDark: '#312e81',
                        primaryDeep: '#1e1b4b',
                        secondary: '#8b5cf6',
                        accent: '#f59e0b',
                        success: '#10b981',
                        danger: '#ef4444',
                        warning: '#f59e0b',
                        info: '#06b6d4',
                        textMain: '#1e293b',
                        textSub: '#64748b',
                        surface: '#f8fafc',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    boxShadow: {
                        'card': '0 1px 3px 0 rgb(0 0 0 / 0.04), 0 1px 2px -1px rgb(0 0 0 / 0.04)',
                        'card-hover': '0 10px 15px -3px rgb(0 0 0 / 0.08), 0 4px 6px -4px rgb(0 0 0 / 0.04)',
                        'glow-primary': '0 0 20px rgb(99 102 241 / 0.3)',
                        'glow-success': '0 0 20px rgb(16 185 129 / 0.3)',
                    }
                }
            }
        }
    </script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .gradient-header {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #6366f1 100%);
        }
        .gradient-sidebar {
            background: linear-gradient(180deg, #1e1b4b 0%, #312e81 100%);
        }
        .card-green { background: linear-gradient(135deg, #059669, #10b981); }
        .card-orange { background: linear-gradient(135deg, #d97706, #f59e0b); }
        .card-blue { background: linear-gradient(135deg, #4f46e5, #818cf8); }
        .card-red { background: linear-gradient(135deg, #dc2626, #f87171); }
        .card-purple { background: linear-gradient(135deg, #7c3aed, #a78bfa); }
        .card-cyan { background: linear-gradient(135deg, #0891b2, #22d3ee); }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .bottom-nav-active { color: #6366f1; }
        .menu-icon-box {
            width: 56px; height: 56px;
            border-radius: 16px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: all 0.2s;
        }
        .menu-icon-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* Sidebar auto-collapse transition */
        #desktopContent {
            transition: margin-left 0.3s ease-in-out;
        }
        #desktopSidebar {
            transition: width 0.3s ease-in-out;
        }
        .sidebar-label {
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .sub-menu {
            transition: max-height 0.3s ease, opacity 0.3s ease;
        }
        .submenu-arrow {
            transition: transform 0.3s ease;
        }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-surface min-h-screen antialiased">
