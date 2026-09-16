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
var __param = (this && this.__param) || function (paramIndex, decorator) {
    return function (target, key) { decorator(target, key, paramIndex); }
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.AdminController = void 0;
const common_1 = require("@nestjs/common");
const platform_express_1 = require("@nestjs/platform-express");
const pemesanan_controller_1 = require("../pemesanan/pemesanan.controller");
const admin_service_1 = require("./admin.service");
const pemesanan_service_1 = require("../pemesanan/pemesanan.service");
const jwt_auth_guard_1 = require("../auth/jwt-auth.guard");
const roles_guard_1 = require("../auth/roles.guard");
const roles_decorator_1 = require("../auth/roles.decorator");
let AdminController = class AdminController {
    constructor(adminService, pemesananService) {
        this.adminService = adminService;
        this.pemesananService = pemesananService;
    }
    approvalIndex(q) { return this.adminService.approvalIndex(q); }
    approvalStore(body, req, file) {
        const filePath = file ? `disposisi/${file.filename}` : undefined;
        return this.adminService.adminStorePemesanan(body, req.user, filePath);
    }
    approvalShow(id) { return this.adminService.approvalShow(id); }
    approve(id, body, req) { return this.adminService.approve(id, req.user, body.catatan_admin); }
    reject(id, body, req) { return this.adminService.reject(id, req.user, body.alasan_penolakan); }
    approvalSelesaiAwal(id, req) {
        return this.pemesananService.selesaiAwal(id, req.user);
    }
    approvalDestroy(id) { return this.adminService.approvalDestroy(id); }
    userIndex(q) { return this.adminService.userIndex(q); }
    userShow(id) { return this.adminService.userShow(id); }
    userStore(body) { return this.adminService.userStore(body); }
    userUpdate(id, body) { return this.adminService.userUpdate(id, body); }
    userDestroy(id) { return this.adminService.userDestroy(id); }
    getUnits() { return this.adminService.getUnits(); }
    getWhatsappStatus() { return this.adminService.getWhatsappStatus(); }
    testWhatsapp(body) { return this.adminService.testWhatsapp(body?.phone); }
};
exports.AdminController = AdminController;
__decorate([
    (0, common_1.Get)('admin/approval'),
    __param(0, (0, common_1.Query)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Object]),
    __metadata("design:returntype", void 0)
], AdminController.prototype, "approvalIndex", null);
__decorate([
    (0, common_1.Post)('admin/approval'),
    (0, common_1.HttpCode)(common_1.HttpStatus.CREATED),
    (0, common_1.UseInterceptors)((0, platform_express_1.FileInterceptor)('file_disposisi', { storage: pemesanan_controller_1.disposisiStorage })),
    __param(0, (0, common_1.Body)()),
    __param(1, (0, common_1.Req)()),
    __param(2, (0, common_1.UploadedFile)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Object, Object, Object]),
    __metadata("design:returntype", void 0)
], AdminController.prototype, "approvalStore", null);
__decorate([
    (0, common_1.Get)('admin/approval/:id'),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number]),
    __metadata("design:returntype", void 0)
], AdminController.prototype, "approvalShow", null);
__decorate([
    (0, common_1.Post)('admin/approval/:id/approve'),
    (0, common_1.HttpCode)(common_1.HttpStatus.OK),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __param(1, (0, common_1.Body)()),
    __param(2, (0, common_1.Req)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number, Object, Object]),
    __metadata("design:returntype", void 0)
], AdminController.prototype, "approve", null);
__decorate([
    (0, common_1.Post)('admin/approval/:id/reject'),
    (0, common_1.HttpCode)(common_1.HttpStatus.OK),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __param(1, (0, common_1.Body)()),
    __param(2, (0, common_1.Req)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number, Object, Object]),
    __metadata("design:returntype", void 0)
], AdminController.prototype, "reject", null);
__decorate([
    (0, common_1.Post)('admin/approval/:id/selesai-awal'),
    (0, common_1.HttpCode)(common_1.HttpStatus.OK),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __param(1, (0, common_1.Req)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number, Object]),
    __metadata("design:returntype", void 0)
], AdminController.prototype, "approvalSelesaiAwal", null);
__decorate([
    (0, common_1.Delete)('admin/approval/:id'),
    (0, common_1.HttpCode)(common_1.HttpStatus.OK),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number]),
    __metadata("design:returntype", void 0)
], AdminController.prototype, "approvalDestroy", null);
__decorate([
    (0, common_1.Get)('admin/users'),
    __param(0, (0, common_1.Query)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Object]),
    __metadata("design:returntype", void 0)
], AdminController.prototype, "userIndex", null);
__decorate([
    (0, common_1.Get)('admin/users/:id'),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number]),
    __metadata("design:returntype", void 0)
], AdminController.prototype, "userShow", null);
__decorate([
    (0, common_1.Post)('admin/users'),
    (0, common_1.HttpCode)(common_1.HttpStatus.CREATED),
    __param(0, (0, common_1.Body)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Object]),
    __metadata("design:returntype", void 0)
], AdminController.prototype, "userStore", null);
__decorate([
    (0, common_1.Put)('admin/users/:id'),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __param(1, (0, common_1.Body)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number, Object]),
    __metadata("design:returntype", void 0)
], AdminController.prototype, "userUpdate", null);
__decorate([
    (0, common_1.Delete)('admin/users/:id'),
    (0, common_1.HttpCode)(common_1.HttpStatus.OK),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number]),
    __metadata("design:returntype", void 0)
], AdminController.prototype, "userDestroy", null);
__decorate([
    (0, common_1.Get)('admin/units'),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", []),
    __metadata("design:returntype", void 0)
], AdminController.prototype, "getUnits", null);
__decorate([
    (0, common_1.Get)('admin/whatsapp/status'),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", []),
    __metadata("design:returntype", void 0)
], AdminController.prototype, "getWhatsappStatus", null);
__decorate([
    (0, common_1.Post)('admin/whatsapp/test'),
    (0, common_1.HttpCode)(common_1.HttpStatus.OK),
    __param(0, (0, common_1.Body)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Object]),
    __metadata("design:returntype", void 0)
], AdminController.prototype, "testWhatsapp", null);
exports.AdminController = AdminController = __decorate([
    (0, common_1.Controller)(''),
    (0, common_1.UseGuards)(jwt_auth_guard_1.JwtAuthGuard, roles_guard_1.RolesGuard),
    (0, roles_decorator_1.Roles)('admin'),
    __metadata("design:paramtypes", [admin_service_1.AdminService,
        pemesanan_service_1.PemesananService])
], AdminController);
//# sourceMappingURL=admin.controller.js.map