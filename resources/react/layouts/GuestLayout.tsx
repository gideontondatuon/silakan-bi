import React from 'react';

export const GuestLayout: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    return (
        <div className="login-page">
            {children}
        </div>
    );
};
