import React, { useEffect, useState } from 'react';
import { Outlet, useLocation } from 'react-router-dom';
import { Sidebar } from '../components/layout/Sidebar';
import { Navbar } from '../components/layout/Navbar';
import { ToastContainer } from '../components/feedback/ToastContainer';
import { CommandPalette } from '../components/common/CommandPalette';

interface AppLayoutProps {
    children?: React.ReactNode;
}

export const AppLayout: React.FC<AppLayoutProps> = ({ children }) => {
    const location = useLocation();

    // Track desktop collapsed state (persisted in localStorage)
    const [isSidebarCollapsed, setIsSidebarCollapsed] = useState<boolean>(() => {
        if (typeof window !== 'undefined' && window.innerWidth > 768) {
            return localStorage.getItem('silakan_sidebar_collapsed') === 'true';
        }
        return false;
    });

    // Track mobile open state
    const [isMobileSidebarOpen, setIsMobileSidebarOpen] = useState(false);

    // Global Command Palette state
    const [isCommandPaletteOpen, setIsCommandPaletteOpen] = useState(false);

    // Global keyboard shortcut: Ctrl+K or Cmd+K
    useEffect(() => {
        const handleKeyDown = (e: KeyboardEvent) => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                setIsCommandPaletteOpen((prev) => !prev);
            }
        };

        window.addEventListener('keydown', handleKeyDown);
        return () => window.removeEventListener('keydown', handleKeyDown);
    }, []);

    // Sync body classes with state
    useEffect(() => {
        if (isSidebarCollapsed && window.innerWidth > 768) {
            document.body.classList.add('sidebar-collapsed');
        } else {
            document.body.classList.remove('sidebar-collapsed');
        }
    }, [isSidebarCollapsed]);

    useEffect(() => {
        if (isMobileSidebarOpen) {
            document.body.classList.add('mobile-sidebar-open');
        } else {
            document.body.classList.remove('mobile-sidebar-open');
        }
    }, [isMobileSidebarOpen]);

    // Handle window resize between mobile and desktop
    useEffect(() => {
        const handleResize = () => {
            if (window.innerWidth > 768) {
                // Leaving mobile: close mobile drawer
                setIsMobileSidebarOpen(false);
                document.body.classList.remove('mobile-sidebar-open');

                // Restore desktop collapsed preference
                const saved = localStorage.getItem('silakan_sidebar_collapsed') === 'true';
                setIsSidebarCollapsed(saved);
                if (saved) {
                    document.body.classList.add('sidebar-collapsed');
                } else {
                    document.body.classList.remove('sidebar-collapsed');
                }
            } else {
                // Entering mobile: remove desktop collapsed class
                document.body.classList.remove('sidebar-collapsed');
            }
        };

        window.addEventListener('resize', handleResize);
        return () => window.removeEventListener('resize', handleResize);
    }, []);

    // Close mobile sidebar whenever location changes
    useEffect(() => {
        setIsMobileSidebarOpen(false);
        document.body.classList.remove('mobile-sidebar-open');
    }, [location.pathname]);

    // Clean up on unmount
    useEffect(() => {
        return () => {
            document.body.classList.remove('sidebar-collapsed');
            document.body.classList.remove('mobile-sidebar-open');
        };
    }, []);

    // Toggle sidebar function
    const handleToggleSidebar = () => {
        if (window.innerWidth <= 768) {
            setIsMobileSidebarOpen((prev) => !prev);
        } else {
            setIsSidebarCollapsed((prev) => {
                const next = !prev;
                localStorage.setItem('silakan_sidebar_collapsed', next ? 'true' : 'false');
                return next;
            });
        }
    };

    const handleCloseMobile = () => {
        setIsMobileSidebarOpen(false);
    };

    return (
        <div className="app">
            <Sidebar
                isOpen={isMobileSidebarOpen}
                isCollapsed={isSidebarCollapsed}
                onCloseMobile={handleCloseMobile}
            />

            {/* Mobile Overlay */}
            <div
                className="sidebar-overlay"
                id="sidebarOverlay"
                onClick={handleCloseMobile}
            />

            <main className="main">
                <Navbar
                    onToggleSidebar={handleToggleSidebar}
                    isSidebarCollapsed={isSidebarCollapsed}
                />

                <section className="content">
                    {children || <Outlet />}
                </section>
            </main>

            <CommandPalette
                isOpen={isCommandPaletteOpen}
                onClose={() => setIsCommandPaletteOpen(false)}
            />

            <ToastContainer />
        </div>
    );
}; 
