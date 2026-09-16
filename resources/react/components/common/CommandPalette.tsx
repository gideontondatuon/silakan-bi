import React, { useEffect, useRef, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';

interface CommandPaletteProps {
    isOpen: boolean;
    onClose: () => void;
}

interface CommandItem {
    id: string;
    title: string;
    subtitle?: string;
    icon: string;
    group: 'Navigasi' | 'Aksi Cepat' | 'Ruangan';
    action: () => void;
    adminOnly?: boolean;
}

export const CommandPalette: React.FC<CommandPaletteProps> = ({ isOpen, onClose }) => {
    const { role } = useAuth();
    const navigate = useNavigate();
    const [query, setQuery] = useState('');
    const [selectedIndex, setSelectedIndex] = useState(0);
    const inputRef = useRef<HTMLInputElement>(null);

    const isAdmin = role === 'admin';

    const items: CommandItem[] = [
        {
            id: 'nav-dashboard',
            title: isAdmin ? 'Dashboard Admin' : 'Dashboard Pegawai',
            subtitle: 'Halaman ringkasan utama sistem',
            icon: 'bi-grid-fill',
            group: 'Navigasi',
            action: () => navigate(isAdmin ? '/admin/dashboard' : '/dashboard'),
        },
        {
            id: 'nav-booking-create',
            title: 'Buat Pemesanan Rapat Baru',
            subtitle: 'Form reservasi ruangan rapat & fasilitas',
            icon: 'bi-calendar-plus-fill',
            group: 'Aksi Cepat',
            action: () => navigate('/pemesanan/create'),
        },
        {
            id: 'nav-kalender',
            title: 'Kalender Ruangan',
            subtitle: 'Visual jadwal rapat bulanan & mingguan',
            icon: 'bi-calendar-week-fill',
            group: 'Navigasi',
            action: () => navigate('/kalender'),
        },
        {
            id: 'nav-live',
            title: 'Kegiatan Berlangsung (Live)',
            subtitle: 'Monitor ruangan yang sedang digunakan saat ini',
            icon: 'bi-broadcast-pin',
            group: 'Navigasi',
            action: () => navigate('/kegiatan-berlangsung'),
        },
        {
            id: 'nav-approval',
            title: 'Persetujuan Pemesanan Ruangan',
            subtitle: 'Verifikasi dan proses reservasi masuk',
            icon: 'bi-check2-circle',
            group: 'Navigasi',
            action: () => navigate('/admin/approval'),
            adminOnly: true,
        },
        {
            id: 'nav-ruangan',
            title: 'Data & Manajemen Ruangan',
            subtitle: 'Daftar ruangan, kapasitas, dan fasilitas',
            icon: 'bi-building-fill',
            group: 'Navigasi',
            action: () => navigate('/admin/ruangan'),
            adminOnly: true,
        },
        {
            id: 'nav-layout',
            title: 'Layout & Tata Letak Ruang',
            subtitle: 'Pengaturan denah format meja/kursi',
            icon: 'bi-grid-3x3-gap-fill',
            group: 'Navigasi',
            action: () => navigate('/admin/layout'),
            adminOnly: true,
        },
        {
            id: 'nav-laporan',
            title: 'Laporan & Rekap Eksekutif',
            subtitle: 'Statistik, analisis utilitas, ekspor PDF/PPT',
            icon: 'bi-file-earmark-bar-graph-fill',
            group: 'Navigasi',
            action: () => navigate('/admin/laporan'),
            adminOnly: true,
        },
        {
            id: 'nav-audit',
            title: 'Audit Log & Rekam Jejak',
            subtitle: 'Histori aktivitas dan perubahan data sistem',
            icon: 'bi-shield-check',
            group: 'Navigasi',
            action: () => navigate('/admin/audit-log'),
            adminOnly: true,
        },
        {
            id: 'nav-kiosk-internal',
            title: 'Buka Display Rapat Internal (Kiosk TV)',
            subtitle: 'Layar signage khusus agenda rapat internal Kantor Perwakilan (/display/internal)',
            icon: 'bi-display',
            group: 'Aksi Cepat',
            action: () => window.open('/display/internal', '_blank'),
        },
        {
            id: 'nav-kiosk-eksternal',
            title: 'Buka Display Rapat Eksternal (Kiosk TV)',
            subtitle: 'Layar signage khusus agenda tamu & stakeholder eksternal (/display/eksternal)',
            icon: 'bi-tv-fill',
            group: 'Aksi Cepat',
            action: () => window.open('/display/eksternal', '_blank'),
        },
        {
            id: 'nav-profile',
            title: 'Profil & Pengaturan Akun',
            subtitle: 'Informasi pengguna dan kata sandi',
            icon: 'bi-person-circle',
            group: 'Navigasi',
            action: () => navigate('/profile'),
        },
    ];

    // Filter based on role and search query
    const filteredItems = items.filter((item) => {
        if (item.adminOnly && !isAdmin) return false;
        if (!query.trim()) return true;
        const q = query.toLowerCase();
        return (
            item.title.toLowerCase().includes(q) ||
            (item.subtitle && item.subtitle.toLowerCase().includes(q)) ||
            item.group.toLowerCase().includes(q)
        );
    });

    useEffect(() => {
        if (isOpen) {
            setQuery('');
            setSelectedIndex(0);
            setTimeout(() => inputRef.current?.focus(), 50);
        }
    }, [isOpen]);

    useEffect(() => {
        setSelectedIndex(0);
    }, [query]);

    // Keyboard navigation
    useEffect(() => {
        if (!isOpen) return;

        const handleKeyDown = (e: KeyboardEvent) => {
            if (e.key === 'Escape') {
                e.preventDefault();
                onClose();
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                setSelectedIndex((prev) => (prev < filteredItems.length - 1 ? prev + 1 : 0));
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                setSelectedIndex((prev) => (prev > 0 ? prev - 1 : filteredItems.length - 1));
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (filteredItems[selectedIndex]) {
                    filteredItems[selectedIndex].action();
                    onClose();
                }
            }
        };

        window.addEventListener('keydown', handleKeyDown);
        return () => window.removeEventListener('keydown', handleKeyDown);
    }, [isOpen, filteredItems, selectedIndex, onClose]);

    if (!isOpen) return null;

    return (
        <div className="cmd-palette-overlay" onClick={onClose}>
            <div className="cmd-palette-box" onClick={(e) => e.stopPropagation()}>
                <div className="cmd-palette-header">
                    <i className="bi bi-search" style={{ color: '#005baa', fontSize: '18px' }}></i>
                    <input
                        ref={inputRef}
                        type="text"
                        className="cmd-palette-input"
                        placeholder="Ketik menu, nama ruangan, atau perintah cepat... (Esc untuk tutup)"
                        value={query}
                        onChange={(e) => setQuery(e.target.value)}
                    />
                    {query && (
                        <button
                            type="button"
                            onClick={() => setQuery('')}
                            style={{ background: 'none', border: 'none', cursor: 'pointer', color: '#94a3b8' }}
                        >
                            <i className="bi bi-x-circle-fill"></i>
                        </button>
                    )}
                </div>

                <div className="cmd-palette-body">
                    {filteredItems.length === 0 ? (
                        <div style={{ padding: '30px 20px', textAlign: 'center', color: '#94a3b8' }}>
                            <i className="bi bi-search" style={{ fontSize: '28px', display: 'block', marginBottom: '8px' }}></i>
                            <span style={{ fontSize: '13.5px' }}>Tidak ditemukan perintah untuk "{query}"</span>
                        </div>
                    ) : (
                        filteredItems.map((item, index) => {
                            const isSelected = index === selectedIndex;
                            return (
                                <div
                                    key={item.id}
                                    className={`cmd-palette-item ${isSelected ? 'active' : ''}`}
                                    onClick={() => {
                                        item.action();
                                        onClose();
                                    }}
                                    onMouseEnter={() => setSelectedIndex(index)}
                                >
                                    <div className="cmd-item-left">
                                        <div className="cmd-item-icon">
                                            <i className={`bi ${item.icon}`}></i>
                                        </div>
                                        <div>
                                            <div style={{ fontWeight: 600, fontSize: '13.5px' }}>{item.title}</div>
                                            {item.subtitle && (
                                                <div style={{ fontSize: '11.5px', color: isSelected ? '#005baa' : '#64748b' }}>
                                                    {item.subtitle}
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                    <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                                        <span
                                            style={{
                                                fontSize: '10.5px',
                                                padding: '2px 8px',
                                                borderRadius: '6px',
                                                background: isSelected ? '#e0effe' : '#f1f5f9',
                                                color: isSelected ? '#0369a1' : '#64748b',
                                                fontWeight: 600,
                                            }}
                                        >
                                            {item.group}
                                        </span>
                                        {isSelected && (
                                            <span style={{ fontSize: '11px', color: '#005baa' }}>
                                                <i className="bi bi-arrow-return-left"></i>
                                            </span>
                                        )}
                                    </div>
                                </div>
                            );
                        })
                    )}
                </div>

                <div className="cmd-palette-footer">
                    <div className="cmd-palette-footer-keys">
                        <span className="cmd-key-hint">
                            <kbd>&uarr;</kbd> <kbd>&darr;</kbd> Navigasi
                        </span>
                        <span className="cmd-key-hint">
                            <kbd>&crarr;</kbd> Pilih
                        </span>
                        <span className="cmd-key-hint">
                            <kbd>Esc</kbd> Tutup
                        </span>
                    </div>
                    <span style={{ fontWeight: 600, color: '#005baa' }}>SILAKAN &bull; Kantor Perwakilan</span>
                </div>
            </div>
        </div>
    );
};
