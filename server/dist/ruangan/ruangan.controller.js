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
exports.RuanganController = void 0;
const common_1 = require("@nestjs/common");
const ruangan_service_1 = require("./ruangan.service");
const jwt_auth_guard_1 = require("../auth/jwt-auth.guard");
const roles_guard_1 = require("../auth/roles.guard");
const roles_decorator_1 = require("../auth/roles.decorator");
let RuanganController = class RuanganController {
    constructor(ruanganService) {
        this.ruanganService = ruanganService;
    }
    index(query) {
        return this.ruanganService.findAll(query);
    }
    adminIndex(query) {
        return this.ruanganService.findAdmin(query);
    }
    adminShow(id) {
        return this.ruanganService.findOne(id);
    }
    show(id) {
        return this.ruanganService.findOne(id);
    }
    layouts(id) {
        return this.ruanganService.getLayouts(id);
    }
    layoutsById(id) {
        return this.ruanganService.getLayouts(id);
    }
    adminCreate(body) {
        return this.ruanganService.create(body);
    }
    adminUpdate(id, body) {
        return this.ruanganService.update(id, body);
    }
    adminRemove(id) {
        return this.ruanganService.remove(id);
    }
    adminLayoutList(query) {
        return this.ruanganService.findAllLayouts(query);
    }
    adminLayoutDetail(id) {
        return this.ruanganService.findLayout(id);
    }
    adminCreateLayout(body) {
        return this.ruanganService.createLayout(body);
    }
    adminUpdateLayout(id, body) {
        return this.ruanganService.updateLayout(id, body);
    }
    adminDeleteLayout(id) {
        return this.ruanganService.deleteLayout(id);
    }
};
exports.RuanganController = RuanganController;
__decorate([
    (0, common_1.Get)('ruangan'),
    __param(0, (0, common_1.Query)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Object]),
    __metadata("design:returntype", void 0)
], RuanganController.prototype, "index", null);
__decorate([
    (0, common_1.Get)('admin/ruangan'),
    (0, common_1.UseGuards)(roles_guard_1.RolesGuard),
    (0, roles_decorator_1.Roles)('admin'),
    __param(0, (0, common_1.Query)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Object]),
    __metadata("design:returntype", void 0)
], RuanganController.prototype, "adminIndex", null);
__decorate([
    (0, common_1.Get)('admin/ruangan/:id'),
    (0, common_1.UseGuards)(roles_guard_1.RolesGuard),
    (0, roles_decorator_1.Roles)('admin'),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number]),
    __metadata("design:returntype", void 0)
], RuanganController.prototype, "adminShow", null);
__decorate([
    (0, common_1.Get)('ruangan/:id'),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number]),
    __metadata("design:returntype", void 0)
], RuanganController.prototype, "show", null);
__decorate([
    (0, common_1.Get)('ruangan/:id/layouts'),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number]),
    __metadata("design:returntype", void 0)
], RuanganController.prototype, "layouts", null);
__decorate([
    (0, common_1.Get)('ruangan/:id/layouts-by-id'),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number]),
    __metadata("design:returntype", void 0)
], RuanganController.prototype, "layoutsById", null);
__decorate([
    (0, common_1.Post)('admin/ruangan'),
    (0, common_1.UseGuards)(roles_guard_1.RolesGuard),
    (0, roles_decorator_1.Roles)('admin'),
    __param(0, (0, common_1.Body)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Object]),
    __metadata("design:returntype", void 0)
], RuanganController.prototype, "adminCreate", null);
__decorate([
    (0, common_1.Put)('admin/ruangan/:id'),
    (0, common_1.UseGuards)(roles_guard_1.RolesGuard),
    (0, roles_decorator_1.Roles)('admin'),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __param(1, (0, common_1.Body)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number, Object]),
    __metadata("design:returntype", void 0)
], RuanganController.prototype, "adminUpdate", null);
__decorate([
    (0, common_1.Delete)('admin/ruangan/:id'),
    (0, common_1.UseGuards)(roles_guard_1.RolesGuard),
    (0, roles_decorator_1.Roles)('admin'),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number]),
    __metadata("design:returntype", void 0)
], RuanganController.prototype, "adminRemove", null);
__decorate([
    (0, common_1.Get)('admin/layout'),
    (0, common_1.UseGuards)(roles_guard_1.RolesGuard),
    (0, roles_decorator_1.Roles)('admin'),
    __param(0, (0, common_1.Query)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Object]),
    __metadata("design:returntype", void 0)
], RuanganController.prototype, "adminLayoutList", null);
__decorate([
    (0, common_1.Get)('admin/layout/:id'),
    (0, common_1.UseGuards)(roles_guard_1.RolesGuard),
    (0, roles_decorator_1.Roles)('admin'),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number]),
    __metadata("design:returntype", void 0)
], RuanganController.prototype, "adminLayoutDetail", null);
__decorate([
    (0, common_1.Post)('admin/layout'),
    (0, common_1.UseGuards)(roles_guard_1.RolesGuard),
    (0, roles_decorator_1.Roles)('admin'),
    __param(0, (0, common_1.Body)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Object]),
    __metadata("design:returntype", void 0)
], RuanganController.prototype, "adminCreateLayout", null);
__decorate([
    (0, common_1.Put)('admin/layout/:id'),
    (0, common_1.UseGuards)(roles_guard_1.RolesGuard),
    (0, roles_decorator_1.Roles)('admin'),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __param(1, (0, common_1.Body)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number, Object]),
    __metadata("design:returntype", void 0)
], RuanganController.prototype, "adminUpdateLayout", null);
__decorate([
    (0, common_1.Delete)('admin/layout/:id'),
    (0, common_1.UseGuards)(roles_guard_1.RolesGuard),
    (0, roles_decorator_1.Roles)('admin'),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number]),
    __metadata("design:returntype", void 0)
], RuanganController.prototype, "adminDeleteLayout", null);
exports.RuanganController = RuanganController = __decorate([
    (0, common_1.Controller)(''),
    (0, common_1.UseGuards)(jwt_auth_guard_1.JwtAuthGuard),
    __metadata("design:paramtypes", [ruangan_service_1.RuanganService])
], RuanganController);
//# sourceMappingURL=ruangan.controller.js.map