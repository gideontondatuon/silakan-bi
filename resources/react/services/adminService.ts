import api from './api';
import { ApiResponse, AuditLog, Department, HariLibur, LayoutRuangan, PaginatedData, Pemesanan, Ruangan, User } from '../types';

export interface AdminDashboardData {
    stats: {
        total_ruangan: number;
        total_pemesanan: number;
        waiting_approval: number;
        disetujui: number;
        ditolak: number;
        pemesanan_bulan_ini: number;
    };
    kegiatan_hari_ini: Pemesanan[];
    kegiatan_berlangsung: Pemesanan[];
    agenda_mendatang: Pemesanan[];
    waiting_list: Pemesanan[];
    ruangan_terpopuler?: Array<{ ruangan_id: number; total: number; ruangan?: Ruangan }>;
    aktivitas_terbaru: Pemesanan[];
    charts: {
        monthly: { labels: string[]; data: number[] };
        popular_rooms: { labels: string[]; data: number[] };
        unit_distribution: { labels: string[]; data: number[] };
    };
}

export interface ApprovalListData {
    items: PaginatedData<Pemesanan>;
    counts: {
        pending: number;
        disetujui: number;
        selesai: number;
        semua?: number;
    };
}

export const adminService = {
    // Dashboard
    async getAdminDashboard(): Promise<ApiResponse<AdminDashboardData>> {
        const response = await api.get<ApiResponse<AdminDashboardData>>('/dashboard/admin');
        return response.data;
    },

    // Approvals
    async getApprovalList(params?: { tab?: string; q?: string; ruangan_id?: number | string; tanggal?: string; page?: number; per_page?: number }): Promise<ApiResponse<ApprovalListData>> {
        const response = await api.get<ApiResponse<ApprovalListData>>('/admin/approval', { params });
        return response.data;
    },

    async getApprovalDetail(id: number | string): Promise<ApiResponse<Pemesanan>> {
        const response = await api.get<ApiResponse<Pemesanan>>(`/admin/approval/${id}`);
        return response.data;
    },

    async createBooking(formData: FormData): Promise<ApiResponse<Pemesanan>> {
        const response = await api.post<ApiResponse<Pemesanan>>('/admin/approval', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        return response.data;
    },

    async approve(id: number | string, catatanAdmin?: string): Promise<ApiResponse<Pemesanan>> {
        const response = await api.post<ApiResponse<Pemesanan>>(`/admin/approval/${id}/approve`, {
            catatan_admin: catatanAdmin,
        });
        return response.data;
    },

    async reject(id: number | string, alasanPenolakan: string): Promise<ApiResponse<Pemesanan>> {
        const response = await api.post<ApiResponse<Pemesanan>>(`/admin/approval/${id}/reject`, {
            alasan_penolakan: alasanPenolakan,
        });
        return response.data;
    },

    async selesaiAwal(id: number | string): Promise<ApiResponse<Pemesanan>> {
        const response = await api.post<ApiResponse<Pemesanan>>(`/admin/approval/${id}/selesai-awal`);
        return response.data;
    },

    async deleteBooking(id: number | string): Promise<ApiResponse<void>> {
        const response = await api.delete<ApiResponse<void>>(`/admin/approval/${id}`);
        return response.data;
    },

    // Master Ruangan
    async getRuanganList(params?: { q?: string; page?: number; per_page?: number }): Promise<ApiResponse<any>> {
        const response = await api.get<ApiResponse<any>>('/admin/ruangan', { params });
        return response.data;
    },

    async getRuanganDetail(id: number | string): Promise<ApiResponse<Ruangan>> {
        const response = await api.get<ApiResponse<Ruangan>>(`/admin/ruangan/${id}`);
        return response.data;
    },

    async createRuangan(data: { nama_ruangan: string; kapasitas: number; lokasi: string; status: string; layouts?: number[] }): Promise<ApiResponse<Ruangan>> {
        const response = await api.post<ApiResponse<Ruangan>>('/admin/ruangan', data);
        return response.data;
    },

    async updateRuangan(id: number | string, data: { nama_ruangan: string; kapasitas: number; lokasi: string; status: string; layouts?: number[] }): Promise<ApiResponse<Ruangan>> {
        const response = await api.put<ApiResponse<Ruangan>>(`/admin/ruangan/${id}`, data);
        return response.data;
    },

    async deleteRuangan(id: number | string): Promise<ApiResponse<void>> {
        const response = await api.delete<ApiResponse<void>>(`/admin/ruangan/${id}`);
        return response.data;
    },

    // Master Layout
    async getLayoutList(params?: { q?: string; page?: number; per_page?: number }): Promise<ApiResponse<any>> {
        const response = await api.get<ApiResponse<any>>('/admin/layout', { params });
        return response.data;
    },

    async getLayoutDetail(id: number | string): Promise<ApiResponse<LayoutRuangan>> {
        const response = await api.get<ApiResponse<LayoutRuangan>>(`/admin/layout/${id}`);
        return response.data;
    },

    async createLayout(data: { nama_layout: string; ruangan_id?: number | null }): Promise<ApiResponse<LayoutRuangan>> {
        const response = await api.post<ApiResponse<LayoutRuangan>>('/admin/layout', data);
        return response.data;
    },

    async updateLayout(id: number | string, data: { nama_layout: string; ruangan_id?: number | null }): Promise<ApiResponse<LayoutRuangan>> {
        const response = await api.put<ApiResponse<LayoutRuangan>>(`/admin/layout/${id}`, data);
        return response.data;
    },

    async deleteLayout(id: number | string): Promise<ApiResponse<void>> {
        const response = await api.delete<ApiResponse<void>>(`/admin/layout/${id}`);
        return response.data;
    },

    // Master Hari Libur
    async getHariLiburList(params?: { tahun?: number | string; kategori?: string; page?: number; per_page?: number }): Promise<ApiResponse<{ items: PaginatedData<HariLibur>; available_years: number[] }>> {
        const response = await api.get<ApiResponse<{ items: PaginatedData<HariLibur>; available_years: number[] }>>('/admin/hari-libur', { params });
        return response.data;
    },

    async createHariLibur(data: { tanggal: string; keterangan: string; kategori: string }): Promise<ApiResponse<HariLibur>> {
        const response = await api.post<ApiResponse<HariLibur>>('/admin/hari-libur', data);
        return response.data;
    },

    async deleteHariLibur(id: number | string): Promise<ApiResponse<void>> {
        const response = await api.delete<ApiResponse<void>>(`/admin/hari-libur/${id}`);
        return response.data;
    },

    async syncHariLibur(tahun?: number | string): Promise<ApiResponse<any>> {
        const response = await api.post<ApiResponse<any>>('/admin/hari-libur/sync', { tahun });
        return response.data;
    },

    // User Management
    async getUserList(params?: { q?: string; page?: number; per_page?: number }): Promise<ApiResponse<{ admins: User[]; users: PaginatedData<User>; departments: Department[] }>> {
        const response = await api.get<ApiResponse<{ admins: User[]; users: PaginatedData<User>; departments: Department[] }>>('/admin/users', { params });
        return response.data;
    },

    async getUserDetail(id: number | string): Promise<ApiResponse<User>> {
        const response = await api.get<ApiResponse<User>>(`/admin/users/${id}`);
        return response.data;
    },

    async createUser(data: any): Promise<ApiResponse<User>> {
        const response = await api.post<ApiResponse<User>>('/admin/users', data);
        return response.data;
    },

    async updateUser(id: number | string, data: any): Promise<ApiResponse<User>> {
        const response = await api.put<ApiResponse<User>>(`/admin/users/${id}`, data);
        return response.data;
    },

    async deleteUser(id: number | string): Promise<ApiResponse<void>> {
        const response = await api.delete<ApiResponse<void>>(`/admin/users/${id}`);
        return response.data;
    },

    // Laporan & Rekapitulasi
    async getLaporanData(params?: any): Promise<ApiResponse<any>> {
        const response = await api.get<ApiResponse<any>>('/admin/laporan', { params });
        return response.data;
    },

    getExcelExportUrl(params?: any): string {
        const query = new URLSearchParams(params || {}).toString();
        return `/api/admin/laporan/export-excel?${query}`;
    },

    // Audit Log
    async getAuditLogs(params?: { tanggal_mulai?: string; tanggal_selesai?: string; modul?: string; q?: string; page?: number; per_page?: number }): Promise<ApiResponse<PaginatedData<AuditLog>>> {
        const response = await api.get<ApiResponse<PaginatedData<AuditLog>>>('/admin/audit-log', { params });
        return response.data;
    },
};
