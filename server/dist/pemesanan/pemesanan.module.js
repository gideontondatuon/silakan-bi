"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.PemesananModule = void 0;
const common_1 = require("@nestjs/common");
const pemesanan_controller_1 = require("./pemesanan.controller");
const pemesanan_service_1 = require("./pemesanan.service");
const notification_module_1 = require("../notification/notification.module");
const whatsapp_module_1 = require("../whatsapp/whatsapp.module");
let PemesananModule = class PemesananModule {
};
exports.PemesananModule = PemesananModule;
exports.PemesananModule = PemesananModule = __decorate([
    (0, common_1.Module)({
        imports: [notification_module_1.NotificationModule, whatsapp_module_1.WhatsappModule],
        controllers: [pemesanan_controller_1.PemesananController],
        providers: [pemesanan_service_1.PemesananService],
        exports: [pemesanan_service_1.PemesananService],
    })
], PemesananModule);
//# sourceMappingURL=pemesanan.module.js.map