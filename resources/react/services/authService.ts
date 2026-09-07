import api from './api';
import { ApiResponse, User } from '../types';

export interface LoginResponseData {
    user: User;
    token: string;
    role: 'admin' | 'user';
    redirect_url: string;
}

export const authService = {
    async login(loginInput: string, password: string, remember: boolean = false): Promise<ApiResponse<LoginResponseData>> {
        // First initialize CSRF cookie for Sanctum SPA
        try {
            await api.get('/sanctum/csrf-cookie', { baseURL: '/' });
        } catch (e) {
            // If already set or direct token, proceed
        }

        const response = await api.post<ApiResponse<LoginResponseData>>('/auth/login', {
            login_input: loginInput,
            password: password,
            remember: remember,
        });
        return response.data;
    },

    async me(): Promise<ApiResponse<User>> {
        const response = await api.get<ApiResponse<User>>('/auth/me');
        return response.data;
    },

    async updateProfile(data: { name?: string; username: string; email?: string; no_wa?: string }): Promise<ApiResponse<User>> {
        const response = await api.put<ApiResponse<User>>('/auth/profile', data);
        return response.data;
    },

    async updatePassword(data: { current_password: string; password: string; password_confirmation: string }): Promise<ApiResponse<void>> {
        const response = await api.put<ApiResponse<void>>('/auth/password', data);
        return response.data;
    },

    async logout(): Promise<ApiResponse<void>> {
        const response = await api.post<ApiResponse<void>>('/auth/logout');
        return response.data;
    },
};
