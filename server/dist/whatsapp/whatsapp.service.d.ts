import { HttpService } from '@nestjs/axios';
export declare function formatPhoneNumber(phone: string): string;
export declare class WhatsappService {
    private httpService;
    private readonly logger;
    constructor(httpService: HttpService);
    send(to: string, message: string): Promise<boolean>;
    notifyAdmin(message: string): Promise<boolean>;
    getStatus(): {
        status: string;
        data: {
            provider: string;
            enabled: boolean;
            has_token: boolean;
            token_masked: string;
            api_url: string;
            admin_wa_number: string;
        };
    };
    testConnection(targetPhone?: string): Promise<{
        status: string;
        message: string;
    }>;
}
