import axios, { AxiosError, InternalAxiosRequestConfig } from 'axios';

const api = axios.create({
    baseURL: '/api',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    },
    withCredentials: true,
});

// Request interceptor: attach Sanctum token if present in localStorage
api.interceptors.request.use((config: InternalAxiosRequestConfig) => {
    const token = localStorage.getItem('silakan_token');
    if (token && config.headers) {
        config.headers.Authorization = `Bearer ${token}`;
    }

    // Attach CSRF token from document meta tag if available
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken && config.headers) {
        config.headers['X-CSRF-TOKEN'] = csrfToken;
    }

    return config;
});

// Response interceptor: handle 401 unauthenticated and 419 token mismatch
api.interceptors.response.use(
    (response) => response,
    (error: AxiosError) => {
        if (error.response?.status === 401) {
            // If on protected page, redirect to login
            const currentPath = window.location.pathname;
            if (currentPath !== '/login' && !currentPath.startsWith('/kiosk') && !currentPath.startsWith('/display')) {
                localStorage.removeItem('silakan_token');
                localStorage.removeItem('silakan_user');
                window.location.href = '/login';
            }
        } else if (error.response?.status === 419) {
            // CSRF mismatch / session expired: reload to get fresh session & CSRF
            console.warn('Session expired or CSRF token mismatch, refreshing session...');
            const currentPath = window.location.pathname;
            if (currentPath !== '/login') {
                window.location.reload();
            }
        }
        return Promise.reject(error);
    }
);

export default api;
