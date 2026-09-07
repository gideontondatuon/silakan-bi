import React, { useState } from 'react';
import { Outlet } from 'react-router-dom';
import { Sidebar } from '../components/layout/Sidebar';
import { Navbar } from '../components/layout/Navbar';
import { ToastContainer } from '../components/feedback/ToastContainer';

interface AppLayoutProps {
    children?: React.ReactNode;
}

export const AppLayout: React.FC<AppLayoutProps> = ({ children }) => {
    const [isMobileSidebarOpen, setIsMobileSidebarOpen] = useState(false);

    return (
        <div className="app">
            <Sidebar
                isOpen={isMobileSidebarOpen}
                onCloseMobile={() => setIsMobileSidebarOpen(false)}
            />

            {/* Mobile Overlay */}
            <div
                className={`sidebar-overlay ${isMobileSidebarOpen ? 'show' : ''}`}
                id="sidebarOverlay"
                onClick={() => setIsMobileSidebarOpen(false)}
            />

            <main className="main">
                <Navbar onToggleSidebar={() => setIsMobileSidebarOpen((prev) => !prev)} />

                <section className="content">
                    {children || <Outlet />}
                </section>
            </main>

            <ToastContainer />
        </div>
    );
};
