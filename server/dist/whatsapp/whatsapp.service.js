"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var WhatsappService_1;
Object.defineProperty(exports, "__esModule", { value: true });
exports.WhatsappService = void 0;
exports.formatPhoneNumber = formatPhoneNumber;
const common_1 = require("@nestjs/common");
const axios_1 = require("@nestjs/axios");
const rxjs_1 = require("rxjs");
function formatPhoneNumber(phone) {
    if (!phone)
        return '';
    let cleaned = phone.trim().replace(/\D/g, '');
    if (cleaned.startsWith('0')) {
        cleaned = '62' + cleaned.substring(1);
    }
    else if (!cleaned.startsWith('62') && cleaned.startsWith('8')) {
        cleaned = '62' + cleaned;
    }
    return cleaned;
}
let WhatsappService = WhatsappService_1 = class WhatsappService {
    constructor(httpService) {
        this.httpService = httpService;
        this.logger = new common_1.Logger(WhatsappService_1.name);
    }
    async send(to, message) {
        const enabled = process.env.WA_GATEWAY_ENABLED === 'true';
        if (!enabled) {
            this.logger.debug('WhatsApp gateway is disabled (WA_GATEWAY_ENABLED !== true).');
            return false;
        }
        const token = process.env.WA_GATEWAY_TOKEN?.trim();
        if (!token || token === 'YOUR_FONNTE_TOKEN_HERE') {
            this.logger.warn('WhatsApp gateway is enabled, but WA_GATEWAY_TOKEN is not set in .env.');
            return false;
        }
        const formattedTarget = formatPhoneNumber(to);
        if (!formattedTarget || formattedTarget.length < 9) {
            this.logger.warn(`Invalid target phone number: "${to}" (formatted: "${formattedTarget}").`);
            return false;
        }
        const url = process.env.WA_GATEWAY_URL?.trim() || 'https://api.fonnte.com/send';
        try {
            this.logger.log(`Sending WhatsApp message to ${formattedTarget}...`);
            const response = await (0, rxjs_1.firstValueFrom)(this.httpService.post(url, {
                target: formattedTarget,
                message: message,
                countryCode: '62',
            }, {
                headers: {
                    Authorization: token,
                    'Content-Type': 'application/json',
                },
                timeout: 10000,
            }));
            const data = response.data;
            if (data?.status === true) {
                this.logger.log(`✅ WhatsApp message sent successfully to ${formattedTarget}.`);
                return true;
            }
            else {
                this.logger.warn(`❌ WhatsApp gateway returned error for ${formattedTarget}: ${data?.reason || JSON.stringify(data)}`);
                return false;
            }
        }
        catch (err) {
            const errorDetail = err?.response?.data
                ? JSON.stringify(err.response.data)
                : err?.message;
            this.logger.error(`❌ Failed to send WhatsApp to ${formattedTarget}: ${errorDetail}`);
            return false;
        }
    }
    async notifyAdmin(message) {
        const adminNumber = process.env.ADMIN_WA_NUMBER;
        if (!adminNumber) {
            this.logger.debug('ADMIN_WA_NUMBER is not set in .env, skipping admin notification.');
            return false;
        }
        return this.send(adminNumber, message);
    }
    getStatus() {
        const enabled = process.env.WA_GATEWAY_ENABLED === 'true';
        const token = process.env.WA_GATEWAY_TOKEN?.trim() || '';
        const adminNumber = process.env.ADMIN_WA_NUMBER?.trim() || '';
        const url = process.env.WA_GATEWAY_URL?.trim() || 'https://api.fonnte.com/send';
        let maskedToken = '';
        if (token) {
            if (token.length > 8) {
                maskedToken = `${token.substring(0, 4)}••••••••${token.substring(token.length - 4)}`;
            }
            else {
                maskedToken = '••••••••';
            }
        }
        return {
            status: 'success',
            data: {
                provider: 'Fonnte',
                enabled,
                has_token: !!token && token !== 'YOUR_FONNTE_TOKEN_HERE',
                token_masked: maskedToken,
                api_url: url,
                admin_wa_number: adminNumber,
            },
        };
    }
    async testConnection(targetPhone) {
        const phone = targetPhone?.trim() || process.env.ADMIN_WA_NUMBER?.trim();
        if (!phone) {
            return {
                status: 'error',
                message: 'Nomor tujuan tidak ditentukan dan ADMIN_WA_NUMBER belum diisi di .env.',
            };
        }
        const testMessage = `🔔 *TES KONEKSI WHATSAPP GATEWAY SILAKAN*\n\n✅ Layanan WhatsApp Gateway Fonnte berhasil terhubung dan siap digunakan oleh sistem SILAKAN KPwBI Sulawesi Utara.\n\nWaktu tes: ${new Date().toLocaleString('id-ID', { timeZone: 'Asia/Makassar' })} WITA.`;
        const success = await this.send(phone, testMessage);
        if (success) {
            return {
                status: 'success',
                message: `Pesan tes berhasil dikirim ke nomor ${phone}. Silakan periksa WhatsApp Anda.`,
            };
        }
        else {
            return {
                status: 'error',
                message: `Gagal mengirim pesan ke nomor ${phone}. Periksa kembali token Fonnte dan pastikan perangkat WhatsApp di dashboard Fonnte dalam status 'Connected'.`,
            };
        }
    }
};
exports.WhatsappService = WhatsappService;
exports.WhatsappService = WhatsappService = WhatsappService_1 = __decorate([
    (0, common_1.Injectable)(),
    __metadata("design:paramtypes", [axios_1.HttpService])
], WhatsappService);
//# sourceMappingURL=whatsapp.service.js.map