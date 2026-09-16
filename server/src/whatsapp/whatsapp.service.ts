import { Injectable, Logger } from '@nestjs/common';
import { HttpService } from '@nestjs/axios';
import { firstValueFrom } from 'rxjs';

/**
 * Format phone number to standard international format (628...)
 * Handles various inputs: 08..., +628..., 628..., 0812-3456-7890, etc.
 */
export function formatPhoneNumber(phone: string): string {
  if (!phone) return '';
  // Remove all non-digit characters
  let cleaned = phone.trim().replace(/\D/g, '');

  // If starts with '08', change '0' to '62'
  if (cleaned.startsWith('0')) {
    cleaned = '62' + cleaned.substring(1);
  } else if (!cleaned.startsWith('62') && cleaned.startsWith('8')) {
    cleaned = '62' + cleaned;
  }

  return cleaned;
}

@Injectable()
export class WhatsappService {
  private readonly logger = new Logger(WhatsappService.name);

  constructor(private httpService: HttpService) {}

  /**
   * Send WhatsApp message via Fonnte gateway
   * @param to Target phone number (e.g. 08123456789 or 628123456789)
   * @param message Message content to send
   */
  async send(to: string, message: string): Promise<boolean> {
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

      const response = await firstValueFrom(
        this.httpService.post(
          url,
          {
            target: formattedTarget,
            message: message,
            countryCode: '62',
          },
          {
            headers: {
              Authorization: token,
              'Content-Type': 'application/json',
            },
            timeout: 10000,
          },
        ),
      );

      const data = response.data;
      if (data?.status === true) {
        this.logger.log(`✅ WhatsApp message sent successfully to ${formattedTarget}.`);
        return true;
      } else {
        this.logger.warn(
          `❌ WhatsApp gateway returned error for ${formattedTarget}: ${data?.reason || JSON.stringify(data)}`,
        );
        return false;
      }
    } catch (err: any) {
      const errorDetail = err?.response?.data
        ? JSON.stringify(err.response.data)
        : err?.message;
      this.logger.error(`❌ Failed to send WhatsApp to ${formattedTarget}: ${errorDetail}`);
      return false;
    }
  }

  /**
   * Send notification message to Admin WhatsApp number
   */
  async notifyAdmin(message: string): Promise<boolean> {
    const adminNumber = process.env.ADMIN_WA_NUMBER;
    if (!adminNumber) {
      this.logger.debug('ADMIN_WA_NUMBER is not set in .env, skipping admin notification.');
      return false;
    }
    return this.send(adminNumber, message);
  }

  /**
   * Get current WhatsApp gateway status and configuration info (masked token for security)
   */
  getStatus() {
    const enabled = process.env.WA_GATEWAY_ENABLED === 'true';
    const token = process.env.WA_GATEWAY_TOKEN?.trim() || '';
    const adminNumber = process.env.ADMIN_WA_NUMBER?.trim() || '';
    const url = process.env.WA_GATEWAY_URL?.trim() || 'https://api.fonnte.com/send';

    let maskedToken = '';
    if (token) {
      if (token.length > 8) {
        maskedToken = `${token.substring(0, 4)}••••••••${token.substring(token.length - 4)}`;
      } else {
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

  /**
   * Test WhatsApp gateway by sending a verification message
   */
  async testConnection(targetPhone?: string) {
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
    } else {
      return {
        status: 'error',
        message: `Gagal mengirim pesan ke nomor ${phone}. Periksa kembali token Fonnte dan pastikan perangkat WhatsApp di dashboard Fonnte dalam status 'Connected'.`,
      };
    }
  }
}
