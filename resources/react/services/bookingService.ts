import api from './api';
import { ApiResponse, LayoutRuangan, PaginatedData, Pemesanan, Ruangan } from '../types';

export interface ConflictCheckResult {
    status: string;
    conflict: boolean;
    count: number;
    conflicts: Array<{
        id: number;
        kode_pemesanan: string;
        judul_kegiatan: string;
        unit: string;
        pic: string;
        waktu_mulai: string;
        waktu_selesai: string;
        status: string;
    }>;
    message: string;
}

export interface UserDashboardData {
    stats: {
        total: number;
        pending: number;
        approved: number;
        upcoming: number;
    };
    pemesanan_terbaru: Pemesanan[];
    kegiatan_hari_ini: Pemesanan[];
    kegiatan_berlangsung: Pemesanan[];
}

export interface KalenderIndexData {
    ruangan: Ruangan[];
    stats: {
        total_ruangan: number;
        jadwal_aktif: number;
        akan_datang: number;
    };
    upcoming: Array<{
        id: number;
        kode_pemesanan: string;
        judul_kegiatan: string;
        tanggal_kegiatan: string;
        waktu_mulai: string;
        waktu_selesai: string;
        pic_kegiatan: string;
        nama_ruangan: string;
        nama_unit: string;
    }>;
}

export const bookingService = {
    async getKalenderIndex(): Promise<ApiResponse<KalenderIndexData>> {
        const response = await api.get<ApiResponse<KalenderIndexData>>('/kalender');
        return response.data;
    },

    async getUserDashboard(): Promise<ApiResponse<UserDashboardData>> {
        const response = await api.get<ApiResponse<UserDashboardData>>('/dashboard/user');
        return response.data;
    },

    async getKegiatanBerlangsung(): Promise<ApiResponse<Pemesanan[]>> {
        const response = await api.get<ApiResponse<Pemesanan[]>>('/kegiatan-berlangsung');
        return response.data;
    },

    async getPemesananList(params?: { page?: number; per_page?: number; status?: string; q?: string }): Promise<ApiResponse<PaginatedData<Pemesanan>>> {
        const response = await api.get<ApiResponse<PaginatedData<Pemesanan>>>('/pemesanan', { params });
        return response.data;
    },

    async getPemesananDetail(id: number | string): Promise<ApiResponse<Pemesanan>> {
        const response = await api.get<ApiResponse<Pemesanan>>(`/pemesanan/${id}`);
        return response.data;
    },

    async createPemesanan(formData: FormData): Promise<ApiResponse<Pemesanan>> {
        const response = await api.post<ApiResponse<Pemesanan>>('/pemesanan', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        return response.data;
    },

    async cancelPemesanan(id: number | string): Promise<ApiResponse<Pemesanan>> {
        const response = await api.post<ApiResponse<Pemesanan>>(`/pemesanan/${id}/cancel`);
        return response.data;
    },

    async selesaiAwal(id: number | string): Promise<ApiResponse<Pemesanan>> {
        const response = await api.post<ApiResponse<Pemesanan>>(`/pemesanan/${id}/selesai-awal`);
        return response.data;
    },

    async checkConflict(params: {
        ruangan_id: number | string;
        tanggal_kegiatan: string;
        waktu_mulai: string;
        waktu_selesai: string;
        exclude_id?: number;
    }): Promise<ConflictCheckResult> {
        const response = await api.get<ConflictCheckResult>('/pemesanan/check-conflict', { params });
        return response.data;
    },

    async getRuanganList(onlyActive: boolean = true): Promise<ApiResponse<Ruangan[]>> {
        const response = await api.get<ApiResponse<Ruangan[]>>('/ruangan', {
            params: { only_active: onlyActive ? 1 : 0 },
        });
        return response.data;
    },

    async getLayoutsByRuangan(ruanganId: number | string): Promise<LayoutRuangan[]> {
        const response = await api.get<LayoutRuangan[]>(`/ruangan/${ruanganId}/layouts`);
        return response.data;
    },

    async getKalenderEvents(ruanganId?: number | string): Promise<any[]> {
        const response = await api.get<any[]>('/kalender/events', {
            params: ruanganId ? { ruangan_id: ruanganId } : {},
        });
        return response.data;
    },

    async getDisplayKioskData(): Promise<any> {
        const response = await api.get('/display-data');
        return response.data;
    },
};
