import React, { useEffect, useState } from 'react';
import { adminService } from '../../services/adminService';
import { Department, PaginatedData, User } from '../../types';
import { LoadingSpinner } from '../../components/common/LoadingSpinner';
import { Modal } from '../../components/common/Modal';
import { AlertBanner } from '../../components/feedback/AlertBanner';

export const UserList: React.FC = () => {
    const [admins, setAdmins] = useState<User[]>([]);
    const [users, setUsers] = useState<PaginatedData<User> | null>(null);
    const [departments, setDepartments] = useState<Department[]>([]);
    const [isLoading, setIsLoading] = useState(true);
    const [searchQuery, setSearchQuery] = useState('');
    const [page, setPage] = useState(1);
    const [alertMessage, setAlertMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    // Modal Create / Edit
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [editingUser, setEditingUser] = useState<User | null>(null);
    const [formName, setFormName] = useState('');
    const [formUsername, setFormUsername] = useState('');
    const [formEmail, setFormEmail] = useState('');
    const [formNoWa, setFormNoWa] = useState('');
    const [formPassword, setFormPassword] = useState('');
    const [formRole, setFormRole] = useState<'admin' | 'user'>('user');
    const [formNamaUnit, setFormNamaUnit] = useState('');
    const [formKodeUnit, setFormKodeUnit] = useState('');
    const [formDepartmentId, setFormDepartmentId] = useState('');
    const [isSubmitting, setIsSubmitting] = useState(false);

    // Delete
    const [deleteTarget, setDeleteTarget] = useState<User | null>(null);
    const [isDeleting, setIsDeleting] = useState(false);

    const loadData = async () => {
        setIsLoading(true);
        try {
            const res = await adminService.getUserList({ q: searchQuery || undefined, page });
            if (res.status === 'success') {
                setAdmins(res.data.admins || []);
                setUsers(res.data.users);
                setDepartments(res.data.departments || []);
            }
        } catch (e) {
            // Ignore
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        loadData();
    }, [page]);

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        setPage(1);
        loadData();
    };

    const openCreate = () => {
        setEditingUser(null);
        setFormName('');
        setFormUsername('');
        setFormEmail('');
        setFormNoWa('');
        setFormPassword('');
        setFormRole('user');
        setFormNamaUnit('');
        setFormKodeUnit('');
        setFormDepartmentId('');
        setIsModalOpen(true);
    };

    const openEdit = (u: User) => {
        setEditingUser(u);
        setFormName(u.name || '');
        setFormUsername(u.username);
        setFormEmail(u.email || '');
        setFormNoWa(u.no_wa || '');
        setFormPassword('');
        setFormRole(u.role);
        setFormNamaUnit(u.nama_unit || '');
        setFormKodeUnit(u.kode_unit || '');
        setFormDepartmentId(u.department_id ? String(u.department_id) : '');
        setIsModalOpen(true);
    };

    const handleFormSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);
        try {
            const payload: any = {
                name: formName,
                username: formUsername,
                email: formEmail || undefined,
                no_wa: formNoWa || undefined,
                role: formRole,
                nama_unit: formNamaUnit,
                kode_unit: formKodeUnit,
                department_id: formDepartmentId || undefined,
            };

            if (formPassword) {
                payload.password = formPassword;
            }

            if (editingUser) {
                const res = await adminService.updateUser(editingUser.id, payload);
                setAlertMessage({ type: 'success', text: res.message || 'User berhasil diperbarui.' });
            } else {
                payload.password = formPassword; // Required for create
                const res = await adminService.createUser(payload);
                setAlertMessage({ type: 'success', text: res.message || 'User baru berhasil dibuat.' });
            }

            setIsModalOpen(false);
            loadData();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menyimpan user.' });
        } finally {
            setIsSubmitting(false);
        }
    };

    const handleDelete = async () => {
        if (!deleteTarget) return;
        setIsDeleting(true);
        try {
            const res = await adminService.deleteUser(deleteTarget.id);
            setAlertMessage({ type: 'success', text: res.message || 'User berhasil dihapus.' });
            setDeleteTarget(null);
            loadData();
        } catch (err: any) {
            setAlertMessage({ type: 'error', text: err.response?.data?.message || 'Gagal menghapus user.' });
        } finally {
            setIsDeleting(false);
        }
    };

    return (
        <div>
            {/* Header */}
            <div className="dashboard-header" style={{ marginBottom: '20px' }}>
                <div>
                    <h1>
                        <i className="bi bi-people" style={{ color: '#005baa', marginRight: '8px' }}></i>
                        Manajemen Pengguna & Unit Kerja
                    </h1>
                    <p>Kelola akun administrator, pegawai, dan perwakilan unit kerja internal KPwBI Sulut</p>
                </div>
                <div>
                    <button type="button" className="btn-primary" onClick={openCreate}>
                        <i className="bi bi-person-plus-fill"></i> Tambah Pengguna Baru
                    </button>
                </div>
            </div>

            {alertMessage && (
                <AlertBanner
                    type={alertMessage.type}
                    message={alertMessage.text}
                    onClose={() => setAlertMessage(null)}
                />
            )}

            {/* Admin Table Section */}
            <div style={{ marginBottom: '32px' }}>
                <h3 style={{ fontSize: '15px', fontWeight: 700, color: '#003b73', marginBottom: '12px' }}>
                    <i className="bi bi-shield-lock" style={{ color: '#005baa', marginRight: '6px' }}></i>
                    Daftar Akun Administrator
                </h3>
                <div style={{ background: '#fff', borderRadius: '14px', border: '1px solid #e2e8f0', overflow: 'hidden' }}>
                    <div className="table-responsive">
                        <table className="data-table">
                            <thead>
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <th>Username</th>
                                    <th>Email / WhatsApp</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {admins.map((admin) => (
                                    <tr key={admin.id}>
                                        <td><strong>{admin.name || admin.username}</strong></td>
                                        <td><span style={{ fontFamily: 'monospace', fontWeight: 600 }}>{admin.username}</span></td>
                                        <td>
                                            <div>{admin.email || '-'}</div>
                                            <small style={{ color: '#64748b' }}>{admin.no_wa || '-'}</small>
                                        </td>
                                        <td>
                                            <span style={{ padding: '3px 10px', borderRadius: '9999px', fontSize: '11px', fontWeight: 800, background: '#e0f2fe', color: '#005baa' }}>
                                                ADMINISTRATOR
                                            </span>
                                        </td>
                                        <td>
                                            <button
                                                type="button"
                                                onClick={() => openEdit(admin)}
                                                className="btn-table-action"
                                                style={{ border: 'none', background: 'none' }}
                                            >
                                                <i className="bi bi-pencil"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {/* Users Table Section */}
            <div>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '12px', flexWrap: 'wrap', gap: '10px' }}>
                    <h3 style={{ fontSize: '15px', fontWeight: 700, color: '#003b73', margin: 0 }}>
                        <i className="bi bi-person-badge" style={{ color: '#005baa', marginRight: '6px' }}></i>
                        Daftar Akun Unit Kerja / Pegawai
                    </h3>

                    <form onSubmit={handleSearch} style={{ display: 'flex', gap: '8px' }}>
                        <input
                            type="text"
                            className="login-input"
                            placeholder="Cari nama, unit, username..."
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                            style={{ height: '36px', fontSize: '12.5px', width: '220px' }}
                        />
                        <button type="submit" className="btn-primary" style={{ padding: '0 12px', height: '36px' }}>
                            <i className="bi bi-search"></i>
                        </button>
                    </form>
                </div>

                <div style={{ background: '#fff', borderRadius: '16px', border: '1px solid #e2e8f0', overflow: 'hidden' }}>
                    {isLoading ? (
                        <LoadingSpinner message="Memuat daftar pengguna..." />
                    ) : (
                        <div className="table-responsive">
                            <table className="data-table">
                                <thead>
                                    <tr>
                                        <th>Nama Unit / Tim Kerja</th>
                                        <th>Username</th>
                                        <th>Nama Pegawai (PIC)</th>
                                        <th>Kontak (WA)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {users && users.data.length > 0 ? (
                                        users.data.map((u) => (
                                            <tr key={u.id}>
                                                <td>
                                                    <strong>{u.nama_unit || '-'}</strong>
                                                    <div style={{ fontSize: '11.5px', color: '#64748b' }}>Kode: {u.kode_unit || '-'}</div>
                                                </td>
                                                <td><span style={{ fontFamily: 'monospace', fontWeight: 600 }}>{u.username}</span></td>
                                                <td>{u.name || '-'}</td>
                                                <td>{u.no_wa || u.email || '-'}</td>
                                                <td>
                                                    <div style={{ display: 'flex', gap: '6px' }}>
                                                        <button
                                                            type="button"
                                                            onClick={() => openEdit(u)}
                                                            className="btn-table-action"
                                                            style={{ border: 'none', background: 'none' }}
                                                        >
                                                            <i className="bi bi-pencil"></i> Edit
                                                        </button>
                                                        <button
                                                            type="button"
                                                            onClick={() => setDeleteTarget(u)}
                                                            style={{ background: '#fef2f2', color: '#dc2626', border: '1px solid #fecdd3', borderRadius: '6px', padding: '4px 8px', fontSize: '12px', cursor: 'pointer' }}
                                                        >
                                                            <i className="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan={5} style={{ textAlign: 'center', padding: '32px', color: '#64748b' }}>
                                                Tidak ada pengguna yang sesuai dengan pencarian.
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>
            </div>

            {/* Modal Create / Edit */}
            <Modal
                isOpen={isModalOpen}
                onClose={() => setIsModalOpen(false)}
                title={editingUser ? `Edit Pengguna: ${editingUser.username}` : 'Tambah Pengguna Baru'}
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setIsModalOpen(false)} disabled={isSubmitting}>
                            Batal
                        </button>
                        <button type="submit" form="userForm" className="btn-primary" disabled={isSubmitting}>
                            {isSubmitting ? 'Menyimpan...' : 'Simpan User'}
                        </button>
                    </>
                }
            >
                <form id="userForm" onSubmit={handleFormSubmit}>
                    <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px', marginBottom: '14px' }}>
                        <div className="form-group">
                            <label className="required" style={{ display: 'block', marginBottom: '6px', fontSize: '13px', fontWeight: 600 }}>
                                Username Akun
                            </label>
                            <input
                                type="text"
                                className="login-input"
                                value={formUsername}
                                onChange={(e) => setFormUsername(e.target.value)}
                                placeholder="Contoh: kehumasan"
                                required
                            />
                        </div>

                        <div className="form-group">
                            <label className="required" style={{ display: 'block', marginBottom: '6px', fontSize: '13px', fontWeight: 600 }}>
                                Role Akun
                            </label>
                            <select
                                className="login-input"
                                value={formRole}
                                onChange={(e) => setFormRole(e.target.value as any)}
                                required
                            >
                                <option value="user">User (Unit Kerja)</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                    </div>

                    <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px', marginBottom: '14px' }}>
                        <div className="form-group">
                            <label className="required" style={{ display: 'block', marginBottom: '6px', fontSize: '13px', fontWeight: 600 }}>
                                Nama Unit Kerja
                            </label>
                            <input
                                type="text"
                                className="login-input"
                                value={formNamaUnit}
                                onChange={(e) => setFormNamaUnit(e.target.value)}
                                placeholder="Contoh: Tim Kehumasan"
                                required
                            />
                        </div>

                        <div className="form-group">
                            <label className="required" style={{ display: 'block', marginBottom: '6px', fontSize: '13px', fontWeight: 600 }}>
                                Kode Singkatan Unit
                            </label>
                            <input
                                type="text"
                                className="login-input"
                                value={formKodeUnit}
                                onChange={(e) => setFormKodeUnit(e.target.value)}
                                placeholder="Contoh: HUMAS"
                                required
                            />
                        </div>
                    </div>

                    <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px', marginBottom: '14px' }}>
                        <div className="form-group">
                            <label style={{ display: 'block', marginBottom: '6px', fontSize: '13px', fontWeight: 600 }}>
                                Nama Lengkap PIC
                            </label>
                            <input
                                type="text"
                                className="login-input"
                                value={formName}
                                onChange={(e) => setFormName(e.target.value)}
                                placeholder="Nama pegawai"
                            />
                        </div>

                        <div className="form-group">
                            <label style={{ display: 'block', marginBottom: '6px', fontSize: '13px', fontWeight: 600 }}>
                                Nomor WhatsApp
                            </label>
                            <input
                                type="text"
                                className="login-input"
                                value={formNoWa}
                                onChange={(e) => setFormNoWa(e.target.value)}
                                placeholder="08xxxxxxxxxx"
                            />
                        </div>
                    </div>

                    <div className="form-group" style={{ marginBottom: '14px' }}>
                        <label style={{ display: 'block', marginBottom: '6px', fontSize: '13px', fontWeight: 600 }}>
                            {editingUser ? 'Password Baru (Kosongkan jika tidak diubah)' : 'Password Akun (Min. 8 Karakter)'}
                        </label>
                        <input
                            type="password"
                            className="login-input"
                            value={formPassword}
                            onChange={(e) => setFormPassword(e.target.value)}
                            placeholder={editingUser ? 'Biarkan kosong untuk mempertahankan password' : 'Masukkan password'}
                            required={!editingUser}
                        />
                    </div>
                </form>
            </Modal>

            {/* Modal Delete */}
            <Modal
                isOpen={!!deleteTarget}
                onClose={() => setDeleteTarget(null)}
                title="Hapus Akun Pengguna"
                footer={
                    <>
                        <button type="button" className="btn-secondary" onClick={() => setDeleteTarget(null)} disabled={isDeleting}>
                            Batal
                        </button>
                        <button type="button" className="btn-primary" style={{ background: '#dc2626', borderColor: '#dc2626' }} onClick={handleDelete} disabled={isDeleting}>
                            {isDeleting ? 'Menghapus...' : 'Ya, Hapus'}
                        </button>
                    </>
                }
            >
                <p style={{ fontSize: '14px', color: '#334155' }}>
                    Apakah Anda yakin ingin menghapus user <strong>{deleteTarget?.username}</strong> ({deleteTarget?.nama_unit})? User yang memiliki riwayat pemesanan tidak dapat dihapus.
                </p>
            </Modal>
        </div>
    );
};
