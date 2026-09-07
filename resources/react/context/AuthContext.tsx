import React, { createContext, useContext, useEffect, useState } from 'react';
import { User, UserRole } from '../types';
import { authService, LoginResponseData } from '../services/authService';

interface AuthContextType {
    user: User | null;
    role: UserRole | null;
    token: string | null;
    isAuthenticated: boolean;
    isLoading: boolean;
    login: (loginInput: string, password: string, remember?: boolean) => Promise<LoginResponseData>;
    logout: () => Promise<void>;
    refreshUser: () => Promise<void>;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const [user, setUser] = useState<User | null>(() => {
        const cached = localStorage.getItem('silakan_user');
        return cached ? JSON.parse(cached) : null;
    });
    const [token, setToken] = useState<string | null>(() => localStorage.getItem('silakan_token'));
    const [isLoading, setIsLoading] = useState<boolean>(true);

    const role: UserRole | null = user ? user.role : null;
    const isAuthenticated = !!user;

    useEffect(() => {
        // Verify session with backend on initial mount
        const initAuth = async () => {
            const storedToken = localStorage.getItem('silakan_token');
            if (storedToken) {
                try {
                    const res = await authService.me();
                    if (res.status === 'success' && res.data) {
                        setUser(res.data);
                        localStorage.setItem('silakan_user', JSON.stringify(res.data));
                    }
                } catch (e) {
                    // Token invalid or expired
                    setUser(null);
                    setToken(null);
                    localStorage.removeItem('silakan_token');
                    localStorage.removeItem('silakan_user');
                }
            }
            setIsLoading(false);
        };

        initAuth();
    }, []);

    const login = async (loginInput: string, password: string, remember: boolean = false): Promise<LoginResponseData> => {
        const response = await authService.login(loginInput, password, remember);
        const data = response.data;

        setUser(data.user);
        setToken(data.token);
        localStorage.setItem('silakan_token', data.token);
        localStorage.setItem('silakan_user', JSON.stringify(data.user));

        return data;
    };

    const logout = async () => {
        try {
            await authService.logout();
        } catch (e) {
            // Ignore failure on logout
        } finally {
            setUser(null);
            setToken(null);
            localStorage.removeItem('silakan_token');
            localStorage.removeItem('silakan_user');
            window.location.href = '/login';
        }
    };

    const refreshUser = async () => {
        try {
            const res = await authService.me();
            if (res.status === 'success' && res.data) {
                setUser(res.data);
                localStorage.setItem('silakan_user', JSON.stringify(res.data));
            }
        } catch (e) {
            // Ignore
        }
    };

    return (
        <AuthContext.Provider
            value={{
                user,
                role,
                token,
                isAuthenticated,
                isLoading,
                login,
                logout,
                refreshUser,
            }}
        >
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = (): AuthContextType => {
    const context = useContext(AuthContext);
    if (!context) {
        throw new Error('useAuth must be used within an AuthProvider');
    }
    return context;
};
