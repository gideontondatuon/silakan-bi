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
exports.HariLiburController = void 0;
const common_1 = require("@nestjs/common");
const hari_libur_service_1 = require("./hari-libur.service");
const jwt_auth_guard_1 = require("../auth/jwt-auth.guard");
const roles_guard_1 = require("../auth/roles.guard");
const roles_decorator_1 = require("../auth/roles.decorator");
let HariLiburController = class HariLiburController {
    constructor(service) {
        this.service = service;
    }
    allDates() {
        return this.service.allDates();
    }
    index(query) {
        return this.service.index(query);
    }
    sync(body) {
        const year = body.tahun ? parseInt(body.tahun) : new Date().getFullYear();
        return this.service.syncApi(year);
    }
    store(body) {
        return this.service.store(body);
    }
    remove(id) {
        return this.service.remove(id);
    }
};
exports.HariLiburController = HariLiburController;
__decorate([
    (0, common_1.Get)('hari-libur/dates'),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", []),
    __metadata("design:returntype", void 0)
], HariLiburController.prototype, "allDates", null);
__decorate([
    (0, common_1.Get)('admin/hari-libur'),
    (0, common_1.UseGuards)(roles_guard_1.RolesGuard),
    (0, roles_decorator_1.Roles)('admin'),
    __param(0, (0, common_1.Query)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Object]),
    __metadata("design:returntype", void 0)
], HariLiburController.prototype, "index", null);
__decorate([
    (0, common_1.Post)('admin/hari-libur/sync'),
    (0, common_1.UseGuards)(roles_guard_1.RolesGuard),
    (0, roles_decorator_1.Roles)('admin'),
    (0, common_1.HttpCode)(common_1.HttpStatus.OK),
    __param(0, (0, common_1.Body)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Object]),
    __metadata("design:returntype", void 0)
], HariLiburController.prototype, "sync", null);
__decorate([
    (0, common_1.Post)('admin/hari-libur'),
    (0, common_1.UseGuards)(roles_guard_1.RolesGuard),
    (0, roles_decorator_1.Roles)('admin'),
    (0, common_1.HttpCode)(common_1.HttpStatus.CREATED),
    __param(0, (0, common_1.Body)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Object]),
    __metadata("design:returntype", void 0)
], HariLiburController.prototype, "store", null);
__decorate([
    (0, common_1.Delete)('admin/hari-libur/:id'),
    (0, common_1.UseGuards)(roles_guard_1.RolesGuard),
    (0, roles_decorator_1.Roles)('admin'),
    (0, common_1.HttpCode)(common_1.HttpStatus.OK),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number]),
    __metadata("design:returntype", void 0)
], HariLiburController.prototype, "remove", null);
exports.HariLiburController = HariLiburController = __decorate([
    (0, common_1.Controller)(''),
    (0, common_1.UseGuards)(jwt_auth_guard_1.JwtAuthGuard),
    __metadata("design:paramtypes", [hari_libur_service_1.HariLiburService])
], HariLiburController);
//# sourceMappingURL=hari-libur.controller.js.map