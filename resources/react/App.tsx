import React from 'react';
import { BrowserRouter } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import { NotificationProvider } from './context/NotificationContext';
import { AppRoutes } from './routes/AppRoutes';

const basename = typeof window !== 'undefined' && window.location.pathname.startsWith('/react') ? '/react' : '/';

export const App: React.FC = () => {
    return (
        <BrowserRouter basename={basename}>
            <AuthProvider>
                <NotificationProvider>
                    <AppRoutes />
                </NotificationProvider>
            </AuthProvider>
        </BrowserRouter>
    );
};

export default App;
