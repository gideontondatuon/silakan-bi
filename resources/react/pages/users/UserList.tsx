import React, { useEffect, useState } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import { adminService } from '../../services/adminService';
import { PaginatedData, User } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const UserList: React.FC = () => {
    const { user: currentUser } = useAuth();
    const location = useLocation();

    const [admins, setAdmins] = useState<User[]>([]);
    const [users, setUsers] = useState<PaginatedData<User> | null>(null);
    const [isLoading, setIsLoading] = useState(true);
    const [currentPage, setCurrentPage] = useState(1);
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    // Password visibility & copy state
    const [revealedPasswords, setRevealedPasswords] = useState<Record<number, boolean>>({});
    const [copiedUserId, setCopiedUserId] = useState<number | null>(null);

    // Delete modal state
    const [deleteTarget, setDeleteTarget] = useState<User | null>(null);
    const [isDeleting, setIsDeleting] = useState(false);

    // Check flash message from navigation
    useEffect(() => {
        if (location.state && (location.state as any).flashMessage) {
            setAlertMessage({
                type: 'success',
                text: (location.state as any).flashMessage,
            });
            window.history.replaceState({}, document.title);
        }
    }, [location.state]);

    const loadData = async (pageToLoad = currentPage) => {
        setIsLoading(true);
        try {
            const res = await adminService.getUserList({ page: pageToLoad });
            if (res.status === 'success' && res.data) {
                if (res.data.users) {
                    setAdmins(res.data.admins || []);
                    setUsers(res.data.users);
                } else if ((res.data as any).data) {
                    const allUsers: User[] = (res.data as any).data || [];
                    const adminList = allUsers.filter(u => u.role === 'admin');
                    const regularUsers = allUsers.filter(u => u.role !== 'admin');
                    setAdmins(adminList);
                    setUsers({
                        ...(res.data as any),
                        data: regularUsers,
                        total: regularUsers.length,
                    });
                }
            }
        } catch (err: any) {
            setAlertMessage({
                type: 'error',
                text: err.response?.data?.message || 'Gagal memuat data pengguna.',
            });
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        loadData(currentPage);
    }, [currentPage]);

    const getUserInitials = (u: User): string => {
        if (u.initials) return u.initials;
        if (u.kode_unit) return u.kode_unit.toUpperCase();
        const sourceName = u.nama_unit || u.name || u.username || '';
        const words = sourceName.trim().split(/\s+/);
        if (words.length >= 2) {
            let inits = '';
            for (const w of words) {
                if (w) inits += w.charAt(0);
            }
            return inits.substring(0, 5).toUpperCase();
        }
        return sourceName.substring(0, 2).toUpperCase();
    };

    const getAvatarFontSize = (initials: string): React.CSSProperties => {
        const len = initials.length;
        if (len >= 6) return { fontSize: '8px', letterSpacing: '-0.5px', padding: '0 2px' };
        if (len === 5) return { fontSize: '9px', letterSpacing: '-0.5px', padding: '0 2px' };
        if (len === 4) return { fontSize: '10.5px', letterSpacing: '-0.3px', padding: '0 2px' };
        if (len === 3) return { fontSize: '12px', letterSpacing: '0', padding: '0 2px' };
        return { fontSize: '14px', letterSpacing: '0', padding: '0 2px' };
    };

    const togglePassVisibility = (userId: number) => {
        setRevealedPasswords((prev) => ({
            ...prev,
            [userId]: !prev[userId],
        }));
    };

    const copyPassword = (text: string, userId: number) => {
        if (!navigator.clipboard) {
            const temp = document.createElement('input');
            temp.value = text;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
        } else {
            navigator.clipboard.writeText(text);
        }

        setCopiedUserId(userId);
        setTimeout(() => {
            setCopiedUserId((prev) => (prev === userId ? null : prev));
        }, 1500);
    };

    const handleDelete = async () => {
        if (!deleteTarget) return;
        setIsDeleting(true);
        try {
            const res = await adminService.deleteUser(deleteTarget.id);
            setAlertMessage({ type: 'success', text: res.message || 'User berhasil dihapus.' });
            setDeleteTarget(null);
            loadData(currentPage);
        } catch (err: any) {
            setAlertMessage({
                type: 'error',
                text: err.response?.data?.message || 'Gagal menghapus user.',
            });
        } finally {
            setIsDeleting(false);
        }
    };

    return (
        <div>
            {/* Dashboard Header */}
            <div className="dashboard-header">
                <div>
                    <h1>
                        <i className="bi bi-people" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Data User &amp; Akun Sistem
                    </h1>
                    <p>Manajemen akun Administrator dan akun unit kerja sistem SILAKAN.</p>
                </div>
                <Link to="/admin/users/create" className="btn-primary">
                    <i className="bi bi-person-plus"></i> Tambah User Baru
                </Link>
            </div>

            {alertMessage && (
                <AlertBanner
                    type={alertMessage.type}
                    message={alertMessage.text}
                    onClose={() => setAlertMessage(null)}
                />
            )}

            {isLoading && !users ? (
                <div style={{ padding: '60px', textAlign: 'center' }}>
                    <LoadingSpinner />
                </div>
            ) : (
                <div style={{ display: 'flex', flexDirection: 'column', gap: '24px' }}>
                    {/* KOTAK 1: AKUN ADMINISTRATOR */}
                    <div
                        className="dashboard-section"
                        style={{
                            margin: 0,
                            background: '#ffffff',
                            borderRadius: '16px',
                            border: '1px solid #cbd5e1',
                            boxShadow: '0 4px 20px rgba(0,0,0,0.04)',
                            overflow: 'hidden',
                        }}
                    >
                        <div
                            className="section-header"
                            style={{
                                padding: '16px 24px',
                                background: 'linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%)',
                                borderBottom: '1.5px solid #e2e8f0',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'space-between',
                                flexWrap: 'wrap',
                                gap: '10px',
                            }}
                        >
                            <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                                <div
                                    style={{
                                        width: '36px',
                                        height: '36px',
                                        borderRadius: '10px',
                                        background: 'linear-gradient(135deg,#003b73,#005baa)',
                                        display: 'flex',
                                        alignItems: 'center',
                                        justifyContent: 'center',
                                        color: 'white',
                                    }}
                                >
                                    <i className="bi bi-shield-lock-fill" style={{ fontSize: '17px' }}></i>
                                </div>
                                <div>
                                    <h2 style={{ fontSize: '16px', fontWeight: 800, color: '#0f172a', margin: 0, lineHeight: 1.2 }}>
                                        Akun Administrator
                                    </h2>
                                    <p style={{ margin: '2px 0 0 0', fontSize: '12px', color: '#64748b' }}>
                                        Pengelola sistem dengan hak akses penuh, tidak dapat dihapus demi keamanan operasional.
                                    </p>
                                </div>
                            </div>
                            <span
                                className="badge"
                                style={{
                                    background: '#e0f2fe',
                                    color: '#0369a1',
                                    border: '1px solid #bae6fd',
                                    padding: '6px 14px',
                                    borderRadius: '9999px',
                                    fontWeight: 700,
                                    fontSize: '12px',
                                    display: 'inline-flex',
                                    alignItems: 'center',
                                    gap: '6px',
                                }}
                            >
                                <i className="bi bi-shield-check"></i> Hak Akses Penuh
                            </span>
                        </div>

                        <div style={{ padding: '20px 24px' }}>
                            <div
                                style={{
                                    display: 'grid',
                                    gridTemplateColumns: 'repeat(auto-fill, minmax(min(100%, 280px), 1fr))',
                                    gap: '16px',
                                }}
                            >
                                {admins.length > 0 ? (
                                    admins.map((admin) => {
                                        const inits = getUserInitials(admin);
                                        const avStyle = getAvatarFontSize(inits);
                                        const isMe = currentUser?.id === admin.id;

                                        return (
                                            <div
                                                key={admin.id}
                                                style={{
                                                    background: '#ffffff',
                                                    border: '1.5px solid #e2e8f0',
                                                    borderRadius: '14px',
                                                    padding: '18px 20px',
                                                    display: 'flex',
                                                    flexDirection: 'column',
                                                    justifyContent: 'space-between',
                                                    gap: '14px',
                                                    boxShadow: '0 2px 8px rgba(0,0,0,0.02)',
                                                    transition: 'all .2s',
                                                }}
                                                onMouseEnter={(e) => {
                                                    e.currentTarget.style.borderColor = '#005baa';
                                                    e.currentTarget.style.boxShadow = '0 6px 16px rgba(0,91,170,0.08)';
                                                }}
                                                onMouseLeave={(e) => {
                                                    e.currentTarget.style.borderColor = '#e2e8f0';
                                                    e.currentTarget.style.boxShadow = '0 2px 8px rgba(0,0,0,0.02)';
                                                }}
                                            >
                                                <div style={{ display: 'flex', alignItems: 'flex-start', gap: '14px' }}>
                                                    <div
                                                        style={{
                                                            width: '48px',
                                                            height: '48px',
                                                            borderRadius: '50%',
                                                            background: 'linear-gradient(135deg,#005baa,#003b73)',
                                                            color: 'white',
                                                            display: 'flex',
                                                            alignItems: 'center',
                                                            justifyContent: 'center',
                                                            fontWeight: 800,
                                                            fontSize: '17px',
                                                            flexShrink: 0,
                                                            boxShadow: '0 3px 8px rgba(0,91,170,0.25)',
                                                            ...avStyle,
                                                        }}
                                                    >
                                                        {inits}
                                                    </div>
                                                    <div style={{ flex: 1, minWidth: 0 }}>
                                                        <div style={{ display: 'flex', alignItems: 'center', gap: '6px', flexWrap: 'wrap' }}>
                                                            <h3 style={{ margin: 0, fontSize: '15px', fontWeight: 800, color: '#0f172a', lineHeight: 1.2 }}>
                                                                {admin.name || 'Administrator Utama'}
                                                            </h3>
                                                            {isMe && (
                                                                <span
                                                                    style={{
                                                                        fontSize: '10.5px',
                                                                        background: '#e0f2fe',
                                                                        color: '#0284c7',
                                                                        fontWeight: 700,
                                                                        padding: '2px 7px',
                                                                        borderRadius: '6px',
                                                                        border: '1px solid #bae6fd',
                                                                    }}
                                                                >
                                                                    Akun Anda
                                                                </span>
                                                            )}
                                                        </div>
                                                        <div style={{ marginTop: '4px', display: 'flex', alignItems: 'center', gap: '8px', flexWrap: 'wrap' }}>
                                                            <code
                                                                style={{
                                                                    background: '#f1f5f9',
                                                                    color: '#005baa',
                                                                    padding: '2px 8px',
                                                                    borderRadius: '6px',
                                                                    fontFamily: 'Consolas, monospace',
                                                                    fontSize: '12.5px',
                                                                    fontWeight: 700,
                                                                }}
                                                            >
                                                                {admin.username}
                                                            </code>
                                                            <span style={{ fontSize: '11.5px', color: '#64748b' }}>
                                                                <i className="bi bi-building"></i> {admin.nama_unit || 'Administrator Sarpras'}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div
                                                    style={{
                                                        display: 'flex',
                                                        alignItems: 'center',
                                                        justifyContent: 'space-between',
                                                        paddingTop: '12px',
                                                        borderTop: '1px solid #f1f5f9',
                                                        flexWrap: 'wrap',
                                                        gap: '8px',
                                                    }}
                                                >
                                                    <span
                                                        className="badge"
                                                        style={{
                                                            background: '#f8fafc',
                                                            color: '#64748b',
                                                            border: '1px solid #e2e8f0',
                                                            fontSize: '11.5px',
                                                            padding: '5px 10px',
                                                            borderRadius: '7px',
                                                            display: 'inline-flex',
                                                            alignItems: 'center',
                                                            gap: '5px',
                                                        }}
                                                    >
                                                        <i className="bi bi-shield-lock-fill" style={{ color: '#005baa' }}></i> Dilindungi (Tidak Dapat Dihapus)
                                                    </span>
                                                    <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                                                        <Link
                                                            to={`/admin/users/${admin.id}/edit`}
                                                            className="btn-primary btn-sm"
                                                            style={{ padding: '6px 12px', fontSize: '12px', borderRadius: '8px' }}
                                                            title="Ubah Nama & Password Admin"
                                                        >
                                                            <i className="bi bi-pencil-square"></i> Ubah Nama &amp; Password
                                                        </Link>
                                                        {isMe && (
                                                            <Link
                                                                to="/profile"
                                                                className="btn-secondary btn-sm"
                                                                style={{ padding: '6px 12px', fontSize: '12px', borderRadius: '8px' }}
                                                                title="Buka Profil Saya"
                                                            >
                                                                <i className="bi bi-person-gear"></i> Profil
                                                            </Link>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        );
                                    })
                                ) : (
                                    <div style={{ padding: '20px', textAlign: 'center', color: '#64748b', fontSize: '13px' }}>
                                        Belum ada akun admin yang terdaftar.
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* KOTAK 2: DAFTAR USER BIASA (UNIT KERJA) */}
                    <div
                        className="dashboard-section"
                        style={{
                            margin: 0,
                            background: '#ffffff',
                            borderRadius: '16px',
                            border: '1px solid #cbd5e1',
                            boxShadow: '0 4px 20px rgba(0,0,0,0.04)',
                            overflow: 'hidden',
                        }}
                    >
                        <div
                            className="section-header"
                            style={{
                                padding: '16px 24px',
                                background: '#f8fafc',
                                borderBottom: '1.5px solid #e2e8f0',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'space-between',
                                flexWrap: 'wrap',
                                gap: '10px',
                            }}
                        >
                            <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                                <div
                                    style={{
                                        width: '36px',
                                        height: '36px',
                                        borderRadius: '10px',
                                        background: 'linear-gradient(135deg,#e0f2fe,#bae6fd)',
                                        display: 'flex',
                                        alignItems: 'center',
                                        justifyContent: 'center',
                                        color: '#0284c7',
                                    }}
                                >
                                    <i className="bi bi-people-fill" style={{ fontSize: '17px' }}></i>
                                </div>
                                <div>
                                    <h2 style={{ fontSize: '16px', fontWeight: 800, color: '#0f172a', margin: 0, lineHeight: 1.2 }}>
                                        Daftar Akun Unit Kerja (Pengguna Biasa)
                                    </h2>
                                    <p style={{ margin: '2px 0 0 0', fontSize: '12px', color: '#64748b' }}>
                                        Daftar akun pemohon ruangan per unit kerja. Admin dapat melihat password akun unit kerja.
                                    </p>
                                </div>
                            </div>
                            <span
                                style={{
                                    background: '#ffffff',
                                    color: '#0f172a',
                                    border: '1px solid #cbd5e1',
                                    fontWeight: 700,
                                    padding: '5px 12px',
                                    borderRadius: '9999px',
                                    fontSize: '12px',
                                    display: 'inline-flex',
                                    alignItems: 'center',
                                    gap: '6px',
                                }}
                            >
                                <i className="bi bi-person-check-fill" style={{ color: '#005baa' }}></i> {users?.total || 0} Akun Terdaftar
                            </span>
                        </div>

                        <div className="table-wrapper" style={{ border: 'none' }}>
                            <table className="data-table">
                                <thead>
                                    <tr>
                                        <th style={{ width: '45px', textAlign: 'center' }}>#</th>
                                        <th>Username ID</th>
                                        <th>Nama Unit Kerja</th>
                                        <th>Kode Unit</th>
                                        <th>Role</th>
                                        <th>Password Akun</th>
                                        <th style={{ textAlign: 'center', width: '150px' }}>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {users?.data && users.data.length > 0 ? (
                                        users.data.map((u, index) => {
                                            const inits = getUserInitials(u);
                                            const avStyle = getAvatarFontSize(inits);
                                            const roleVal = typeof u.role === 'object' && u.role ? (u.role as any).value : u.role;
                                            const defaultPass = u.username === 'admin' || roleVal === 'admin' ? 'password' : 'kpwbisulut';
                                            const displayPassword = u.password_plain || defaultPass;
                                            const isRevealed = !!revealedPasswords[u.id];
                                            const isCopied = copiedUserId === u.id;
                                            const rowNum = (users.from || 1) + index;

                                            return (
                                                <tr key={u.id}>
                                                    <td style={{ color: '#94a3b8', fontSize: '12px', textAlign: 'center' }}>
                                                        {rowNum}
                                                    </td>
                                                    <td>
                                                        <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                                                            <div
                                                                style={{
                                                                    width: '34px',
                                                                    height: '34px',
                                                                    borderRadius: '50%',
                                                                    background: 'linear-gradient(135deg,#005baa,#003b73)',
                                                                    display: 'flex',
                                                                    alignItems: 'center',
                                                                    justifyContent: 'center',
                                                                    color: 'white',
                                                                    fontWeight: 700,
                                                                    fontSize: '12px',
                                                                    flexShrink: 0,
                                                                    ...avStyle,
                                                                }}
                                                            >
                                                                {inits}
                                                            </div>
                                                            <div>
                                                                <code style={{ fontWeight: 700, color: '#003b73', fontFamily: 'Consolas, monospace', fontSize: '13px' }}>
                                                                    {u.username}
                                                                </code>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <strong style={{ color: '#1e293b', display: 'block' }}>
                                                            {u.nama_unit || u.name || '-'}
                                                        </strong>
                                                    </td>
                                                    <td>
                                                        {u.kode_unit ? (
                                                            <span style={{ background: '#f1f5f9', color: '#334155', border: '1px solid #cbd5e1', padding: '2px 8px', borderRadius: '6px', fontSize: '11.5px', fontWeight: 700 }}>
                                                                {u.kode_unit}
                                                            </span>
                                                        ) : (
                                                            <span style={{ color: '#94a3b8' }}>—</span>
                                                        )}
                                                    </td>
                                                    <td>
                                                        <span className="badge badge-secondary" style={{ fontSize: '11.5px', padding: '4px 9px' }}>
                                                            <i className="bi bi-person"></i> User
                                                        </span>
                                                    </td>
                                                    {/* Password Viewer Column */}
                                                    <td>
                                                        <div style={{ display: 'inline-flex', alignItems: 'center', gap: '6px', background: '#f8fafc', border: '1px solid #cbd5e1', padding: '4px 10px', borderRadius: '8px' }}>
                                                            <span style={{ fontFamily: 'Consolas, monospace', fontWeight: 700, letterSpacing: '1px', fontSize: '13px', color: '#1e293b' }}>
                                                                {isRevealed ? displayPassword : '••••••••'}
                                                            </span>
                                                            <button
                                                                type="button"
                                                                onClick={() => togglePassVisibility(u.id)}
                                                                title="Lihat / Sembunyikan Password"
                                                                style={{
                                                                    background: 'none',
                                                                    border: 'none',
                                                                    cursor: 'pointer',
                                                                    color: isRevealed ? '#dc2626' : '#005baa',
                                                                    padding: '2px 4px',
                                                                    borderRadius: '4px',
                                                                    display: 'inline-flex',
                                                                    alignItems: 'center',
                                                                    fontSize: '14px',
                                                                }}
                                                            >
                                                                <i className={`bi ${isRevealed ? 'bi-eye-slash' : 'bi-eye'}`}></i>
                                                            </button>
                                                            <button
                                                                type="button"
                                                                onClick={() => copyPassword(displayPassword, u.id)}
                                                                title={isCopied ? 'Tersalin!' : 'Salin Password'}
                                                                style={{
                                                                    background: 'none',
                                                                    border: 'none',
                                                                    cursor: 'pointer',
                                                                    color: isCopied ? '#059669' : '#64748b',
                                                                    padding: '2px 4px',
                                                                    borderRadius: '4px',
                                                                    display: 'inline-flex',
                                                                    alignItems: 'center',
                                                                    fontSize: '13px',
                                                                }}
                                                            >
                                                                <i className={`bi ${isCopied ? 'bi-check2' : 'bi-clipboard'}`}></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td style={{ textAlign: 'center' }}>
                                                        <div className="action-group" style={{ justifyContent: 'center' }}>
                                                            <Link
                                                                to={`/admin/users/${u.id}/edit`}
                                                                className="btn-secondary btn-sm"
                                                                title="Edit Akun User"
                                                            >
                                                                <i className="bi bi-pencil"></i> Edit
                                                            </Link>
                                                            <button
                                                                type="button"
                                                                className="btn-danger btn-sm"
                                                                title="Hapus User"
                                                                onClick={() => setDeleteTarget(u)}
                                                            >
                                                                <i className="bi bi-trash"></i> Hapus
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            );
                                        })
                                    ) : (
                                        <tr>
                                            <td colSpan={7}>
                                                <div className="empty-state" style={{ padding: '40px 20px' }}>
                                                    <i className="bi bi-people" style={{ fontSize: '36px', color: '#94a3b8' }}></i>
                                                    <p style={{ marginTop: '8px', color: '#64748b' }}>
                                                        Belum ada data user unit kerja.{' '}
                                                        <Link to="/admin/users/create" style={{ color: '#005baa', fontWeight: 700 }}>
                                                            Tambah user sekarang
                                                        </Link>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>

                        {users && users.last_page > 1 && (
                            <div
                                style={{
                                    padding: '16px 24px',
                                    borderTop: '1px solid #f1f5f9',
                                    background: '#f8fafc',
                                    display: 'flex',
                                    justifyContent: 'space-between',
                                    alignItems: 'center',
                                    flexWrap: 'wrap',
                                    gap: '12px',
                                }}
                            >
                                <div style={{ fontSize: '13px', color: '#64748b' }}>
                                    Menampilkan {users.from || 1} - {users.to || users.data.length} dari {users.total} user
                                </div>
                                <div style={{ display: 'flex', gap: '6px' }}>
                                    <button
                                        type="button"
                                        className="btn-secondary btn-sm"
                                        disabled={currentPage <= 1}
                                        onClick={() => setCurrentPage((p) => Math.max(1, p - 1))}
                                    >
                                        <i className="bi bi-chevron-left"></i> Sebelumnya
                                    </button>
                                    {Array.from({ length: users.last_page }, (_, idx) => idx + 1).map((pg) => (
                                        <button
                                            key={pg}
                                            type="button"
                                            className={`btn-sm ${pg === currentPage ? 'btn-primary' : 'btn-secondary'}`}
                                            onClick={() => setCurrentPage(pg)}
                                        >
                                            {pg}
                                        </button>
                                    ))}
                                    <button
                                        type="button"
                                        className="btn-secondary btn-sm"
                                        disabled={currentPage >= users.last_page}
                                        onClick={() => setCurrentPage((p) => Math.min(users.last_page, p + 1))}
                                    >
                                        Selanjutnya <i className="bi bi-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            )}

            {/* Custom Modal Delete User (1:1 with submitFormWithConfirm Blade) */}
            {deleteTarget && (
                <div
                    className="custom-modal-overlay"
                    style={{
                        display: 'flex',
                        position: 'fixed',
                        top: 0,
                        left: 0,
                        width: '100%',
                        height: '100%',
                        background: 'rgba(15,23,42,0.6)',
                        backdropFilter: 'blur(5px)',
                        WebkitBackdropFilter: 'blur(5px)',
                        zIndex: 99999,
                        alignItems: 'center',
                        justifyContent: 'center',
                        padding: '16px',
                    }}
                >
                    <div
                        className="custom-modal-card"
                        style={{
                            background: '#ffffff',
                            borderRadius: '16px',
                            maxWidth: '440px',
                            width: '100%',
                            boxShadow: '0 25px 50px -12px rgba(0,0,0,0.25)',
                            overflow: 'hidden',
                            border: '1px solid #e2e8f0',
                            animation: 'modalFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1)',
                        }}
                    >
                        <div style={{ padding: '24px 24px 16px', display: 'flex', alignItems: 'flex-start', gap: '16px' }}>
                            <div
                                style={{
                                    width: '48px',
                                    height: '48px',
                                    borderRadius: '12px',
                                    background: '#fee2e2',
                                    color: '#dc2626',
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    fontSize: '24px',
                                    flexShrink: 0,
                                }}
                            >
                                <i className="bi bi-exclamation-triangle"></i>
                            </div>
                            <div style={{ flex: 1 }}>
                                <h3 style={{ margin: '0 0 6px', fontSize: '18px', fontWeight: 700, color: '#0f172a' }}>
                                    Hapus User
                                </h3>
                                <p style={{ margin: 0, fontSize: '14px', color: '#64748b', lineHeight: 1.5 }}>
                                    Apakah Anda yakin ingin menghapus user{' '}
                                    <strong style={{ color: '#0f172a' }}>
                                        {deleteTarget.nama_unit || deleteTarget.username}
                                    </strong>{' '}
                                    ({deleteTarget.username})?
                                </p>
                            </div>
                        </div>

                        <div
                            style={{
                                padding: '16px 24px',
                                background: '#f8fafc',
                                borderTop: '1px solid #f1f5f9',
                                display: 'flex',
                                justifyContent: 'flex-end',
                                gap: '10px',
                            }}
                        >
                            <button
                                type="button"
                                className="btn-secondary"
                                onClick={() => setDeleteTarget(null)}
                                disabled={isDeleting}
                            >
                                Batal
                            </button>
                            <button
                                type="button"
                                className="btn-danger"
                                onClick={handleDelete}
                                disabled={isDeleting}
                                style={{
                                    background: '#dc2626',
                                    borderColor: '#dc2626',
                                    color: '#ffffff',
                                    display: 'inline-flex',
                                    alignItems: 'center',
                                    gap: '6px',
                                }}
                            >
                                {isDeleting ? (
                                    <>
                                        <span
                                            className="spinner-border spinner-border-sm"
                                            style={{
                                                width: '14px',
                                                height: '14px',
                                                border: '2px solid #fff',
                                                borderRightColor: 'transparent',
                                                borderRadius: '50%',
                                                display: 'inline-block',
                                                animation: 'spin .75s linear infinite',
                                            }}
                                        ></span>
                                        Menghapus...
                                    </>
                                ) : (
                                    <>
                                        <i className="bi bi-trash"></i> Ya, Hapus
                                    </>
                                )}
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};
