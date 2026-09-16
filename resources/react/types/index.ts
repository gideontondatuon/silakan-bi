export type UserRole = 'admin' | 'user';

export type BookingStatus = 'Pending' | 'Disetujui' | 'Ditolak' | 'Cancel' | 'Selesai';

export interface Department {
    id: number;
    nama_department?: string;
    kode_department?: string;
    nama_unit?: string;
    kode_unit?: string;
    created_at?: string;
    updated_at?: string;
}

export interface User {
    id: number;
    name: string | null;
    username: string;
    email: string | null;
    nama_unit: string;
    kode_unit?: string | null;
    no_wa: string | null;
    role: UserRole;
    department_id?: number | null;
    department?: Department | null;
    password_plain?: string | null;
    initials?: string;
    avatar_style?: string;
    created_at?: string;
    updated_at?: string;
}

export interface LayoutRuangan {
    id: number;
    nama_layout: string;
    ruangan_id?: number | null;
    kapasitas_layout?: number | null;
    gambar_layout?: string | null;
    ruangan?: Ruangan | null;
    ruangans?: Ruangan[];
    created_at?: string;
    updated_at?: string;
}

export interface Ruangan {
    id: number;
    nama_ruangan: string;
    kapasitas: number;
    lokasi: string;
    keterangan?: string | null;
    status: 'aktif' | 'nonaktif' | 'perawatan';
    layouts?: LayoutRuangan[];
    created_at?: string;
    updated_at?: string;
}

export interface StatusHistory {
    id: number;
    pemesanan_id: number;
    status: BookingStatus;
    user_id: number;
    catatan?: string | null;
    user?: User;
    created_at: string;
}

export interface Pemesanan {
    id: number;
    kode_pemesanan: string;
    user_id: number;
    ruangan_id: number;
    layout_ruangan_id?: number | null;
    tanggal_kegiatan: string;
    waktu_mulai: string;
    waktu_selesai: string;
    judul_kegiatan: string;
    jenis_kegiatan?: 'Internal' | 'Eksternal';
    pic_kegiatan: string;
    no_wa_pic?: string | null;
    jenis_pic: 'Organik' | 'Non Organik';
    jumlah_tamu: number;
    keterangan_layout?: string | null;
    catatan_user?: string | null;
    catatan_admin?: string | null;
    file_disposisi?: string | null;
    status: BookingStatus;
    is_finished?: boolean;
    approved_by?: number | null;
    approved_at?: string | null;
    rejected_by?: number | null;
    rejected_at?: string | null;
    alasan_penolakan?: string | null;
    cancelled_by?: number | null;
    cancelled_at?: string | null;
    alasan_pembatalan?: string | null;
    user?: User;
    users?: User;
    ruangan?: Ruangan;
    layout?: LayoutRuangan;
    approver?: User;
    rejector?: User;
    canceller?: User;
    history?: StatusHistory[];
    created_at: string;
    updated_at: string;
}

export interface HariLibur {
    id: number;
    tanggal: string;
    keterangan: string;
    kategori: 'libur_nasional' | 'cuti_bersama' | 'internal';
    kategori_label?: string;
    is_nasional: boolean;
    created_at?: string;
    updated_at?: string;
}

export interface NotificationItem {
    id: string;
    judul?: string;
    pesan?: string;
    waktu?: string;
    pemesanan_id?: number | null;
    url?: string;
    read_at?: string | null;
    created_at: string;
    data?: {
        judul?: string;
        pesan?: string;
        waktu?: string;
        pemesanan_id?: number | null;
        url?: string;
    };
}

export interface AuditLog {
    id: number;
    user_id?: number | null;
    aksi?: string;
    activity?: string;
    aktivitas?: string;
    modul?: string | null;
    keterangan?: string;
    description?: string;
    deskripsi?: string;
    ip_address?: string | null;
    user_agent?: string | null;
    user?: User | null;
    created_at: string;
}

export interface PaginatedData<T> {
    current_page: number;
    data: T[];
    first_page_url?: string;
    from: number | null;
    last_page: number;
    last_page_url?: string;
    next_page_url?: string | null;
    path?: string;
    per_page: number;
    prev_page_url?: string | null;
    to: number | null;
    total: number;
}

export interface ApiResponse<T = any> {
    status: 'success' | 'error';
    message?: string;
    data: T;
}
